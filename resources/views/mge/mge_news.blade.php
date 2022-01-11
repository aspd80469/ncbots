@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                    公告管理 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/news/add') }}'"><i class="fas fa-plus"></i> 新增公告</button>
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

                        <form class="row g-3" role="form" method="POST" enctype="multipart/form-data" action="{{ url('mge/news') }}">
                            @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="status-select" class="mr-2">標題</label>
                                <input type="text" class="form-control" id="s_title" name="s_email" value="{{ old('s_title') }}" placeholder="">
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="status-select" class="mr-2">內容</label>
                                <input type="text" class="form-control"  id="s_content" name="s_notice" value="{{ old('s_content') }}">
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
                                <th>#</th>
                                <th>標題</th>
                                <th>建立日期</th>
                                <th>是否顯示</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $news as $nnews )
                            <tr>
                                <td>
                                    <a href="{{ url("/mge/news/" . $nnews->id )}}" class="text-body">
                                    #{{ $nnews->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/news/" . $nnews->id )}}" class="text-body">
                                    {{ $nnews->title }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("/mge/news/" . $nnews->id )}}" class="text-body">
                                        {{ $nnews->created_at }}
                                    </a>
                                </td>
                                <td>
                                    @if( $nnews->display == '0' )
                                    是
                                    @else
                                    否
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if( $news->isEmpty() )
                            <tr>
                                <td>
                                    目前沒有公告
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $news->appends(Request::except('page'))->links() }}
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
