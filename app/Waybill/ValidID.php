<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class ValidID extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblvalid_id';
    
}
