@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">

            </div>
            <h4 class="page-title">
                參數設定
                &nbsp;&nbsp;&nbsp;
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 

<div class="row">

        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">

                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="
                    {{ URL::to('mge/settings/')}}
                    ">
                    @csrf

                        <div class="form-group row mb-3">
                            <label for="display" class="col-3 col-form-label">啟用會員註冊<span class="text-danger">*</span></label>
                            <div class="col-9">
                                
                                <select id="allowRegister" name="allowRegister" class="form-control">
                                    <option value="Y" @if( $allowRegister == "Y" ) selected @endif>是</option>
                                    <option value="N" @if( $allowRegister == "N" ) selected @endif>否</option>
                                </select>
                                                            
                                @error('allowRegister')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="display" class="col-3 col-form-label">會員註冊推薦碼必填<span class="text-danger">*</span></label>
                            <div class="col-9">
                                
                                <select id="requiredRefCode" name="requiredRefCode" class="form-control">
                                    <option value="Y" @if( $requiredRefCode == "Y" ) selected @endif>是</option>
                                    <option value="N" @if( $requiredRefCode == "N" ) selected @endif>否</option>
                                </select>
                                                            
                                @error('requiredRefCode')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-9">
                                <button type="submit" class="btn btn-blue waves-effect waves-light" >儲存</button>
                            </div>
                        </div>
                    </form>

                </div>  <!-- end card-body -->
            </div>  <!-- end card -->
        </div>  <!-- end col -->

    </div>
<!-- end row -->

@endsection