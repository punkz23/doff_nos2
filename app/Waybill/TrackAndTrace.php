<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class TrackAndTrace extends Model
{
    public $timestamps = false;

    protected $connection = 'recordlogs';

    protected $table = 'tbltrackandtrace';

    protected $fillable = ['trackandtrace_status','reference_no','online_booking'];
}
