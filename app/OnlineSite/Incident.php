<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public $timestamps = false;
    
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'incident_no','incident_datetime','incident_subject','incident_description','posted_by','posted_datetime','guest','incident_category_id'
    ];

    protected $table = 'tblincidents';
}
