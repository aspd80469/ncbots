@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">參數設定 &nbsp; &nbsp;
                
                <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('mge/settings/add') }}'"><i class="fas fa-plus"></i> 新增參數</button>
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
                                <th>參數</th>
                                <th>描述</th>
                                <th>設定值</th>
                                <th>管理</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $settings as $setting )
                            <tr>
                                <td>
                                  {{ $setting->name }}
                                </td>
                                <td>
                                    {{ $setting->sdesc }}
                                  </td>
                                <td>
                                    {{ $setting->value }}
                                  </td>
                                <td>
                                    <button type="submit" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url("mge/settings/" . $setting->id )}}'">編輯</button>
                                  </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination pagination-rounded justify-content-end my-2">
                    {{ $settings->appends(Request::except('page'))->links() }}
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
