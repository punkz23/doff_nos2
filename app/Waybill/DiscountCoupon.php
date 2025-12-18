<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tbldiscount_coupon';
}
