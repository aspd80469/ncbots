@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($manager) )
                建立管理帳號
                @else
                編輯管理帳號
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
                        @if( is_null($manager) )
                        {{ URL::to('mge/managers/add')}}
                        @else
                        {{ URL::to('mge/managers')}}/{{ $manager->id }}
                        @endif
                        ">
                        @csrf

                        <div class="mb-3">
                            <label for="account" class="form-label">帳號<span class="text-danger">*</span></label>
                            <input type="text" id="account" name="account" class="form-control" placeholder="請輸入帳號" value="@if( !is_null($manager) ){{ $manager->account }}@endif">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">密碼<span class="text-danger">*</span></label>
                            <input type="text" id="password" name="password" class="form-control" placeholder="請輸入密碼@if( !is_null($manager) )，無變更請留空@else 最少6個字元  @endif" @if( is_null($manager) )  required minlength="6" @endif>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/managers" )}}'">返回</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection