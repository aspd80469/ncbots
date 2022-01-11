<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use App\Models\UserActPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Session;
use Auth;


class PlanController extends Controller
{

    //[管理][會員方案]
    public function index()
    {

        //[主頁]

        //搜尋條件
        $userPlanRecords =  UserActPlan::orderBy('id', 'ASC')->paginate(20);

        return view('mge/mge_plan', [
            'userPlanRecords' => $userPlanRecords,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(),[
			'planID' => 'required|numeric|min:0',
			'sstatus' => 'required|nullable|max:5',
            'takeDate' => 'nullable',
            'useDay' => 'nullable',
            'edDate' => 'nullable',
            'paidAmount' => 'nullable',
            'paidDate' => 'required',
            'paidTxid' => 'nullable',
            'notice' => 'nullable|string|max:191',
            'payMethod' => 'required|string|max:20',
            'payfeeUnit' => 'required|string|max:5',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        //檢查生效日期與到期日期不能在目前已完成訂單的區間
        $lastComptedOrder = UserActPlan::where('status' , 2)->orderBy('created_at', 'DESC')->first();
        if( !is_null($lastComptedOrder) ){
            
            $isInDate_s = Carbon::parse(htmlspecialchars($request->input('takeDate'), ENT_QUOTES))->between($lastComptedOrder->takeDate, $lastComptedOrder->edDate);
            $isInDate_e = Carbon::parse(htmlspecialchars($request->input('edDate'), ENT_QUOTES))->between($lastComptedOrder->takeDate, $lastComptedOrder->edDate);
    
            if( $isInDate_s & $isInDate_e ){
                Session::flash('alert-danger', '建立會員方案失敗，新訂單生效區間位於目前生效方案區間');
                return redirect('mge/plans');
            }

        }
        

        $userActPlan = new UserActPlan;
		$userActPlan->planID = htmlspecialchars($request->input('planID'), ENT_QUOTES);
        $userActPlan->status = htmlspecialchars($request->input('sstatus'), ENT_QUOTES);
		$userActPlan->applyDate = Carbon::now()->format('Y:m:d H:i:s');
		$userActPlan->takeDate = htmlspecialchars($request->input('takeDate'), ENT_QUOTES);
        $userActPlan->useDay = htmlspecialchars($request->input('useDay'), ENT_QUOTES);
        $userActPlan->edDate = htmlspecialchars($request->input('edDate'), ENT_QUOTES);
        $userActPlan->paidAmount = htmlspecialchars($request->input('paidAmount'), ENT_QUOTES);
        $userActPlan->paidDate = htmlspecialchars($request->input('paidDate'), ENT_QUOTES);
        $userActPlan->paidTxid = htmlspecialchars($request->input('paidTxid'), ENT_QUOTES);
        $userActPlan->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);
        $userActPlan->payMethod = htmlspecialchars($request->input('payMethod'), ENT_QUOTES);
        $userActPlan->payfeeUnit = htmlspecialchars($request->input('payfeeUnit'), ENT_QUOTES);
        
        if ( $userActPlan->save() )
        {
            Session::flash('alert-success', '已成功建立會員方案');
        }
        else
        {
            Session::flash('alert-danger', '建立會員方案失敗');
        }

        return redirect('mge/plans');

    }

    public function edit($id = 0)
    {

        //[編輯]
        $userPlans = UserPlan::query()->get();
        $userPlanRecord = ($id == 0) ? (null) : (UserActPlan::findOrFail($id));

        return view('mge/mge_plan_single', [
            'userPlans' => $userPlans,
            'userPlanRecord' => $userPlanRecord,
        ]);
    }

    
    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(),[
			'planID' => 'required|numeric|min:0',
			'sstatus' => 'required|nullable|max:5',
            'takeDate' => 'nullable',
            'useDay' => 'nullable',
            'edDate' => 'nullable',
            'paidAmount' => 'nullable',
            'paidDate' => 'required',
            'paidTxid' => 'nullable',
            'notice' => 'nullable|string|max:191',
            'payMethod' => 'required|string|max:20',
            'payfeeUnit' => 'required|string|max:5',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        $userActPlan = UserActPlan::findOrFail($id);
		
		$userActPlan->planID = htmlspecialchars($request->input('planID'), ENT_QUOTES);
        $userActPlan->status = htmlspecialchars($request->input('sstatus'), ENT_QUOTES);
		$userActPlan->applyDate = htmlspecialchars($request->input('applyDate'), ENT_QUOTES);
		$userActPlan->takeDate = htmlspecialchars($request->input('takeDate'), ENT_QUOTES);
        $userActPlan->useDay = htmlspecialchars($request->input('useDay'), ENT_QUOTES);
        $userActPlan->edDate = htmlspecialchars($request->input('edDate'), ENT_QUOTES);
        $userActPlan->paidAmount = htmlspecialchars($request->input('paidAmount'), ENT_QUOTES);
        $userActPlan->paidDate = htmlspecialchars($request->input('paidDate'), ENT_QUOTES);
        $userActPlan->paidTxid = htmlspecialchars($request->input('paidTxid'), ENT_QUOTES);
        $userActPlan->notice = htmlspecialchars($request->input('notice'), ENT_QUOTES);
        $userActPlan->payMethod = htmlspecialchars($request->input('payMethod'), ENT_QUOTES);
        $userActPlan->payfeeUnit = htmlspecialchars($request->input('payfeeUnit'), ENT_QUOTES);

        if( $userActPlan->save() )
        {
            Session::flash('alert-success', '已更新方案');
        }
        else
        {
            Session::flash('alert-dangeer', '方案更新失敗');
        }
        return redirect('mge/plans');

    }

    public function destroy($id)
    {

        //[刪除]
        $userActPlan = UserActPlan::findOrFail($id);

        //刪除方案
        Session::flash('alert-danger', '已成功刪除方案 ' . $userActPlan->id);
        UserActPlan::destroy($id);

        return redirect('/mge/plans');
    }
}