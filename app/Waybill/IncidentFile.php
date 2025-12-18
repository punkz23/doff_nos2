<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class IncidentFile extends Model
{
    public $timestamps = false;
    
    protected $connection = 'waybill';

    protected $fillable = [
        'incident_no','description','file_link'
    ];

    protected $table = 'tblincident_files';
}
