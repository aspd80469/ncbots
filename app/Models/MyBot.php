<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MyBot extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
        'account', 'name', 'password','last_login_at','last_login_ip',
    ];

    //返回
    public function getBotStgy()
    {
        return $this->belongsTo(BotsStgy::class, 'usedStgy', 'id');
    }

}
