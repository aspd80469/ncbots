@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                    策略管理 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/botStgys/add') }}'"><i class="fas fa-plus"></i> 新增策略</button>
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-lg-12">         
                           
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
                                <th>#策略ID</th>
                                <th>策略名稱</th>
                                <th>策略Function</th>
                                <th>狀態</th>
                                <th>說明</th>
                                <th>最大DCA次數</th>
                                <th>rBuy1h%</th>
                                <th>rSell1h%</th>
                                <th>rBuy2h%</th>
                                <th>rSell2h%</th>
                                <th>rBuy4h%</th>
                                <th>rSell4h%</th>
                                <th>rBuy6h%</th>
                                <th>rSell6h%</th>
                                <th>rBuy12h%</th>
                                <th>rSell12h%</th>
                                <th>rBuyDay%</th>
                                <th>rSelDay%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $botStgys as $botStgy )
                            <tr>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        #{{ $botStgy->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->stgyName }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->stgyMapfun }}
                                    </a>
                                </td>
                                <td>
                                    @if( $botStgy->status == '0' )
                                    <span class="badge bg-success font-16">啟用</span>
                                    @else
                                    <span class="badge bg-danger font-16">停用</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->notice }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->maxDCAqty }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuy1h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSell1h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuy2h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSell2h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuy4h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSell4h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuy6h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSell6h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuy12h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSell12h }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reBuyDay }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/botStgys/" . $botStgy->id )}}">
                                        {{ $botStgy->reSellDay }}
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                            @if( $botStgys->isEmpty() )
                            <tr>
                                <td>
                                    目前沒有策略
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $botStgys->appends(Request::except('page'))->links() }}
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
