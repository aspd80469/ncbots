<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Hash;
use DB;

class UserController extends Controller
{

    //[分店][帳號]
    public function index()
    {

        //[主頁]

        //搜尋條件
        $users = User::orderBy('created_at', 'DESC')->paginate(20);
        
        return view('mge/mge_user',[
            'users'=>$users,
        ]);

    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        $user = new User;
        $user->username = htmlspecialchars($request->input('username'), ENT_QUOTES);
        $user->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));
        
        if ( $user->save() )
        {
            Session::flash('alert-success', '已成功建立帳號');
        }
        else
        {
            Session::flash('alert-danger', '建立帳號失敗');
        }

        return redirect('mge/user/' . $user->storeid);

    }

    public function edit($id, $storeid)
    {

        //[編輯]
        $user = ( $id == 0 ) ? ( null ) : ( User::findOrFail($id) );

        return view('mge_user_single',[
            'user'=> $user ,
        ]);

    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(),[
            'username' => 'required|unique:users,username,'.$id,
            'password' => 'nullable|min:6',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        $user = User::findOrFail($id);
        $user->username = htmlspecialchars($request->input('username'), ENT_QUOTES);
        if( htmlspecialchars($request->input('password'), ENT_QUOTES) !="" ){
            $user->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));
        }

        $user->userrole = htmlspecialchars($request->input('userrole'), ENT_QUOTES);

        if( $user->save() )
        {
            Session::flash('alert-success', '已更新帳號');
        }
        else
        {
            Session::flash('alert-dangeer', '帳號更新失敗');
        }
        return redirect('mge/user/'. $user->storeid );

    }

    public function destroy($id)
    {
        
        //[刪除]
        User::destroy($id);
        return redirect('mge/users' );

    }
    
}