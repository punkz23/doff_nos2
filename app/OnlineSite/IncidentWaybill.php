<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class IncidentWaybill extends Model
{
    public $timestamps = false;
    
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'incident_no','tracking_no'
    ];

    protected $table = 'tblincident_waybill';
}
