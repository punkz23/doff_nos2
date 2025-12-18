<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class OnlinePayment extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblonline_payment';
    public $timestamps = false;

    protected $fillable = [
        'onlinepayment_id','onlinepayment_date','for_confirmation_pdate','onlinepayment_amount',
        'for_confirmation_amount','verification_code','gcash_added_datetime',
        'for_confirmation','confirmation_status','confirmation_email','confirmation_branch',
        'confirmation_branch_account_name','confirmation_branch_account_no',
        'bank_no','pasabox_cf'
    ];

    public function online_payment_tcode(){
        return $this->hasMany('App\Waybill\OnlinePaymentConfirmation','onlinepayment_id','onlinepayment_id');
    }
}
