<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;

    protected $connection = 'waybill';

    protected $table = 'tblstocks';
    public $primaryKey = 'stock_code';
    protected $fillable = ['stock_no','stock_description'];
}
