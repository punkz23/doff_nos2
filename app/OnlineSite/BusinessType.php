<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblbusinesstype';

    public function business_type_category(){
    	return $this->hasMany('App\OnlineSite\BusinessTypeCategory','businesstype_id','businesstype_id');
    }
}
