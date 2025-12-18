<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

    protected $table = 'tblbranchoffice';
    
    public function branch(){
    	return $this->belongsTo('App\DOFFConfiguration\Branch','branchoffice_no','branchoffice_no');
    }

}
