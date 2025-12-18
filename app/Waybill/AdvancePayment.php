<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePayment extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tbladvance_payment';
    public $timestamps = false;

    protected $fillable = [
        'withdraw','reference_no','pca_account_no','deposit','contact_id',
        'advance_payment_status','deposit_date','deposit_account_name',
        'deposit_account_no','deposit_reference','prepared_datetime',
        'pca_adv_onlinepayment','application_group_id'
    ];
}
