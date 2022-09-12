@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">會員下單 &nbsp; &nbsp;
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
                            @csrf
                            <div class="col-xl-2 col-md-3">
                                <label for="status-select" class="mr-2">Email</label>
                                <input type="text" class="form-control" id="s_email" name="s_email" value="{{ old('s_email') }}" placeholder="">
                            </div>
                            <div class="col-xl-2 col-md-3">
                                <label for="s_name" class="mr-2">姓名</label>
                                <input type="text" class="form-control" id="s_name" name="s_name" value="{{ old('s_name') }}" placeholder="">
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
                                <th>機器人列表</th>
                                <th>下單紀錄</th>
                                <th>Email</th>
                                <th>姓名</th>
                                <th>燃料費</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $users as $user )
                            <tr>
                                <td>
                                    <a href="{{ url("mge/myBots/" . $user->id )}}" class="font-weight-bold">
                                        檢視機器人
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("mge/orders/" . $user->id )}}" class="font-weight-bold">
                                        檢視下單紀錄
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('mge/orders/' . $user->id )}}" class="font-weight-bold">
                                        {{ $user->email }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url("mge/user/" . $user->id )}}" class="font-weight-bold">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ $user->burnMoney }}
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