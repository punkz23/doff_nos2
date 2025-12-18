<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class OnlinePaymentConfirmation extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblonline_payment_confirmation_tcode';
    public $timestamps = false;

    protected $fillable = [
        'onlinepayment_id','online_booking_ref'
    ];

    public function online_payment(){
        return $this->belongsTo('App\Waybill\OnlinePayment','onlinepayment_id','onlinepayment_id');
    }
}
