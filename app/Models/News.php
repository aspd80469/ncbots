<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class News extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
        'account', 'name', 'password','last_login_at','last_login_ip',
    ];

}
