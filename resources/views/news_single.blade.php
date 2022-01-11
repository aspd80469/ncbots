@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                最新消息
            </h4>
        </div>
    </div>
</div>     
<!-- end page title --> 
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <div class="card">
                <div class="card-body">

                        <div class="mb-3">
                            <h2 class="page-title">
                                {{ $news->title }}
                            </h2>
                            
                            <label for="display" class="col-3 col-form-label">{{ $news->created_at }}</label>
                            <div class="col-9 font-16">
                                {!! $news->content !!}
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("dashboard" )}}'">返回</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection