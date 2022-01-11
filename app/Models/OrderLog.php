<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OrderLog extends Authenticatable
{
    protected $fillable = [
        '', 
    ];


    //返回
    public function getmyBot()
    {
        return $this->belongsTo(MyBot::class, 'myBotId', 'id');
    }

}
