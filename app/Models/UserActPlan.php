<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserActPlan extends Authenticatable
{
    // protected $table = 'managers';

    protected $fillable = [
    ];

    //返回
    public function getUserPlan()
    {
        return $this->belongsTo(UserPlan::class, 'planID', 'id');
    }

        //返回
        public function getUser()
        {
            return $this->belongsTo(User::class, 'planID', 'id');
        }

}
