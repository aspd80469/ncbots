@extends('layouts.app')

@section('content')
<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="
            @if( is_null($user) )
            {{ URL::to('mge/user')}}
            @else
            {{ URL::to('mge/user')}}/{{ $user->id }}
            @endif
            ">
            @csrf

<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-uppercase bg-light p-2 mt-0 mb-3">
                @if( is_null($user) )
                會員建檔
                @else
                會員編輯 {{ $user->s_tnum }}
                @endif
            </h4>

            <div class="form-group row mb-3">
                <label for="name" class="col-3 col-form-label">姓名 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="text" class="form-control" name="name" minlength="2" maxlength="20" value="@if( !is_null($user) ){{ $user->name }}@endif">
                    @if( $errors->has('name') )
                    <span class="help-block">
                    <strong>必填，最大長度為20字元</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="password" class="col-3 col-form-label">密碼</label>
                <div class="col-9">
                    <input type="password" name="password" class="form-control" placeholder="@if( !is_null($user) )無變更請留空@endif" @if( is_null($user) ) minlength="6" @endif>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="status" class="col-3 col-form-label">狀態 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select class="form-control" name="status">
                        <option value="1" @if ( !is_null($user) && $user->status == '0' ) selected @endif>啟用</option>
                        <option value="2" @if ( !is_null($user) && $user->status == '1' ) selected @endif>停用</option>
                    </select>
                    @if( $errors->has('status') )
                    <span class="help-block">
                    <strong>必填，必須選擇一個選項</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="notice" class="col-3 col-form-label">備註</label>
                <div class="col-9">
                    <textarea class="form-control" name="notice" >@if( !is_null($user) ){{ $user->notice }}@endif</textarea>
                </div>
            </div>

            <div class="form-group mb-3">

                <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                <button type="button" onclick="window.location='{{ url("mge/users" )}}'" class="btn btn-secondary waves-effect">返回</button>
                @if( !is_null($user) )
                <a href="{{ url("mge/student/delete/" . $user->id )}}" onclick="javascript:return confirm('確認刪除會員?');" class="btn btn-danger waves-effect">刪除</a>
                @endif
                
            </div>

          
        </div> <!-- end card-box -->
    </div> <!-- end col -->
    
</div>
<!-- end row -->
</form>
@endsection
