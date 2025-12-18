<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class FbDataDeletion extends Model
{
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'confirmation_code','user_id','fb_id'
    ];
}
