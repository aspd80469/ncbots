@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                    幣安現貨報價 &nbsp; &nbsp;
                  <button type="button" class="btn btn-warning waves-effect waves-light" onclick="window.location='{{ url('mge/binancePrice/update') }}'"><i class="fas fa-sync"></i> 立即更新</button>
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

                        <form class="row g-3" role="form" method="POST" enctype="multipart/form-data" action="{{ url('mge/binancePrice') }}">
                            @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="s_symbol" class="mr-2">幣種</label>
                                <input type="text" class="form-control" id="s_symbol" name="s_symbol" value="{{ old('s_symbol') }}" placeholder="">
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
                                <th>幣種</th>
                                <th>價格</th>
                                <th>狀態</th>
                                <th>最近更新</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $symbolPrices as $symbolPrice )
                            <tr>
                                <td>
                                    {{ $symbolPrice->symbol }}
                                </td>
                                <td>
                                    {{ $symbolPrice->price }}
                                </td>
                                <td>
                                    @if( $symbolPrice->disable == '0')
                                    啟用
                                    @elseif( $symbolPrice->disable == '1')
                                    停用
                                    @endif
                                </td>
                                <td>
                                    {{ $symbolPrice->updated_at	 }}
                                </td>
                            </tr>
                            @endforeach
                            @if( $symbolPrices->isEmpty() )
                            <tr>
                                <td>
                                    目前沒有幣種報價
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $symbolPrices->appends(Request::except('page'))->links() }}
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
