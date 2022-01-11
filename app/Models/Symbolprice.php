<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Symbolprice extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    //返回指定交易對的獲利金額
    public function getPairSell()
    {
        //賣出訂單-買入訂單
        return $this->hasMany(Orders::class, 'symbol', 'symbol')->where('isBuyer', '0')->sum('quoteQty');

    }

    //返回指定交易對的獲利金額
    public function getPairBuy()
    {
        //賣出訂單-買入訂單
        return $this->hasMany(Orders::class, 'symbol' , 'symbol' )->where('isBuyer', '1' )->sum('quoteQty');

    }

    //返回指定交易對的獲利金額
    public function getPairProfit()
    {
        //賣出訂單-買入訂單
        return $this->hasMany(Orders::class, 'symbol' , 'symbol' )->where('isBuyer', '0' )->sum('quoteQty')
                - $this->hasMany(Orders::class, 'symbol' , 'symbol' )->where('isBuyer', '1' )->sum('quoteQty');

    }

}
