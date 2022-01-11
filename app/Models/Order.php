<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Order extends Authenticatable
{
    protected $fillable = [
        '',
    ];

    //返回
    public function getorderLog()
    {
        return $this->hasMany(OrderLog::class, 'orderId', 'id');
    }

}
