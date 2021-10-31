<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function myBots()
    {

        //[我的機器人]
        return view('myBots',[
        ]);

    }
    
    public function userPlanRecords()
    {

        //[會員方案]
        return view('userPlanRecords',[
        ]);

    }

    public function userPlans()
    {

        //[付費紀錄]
        return view('userPlans',[
        ]);

    }

    public function riskNotice()
    {

        //[風險聲明]
        return view('riskNotice',[
        ]);

    }
}
