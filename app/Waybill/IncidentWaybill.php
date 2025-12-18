<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class IncidentWaybill extends Model
{
    public $timestamps = false;
    
    protected $connection = 'waybill';

    protected $fillable = [
        'incident_no','tracking_no'
    ];
    
    protected $table = 'tblincident_waybill';
}
