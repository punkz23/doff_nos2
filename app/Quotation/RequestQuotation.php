<?php

namespace App\Quotation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestQuotation extends Model
{   
    use HasFactory;
    public $timestamps = false;
    protected $connection = 'quotation';
    protected $table = 'tblrequest_quotation';
    public $primaryKey = "request_quotation_id";
    public $incrementing = false;  

    protected $fillable = [
        'request_quotation_id','request_by_fileas','request_by_fname','request_by_mname','request_by_lname','request_by_cno','request_by_email','request_by_street','request_by_barangay','request_by_city','request_by_province','request_by_postalcode','origin_branch','destination_branch','declared_value','delivery','delivery_truckpanel','delivery_sectorate','pickup','pickup_truckpanel','pickup_sectorate','request_status','pickup_street','pickup_brgy','pickup_city','delivery_street','delivery_brgy','delivery_city'
    ];
}
