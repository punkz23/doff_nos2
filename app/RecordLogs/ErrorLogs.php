<?php

namespace App\RecordLogs;

use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{
    protected $connection='recordlogs';
    protected $table = 'error_logs';
    public $primaryKey = "error_id";
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'error_id',
        'error_message',
        'error_description',
        'encountered_by',
        'request_url',
        'assumed_module',
        'sql',
        'bindings',
        'time',
        'error_info'
    ];

}
