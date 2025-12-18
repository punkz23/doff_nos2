<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class ContactGuest extends Model
{
    public $timestamps = false;
    
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'contact_id','fileas','fname','lname','mname','email','contact_no'
    ];

    protected $table = 'tblcontacts_guest';
}
