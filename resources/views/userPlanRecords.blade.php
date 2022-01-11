
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">方案訂單 &nbsp; &nbsp;
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
                                <th>狀態</th>
								<th>方案</th>
                                <th>生效日期</th>
                                <th>到期日期</th>
                                <th>使用天數</th>
								<th>實付金額</th>
                                <th>貨幣單位</th>
                                <th>申購時間</th>
								<th>付款日期</th>
								<th>付款方式</th>
								<th>TxID</th>
                                <th>備註</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $userPlanRecords as $userPlanRecord )
                            <tr>
                                <td>
                                    {{ $userPlanRecord->id }}
                                </td>
                                <td>
                                    @if( $userPlanRecord->status == '0' )
                                    <span class="badge bg-info font-16">新訂單</span>
                                    <a href="{{ url('userPlanRecordsCancel/' . $userPlanRecord->id )}}" class="font-weight-bold">
                                        取消
                                    </a>
                                    @elseif( $userPlanRecord->status == '1' )
                                    <span class="badge bg-primary font-16">處理中</span>
                                    @elseif( $userPlanRecord->status == '2' )
                                    <span class="badge bg-success font-16">已完成</span>
                                    @elseif( $userPlanRecord->status == '3' )
                                    <span class="badge bg-secondary font-16">已取消</span>
                                    @elseif( $userPlanRecord->status == '4' )
                                    <span class="badge bg-warning font-16">異常</span>
                                    @elseif( $userPlanRecord->status == '5' )
                                    <span class="badge bg-blue font-16">已退費</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $userPlanRecord->getUserPlan->planName }}
                                </td>
                                <td>
                                    {{ $userPlanRecord->takeDate }}
                                </td>
                                <td>
                                    {{ $userPlanRecord->edDate }}
                                </td>
                                <td>
                                    {{ $userPlanRecord->useDay }}
                                </td>
								<td>
                                    {{ number_format( $userPlanRecord->paidAmount , 0 , '.', ',') }}
                                </td>
                                <td>
									{{ $userPlanRecord->payfeeUnit }}
                                </td>
                                <td>
									{{ $userPlanRecord->applyDate }}
                                </td>
								<td>
									{{ $userPlanRecord->paidDate }}
                                </td>
								<td>
                                    @if( $userPlanRecord->payMethod = "TRC20" )
                                    區塊練轉帳-TRC20
                                    @elseif( $userPlanRecord->payMethod = "ERC20" )
                                    區塊練轉帳-ERC20
                                    @elseif( $userPlanRecord->payMethod = "SPL20" )
                                    區塊練轉帳-SOL
                                    @elseif( $userPlanRecord->payMethod = "NTDTransfer" )
                                    台幣轉帳
                                    @endif
                                </td>
								<td>
                                    <input type="text" class="form-control" id="paidTxid" name="paidTxid" value="{{ $userPlanRecord->paidTxid }}" readonly >
                                </td>
                                <td>
									{{ $userPlanRecord->notice }}
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