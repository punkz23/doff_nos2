<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblsectorate2';

    public function route_template_details(){
    	return $this->hasMany('App\Waybill\SectorRouteTemplate','sectorate_no','sectorate_no');
    }
    public function branch(){
    	return $this->belongsTo('App\Waybill\Branch','branchoffice_no','branchoffice_no');
    }

}
