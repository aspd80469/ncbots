<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyBot;
use App\Models\UserActPlan;
use Carbon\Carbon;
use Auth;

class myBotLive extends Component
{
    public function render()
    {
        $today = Carbon::now();
        $actUserPlan = UserActPlan::where('userid' , Auth::User()->id)
        ->where('status', 2)
        ->whereDate('takeDate','<=', $today)
        ->whereDate('edDate','>=', $today)->first();

        $myBots = MyBot::where('userid' , Auth::User()->id)->get();

        foreach( $myBots as $myBot){
            $myBot->field_1 = unserialize($myBot->field_1);
            $myBot->field_2 = unserialize($myBot->field_2);
            $myBot->field_3 = unserialize($myBot->field_3);
        }

        return view('livewire.myBotsLive');
    }
}
