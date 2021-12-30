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
    protected $signature = 'daemon:Price';

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

            //執行機器人，取得條件： 1.狀態為0 正常  2.api key1 有設定值
            $myBots = MyBot::where('status', 0)->whereNotNull('apiKeyId1')->get();

            //遍歷每一個機器人，去呼叫對應該執行的策略
            foreach( $myBots as $myBot ){

                //此處必須設定對應策略執行Function，否則會無法執行
                if( $myBot->usedStgy == "1" ){
                    echo "\n\n" . "#" . $myBot->id . "機器人執行" . "JRB_DCA_SL_STRATEGY 策略" . "\n\n";
                    $botsStgy = BotsStgy::find(1);
                    $this->JRB_DCA_SL_STRATEGY($myBot , $botsStgy);
                }else{

                    //沒有找到對應策略，暫停機器人
                    $myBot->status = 1 ;
                    $myBot->save();
                    $this->sysLog($myBot , "myBot" , "ERR" , "異常-機器人策略設定不正確" );
                    echo "  異常-機器人策略設定不正確\n";

                }

            }
            
            echo "完成，執行時間每10秒更新\n\n";
            
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
        //定義
        $allowSymbolLists = explode(";", $botsStgy->allowSymbol);
        $maxDCAfreq = $botsStgy->maxDCAqty;//最大攤平次數，每次攤平使用多一倍成本，故單幣種投資金額會提高很大，超過則顯示該幣種警告
        $fixedBuyAmt = $botsStgy->fixedBuyAmt;
        //機器人狀態儲存

        //儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
        $stat_holdCoins = unserialize($myBot->field_1);

        //儲存目前 0追蹤中買入幣種 1最低價格 2反彈價格 3時框
        $stat_tracebuys = unserialize($myBot->field_2);

        //儲存目前 0追蹤中賣出幣種 1最高價格 2反彈價格 3時框
        //$stat_tracesells = unserialize($myBot->field_3);

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

        // if( $stat_tracesells == false & !is_array($stat_tracesells) ){
        //     $stat_tracesells = array();
        //     $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_tracesells解析失敗紀錄並初始化" );
        //     echo "  異常-stat_tracesells解析失敗紀錄並初始化\n";
        // }

        if( !is_numeric($stat_signalNum) ){

            //直接從目前可以使用的訊號開始處理
            $avbleSgnls = SysSignal::select('id')->where('onlyStgyIds', '')->orWhere('onlyStgyIds', 'LIKE', '%1;%')->get();
            $sysSignalLogs = SysSignalLog::where('kType' , 'crypto')
                                        ->whereIn('token' , $allowSymbolLists)
                                        ->where('exchange' , 'binance')
                                        ->whereIn('sigId' , $avbleSgnls)
                                        ->where('created_at', '>=', Carbon::now()->subMinutes(5)
                                        ->toDateTimeString())->first();

            $stat_signalNum = $sysSignalLogs->id;
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-stat_signalNum解析失敗紀錄並初始化" );
            echo "  異常-stat_signalNum解析失敗紀錄並初始化\n";
        }

        //機器人只透過接收可使用的訊號來更新機器人狀態
        //1.檢查是否有新的訊號，撈取標準是目前時間往前推5分鐘，超過時間的訊號不處理
        $avbleSgnls = SysSignal::select('id')->where('onlyStgyIds', '')->orWhere('onlyStgyIds', 'LIKE', '%1;%')->get();
        
        $sysSignalLogs = SysSignalLog::where('kType' , 'crypto')
                                    ->whereIn('token' , $allowSymbolLists)
                                    ->where('exchange' , 'binance')
                                    ->where('id', '>' , $stat_signalNum )
                                    ->whereIn('sigId' , $avbleSgnls)
                                    ->where('created_at', '>=', Carbon::now()->subMinutes(5)
                                    ->toDateTimeString())->get();

        if( !is_null($sysSignalLogs) ){
            echo "---收到訊號------------------------------------\n";
            echo $sysSignalLogs . "\n";
            echo "-----------------------------------------------\n";
        }
        
        //2.從已經取得的訊號判斷，如果最近沒有訊號就持續等待下一次的買入訊號或是下跌追蹤買入訊號出現
        foreach( $sysSignalLogs as $sysSignalLog ){

            //重置訊號是否已被處理過、是否持有該幣種的flag、計算該次固定買入數量，處理過則忽略其他判斷
            $fixedBuyQty = round( $botsStgy->fixedBuyAmt / $sysSignalLog->price , 6 );
            $isProcess = false;
            $isHold = false;
            //檢查是否以持有該幣種，並且看是買入還是賣出訊號
            //1.未持有且買入訊號，則買入
            //2.未持有且賣出訊號，則追蹤買入
            //3.持有且買入訊號，則追蹤賣出
            //4.持有且賣出訊號，判斷價格是否已超過固定獲利1%或以上則賣出，沒有則追蹤買入(DCA)

            //檢查目前是否持有該訊號的幣種
            $isFindToken = array_search($sysSignalLog->token , array_column($stat_holdCoins, 'token'));
            $isHold = false;
            $token = '';
            $avgPrice = '';
            $reSellPrice = '';
            $holdQty = '';
            $direction = '';
            $DCA = '';
            $timeFrame = '';

            if( $isFindToken >= 0 ){

                echo "  目前持有訊號幣種：" . $sysSignalLog->token . "，isFindToken：" . $isFindToken ."\n";

                //撈出指定coin
                foreach($stat_holdCoins as $stat_holdCoin){
                    if( $stat_holdCoin["token"] == $sysSignalLog->token && 
                        $stat_holdCoin["timeFrame"] == $sysSignalLog->timeFrame 
                    ){
                        $isHold = true;
                        $token = $stat_holdCoin["token"];
                        $avgPrice = $stat_holdCoin["avgPrice"];
                        $reSellPrice = $stat_holdCoin["reSellPrice"];
                        $holdQty = $stat_holdCoin["qty"];
                        $direction = $stat_holdCoin["direction"];
                        $DCA = $stat_holdCoin["DCA"];
                        $timeFrame = $stat_holdCoin["timeFrame"];
                        break;
                    }
                }

            }

            //1.未持有且買入訊號，則買入
            if( $isHold == false && $sysSignalLog->direction == "buy" ){

                echo "  執行策略-1.未持有且買入訊號，則買入\n";
                $this->binanceBuySellLog($myBot , "buy" , $fixedBuyQty  , $sysSignalLog->token , $sysSignalLog->timeFrame , true);//呼叫實際買入或賣出
                //更新持有清單 儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
                $coinHold = array( "token" => $sysSignalLog->token  , 
                                    "avgPrice" => $sysSignalLog->price , 
                                    "enterSellPrice" => $sysSignalLog->price , 
                                    "reSellPrice" => $sysSignalLog->price * 1.02 , 
                                    "qty" => $fixedBuyQty , 
                                    "direction" => 'buy' ,
                                    "DCA" => 0 ,
                                    "timeFrame" => $sysSignalLog->timeFrame 
                                );
                array_push($stat_holdCoins, $coinHold);

                $isProcess = true;
                $stat_signalNum = $sysSignalLog->id;
                break;//直接跳出外面迴圈
            }

            //2.未持有且賣出訊號，則追蹤買入，條件反彈價格8%，如已高於開始追蹤時價格則停止直接停止追價並移出
            if( $isHold == false && $sysSignalLog->direction == "sell" ){

                echo "  執行策略-2.未持有且賣出訊號，則追蹤買入，條件反彈價格8%，如已高於開始追蹤時價格則停止直接停止追價並移出\n";

                $coin = array( "token" => $sysSignalLog->token  , 
                                "enterPrice" => $sysSignalLog->price , 
                                "price" => $sysSignalLog->price , 
                                "reBuyPrice" => $sysSignalLog->price * 1.08 , 
                                "timeFrame" => $sysSignalLog->timeFrame 
                                );

                array_push($stat_tracebuys, $coin);
                $isProcess = true;
                $stat_signalNum = $sysSignalLog->id;
                break;//直接跳出外面迴圈
            }

            //3.持有且買入訊號，則追蹤賣出
            // if( $isHold == true && $sysSignalLog->direction == "buy" ){

            //     echo "  執行策略-3.持有且買入訊號，則追蹤賣出，持有幣種將持續維持追蹤賣出\n";
                
            //     //檢查是否是持有且在追中買入的情形，如是則移出
            //     foreach($stat_tracebuys as &$stat_tracebuy){
            //         if( $stat_tracebuy["token"] == $sysSignalLog->token && 
            //             $stat_tracebuy["timeFrame"] == $sysSignalLog->timeFrame 
            //         ){
            //             //如果有在已持有清單，且在追中買入模式，則移除追中買入模式，改為賣出
            //             unset($stat_tracebuy);
            //             break;
            //         }
            //     }

            //     //把幣種移入追蹤賣出，回彈價格2%
            //     $coin = array( "token" => $sysSignalLog->token  , 
            //                     "price" => $sysSignalLog->price , 
            //                     "reSellPrice" => $sysSignalLog->price * 1.02 , 
            //                     "timeFrame" => $sysSignalLog->timeFrame 
            //                     );
            //     array_push($stat_tracesells, $coin);

            //     $isProcess = true;
            //     $stat_signalNum = $sysSignalLog->id;
            //     break;//直接跳出外面迴圈

            // }

            //4.持有且賣出訊號，判斷價格是否已超過固定獲利1%或以上則賣出，沒有則追蹤買入(DCA)
            if( $isHold == true && $sysSignalLog->direction == "sell" ){

                echo "  執行策略-4.持有且賣出訊號，判斷價格是否已超過固定獲利1%或以上則賣出，沒有則追蹤買入(DCA)\n";

                $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $sysSignalLog->token)->first();
                echo "    目前現貨價格：" . $symbolPrice->price ."，機器人平均成本價格：" . $avgPrice . "\n";

                //如果價格已超過固定獲利1%或以上則賣出
                if($isHold && $symbolPrice->price > $avgPrice * 1.01){

                    echo "    已達固定獲利1%或以上，目前現貨價格：" . $symbolPrice->price ."，平均成本：" . $avgPrice  . "，獲利1%價格：" . $avgPrice * 1.01 . "，執行賣出\n";
                    $this->binanceBuySellLog($myBot , "sell" , $holdQty , $sysSignalLog->token , $symbolPrice->timeFrame , false);//呼叫實際買入或賣出

                    //找到該幣種並移除持有
                    foreach($stat_holdCoins as $stat_holdCoin => $v){
                        if( $v["token"] == $sysSignalLog->token && 
                            $v["timeFrame"] == $sysSignalLog->timeFrame 
                        ){
                            unset($stat_holdCoins[$stat_holdCoin]);
                        }
                    }

                }else{

                    //把幣種移入追蹤買入，反彈買入價格為8%
                    echo "    未達固定獲利1%或以上，幣種：" . $sysSignalLog->token ."，進入追蹤買入攤平模式(DCA)\n";

                    $coin = array( "token" => $sysSignalLog->token  , 
                                "enterPrice" => $symbolPrice->price , 
                                "price" => $symbolPrice->price , 
                                "reBuyPrice" => $sysSignalLog->price * 1.08 , 
                                "timeFrame" => $sysSignalLog->timeFrame 
                                );

                    array_push($stat_tracebuys, $coin);


                    //持有幣種增加註記，進入DCA
                    foreach($stat_holdCoins as $stat_holdCoin => $v){
                        if( $v["token"] == $sysSignalLog->token && 
                            $v["timeFrame"] == $sysSignalLog->timeFrame 
                        ){
                            $stat_holdCoins[$stat_holdCoin]["DCA"] += 1;//註記被加入了攤平計畫
                            break;
                        }
                    }

                }

                $stat_signalNum = $sysSignalLog->id;
                $isProcess = true;
                break;//直接跳出外面迴圈

            }

        }

        //已處理完所有新訊號，寫入資料庫
        //更新持有清單 儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數 5時框
        $myBot->field_1 = serialize($stat_holdCoins);
        //儲存目前 0追蹤中買入幣種 1最低價格 2反彈價格 3時框
        $myBot->field_2 = serialize($stat_tracebuys);
        //儲存目前 0追蹤中賣出幣種 1最高價格 2反彈價格 3時框
        // $myBot->field_3 = serialize($stat_tracesells);
        $myBot->field_4 = $stat_signalNum;
        $myBot->save();

        //如果追蹤買入幣種不是空的，才執行Trace buy
        if( !is_null($stat_tracebuys) && count($stat_tracebuys) > 0 ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_BUY($myBot , $stat_holdCoins , $stat_tracebuys ,  $maxDCAfreq , $fixedBuyAmt);
        }

        //如果追蹤買賣出幣種不是空的，才執行Trace sell
        if( !is_null($stat_holdCoins) && count($stat_holdCoins) > 0 ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_SELL( $myBot , $stat_holdCoins );
        }

        return true;
    }

    //如果追蹤買入幣種不是空的，才執行Trace buy 未持有且賣出訊號，則追蹤買入，條件反彈價格8%，如已高於開始追蹤時價格則停止直接停止追價並移出
    public function JRB_DCA_SL_STRATEGY_TRACE_BUY( $myBot , $stat_holdCoins , $stat_tracebuys , $maxDCAfreq , $fixedBuyAmt ){

        echo "  JRB_DCA_SL_STRATEGY_TRACE_BUY(DCA)\n";

        $coinNeedRemoveLists = array();
        //對所有追蹤買入幣種作處理
        foreach($stat_tracebuys as $stat_tracebuy => $stv){
            $isDCAMax = false;
            //檢查該幣種是否已達最大買入次數，已到達時就直接返回不再追價
            foreach( $stat_holdCoins as $stat_holdCoin => $bcv ){

                if( ($stv['token']  ==  $bcv["token"]) && ($bcv["DCA"] >  $maxDCAfreq) ){

                    //寫入最大買入訊息警告
                    echo "  幣種" . $bcv["token"] . "進入DCA已達最大買入次數，請確認\n";
                    $myBot->retMsg = "幣種 " . $bcv["token"] . " 進入DCA已達最大買入次數，請確認";
                    $myBot->save();
                    
                    $this->sysLog($myBot , "myBot" , "DCA" , "幣種 " . $bcv["token"] . " 進入DCA已達最大買入次數，請確認" );

                    //移除追蹤該幣種
                    unset($stat_holdCoins[$stat_holdCoin]);
                    $isDCAMax = true;//已到達最大買入次數就直接返回不再追價
                    break;
                }

            }

            //已到達最大買入次數就直接返回不再追價
            if(  $isDCAMax ){
                break;
            }

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $stv["token"])->first();

            echo "  執行-追蹤買入幣種：" . $stv["token"] . "，幣安價格：" . $symbolPrice->price . "，追蹤價格：" . $stv["price"] . "，反彈買入價：" . $stv["reBuyPrice"] . "\n";

            //如果現在比追蹤價格還低，更新最終價格，並重新計算反彈價格
            if( $symbolPrice->price < $stv["price"] ){

                $orgPrice = $stv["price"];
                $orgreBuyPrice = $stv["reBuyPrice"];
                $stat_tracebuys[$stat_tracebuy]["price"] = $symbolPrice->price;
                $stat_tracebuys[$stat_tracebuy]["reBuyPrice"] = $symbolPrice->price * 1.08;
                echo "  執行-更新追蹤買入最低價：" . $symbolPrice->price . "<" . $orgPrice . "，新追蹤價格：" . $stv["price"] . "，新反彈買入價：" . $stv["reBuyPrice"] . "\n";
                
            }

            //如果現在比追蹤價格還高且已經超過反彈價格，超過則發動買入，但必須小於enterPirce
            if( ( $symbolPrice->price <= $stv["enterPrice"]) && ($symbolPrice->price > $stv["price"]) ){
                echo "  執行-現在比追蹤價格還高，追蹤價格還高且已經超過反彈價格，但必須小於enterPirce，超過則發動買入：" . $symbolPrice->price . ">" . $stv["price"] . "\n";

                //檢查目前是否已持有，已持有則更新，未持有則加入該幣種
                $isHold = false;
                foreach($stat_holdCoins as $stat_holdCoin){
                    if($stat_holdCoin["token"] == $stv["token"] ){//如果有在已持有清單
                        $isHold = true;
                        break;
                    }
                }

                if( $isHold ){

                    //已持有，找到該幣種並更新資料
                    foreach($stat_holdCoins as $stat_holdCoin => $vvv){
                        if( $stv["token"] == $symbolPrice->symbol ){
                            //找到該幣種
                            echo "  執行-持有，更新平均成本：" . ($vvv["avgPrice"] + $symbolPrice->price) / 2 . "\n";
                            $stat_holdCoins[$stat_holdCoin]["avgPrice"] = ($vvv["avgPrice"] + $symbolPrice->price) / 2;
                            $stat_holdCoins[$stat_holdCoin]["qty"] += $vvv["qty"];//買入目前持有同樣的數量f
                            $stat_holdCoins[$stat_holdCoin]["direction"] = 'buy';
                            $stat_holdCoins[$stat_holdCoin]["DCA"] += 1;//DCA
                            $needBuyQty = $vvv["qty"];
                        }
                    }

                }else{

                    echo "  執行-未持有，更新成本：" . $symbolPrice->price . "\n";
                    $coinHold = array( "token" => $symbolPrice->token  , 
                                    "avgPrice" => $symbolPrice->price , 
                                    "enterSellPrice" => $symbolPrice->price , 
                                    "reSellPrice" => $symbolPrice->price * 1.02 , 
                                    "qty" =>  round( $fixedBuyAmt / $symbolPrice->price , 6 ) , 
                                    "direction" => 'buy' ,
                                    "DCA" => 0 ,
                                    "timeFrame" => $symbolPrice->timeFrame 
                                );
                    array_push($stat_holdCoins, $coinHold);
                }

                //判斷此次DCA是否為第一次買入
                $isFirstBuy = false;
                $order = Order::where('myBotId' , $myBot->id )
                            ->where('symbol' , $stv["token"] )
                            ->where('isTrade' , 0)
                            ->first();
                if( is_null($order) ){
                    $isFirstBuy = true;
                    $needBuyQty = round($fixedBuyAmt / $symbolPrice->price, 6);
                }else{
                    $needBuyQty = round( $stv["qty"] , 6);
                }

                $isBuy = true;
                //呼叫實際 1.買入或賣出 2.數量 3.token 4.timeFrame 5.第一次買入
                $this->binanceBuySellLog( $myBot , "buy" , $needBuyQty , $stv["token"] , $stv["timeFrame"] , $isFirstBuy );
                //移出追蹤，併計入持有幣種
                $coinNeedRemove = array(    "token" => $stv["token"]  , 
                                            "timeFrame" => $stv["timeFrame"] 
                                        );
                array_push($coinNeedRemoveLists, $coinNeedRemove);


            }

        }

        foreach($stat_tracebuys as $stat_tracebuy => $v ){
            foreach( $coinNeedRemoveLists as $coinNeedRemoveList ){
                
                if( $v["token"] == $coinNeedRemoveList["token"] &&
                    $v["timeFrame"] == $coinNeedRemoveList["timeFrame"]
                    ){
                        echo "---stat_tracebuys---\n";
                        print_r($stat_tracebuys);
                        echo "---coinNeedRemoveList---\n";
                        print_r($coinNeedRemoveList);
                        echo "---coinNeedRemoveList---\n";

                    unset($stat_tracebuys[$stat_tracebuy]);
                }

            }
        }

        echo '---追蹤買入前檢視stat_tracebuys---\n';
        print_r($stat_tracebuys);
        echo '---------------------------------\n';

        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_2 = serialize($stat_tracebuys);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->save();

        return true;

    }
    
    //如果追蹤賣出幣種不是空的，才執行Trace sell
    public function JRB_DCA_SL_STRATEGY_TRACE_SELL( $myBot , $stat_holdCoins ){

        //對所有追蹤賣出幣種作處理
        foreach($stat_holdCoins as $stat_holdCoin => $v ){

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $v["token"])->first();

            echo    "  追蹤賣出幣種：" . $v["token"] .  "幣安價格：" . $symbolPrice->price . 
                    "，追蹤價格：" . $v["enterSellPrice"] . "，追蹤反彈賣出價格：" . $v["reSellPrice"]  ."\n";

            //如果現在比追蹤價格還高，更新最終價格，並重新計算反彈賣出價格
            if( $symbolPrice->price > $v["enterSellPrice"] ){
                echo    "  重新計算反彈賣出價格\n";
                $stat_holdCoins[$stat_holdCoin]["enterSellPrice"] = $symbolPrice->price;
                $stat_holdCoins[$stat_holdCoin]["reSellPrice"] = $symbolPrice->price * 0.95;
            }

            //判斷高於平均買入成本價1%且低於反彈價格，超過則發動賣出
            if(  ($symbolPrice->price > $v["avgPrice"] * 1.01)  && ($symbolPrice->price < $v["reSellPrice"]) ){

                echo "  持有且目前價格大於平均成本價且獲利超過1%，執行售出\n";
                
                $this->binanceBuySellLog( $myBot , "sell" , $v["qty"] , $symbolPrice->symbol , $v["timeFrame"] , false);//呼叫實際 1.買入或賣出 2.數量
                //移出追蹤，併計入持有幣種
                unset($stat_holdCoins[$stat_holdCoin]);

            }else{

                //持有且未大於平均成本，又收到賣出訊號

            }

        }

        // foreach($stat_holdCoins as $stat_holdCoin){

        //     echo "-----------------------------" . "\n";
        //     echo $stat_holdCoin["token"] . "\n";
        //     echo $stat_holdCoin["price"] . "\n";
        //     echo $stat_holdCoin["reSellPrice"] . "\n";
        //     echo $stat_holdCoin["timeFrame"] . "\n";
        //     echo "-----------------------------" . "\n";

        // }

        
        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
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
        elseif( $direction == "buy" & !$isFirstBuy )
        {

            //找最近一次未關單 isTrade = 0 , 該幣種的訂單
            $order = Order::where('myBotId' , $myBot->id)
                            ->where('symbol' , $token)
                            ->where('isTrade' , 0)
                            ->first();

        }elseif( $direction == "sell" & $isFirstBuy == false ){

            //如果是賣出則關單
            $order = Order::where('myBotId' , $myBot->id)
            ->where('symbol' , $token)
            ->where('isTrade' , 0)
            ->first();

            if( !is_null($order) ){
                $order->isTrade = 1;
                $order->save();
            }else{

                $this->sysLog($myBot , "myBot" , "ERR" , "異常-主訂單已關單或無此主訂單但收到賣出訊號" );
                echo "  異常-主訂單已關單或無此主訂單但收到賣出訊號\n";
            }

        }

        if( is_null($order) ){
            $this->sysLog($myBot , "myBot" , "ERR" , "異常-取得主訂單或建立主訂單異常" );
            echo "  異常-取得主訂單或建立主訂單異常\n";
        }else{

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


        }

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
