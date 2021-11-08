<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class SettingController extends Controller
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
    public function edit()
    {
        //設定資料

        //是否允許新會員註冊
        $allowRegister  = $this->settingService->getSetting('allowRegister');

        //是否註冊推薦碼必填
        $requiredRefCode  = $this->settingService->getSetting('requiredRefCode');

        if( is_null($allowRegister) ){
            $this->settingService->createSetting('allowRegister' , 'N');
        }

        if( is_null($requiredRefCode) ){
            $this->settingService->createSetting('requiredRefCode' , 'N');
        }
        

        return view('mge/mge_settings', [
            'allowRegister' => $allowRegister,
            'requiredRefCode' => $requiredRefCode,
        ]);

    }

    public function update(Request $request)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'allowRegister' => 'required|string|max:1',
            'requiredRefCode' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        //設定資料
        $this->settingService->updateSetting('allowRegister' , htmlspecialchars($request->input('allowRegister'), ENT_QUOTES));
        $this->settingService->updateSetting('requiredRefCode' , htmlspecialchars($request->input('requiredRefCode'), ENT_QUOTES));

        Session::flash('alert-success', '設定更新成功');

        return redirect('mge/settings');
    }

}
