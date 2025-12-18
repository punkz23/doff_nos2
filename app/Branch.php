<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name', 'address','google_maps_api','branch_filter_id'
    ];

    public function branch_contact(){
    	return $this->hasMany('App\BranchContact');
    }

    public function branch_schedule(){
    	return $this->hasMany('App\BranchSchedule');
    }

    public function branch_filter(){
    	return $this->belongsTo('App\BranchFilter');
    }
}
