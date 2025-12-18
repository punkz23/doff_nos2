<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class WaybillShipment extends Model
{
	public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblwaybill_shipments';

    protected $fillable = ['waybill_shipment_id','reference_no','item_code','item_description','unit_no','unit_description','quantity','weight','height','width','lenght','freight_amount','cargo_type_id'];

    public function waybill(){
    	return $this->belongsTo('App\OnlineSite\Waybill','reference_no','reference_no');
    }

    public function stock(){
        return $this->hasOne('App\OnlineSite\Stock','stock_no','item_code');
    }

    public function unit(){
        return $this->hasOne('App\OnlineSite\Unit','unit_no','unit_no');
    }
    public function pasabox_received_details(){
        return $this->hasMany('App\Waybill\PasaboxReceiveDetails','waybill_shipment_id','waybill_shipment_id')
        ->with('pasabox_received_files');
    }
}
