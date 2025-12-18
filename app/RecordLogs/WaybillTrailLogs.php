<?php

namespace App\RecordLogs;

use Illuminate\Database\Eloquent\Model;

class WaybillTrailLogs extends Model
{
    protected $connection = 'recordlogs';

    protected $table = 'waybill_trail_logs';

    protected $fillable = ['reference_no','user_id','action_taken'];

    public function waybill(){
    	return $this->belongsTo('App\OnlineSite\Waybill','reference_no','reference_no');
    }
}
