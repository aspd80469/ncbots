@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($sysSignal) )
                新增訊號Token
                @else
                編輯訊號Token
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
                        @if( is_null($sysSignal) )
                        {{ URL::to('mge/sysSignals/add')}}
                        @else
                        {{ URL::to('mge/sysSignals')}}/{{ $sysSignal->id }}
                        @endif
                        ">
                        @csrf

                        <div class="mb-3">
                            <label for="ncToken" class="form-label">Token<span class="text-danger">*</span></label>
                            <input type="text" id="ncToken" name="ncToken" class="form-control" placeholder="請輸入Token" value="@if( !is_null($sysSignal) ){{ $sysSignal->ncToken }}@endif">

                            @if( $errors->has('ncToken') )
							<span class="help-block" style="color: red;">
							<strong>必填，請輸入</strong>
							</span>
							@endif

                            <span class="help-block">
                            <strong>不同訊號來源及指標應使用不同Token避免策略上判斷錯誤</strong>
                            </span>

                        </div>

                        <div class="mb-3">
                            <label for="tdsec" class="form-label">Token描述</label>
                            <input type="text" id="tdsec" name="tdsec" class="form-control" maxlength="100" placeholder="請輸入Token描述" value="@if( !is_null($sysSignal) ){{ $sysSignal->tdsec }}@endif">

                            @if( $errors->has('tdsec') )
							<span class="help-block" style="color: red;">
							<strong>請確認Token描述</strong>
							</span>
							@endif

                        </div>

                        <div class="mb-3">
                            <label for="onlyStgyIds" class="form-label">限定策略ID使用</label>
                            <input type="text" id="onlyStgyIds" name="onlyStgyIds" class="form-control" placeholder="請輸入策略ID，並以半形逗號分隔" value="@if( !is_null($sysSignal) ){{ $sysSignal->onlyStgyIds }}@endif">

                            @if( $errors->has('onlyStgyIds') )
							<span class="help-block" style="color: red;">
							<strong>請確認限定策略ID</strong>
							</span>
							@endif

                            <span class="help-block">
                            <strong>限制策略ID將讓非設定的策略無法使用訊號，請前後以半形分號(;)分隔</strong>
                            </span>

                        </div>

                        <div class="mb-3">
                            <label for="expired_at" class="form-label">有效期限</label>
                            <input type="text" id="expired_at" name="expired_at" class="form-control" placeholder="請輸入有效期限，無設定將持續有效" value="@if( !is_null($sysSignal) ){{ $sysSignal->expired_at }}@endif">

                            @if( $errors->has('expired_at') )
							<span class="help-block" style="color: red;">
							<strong>請確認有效期限</strong>
							</span>
							@endif
                            
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">啟用</label>
                            <select class="form-control" name="status">
								<option value="0" @if ( !is_null($sysSignal) && $sysSignal->status == '0' ) selected @endif>啟用</option>
								<option value="1" @if ( is_null($sysSignal) | (!is_null($sysSignal) && $sysSignal->status == '1') ) selected @endif>停用</option>
							</select>

							@if( $errors->has('status') )
							<span class="help-block" style="color: red;">
							<strong>請確認啟用狀態</strong>
							</span>
							@endif

                            <span class="help-block">
                            <strong>設定【停用】即使尚未過有效期限也無法收到訊號</strong>
                            </span>

                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/sysSignals" )}}'">返回</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection