<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class ContactVerification extends Model
{
    public $timestamps = false;
    protected $connection = 'dailyove_online_site';
    protected $table = 'tblcontacts_verification';


    protected $fillable = [
        'contact_id','contacts_verification_pic_id','contacts_verification_pic_with_id','valid_id_no','id_no'
    ];

    public function contact(){
    	return $this->belongsTo('App\OnlineSite\Contact','contact_id','contact_id');
    }
    public function valid_id(){
    	return $this->belongsTo('App\Waybill\ValidID','valid_id_no','valid_id_no');
    }
}
