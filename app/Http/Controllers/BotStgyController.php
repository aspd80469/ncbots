<?php

namespace App\Http\Controllers;

use App\Models\BotsStgy;
use App\Models\Symbolprice;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;

class BotStgyController extends Controller
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
        $botStgys =  BotsStgy::paginate(50);

        return view('mge/mge_botStgy', [
            'botStgys' => $botStgys,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(), [
            'stgyName' => 'required|max:255',
            'stgyMapfun' => 'required|',
            'notice' => 'nullable|string',
            'reBuy1h' => 'nullable|numeric|min:0|max:100',
            'reSell1h' => 'nullable|numeric|min:0|max:100',
            'reBuy2h' => 'nullable|numeric|min:0|max:100',
            'reSell2h' => 'nullable|numeric|min:0|max:100',
            'reBuy4h' => 'nullable|numeric|min:0|max:100',
            'reSell4h' => 'nullable|numeric|min:0|max:100',
            'reBuy6h' => 'nullable|numeric|min:0|max:100',
            'reSell6h' => 'nullable|numeric|min:0|max:100',
            'reBuy12h' => 'nullable|numeric|min:0|max:100',
            'reSell12h' => 'nullable|numeric|min:0|max:100',
            'reBuyDay' => 'nullable|numeric|min:0|max:100',
            'reSellDay' => 'nullable|numeric|min:0|max:100',
            'maxDCAqty' => 'nullable|numeric',
            'sstatus' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $botStgys = new BotsStgy;
        $botStgys->stgyName = htmlspecialchars($request->input('stgyName'), ENT_QUOTES);
        $botStgys->stgyMapfun = htmlspecialchars($request->input('stgyMapfun'), ENT_QUOTES);
        $botStgys->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);
        $botStgys->reBuy1h = htmlspecialchars($request->input('reBuy1h'), ENT_QUOTES);
        $botStgys->reSell1h = htmlspecialchars($request->input('reSell1h'), ENT_QUOTES);
        $botStgys->reBuy2h = htmlspecialchars($request->input('reBuy2h'), ENT_QUOTES);
        $botStgys->reSell2h = htmlspecialchars($request->input('reSell2h'), ENT_QUOTES);
        $botStgys->reBuy4h = htmlspecialchars($request->input('reBuy4h'), ENT_QUOTES);
        $botStgys->reSell4h = htmlspecialchars($request->input('reSell4h'), ENT_QUOTES);
        $botStgys->reBuy6h = htmlspecialchars($request->input('reBuy6h'), ENT_QUOTES);
        $botStgys->reSell6h = htmlspecialchars($request->input('reSell6h'), ENT_QUOTES);
        $botStgys->reBuy12h = htmlspecialchars($request->input('reBuy12h'), ENT_QUOTES);
        $botStgys->reSell12h = htmlspecialchars($request->input('reSell12h'), ENT_QUOTES);
        $botStgys->reBuyDay = htmlspecialchars($request->input('reBuyDay'), ENT_QUOTES);
        $botStgys->reSellDay = htmlspecialchars($request->input('reSellDay'), ENT_QUOTES);
        $botStgys->maxDCAqty = htmlspecialchars($request->input('maxDCAqty'), ENT_QUOTES);
        $botStgys->status = htmlspecialchars($request->input('sstatus'), ENT_QUOTES);

        if ($botStgys->save()) {
            Session::flash('alert-success', '建立策略成功');
        } else {
            Session::flash('alert-danger', '建立策略失敗');
        }

        return redirect('/mge/botStgys');
    }

    public function edit($id)
    {

        //[編輯]
        $botStgy = ($id == 0) ? (null) : (BotsStgy::findOrFail($id));

        return view('mge/mge_botStgy_single', [
            'botStgy' => $botStgy,
        ]);
    }

    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(), [
            'stgyName' => 'required|max:255',
            'stgyMapfun' => 'required|',
            'notice' => 'nullable|string',
            'reBuy1h' => 'nullable|numeric|min:0|max:100',
            'reSell1h' => 'nullable|numeric|min:0|max:100',
            'reBuy2h' => 'nullable|numeric|min:0|max:100',
            'reSell2h' => 'nullable|numeric|min:0|max:100',
            'reBuy4h' => 'nullable|numeric|min:0|max:100',
            'reSell4h' => 'nullable|numeric|min:0|max:100',
            'reBuy6h' => 'nullable|numeric|min:0|max:100',
            'reSell6h' => 'nullable|numeric|min:0|max:100',
            'reBuy12h' => 'nullable|numeric|min:0|max:100',
            'reSell12h' => 'nullable|numeric|min:0|max:100',
            'reBuyDay' => 'nullable|numeric|min:0|max:100',
            'reSellDay' => 'nullable|numeric|min:0|max:100',
            'maxDCAqty' => 'nullable|numeric',
            'sstatus' => 'nullable|numeric|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $botStgys = BotsStgy::findOrFail($id);
        $botStgys->stgyName = htmlspecialchars($request->input('stgyName'), ENT_QUOTES);
        $botStgys->stgyMapfun = htmlspecialchars($request->input('stgyMapfun'), ENT_QUOTES);
        $botStgys->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);
        $botStgys->reBuy1h = htmlspecialchars($request->input('reBuy1h'), ENT_QUOTES);
        $botStgys->reSell1h = htmlspecialchars($request->input('reSell1h'), ENT_QUOTES);
        $botStgys->reBuy2h = htmlspecialchars($request->input('reBuy2h'), ENT_QUOTES);
        $botStgys->reSell2h = htmlspecialchars($request->input('reSell2h'), ENT_QUOTES);
        $botStgys->reBuy4h = htmlspecialchars($request->input('reBuy4h'), ENT_QUOTES);
        $botStgys->reSell4h = htmlspecialchars($request->input('reSell4h'), ENT_QUOTES);
        $botStgys->reBuy6h = htmlspecialchars($request->input('reBuy6h'), ENT_QUOTES);
        $botStgys->reSell6h = htmlspecialchars($request->input('reSell6h'), ENT_QUOTES);
        $botStgys->reBuy12h = htmlspecialchars($request->input('reBuy12h'), ENT_QUOTES);
        $botStgys->reSell12h = htmlspecialchars($request->input('reSell12h'), ENT_QUOTES);
        $botStgys->reBuyDay = htmlspecialchars($request->input('reBuyDay'), ENT_QUOTES);
        $botStgys->reSellDay = htmlspecialchars($request->input('reSellDay'), ENT_QUOTES);
        $botStgys->maxDCAqty = htmlspecialchars($request->input('maxDCAqty'), ENT_QUOTES);
        $botStgys->maxDCAqty = htmlspecialchars($request->input('maxDCAqty'), ENT_QUOTES);
        $botStgys->status = htmlspecialchars($request->input('sstatus'), ENT_QUOTES);

        if ($botStgys->save()) {
            Session::flash('alert-success', '策略更新成功');
        } else {
            Session::flash('alert-danger', '策略更新失敗');
        }
        return redirect('/mge/botStgys/');
    }

    public function destroy($id)
    {

        //[刪除]

        $botStgys = BotsStgy::findOrFail($id);
        BotsStgy::destroy($id);
        Session::flash('alert-danger', '已成功刪除策略：' . $botStgys->stgyName . '，策略Function：' . 	$botStgys->stgyMapfun );

        return redirect('/mge/botStgys');
    }


}
