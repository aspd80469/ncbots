<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SettingService;
use App\Models\MyBot;
use App\Models\OrderLog;
use App\Models\Symbolprice;
use App\Models\SysSignal;
use App\Models\SysSignalLog;
use App\Models\SysLog;
use Carbon\Carbon;

class DaemonMyBots extends Command
{
    // 命令名稱
    protected $signature = 'daemon:DaemonMyBots';

    // 說明文字
    protected $description = '機器人守護程式';

    public function __construct(SettingService $settingService)
    {
        parent::__construct();
        $this->settingService = $settingService;
    }

    // Console 執行的程式
    public function handle()
    {

        //無限執行機器人守護程式
        while(1){

            //撈取所有可以執行的交易機器人 狀態為0 正常，至少api key1 有設定值
            $myBots = MyBot::where('status', 0)->where('apiKeyId1', "!=" , "")->get();

            //遍歷每一個機器人，去呼叫對應該執行的策略
            foreach( $myBots as $myBot ){

                //此處必須設定對應策略執行Function，否則會無法執行
                if( $myBot->usedStgy == "JRB_DCA_SL_STRATEGY" ){
                    $this->JRB_DCA_SL_STRATEGY($myBot);
                }

            }
            
            echo "機器人遍歷批次執行完畢，執行時間每10秒更新\n";
            //暫停時間
            sleep(10);

        }

    }

    //JRB攤平止損趨勢策略
    public function JRB_DCA_SL_STRATEGY($myBot)
    {
        //定義可操作幣種
        $allowSymbolLists = "BTCUSDT;ETHUSDT;SOLUSDT;MATICUSDT;FTTUSDT;LUNAUSDT";
        $maxDCAfreq = 3;//最大攤平次數，每次攤平使用多一倍成本，故單幣種投資金額會提高很大，超過則顯示該幣種警告
        $aSLists = explode(";", $allowSymbolLists);

        //機器人狀態儲存
        $stat_holdCoins = unserialize($myBot->field_1);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $stat_tracebuys = unserialize($myBot->field_2);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $stat_tracesells = unserialize($myBot->field_3);//儲存目前 追蹤中賣出幣種 最高價格 反彈價格
        $stat_signalNum = $myBot->field_4;//最後訊號處理的編號，避免處理到已收到的訊號

        //機器人只透過接收可使用的訊號來更新機器人狀態

        //1.檢查是否有新的訊號，撈取標準是目前時間往前推5分鐘，超過時間的訊號不處理
        //todo 有可能有延遲傳遞的訊號要處理
        //todo 訊號限定會有刪除策略後對應不到id的問題

        //可以使用的訊號
        $avbleSgnls = SysSignal::select('id')->where('onlyStgyIds', '')->orWhere('onlyStgyIds', 'LIKE', '%1;%')->get();
        foreach( $avbleSgnls as $avbleSgnl){
            $avbleSgnlStr = $avbleSgnl->id + ",";
        }
        $avbleSgnlStr = substr($avbleSgnlStr, -1);//移除最後一個分號
        $sysSignalLogs = SysSignalLog::where('exchange' , 'binance')
                                    ->where('id', '>' , $stat_signalNum )
                                    ->whereIn('sigId' , $avbleSgnlStr)
                                    ->where('created_at', '>=', Carbon::now()->subMinutes(5)
                                    ->toDateTimeString())->get();
        

        //2.從已經取得的訊號判斷，如果最近沒有訊號就持續等待下一次的買入訊號或是下跌追蹤買入訊號出現
        foreach( $sysSignalLogs as $sysSignalLog ){

            //如果訊號是有在可操作清單內
            if( in_array($sysSignalLog->token , $aSLists) ){

                //檢查是否以持有該幣種，並且看是買入還是賣出訊號
                //1.未持有且買入訊號，則買入
                //2.未持有且賣出訊號，則追蹤買入
                //3.持有且買入訊號，則追蹤賣出
                //4.持有且賣出訊號，則追蹤買入(DCA)
                
                //訊號是否已被處理過，處理過則忽略其他判斷
                $isProcess = false;

                //1.未持有且買入訊號，則買入
                $isHold = false;
                foreach($stat_holdCoins as $stat_holdCoin){
                    if($stat_holdCoin[0] == $sysSignalLog->token ){//如果有在已持有清單
                        $isHold = true;
                        break;
                    }
                }

                if( $isHold == false && $sysSignalLog->direction == "buy" ){
                    $this->binanceBuySellLog($myBot , "buy" , 30/$sysSignalLog->price , $sysSignalLog);//呼叫實際買入或賣出
                    $isProcess = true;
                    echo "1.未持有且買入訊號，則買入\n";
                    break;//直接跳出外面迴圈
                }

                //2.未持有且賣出訊號，則追蹤買入
                if( $isHold == false && $sysSignalLog->direction == "sell" ){

                    //把幣種移入追蹤買入
                    $coin=array( $sysSignalLog->token , $sysSignalLog->price , $sysSignalLog->price * 1.1 );
                    array_push($stat_tracebuys , $coin);
                    $isProcess = true;
                    echo "2.未持有且賣出訊號，則追蹤買入\n";
                    break;//直接跳出外面迴圈
                }

                //3.持有且買入訊號，則追蹤賣出
                if( $isHold == true && $sysSignalLog->direction == "buy" ){
                    
                    //把幣種移入追蹤賣出
                    $coin=array( $sysSignalLog->token , $sysSignalLog->price , $sysSignalLog->price * 1.1 );
                    array_push($stat_tracesells , $coin);
                    $isProcess = true;
                    echo "3.持有且買入訊號，則追蹤賣出\n";
                    break;//直接跳出外面迴圈

                }

                //4.持有且賣出訊號，則追蹤買入(DCA)
                if( $isHold == true && $sysSignalLog->direction == "sell" ){
                    
                    //把幣種移入追蹤賣出
                    $coin=array( $sysSignalLog->token , $sysSignalLog->price , $sysSignalLog->price * 1.1 );
                    array_push($stat_tracesells , $coin);

                    //持有幣種增加註記，進入DCA
                    foreach($stat_holdCoins as $stat_holdCoin){
                        if($stat_holdCoin[0] == $sysSignalLog->token ){//如果有在已持有清單
                            $stat_holdCoin[0][4] =  $stat_holdCoin[0][4]+1;//註記被加入了攤平計畫
                            break;
                        }
                    }
                    echo "4.持有且賣出訊號，則追蹤買入(DCA)\n";
                    $isProcess = true;
                    break;//直接跳出外面迴圈

                }

            }

        }

        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_2 = serialize($stat_tracebuys);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->field_3 = serialize($stat_tracesells);//儲存目前 追蹤中賣出幣種 最高價格 反彈價格
        $myBot->save();


        //如果追蹤買入幣種不是空的，才執行Trace buy
        if( !is_null($stat_tracebuys) ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_BUY($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells);
        }

        //如果追蹤買賣出幣種不是空的，才執行Trace sell
        if( !is_null($stat_tracesells) ){
            $this->JRB_DCA_SL_STRATEGY_TRACE_SELL($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells);
        }

        return true;
    }

    //如果追蹤買入幣種不是空的，才執行Trace buy
    public function JRB_DCA_SL_STRATEGY_TRACE_BUY($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells){

        //對所有追蹤買入幣種作處理
        foreach($stat_tracebuys as $stat_tracebuy){

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $stat_tracebuy[0])->first();

            //如果現在比追蹤價格還低，更新最終價格，並重新計算反彈價格
            if( $symbolPrice->price < $stat_tracebuy[1] ){
                $stat_tracebuy[1] = $symbolPrice->price;
                $stat_tracebuy[2] = $symbolPrice->price * 1.1;
            }

            //如果現在比追蹤價格還高，判斷是否已經超過反彈價格，超過則發動買入
            if( $symbolPrice->price > $stat_tracebuy[2] ){

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
                    foreach($stat_holdCoins as $stat_holdCoin){
                        if($stat_holdCoin[0] == $stat_tracebuy[0] ){
                            //找到該幣種
                            $stat_holdCoin[0][1] = ($stat_holdCoin[0][1] + $symbolPrice->price) / 2;
                            $stat_holdCoin[0][2] += $stat_holdCoin[0][2];//買入目前持有同樣的數量
                            $stat_holdCoin[0][3] = 'buy';
                            $stat_holdCoin[0][4] += 1;//DCA
                            $needBuyQty = $stat_holdCoin[0][2];
                        }
                    }


                }else{

                    $coinHold = array( $stat_tracebuy[0]  , $symbolPrice->price , 30/$symbolPrice->price , 'buy' , 0 );
                    array_push($stat_holdCoins, $coinHold);
                }

                $this->binanceBuySellLog( $myBot , "buy" , $needBuyQty , $sysSignalLog);//呼叫實際 1.買入或賣出 2.數量
                //移出追蹤，併計入持有幣種
                unset($stat_tracebuy);

                

            }

        }

        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_2 = serialize($stat_tracebuys);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->save();

    }
    
    //如果追蹤賣出幣種不是空的，才執行Trace sell
    public function JRB_DCA_SL_STRATEGY_TRACE_SELL($myBot , $stat_holdCoins , $stat_tracebuys , $stat_tracesells){

        //對所有追蹤買出幣種作處理
        foreach($stat_tracesells as $stat_tracesell){

            //撈取Binance現貨價格
            $symbolPrice = SymbolPrice::where('exchange', 'binance')->where('symbol', $stat_tracesell[0])->first();

            //如果現在比追蹤價格還高，更新最終價格，並重新計算反彈賣出價格
            if( $symbolPrice->price < $stat_tracesell[1] ){
                $stat_tracesell[1] = $symbolPrice->price;
                $stat_tracesell[2] = $symbolPrice->price * 0.9;
            }

            //如果現在比追蹤價格還高，判斷是否已經低於反彈價格，超過則發動買出
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
                            $needBuyQty = $stat_holdCoin[0][2];
                            unset($stat_holdCoin);
                        }
                    }


                }

                $this->binanceBuySellLog( $myBot , "sell" , $needBuyQty , $sysSignalLog);//呼叫實際 1.買入或賣出 2.數量
                //移出追蹤，併計入持有幣種
                unset($stat_tracesell);

            }

        }

        //已處理完所有新訊號，寫入資料庫
        $myBot->field_1 = serialize($stat_holdCoins);//儲存目前 0已購入幣種 1平均成本 2總數量 3上次買入是(sell or buy) 4已經DCA次數
        $myBot->field_2 = serialize($stat_tracebuys);//儲存目前 追蹤中買入幣種 最低價格 反彈價格
        $myBot->save();

    }

    //寫入訂單紀錄
    public function binanceBuySellLog($myBot , $direction , $qty , $sysSignalLog){

        $orderLog = new OrderLog();
        $orderLog->myBotId = $myBot->id;
        $orderLog->symbol = $sysSignalLog->token;
        $orderLog->qty = $myBot->invAmount / 10;
        $orderLog->timeFrame = $sysSignalLog->timeFrame;
        $orderLog->direction = $sysSignalLog->direction;
        $orderLog->exchange = "binance";
        $orderLog->save();

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

}
