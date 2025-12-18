<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class QrcodeProfile extends Model
{
    public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tblqrcode_profile';

    protected $fillable = [
        'qrcode_profile_id','contact_id','date_deactivated'
    ];
    
    public function contact(){
    	return $this->belongsTo('App\OnlineSite\Contact','contact_id','contact_id');
    }
    public function qr_code_details(){
        return $this->hasMany('App\OnlineSite\QrcodeProfileDetails','qrcode_profile_id','qrcode_profile_id');
    }

}
