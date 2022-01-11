<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MyBot;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use NotificationChannels\Telegram\TelegramUpdates;
use Session;
use Hash;

class UserController extends Controller
{

    
    //[帳號]
    public function init_user()
    {

        $iniUser = User::where('email', "test@gmail.com")->first();
        if(!is_null($iniUser)){
            $iniUser->password = Hash::make("a123456");
            $iniUser->save();
            Session::flash('alert-success', 'test@gmail.com帳號重置密碼成功');

        }else{

            $user = new User;
            $user->email = htmlspecialchars("test@gmail.com");
            $user->password = Hash::make("a123456");
            $user->save();
            Session::flash('alert-success', '已成功建立帳號');
        }
        
        return redirect('login' );

    }

    //[帳號]
    public function index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $users = User::query();

        $s_email = htmlspecialchars($request->input('s_email'), ENT_QUOTES);
        $s_name = htmlspecialchars($request->input('s_name'), ENT_QUOTES);
        $s_notice = htmlspecialchars($request->input('s_notice'), ENT_QUOTES);

        $request->flash();
        
        if ($s_email != '') {
            $users = $users->where('email', 'LIKE', '%' . $s_email . '%');
        }

        if ($s_name != '') {
            $users = $users->where('name', 'LIKE', '%' . $s_name . '%');
        }

        if ($s_notice != '') {
            $users = $users->where('notice', 'LIKE', '%' . $s_notice . '%');
        }

        $users = $users->orderBy('created_at', 'DESC')->paginate(20);
        
        return view('mge/mge_user',[
            'users'=>$users,
        ]);

    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(),[
            'email' => 'required|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
            'name' => 'nullable|string',
            'notice' => 'nullable|string',
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
        $user->name = htmlspecialchars($request->input('name'), ENT_QUOTES);
        $user->tgId = htmlspecialchars($request->input('tgId'), ENT_QUOTES);
        $user->status = htmlspecialchars($request->input('status'), ENT_QUOTES);
        $user->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);
        
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

    public function edit($id = 0)
    {

        //[編輯]
        $user = ( $id == 0 ) ? ( null ) : ( User::findOrFail($id) );

        return view('mge/mge_user_single',[
            'user'=> $user ,
        ]);

    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(),[
            'email' => 'required|unique:users,email,'.$id,
            'password' => 'nullable|min:6',
            'name' => 'nullable|string',
            'notice' => 'nullable|string',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        //get tgId
        //$chatId = '';
        // Response is an array of updates.
        //$updates = TelegramUpdates::create()
        // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
        // ->latest()

        // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).
        //->limit(2)

        // (Optional). Add more params to the request.
        //->options([
        //    'timeout' => 0,
        //])
        //->get();

        // if($updates['ok']) {
        // // Chat ID
        //     $chatId = $updates['result'][0]['message']['chat']['id'];
        // }

        $user = User::findOrFail($id);
        $user->email = htmlspecialchars($request->input('email'), ENT_QUOTES);
        if( htmlspecialchars($request->input('password'), ENT_QUOTES) !="" ){
            $user->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));
        }

        $user->name = htmlspecialchars($request->input('name'), ENT_QUOTES);
        $user->tgId = htmlspecialchars($request->input('tgId'), ENT_QUOTES);
        $user->status = htmlspecialchars($request->input('status'), ENT_QUOTES);
        $user->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);

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

        //檢查是否有機器人，必須先刪除機器人
        $myBoyCount = MyBot::where('userid', $id)->count();
        if( $myBoyCount != 0 ){
            Session::flash('alert-warning', '無法刪除會員，請先移除會員所有機器人');
            return redirect('mge/users');
        }

        User::destroy($id);
        return redirect('mge/users');

    }


    public function userPwdReset($id)
    {
        
        $user = User::findOrFail($id);
        $user->password = Hash::make($user->email);
        $user->save();

        Session::flash('alert-success', '已重置帳號:' . $user->email . "，密碼:" . $user->email . "成功");
        return redirect('mge/users');

    }

    //[會員下單紀錄]
    public function orderIndex(Request $request)
    {

        //[主頁]

        //搜尋條件
        $users = User::query();

        $s_email = htmlspecialchars($request->input('s_email'), ENT_QUOTES);
        $s_notice = htmlspecialchars($request->input('s_notice'), ENT_QUOTES);

        $request->flash();
        
        if ($s_email != '') {
            $users = $users->where('email', 'LIKE', '%' . $s_email . '%');
        }

        if ($s_notice != '') {
            $users = $users->where('notice', 'LIKE', '%' . $s_notice . '%');
        }

        $users = $users->orderBy('created_at', 'DESC')->paginate(20);
        
        return view('mge/mge_orders',[
            'users'=>$users,
        ]);

    }

    public function orderbyUser_index(Request $request, $id)
    {

        //[主頁][檢視下單紀錄]by User

        //搜尋條件
        $orders = Order::query();

        // $s_symbol = htmlspecialchars($request->input('s_symbol'), ENT_QUOTES);
        // $s_qty = htmlspecialchars($request->input('s_qty'), ENT_QUOTES);
        // $s_exchange = htmlspecialchars($request->input('s_exchange'), ENT_QUOTES);
        // $s_direction = htmlspecialchars($request->input('s_direction'), ENT_QUOTES);

        // $request->flash();

        // if ($s_symbol != '') {
        //     $orderLogs = $orderLogs->where('symbol', 'LIKE', '%' . $s_symbol . '%');
        // }

        // if ($s_qty != '') {
        //     $orderLogs = $orderLogs->where('qty', 'LIKE', '%' . $s_qty . '%');
        // }
        
        // if ($s_exchange != '') {
        //     $orderLogs = $orderLogs->where('exchange', 'LIKE', '%' . $s_exchange . '%');
        // }

        // if ($s_direction != '') {
        //     $orderLogs = $orderLogs->where('direction', 'LIKE', '%' . $s_direction . '%');
        // }

        $orders = $orders->orderBy('created_at', 'DESC')->paginate(50);
        
        return view('mge/mge_ordersByUser',[
            'orders'=>$orders,
        ]);

    }

    public function myBotsbyUser_index(Request $request, $id)
    {

        //[主頁][檢視機器人列表]by User

        //搜尋條件
        $myBots = MyBot::where('userid' , $id);

        // $s_symbol = htmlspecialchars($request->input('s_symbol'), ENT_QUOTES);
        // $s_qty = htmlspecialchars($request->input('s_qty'), ENT_QUOTES);
        // $s_exchange = htmlspecialchars($request->input('s_exchange'), ENT_QUOTES);
        // $s_direction = htmlspecialchars($request->input('s_direction'), ENT_QUOTES);

        // $request->flash();

        // if ($s_symbol != '') {
        //     $orderLogs = $orderLogs->where('symbol', 'LIKE', '%' . $s_symbol . '%');
        // }

        // if ($s_qty != '') {
        //     $orderLogs = $orderLogs->where('qty', 'LIKE', '%' . $s_qty . '%');
        // }
        
        // if ($s_exchange != '') {
        //     $orderLogs = $orderLogs->where('exchange', 'LIKE', '%' . $s_exchange . '%');
        // }

        // if ($s_direction != '') {
        //     $orderLogs = $orderLogs->where('direction', 'LIKE', '%' . $s_direction . '%');
        // }

        $myBots = $myBots->orderBy('created_at', 'DESC')->paginate(50);
        
        return view('mge/mge_myBotsByUser',[
            'myBots'=>$myBots,
        ]);

    }

    //////////////////////////////////////////////////////////////////////
    //燃料費
    //////////////////////////////////////////////////////////////////////

    //[管理][燃料費]
    public function userBurnMoney_index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $news = News::query();
        $s_title = htmlspecialchars($request->input('s_title'), ENT_QUOTES);
        $s_content = htmlspecialchars($request->input('s_content'), ENT_QUOTES);

        $request->flash();

        if ($s_title != '') {
            $news->where('title', 'LIKE', '%' . $s_title . '%');
        }

        if ($s_content != '') {
            $news->where('content', 'LIKE', '%' . $s_content . '%');
        }

        $news = $news->orderBy('created_at', 'DESC')->paginate(20);

        return view('mge/mge_news', [
            'news' => $news,
        ]);
    }

    public function userBurnMoney_edit($id)
    {

        //[編輯]
        $user = User::find($id);

        return view('mge/mge_user_burnMoney_single', [
            'user' => $user,
        ]);
    }

    public function userBurnMoney_update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'typeStatus' => 'required|numeric|min:0|max:1',
            'burnMoney' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($id);

        if( htmlspecialchars($request->input('typeStatus'), ENT_QUOTES) == 0 ){
            //增加
            $user->burnMoney += htmlspecialchars($request->input('burnMoney'), ENT_QUOTES);
        }else{
            //減少
            $user->burnMoney -= htmlspecialchars($request->input('burnMoney'), ENT_QUOTES);
        }

        if ($user->save()) {
            Session::flash('alert-success', $user->email . ' 會員燃料費更新成功');
        } else {
            Session::flash('alert-danger', '會員燃料費更新失敗');
        }
        return redirect('/mge/users/');
    }

    
}