<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
        'account', 'name', 'password','last_login_at','last_login_ip',
    ];

    // public function GettLLog()
    // {
    //     return $this->hasMany(LLog::class, 'll_userid', 'id')->where('ll_ismanager', 1)->where('ll_action', 'Login')->take(20)->orderBy('created_at', 'DESC');
    // }

}
