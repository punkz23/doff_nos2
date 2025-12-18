<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class ORCRDetails extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblorcrdetails';
    public $timestamps = false;
    
    protected $fillable = [
        'reference_no',
        'pasabox_cf',
        'pasabox_cf_ref_no',
        'online_booking_ref',
        'transaction_date',
        'advancepayment',
        'deposit'
    ];

    public function adjustment_add(){
        return $this->hasMany('App\Waybill\Adjustment','reference_no','reference_no')->where('add_less',1);
    }

    public function adjustment_less(){
        return $this->hasMany('App\Waybill\Adjustment','reference_no','reference_no')->where('add_less',0);
    }
}
