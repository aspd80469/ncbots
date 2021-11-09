<?php

namespace App\Http\Controllers;

use App\Models\SysSignal;
use App\Models\SysSignalLog;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Carbon;

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
            !array_key_exists( "ncToken" , $data) |
            !array_key_exists( "token" , $data) |
            !array_key_exists( "price" , $data) |
            !array_key_exists( "timeFrame" , $data) |
            !array_key_exists( "direction" , $data) |
            !array_key_exists( "exchange" , $data) 
        ){

            $rData['code'] = "-102";
            $rData['msg'] ="資料格式不正確";
            return json_encode($rData);

        }
        
        //過濾html
        $data['ncToken'] = htmlspecialchars($data['ncToken'], ENT_QUOTES);
        $data['token'] = htmlspecialchars($data['token'], ENT_QUOTES);
        $data['price'] = htmlspecialchars($data['price'], ENT_QUOTES);
        $data['timeFrame'] = htmlspecialchars($data['timeFrame'], ENT_QUOTES);
        $data['direction'] = htmlspecialchars($data['direction'], ENT_QUOTES);
        $data['exchange'] = htmlspecialchars($data['exchange'], ENT_QUOTES);

        //檢查ncToken是否存在
        $sysSignal = SysSignal::where('ncToken', $data['ncToken'])->first();
        if(is_null($sysSignal) | Carbon::now()->gt(Carbon::parse($sysSignal->exp_time)) ){
            $rData['code'] = "-103";
            $rData['msg'] ="驗證token無效";
            return json_encode($rData);
        }
        
        //檢查資料格式 - token
        if(strlen($data['token']) > 10){
            $rData['code'] = "-110";
            $rData['msg'] = "token長度大於10";
            return json_encode($rData);
        }
        
        //檢查資料格式 - price
        if(strlen($data['price']) > 20){
            $rData['code'] = "-111";
            $rData['msg'] ="price長度大於10";
            return json_encode($rData);
        }
        
        //檢查資料格式 - timeFrame 15m 30m 1h 4h 12h day week
        if(strlen($data['timeFrame']) > 4 |
            $data['direction'] != "15m" | 
            $data['direction'] != "30m" | 
            $data['direction'] != "1h" | 
            $data['direction'] != "4h" | 
            $data['direction'] != "day" | 
            $data['direction'] != "week"
        ){
            $rData['code'] = "-112";
            $rData['msg'] ="timeFrame長度大於4或非指定參數";
            return json_encode($rData);
        }
        
        //檢查資料格式 - direction
        if(strlen($data['direction']) > 4 | $data['direction'] != "sell" | $data['direction'] != "buy"){
            $rData['code'] = "-113";
            $rData['msg'] ="direction長度大於4或非指定參數";
            return json_encode($rData);
        }
        
        //檢查資料格式 - exchange
        if(strlen($data['exchange']) > 10 | 
            $data['exchange'] != "binance" | 
            $data['exchange'] != "ftx"
        ){
            
            $rData['code'] = "-114";
            $rData['msg'] ="exchange長度大於10或非指定參數";
            return json_encode($rData);
        }

        //寫入資料庫
        $signal = SysSignalLog::new();
        $signal->token = $data['token'];
        $signal->price = $data['price'];
        $signal->timeFrame = $data['timeFrame'];
        $signal->direction = $data['direction'];
        $signal->exchange = $data['exchange'];
        $signal->save();
        
        //加入Queene or 直接下單
        

        $rData['code'] = "200";
        $rData['msg'] = "";
        return json_encode($rData);


    }

}
