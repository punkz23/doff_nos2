<?php

namespace App\Waybill;

use Illuminate\Database\Eloquent\Model;
use DB;
class Waybill extends Model
{
    protected $connection = 'waybill';

    protected $table = 'tblwaybills';

    protected $appends = ['total_adj_less','total_adj_add','total_deposit','total_withdraw'];

    public function track_and_trace(){
    	return $this->hasMany('App\RecordLogs\TrackAndTrace','transactioncode','transactioncode');
    }

    public function waybill(){
    	return $this->belongsTo('App\OnlineSite\Waybill','reference_no','reference_no');
    }

    public function shipper(){
        return $this->belongsTo('App\Waybill\Contact','shipper_id','contact_id');
    }

    public function consignee(){
        return $this->belongsTo('App\Waybill\Contact','consignee_id','contact_id');
    }

    public function orcr_detail(){
        return $this->hasMany('App\Waybill\ORCRDetails','transactioncode','transactioncode');
    }

    public function adjustment_less(){
        return $this->hasMany('App\Waybill\Adjustment','transactioncode','transactioncode')->where('add_less',0);
    }

    public function adjustment_add(){
        return $this->hasMany('App\Waybill\Adjustment','transactioncode','transactioncode')->where('add_less',1);
    }

    public function soa_detail(){
        return $this->hasMany('App\Waybill\SoaDetail','waybill_no','transactioncode');
    }

    public function charge_account(){
        return $this->hasMany('App\Waybill\ChargeAccount','contact_id','chargeto_id');
    }

    public function waybill_reference(){
        return $this->hasMany('App\Waybill\WaybillReference','transactioncode','transactioncode');
    }

    public function track_trace(){
        return $this->hasMany('App\RecordLogs\TrackAndTrace','transactioncode','transactioncode');
    }

    public function getTotalAdjAddAttribute(){
        // return $this->adjustment->where('add_less',1)->sum('adjustment_amount');
    }

    public function getTotalAdjLessAttribute(){
        // return $this->adjustment->where('add_less',0)->sum('adjustment_amount');
    }

    public function getTotalWithdrawAttribute(){
        return $this->orcr_detail->sum('withdraw');
    }

    public function getTotalDepositAttribute(){
        return $this->orcr_detail->sum('deposit');
    }

    public function scopeRemaining($query,$limit){
        
        // $query->leftJoin('tbladjustment', 'tblwaybills.transactioncode', '=', 'tbladjustment.transactioncode')
        //       ->leftJoin('tblorcrdetails', 'tblwaybills.transactioncode', '=', 'tblorcrdetails.transactioncode');
        // $query->
        // ->whereRaw("SUM(SUM(tblorcrdetails.withdraw)-SUM(tblorcrdetails.withdraw))> ?",[$limit]);
        // $query->whereRaw("SUM((total_withdraw-total_deposit)-(total_adj_add-total_adj_less)) > 0");
    }

    public function source(){
    	return $this->belongsTo('App\DOFFConfiguration\Branch','sourcebranch_id','branchoffice_no');
    }

    public function destination(){
    	return $this->belongsTo('App\DOFFConfiguration\Branch','destinationbranch_id','branchoffice_no');
    }

    public function shipper_address(){
        return $this->belongsTo('App\Waybill\ContactAddress','shipper_address_id','user_address_id');
    }
    public function consignee_address(){
        return $this->belongsTo('App\Waybill\ContactAddress','consignee_address_id','user_address_id');
    }
    public function waybill_shipment(){
        return $this->hasMany('App\Waybill\WaybillShipment','transactioncode','transactioncode')
        ->with(['cargo_type','waybill_shipment_details']);
    }

    public function delivery_address(){
        return $this->belongsTo('App\Waybill\Sector','delivery_sector_id','sectorate_no');
    }
}
