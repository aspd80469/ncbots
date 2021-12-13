<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\ManualOrderController;
use App\Http\Controllers\UserPlanController;
use App\Http\Controllers\SysSignalController;
use App\Http\Controllers\SysSignalLogController;
use App\Http\Controllers\SysLogController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('userPlans', function () {
    return view('userPlans');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


//Route::group(['middleware' => ['auth']], function() { 

    Route::get('myBots', [HomeController::class, 'myBots'])->name('myBots');
    Route::get('userPlans', [HomeController::class, 'userPlans'])->name('userPlans');
    Route::get('userPlanRecords', [HomeController::class, 'userPlanRecords'])->name('userPlanRecords');
    Route::get('riskNotice', [HomeController::class, 'riskNotice'])->name('riskNotice');

//});

//[管理][登入]
Route::get('mge/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('mge/login', [AdminLoginController::class, 'showLoginForm']);

//Route::group(['middleware' => ['auth:manager'], 'prefix' => 'mge'], function() { 
Route::group(['prefix' => 'mge'], function() { 

    Route::get('admin', [AdminHomeController::class, 'admin'])->name('admin');

    //[管理][會員]
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('user/{id?}', [UserController::class, 'edit']);
    Route::post('users/edit/{id}', [UserController::class, 'update']);
    Route::get('users/delete/{id}', [UserController::class, 'destroy']);

    //[管理][手動補單]
    Route::get('manualOrders', [ManualOrderController::class, 'index']);

    //[管理][會員方案]
    Route::get('userPlans', [UserPlanController::class, 'index']);
    Route::post('userPlans', [UserPlanController::class, 'store']);
    Route::get('userPlan/{id?}', [UserPlanController::class, 'edit']);
    Route::post('userPlans/edit/{id}', [UserPlanController::class, 'update']);
    Route::get('userPlans/delete/{id}', [UserPlanController::class, 'destroy']);

    //[管理][風險聲明]
    Route::get('riskNotice', [SettingController::class, 'riskNotice_edit']);
    Route::post('riskNotice', [SettingController::class, 'riskNotice_update']);

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

    //[管理][系統設定]
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('settings', [SettingController::class, 'index']);
    Route::post('settings/add', [SettingController::class, 'store']);
    Route::get('settings/{id}', [SettingController::class, 'edit']);
    Route::post('settings/{id}', [SettingController::class, 'update']);
    Route::get('settings/delete/{id}', [SettingController::class, 'destroy']);

});

Route::group(['prefix' => 'v1/api'], function() {
    
    //[API][接收第三方訊號]
    Route::get('signals', [SignalController::class, 'signalReceive']);

});
