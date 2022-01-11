<?php

namespace App\Http\Controllers;	

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class AdminLoginController extends Controller {

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = 'mge/admin';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest')->except('logout');
	}

	/**
	 * 修改載入的 login 頁面.
	 */
	function showLoginForm() {
		return view('auth.admin_login');
	}

	/**
	 * 修改驗證欄位
	 */
	function username() {
		return 'account';
	}

	/**
	 * 修改驗證時使用的 guard
	 */
	protected function guard() {
		return \Auth::guard('manager');
	}

	public function authenticated(Request $request, $user)
    {
		if ( Auth::guard('manager')->user() ) 
		{

			$user->update([
				'last_login_at' => Carbon::now()->toDateTimeString(),
				'last_login_ip' => $request->getClientIp()
			]);

			return redirect('mge/admin');
			
        }else{

            return redirect("mge/login");
        }

    }

	/**
	 * 修改登出後的轉址路徑
	 */
	public function logout(Request $request) {
		$this->guard()->logout();
		$request->session()->flush();
		$request->session()->regenerate();		
		return redirect('/mge/login');
	}

}