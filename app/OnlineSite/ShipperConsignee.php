<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class ShipperConsignee extends Model
{
	public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblshipperconsignee';

    protected $fillable = [
        'contact_id','shipper_consignee_id','latest_transaction','rider','default_customer','qr_code','date_deactivated','deactivated_view','pasabox'
    ];

    public function qr_profile(){
    	return $this->belongsTo('App\OnlineSite\QrcodeProfile','qr_code','qrcode_profile_id');
    }
}
