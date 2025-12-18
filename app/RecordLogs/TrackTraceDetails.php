<?php

namespace App\Recordlogs;

use Illuminate\Database\Eloquent\Model;

class TrackTraceDetails extends Model
{
    protected $connection = 'recordlogs';
    protected $table = 'tblonlinebooking_recordlog_details';

    public function ol_track_trace_header(){
        return $this->belongsTo('App\RecordLogs\TrackTraceHeader','obr_id','obr_id');
    }
    public function ol_track_trace(){
        return $this->hasMany('App\RecordLogs\CustomerTrackTrace','obr_details_id','obr_details_id');
    }
}
