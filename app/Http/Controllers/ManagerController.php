<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Hash;
use Auth;


class ManagerController extends Controller
{

    //[管理][管理帳號]
    public function index()
    {

        //[主頁]
        $managers =  Manager::orderBy('created_at', 'DESC')->paginate(20);

        return view('mge/mge_managers', [
            'managers' => $managers,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(), [
            'account' => 'required|unique:managers,account',
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $manager = new Manager;
        $manager->account = htmlspecialchars($request->input('account'), ENT_QUOTES);
        $manager->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));

        if ($manager->save()) {
            Session::flash('alert-success', '建立管理帳號成功');
        } else {
            Session::flash('alert-danger', '建立管理帳號失敗');
        }

        return redirect('/mge/managers');
    }

    public function edit($id)
    {

        //[編輯]
        $manager = ($id == 0) ? (null) : (Manager::findOrFail($id));

        return view('mge/mge_managers_single', [
            'manager' => $manager,
        ]);
    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'account' => 'required|unique:managers,account,' . $id,
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $manager = Manager::findOrFail($id);
        $manager->account = htmlspecialchars($request->input('account'), ENT_QUOTES);

        if( htmlspecialchars($request->input('password'), ENT_QUOTES) !="" ){
            $manager->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));
        }

        
        if ($manager->save()) {
            Session::flash('alert-success', '管理帳號更新成功');
        } else {
            Session::flash('alert-dangeer', '管理帳號更新失敗');
        }
        return redirect('/mge/managers');
    }

    public function destroy($id)
    {

        //[刪除]
        $manager = Manager::findOrFail($id);

        if ( $manager->id == 1) {
            Session::flash('alert-danger', '你不能刪除系統管理帳號：' . $manager->name . ':' . $manager->account);
        }else{

            //刪除帳號
            Session::flash('alert-danger', '已成功刪除管理帳號：' . $manager->name . ':' . $manager->account);
            Manager::destroy($id);
        }

        return redirect('/mge/managers');
    }
}
