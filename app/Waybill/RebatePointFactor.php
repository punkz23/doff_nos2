<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class RebatePointFactor extends Model
{
    
    protected $connection = 'waybill';
    protected $table = 'tblrebate_point_factor';

}
