<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingService;
use Carbon\Carbon;
use App\Models\BotsStgy;
use App\Models\MyBot;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Symbolprice;
use App\Models\SysSignal;
use App\Models\SysSignalLog;
use App\Models\SysLog;

class DaemonPrice extends Command
{
    // 命令名稱
    protected $signature = 'daemon:DaemonPrice';

    // 說明文字
    protected $description = '接收Binance現貨、FTX交易訊號，每10秒更新一次';

    public function __construct(SettingService $settingService)
    {
        parent::__construct();
        $this->settingService = $settingService;
    }

    // Console 執行的程式
    public function handle()
    {

        //無限執行取得交易所訊號
        while(1){

            //Binance
            //$this->binanceGetStockPrice();

            //FTX
            //$this->ftxGetStockPrice();

            //執行機器人
            //撈取所有可以執行的交易機器人 狀態為0 正常，至少api key1 有設定值
            $myBots = MyBot::where('status', 0)->where('apiKeyId1', "!=" , "")->get();

            //遍歷每一個機器人，去呼叫對應該執行的策略
            foreach( $myBots as $myBot ){

                //此處必須設定對應策略執行Function，否則會無法執行
                if( $myBot->usedStgy == "1" ){
                    echo "\n\n" . "#" . $myBot->id . "機器人執行" . "JRB_DCA_SL_STRATEGY 策略" . "\n\n";
                    $botsStgy = BotsStgy::find(1);
                    $this->JRB_DCA_SL_STRATEGY($myBot , $botsStgy);
                }

            }
            
            echo "機器人遍歷批次執行完畢，執行時間每10秒更新\n";
            
            //暫停時間
            sleep(10);

        }


    }

    public function http_request($url, $headers, $data = array())
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // dd($headers);
        if ($data) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            //$content = false;
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        return $content;
    }

    private function binanceGetStockPrice(){

        //呼叫Binance API
        $base = "https://api.binance.com/";
        $headers = array(
            'Content-Type: application/json',
        );

        //必填參數設定
        $params['recvWindow'] = "5000";
        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');

        $endpoint = $base . "api/v3/ticker/price";
        $res = json_decode($this->http_request($endpoint, $headers));

        for( $i = 0 ; $i < count($res) ; $i++ ){

            //如果尾巴是USDT才處理
            //$isUSDT = substr($result[$i]->symbol, -4) == "USDT" ? true : false;

            $sp = Symbolprice::where('exchange' , "binance" )->where('symbol' , $res[$i]->symbol )-> first();

            if( is_null($sp) ){
                $sp = new Symbolprice();

            }

            $sp->exchange = "binance";
            $sp->symbol =$res[$i]->symbol;
            $sp->price = $res[$i]->price;

            $sp->save();

        }
        
        //echo "Binance 現貨交易訊號更新完畢，執行時間每10秒更新\n";

    }

    private function ftxGetStockPrice(){

        //呼叫Binance API
        $base = "https://ftx.com/";
        $headers = array(
            'Content-Type: application/json',
        );

        //必填參數設定
        $params['recvWindow'] = "5000";
        $params['timestamp'] = number_format(microtime(true) * 1000, 0, '.', '');

        $endpoint = $base . "api/markets";
        $res = json_decode($this->http_request($endpoint, $headers));

        for( $i = 0 ; $i < count($res->result) ; $i++ ){

            $sp = Symbolprice::where('exchange' , "ftx" )->where('symbol' , $res->result[$i]->name )->first();

            if( is_null($sp) ){
                $sp = new Symbolprice();
            }
            
            $sp->exchange = "ftx";
            $sp->symbol =$res->result[$i]->name;
            $sp->price = $res->result[$i]->price;

            $sp->save();

        }
        
        //echo "FTX 現貨交易訊號更新完畢，執行時間每10秒更新\n";

    }

    ////////////////////////////////////////////////////////////////////////////////////////.
    //JRB攤平止損趨勢策略
    public function JRB_DCA_SL_STRATEGY( $myBot , $botsStgy )
    {
        //定義可操作幣種
        $allowSymbolLists = "BTCUSDT;ETHUSDT;SOLUSDT;LUNAUSDT;FTMUSDT;AVAXUSDT;BNBUSDT;FTTUSDT;C98USDT;MATICUSDT;DOTUSDT;BITUSDT;C98USDT";
        $maxDCAfreq = $botsStgy->maxDCAqty;//最大攤平次數，每次攤平使用多一倍成本，故單幣種投資金額會提高很大，超過則顯示該幣種警告
        $fixedBuyQty = 30;
        $aSLists = explode(";", $allowSymbolLists);

        //機器人狀態儲存

        //儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
        $stat_holdCoins = unserialize($myBot->field_1);

        //儲存目前 0追蹤中買入幣種 1最低價格 2反彈價格 3時框
        $stat_tracebuys = unserialize($myBot->field_2);

        //儲存目前 0追蹤中賣出幣種 1最高價格 2反彈價格 3時框
        $stat_tracesells = unserialize($myBot->field_3);

        //最後訊號處理的編號，避免處理到已收到的訊號，一種策略只能追隨一種最後處理訊號狀態
        $stat_signalNum = $myBot->field_4;

        //檢查如果解析失敗紀錄並初始化
        if( $stat_holdCoins == false & !is_array($stat_holdCoins) ){
            $stat_holdCoins = array();
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_holdCoins解析失敗紀錄並初始化" );
            echo "  異常-stat_holdCoins解析失敗紀錄並初始化\n";
        }

        if( $stat_tracebuys == false & !is_array($stat_tracebuys) ){
            $stat_tracebuys = array();
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_tracebuys解析失敗紀錄並初始化" );
            echo "  異常-stat_tracebuys解析失敗紀錄並初始化\n";
        }

        if( $stat_tracesells == false & !is_array($stat_tracesells) ){
            $stat_tracesells = array();
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_tracesells解析失敗紀錄並初始化" );
            echo "  異常-stat_tracesells解析失敗紀錄並初始化\n";
        }

        if( !is_numeric($stat_signalNum) ){
            //先設定為 0，後面執行策略後會直接變成目前處理的訊號
            $stat_tracesells = 0;
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_signalNum解析失敗紀錄並初始化" );
            echo "  異常-stat_signalNum解析失敗紀錄並初始化\n";
        }

        //機器人只透過接收可使用的訊號來更新機器人狀態

        //1.檢查是否有新的訊號，撈取標準是目前時間往前推5分鐘，超過時間的訊號不處理

        //可以使用的訊號
        $avbleSgnls = SysSignal::select('id')->where('onlyStgyIds', '')->orWhere('onlyStgyIds', 'LIKE', '%1;%')->get();
        
        $sysSignalLogs = SysSignalLog::where('kType' , 'crypto')
                                    ->where('exchange' , 'binance')
                                    ->where('id', '>' , $stat_signalNum )
                                    ->whereIn('sigId' , $avbleSgnls)
                                    ->where('created_at', '>=', Carbon::now()->subMinutes(5)
                                    ->toDateTimeString())->get();

        if( !is_null($sysSignalLogs) ){

            echo "---接收到可操作訊號清單-------------------------\n";
            echo $sysSignalLogs . "\n";
            echo "-----------------------------------------\n";

        }
        
        //2.從已經取得的訊號判斷，如果最近沒有訊號就持續等待下一次的買入訊號或是下跌追蹤買入訊號出現
        foreach( $sysSignalLogs as $sysSignalLog ){

            //如果訊號是有在可操作清單內
            if( in_array($sysSignalLog->token , $aSLists) ){

                echo "進入策略可操作幣種的訊號\n";

                //檢查是否以持有該幣種，並且看是買入還是賣出訊號
                //1.未持有且買入訊號，則買入
                //2.未持有且賣出訊號，則追蹤買入
                //3.持有且買入訊號，則追蹤賣出
                //4.持有且賣出訊號，判斷價格是否已超過固定獲利1%或以上則賣出，沒有則追蹤買入(DCA)
                
                //訊號是否已被處理過，處理過則忽略其他判斷
                $isProcess = false;

                //1.未持有且買入訊號，則買入
                $isHold = false;

                //if( array_search( $sysSignalLog->token , array_column($stat_holdCoins, 0)) ){
                    //如果有在已持有清單
                   // $isHold = true;
                //}

                foreach($stat_holdCoins as $stat_holdCoin){
                    if($stat_holdCoin[0] == $sysSignalLog->token && $stat_holdCoin[5] == $sysSignalLog->timeframe ){
                        $isHold = true;
                        break;
                    }
                }

                if( $isHold == false && $sysSignalLog->direction == "buy" ){

                    echo "  執行策略-1.未持有且買入訊號，則買入\n";
                    //呼叫買入訊號
                    $this->binanceBuySellLog($myBot , "buy" , round( $fixedBuyQty / $sysSignalLog->price , 6 )  , $sysSignalLog->token , $sysSignalLog->timeFrame , true);//呼叫實際買入或賣出
                    //更新持有清單 儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
                    $coinHold = array( $sysSignalLog->token  , $sysSignalLog->price , round($fixedBuyQty / $sysSignalLog->price, 6) , 'buy' , 0 , $sysSignalLog->timeFrame );
                    array_push($stat_holdCoins, $coinHold);

                    $isProcess = true;
                    $stat_signalNum = $sysSignalLog->id;
                    
                    break;//直接跳出外面迴圈
                }

                //2.未持有且賣出訊號，則追蹤買入
                if( $isHold == false && $sysSignalLog->direction == "sell" ){

                    echo "  執行策略-2.未持有且賣出訊號，則追蹤買入\n";

                    //把幣種移入追蹤買入，回彈價格2%
                    $coin=array( $sysSignalLog->token , $sysSignalLog->price , $sysSignalLog->price * 1.02 , $sysSignalLog->timeFrame  );
                    array_push($stat_tracebuys , $coin);
                    $isProcess = true;
                    $stat_signalNum = $sysSignalLog->id;

                    break;//直接跳出外面迴圈
                }

                //3.持有且買入訊號，則追蹤賣出
                if( $isHold == true && $sysSignalLog->direction == "buy" ){

                    echo "  執行策略-3.持有且買入訊號，則追蹤賣出\n";
                    
                    //檢查是否是持有且在追中買入的情形，如是則移出
                    foreach($stat_tracebuys as $stat_tracebuy){
                        if($stat_tracebuy[0] == $sysSignalLog->token && $stat_holdCoin[3] == $sysSignalLog->timeFrame ){//如果有在已持有清單，且在追中買入模式
                            
                            //移除追中買入模式，改為賣出
                            unset($stat_tracebuy);

                            break;
                        }
                    }

                    //把幣種移入追蹤賣出，回彈價格2%
                    $coin=array( $sysSignalLog->token , $sysSignalLog->price , $sysSignalLog->price * 1.02 , $sysSignalLog->timeFrame );
                    array_push($stat_tracesells , $coin);
                    $isProcess = true;
                    $stat_signalNum = $sysSignalLog->id;
                    break;//直接跳出外面迴圈

                }

                //4.持有且賣出訊號，判斷價格是否已超過固定獲利1.5%或以上則賣出，沒有則追蹤買入(DCA)
                if( $isHold == true && $sysSignalLog->direction == "sell" ){

                    echo "  執行策略-4.持有且賣出訊號，判斷價格是否已超過固定獲利1%或以上則賣出，沒有則追蹤買入(DCA)\n";

                    //檢查是否是持有且在追中賣出的情形，如是則移出
                    foreach($stat_tracesells as $stat_tracesell){
                        if($stat_tracesell[0] == $sysSignalLog->token && $stat_tracesell[3] == $sysSignalLog->timeFrame ){//如果有在已持有清單，且在追中買入模式
                            
                            //移除追中買入模式，改為賣出
                            unset($stat_tracesell);

                            if( count($stat_tracesells) == 1){
                                $stat_tracesells = array();
                            }

                            break;
                        }
                    }
                    
                    //如果價格已超過固定獲利1%或以上則賣出
                    $findToken = false;
                    //找到持有該幣種的狀態，從裡面找出該幣種的平均買入成本
                    foreach($stat_holdCoins as $stat_holdCoin){
                        if($stat_holdCoin[0] == $sysSignalLog->token && $stat_holdCoin[5] == $sysSignalLog->timeFrame ){//如果有在已持有清單
                            $findToken = true;
                            $avagPrice = $stat_holdCoin[1];//撈出平均成本
                            $holdQty = $stat_holdCoin[2];//撈出持有數量
                            break;
                        }else{

                            $this->sysLog($myBot , "myBot" , "ERR" , "異常-持有幣種紀錄中無該幣種" );
                            echo "  異常-持有幣種紀錄中無該幣種\n";
                            $avagPrice = 0;
                            $holdQty = 0;
                        }
                    }

                    //有找到持有該幣種並撈到平均成本
                    if($findToken && $avagPrice > 0 && $holdQty > 0){

                        //如果價格已超過固定獲利1%或以上則賣出
                        $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $sysSignalLog->token)->first();
                        echo "  目前現貨價格:" . $symbolPrice->price ."，機器人平均成本價格:" . $avagPrice . "\n";

                        if( $symbolPrice->price > $avagPrice * 1.01 ){

                            $this->binanceBuySellLog($myBot , "sell" , $holdQty , $sysSignalLog->token , $symbolPrice->timeFrame , false);//呼叫實際買入或賣出
                            echo "  已達獲利價格，呼叫執行賣出\n";

                            //已持有，找到該幣種並更新資料
                            foreach($stat_holdCoins as $stat_holdCoin){
                                if($stat_holdCoin[0] == $sysSignalLog->token && $stat_holdCoin[3] == $sysSignalLog->timeFrame ){
                                    //找到該幣種，並移除持有
                                    unset($stat_holdCoin);

                                    if( count($stat_holdCoins) == 1){
                                        $stat_holdCoins = array();
                                    }
                                    
                                }
                            }

                        }else{

                            //把幣種移入追蹤買入，反彈買入價格為5%
                            echo "  未達獲利價格1%，進入追蹤買入攤平模式\n";
                            $coin=array( $sysSignalLog->token , $symbolPrice->price , $symbolPrice->price * 1.05 , $sysSignalLog->timeFrame );
                            array_push($stat_tracebuys , $coin);

                            //持有幣種增加註記，進入DCA
                            foreach($stat_holdCoins as $stat_holdCoin){
                                if($stat_holdCoin[0] == $sysSignalLog->token && $stat_holdCoin[3] == $sysSignalLog->timeFrame ){//如果有在已持有清單
                                    $stat_holdCoin[4] += 1;//註記被加入了攤平計畫
                                    break;
                                }
                            }
                        }

                    }else{

                        $this->sysLog($myBot , "myBot" , "ERR" , "異常-持有幣種紀錄中無該幣種、撈取平均成本、持有數量失敗" );
                        echo "  異常-持有幣種紀錄中無該幣種、撈取平均成本、持有數量失敗\n";

                    }

                    $stat_signalNum = $sysSignalLog->id;
                    $isProcess = true;
                    break;//直接跳出外面迴圈

                }

            }

        }

        //已處理完所有新訊號，寫入資料庫
        //更新持有清單 儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
        $myBot->field_1 = serialize($stat_holdCoins);
        //儲存目前 0追蹤中買入幣種 1最低價格 2反彈價格 3時框
        $myBot->field_2 = serialize($stat_tracebuys);
        //儲存目前 0追蹤中賣出幣種 1最高價格 2反彈價格 3時框
        $myBot->field_3 = serialize($stat_tracesells);
        $myBot->field_4 = $stat_signalNum;
        $myBot->save();


        //如果追蹤買入幣種不是空的，才執行Trace buy
        if( !is_null($stat_tracebuys) && count($stat_tracebuys) > 0 ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_BUY($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells ,  $maxDCAfreq , $fixedBuyQty );
        }

        //如果追蹤買賣出幣種不是空的，才執行Trace sell
        if( !is_null($stat_tracesells) && count($stat_tracebuys) > 0 ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_SELL($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells );
        }

        return true;
    }

    //如果追蹤買入幣種不是空的，才執行Trace buy
    public function JRB_DCA_SL_STRATEGY_TRACE_BUY($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells , $maxDCAfreq , $fixedBuyQty ){


        //對所有追蹤買入幣種作處理
        foreach($stat_tracebuys as &$stat_tracebuy){

            //檢查該幣種是否已達最大買入次數，已到達時就直接返回不再追價
            foreach( $stat_holdCoins as &$stat_holdCoin){

                if( $stat_holdCoin  ==  $stat_tracebuy[0] ){

                    if( $stat_holdCoin[4] >  $maxDCAfreq ){

                        //寫入最大買入訊息警告
                        echo "  幣種" . $stat_tracebuy[0] . "進入DCA已達最大買入次數，請確認\n";
                        $myBot->retMsg = "幣種 " . $stat_tracebuy[0] . " 進入DCA已達最大買入次數，請確認";
                        $myBot->save();
                        
                        $this->sysLog($myBot , "myBot" , "DCA" , "幣種 " . $stat_tracebuy[0] . " 進入DCA已達最大買入次數，請確認" );

                        //移除追蹤該幣種
                        unset($stat_holdCoin);

                        if( count($stat_holdCoins) == 1){
                            $stat_holdCoins = array();
                        }
                        
                        break;//已到達最大買入次數就直接返回不再追價

                    }

                }

            }
            

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $stat_tracebuy[0])->first();

            echo "  執行-追蹤買入幣種：" . $stat_tracebuy[0] . "，幣安價格：" . $symbolPrice->price . "，追蹤價格：" . $stat_tracebuy[1] . "，反彈買入價：" . $stat_tracebuy[2] ."\n";

            //如果現在比追蹤價格還低，更新最終價格，並重新計算反彈價格
            if( $symbolPrice->price < $stat_tracebuy[1] ){
                echo "  執行-更新追蹤買入最低價：" . $symbolPrice->price . "<" . $stat_tracebuy[1] . "反彈買入價：" . $stat_tracebuy[2] . "\n";
                $stat_tracebuy[1] = $symbolPrice->price;
                $stat_tracebuy[2] = $symbolPrice->price * 1.02;

            }

            //如果現在比追蹤價格還高，判斷是否已經超過反彈價格，超過則發動買入
            if( $symbolPrice->price > $stat_tracebuy[2] ){

                echo "  執行-現在比追蹤價格還高，判斷是否已經超過反彈價格，超過則發動買入：" . $symbolPrice->price . ">" . $stat_tracebuy[2] . "\n";
                //檢查目前是否已持有，已持有則更新，未持有則加入該幣種
                $isHold = false;
                foreach($stat_holdCoins as $stat_holdCoin){
                    if($stat_holdCoin[0] == $stat_tracebuy[0] ){//如果有在已持有清單
                        $isHold = true;
                        break;
                    }
                }

                if( $isHold ){

                    //已持有，找到該幣種並更新資料
                    foreach($stat_holdCoins as &$stat_holdCoin){
                        if($stat_holdCoin[0] == $stat_tracebuy[0] ){
                            //找到該幣種
                            $stat_holdCoin[1] = ($stat_holdCoin[1] + $symbolPrice->price) / 2;
                            $stat_holdCoin[2] += $stat_holdCoin[2];//買入目前持有同樣的數量
                            $stat_holdCoin[3] = 'buy';
                            $stat_holdCoin[4] += 1;//DCA
                            $needBuyQty = $stat_holdCoin[2];
                        }
                    }


                }else{

                    $coinHold = array( $stat_tracebuy[0]  , $symbolPrice->price , round( $fixedBuyQty / $symbolPrice->price , 6 ) , 'buy' , 0 , $stat_holdCoin[5] );
                    array_push($stat_holdCoins, $coinHold);
                }

                //判斷此次DCA是否為第一次買入
                $isFirstBuy = false;
                $order = Order::where('myBotId' , $myBot->id )
                            ->where('symbol' , $stat_tracebuy[0] )
                            ->where('isTrade' , 0)
                            ->first();
                if( is_null($order) ){
                    $isFirstBuy = true;
                }

                $isBuy = true;
                $this->binanceBuySellLog( $myBot , "buy" , $needBuyQty , $stat_tracebuy[0] , $stat_tracebuy[3] , $isFirstBuy );//呼叫實際 1.買入或賣出 2.數量 3.token 4.timeframe 5.第一次買入
                //移出追蹤，併計入持有幣種
                unset($stat_tracebuy[0]);
                unset($stat_tracebuy[1]);
                unset($stat_tracebuy[2]);
                unset($stat_tracebuy[3]);
                unset($stat_tracebuy);

            }

        }

        if( !is_null($stat_tracebuys) && count($stat_tracebuys) > 0 ){

            foreach($stat_tracebuys as $stat_tracebuy => $test){

                if(empty($$test)){
                    unset($stat_tracebuys[$test]);
                }else{

                    echo "-----------------------------" . "\n";
                    echo $stat_tracebuy[0] . "\n";
                    echo $stat_tracebuy[1] . "\n";
                    echo $stat_tracebuy[2] . "\n";
                    echo $stat_tracebuy[3] . "\n";
                    echo "-----------------------------" . "\n";

                }

                
    
            }
        }else{
            $stat_tracebuys = array();
        }
        

        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_2 = serialize($stat_tracebuys);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->save();

        return true;

    }
    
    //如果追蹤賣出幣種不是空的，才執行Trace sell
    public function JRB_DCA_SL_STRATEGY_TRACE_SELL($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells){

        //對所有追蹤賣出幣種作處理
        foreach($stat_tracesells as &$stat_tracesell){

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $stat_tracesell[0])->first();

            echo "  追蹤賣出幣種：" . $stat_tracesell[0] . "\n" .  "幣安價格：" . $symbolPrice->price . "，追蹤價格：" . $stat_tracesell[1] . '\n';

            //如果現在比追蹤價格還高，更新最終價格，並重新計算反彈賣出價格
            if( $symbolPrice->price < $stat_tracesell[1] ){
                $stat_tracesell[1] = $symbolPrice->price;
                $stat_tracesell[2] = $symbolPrice->price * 0.9;
            }

            //如果現在比追蹤價格還高，判斷是否已經低於反彈價格，超過則發動賣出
            if( $symbolPrice->price < $stat_tracesell[2] ){

                //檢查目前是否已持有，已持有則更新
                $isHold = false;
                foreach($stat_holdCoins as $stat_holdCoin){
                    if($stat_holdCoin[0] == $stat_tracesell[0] ){//如果有在已持有清單
                        $isHold = true;
                        break;
                    }
                }

                if( $isHold ){

                    //已持有，找到該幣種並更新資料
                    foreach($stat_holdCoins as $stat_holdCoin){
                        if($stat_holdCoin[0] == $stat_tracesell[0] ){
                            //找到該幣種，並移除持有
                            $needBuyQty = $stat_holdCoin[2];
                            unset($stat_holdCoin);

                            // if( count($stat_holdCoin) == 1){
                            //     $stat_holdCoin = array();
                            // }
                        }
                    }

                    $this->binanceBuySellLog( $myBot , "sell" , $needBuyQty , $symbolPrice->symbol , $symbolPrice->timeFrame , false);//呼叫實際 1.買入或賣出 2.數量
                    //移出追蹤，併計入持有幣種
                    unset($stat_tracesell);

                    if( count($stat_tracesell) == 1){
                        $stat_tracesell = array();
                    }

                }

                

            }

        }

        foreach($stat_tracesells as $stat_tracesell){

            echo "-----------------------------" . "\n";
            echo $stat_tracesell[0] . "\n";
            echo $stat_tracesell[1] . "\n";
            echo $stat_tracesell[2] . "\n";
            echo $stat_tracesell[3] . "\n";
            echo "-----------------------------" . "\n";

        }

        
        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_3 = serialize($stat_tracesells);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->save();

        return true;

    }

    //寫入訂單紀錄
    public function binanceBuySellLog($myBot , $direction , $qty , $token , $timeFrame  , $isFirstBuy){


        //如果是第一次買入，建立主訂單
        if(  $direction == "buy" & $isFirstBuy ){
            $order = new Order();
            $order->myBotId = $myBot->id;
            $order->userid = $myBot->userid;
            $order->symbol = $token;
            $order->isTrade = 0;
            $order->save();
        }
        else
        {

            //找最近一次未關單 isTrade = 0 , 該幣種的訂單
            $order = Order::where('myBotId' , $myBot->id)
                            ->where('symbol' , $token)
                            ->where('isTrade' , 0)
                            ->first();

        }

        if( is_null($order) ){
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-取得主訂單或建立主訂單異常" );
            echo "  異常-取得主訂單或建立主訂單異常\n";
        }

        //如果是賣出則關單
        if(  $direction == "sell" & $isFirstBuy == false ){
            
            $order = Order::where('myBotId' , $myBot->id)
                            ->where('symbol' , $token)
                            ->where('isTrade' , 0)
                            ->first();

            $order->isTrade = 1;
            $order->save();
        }
        

        $orderLog = new OrderLog();
        $orderLog->orderId =  $order->id;
        $orderLog->userid = $myBot->userid;
        $orderLog->myBotId = $myBot->id;
        $orderLog->symbol = $token;
        $orderLog->qty = $qty;
        $orderLog->timeFrame = $timeFrame;
        $orderLog->direction = $direction;
        $orderLog->exchange = "binance";
        $orderLog->save();

        return true;

    }

    public function sysLog($myBot , $type = "myBot" , $operation , $msg ){

        $sysLog = new SysLog();
        $sysLog->type = $type;
        $sysLog->operation = $operation;
        $sysLog->msg =  $msg;
        $sysLog->myBotId =  $myBot->id ;
        $sysLog->userid = $myBot->userid;
        $sysLog->save();

        return true;

    }
    ////////////////////////////////////////////////////////////////////////////////////////

}
