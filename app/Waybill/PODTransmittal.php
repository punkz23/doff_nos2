<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PODTransmittal extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblpod_transmittal';

    public function pod_transmittal_details(){
        return $this->hasMany('App\Waybill\PODTransmittalDetails','pod_transmittal_no','pod_transmittal_no');
    }

    public function pod_transmittal_upload(){
        return $this->hasMany('App\Waybill\PODTransmittalUpload','pod_transmittal_no','pod_transmittal_no');
    }

}
