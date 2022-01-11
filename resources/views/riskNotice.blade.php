@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
            </div>
            <h4 class="page-title">風險聲明</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="font-16">
                            <h2 class="text-muted">政策:</h2>

                            {!! html_entity_decode($sysRiskNoticeText) !!}

                        </div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection