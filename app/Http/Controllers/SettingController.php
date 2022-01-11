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

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(), [
            'sname' => 'required|string|max:255',
            'svalue' => 'nullable|string',
            'sdesc' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $setting = new Setting;
        $setting->name = htmlspecialchars($request->input('sname'), ENT_QUOTES);
        $setting->value = htmlspecialchars($request->input('svalue'), ENT_QUOTES);
        $setting->sdesc = htmlspecialchars($request->input('sdesc'), ENT_QUOTES);

        if ($setting->save()) {
            Session::flash('alert-success', '建立參數成功');
        } else {
            Session::flash('alert-danger', '建立參數失敗');
        }

        return redirect('/mge/settings');

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
            'sname' => 'required|string|max:255',
            'svalue' => 'nullable|string',
            'sdesc' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $setting = Setting::findOrFail($id);
        $setting->name = htmlspecialchars($request->input('sname'), ENT_QUOTES);
        $setting->value = htmlspecialchars($request->input('svalue'), ENT_QUOTES);
        $setting->sdesc = htmlspecialchars($request->input('sdesc'), ENT_QUOTES);
        
        if ($setting->save()) {
            Session::flash('alert-success', '參數更新成功');
        } else {
            Session::flash('alert-dangeer', '參數更新失敗');
        }
        return redirect('/mge/settings');
    }

    public function destroy($id)
    {
        
        //[刪除]
        $setting = Setting::where('sysDefPara' , 0 )->find($id);
        if(is_null($setting)){
            Session::flash('alert-danger', '無法刪除系統參數：' . $setting->name );
            return redirect('/mge/settings');
        }

        Setting::destroy($id);
        Session::flash('alert-danger', '已成功刪除系統參數：' . $setting->name );

        return redirect('/mge/settings');
    }

    //[管理][系統狀態
    public function sysStatus()
    {

        //[主頁]
        return view('mge/mge_sysStatus', [
        ]);
    }

}
