<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestQuotationDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $connection = 'quotation';
    protected $table = 'tblrequest_quotation_details';
    public $primaryKey = "request_quotation_details_id";
    public $incrementing = false;  

    protected $fillable = [
        'request_quotation_details_id','request_quotation_id','item_id','item_name','item_unit','total_qty'
    ];
}
