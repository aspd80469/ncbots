<?php

namespace App\Http\Controllers;

use App\Models\SysSignal;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;


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
        $rdata = array();

        //剖析json
        $data = $request->json()->all();

        //檢查data格式是否正確
        if( empty($data) | is_null($data) ){

            $rdata['msg'] = "資料格式不正確";
            return json_encode($rdata);
        }

        if( empty($data['ncToken']) | is_null($data['ncToken']) ){
            $rdata['msg'] ="沒有有效的驗證";
            return json_encode($rdata);
        }

        //檢查token是否存在
        if (SysSignal::where('ncToken', $data['ncToken'])->first()){
            $rdata['msg'] ="驗證Token無效";
            return json_encode($rdata);
        }

        //
        if( !array_key_exists( "token" , $data) |
            !array_key_exists( "price" , $data) |
            !array_key_exists( "timeFrame" , $data) |
            !array_key_exists( "direction" , $data) |
            !array_key_exists( "exchange" , $data) 
        ){

            $rdata['msg'] ="欄位驗證無效";
            return json_encode($rdata);

        }

        //寫入資料庫
        $signal = SysSignal::new();
        $signal->token = $data['token'];
        $signal->price = $data['price'];
        $signal->timeFrame = $data['timeFrame'];
        $signal->direction = $data['direction'];
        $signal->exchange = $data['exchange'];
        $signal->save();

        $rdata['msg'] = "200";
        return json_encode($rdata);


    }

}
