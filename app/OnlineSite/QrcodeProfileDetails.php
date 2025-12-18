<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QrcodeProfileDetails extends Model
{
    public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblqrcode_profile_details';

    protected $fillable = [
        'qrcode_profile_id','useraddress_no'
    ];

    public function qr_code_profile(){
    	return $this->belongsTo('App\OnlineSite\QrcodeProfile','qrcode_profile_id','qrcode_profile_id');
    }

    public function qr_code_profile_address(){
    	return $this->belongsTo('App\OnlineSite\UserAddress','useraddress_no','useraddress_no')
        ->select("*", DB::raw("UPPER(CONCAT(CASE WHEN address_caption !='' THEN CONCAT(address_caption,': ') ELSE '' END,CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END)) as full_address"));
    }
}
