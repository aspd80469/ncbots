
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($userPlanRecord) )
                建立會員方案
                @else
                編輯會員方案
                @endif
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <div class="card">
                <div class="card-body">

                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="
					@if( is_null($userPlanRecord) )
					{{ URL::to('mge/plans')}}
					@else
					{{ URL::to('mge/plans')}}/{{ $userPlanRecord->id }}
					@endif
					">
					@csrf

                        <div class="mb-3">
                            <label for="sstatus" class="form-label">狀態</label>
                            <select class="form-control" id="sstatus" name="sstatus">
                                <option value="0" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '0' ) selected @endif>新訂單</option>
                                <option value="1" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '1' ) selected @endif>處理中</option>
                                <option value="2" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '2' ) selected @endif>已完成</option>
                                <option value="3" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '3' ) selected @endif>已取消</option>
                                <option value="4" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '4' ) selected @endif>異常</option>
                                <option value="5" @if ( !is_null($userPlanRecord) && $userPlanRecord->status == '5' ) selected @endif>已退費</option>
                            </select>
                            
                            @if( $errors->has('sstatus') )
                            <span class="help-block" style="color: red;">
                            <strong>必填，必須選擇一個選項</strong>
                            </span>
                            @endif
                    
                        </div>

                        <div class="mb-3">
                            <label for="planID" class="form-label">訂購方案</label>
                            <select class="form-control" id="planID" name="planID" required>
                                <option value="">---請選擇---</option>
                                @foreach( $userPlans as $userPlan)
                                <option value="{{ $userPlan->id }}" @if ( !is_null($userPlanRecord) && $userPlanRecord->planID == $userPlan->id ) selected @endif>{{ $userPlan->planName}}</option>
                                @endforeach
                            </select>
                            
                            @if( $errors->has('planID') )
                            <span class="help-block" style="color: red;">
                            <strong>必填，必須選擇一個選項</strong>
                            </span>
                            @endif
                    
                        </div>

                        <div class="mb-3">
                            <label for="paidAmount" class="form-label">實際付款金額</label>
                            <input type="number" id="paidAmount" name="paidAmount" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->paidAmount }}@endif">
							
							@if( $errors->has('paidAmount') )
							<span class="help-block" style="color: red;">
							<strong>必填，最大長度為20字元</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="takeDate" class="form-label">生效日期</label>
                            <input type="text" id="takeDate" name="takeDate" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->takeDate }}@endif">
							
							@if( $errors->has('takeDate') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="edDate" class="form-label">到期日期</label>
                            <input type="text" id="edDate" name="edDate" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->edDate }}@endif">
							
							@if( $errors->has('edDate') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="useDay" class="form-label">使用天數</label>
                            <input type="number" id="useDay" name="useDay" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->useDay }}@endif">
							
							@if( $errors->has('useDay') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="paidDate" class="form-label">付款日期</label>
                            <input type="text" id="paidDate" name="paidDate" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->paidDate }}@endif">
							
							@if( $errors->has('paidDate') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="paidTxid" class="form-label">區塊練Tx</label>
                            <input type="text" id="paidTxid" name="paidTxid" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->paidTxid }}@endif">
							
							@if( $errors->has('paidTxid') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="notice" class="form-label">備註</label>
                            <input type="text" id="notice" name="notice" class="form-control" maxlength="191" placeholder="如為匯款可填寫匯款資訊" value="">
							
							@if( $errors->has('notice') )
                                <span class="help-block" style="color: red;">
                                <strong>選填，請輸入備註</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="payMethod" class="form-label">付款方式</label>
                            <select id="payMethod" name="payMethod" class="form-control" required>
                                <option value="">---請選擇---</option>
                                <option value="TRC20" @if( !is_null($userPlanRecord) && $userPlanRecord->payMethod == "TRC20" ) selected @endif>區塊練轉帳-TRC20</option>
                                <option value="ERC20" @if( !is_null($userPlanRecord) && $userPlanRecord->payMethod == "ERC20" ) selected @endif>區塊練轉帳-ERC20</option>
                                <option value="SPL20" @if( !is_null($userPlanRecord) && $userPlanRecord->payMethod == "SPL20" ) selected @endif>區塊練轉帳-SOL</option>
                                <option value="NTDTransfer" @if( !is_null($userPlanRecord) && $userPlanRecord->payMethod == "NTDTransfer" ) selected @endif>台幣轉帳</option>
                            </select>
							
							@if( $errors->has('payMethod') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>
						
						<div class="mb-3">
                            <label for="payfeeUnit" class="form-label">貨幣單位</label>
                            <input type="payfeeUnit" id="payfeeUnit" name="payfeeUnit" maxlength="5" class="form-control" placeholder="" value="@if( !is_null($userPlanRecord) ){{ $userPlanRecord->payfeeUnit }}@endif">
							
							@if( $errors->has('payfeeUnit') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/plans" )}}'">返回</button>
							
							@if( !is_null($userPlanRecord) && $userPlanRecord->status !=2 )
							<a href="{{ url("mge/plan/delete/" . $userPlanRecord->id )}}" onclick="javascript:return confirm('確認刪除方案?');" class="btn btn-danger waves-effect">刪除</a>
							@endif
				
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection