<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class WaybillReference extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblwaybills_reference_attachment';

    public function specialrate_reference_attachment(){
        return $this->hasOne('App\Waybill\SpecialRateReferenceAttachment','specialrate_reference_attachment_id','specialrate_reference_attachment_id');
    }
    public function pod_transmittal_details_ref(){
        return $this->hasOne('App\Waybill\SpecialRateReferenceAttachment','specialrate_reference_attachment_id','specialrate_reference_attachment_id');
    }

    public function waybill(){
        return $this->hasOne('App\Waybill\Waybill','transactioncode','transactioncode');
    }

}
