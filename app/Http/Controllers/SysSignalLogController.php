<?php

namespace App\Http\Controllers;

use App\Models\SysSignalLog;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class SysSignalLogController extends Controller
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
        $sysSignalLogs =  SysSignalLog::orderBy('created_at', 'DESC')->paginate(50);

        return view('mge/mge_sysSignalLog', [
            'sysSignalLogs' => $sysSignalLogs,
        ]);
    }

}
