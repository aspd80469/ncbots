
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">系統紀錄 &nbsp; &nbsp;
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
                                <th>#</th>
								<th>時間</th>
                                <th>類型</th>
                                <th>動作</th>
								<th>內容</th>
                                <th>使用者</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sysLogs as $sysLog )
                            <tr>
                                <td>
                                  {{ $sysLog->id }}
                                </td>
                                <td>
									{{ $sysLog->created_at }}
                                </td>
								<td>
									{{ $sysLog->type }}
                                </td>
                                <td>
									{{ $sysLog->operation }}
                                </td>
								<td>
									{{ $sysLog->msg }}
                                </td>
                                <td>
									{{ $sysLog->userid }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $sysLogs->appends(Request::except('page'))->links() }}
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
