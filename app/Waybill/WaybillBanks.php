<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class WaybillBanks extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblbanks';
}
