@extends('layouts.app')

@section('content')
<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="
            @if( is_null($student) )
            {{ URL::to('mge/student/add')}}
            @else
            {{ URL::to('mge/student')}}/{{ $student->id }}
            @endif
            ">
            @csrf

<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-uppercase bg-light p-2 mt-0 mb-3">
                @if( is_null($student) )
                會員建檔
                @else
                會員編輯 {{ $student->s_tnum }}
                @endif
            </h4>

            @if( Auth::user()->userrole == 0 )
            <div class="form-group row mb-3">
                <label for="password" class="col-3 col-form-label">密碼</label>
                <div class="col-9">
                    <input type="password" name="password" class="form-control" placeholder="請輸入密碼，未設定預設為手機號碼@if( !is_null($student) )，無變更請留空@endif" @if( is_null($student) ) minlength="6" @endif>
                </div>
            </div>
            @endif

            <div class="form-group row mb-3">
                <label for="s_branch" class="col-3 col-form-label">分店 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select class="form-control" name="s_branch" @if( Auth::user()->userrole != 0 ) disabled @endif>
                        <option value="1" @if ( !is_null($student) && $student->s_branch == '1' ) selected @endif>三重店</option>
                        <option value="2" @if ( !is_null($student) && $student->s_branch == '2' ) selected @endif>台北店</option>
                    </select>
                    @if( $errors->has('s_branch') )
                    <span class="help-block">
                    <strong>必填，必須選擇一個選項</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_name" class="col-3 col-form-label">姓名 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="text" class="form-control" name="s_name" minlength="2" maxlength="20" value="@if( !is_null($student) ){{ $student->s_name }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif required>
                    @if( $errors->has('s_name') )
                    <span class="help-block">
                    <strong>必填，最大長度為20字元</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_gender" class="col-3 col-form-label">性別 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <div class="radio form-check-inline">
                        <input type="radio" name="s_gender" id="s_gender" value="1" @if ( !is_null($student) && $student->s_gender == 1 ) checked @endif @if( Auth::user()->userrole != 0 ) disabled @endif required>
                        <label for="inlineRadio1"> 男 </label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" name="s_gender" id="s_gender" value="2" @if ( !is_null($student) && $student->s_gender == 2 ) checked @endif @if( Auth::user()->userrole != 0 ) disabled @endif required>
                        <label for="inlineRadio2"> 女 </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_birth" class="col-3 col-form-label">出生年月日</label>
                <div class="col-9">
                    <input type="text" class="form-control" id="datepicker" data-provide="datepicker" data-date-autoclose="true" name="s_birth" data-toggle="input-mask" data-mask-format="0000/00/00" maxlength="10" placeholder="YYYY/MM/DD" autocomplete="off" value="@if( !is_null($student) ) @if( date('Y/m/d', strtotime($student->s_birth)) != "1970/01/01" ) {{ date('Y/m/d', strtotime($student->s_birth)) }} @endif @endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                    @if( $errors->has('s_birth') )
                    <span class="help-block">
                    <strong>選填，格式必須為YYYY/MM/DD</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_telephone" class="col-3 col-form-label">市話</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="s_telephone" placeholder="XX-XXXXXXXX" data-toggle="input-mask" data-mask-format="00-00000000" maxlength="11" value="@if( !is_null($student) ){{ $student->s_telephone }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_cellphone" class="col-3 col-form-label">手機 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="text" class="form-control" name="s_cellphone" placeholder="09XXXXXXXX" maxlength="10" value="@if( !is_null($student) ){{ $student->s_cellphone }}@endif" pattern="[09]{2}[0-9]{8}" required @if( Auth::user()->userrole != 0 ) disabled @endif>
                    @if( $errors->has('s_cellphone') )
                    <span class="help-block">
                    <strong>必填，必須為數字</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_usuallyaddress" class="col-3 col-form-label">通訊地址</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="s_usuallyaddress" value="@if( !is_null($student) ){{ $student->s_usuallyaddress }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                </div>
            </div>
            
            <div class="form-group row mb-3">
                <label for="s_usuallyaddress" class="col-3 col-form-label">體驗日期 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="text" class="form-control" id="datepicker2" name="s_trialdatetime" placeholder="YYYY/MM/DD" data-toggle="input-mask" data-mask-format="0000/00/00" maxlength="10" autocomplete="off" value="@if( !is_null($student) && date('Y/m/d', strtotime($student->s_trialdatetime)) !="1970/01/01" ){{ date('Y/m/d', strtotime($student->s_trialdatetime)) }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_trialcoach" class="col-3 col-form-label">體驗接待教練 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select class="form-control" name="s_trialcoach" @if( Auth::user()->userrole != 0 ) disabled @endif>
                    <option value="0">尚未體驗</option>
                    @foreach( $coach as $ccoach )
                    <option value="{{ $ccoach->id }}" @if ( !is_null($student) && $student->s_trialcoach == $ccoach->id ) selected @endif >{{ $ccoach->c_tnum }}{{ $ccoach->c_name }}</option>
                    @endforeach
                    </select>
                    @if( $errors->has('s_trialcoach') )
                    <span class="help-block">
                    <strong>必填，必須選擇一個選項</strong>
                    </span>
                    @endif
                    <span class="help-block">
                        <strong>體驗接待教練為初次帶會員參觀指導之教練，合約教練為課堂預定上課教練</strong>
                    </span>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_urgcontact" class="col-3 col-form-label">緊急聯絡人</label>
                <div class="col-9">
                    <input type="text" class="form-control" name="s_urgcontact" maxlength="20" value="@if( !is_null($student) ){{ $student->s_urgcontact }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_urgcontactphone" class="col-3 col-form-label">緊急聯絡人電話</label>
                <div class="col-9">
                    <input type="number" class="form-control" name="s_urgcontactphone" maxlength="10" placeholder="09XXXXXXXX"  value="@if( !is_null($student) ){{ $student->s_urgcontactcellphone }}@endif" @if( Auth::user()->userrole != 0 ) disabled @endif>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="s_notice" class="col-3 col-form-label">備註</label>
                <div class="col-9">
                    <textarea class="form-control" name="s_notice" @if( Auth::user()->userrole != 0 ) disabled @endif>@if( !is_null($student) ){{ $student->s_notice }}@endif</textarea>
                </div>
            </div>

            <div class="form-group mb-3">
              @if( Auth::user()->userrole == 0 )
                @if( !is_null($student) )
                  <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                  <button type="button" onclick="window.location='{{ url("mge/student" )}}'" class="btn btn-secondary waves-effect">返回</button>
                  @if( !is_null($student) && $contract_qty == 0 & $tradingrecord_qty == 0 & $paymentrecord_qty == 0 )
                  <a href="{{ url("mge/student/delete/" . $student->id )}}" onclick="javascript:return confirm('確認刪除會員?');" class="btn btn-danger waves-effect">刪除</a>
                  @endif
                @endif
              @else
                <button type="button" onclick="window.location='{{ url("mge/student" )}}'" class="btn btn-secondary waves-effect">返回</button>
              @endif
            </div>

          
        </div> <!-- end card-box -->
    </div> <!-- end col -->

    <!--合約資訊-->
    @if(!is_null($contract))
    <div class="col-lg-6">

        <div class="card">
            <div class="card-body">
            <h4 class="text-uppercase bg-light p-2 mt-0 mb-3">
                合約資訊
            </h4>
            <div class="table-responsive">
                @if( $contract->count() > 0 )
                <table class="table table-centered mb-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>合約編號</th>
                            <th>簽署日期</th>
                            <th class="text-center">剩餘/總堂數</th>
                            <th>繳費狀態</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $contract as $ccontract )
                        <tr>
                            <td>
                                <a href="{{ url("mge/contract/" . $ccontract->id )}}">{{ $ccontract->fc_tnum }}</a>
                            </td>
                            <td>
                                {{ date('Y/m/d', strtotime($ccontract->fc_signddate)) }}
                            </td>
                            <td class="text-center">
                                {{ $ccontract->fc_remainingqty }} / {{ $ccontract->fc_totalqty }}
                            </td>
                            <td>
                                @if( $ccontract->fc_installment == 0 )
                                    @if( $ccontract->paymentcount == 1 )
                                    單筆已繳
                                    @else
                                    單筆未繳 {{ number_format( $ccontract->fc_amount - $ccontract->paidamountcount , 0, '.', ',') }}元
                                    @endif
                                @else
                                    @if( $ccontract->fc_installment - $ccontract->paymentcount == 0 )

                                        共分{{ $ccontract->fc_installment }}期已繳清
                                    @else

                                        分期({{ $ccontract->fc_installment - $ccontract->paymentcount }}/{{ $ccontract->fc_installment }})
                                        <br>
                                        已繳：
                                        {{ number_format( $ccontract->paidamountcount , 0, '.', ',') }}元
                                        <br>
                                        剩餘
                                        {{ number_format( $ccontract->fc_amount - $ccontract->paidamountcount , 0, '.', ',') }}
                                        元

                                    @endif

                                
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                目前沒有合約紀錄
                @endif
            </div>
            </div>
        </div>
    </div>
    @endif

    <!--新增會員的新增合約表格-->
    @if( is_null($student) )

    <div class="col-lg-6">
        <div class="card-box">
            <h4 class="text-uppercase bg-light p-2 mt-0 mb-3">
                新增合約資料
            </h4>

            <div class="form-group row mb-3">
                <label for="fc_coachsno" class="col-3 col-form-label">教練姓名 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select class="form-control" name="fc_coachsno" required>
                    <option value="">---請選擇---</option>
                    @foreach( $coach as $ccoach )
                    <option value="{{ $ccoach->id }}" >{{ $ccoach->c_tnum }} {{ $ccoach->c_name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_type" class="col-3 col-form-label">合約類型 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <div class="radio form-check-inline">
                        <input type="radio" name="fc_type" id="fc_type" value="1" required>
                        <label for="inlineRadio1"> 教練課 </label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" name="fc_type" id="fc_type" value="2" required>
                        <label for="inlineRadio2"> 團體課 </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_paymenttype" class="col-3 col-form-label">繳費類型 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <div class="form-check-inline">
                        <input type="radio" name="fc_paymenttype" id="fc_paymenttype" value="1" required>
                        <label for="inlineRadio1"> 單筆 </label>
                        &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="fc_paymenttype" id="fc_paymenttype" value="2" required>
                        <input type="number" style="width: 40px;" name="fc_installment" value="2" min="2" max="12">
                        <label for="inlineRadio1"> 分期 </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_coursepirce" class="col-3 col-form-label">單堂價格 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="number" class="form-control" id="fc_coursepirce" name="fc_coursepirce" required >
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_totalqty" class="col-3 col-form-label">課程堂數 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="number" class="form-control" id="fc_totalqty" name="fc_totalqty" required >
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_remainingqty" class="col-3 col-form-label">剩餘堂數 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="number" class="form-control" id="fc_remainingqty" name="fc_remainingqty" required >
                <span>*舊有合約請輸入剩餘堂數，新合約請堂數與課程堂數一致</span>
                </div>
            </div>

            @if( !is_null($contract) )
            <div class="form-group row mb-3">
                <label for="fc_remainingqty" class="col-3 col-form-label">剩餘堂數 <span class="text-danger">*</span></label>
                <div class="col-9">
                    {{ $contract->fc_remainingqty }}
                </div>
            </div>
            @endif

            <div class="form-group row mb-3">
                <label for="fc_amount" class="col-3 col-form-label">總金額 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="number" class="form-control" id="fc_amount" name="fc_amount" required>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_signddate" class="col-3 col-form-label">簽署日期 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <input type="text" class="form-control" id="datepicker" name="fc_signddate" data-toggle="input-mask" data-mask-format="0000/00/00" maxlength="10" placeholder="YYYY/MM/DD" required autocomplete="off">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="fc_billingtype" class="col-3 col-form-label">課程期限 <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select class="form-control" name="fc_billingtype" @if( Auth::user()->userrole != 0 ) disabled @endif>
                    <option value="3" @if ( !is_null($student) && $student->fc_billingtype == '3' ) selected @endif>3個月</option>
                    <option value="6" @if ( !is_null($student) && $student->fc_billingtype == '6' ) selected @endif>6個月</option>
                    <option value="9" @if ( !is_null($student) && $student->fc_billingtype == '9' ) selected @endif>9個月</option>
                    <option value="12" @if ( !is_null($student) && $student->fc_billingtype == '12' ) selected @endif>12個月</option>
                    <option value="18"" @if ( !is_null($student) && $student->fc_billingtype == '18' ) selected @endif>18個月</option>
                    </select>
                    <strong>有效日期會從課程第一堂上課時起算，可至合約編輯修改有效日期</strong>
                    @if( $errors->has('fc_billingtype') )
                    <span class="help-block">
                    <strong>必填，必須選擇一個選項</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group mb-3">
              @if( Auth::user()->userrole == 0 )
                
                  <button type="submit" class="btn btn-blue waves-effect waves-light">儲存</button>
                  <button type="button" onclick="window.location='{{ url("mge/student" )}}'" class="btn btn-secondary waves-effect">返回</button>
                  @if( !is_null($student) && $contract_qty == 0 & $tradingrecord_qty == 0 & $paymentrecord_qty == 0 )
                  <a href="{{ url("mge/student/delete/" . $student->id )}}" onclick="javascript:return confirm('確認刪除會員?');" class="btn btn-danger waves-effect">刪除</a>
                  @endif
                
              @else
                <button type="button" onclick="window.location='{{ url("mge/student" )}}'" class="btn btn-secondary waves-effect">返回</button>
              @endif
            </div>
        </div> <!-- end card-box -->
    </div> <!-- end col -->

    @endif
    
</div>
<!-- end row -->
</form>
@endsection
