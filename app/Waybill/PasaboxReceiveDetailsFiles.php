<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PasaboxReceiveDetailsFiles extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblpasabox_received_details_file';
    public $timestamps = false;

    public function pasabox_received_details(){
        return $this->belongsTo('App\Waybill\PasaboxReceiveDetails','pasabox_received_details_id','pasabox_received_details_id');
    }
}
