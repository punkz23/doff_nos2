<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['ip_address','from','to','message','is_read','read_at'];

    public function sender(){
        return $this->belongsTo('App\User','from','id');
    }

    public function receiver(){
        return $this->belongsTo('App\User','to','id');
    }
}
