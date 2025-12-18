<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class CargoType extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblcargotype';

}
