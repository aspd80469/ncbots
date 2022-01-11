@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">訊號紀錄 &nbsp; &nbsp;
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
                        <form class="row g-3" role="form" method="POST" enctype="multipart/form-data" action="{{ url('mge/sysSignalLogs') }}">
                            @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="s_kType" class="mr-2">類型</label>
								<select class="form-control" name="s_kType">
									<option value="crypto" @if ( old('s_kType') == 'crypto' ) selected @endif>Crypto</option>
									<option value="stock" @if ( old('s_kType') == 'stock' ) selected @endif>Stock</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="s_symbol" class="mr-2">Token</label>
                                <input type="text" id="s_symbol" name="s_symbol" class="form-control" placeholder="" value="">

                            </div>
							<div class="col-xl-2 col-md-3">
                                <label for="s_timeFrame" class="mr-2">時框</label>
								<select class="form-control" name="s_timeFrame">
                                    <option value="">---請選擇---</option>
									<option value="15m" @if ( old('s_timeFrame') == '15m' ) selected @endif>15M</option>
									<option value="30m" @if ( old('s_timeFrame') == '30m' ) selected @endif>30M</option>
									<option value="1h" @if ( old('s_timeFrame') == '1h' ) selected @endif>1H</option>
                                    <option value="2h" @if ( old('s_timeFrame') == '2h' ) selected @endif>2H</option>
									<option value="4h" @if ( old('s_timeFrame') == '4h' ) selected @endif>4H</option>
                                    <option value="6h" @if ( old('s_timeFrame') == '6h' ) selected @endif>6H</option>
									<option value="12h" @if ( old('s_timeFrame') == '12h' ) selected @endif>12H</option>
									<option value="day" @if ( old('s_timeFrame') == 'day' ) selected @endif>天</option>
									<option value="week" @if ( old('s_timeFrame') == 'week' ) selected @endif>周</option>
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
                                <label for="s_exchange" class="mr-2">交易所</label>
								<select class="form-control" name="s_exchange">
                                    <option value="">---請選擇---</option>
									<option value="binance" @if ( old('s_exchange') == 'binance' ) selected @endif>Binance</option>
									<option value="ftx" @if ( old('s_exchange') == 'ftx' ) selected @endif>FTX</option>
                                    <option value="bybit" @if ( old('s_exchange') == 'bybit' ) selected @endif>Bybit</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <br>
                                <button type="submit" class="btn btn-blue waves-effect waves-light">搜尋</button>
                            </div>
                            
                        </form>                            
                    </div>
                    <div class="col-lg-4">
                        <div class="text-lg-right">

                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>時間</th>
                                <th>訊號源</th>
                                <th>Token</th>
                                <th>價格</th>
                                <th>時框</th>
                                <th>方向</th>
								<th>交易所</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $sysSignalLogs as $sysSignalLog )
                            <tr>
                                <td>
                                    {{ $sysSignalLog->created_at }}
                                </td>
                                <td>
                                    {{ $sysSignalLog->getSysSignals->tdsec }}-{{ $sysSignalLog->getSysSignals->ncToken }}
                                </td>
                                <td>
                                    {{ $sysSignalLog->token }}
                                </td>
                                <td>
                                    {{ number_format( $sysSignalLog->price , 2 , '.', ',') }}
                                </td>
                                <td>
                                    @if( $sysSignalLog->timeFrame == "15m" )
                                        15M
									@elseif( $sysSignalLog->timeFrame == "30m" )
                                        30M
                                    @elseif( $sysSignalLog->timeFrame == "1h" )
                                        1H
                                    @elseif( $sysSignalLog->timeFrame == "2h" )
                                        2H
                                    @elseif( $sysSignalLog->timeFrame == "4h" )
                                        4H
                                    @elseif( $sysSignalLog->timeFrame == "6h" )
                                        6H
                                    @elseif( $sysSignalLog->timeFrame == "12h" )
                                        12H
                                    @elseif( $sysSignalLog->timeFrame == "day" )
                                        天
                                    @elseif( $sysSignalLog->timeFrame == "week" )
									    周
									@endif
                                </td>
                                <td>
									@if( $sysSignalLog->direction == "buy" )
										買入
									@elseif( $sysSignalLog->direction == "sell" )
										賣出
									@endif
                                </td>
								<td>
									@if( $sysSignalLog->exchange == "binance" )
										Binance
									@elseif( $sysSignalLog->exchange == "ftx" )
										FTX
                                    @elseif( $sysSignalLog->exchange == "bybit" )
                                        Bybit
									@endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $sysSignalLogs->appends(Request::except('page'))->links() }}
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