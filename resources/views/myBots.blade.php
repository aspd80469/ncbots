@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
            </div>
            <h4 class="page-title">
                我的機器人@if( !is_null($actUserPlan) )({{ count($myBots) }}/{{ $actUserPlan->getUserPlan->maxBotQty }})@endif
                @if( !is_null($actUserPlan) && count($myBots) < $actUserPlan->getUserPlan->maxBotQty)
                <button type="button" class="btn btn-blue waves-effect waves-light" onclick="window.location='{{ url('/myBots/0') }}'"><i class="fas fa-plus"></i> 新增機器人</button>
                @endif
            </h4>
            
        </div>
    </div>
</div>

<div class="row">

    @foreach ($myBots as $myBot)
    <div class="col-lg-4">
        <div class="card border-dark border mb-3">
            <div class="card-header">
                <a href="{{ url('/myBots/') }}/{{ $myBot->id }}" >
                    #{{ $myBot->id }}機器人
                </a>
                <a href="{{ url('/myBotOrders/') }}/{{ $myBot->id }}" >
                    下單紀錄
                </a>
                <span role="alert" style="color: red;">
                    {{ $myBot->botMsg }}
                </span>
            </div>
            <div class="card-body">
                <h4 class="card-title text-success">
                    @if( $myBot->status == 0 )
                    正常(運行中)
                    @elseif( $myBot->status == 1 )
                    暫停中
                    @elseif( $myBot->status ==2 )
                    終止
                    @endif
                </h4>
                <h4 class="text-dark my-1">{{ $myBot->getBotStgy->stgyName }}</h4>
                持倉資訊
                <textarea class="form-control" id="field_1" name="field_1" rows="10"><?php print_R($myBot->field_1); ?></textarea>
                追蹤中買入幣種
                <textarea class="form-control" id="field_2" name="field_2" rows="10"><?php print_R($myBot->field_2); ?></textarea>
                追蹤中賣出幣種
                <textarea class="form-control" id="field_3" name="field_3" rows="10"><?php print_R($myBot->field_3); ?></textarea>

                <a href="{{ url('/myBotOrders/') }}/{{ $myBot->id }}" >
                <h3 class="text-dark my-1 text-end">投資金額 $<span data-plugin="counterup">{{ number_format( $myBot->invAmount , 0 , '.', ',') }}</span></h3>
                </a>
            </div>
        </div>
    </div>
    <!-- end col -->

    @endforeach

    <script language="javascript">
        setTimeout(function(){
            window.location.reload(1);
        }, 30000);
    </script>


    {{-- <livewire:myBotLive /> --}}

</div>
@endsection