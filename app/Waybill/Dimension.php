<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tbldimension';

    public function contact(){
    	return $this->belongsTo('App\Waybill\Contact','contact_id','contact_id');
    }
}
