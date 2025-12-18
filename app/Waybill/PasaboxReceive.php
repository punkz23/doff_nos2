<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PasaboxReceive extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblpasabox_to_receive';
    public $timestamps = false;

    protected $fillable = [
        'online_booking_ref'
    ];

    public function pasabox_received_details(){
        return $this->hasMany('App\Waybill\PasaboxReceiveDetails','pasabox_to_receive_id','pasabox_to_receive_id');
    }

}
