
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                訂購會員方案
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
					{{ URL::to('userPlansNewRecord')}}
					">
					@csrf
					
                        <div class="mb-3">
                            <label for="planName" class="form-label">選擇的方案：</label>

                            <input type="hidden" id="planNameId" name="planNameId" value="{{ $userPlan->id }}">
                            <h2>{{ $userPlan->planName }}</h2> 

                        </div>

                        <div class="mb-3">
                            <label for="planName" class="form-label">付款地址：</label>
                            @if( !is_null($allowUserPlanPayByTRC20) && $allowUserPlanPayByTRC20 == "Y" )
                            <h4>TRC20：</h4>
                            <input type="text" class="form-control" id="sysMainWalletTRC20" name="sysMainWalletTRC20" value="{{ $sysMainWalletTRC20 }}" readonly>
                            @endif

                            @if( !is_null($allowUserPlanPayByERC20) && $allowUserPlanPayByERC20 == "Y" )
                            <h4>ERC20：</h4>
                            <input type="text" class="form-control" id="sysMainWalletERC20" name="sysMainWalletERC20" value="{{ $sysMainWalletERC20 }}" readonly>
                            @endif

                        </div>

                        <div class="mb-3">
                            <label for="paidAmount" class="form-label">實際付款金額</label>
							
                            <input type="number" id="paidAmount" name="paidAmount" class="form-control" placeholder="" value="" min="0" required>
							
							@if( $errors->has('paidAmount') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，API Key</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="paidTxid" class="form-label">區塊練Tx</label>
							
                            <input type="text" id="paidTxid" name="paidTxid" class="form-control" placeholder="" value="">
							
							@if( $errors->has('paidTxid') )
                                <span class="help-block" style="color: red;">
                                <strong>選填，請輸入區塊練Tx</strong>
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
                                <option value="TRC20" >區塊練轉帳-TRC20</option>
                                <option value="ERC20" >區塊練轉帳-ERC20</option>
                                <option value="SPL20" >區塊練轉帳-SOL</option>
                                <option value="NTDTransfer" >台幣轉帳</option>
                            </select>

							
							@if( $errors->has('payMethod') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，請選擇付款方式</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <span class="help-block">
                                <strong>1.付款後請通知客服人員協助確認付款資訊</strong>
                                <br>
                                <strong>2.請注意地址僅能接受USDT付款，非USDT將不可找回</strong>
                                <br>
                                <strong>3.儲值時請注意轉出時的手續費，避免實際到帳時少了手續費金額，無法完成開通</strong>
                            </span>
                            <br><br>
                            <button type="submit" class="btn btn-blue waves-effect waves-light">申購</button>	
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("userPlans" )}}'">返回</button>			
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection