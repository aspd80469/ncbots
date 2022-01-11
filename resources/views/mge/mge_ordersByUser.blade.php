@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">會員下單紀錄 &nbsp; &nbsp;
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-13">
                    <div class="col-lg-12">
                        <form class="row g-3" role="form" method="POST" enctype="multipart/form-data" action="{{ url('mge/orders') }}">
                            {{-- @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="s_symbol" class="mr-2">幣種</label>
                                <input type="text" class="form-control" id="s_symbol" name="s_symbol" value="{{ old('s_symbol') }}" placeholder="">
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="s_qty" class="mr-2">數量</label>
                                <input type="text" class="form-control"  id="s_qty" name="s_qty" value="{{ old('s_qty') }}">
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="s_exchange" class="mr-2">交易所</label>
                                <select class="form-control" name="s_exchange">
                                    <option value="">---請選擇---</option>
									<option value="binance" @if ( old('s_exchange') == 'binance' ) selected @endif>Binance</option>
									<option value="ftx" @if ( old('exchs_exchangeange') == 'ftx' ) selected @endif>FTX</option>
                                    <option value="bybit" @if ( old('s_exchange') == 'bybit' ) selected @endif>Bybit</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="s_direction" class="mr-2">方向</label>
                                <select class="form-control" name="s_direction">
                                    <option value="">---請選擇---</option>
									<option value="buy" @if ( old('s_direction') == 'buy' ) selected @endif>買入</option>
									<option value="sell" @if ( old('s_direction') == 'sell' ) selected @endif>賣出</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <br>
                                <button type="submit" class="btn btn-blue waves-effect waves-light">搜尋</button>
                            </div> --}}
                            
                        </form>                            
                    </div>
                    <div class="col-lg-4">
                        <div class="text-lg-right">

                        </div>
                    </div><!-- end col-->
                </div>

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

                            @foreach( $orders as $order )
                            <tr>
                                <th scope="row">{{ $order->id }}</th>
                                <th >{{ $order->myBotId }}</th>
                                <th >{{ $order->symbol }}</th>
                                <th >
                                    @if( $order->isTrade == 0 )
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
                                                <th>機器人ID</th>
                                                <th>幣種</th>
                                                <th>數量</th>
                                                <th>時框</th>
                                                <th>方向</th>
                                                <th>交易所</th>
                                                <th>時間</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach( $order->getorderLog as $olog )
                                            <tr>
                                                <th scope="row">{{ $olog->id }}</th>
                                                <td>{{ $olog->myBotId }}</td>
                                                <td>{{ $olog->symbol }}</td>
                                                <td>{{ $olog->qty }}</td>
                                                <td>
                                                    @if( $olog->timeFrame == "15m" )
                                                    15M
                                                    @elseif( $olog->timeFrame == "30m" )
                                                        30M
                                                    @elseif( $olog->timeFrame == "1h" )
                                                        1H
                                                    @elseif( $olog->timeFrame == "2h" )
                                                        2H
                                                    @elseif( $olog->timeFrame == "4h" )
                                                        4H
                                                    @elseif( $olog->timeFrame == "6h" )
                                                        6H
                                                    @elseif( $olog->timeFrame == "12h" )
                                                        12H
                                                    @elseif( $olog->timeFrame == "day" )
                                                        天
                                                    @elseif( $olog->timeFrame == "week" )
                                                        周
                                                    @endif
                                                </td>
                                                <td>
                                                    @if( $olog->direction == "buy" )
                                                    買入
                                                    @else
                                                    賣出	
                                                    @endif
                                                </td>
                                                <td>
                                                    @if( $olog->exchange == "binance" )
                                                        Binance
                                                    @elseif( $olog->exchange == "ftx" )
                                                        FTX
                                                    @elseif( $olog->exchange == "bybit" )
                                                        Bybit
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
                    {{ $orders->appends(Request::except('page'))->links() }}
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