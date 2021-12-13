@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">方案管理 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/userPlan') }}'">建立方案</button>
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
								<th>方案名稱</th>
                                <th>最大下單量</th>
                                <th>Api數量</th>
                                <th>付款週期</th>
                                <th>費用</th>
                                <th>啟用</th>
                                <th>建立日期</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $userPlans as $userPlan )
                            <tr>
                                <td>
                                  {{ $userPlan->id }}
                                </td>
                                <td>
                                    {{ $userPlan->planName }}
                                </td>
                                <td>
                                    {{ $userPlan->maxOrders }}
                                </td>
                                <td>
                                    {{ $userPlan->maxApiSlot }}
                                </td>
                                <td>
                                    {{ $userPlan->payPeriod }}
                                </td>
                                <td>
                                    {{ $userPlan->fee }}
                                </td>
                                <td>
                                    {{ $userPlan->enabled }}
                                </td>
                                <td>
                                    {{ $userPlan->created_at }}
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url("mge/userPlans/" . $userPlan->id )}}'">編輯</button>
                                    <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="window.location='{{ url("mge/userPlans/delete/" . $userPlan->id  )}}'">刪除</button>
                                  </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $userPlans->appends(Request::except('page'))->links() }}
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