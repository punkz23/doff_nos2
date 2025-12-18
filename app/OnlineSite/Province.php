<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblprovinces';

    public function city(){
        return $this->hasMany('App\OnlineSite\City','province_id','province_id')->orderBy('cities_name','ASC');
    }
}
