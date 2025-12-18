<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class PODTransmittalUpload extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblpod_transmittal_upload';
}
