<?php

namespace App\Recordlogs;

use Illuminate\Database\Eloquent\Model;

class TrackTraceHeader extends Model
{
    protected $connection = 'recordlogs';
    protected $table = 'tblonlinebooking_recordlog';


    public function ol_track_trace_details(){
        return $this->hasMany('App\RecordLogs\TrackTraceDetails','obr_id','obr_id');
    }
}
