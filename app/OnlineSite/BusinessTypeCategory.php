<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class BusinessTypeCategory extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblbusinesstype_category';

    public function business_type(){
    	return $this->hasMany('App\BusinessType','businesstype_id','businesstype_id');
    }
}
