
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                會員資料
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
					{{ URL::to('profile')}}
					">
					@csrf
					
                        <div class="mb-3">
                            <label for="sname" class="form-label">姓名</label>
                            <input type="text" id="sname" name="sname" class="form-control" maxlength="20" placeholder="請輸入姓名" value="{{ Auth::User()->name }}">
							
							@if( $errors->has('sname') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，請輸入姓名，最大長度20字元</strong>
                                </span>
							@endif
					
                        </div>
						
                        <div class="mb-3">
                            <label for="password" class="form-label">密碼</label>
                            <input type="text" id="password" name="password" class="form-control" placeholder="請輸入密碼，無變更請留空，最少6個字元" >

                            @if( $errors->has('password') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，請輸入密碼，最少6個字元</strong>
                                </span>
							@endif

                        </div>

                        <div class="mb-3">
                            <label for="tgId" class="form-label">Telegram ID</label>
                            <input type="text" id="tgId" name="tgId" class="form-control" maxlength="20" placeholder="請輸入Telegram ID" value="{{ Auth::User()->tgId }}">
							
							@if( $errors->has('tgId') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，請輸入姓名，最大長度20字元</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>				
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection