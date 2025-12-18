<?php

namespace App\OnlineSite;
use Auth;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
	public $timestamps = false;
	
    protected $connection = 'dailyove_online_site';

    protected $table = 'tbluser_address';

    protected $fillable = ['useraddress_no','street','barangay','city','province','postal_code','user_id','address_def','sectorate_no','address_caption','added_by'];

    protected $attributes = [
        'address_def'=>'0',
        'address_caption'=>'NONE',
        'added_by'=>null
    ];

    public function sector(){
        return $this->belongsTo('App\Waybill\Sector','sectorate_no','sectorate_no');
    }
    public function qr_details(){
        return $this->hasMany('App\OnlineSite\QrcodeProfileDetails','useraddress_no','useraddress_no');
    }
}
