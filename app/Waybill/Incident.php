<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public $timestamps = false;
    
    protected $connection = 'waybill';

    protected $fillable = [
        'incident_no','incident_datetime','incident_subject','incident_description','branchoffice_no','posted_by','posted_datetime','accident_report_no','settlement_status','settlement_datetime','investigation_status','settlement_by','incident_category_id'
    ];

    protected $table = 'tblincidents';
}
