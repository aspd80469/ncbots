<?php

namespace App\Http\Controllers;

use App\Models\SysSignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;


class SysSignalController extends Controller
{

    //[管理][訊號Token]
    public function index()
    {

        //[主頁]

        //搜尋條件
        $sysSignals =  SysSignal::orderBy('created_at', 'DESC')->paginate(20);

        return view('mge/mge_sysSignals', [
            'sysSignals' => $sysSignals,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(), [
            'ncToken' => 'required|string',
            'tdsec' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $sysSignal = new SysSignal;
        $sysSignal->ncToken = htmlspecialchars($request->input('ncToken'), ENT_QUOTES);
        $sysSignal->tdsec = htmlspecialchars($request->input('tdsec'), ENT_QUOTES);
        $sysSignal->expired_at = htmlspecialchars($request->input('expired_at'), ENT_QUOTES);
        $sysSignal->onlyStgyIds = htmlspecialchars($request->input('onlyStgyIds'), ENT_QUOTES);
        $sysSignal->status = htmlspecialchars($request->input('status'), ENT_QUOTES);

        if ($sysSignal->save()) {
            Session::flash('alert-success', '新增訊號Token成功');
        } else {
            Session::flash('alert-danger', '新增訊號Token失敗');
        }

        return redirect('/mge/sysSignals');
    }

    public function edit($id)
    {

        //[編輯]
        $sysSignal = ($id == 0) ? (null) : (SysSignal::findOrFail($id));

        return view('mge/mge_sysSignals_single', [
            'sysSignal' => $sysSignal,
        ]);
    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'ncToken' => 'required|unique:sys_signals,ncToken,' .$id,
            'tdsec' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $sysSignal = SysSignal::findOrFail($id);
        $sysSignal->ncToken = htmlspecialchars($request->input('ncToken'), ENT_QUOTES);
        $sysSignal->tdsec = htmlspecialchars($request->input('tdsec'), ENT_QUOTES);
        if( !is_null(htmlspecialchars($request->input('expired_at'), ENT_QUOTES)) && !empty(htmlspecialchars($request->input('expired_at'), ENT_QUOTES)) ){
            $sysSignal->expired_at = htmlspecialchars($request->input('expired_at'), ENT_QUOTES);
        }
        $sysSignal->onlyStgyIds = htmlspecialchars($request->input('onlyStgyIds'), ENT_QUOTES);
        $sysSignal->status = htmlspecialchars($request->input('status'), ENT_QUOTES);
        
        if ($sysSignal->save()) {
            Session::flash('alert-success', '訊號Token更新成功');
        } else {
            Session::flash('alert-dangeer', '訊號Token更新失敗');
        }
        return redirect('/mge/sysSignals');
    }

    public function destroy($id)
    {

        //[刪除]
        $sysSignal = SysSignal::findOrFail($id);

        //刪除
        Session::flash('alert-danger', '已成功刪除訊號Token ' . $sysSignal->ncToken . ':' . $sysSignal->tdsec);
        SysSignal::destroy($id);

        return redirect('/mge/sysSignals');
    }
}
