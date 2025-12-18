<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    

	public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblcontacts';

    protected $fillable = [
        'contact_id','fileas','fname','mname','lname','email','birthday','gender','religion','nationality','civil_status','contact_no','company','business_category_id','branchoffice_id','discount','bir2306','bir2307','vat','employee','customer','use_company','email_verification','profile_photo_path','department','position','doff_account','contact_status','member_since','verified_account'
    ];

    protected $attributes = [
        
        'gender'=>'DEFAULT',
        'position'=>'',
        'religion'=>'',
        'nationality'=>'',
        'civil_status'=>'',
        'branchoffice_id'=>0,
        'discount'=>0,
        'bir2306'=>0,
        'bir2307'=>0,
        'vat'=>1,
        'employee'=>0,
        'customer'=>0,
        'email_verification'=>0,
        'profile_photo_path'=>'',
        'department'=>null,
        'doff_account'=>null,
        'contact_status'=>0,
        'birthday'=>null,
        'verified_account'=>0,
        'fname'=>'',
        'mname'=>'',
        'lname'=>'',
        'business_category_id'=>0
    ];

    public function shipper_consignee(){
    	return $this->belongsTo('App\OnlineSite\ShipperConsignee','contact_id','shipper_consignee_id');
    }

    public function waybill(){
        return $this->hasMany('App\OnlineSite\Waybill','prepared_by','contact_id');
    }

    public function user_address(){
    	return $this->hasMany('App\OnlineSite\UserAddress','user_id','contact_id')->select("*", DB::raw("UPPER(CONCAT(CASE WHEN address_caption !='' THEN CONCAT(address_caption,': ') ELSE '' END,CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END)) as full_address"));
    }

    public function doff_account_data(){
        return $this->hasOne('App\Waybill\Contact','doff_account','doff_account');
    }

    public function contact_number(){
        return $this->hasMany('App\OnlineSite\ContactNumber','contact_id','contact_id');
    }

    public function contact_verification(){
        return $this->hasMany('App\OnlineSite\ContactVerification','contact_id','contact_id');
    }

    public function qr_code_profile(){
        return $this->hasMany('App\OnlineSite\QrcodeProfile','contact_id','contact_id');
    }
}
