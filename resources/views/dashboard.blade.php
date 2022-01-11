
@extends('layouts.app')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if(!Auth::guard('manager')->user())
                最新消息 &nbsp; &nbsp;
                @endif
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 
@if(!Auth::guard('manager')->user())
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-centered mb-0 font-16">
                        <thead class="thead-light">
                            <tr>
								<th>標題</th>
                                <th>刊登日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $news as $new )
                            <tr>
                                <td>
                                    <a href="{{ url("/news/" . $new->id )}}" >
                                    {{ $new->title }}
                                    </a>
                                </td>
                                <td>
									{{ $new->created_at }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>
<!-- end row -->
@endif

@endsection