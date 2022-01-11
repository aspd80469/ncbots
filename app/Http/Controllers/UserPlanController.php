<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use App\Models\UserActPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;


class UserPlanController extends Controller
{

    //[管理][方案管理]
    public function index()
    {

        //[主頁]

        //搜尋條件
        $userPlans =  UserPlan::orderBy('id', 'ASC')->paginate(20);

        return view('mge/mge_userPlan', [
            'userPlans' => $userPlans,
        ]);
    }

    public function store(Request $request)
    {

        //[儲存]
        $validator = Validator::make($request->all(),[
			'planName' => 'required|string',
            'maxBotQty' => 'required|min:0',
			'maxOrders' => 'required|min:0',
            'maxAmount' => 'required|numeric|min:0',
			'maxApiSlot' => 'required|numeric|min:1',
            'feeBySeason' => 'required|numeric|min:0',
            'feeByYear' => 'required|numeric|min:0',
            'feeUnit' => 'required|string|min:0',
            'suggest' => 'required|numeric|min:0',
            'enabled' => 'required|numeric|max:1',
        ]);

        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        $userPlan = new UserPlan;
		$userPlan->planName = htmlspecialchars($request->input('planName'), ENT_QUOTES);
        $userPlan->maxBotQty = htmlspecialchars($request->input('maxBotQty'), ENT_QUOTES);
        $userPlan->maxOrders = htmlspecialchars($request->input('maxOrders'), ENT_QUOTES);
        $userPlan->maxAmount = htmlspecialchars($request->input('maxAmount'), ENT_QUOTES);
		$userPlan->maxApiSlot = htmlspecialchars($request->input('maxApiSlot'), ENT_QUOTES);
		$userPlan->feeBySeason = htmlspecialchars($request->input('feeBySeason'), ENT_QUOTES);
        $userPlan->feeByYear = htmlspecialchars($request->input('feeByYear'), ENT_QUOTES);
        $userPlan->feeUnit = htmlspecialchars($request->input('feeUnit'), ENT_QUOTES);
        $userPlan->suggest = htmlspecialchars($request->input('suggest'), ENT_QUOTES);
        $userPlan->enabled = htmlspecialchars($request->input('enabled'), ENT_QUOTES);
        
        if ( $userPlan->save() )
        {
            Session::flash('alert-success', '已成功建立方案');
        }
        else
        {
            Session::flash('alert-danger', '建立方案失敗');
        }

        return redirect('mge/userPlans');

    }

    public function edit($id = 0)
    {

        //[編輯]
        $userPlan = ($id == 0) ? (null) : (UserPlan::findOrFail($id));

        return view('mge/mge_userPlan_single', [
            'userPlan' => $userPlan,
        ]);
    }

    
    public function update(Request $request, $id)
    {

        //[更新]
        $validator = Validator::make($request->all(),[
			'planName' => 'required|string',
            'maxBotQty' => 'required|min:0',
			'maxOrders' => 'required|min:0',
            'maxAmount' => 'required|numeric|min:0',
			'maxApiSlot' => 'required|numeric|min:1',
            'feeBySeason' => 'required|numeric|min:0',
            'feeByYear' => 'required|numeric|min:0',
            'feeUnit' => 'required|string|min:0',
            'suggest' => 'required|numeric|min:0',
            'enabled' => 'required|numeric|max:1',
        ]);
       
        if ( $validator->fails() )
        {
            return Redirect::back()
            ->withErrors($validator)
            ->withInput();
        }

        $userPlan = UserPlan::findOrFail($id);
		
		$userPlan->planName = htmlspecialchars($request->input('planName'), ENT_QUOTES);
        $userPlan->maxBotQty = htmlspecialchars($request->input('maxBotQty'), ENT_QUOTES);
        $userPlan->maxOrders = htmlspecialchars($request->input('maxOrders'), ENT_QUOTES);
        $userPlan->maxAmount = htmlspecialchars($request->input('maxAmount'), ENT_QUOTES);
		$userPlan->maxApiSlot = htmlspecialchars($request->input('maxApiSlot'), ENT_QUOTES);
		$userPlan->feeBySeason = htmlspecialchars($request->input('feeBySeason'), ENT_QUOTES);
        $userPlan->feeByYear = htmlspecialchars($request->input('feeByYear'), ENT_QUOTES);
        $userPlan->feeUnit = htmlspecialchars($request->input('feeUnit'), ENT_QUOTES);
        $userPlan->suggest = htmlspecialchars($request->input('suggest'), ENT_QUOTES);
        $userPlan->enabled = htmlspecialchars($request->input('enabled'), ENT_QUOTES);

        if( $userPlan->save() )
        {
            Session::flash('alert-success', '已更新方案');
        }
        else
        {
            Session::flash('alert-dangeer', '方案更新失敗');
        }
        return redirect('mge/userPlans');

    }

    public function destroy($id)
    {

        //[刪除]
        $userPlan = UserPlan::findOrFail($id);

        //如果此方案已被使用者使用，不可以刪除
        $userActPlanCount = UserActPlan::where('planID' , $id)->count();
        if( $userActPlanCount > 0 ){
            Session::flash('alert-warning', '方案已被使用者訂購，僅能停用方案無法刪除');
            return redirect('/mge/userPlans');
        }

        //刪除方案
        Session::flash('alert-danger', '已成功刪除方案 ' . $userPlan->id);
        UserPlan::destroy($id);

        return redirect('/mge/userPlans');
    }
}