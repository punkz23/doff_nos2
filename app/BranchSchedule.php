<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSchedule extends Model
{
    protected $fillable = [
        'branch_id', 'days_from','days_to','time_from','time_to'
    ];
}
