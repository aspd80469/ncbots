@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-xl-10">

        <!-- Pricing Title-->
        <div class="text-center">
            <h3 class="mb-2">選擇 <b>會員方案</b></h3>
            <p class="text-muted w-50 m-auto"></p>
        </div>

        <!-- Plans -->
        <div class="row my-3">

            @foreach($userPlans as $userPlan)
            <div class="col-md-4">
                <div class="card card-pricing @if( $userPlan->suggest == 1 ) card-pricing-recommended @else card-pricing @endif">
                    <div class="card-body text-center">
                        <p class="card-pricing-plan-name fw-bold text-uppercase">{{ $userPlan->planName }}</p>
                        <span class="card-pricing-icon @if( $userPlan->suggest == 1 ) text-white @else text-dark @endif ">
                            <i class="fas fa-cannabis"></i>
                        </span>
                        <h2 class="card-pricing-price @if( $userPlan->suggest == 1 ) text-white @endif">${{ $userPlan->feeBySeason }} <span>/ 季</span></h2>
                        <h3 class="card-pricing-price @if( $userPlan->suggest == 1 ) text-white @endif">${{ $userPlan->feeByYear }} <span>/ 年</span></h3>
                        <ul class="card-pricing-features @if( $userPlan->suggest == 1 ) text-white @else text-dark @endif">
                            <li>資金上限 {{ number_format( $userPlan->maxAmount , 0 , '.', ',') }} USDT</li>
                            <li>單次下單上限 {{ number_format( $userPlan->maxOrders , 0 , '.', ',') }} USDT</li>
                            <li>機器人數量上限 {{ number_format( $userPlan->maxBotQty , 0 , '.', ',') }} USDT</li>
                            <li>API數量上限  {{ $userPlan->maxApiSlot }} 組</li>
                            <li>支援幣安、FTX、ByBit交易所</li>
                        </ul>
                        <button class="btn @if( $userPlan->suggest == 1 ) btn-light @else btn-secondary @endif  waves-effect waves-light mt-4 mb-2 width-sm" onclick="window.location='{{ url("userPlansNewRecord" )}}/{{ $userPlan->id }}'">選擇此方案</button>
                    </div>
                </div> <!-- end Pricing_card -->
            </div> <!-- end col -->
            @endforeach

        </div>
        <!-- end row -->

    </div> <!-- end col-->
</div>
@endsection