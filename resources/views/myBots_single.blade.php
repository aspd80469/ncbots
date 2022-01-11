
@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                管理機器人設定
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
					@if( is_null($myBot) )
                    {{ URL::to('myBots/add')}}
                    @else
                    {{ URL::to('myBots/')}}/{{ $myBot->id }}
                    @endif
					">
					@csrf
					
                        <div class="mb-3">
                            <label for="invAmount" class="form-label">投資金額</label>
                            <input type="number" id="invAmount" name="invAmount" class="form-control" placeholder="請輸入投資金額" min="0" required>
							
							@if( $errors->has('invAmount') )
                                <span class="help-block" style="color: red;">
                                <strong>必填，請輸入投資金額</strong>
                                </span>
							@endif
					
                        </div>

                        <div class="mb-3">
                            <label for="usedStgy" class="form-label">使用策略</label>
							
                            <select id="usedStgy" name="usedStgy" class="form-control">
                                <option value="">---請選擇---</option>
                                @foreach($botsStgys as $botsStgy)
                                <option value="{{ $botsStgy->id }}" @if(!is_null($myBot) && $myBot->usedStgy == $botsStgy->id ) selected @endif>{{ $botsStgy->stgyName }}</option>
                                @endforeach
                            </select>
                                                        
                            @error('display')
                            <span role="alert" style="color: red;">
                                {{ $message }}
                            </span>
                            @enderror
					
                        </div>

                        <div class="mb-3">
                            <label for="apiKeyId1" class="form-label">API Key 1</label>
							
                            <select id="apiKeyId1" name="apiKeyId1" class="form-control" required>
                                <option value="">---請選擇---</option>
                                @foreach($userKeys as $userKey)
                                <option value="{{ $userKey->id }}" @if(!is_null($myBot) && $myBot->apiKeyId1 == $userKey->id ) selected @endif>{{ $userKey->exchange }} - {{ $userKey->apikey }}</option>
                                @endforeach
                            </select>
                                                        
                            @error('apiKeyId1')
                            <span role="alert" style="color: red;">
                                {{ $message }}
                            </span>
                            @enderror
					
                        </div>

                        <div class="mb-3">
                            <label for="apiKeyId2" class="form-label">API Key 2</label>
							
                            <select id="apiKeyId2" name="apiKeyId2" class="form-control">
                                <option value="">---請選擇---</option>
                                @foreach($userKeys as $userKey)
                                <option value="{{ $userKey->id }}" @if(!is_null($myBot) && $myBot->apiKeyId2 == $userKey->id ) selected @endif>{{ $userKey->exchange }} - {{ $userKey->apikey }}</option>
                                @endforeach
                            </select>
                                                        
                            @error('apiKeyId2')
                            <span role="alert" style="color: red;">
                                {{ $message }}
                            </span>
                            @enderror
					
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("myBots" )}}'">返回</button>			
                        </div>
                        
                    </form>
                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>
@endsection