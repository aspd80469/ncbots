
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                燃料費異動
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
					{{ URL::to('mge/userBurnMoney/')}}/{{ $user->id }}
					">
					@csrf

                        <div class="mb-3">
                            <label for="burnMoney" class="form-label">燃料費</label>
                            <h2>{{ $user->burnMoney }}</h2> 
                        </div>
                    
                        <div class="mb-3">
                            <label for="typeStatus" class="form-label">異動種類</label>
                            <select class="form-control" name="typeStatus" required>
                                <option value="">---請選擇---</option>
                                <option value="0" >增加</option>
                                <option value="1" >減少</option>
                            </select>

                            @if( $errors->has('typeStatus') )
                            <span class="help-block" style="color: red;">
                            <strong>必填，必須選擇一個選項</strong>
                            </span>
                            @endif
                    
                        </div>
                    
                        <div class="mb-3">
                            <label for="burnMoney" class="form-label">燃料費</label>
                            <input type="numeric" id="burnMoney" name="burnMoney" class="form-control" placeholder="" value="" min="0" required>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">更新</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/users" )}}'">返回</button>
                
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection