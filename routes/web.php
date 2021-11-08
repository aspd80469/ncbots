<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SingalController;

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
    Route::get('users/delete/{id}', [ManagersUserControllerController::class, 'destroy']);

    //[管理][管理者]
    Route::get('managers', [ManagerController::class, 'index']);
    Route::post('managers', [ManagerController::class, 'index']);
    Route::post('managers/add', [ManagerController::class, 'store']);
    Route::get('managers/{id}', [ManagerController::class, 'edit']);
    Route::post('managers/{id}', [ManagerController::class, 'update']);
    Route::get('managers/delete/{id}', [ManagerController::class, 'destroy']);

    //[管理][系統設定]
    Route::get('settings', [SettingController::class, 'edit']);
    Route::post('settings', [SettingController::class, 'update']);

});

Route::group(['prefix' => 'v1/api'], function() {
    
    //[API][接收第三方訊號]
    Route::get('signals', [SignalController::class, 'signalReceive']);

});
