@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">會員管理 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/user') }}'">會員建檔</button>
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
                        <form class="row g-3" role="form" method="POST" enctype="multipart/form-data" action="{{ url('mge/users') }}">
                            @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="status-select" class="mr-2">Email</label>
                                <input type="text" class="form-control" id="s_tnum" name="s_tnum" value="{{ old('s_tnum') }}" placeholder="">
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="status-select" class="mr-2">備註</label>
                                <input type="text" class="form-control"  id="s_notice" name="s_notice" value="{{ old('s_notice') }}">
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
                                <th>Email</th>
                                <th>姓名</th>
                                <th>備註</th>
                                <th>狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $users as $user )
                            <tr>
                                <td>
                                  <a href="{{ url('mge/user/' . $user->id )}}" class="font-weight-bold">
                                    {{ $user->email }}
                                  </a>
                                </td>
                                <td>
                                  <a href="{{ url("mge/user/" . $user->id )}}" class="font-weight-bold">
                                    {{ $user->name }}
                                  </a>
                                </td>
                                <td>
                                  {{ $user->notice }}
                                </td>
                                <td>
                                    @if( $user->status == '0')
                                    啟用
                                    @else
                                    停用
                                    @endif
                                  </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $users->appends(Request::except('page'))->links() }}
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