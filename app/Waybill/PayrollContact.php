<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PayrollContact extends Model
{
    public $timestamps = false;

    protected $connection = 'payroll';

    protected $table = 'tblcontacts';
}
