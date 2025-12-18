<?php

namespace App\RecordLogs;

use Illuminate\Database\Eloquent\Model;

class TrackAndTrace extends Model
{
    public $timestamps = false;

    protected $connection = 'recordlogs';

    protected $table = 'tbltrack_trace';

    public function waybill(){
    	return $this->belongsTo('App\Waybill\Waybill','transactioncode','transactioncode');
    }

    public function ol_track_trace_details(){
        return $this->belongsTo('App\RecordLogs\TrackTraceDetails','obr_details_id','obr_details_id');
    }
    
}
