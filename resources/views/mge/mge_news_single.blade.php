@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($news) )
                新增公告
                @else
                編輯公告
                @endif
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
                    
                    <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="
                    @if( is_null($news) )
                    {{ URL::to('mge/news/add')}}
                    @else
                    {{ URL::to('mge/news')}}/{{ $news->id }}
                    @endif
                    ">
                    @csrf

                        <div class="mb-3">
                            <label for="title" class="col-3 col-form-label">標題<span class="text-danger">*</span></label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="title" name="title" value="@if(!is_null($news)){{ $news->title }}@endif" required >
                                                            
                                @error('title')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="col-3 col-form-label">內容</label>
                            <div class="col-12">
                                
                                <div id="snow-editor" name="content" style="height: 300px;" class="ql-container ql-snow"><div class="ql-editor" data-gramm="false" contenteditable="true">@if(!is_null($news)){!! html_entity_decode($news->content) !!}@endif</div>

                                @error('content')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="refcellphone" class="col-3 col-form-label">刊登日期</label>
                            <div class="col-12">
                                    <input type="text" class="form-control" id="created_at" name="created_at" placeholder="YYYY/MM/DD HH:mm:ss" value="@if(!is_null($news)){{ $news->created_at }}@endif" required>
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="display" class="col-3 col-form-label">顯示</label>
                            <div class="col-12">
                                <select id="display" name="display" class="form-control">
                                    <option value="0" @if(!is_null($news) && $news->display == "0" ) selected @endif>是</option>
                                    <option value="1" @if(!is_null($news) && $news->display == "1" ) selected @endif>否</option>
                                </select>
                                                            
                                @error('display')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/news" )}}'">返回</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection