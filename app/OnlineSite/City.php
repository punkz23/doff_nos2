<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblcitiesminicipalities';

    public function province(){
        return $this->belongsTo('App\OnlineSite\Province','province_id','province_id');
    }
}
