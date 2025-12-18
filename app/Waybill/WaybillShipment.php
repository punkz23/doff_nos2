<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class WaybillShipment extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblwaybill_shipments';

    public function cargo_type(){
        return $this->belongsTo('App\Waybill\CargoType','cargo_type_id','cargo_type_id');
    }
    public function waybill_shipment_details(){
        return $this->hasMany('App\Waybill\WaybillShipmentDetails','waybill_shipment_id','waybill_shipment_id');
    }
}
