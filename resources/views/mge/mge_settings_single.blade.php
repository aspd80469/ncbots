@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($setting) )
                新增參數
                @else
                編輯參數
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
                        @if( is_null($setting) )
                        {{ URL::to('mge/settings/add')}}
                        @else
                        {{ URL::to('mge/settings')}}/{{ $setting->id }}
                        @endif
                        ">
                        @csrf

                        <div class="mb-3">
                            <label for="sname" class="form-label">參數<span class="text-danger">*</span></label>
                            <input type="text" id="sname" name="sname" class="form-control" placeholder="請輸入參數" value="@if( !is_null($setting) ){{ $setting->name }}@endif">

                        </div>

                        <div class="mb-3">
                            <label for="svalue" class="form-label">設定值</label>
                            <textarea class="form-control" id="svalue" name="svalue" rows="10">@if( !is_null($setting) ){!! html_entity_decode($setting->value) !!}@endif</textarea>

                        </div>

                        <div class="mb-3">
                            <label for="sdesc" class="form-label">描述</label>
                            <textarea class="form-control" id="sdesc" name="sdesc" placeholder="請輸入描述" rows="5">@if( !is_null($setting) ){{ $setting->sdesc }}@endif</textarea>

                        </div>

                        <div class="mb-3">
                            <label for="sysDefPara" class="form-label" @if( !is_null($setting) && $setting->sysDefPara == '1') style="display:none" @endif>系統必須參數</label>
                            <select class="form-control" name="sysDefPara" @if( !is_null($setting) && $setting->sysDefPara == '1') style="display:none" @endif>
								<option value="0" @if ( is_null($setting) | (!is_null($setting) && $setting->sysDefPara == '0') ) selected @endif>否</option>
								<option value="1" @if ( !is_null($setting) && $setting->sysDefPara == '1' ) selected @endif>是</option>
							</select>
							@if( $errors->has('sysDefPara') )
							<span class="help-block" style="color: red;">
							<strong>必填，必須選擇一個選項</strong>
							</span>
							@endif

                            @if( is_null($setting))
                            <span class="help-block" style="color: red;">
							<strong>如選【是】，則新增後無法刪除</strong>
							</span>
                            @endif

                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/settings" )}}'">返回</button>
                            @if(!is_null($setting) && $setting->sysDefPara == "0" )
                            <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="window.location='{{ url("mge/settings/delete/" . $setting->id  )}}'">刪除</button>
                            @endif
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection