<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class SectorSchedule extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblsector_schedule';

    public function sector_route_template(){
    	return $this->belongsTo('App\Waybill\RouteTemplate','route_template_no','route_template_no');
    }
    
}
