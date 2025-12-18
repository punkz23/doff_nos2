<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class ChargeAccount extends Model
{
    

    protected $connection = 'waybill';

    protected $table = 'tblchargeaccount';

    public function contact(){
        return $this->hasMany('App\Waybill\Contact','contact_id','contact_id');
    }
}
