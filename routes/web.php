<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminHomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\ManualOrderController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\SysSignalController;
use App\Http\Controllers\SysSignalLogController;
use App\Http\Controllers\SysLogController;
use App\Http\Controllers\BotStgyController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SysStatusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'dashboard']);
Route::get('ctuser', [UserController::class, 'init_user']);

//[管理][系統狀態][用於執行守護排程]
Route::get('sysStatus', [SysStatusController::class, 'sysStatus_call']);

Route::group(['middleware' => ['auth']], function() { 

    //首頁、新聞、會員資料
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('news/{id}', [HomeController::class, 'news'])->name('news');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('profile', [HomeController::class, 'profile_update'])->name('profile_update');

    //機器人
    Route::get('myBots', [HomeController::class, 'myBots'])->name('myBots');
    Route::post('myBots/add', [HomeController::class, 'myBots_store'])->name('myBots_store');
    Route::get('myBots/{id}', [HomeController::class, 'myBots_edit'])->name('myBots_edit');
    Route::post('myBots/{id}', [HomeController::class, 'myBots_update'])->name('myBots_update');

    //機器人訂單
    Route::get('myBotOrders/{id}', [HomeController::class, 'myBotOrders_index'])->name('myBotOrders_index');
    
    Route::get('userPlans', [HomeController::class, 'userPlans'])->name('userPlans');
    Route::get('userPlansNewRecord/{id}', [HomeController::class, 'userPlans_payNewRecord']);
    Route::post('userPlansNewRecord', [HomeController::class, 'userPlans_payNewRecord_store']);
    Route::get('userPlanRecords', [HomeController::class, 'userPlanRecords'])->name('userPlanRecords');
    Route::get('userPlanRecordsCancel/{id}', [HomeController::class, 'userPlanRecordsCancel'])->name('userPlanRecordsCancel');
    Route::get('riskNotice', [HomeController::class, 'riskNotice'])->name('riskNotice');

    //API Key 設定
    Route::get('apiSettings', [HomeController::class, 'apiSettings'])->name('apiSettings');
    Route::post('apiSettings/add', [HomeController::class, 'apiSettings_store']);
    Route::get('apiSettings/{id?}', [HomeController::class, 'apiSettings_edit']);
    Route::post('usapiSettingsers/edit/{id}', [HomeController::class, 'apiSettings_update']);

});

//[管理][登入]
Route::get('mge/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('mge/login', [AdminLoginController::class, 'login']);

Route::group(['middleware' => ['auth:manager'], 'prefix' => 'mge'], function() { 

    Route::get('admin', [AdminHomeController::class, 'dashboard'])->name('admin');

    //[管理][會員]
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'index']);
    Route::post('users/add', [UserController::class, 'store']);
    Route::get('user/{id?}', [UserController::class, 'edit']);
    Route::post('user/{id}', [UserController::class, 'update']);
    Route::get('users/delete/{id}', [UserController::class, 'destroy']);
    Route::get('userPwdReset/{id}', [UserController::class, 'userPwdReset']);

     //[管理][會員方案][燃料費]
     Route::get('userBurnMoney', [UserController::class, 'userBurnMoney_index']);
     Route::get('userBurnMoney/{id}', [UserController::class, 'userBurnMoney_edit']);
     Route::post('userBurnMoney/{id}', [UserController::class, 'userBurnMoney_update']);

    //[管理][會員下單]
    Route::get('orders', [UserController::class, 'orderIndex']);
    Route::post('orders', [UserController::class, 'orderIndex']);
    Route::get('orders/{id}', [UserController::class, 'orderbyUser_index']);
    Route::post('orders/{id}', [UserController::class, 'orderbyUser_index']);

    Route::get('myBots', [UserController::class, 'myBotsIndex']);
    Route::post('myBots', [UserController::class, 'myBotsIndex']);
    Route::get('myBots/{id}', [UserController::class, 'myBotsbyUser_index']);
    Route::post('myBots/{id}', [UserController::class, 'myBotsbyUser_index']);

    //[管理][手動補單]
    Route::get('manualOrders', [ManualOrderController::class, 'index']);

    //[管理][會員方案]
    Route::get('plans', [PlanController::class, 'index']);
    Route::post('plans', [PlanController::class, 'store']);
    Route::get('plans/{id?}', [PlanController::class, 'edit']);
    Route::post('plans/{id}', [PlanController::class, 'update']);
    Route::get('plans/delete/{id}', [PlanController::class, 'destroy']);

    //[管理][會員方案]
    Route::get('userPlans', [UserPlanController::class, 'index']);
    Route::post('userPlans/add', [UserPlanController::class, 'store']);
    Route::get('userPlans/{id?}', [UserPlanController::class, 'edit']);
    Route::post('userPlans/{id}', [UserPlanController::class, 'update']);
    Route::get('userPlans/delete/{id}', [UserPlanController::class, 'destroy']);

    //[管理][訊號Token]
    Route::get('sysSignals', [SysSignalController::class, 'index']);
    Route::post('sysSignals', [SysSignalController::class, 'index']);
    Route::post('sysSignals/add', [SysSignalController::class, 'store']);
    Route::get('sysSignals/{id}', [SysSignalController::class, 'edit']);
    Route::post('sysSignals/{id}', [SysSignalController::class, 'update']);
    Route::get('sysSignals/delete/{id}', [SysSignalController::class, 'destroy']);

    //[管理][訊號紀錄]
    Route::get('sysSignalLogs', [SysSignalLogController::class, 'index']);
    Route::post('sysSignalLogs', [SysSignalLogController::class, 'index']);

    //[管理][系統紀錄]
    Route::get('sysLogs', [SysLogController::class, 'index']);

    //[管理][管理者]
    Route::get('managers', [ManagerController::class, 'index']);
    Route::post('managers', [ManagerController::class, 'index']);
    Route::post('managers/add', [ManagerController::class, 'store']);
    Route::get('managers/{id}', [ManagerController::class, 'edit']);
    Route::post('managers/{id}', [ManagerController::class, 'update']);
    Route::get('managers/delete/{id}', [ManagerController::class, 'destroy']);

    //[管理][交易所報價]
    Route::get('binancePrice', [SysLogController::class, 'binancePrice_index']);
    Route::post('binancePrice', [SysLogController::class, 'binancePrice_index']);
    Route::get('ftxPrice', [SysLogController::class, 'ftxPrice_index']);
    Route::post('ftxPrice', [SysLogController::class, 'ftxPrice_index']);

    //[管理][策略管理]
    Route::get('botStgys', [BotStgyController::class, 'index']);
    Route::post('botStgys', [BotStgyController::class, 'index']);
    Route::post('botStgys/add', [BotStgyController::class, 'store']);
    Route::get('botStgys/{id}', [BotStgyController::class, 'edit']);
    Route::post('botStgys/{id}', [BotStgyController::class, 'update']);
    Route::get('botStgys/delete/{id}', [BotStgyController::class, 'destroy']);

    //[管理][最新消息]
    Route::get('news', [NewsController::class, 'index']);
    Route::post('news', [NewsController::class, 'index']);
    Route::post('news/add', [NewsController::class, 'store']);
    Route::get('news/{id}', [NewsController::class, 'edit']);
    Route::post('news/{id}', [NewsController::class, 'update']);
    Route::get('news/delete/{id}', [NewsController::class, 'destroy']);

    //[管理][參數設定]
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('settings', [SettingController::class, 'index']);
    Route::post('settings/add', [SettingController::class, 'store']);
    Route::get('settings/{id}', [SettingController::class, 'edit']);
    Route::post('settings/{id}', [SettingController::class, 'update']);
    Route::get('settings/delete/{id}', [SettingController::class, 'destroy']);

    Route::get('sysStatus', [SysStatusController::class, 'sysStatus']);

});
