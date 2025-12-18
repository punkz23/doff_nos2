<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class UnclaimedCallLogs extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblunclaimedcall_logs';
}
