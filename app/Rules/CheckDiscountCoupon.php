<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Waybill\DiscountCoupon;
use App\Waybill\Waybill;
use Carbon\Carbon;
class CheckDiscountCoupon implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value!=''){
            $discount_coupon = DiscountCoupon::where('discount_coupon_no',$value)->where('validity_from','<=',date('Y-m-d',strtotime(Carbon::now())))->where('validity_to','>=',date('Y-m-d',strtotime(Carbon::now())));
            $title='';$message='';$type='';
            if($discount_coupon->count()>0){
                if($discount_coupon->first()->one_time_use==1){
                    $chk_coupon = Waybill::where('discount_coupon',$value);
                    if($chk_coupon->count()>0){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }else{
            return true;
        }
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Discount coupon is invalid';
    }
}
