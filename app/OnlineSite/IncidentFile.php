<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class IncidentFile extends Model
{
    public $timestamps = false;
    
    protected $connection = 'dailyove_online_site';

    protected $fillable = [
        'incident_no','description','file_link'
    ];

    protected $table = 'tblincident_files';
}
