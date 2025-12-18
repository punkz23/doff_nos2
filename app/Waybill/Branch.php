<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $connection = 'doff_configuration';
    protected $table = 'doff_configuration.tblbranchoffice';

    public function sector(){
    	return $this->hasMany('App\Waybill\Sector','branchoffice_no','branchoffice_no');
    }
}
