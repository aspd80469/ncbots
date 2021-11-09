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

        //啟用TRC20付款
        $allowUserPlanPayByTRC20  = $this->settingService->getSetting('allowUserPlanPayByTRC20');

        //啟用ERC20付款
        $allowUserPlanPayByERC20  = $this->settingService->getSetting('allowUserPlanPayByERC20');

        //系統主錢包地址(TRC20)
        $sysMainWalletTRC20  = $this->settingService->getSetting('sysMainWalletTRC20');

        //系統主錢包地址(ERC20)
        $sysMainWalletERC20  = $this->settingService->getSetting('sysMainWalletERC20');

        if( is_null($allowRegister) ){
            $this->settingService->createSetting('allowRegister' , 'N');
        }

        if( is_null($requiredRefCode) ){
            $this->settingService->createSetting('requiredRefCode' , 'N');
        }

        if( is_null($allowUserPlanPayByTRC20) ){
            $this->settingService->createSetting('allowUserPlanPayByTRC20' , 'N');
        }

        if( is_null($allowUserPlanPayByERC20) ){
            $this->settingService->createSetting('allowUserPlanPayByERC20' , 'N');
        }

        if( is_null($sysMainWalletTRC20) ){
            $this->settingService->createSetting('sysMainWalletTRC20' , '');
        }

        if( is_null($sysMainWalletERC20) ){
            $this->settingService->createSetting('sysMainWalletERC20' , '');
        }
        

        return view('mge/mge_settings', [
            'allowRegister' => $allowRegister,
            'requiredRefCode' => $requiredRefCode,
            'allowUserPlanPayByTRC20' => $allowUserPlanPayByTRC20,
            'allowUserPlanPayByERC20' => $allowUserPlanPayByERC20,
            'sysMainWalletTRC20' => $sysMainWalletTRC20,
            'sysMainWalletERC20' => $sysMainWalletERC20,
        ]);

    }

    public function update(Request $request)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'allowRegister' => 'required|string|max:1',
            'requiredRefCode' => 'required|string|max:1',
            'allowUserPlanPayByTRC20' => 'required|string|max:1',
            'allowUserPlanPayByERC20' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        //設定資料
        $this->settingService->updateSetting('allowRegister' , htmlspecialchars($request->input('allowRegister'), ENT_QUOTES));
        $this->settingService->updateSetting('requiredRefCode' , htmlspecialchars($request->input('requiredRefCode'), ENT_QUOTES));
        $this->settingService->updateSetting('allowUserPlanPayByTRC20' , htmlspecialchars($request->input('allowUserPlanPayByTRC20'), ENT_QUOTES));
        $this->settingService->updateSetting('allowUserPlanPayByERC20' , htmlspecialchars($request->input('allowUserPlanPayByERC20'), ENT_QUOTES));
        $this->settingService->updateSetting('sysMainWalletTRC20' , htmlspecialchars($request->input('sysMainWalletTRC20'), ENT_QUOTES));
        $this->settingService->updateSetting('sysMainWalletERC20' , htmlspecialchars($request->input('sysMainWalletERC20'), ENT_QUOTES));

        Session::flash('alert-success', '設定更新成功');

        return redirect('mge/settings');
    }

}
