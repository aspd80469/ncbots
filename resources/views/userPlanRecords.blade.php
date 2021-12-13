
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">購買會員方案紀錄 &nbsp; &nbsp;
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
								<th>方案</th>
                                <th>起訖日</th>
								<th>實付金額</th>
								<th>付款日期</th>
								<th>類型</th>
								<th>TxID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $userPlanRecords as $userPlanRecord )
                            <tr>
                                <td>
                                  {{ $userPlanRecord->id }}
                                </td>
                                <td>
									{{ $userPlanRecord->st_date }} - {{ $userPlanRecord->ed_date }}
                                </td>
								<td>
									{{ $userPlanRecord->paidAmount }}
                                </td>
								<td>
									{{ $userPlanRecord->paidDay }}
                                </td>
								<td>
									{{ $userPlanRecord->type }}
                                </td>
								<td>
									{{ $userPlanRecord->TxID }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $userPlanRecords->appends(Request::except('page'))->links() }}
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