<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    public $timestamps = false;
    
    protected $connection = 'quotation';

    protected $table = 'tblunit_convertion';

    protected $fillable = [
        'unit_conversion_id','unit_name','convertion_amt','unit_type'
    ];
}
