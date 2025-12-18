<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class ContactNumber extends Model
{
    protected $connection = 'dailyove_online_site';

    

    protected $fillable = [
        'contact_id','contact_no','type'
    ];
}
