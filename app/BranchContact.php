<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchContact extends Model
{
    protected $fillable = [
        'branch_id', 'contact_no'
    ];

    public function branch(){
    	return $this->belongsTo('App\Branch');
    }
}
