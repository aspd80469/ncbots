@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
            </div>
            <h4 class="page-title">
                #{{ $myBotId }}機器人 下單紀錄
                <button type="button" class="btn btn-secondary waves-effect waves-light" onclick="window.location='{{ url('/myBots') }}'"> 返回</button>
            </h4>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>機器人ID</th>
								<th>幣種</th>
                                {{-- <th>總買入數量</th>
                                <th>總賣出數量</th> --}}
                                <th>執行狀態</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach( $myBotOrders as $myBotOrder )
                            <tr>
                                <th scope="row">{{ $myBotOrder->id }}</th>
                                <th >{{ $myBotOrder->myBotId }}</th>
                                <th >{{ $myBotOrder->symbol }}</th>
                                <th >
                                    @if( $myBotOrder->isTrade == 0 )
                                    執行中
                                    @else
                                    已完成	
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>幣種</th>
                                                <th>數量</th>
                                                <th>方向</th>
                                                <th>時間</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $myBotOrder->getorderLog as $olog )
                                            <tr>
                                                <th scope="row">{{ $olog->id }}</th>
                                                <td>{{ $olog->symbol }}</td>
                                                <td>{{ $olog->qty }}</td>
                                                <td>
                                                    @if( $olog->direction == "buy" )
                                                    買入
                                                    @else
                                                    賣出	
                                                    @endif
                                                </td>
                                                <td>{{ $olog->created_at }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $myBotOrders->appends(Request::except('page'))->links() }}
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