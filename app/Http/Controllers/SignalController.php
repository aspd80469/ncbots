<?php

namespace App\Http\Controllers;

use App\Models\SysSignal;
use App\Models\SysSignalLog;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon\Carbon;

class SignalController extends Controller
{
    protected $settingService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function signalReceive(Request $request)
    {
        //設定資料
        $rData = array();

        //剖析json
        $data = $request->json()->all();
        
        //檢查data格式是否正確
        if( empty($data) | is_null($data) ){

            $rData['code'] = "-101";
            $rData['msg'] = "無效的欄位";
            return json_encode($rData);
        }
        
        if( 
            !array_key_exists( "orderSide" , $data) |
            !array_key_exists( "ticker" , $data) |
            !array_key_exists( "entryPrice" , $data) |
            !array_key_exists( "stopLoss" , $data) |
            !array_key_exists( "tp1" , $data) |
            !array_key_exists( "tp2" , $data) |
            !array_key_exists( "tp3" , $data) |
            !array_key_exists( "tp4" , $data) |
            !array_key_exists( "tp5" , $data) 
        ){

            $rData['code'] = "-102";
            $rData['msg'] ="資料格式不正確";
            return json_encode($rData);

        }
        
        //過濾html
        $data['orderSide'] = htmlspecialchars($data['orderSide'], ENT_QUOTES);
        $data['ticker'] = htmlspecialchars($data['ticker'], ENT_QUOTES);
        $data['entryPrice'] = htmlspecialchars($data['entryPrice'], ENT_QUOTES);
        $data['stopLoss'] = htmlspecialchars($data['stopLoss'], ENT_QUOTES);
        $data['tp1'] = htmlspecialchars($data['tp1'], ENT_QUOTES);
        $data['tp2'] = htmlspecialchars($data['tp2'], ENT_QUOTES);
        $data['tp3'] = htmlspecialchars($data['tp3'], ENT_QUOTES);
        $data['tp4'] = htmlspecialchars($data['tp4'], ENT_QUOTES);
        $data['tp5'] = htmlspecialchars($data['tp5'], ENT_QUOTES);

        //檢查ncToken是否存在
        // $sysSignal = SysSignal::where('ncToken', $data['ncToken'])->first();
        
        // if( is_null($sysSignal) | $sysSignal->status == 1 ){
        //     $rData['code'] = "-103";
        //     $rData['msg'] ="無Token或Token停用";
        //     return json_encode($rData);

        // }

        // if(Carbon::now()->gt(Carbon::parse($sysSignal->expired_at)) ){
        //     $rData['code'] = "-104";
        //     $rData['msg'] ="驗證token無效，已過期";
        //     return json_encode($rData);
        // }

        //檢查資料格式 - orderSide
        if(strlen($data['orderSide']) > 4 | ($data['orderSide'] != "buy" && $data['orderSide'] != "sell") ){
            $rData['code'] = "-105";
            $rData['msg'] = "orderSide長度大於4或非buy或非sell值";
            return json_encode($rData);
        }
        
        //檢查資料格式 - ticker
        if(strlen($data['ticker']) > 10){
            $rData['code'] = "-106";
            $rData['msg'] = "ticker長度大於10";
            return json_encode($rData);
        }
        
        //檢查資料格式 - entryPrice
        if(strlen($data['entryPrice']) > 20){
            $rData['code'] = "-107";
            $rData['msg'] ="entryPrice長度大於20";
            return json_encode($rData);
        }

         //檢查資料格式 - stopLoss
         if(strlen($data['stopLoss']) > 20){
            $rData['code'] = "-108";
            $rData['msg'] ="stopLoss長度大於20";
            return json_encode($rData);
        }

        //檢查資料格式 - tp1 ~ tp5
        if(strlen($data['tp1']) > 20 | strlen($data['tp2']) > 20 | strlen($data['tp3']) > 20 | strlen($data['tp4']) > 20 | strlen($data['tp5']) > 20){
            $rData['code'] = "-109";
            $rData['msg'] ="tp1或tp2或tp3或tp4或tp5";
            return json_encode($rData);
        }
        

        //寫入資料庫
        $signal = new SysSignalLog();
        $signal->sigId = "2";
        $signal->kType = "c";//c crypto s stock
        $signal->token = $data['ticker'];
        $signal->entryPrice = $data['entryPrice'];
        $signal->stopLoss = $data['stopLoss'];
        $signal->tp1 = $data['tp1'];
        $signal->tp2 = $data['tp2'];
        $signal->tp3 = $data['tp3'];
        $signal->tp4 = $data['tp4'];
        $signal->tp5 = $data['tp5'];
        $signal->direction = $data['orderSide'];
        $signal->exchange = "binance";
        $signal->save();

        $rData['code'] = "200";
        $rData['msg'] = "";
        return json_encode($rData);


    }
    
    // public function signalReceive(Request $request)
    // {
    //     //設定資料
    //     $rData = array();

    //     //剖析json
    //     $data = $request->json()->all();
        
    //     //檢查data格式是否正確
    //     if( empty($data) | is_null($data) ){

    //         $rData['code'] = "-101";
    //         $rData['msg'] = "無效的欄位";
    //         return json_encode($rData);
    //     }
        
    //     if( 

    //     ){

    //         $rData['code'] = "-102";
    //         $rData['msg'] ="資料格式不正確";
    //         return json_encode($rData);

    //     }
        
    //     //過濾html
    //     $data['ncToken'] = htmlspecialchars($data['ncToken'], ENT_QUOTES);
    //     $data['kType'] = htmlspecialchars($data['kType'], ENT_QUOTES);
    //     $data['token'] = htmlspecialchars($data['token'], ENT_QUOTES);
    //     $data['price'] = htmlspecialchars($data['price'], ENT_QUOTES);
    //     $data['timeFrame'] = htmlspecialchars($data['timeFrame'], ENT_QUOTES);
    //     $data['direction'] = htmlspecialchars($data['direction'], ENT_QUOTES);
    //     $data['exchange'] = htmlspecialchars($data['exchange'], ENT_QUOTES);

    //     //檢查ncToken是否存在
    //     $sysSignal = SysSignal::where('ncToken', $data['ncToken'])->first();
        
    //     if( is_null($sysSignal) | $sysSignal->status == 1 ){
    //         $rData['code'] = "-103";
    //         $rData['msg'] ="無Token或Token停用";
    //         return json_encode($rData);

    //     }

    //     if(Carbon::now()->gt(Carbon::parse($sysSignal->expired_at)) ){
    //         $rData['code'] = "-104";
    //         $rData['msg'] ="驗證token無效，已過期";
    //         return json_encode($rData);
    //     }

    //     //檢查資料格式 - ktype
    //     if(strlen($data['kType']) > 10){
    //         $rData['code'] = "-105";
    //         $rData['msg'] = "ktype長度大於10";
    //         return json_encode($rData);
    //     }
        
    //     //檢查資料格式 - token
    //     if(strlen($data['token']) > 10){
    //         $rData['code'] = "-106";
    //         $rData['msg'] = "token長度大於10";
    //         return json_encode($rData);
    //     }
        
    //     //檢查資料格式 - price
    //     if(strlen($data['price']) > 20){
    //         $rData['code'] = "-107";
    //         $rData['msg'] ="price長度大於10";
    //         return json_encode($rData);
    //     }
        
    //     //檢查資料格式 - timeFrame 15m 30m 1h 4h 12h day week
    //     if(strlen($data['timeFrame']) > 4 |
    //         ($data['timeFrame'] != "15m" & 
    //         $data['timeFrame'] != "30m" &
    //         $data['timeFrame'] != "1h" &
    //         $data['timeFrame'] != "2h" &
    //         $data['timeFrame'] != "4h" & 
    //         $data['timeFrame'] != "6h" &
    //         $data['timeFrame'] != "12h" &
    //         $data['timeFrame'] != "day" & 
    //         $data['timeFrame'] != "week")
    //     ){
    //         $rData['code'] = "-108";
    //         $rData['msg'] ="timeFrame長度大於4或非指定參數";
    //         return json_encode($rData);
    //     }
        
    //     //檢查資料格式 - direction
    //     if(strlen($data['direction']) > 4 | ($data['direction'] != "sell" & $data['direction'] != "buy" )){
    //         $rData['code'] = "-109";
    //         $rData['msg'] ="direction長度大於4或非指定參數";
    //         return json_encode($rData);
    //     }
        
    //     //檢查資料格式 - exchange
    //     if(strlen($data['exchange']) > 10 | 
    //         ($data['exchange'] != "binance" &
    //         $data['exchange'] != "ftx")
    //     ){
            
    //         $rData['code'] = "-110";
    //         $rData['msg'] ="exchange長度大於10或非指定參數";
    //         return json_encode($rData);
    //     }

    //     //寫入資料庫
    //     $signal = new SysSignalLog();
    //     $signal->kType = $data['kType'];
    //     $signal->token = $data['token'];
    //     $signal->sigId = $sysSignal->id;
    //     $signal->price = $data['price'];
    //     $signal->timeFrame = $data['timeFrame'];
    //     $signal->direction = $data['direction'];
    //     $signal->exchange = $data['exchange'];
    //     $signal->save();

    //     $rData['code'] = "200";
    //     $rData['msg'] = "";
    //     return json_encode($rData);


    // }

}
