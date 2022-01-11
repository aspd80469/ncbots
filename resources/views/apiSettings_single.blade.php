
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                API Key 設定
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
					@if( is_null($apiSetting) )
                    {{ URL::to('apiSettings/add')}}
                    @else
                    {{ URL::to('apiSettings/')}}/{{ $apiSetting->id }}
                    @endif
					">
					@csrf

                        @if( !is_null($apiSetting) )
                        <div class="mb-3">
                            <label for="exchange" class="form-label">狀態</label>

                            <h2>
                            @if( $apiSetting->botUsed == 0 )
                            閒置中
                            @else
                            已使用
                            @endif
                            </h2>
                    
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="exchange" class="form-label">交易所</label>
                            <select id="exchange" name="exchange" class="form-control" required>
                                <option value="" @if(!is_null($apiSetting) && $apiSetting->exchange == "" ) selected @endif>---請選擇---</option>
                                <option value="binance" @if(!is_null($apiSetting) && $apiSetting->exchange == "binance" ) selected @endif>Binance</option>
                                <option value="ftx" @if(!is_null($apiSetting) && $apiSetting->exchange == "ftx" ) selected @endif>FTX</option>
                                <option value="bybit" @if(!is_null($apiSetting) && $apiSetting->exchange == "bybit" ) selected @endif>Bybit</option>
                            </select>
                                                        
                            @error('exchange')
                            <span role="alert" style="color: red;">
                                {{ $message }}
                            </span>
                            @enderror
					
                        </div>

                        <div class="mb-3">
                            <label for="apikey" class="form-label">API Key</label>
							
                            <input type="text" id="apikey" name="apikey" class="form-control" placeholder="" value="@if( !is_null($apiSetting) ){{ $apiSetting->apikey }}@endif" maxlength="50" required>
							
							@if( $errors->has('apikey') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，API Key</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="secretkey" class="form-label">API Secret</label>
                            
                            <div class="input-group input-group-merge">
                                <input type="password" id="secretkey" name="secretkey" class="form-control" placeholder="" value="@if( !is_null($apiSetting) ){{ $apiSetting->secretkey }}@endif" maxlength="50" required>
                                <div class="input-group-text" data-password="false">
                                <span class="password-eye"></span>
                                </div>
                            </div>
							
							@if( $errors->has('secretkey') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，API Secret</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="secretkey" class="form-label">備註</label>
							
                            <input type="text" id="notice" name="notice" class="form-control" placeholder="" value="@if( !is_null($apiSetting) ){{ $apiSetting->notice }}@endif" maxlength="50" required>
							
							@if( $errors->has('notice') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，API Secret</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <span class="help-block">
                                <strong>您可能需要視情形提供具有現貨(Spot)、現貨槓桿、合約交易權限，但請勿勾選提領權限</strong>
                                <br>
                                <strong>同一隻機器人可能需要不同交易所不同權限，設定錯誤機器人將無法正確操作策略</strong>
                            </span>
                            <br><br>
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>	
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("apiSettings" )}}'">返回</button>
                            
                            @if( !is_null($apiSetting) && $apiSetting->botUsed == 0 )
                            <a href="{{ url("apiSettings/delete/" . $apiSetting->id )}}" onclick="javascript:return confirm('確認刪除API Key?');" class="btn btn-danger waves-effect">刪除</a>
                            @endif
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection