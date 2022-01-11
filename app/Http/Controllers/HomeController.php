<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use App\Models\User;
use App\Models\UserActPlan;
use App\Models\UserPlan;
use App\Models\UserKey;
use App\Models\BotsStgy;
use App\Models\MyBot;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotificationTelegram;
use Carbon\Carbon;
use Session;
use Hash;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingService $settingService)
    {
        $this->middleware('auth');
        $this->settingService = $settingService;
    }

    public function dashboard()
    {

        // Notification::route('telegram', '378160546')
        //     ->notify(new NotificationTelegram("378160546"));

        $a =array();
        $b = array('789','456','123');
        array_push($a , $b);
        

        //新聞
        $news = News::where('display' , '0')->orderBy('created_at', 'DESC')->get();

        //[會員主頁]
        return view('dashboard',[
            'news' => $news,
        ]);

    }

    public function news($id)
    {

        //新聞
        $news = News::findOrFail($id);

        //[會員主頁]
        return view('news_single',[
            'news' => $news,
        ]);

    }

    public function profile()
    {

        //[會員資料]
        return view('profile',[
        ]);

    }

    public function profile_update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sname' => 'required|max:20',
            'password' => 'nullable|min:6',
            'tgId' => 'nullable|numeric|max:20',
            
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }


        $user = User::find(Auth::user()->id);
        $user->name = htmlspecialchars($request->input('sname'), ENT_QUOTES);

        if( htmlspecialchars($request->input('password'), ENT_QUOTES) !="" ){
            $user->password = Hash::make(htmlspecialchars($request->input('password'), ENT_QUOTES));
        }

        $user->tgId = htmlspecialchars($request->input('tgId'), ENT_QUOTES);

        //[會員資料]
        if ($user->save()) {
            Session::flash('alert-success', '更新會員資料成功');
        } else {
            Session::flash('alert-dangeer', '更新會員資料失敗');
        }
        return redirect('/profile');

    }

    public function myBots()
    {
        $today = Carbon::now();
        $actUserPlan = UserActPlan::where('userid' , Auth::User()->id)
        ->where('status', 2)
        ->whereDate('takeDate','<=', $today)
        ->whereDate('edDate','>=', $today)->first();

        $myBots = MyBot::where('userid' , Auth::User()->id)->get();

        foreach( $myBots as $myBot){
            $myBot->field_1 = unserialize($myBot->field_1);
            $myBot->field_2 = unserialize($myBot->field_2);
            $myBot->field_3 = unserialize($myBot->field_3);
        }

        //[我的機器人]
        return view('myBots',[
            'actUserPlan' => $actUserPlan,
            'myBots' => $myBots,
        ]);

    }

    public function myBots_edit($id)
    {

        //[我的機器人][編輯]
        $myBot = ($id == 0) ? (null) : (MyBot::findOrFail($id));
        $botsStgys = BotsStgy::all();
        $userKeys = UserKey::where('userid' , Auth::User()->id )->get();

        if( $botsStgys->count() == 0 | $userKeys->count() == 0  ){
            Session::flash('alert-warning', '目前無可用策略或是沒有設定API KEY');
            return redirect('/myBots');
        }

        return view('myBots_single', [
            'myBot' => $myBot,
            'botsStgys' => $botsStgys,
            'userKeys' => $userKeys,
        ]);

    }

    public function myBots_store(Request $request)
    {

        //[我的機器人][更新]
        $validator = Validator::make($request->all(), [
            'invAmount' => 'required|numeric|min:0',
            'usedStgy' => 'required|numeric|min:0',
            'apiKeyId1' => 'required|numeric|min:0',
            'apiKeyId2' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        //檢查有介於於使用期間內的方案
        $today = Carbon::now();
        $actUserPlan = UserActPlan::where('userid' , Auth::User()->id)
                                    ->where('status', 2)
                                    ->whereDate('takeDate','<=', $today)
                                    ->whereDate('edDate','>=', $today)->first();

        if(is_null($actUserPlan)){
            Session::flash('alert-warning', '新增機器人失敗，您目前沒有可用的會員方案');
            return redirect('/myBots');
        }
        
        //計算目前已有機器人數量
        $myBotCount = MyBot::where('userid' , Auth::User()->id)->count();
        //計算目前方案可以使用的機器人最大數量
        $userPlanInfo = UserPlan::where('id', $actUserPlan->getUserPlan->id)->first();

        if( $myBotCount >= $userPlanInfo->maxBotQty ){
            Session::flash('alert-warning', '新增機器人失敗，您目前機器人數量已超過目前方案限制，請先減少機器人數量再新增');
            return redirect('/myBots');
        }

        //檢查總投資金額是否超過目前方案限制
        $myBotInvAmountSum = MyBot::where('userid' , Auth::User()->id)->sum('invAmount') + htmlspecialchars($request->input('invAmount'), ENT_QUOTES);
        if( $myBotInvAmountSum > $userPlanInfo->maxAmount ){
            Session::flash('alert-warning', '新增機器人失敗，目前總投資金額超過上限，請減少投資金額');
            return redirect('/myBots');
        }

        $myBot = new MyBot();
        $myBot->userid = Auth::User()->id;
        $myBot->invAmount = htmlspecialchars($request->input('invAmount'), ENT_QUOTES);
        $myBot->usedStgy = htmlspecialchars($request->input('usedStgy'), ENT_QUOTES);

        //找到原本的api key更新成未使用，新的改為已使用
        $nkey1 = UserKey::find(htmlspecialchars($request->input('apiKeyId1'), ENT_QUOTES));
        if( !is_null($nkey1) ){
            $nkey1->botUsed = 1;
            $nkey1->save();
        }

        $nkey2 = UserKey::find(htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES));
        if (!is_null($nkey2)) {
            $nkey2->botUsed = 1;
            $nkey2->save();
        }

        $myBot->apiKeyId1 = htmlspecialchars($request->input('apiKeyId1'), ENT_QUOTES);

        if( !is_null(htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES)) && !empty(htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES)) ){
            $myBot->apiKeyId2 = htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES);
        }

        if ($myBot->save()) {
            Session::flash('alert-success', '新增機器人成功');
        } else {
            Session::flash('alert-danger', '新增機器人失敗');
        }
        
        return redirect('/myBots');
    }

    public function myBots_update(Request $request,$id)
    {

        //[我的機器人][更新]
        $validator = Validator::make($request->all(), [
            'invAmount' => 'required|numeric|min:0',
            'usedStgy' => 'required|numeric|min:0',
            'apiKeyId1' => 'required|numeric|min:0',
            'apiKeyId2' => 'nullbale|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        //檢查有介於於使用期間內的方案
        $today = Carbon::now();
        $actUserPlan = UserActPlan::where('userid' , Auth::User()->id)
                                    ->where('status', 2)
                                    ->whereDate('takeDate','<=', $today)
                                    ->whereDate('edDate','>=', $today)->first();

        if(is_null($actUserPlan)){
            Session::flash('alert-warning', '更新機器人失敗，您目前沒有可用的會員方案');
            return redirect('/myBots');
        }

        //檢查總投資金額是否超過目前方案限制
        $userPlanInfo = UserPlan::where('id', $actUserPlan->getUserPlan->id)->first();
        $myBotInvAmountSum = MyBot::where('userid' , Auth::User()->id)->sum('invAmount') + htmlspecialchars($request->input('invAmount'), ENT_QUOTES);
        if( $myBotInvAmountSum > $userPlanInfo->maxAmount ){
            Session::flash('alert-warning', '更新機器人失敗，目前總投資金額超過上限，請減少投資金額');
            return redirect('/myBots');
        }

        $myBot = MyBot::findOrFail($id);
        $myBot->userid = Auth::User()->id;
        $myBot->invAmount = htmlspecialchars($request->input('invAmount'), ENT_QUOTES);
        $myBot->usedStgy = htmlspecialchars($request->input('usedStgy'), ENT_QUOTES);

        //找到原本的api key更新成未使用，新的改為已使用
        $orgKey1 = $myBot->apiKeyId1;
        $orgKey2 = $myBot->apiKeyId2;

        $bkey1 = UserKey::find($orgKey1);
        $bkey1->botUsed = 0;
        $bkey1->save();

        $bkey2 = UserKey::find($orgKey2);
        if( !is_null($bkey2) ){
            $bkey2->botUsed = 0;
            $bkey2->save();
        }
        
        $nkey1 = UserKey::find(htmlspecialchars($request->input('apiKeyId1'), ENT_QUOTES));
        if( !is_null($nkey1) ){
            $nkey1->botUsed = 1;
            $nkey1->save();
        }

        $nkey2 = UserKey::find(htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES));
        if (!is_null($nkey2)) {
            $nkey2->botUsed = 1;
            $nkey2->save();
        }

        $myBot->apiKeyId1 = htmlspecialchars($request->input('apiKeyId1'), ENT_QUOTES);
        $myBot->apiKeyId2 = htmlspecialchars($request->input('apiKeyId2'), ENT_QUOTES);

        if ($myBot->save()) {
            Session::flash('alert-success', '更新機器人成功');
        } else {
            Session::flash('alert-danger', '更新機器人失敗');
        }
        return redirect('/myBots');
    }

    public function myBots_destroy($id)
    {

        //更新API KEY使用狀態


        //[我的機器人][刪除]
        if (MyBot::destroy($id)) {
            Session::flash('alert-success', '刪除機器人成功');
        } else {
            Session::flash('alert-danger', '刪除機器人失敗');
        }
        return redirect('/myBots');
    }

    public function myBotOrders_index($id)
    {

        //[機器人下單紀錄]
        $myBotOrders = Order::where('myBotId' , $id )->orderBy('created_at', 'DESC')->paginate(50);

        return view('myBotOrders',[
            'myBotId' => $id,
            'myBotOrders' => $myBotOrders,
        ]);

    }
    
    public function userPlanRecords()
    {

        //[付費紀錄]
        $userPlanRecords = UserActPlan::where('userid' , Auth::User()->id )->orderBy('created_at', 'DESC')->paginate(50);

        return view('userPlanRecords',[
            'userPlanRecords' => $userPlanRecords,
        ]);

    }

    public function  userPlanRecordsCancel($id){

        $userActPlan = UserActPlan::where('userid' , Auth::User()->id)->where('id' , $id)->first();
        $userActPlan->status = 3;
        $userActPlan->save();
        Session::flash('alert-success', '訂單取消成功');
        return redirect('/userPlanRecords');

    }

    public function userPlans()
    {

        //[會員方案]
        $userPlans = UserPlan::where('enabled' , '1' )->get();

        if( $userPlans->count() == 0 ){
            Session::flash('alert-warning', '目前沒有可供購買的方案');
        }

        return view('userPlans',[
            'userPlans' => $userPlans,
        ]);

    }

    
    public function userPlans_payNewRecord($id)
    {

        //[會員方案][新增付款紀錄]
        $userPlan = UserPlan::find($id);

        //撈取主錢包地址
        $allowUserPlanPayByTRC20 =  $this->settingService->getSetting('allowUserPlanPayByTRC20');
        $allowUserPlanPayByERC20 =  $this->settingService->getSetting('allowUserPlanPayByERC20');
        $sysMainWalletTRC20 =  $this->settingService->getSetting('sysMainWalletTRC20');
        $sysMainWalletERC20 =  $this->settingService->getSetting('sysMainWalletERC20');

        if( is_null($allowUserPlanPayByTRC20)  | 
            is_null($allowUserPlanPayByERC20)  | 
            is_null($sysMainWalletTRC20)  | 
            is_null($sysMainWalletERC20)  
            ){

            Session::flash('alert-warning', '系統參數有問題，請洽系統管理員');

        }

        return view('userPlans_newRecord_single',[
            'userPlan' => $userPlan,
            'allowUserPlanPayByTRC20' => $allowUserPlanPayByTRC20,
            'allowUserPlanPayByERC20' => $allowUserPlanPayByERC20,
            'sysMainWalletTRC20' => $sysMainWalletTRC20,
            'sysMainWalletERC20' => $sysMainWalletERC20,
        ]);

    }

    public function userPlans_payNewRecord_store(Request $request)
    {

        //[會員方案][新增付款紀錄][儲存]
        $validator = Validator::make($request->all(), [
            'planNameId' => 'required|numeric|min:0',
            'paidAmount' => 'required|numeric|min:0',
            'paidTxid' => 'nullbale|string',
            'payMethod' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $userActPlan = new UserActPlan();
        $userActPlan->userid = Auth::user()->id;
        $userActPlan->planID = htmlspecialchars($request->input('planNameId'), ENT_QUOTES);
        $userActPlan->status = 0;
        $userActPlan->applyDate = Carbon::now()->format('Y:m:d H:i:s');
        $userActPlan->paidAmount = htmlspecialchars($request->input('paidAmount'), ENT_QUOTES);
        $userActPlan->paidDate = Carbon::now()->format('Y:m:d H:i:s');
        $userActPlan->paidTxid = htmlspecialchars($request->input('paidTxid'), ENT_QUOTES);
        $userActPlan->payMethod = htmlspecialchars($request->input('payMethod'), ENT_QUOTES);

        if ($userActPlan->save()) {
            Session::flash('alert-success', '填寫申購資訊成功');
        } else {
            Session::flash('alert-danger', '填寫申購資訊更新失敗');
        }
        return redirect('/userPlanRecords');

    }

    public function riskNotice()
    {

        //[風險聲明]
        $sysRiskNoticeText = Setting::where('name', 'sysRiskNoticeText')->first();

        if( !is_null($sysRiskNoticeText))
        {
            $sysRiskNoticeText = $sysRiskNoticeText->value;
        }else{
            $sysRiskNoticeText = "";
        }

        return view('riskNotice',[
            'sysRiskNoticeText' => $sysRiskNoticeText,
        ]);

    }

    public function apiSettings()
    {

        //[api key 設定]
        $apiSettings = UserKey::where('userid', Auth::User()->id )->orderBy('created_at', 'DESC')->paginate(50);

        return view('apiSettings',[
            'apiSettings' => $apiSettings,
        ]);

    }

    public function apiSettings_edit($id)
    {

        //[api key 設定][編輯]
        $apiSetting = ($id == 0) ? (null) : (UserKey::findOrFail($id));

        return view('apiSettings_single', [
            'apiSetting' => $apiSetting,
        ]);

    }

    public function apiSettings_store(Request $request)
    {

        //[api key 設定][儲存]
        $validator = Validator::make($request->all(), [
            'exchange' => 'required|max:10',
            'apikey' => 'required|string|max:50',
            'secretkey' => 'required|string|max:50',
            'notice' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        //檢查是否超過目前方案上限
        $apiKeyCount = UserKey::where('userid', Auth::User()->id)->count();

        if( $apiKeyCount >= 10 ){
            Session::flash('alert-danger', 'API Key 數量超過10組，請先刪除未使用的API Key');
            return redirect('/apiSettings');
        }

        $userKey = new UserKey();
        $userKey->userid = Auth::User()->id;
        $userKey->exchange = htmlspecialchars($request->input('exchange'), ENT_QUOTES);
        $userKey->apikey = htmlspecialchars($request->input('apikey'), ENT_QUOTES);
        $userKey->secretkey = htmlspecialchars($request->input('secretkey'), ENT_QUOTES);
        $userKey->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);

        if ($userKey->save()) {
            Session::flash('alert-success', 'API Key 更新成功');
        } else {
            Session::flash('alert-danger', 'API Key 更新失敗');
        }
        return redirect('/apiSettings');

    }

    public function apiSettings_update(Request $request,$id)
    {

        //[api key 設定][更新]
        $validator = Validator::make($request->all(), [
            'exchange' => 'required|max:10',
            'apikey' => 'required|string|max:50',
            'secretkey' => 'required|string|max:50',
            'notice' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $userKey = UserKey::findOrFail($id);
        $userKey->userid = Auth::User()->id;
        $userKey->exchange = htmlspecialchars($request->input('exchange'), ENT_QUOTES);
        $userKey->apikey = htmlspecialchars($request->input('apikey'), ENT_QUOTES);
        $userKey->secretkey = htmlspecialchars($request->input('secretkey'), ENT_QUOTES);
        $userKey->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);

        if ($userKey->save()) {
            Session::flash('alert-success', 'API Key 更新成功');
        } else {
            Session::flash('alert-danger', 'API Key 更新失敗');
        }
        return redirect('/apiSettings');

    }

    public function apiSettings_destroy($id)
    {

        //[api key 設定][刪除]]
        $apiSetting = UserKey::find($id);

        if ( is_null($apiSetting) | $apiSetting->botUsed == "1") {
            Session::flash('alert-danger', 'API Key 可能已在使用中無法刪除');
            return redirect('/apiSettings/' . $apiSetting->id);
        } 

        if (UserKey::destroy($id)) {
            Session::flash('alert-success', 'API Key 刪除成功');
        } else {
            Session::flash('alert-danger', 'API Key 刪除失敗');
        }

        return redirect('/apiSettings');

    }


}
