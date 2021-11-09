<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class ManualOrderController extends Controller
{
    protected $settingService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    //[管理][]
    public function index()
    {

        //[主頁]

        //搜尋條件
        // $sysLogs =  SysLog::orderBy('created_at', 'DESC')->paginate(50);

        return view('mge/mge_manualOrders', [
            // 'sysLogs' => $sysLogs,
        ]);
    }

}
