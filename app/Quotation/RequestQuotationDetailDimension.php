<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestQuotationDetailDimension extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $connection = 'quotation';
    protected $table = 'tblrequest_quotation_details_dimension';
    public $primaryKey = "request_quotation_details_dimension_id";
    public $incrementing = true;  

    protected $fillable = [
        'request_quotation_details_dimension_id','request_quotation_details_id','quantity','weight','height','width','length','weight_utype','dimension_utype'
    ];
}
