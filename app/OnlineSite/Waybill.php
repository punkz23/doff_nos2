<?php

namespace App\OnlineSite;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
class Waybill extends Model
{
    public $timestamps = false;

    protected $connection = 'dailyove_online_site';

	protected $table = 'tblwaybills';
	
	protected $primaryKey = 'reference_no';

	public $incrementing = false;

	protected $keyType = 'string';

    protected $fillable = [
    'reference_no','transactiondate','consignee_id','consignee_address_id','shipper_id','shipper_address_id','declared_value','shipment_type','destinationbranch_id','prepared_by','prepared_datetime','payment_type','discount_coupon','chargeto_id','pickup','pickup_truckpanel','delivery','delivery_truckpanel','shipper_instruction','pickup_sector_id','delivery_sector_id','delivery_sector_street','delivery_sector_street','delivery_sector_barangay','delivery_sector_city','delivery_sector_province','delivery_sector_postalcode','pickup_sector_street','pickup_sector_barangay','pickup_sector_city','pickup_sector_province','pickup_sector_postalcode','booking_status',
	'customer_id','is_guest','contact_person_id','pickup_date','confirm_pickup_date','confirm_pickup_prep_date','mode_payment','mode_payment_io','mode_payment_email','shipper_qr_code','consignee_qr_code',
	'shipping_company_id','shipping_company_address_id','shipping_company_cno','shipping_company_lno','shipping_company_email','pasabox','pasabox_branch_receiver','pasabox_cf_amt',
	'pca_advance_payment','pca_account_no','pca_pasabox_cf_advance_payment'
	];

	protected $attributes = [
		'pickup'=>0,
		'pickup_truckpanel'=>'',
		'delivery'=>0,
		'delivery_truckpanel'=>'',
		'shipper_instruction'=>'',
		'pickup_sector_id'=>'',
		'delivery_sector_id'=>'',
		'delivery_sector_street'=>'',
		'delivery_sector_barangay'=>'',
		'delivery_sector_city'=>'',
		'delivery_sector_province'=>'',
		'delivery_sector_postalcode'=>'',
		'pickup_sector_street'=>'',
		'pickup_sector_barangay'=>'',
		'pickup_sector_city'=>'',
		'pickup_sector_province'=>'',
		'pickup_sector_postalcode'=>'',
		'booking_status'=>0,
		'customer_id'=>null,
		'pickup_date'=>null,
		'confirm_pickup_date'=>null,
		'confirm_pickup_prep_date'=>null,
		'mode_payment'=>null,
		'mode_payment_io'=>null,
		'mode_payment_email'=>null,
		'consignee_qr_code'=>null,
		'shipper_qr_code'=>null,
		'shipping_company_id'=>null,
		'shipping_company_address_id'=>null,
		'shipping_company_cno'=>null,
		'shipping_company_lno'=>null,
		'shipping_company_email'=>null,
		'pasabox'=>0,
		'pasabox_branch_receiver'=>null,
		'pasabox_cf_amt'=>0,
		'pca_advance_payment'=>0,
		'pca_account_no'=>null,
		'pca_pasabox_cf_advance_payment'=>0
	];

	protected $appends = ["encryptedReference"];

	public function getEncryptedReferenceAttribute(){
		return  Crypt::encrypt($this->reference_no);
	}

	public function waybill_shipment(){
		return $this->hasMany('App\OnlineSite\WaybillShipment','reference_no','reference_no');
	}

	public function waybill_contact(){
		return $this->hasMany('App\OnlineSite\WaybillContact','reference_no','reference_no');
	}

	public function waybill_shipment_multiple(){
		return $this->hasMany('App\OnlineSite\WaybillShipmentMultiple','reference_no','reference_no');
	}

	public function waybill(){
		return $this->hasMany('App\Waybill\Waybill','online_booking_ref','reference_no');
	}
	
	public function shipper(){
		return $this->belongsTo('App\OnlineSite\Contact','shipper_id','contact_id');
	}
	public function shipper_sc(){
		return $this->belongsTo('App\OnlineSite\ShipperConsignee','shipper_id','shipper_consignee_id')
		->where('contact_id',Auth::user()->contact_id)
		->where('shipper_consignee_status',1);
	}

	public function shipper_address(){
		return $this->belongsTo('App\OnlineSite\UserAddress','shipper_address_id','useraddress_no');
	}

	public function consignee(){
		return $this->belongsTo('App\OnlineSite\Contact','consignee_id','contact_id');
	}
	public function consignee_sc(){
		return $this->belongsTo('App\OnlineSite\ShipperConsignee','consignee_id','shipper_consignee_id')
		->where('contact_id',Auth::user()->contact_id)
		->where('shipper_consignee_status',1);
	}

	public function consignee_address(){
		return $this->belongsTo('App\OnlineSite\UserAddress','consignee_address_id','useraddress_no');
	}

	public function branch(){
		return $this->hasOne('App\DOFFConfiguration\Branch','branchoffice_no','destinationbranch_id');
	}

	public function branch_receiver(){
		return $this->belongsTo('App\DOFFConfiguration\Branch','pasabox_branch_receiver','branchoffice_no');
	}

	public function branch_config(){
		return $this->belongsTo('App\DOFFCOnfiguration\Branch','branchoffice_no','destinationbranch_id');
	}

	public function charge_to_data(){
		return $this->hasOne('App\Waybill\Contact','contact_id','chargeto_id');
	}

	public function pick_up_sector(){
		return $this->belongsTo('App\Waybill\Sector','pickup_sector_id','sectorate_no');
	}
	public function delivery_sector(){
		return $this->belongsTo('App\Waybill\Sector','delivery_sector_id','sectorate_no');
	}
	public function shipping_company(){
		return $this->belongsTo('App\OnlineSite\Contact','shipping_company_id','contact_id');
	}

	public function shipping_address(){
		return $this->belongsTo('App\OnlineSite\UserAddress','shipping_company_address_id','useraddress_no');
	}

	public function shipping_sc(){
		return $this->belongsTo('App\OnlineSite\ShipperConsignee','shipping_company_address_id','shipper_consignee_id')
		->where('contact_id',Auth::user()->contact_id)
		->where('shipper_consignee_status',1);
	}

	public function pasabox_cf(){

		return $this->hasMany('App\Waybill\OnlinePaymentConfirmation','online_booking_ref','reference_no')
		->wherehas('online_payment',function($query){
			$query->whereIn('confirmation_status',[0,1]);
			//->orWhere('onlinepayment_status',1);
		});
		
	}
	public function pasabox_received(){

		return $this->belongsTo('App\Waybill\PasaboxReceive','reference_no','online_booking_ref');
		
	}
	public function pasabox_cf_adv_payment(){

		return $this->hasMany('App\Waybill\ORCRDetails','pasabox_cf_ref_no','reference_no')
		->where('pasabox_cf',1)
		->where('advancepayment','>',0);
		
	}
	// public function track_trace_recent(){

	// 	return $this->hasMany('App\Waybill\TrackAndTrace','online_booking','reference_no')
	// 	->whereNotNull('online_booking')
	// 	->orderBy('trackandtrace_datetime', 'DESC')
    //     ->limit(1);

	// }
	public function track_trace_recent(){

		return $this->belongsTo('App\RecordLogs\TrackAndTrace','reference_no','online_booking')
		->whereNotNull('online_booking')
		->with(['ol_track_trace_details'=>function($query){
			$query->with('ol_track_trace_header');
		}])
		->orderBy('track_trace_date', 'ASC')
        //->limit(1)
		;

	}
	
}
