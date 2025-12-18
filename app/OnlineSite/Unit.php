<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblunit';
}
