<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class CustomerEmail extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblcustomer_email';
}
