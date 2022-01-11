<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SysLog extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
    ];


    //返回
    public function getUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    // public function getMybot()
    // {
    //     return $this->belongsTo(MyBot::class, 'myBotId', 'id');
    // }

}
