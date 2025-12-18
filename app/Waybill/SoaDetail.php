<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class SoaDetail extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tblsoadetails';
}
