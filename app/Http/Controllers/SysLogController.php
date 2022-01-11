<?php

namespace App\Http\Controllers;

use App\Models\SysLog;
use App\Models\Symbolprice;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class SysLogController extends Controller
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
        $sysLogs =  SysLog::orderBy('created_at', 'DESC')->paginate(50);

        return view('mge/mge_sysLogs', [
            'sysLogs' => $sysLogs,
        ]);
    }

    public function binancePrice_index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $symbolPrices =  Symbolprice::query();
        $s_symbol = htmlspecialchars($request->input('s_symbol'), ENT_QUOTES);

        $request->flash();

        if ($s_symbol != '') {
            $symbolPrices = $symbolPrices->where('symbol', 'LIKE', '%' . $s_symbol . '%');
        }

        $symbolPrices = $symbolPrices->where('exchange', 'binance')->orderBy('created_at', 'DESC')->paginate(50);

        return view('mge/mge_binancePrice', [
            'symbolPrices' => $symbolPrices,
        ]);
    }

    public function ftxPrice_index(Request $request)
    {

        //[主頁]

        //搜尋條件
        $symbolPrices =  Symbolprice::query();
        $s_symbol = htmlspecialchars($request->input('s_symbol'), ENT_QUOTES);

        if ($s_symbol != '') {
            $symbolPrices = $symbolPrices->where('symbol', 'LIKE', '%' . $s_symbol . '%');
        }

        $symbolPrices = $symbolPrices->where('exchange', 'ftx')->orderBy('created_at', 'DESC')->paginate(50);

        return view('mge/mge_ftxPrice', [
            'symbolPrices' => $symbolPrices,
        ]);
    }
    

}
