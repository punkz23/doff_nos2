<?php

namespace App\DOFFConfiguration;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;

    protected $connection = 'doff_configuration';

    protected $table = 'tblbranchoffice';

    public function pasabox_authorized_employee(){
        return $this->belongsTo('App\Waybill\PayrollContact','pasabox_incharge_employee','contact_id');
    }
    public function pasabox_alternative_authorized_employee(){
        return $this->belongsTo('App\Waybill\PayrollContact','pasabox_incharge_alternative_employee','contact_id');
    }

}
