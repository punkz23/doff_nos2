<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class WaybillContact extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

	protected $table = 'tblwaybills_contacts';

    protected $fillable = [
        'waybill_contacts_no','waybill_contacts_no_type','waybill_shipper_consignee','reference_no','default_contacts'
    ];
}
