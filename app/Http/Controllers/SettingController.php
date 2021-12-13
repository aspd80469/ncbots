<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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


    //[管理][系統參數]
    public function index()
    {

        //[主頁]

        //搜尋條件
        $settings =  Setting::orderBy('id', 'ASC')->paginate(20);

        return view('mge/mge_settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        //[編輯]
        $setting = ($id == 0) ? (null) : (Setting::findOrFail($id));

        return view('mge/mge_settings_single', [
            'setting' => $setting,
        ]);

    }

    public function update(Request $request, $id)
    {

         //[更新]
         $validator = Validator::make($request->all(), [
            'name' => 'required|unique:setting,name,' . $id,
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $setting = Setting::findOrFail($id);
        $setting->name = htmlspecialchars($request->input('name'), ENT_QUOTES);
        $setting->value = htmlspecialchars($request->input('value'), ENT_QUOTES);
        $setting->sdesc = htmlspecialchars($request->input('sdesc'), ENT_QUOTES);
        
        if ($setting->save()) {
            Session::flash('alert-success', '參數更新成功');
        } else {
            Session::flash('alert-dangeer', '參數更新失敗');
        }
        return redirect('/mge/settings');
    }

    public function riskNotice_edit()
    {

        //[編輯]
        $setting = ($id == 0) ? (null) : (Setting::findOrFail($id));

        return view('mge/mge_riskNotice_single', [
            'setting' => $setting,
        ]);

    }
    

}
