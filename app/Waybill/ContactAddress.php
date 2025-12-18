<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class ContactAddress extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tbluser_address';
}
