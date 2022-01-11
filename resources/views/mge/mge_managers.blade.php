@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">管理帳號 &nbsp; &nbsp;
                  <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/managers/add') }}'"><i class="fas fa-plus"></i> 新增帳號</button>
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
                                <th>帳號</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $managers as $manager )
                            <tr>
                                <td>
                                    <a href="{{ url('mge/managers/' . $manager->id )}}" class="font-weight-bold">
                                    {{ $manager->account }}
                                    </a>
                                </td>
                                <td>
                                    @if( $manager->id != 1 ) 
                                    <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="window.location='{{ url("mge/managers/delete/" . $manager->id  )}}'">刪除</button>
                                    @endif
                                  </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $managers->appends(Request::except('page'))->links() }}
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
