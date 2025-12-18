<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class WaybillShipmentMultiple extends Model
{
	public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblwaybill_shipments_multipleitem';

    protected $fillable = ['waybill_shipment_id','reference_no','itemcode','itemdescription','quantity','weight','height','width','lenght'];
}
