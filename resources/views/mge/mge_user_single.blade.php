
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($user) )
                建立會員
                @else
                編輯會員
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
					@if( is_null($user) )
					{{ URL::to('mge/user')}}
					@else
					{{ URL::to('mge/user')}}/{{ $user->id }}
					@endif
					">
					@csrf
					
						<div class="mb-3">
                            <label for="email" class="form-label">Email</label>
							@if( is_null($user) )
							
								<input type="text" id="email" name="email" class="form-control" placeholder="請輸入Email" value="">
								
								@if( $errors->has('email') )
								<span class="help-block">
								<strong>必填</strong>
								</span>
								@endif
								
							@else
							
								{{ $user->email }}
							
							@endif
							
							
					
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">姓名</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="請輸入姓名" value="@if( !is_null($user) ){{ $user->name }}@endif">
							
							@if( $errors->has('name') )
							<span class="help-block">
							<strong>必填，最大長度為20字元</strong>
							</span>
							@endif
					
                        </div>
						
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼<span class="text-danger">*</span></label>
                            <input type="text" id="password" name="password" class="form-control" placeholder="請輸入密碼@if( !is_null($user) )，無變更請留空@else 最少6個字元  @endif" >
                        </div>
						
						<div class="mb-3">
                            <label for="name" class="form-label">備註</label>
                            <textarea class="form-control" name="notice" >@if( !is_null($user) ){{ $user->notice }}@endif</textarea>
					
                        </div>
						
						
						<div class="mb-3">
						
							<select class="form-control" name="status">
								<option value="0" @if ( !is_null($user) && $user->status == '0' ) selected @endif>啟用</option>
								<option value="1" @if ( !is_null($user) && $user->status == '1' ) selected @endif>停用</option>
							</select>
							@if( $errors->has('status') )
							<span class="help-block">
							<strong>必填，必須選擇一個選項</strong>
							</span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/users" )}}'">返回</button>
							
							@if( !is_null($user) )
							<a href="{{ url("mge/user/delete/" . $user->id )}}" onclick="javascript:return confirm('確認刪除會員?');" class="btn btn-danger waves-effect">刪除</a>
							@endif
				
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection