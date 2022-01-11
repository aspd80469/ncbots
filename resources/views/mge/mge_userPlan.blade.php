@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">方案管理 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/userPlans') }}/add'"><i class="fas fa-plus"></i> 建立方案</button>
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
                                <th>最大機器人數量</th>
                                <th>最大下單量</th>
                                <th>最大資金上限</th>
                                <th>Api數量</th>
                                <th>季費</th>
                                <th>年費</th>
                                <th>貨幣單位</th>
                                <th>推薦</th>
                                <th>啟用</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $userPlans as $userPlan )
                            <tr>
                                <td>
                                    <a href="{{ url('mge/userPlans/' . $userPlan->id )}}" class="font-weight-bold">
                                        #{{ $userPlan->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('mge/userPlans/' . $userPlan->id )}}" class="font-weight-bold">
                                        {{ $userPlan->planName }}
                                    </a>
                                </td>
                                <td>
                                    {{ number_format( $userPlan->maxBotQty , 0 , '.', ',') }}
                                </td>
                                <td>
                                    {{ number_format( $userPlan->maxOrders , 0 , '.', ',') }}
                                </td>
                                <td>
                                    {{ number_format( $userPlan->maxAmount , 0 , '.', ',') }}
                                </td>
                                <td>
                                    {{ $userPlan->maxApiSlot }}
                                </td>
                                <td>
                                    {{ $userPlan->feeBySeason }}
                                </td>
                                <td>
                                    {{ number_format( $userPlan->feeByYear , 0 , '.', ',') }}
                                </td>
                                <td>
                                    {{ $userPlan->feeUnit }}
                                </td>
                                <td>
                                    @if( $userPlan->suggest == 1 )
                                    <span class="badge bg-success font-16">是</span>
                                    @else
                                    否
                                    @endif
                                </td>
                                <td>
                                    @if( $userPlan->enabled == 1 )
                                    <span class="badge bg-success font-16">是</span>
                                    @else
                                    否
                                    @endif
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