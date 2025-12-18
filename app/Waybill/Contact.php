<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tblcontacts';

    public function charge_account(){
        return $this->hasOne('App\Waybill\ChargeAccount','contact_id','contact_id');
    }

    public function dimension(){
        return $this->hasMany('App\Waybill\Dimension','contact_id','contact_id');
    }

}
