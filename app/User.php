<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar','name', 'email', 'password','user_status','usertype_no','contact_id','facebook_id','google_id','mobileNo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addNew($input)
    {
        $check = static::where('facebook_id',$input['facebook_id'])->first();
        if(is_null($check)){
            return static::create($input)->assignRole('Client');
        }
        return $check;
    }

    public function waybill(){
        return $this->hasMany('App\OnlineSite\Waybill','prepared_by','contact_id');
    }

    public function shipper_consignee(){
        return $this->hasMany('App\OnlineSite\ShipperConsignee','contact_id','contact_id');
    }

    public function contact(){
        return $this->hasOne('App\OnlineSite\Contact','contact_id','contact_id');
    }

    public function user_address(){
        return $this->hasMany('App\OnlineSite\UserAddress','user_id','contact_id');
    }

    public function contact_verified(){
        return $this->hasOne('App\OnlineSite\ContactVerification','contact_id','contact_id')
        ->with('valid_id')
        ->whereIn('contacts_verification_status',[1,0]);
    }
    public function rebate_transaction(){
        return $this->hasMany('App\Waybill\RebateTransaction','customer_id','contact_id')
        ->whereIn('rebate_transaction_status',[1,0])
        ->with('waybill');
    }
}
