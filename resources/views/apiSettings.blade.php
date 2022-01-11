
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                API Key 設定 &nbsp; &nbsp;
                <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('/apiSettings/0') }}'"><i class="fas fa-plus"></i> 新增API Key</button>
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
								<th>交易所</th>
                                <th>API Key</th>
                                <th>備註</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $apiSettings as $apiSetting )
                            <tr>
                                <td>
                                    <a href="{{ url("/apiSettings/" . $apiSetting->id )}}">
                                    @if( $apiSetting->exchange == "binance")
                                    Binance
                                    @elseif( $apiSetting->exchange == "ftx")
                                    FTX
                                    @elseif( $apiSetting->exchange == "bybit")
                                    ByBit
                                    @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/apiSettings/" . $apiSetting->id )}}">
                                    {{ $apiSetting->apikey }}
                                    </a>
                                </td>
                                    <a href="{{ url("/apiSettings/" . $apiSetting->id )}}">
                                    {{ $apiSetting->notice }}
                                    </a>
                                <td>
                                    <a href="{{ url("/apiSettings/" . $apiSetting->id )}}">
                                    {{ $apiSetting->id }}
                                    </a>
                                </td>
                                
                                <td>
                                    @if( $apiSetting->botUsed == 0 )
                                    閒置中
                                    @else
                                    已使用
                                    @endif
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $apiSettings->appends(Request::except('page'))->links() }}
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