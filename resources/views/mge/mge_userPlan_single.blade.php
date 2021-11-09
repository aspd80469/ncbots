
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($userPlan) )
                建立方案
                @else
                編輯方案
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
					@if( is_null($userPlan) )
					{{ URL::to('mge/userPlan')}}
					@else
					{{ URL::to('mge/userPlan')}}/{{ $userPlan->id }}
					@endif
					">
					@csrf

                        <div class="mb-3">
                            <label for="planName" class="form-label">方案名稱</label>
                            <input type="text" id="planName" name="planName" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->planName }}@endif">
							
							@if( $errors->has('planName') )
							<span class="help-block">
							<strong>必填，最大長度為20字元</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="maxOrders" class="form-label">最大下單量</label>
                            <input type="text" id="maxOrders" name="maxOrders" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxOrders }}@endif">
							
							@if( $errors->has('maxOrders') )
							<span class="help-block">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="maxApiSlot" class="form-label">Api數量</label>
                            <input type="text" id="maxApiSlot" name="maxApiSlot" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxApiSlot }}@endif">
							
							@if( $errors->has('maxApiSlot') )
							<span class="help-block">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="payPeriod" class="form-label">付款週期</label>
                            <input type="text" id="payPeriod" name="payPeriod" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->payPeriod }}@endif">
							
							@if( $errors->has('payPeriod') )
							<span class="help-block">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="fee" class="form-label">費用</label>
                            <input type="text" id="fee" name="fee" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->fee }}@endif">
							
							@if( $errors->has('fee') )
							<span class="help-block">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>
						
						<div class="mb-3">
						
                            <label for="fee" class="form-label">啟用</label>
							<select class="form-control" name="enabled">
								<option value="0" @if ( !is_null($userPlan) && $userPlan->enabled == '0' ) selected @endif>啟用</option>
								<option value="1" @if ( !is_null($userPlan) && $userPlan->enabled == '1' ) selected @endif>停用</option>
							</select>
							
							@if( $errors->has('enabled') )
							<span class="help-block">
							<strong>必填，必須選擇一個選項</strong>
							</span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/userPlans" )}}'">返回</button>
							
							@if( !is_null($userPlan) )
							<a href="{{ url("mge/userPlan/delete/" . $userPlan->id )}}" onclick="javascript:return confirm('確認刪除方案?');" class="btn btn-danger waves-effect">刪除</a>
							@endif
				
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection