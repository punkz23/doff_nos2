<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblunit';

    protected $fillable = ['unit_no','unit_description'];
}
