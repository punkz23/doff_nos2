<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class RebateTransaction extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblrebate_transaction';

    public function waybill(){
        return $this->belongsTo('App\Waybill\Waybill','transactioncode','transactioncode');
    }

}
