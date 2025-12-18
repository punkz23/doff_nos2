<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    protected $fillable = ['email','lname','fname','mname','gender','contact_no'];

    public function waybill(){
        return $this->belongsTo('App\OnlineSite\Waybill','contact_person_id','id');
    }
}
