<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PODTransmittalDetails extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblpod_transmittal_details';

    public function waybill_reference_attachment(){
        return $this->hasOne('App\Waybill\WaybillReference','waybills_reference_attachment_id','waybills_reference_attachment_id');
    }
    
}
