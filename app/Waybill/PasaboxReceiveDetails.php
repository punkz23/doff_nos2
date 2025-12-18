<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PasaboxReceiveDetails extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblpasabox_received_details';
    public $timestamps = false;

    public function pasabox_received(){
        return $this->belongsTo('App\Waybill\PasaboxReceive','pasabox_to_receive_id','pasabox_to_receive_id');
    }
    public function pasabox_received_files(){
        return $this->hasMany('App\Waybill\PasaboxReceiveDetailsFiles','pasabox_received_details_id','pasabox_received_details_id');
    }

}
