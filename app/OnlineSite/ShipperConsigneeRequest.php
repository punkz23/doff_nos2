<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class ShipperConsigneeRequest extends Model
{
    public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblshipperconsignee_request';

    protected $fillable = [
        'shipper_consignee_id','contact_id','request_status','request_date','confirmed_date','allow_address','allow_contactno','requestor_allow_address','requestor_allow_contactno'
    ];

    public function contact(){
    	return $this->belongsTo('App\OnlineSite\Contact','shipper_consignee_id','contact_id');
    }

}
