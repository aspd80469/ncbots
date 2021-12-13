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
                        </div>

                        <div class="mb-3">
                            <label for="tdsec" class="form-label">Token描述</label>
                            <input type="text" id="tdsec" name="tdsec" class="form-control" placeholder="請輸入Token描述" value="@if( !is_null($sysSignal) ){{ $sysSignal->tdsec }}@endif">
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