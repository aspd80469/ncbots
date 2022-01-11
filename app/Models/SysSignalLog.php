<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SysSignalLog extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
    ];

    //返回
    public function getSysSignals()
    {
        return $this->belongsTo(SysSignal::class, 'sigId', 'id');
    }

}
