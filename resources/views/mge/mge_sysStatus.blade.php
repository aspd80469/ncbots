
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">系統狀態 &nbsp; &nbsp;
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="alert alert-success" role="alert">
                    <i class="mdi mdi-check-all me-2"></i> 系統正常
                </div>
                
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->

<script language="javascript">
    setTimeout(function(){
        window.location.reload(1);
    }, 3000);
</script>
@endsection
