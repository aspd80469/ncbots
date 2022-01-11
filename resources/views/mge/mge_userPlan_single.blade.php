
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
					{{ URL::to('mge/userPlans')}}/add
					@else
					{{ URL::to('mge/userPlans')}}/{{ $userPlan->id }}
					@endif
					">
					@csrf

                        <div class="mb-3">
                            <label for="planName" class="form-label">方案名稱</label>
                            <input type="text" id="planName" name="planName" maxlength="20" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->planName }}@endif" required>
							
							@if( $errors->has('planName') )
							<span class="help-block" style="color: red;">
							<strong>必填，最大長度為20字元</strong>
							</span>
							@endif
					
                        </div>

						<div class="mb-3">
                            <label for="maxBotQty" class="form-label">最大機器數量</label>
                            <input type="number" id="maxBotQty" name="maxBotQty" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxBotQty }}@endif" required>
							
							@if( $errors->has('maxBotQty') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="maxOrders" class="form-label">最大下單量</label>
                            <input type="number" id="maxOrders" name="maxOrders" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxOrders }}@endif" required>
							
							@if( $errors->has('maxOrders') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="maxAmount" class="form-label">最大資金上限</label>
                            <input type="number" id="maxAmount" name="maxAmount" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxAmount }}@endif" required>
							
							@if( $errors->has('maxAmount') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="maxApiSlot" class="form-label">Api數量</label>
                            <input type="number" id="maxApiSlot" name="maxApiSlot" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->maxApiSlot }}@endif" required>
							
							@if( $errors->has('maxApiSlot') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="feeBySeason" class="form-label">季費</label>
                            <input type="number" id="feeBySeason" name="feeBySeason" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->feeBySeason }}@endif" required>
							
							@if( $errors->has('feeBySeason') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="feeByYear" class="form-label">年費</label>
                            <input type="number" id="feeByYear" name="feeByYear" class="form-control" placeholder="" value="@if( !is_null($userPlan) ){{ $userPlan->feeByYear }}@endif" required>
							
							@if( $errors->has('feeByYear') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="feeUnit" class="form-label">貨幣單位</label>
                            <input type="text" id="feeUnit" name="feeUnit" maxlength="5" class="form-control" placeholder="USDT" value="@if( !is_null($userPlan) ){{ $userPlan->feeUnit }}@endif" required>
							
							@if( $errors->has('feeUnit') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="suggest" class="form-label">是否為推薦</label>
							<select class="form-control" id="suggest" name="suggest" required>
								<option value="1" @if ( !is_null($userPlan) && $userPlan->suggest == '1' ) selected @endif>是</option>
								<option value="0" @if ( !is_null($userPlan) && $userPlan->suggest == '0' ) selected @endif>否</option>
							</select>
							
							@if( $errors->has('suggest') )
							<span class="help-block" style="color: red;">
							<strong>必填</strong>
							</span>
							@endif
					
                        </div>
						
						<div class="mb-3">
						
                            <label for="enabled" class="form-label">啟用</label>
							<select class="form-control" id="enabled" name="enabled" required>
								<option value="1" @if ( !is_null($userPlan) && $userPlan->enabled == '1' ) selected @endif>啟用</option>
								<option value="0" @if ( !is_null($userPlan) && $userPlan->enabled == '0' ) selected @endif>停用</option>
							</select>
							
							@if( $errors->has('enabled') )
							<span class="help-block" style="color: red;">
							<strong>必填，必須選擇一個選項</strong>
							</span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/userPlans" )}}'">返回</button>
							
							@if( !is_null($userPlan) )
							<a href="{{ url("mge/userPlans/delete/" . $userPlan->id )}}" onclick="javascript:return confirm('確認刪除方案?');" class="btn btn-danger waves-effect">刪除</a>
							@endif
				
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection