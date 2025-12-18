<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchFilter extends Model
{
    public function branch(){
    	return $this->hasMany('App\Branch');
    }
}
