<?php

namespace App\RecordLogs;

use Illuminate\Database\Eloquent\Model;

class RecordLogs extends Model
{
    protected $connection='recordlogs';
    protected $table = 'newsletter_logs';
    public $primaryKey = "idnewsletter_logs";
    public $timestamps = false;

    protected $fillable = ['recordlogs','log_datetime','email','customer_id','user_id'];
}
