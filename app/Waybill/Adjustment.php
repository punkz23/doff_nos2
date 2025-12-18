<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tbladjustment';

    public function orcr_detail(){
        return $this->hasMany('App\Waybill\ORCRDetails','reference_no','reference_no');
    }
}
