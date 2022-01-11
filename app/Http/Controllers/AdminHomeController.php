<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use App\Models\News;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

use Carbon\Carbon;
use Session;
use Hash;
use Auth;

class AdminHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function dashboard()
    {

        //新聞
        //$news = News::where('display' , '0')->orderBy('created_at', 'DESC')->get();

        //[管理主頁]
        return view('dashboard',[
            //'news' => $news,
        ]);

    }

}
