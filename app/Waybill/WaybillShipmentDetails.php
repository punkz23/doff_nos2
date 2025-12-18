<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class WaybillShipmentDetails extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblwaybill_shipments_multipleitem';
}
