<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class SectorRouteTemplate extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblroute_template_details';

    public function sector(){
    	return $this->belongsTo('App\Waybill\Sector','sectorate_no','sectorate_no');
    }
    public function route_template(){
    	return $this->belongsTo('App\Waybill\RouteTemplate','route_template_no','route_template_no');
    }
}
