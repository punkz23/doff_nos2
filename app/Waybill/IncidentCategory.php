<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class IncidentCategory extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tblincident_category';

}
