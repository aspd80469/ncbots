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
    public function index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $sysSignalLogs = SysSignalLog::query();

        $s_kType = htmlspecialchars($request->input('s_kType'), ENT_QUOTES);
        $s_symbol = htmlspecialchars($request->input('s_symbol'), ENT_QUOTES);
        $s_timeFrame = htmlspecialchars($request->input('s_timeFrame'), ENT_QUOTES);
        $s_direction = htmlspecialchars($request->input('s_direction'), ENT_QUOTES);
        $s_exchange = htmlspecialchars($request->input('s_exchange'), ENT_QUOTES);

        $request->flash();
        
        if ($s_kType != '') {
            $sysSignalLogs = $sysSignalLogs->where('kType', 'LIKE', '%' . $s_kType . '%');
        }

        if ($s_symbol != '') {
            $sysSignalLogs = $sysSignalLogs->where('symbol', 'LIKE', '%' . $s_symbol . '%');
        }

        if ($s_timeFrame != '') {
            $sysSignalLogs = $sysSignalLogs->where('timeFrame', 'LIKE', '%' . $s_timeFrame . '%');
        }

        if ($s_direction != '') {
            $sysSignalLogs = $sysSignalLogs->where('direction', 'LIKE', '%' . $s_direction . '%');
        }

        if ($s_exchange != '') {
            $sysSignalLogs = $sysSignalLogs->where('exchange', 'LIKE', '%' . $s_exchange . '%');
        }

        $sysSignalLogs = $sysSignalLogs->orderBy('created_at', 'DESC')->paginate(50);


        return view('mge/mge_sysSignalLogs', [
            'sysSignalLogs' => $sysSignalLogs,
        ]);
    }

}
