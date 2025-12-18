<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class RouteTemplate extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblroute_template';

    public function route_template_details(){
    	return $this->hasMany('App\Waybill\SectorRouteTemplate','route_template_no','route_template_no');
    }

    public function sector_schedule(){
    	return $this->hasMany('App\Waybill\SectorSchedule','route_template_no','route_template_no');
    }
    
}
