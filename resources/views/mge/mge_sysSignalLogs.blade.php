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
                                <label for="timeFrame-select" class="mr-2">時框</label>
								<select class="form-control" name="timeFrame">
									<option value="15m" @if ( old('timeFrame') == '15m' ) selected @endif>15M</option>
									<option value="30m" @if ( old('timeFrame') == '30m' ) selected @endif>30M</option>
									<option value="1h" @if ( old('timeFrame') == '1h' ) selected @endif>1H</option>
									<option value="4h" @if ( old('timeFrame') == '4h' ) selected @endif>4H</option>
									<option value="12h" @if ( old('timeFrame') == '12h' ) selected @endif>12H</option>
									<option value="day" @if ( old('timeFrame') == 'day' ) selected @endif>天</option>
									<option value="week" @if ( old('timeFrame') == 'week' ) selected @endif>周</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="direction-select" class="mr-2">方向</label>
								<select class="form-control" name="direction">
									<option value="buy" @if ( old('direction') == 'buy' ) selected @endif>買入</option>
									<option value="sell" @if ( old('direction') == 'sell' ) selected @endif>賣出</option>
								</select>
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="exchange-select" class="mr-2">交易所</label>
								<select class="form-control" name="exchange">
									<option value="binance" @if ( old('exchange') == 'binance' ) selected @endif>Binance</option>
									<option value="ftx" @if ( old('exchange') == 'ftx' ) selected @endif>FTX</option>
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
                                <th>token</th>
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
                                  {{ $sysSignalLog->token }}
                                </td>
                                <td>
                                  {{ $sysSignalLog->price }}
                                </td>
                                <td>
                                  {{ $sysSignalLog->timeFrame }}
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