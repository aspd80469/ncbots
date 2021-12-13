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
                            <label for="name" class="form-label">參數<span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="請輸入參數" value="@if( !is_null($setting) ){{ $setting->name }}@endif">
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">設定值</label>
                            <textarea class="form-control">@if( !is_null($setting) ){{ $setting->value }}@endif</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">描述</label>
                            <textarea class="form-control">@if( !is_null($setting) ){{ $setting->sdesc }}@endif</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/settings" )}}'">返回</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="window.location='{{ url("mge/settings/delete/" . $setting->id  )}}'">刪除</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection