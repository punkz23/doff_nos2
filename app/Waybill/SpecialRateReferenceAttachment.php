<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class SpecialRateReferenceAttachment extends Model
{
    protected $connection = 'waybill';
    protected $table = 'tblspecialrate_reference_attachment';
}
