@extends('layouts.app')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title">
                
            </div>
            <h4 class="page-title">
                @if( is_null($botStgy) )
                新增策略
                @else
                編輯策略
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
                    @if( is_null($botStgy) )
                    {{ URL::to('mge/botStgys/add')}}
                    @else
                    {{ URL::to('mge/botStgys')}}/{{ $botStgy->id }}
                    @endif
                    ">
                    @csrf

                        <div class="mb-3">
                            <label for="title" class="col-3 col-form-label">策略名稱<span class="text-danger">*</span></label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="stgyName" name="stgyName" value="@if(!is_null($botStgy)){{ $botStgy->stgyName }}@endif" required >
                                                            
                                @error('stgyName')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stgyMapfun" class="col-3 col-form-label">策略Function<span class="text-danger">*</span></label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="stgyMapfun" name="stgyMapfun" value="@if(!is_null($botStgy)){{ $botStgy->stgyMapfun }}@endif" required >
                                                            
                                @error('stgyMapfun')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror

                                <span role="alert" style="color: black;">
                                    請確認於程式中已新增該項策略相關程式
                                </span>
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notice" class="col-3 col-form-label">策略說明</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="notice" name="notice" value="@if(!is_null($botStgy)){{ $botStgy->notice }}@endif" >
                                                            
                                @error('notice')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="allowSymbol" class="col-3 col-form-label">允許操作幣種</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="allowSymbol" name="allowSymbol" value="@if(!is_null($botStgy)){{ $botStgy->allowSymbol }}@endif" >
                                                            
                                @error('allowSymbol')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fixedBuyAmt" class="col-3 col-form-label">預設固定買入金額</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="fixedBuyAmt" name="fixedBuyAmt" value="@if(!is_null($botStgy)){{ $botStgy->fixedBuyAmt }}@endif" >
                                                            
                                @error('fixedBuyAmt')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuy1h" class="col-3 col-form-label">rBuy1h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuy1h" name="reBuy1h" value="@if(!is_null($botStgy)){{ $botStgy->reBuy1h }}@endif" required >
                                                            
                                @error('reBuy1h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSell1h" class="col-3 col-form-label">rSell1h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSell1h" name="reSell1h" value="@if(!is_null($botStgy)){{ $botStgy->reSell1h }}@endif" required >
                                                            
                                @error('reSell1h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuy2h" class="col-3 col-form-label">rBuy2h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuy2h" name="reBuy2h" value="@if(!is_null($botStgy)){{ $botStgy->reBuy2h }}@endif" required >
                                                            
                                @error('reBuy2h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSell2h" class="col-3 col-form-label">rSell2h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSell2h" name="reSell2h" value="@if(!is_null($botStgy)){{ $botStgy->reSell2h }}@endif" required >
                                                            
                                @error('reSell2h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuy4h" class="col-3 col-form-label">rBuy4h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuy4h" name="reBuy4h" value="@if(!is_null($botStgy)){{ $botStgy->reBuy4h }}@endif" required >
                                                            
                                @error('reBuy4h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSell4h" class="col-3 col-form-label">rSell4h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSell4h" name="reSell4h" value="@if(!is_null($botStgy)){{ $botStgy->reSell4h }}@endif" required >
                                                            
                                @error('reSell4h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuy6h" class="col-3 col-form-label">rBuy6h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuy6h" name="reBuy6h" value="@if(!is_null($botStgy)){{ $botStgy->reBuy6h }}@endif" required >
                                                            
                                @error('reBuy6h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSell6h" class="col-3 col-form-label">rSell6h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSell6h" name="reSell6h" value="@if(!is_null($botStgy)){{ $botStgy->reSell6h }}@endif" required >
                                                            
                                @error('reSell6h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuy12h" class="col-3 col-form-label">rBuy12h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuy12h" name="reBuy12h" value="@if(!is_null($botStgy)){{ $botStgy->reBuy12h }}@endif" required >
                                                            
                                @error('reBuy12h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSell12h" class="col-3 col-form-label">rSell12h%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSell12h" name="reSell12h" value="@if(!is_null($botStgy)){{ $botStgy->reSell12h }}@endif" required >
                                                            
                                @error('reSell12h')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reBuyDay" class="col-3 col-form-label">rBuyDay%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reBuyDay" name="reBuyDay" value="@if(!is_null($botStgy)){{ $botStgy->reBuyDay }}@endif" required >
                                                            
                                @error('reBuyDay')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reSellDay" class="col-3 col-form-label">rSellDay%</label>
                            <div class="col-12">
                                <input type="text" class="form-control" id="reSellDay" name="reSellDay" value="@if(!is_null($botStgy)){{ $botStgy->reSellDay }}@endif" required >
                                                            
                                @error('reSellDay')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="col-3 col-form-label">狀態</label>
                            <div class="col-12">
                                <select id="sstatus" name="sstatus" class="form-control">
                                    <option value="0" @if(!is_null($botStgy) && $botStgy->status == "0" ) selected @endif>啟用</option>
                                    <option value="1" @if( is_null($botStgy) | (!is_null($botStgy) && $botStgy->status == "1") ) selected @endif>停用</option>
                                </select>
                                                            
                                @error('status')
                                <span role="alert" style="color: red;">
                                    {{ $message }}
                                </span>
                                @enderror
                                
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                            <button type="button"" class="btn btn-secondary waves-effect" onclick="window.location='{{ url("mge/botStgys" )}}'">返回</button>
                        </div>
                        
                    </form>

                </div> <!-- end card-body -->
            </div>
        </div>
    </div>
</div>

@endsection