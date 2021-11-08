@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
            </div>
            <h4 class="page-title">會員方案</h4>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-xl-10">

        <!-- Pricing Title-->
        <div class="text-center">
            <h3 class="mb-2">選擇 <b>方案</b></h3>
            <p class="text-muted w-50 m-auto"></p>
        </div>

        <!-- Plans -->
        <div class="row my-3">
            <div class="col-md-4">
                <div class="card card-pricing">
                    <div class="card-body text-center">
                        <p class="card-pricing-plan-name fw-bold text-uppercase">初階方案</p>
                        <span class="card-pricing-icon text-primary">
                            <i class="fe-users"></i>
                        </span>
                        <h2 class="card-pricing-price">$19 <span>/ 月</span></h2>
                        <h3 class="card-pricing-price">$39 <span>/ 季</span></h3>
                        <ul class="card-pricing-features">
                            <li>資金上限 1,000 USDT</li>
                            <li>API數量 1 組</li>
                            <li>可選幣種BTC、ETH</li>
                            <li>支援幣安交易所</li>
                        </ul>
                        <button class="btn btn-primary waves-effect waves-light mt-4 mb-2 width-sm">選擇此方案</button>
                    </div>
                </div> <!-- end Pricing_card -->
            </div> <!-- end col -->

            <div class="col-md-4">
                <div class="card card-pricing card-pricing-recommended">
                    <div class="card-body text-center">
                        <p class="card-pricing-plan-name fw-bold text-uppercase">基本方案</p>
                        <span class="card-pricing-icon text-white">
                            <i class="fe-award"></i>
                        </span>
                        <h2 class="card-pricing-price text-white">$29 <span>/ 月</span></h2>
                        <h3 class="card-pricing-price text-white">$49 <span>/ 季</span></h3>
                        <ul class="card-pricing-features">
                            <li>資金上限 5,000 USDT</li>
                            <li>API數量 2 組</li>
                            <li>可選幣種BTC、ETH、FTT、SOL</li>
                            <li>支援幣安、FTX交易所</li>
                        </ul>
                        <button class="btn btn-light waves-effect mt-4 mb-2 width-sm">選擇此方案</button>
                    </div>
                </div> <!-- end Pricing_card -->
            </div> <!-- end col -->

            <div class="col-md-4">
                <div class="card card-pricing">
                    <div class="card-body text-center">
                        <p class="card-pricing-plan-name fw-bold text-uppercase">進階方案</p>
                        <span class="card-pricing-icon text-primary">
                            <i class="fe-aperture"></i>
                        </span>
                        <h2 class="card-pricing-price">$39 <span>/ 月</span></h2>
                        <h3 class="card-pricing-price">$99 <span>/ 季</span></h3>
                        <ul class="card-pricing-features">
                            <li>資金上限 10,000 USDT</li>
                            <li>API數量 3 組</li>
                            <li>可選所有幣種</li>
                            <li>支援幣安、FTX交易所</li>
                        </ul>
                        <button class="btn btn-primary waves-effect waves-light mt-4 mb-2 width-sm">選擇此方案</button>
                    </div>
                </div> <!-- end Pricing_card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->

    </div> <!-- end col-->
</div>
@endsection