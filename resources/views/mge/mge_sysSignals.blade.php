@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">訊號Token &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/sysSignals/add') }}'"><i class="fas fa-plus"></i> 新增Token</button>
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Token</th>
                                <th>描述</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sysSignals as $sysSignal )
                            <tr>
                                <td>
                                  {{ $sysSignal->ncToken }}
                                </td>
                                <td>
                                    {{ $sysSignal->tdsec }}
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url("mge/sysSignals/" . $sysSignal->id )}}'">編輯</button>
                                  </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $sysSignals->appends(Request::except('page'))->links() }}
                </div>

                <style>
                    nav {
                        overflow: scroll !important;
                    }
                </style>
                
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->
 
@endsection
