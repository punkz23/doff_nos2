<?php

namespace App\Observers;
use App\OnlineSite\Waybill;
use App\RecordLogs\WaybillTrailLogs;
use Auth;
class WaybillObserver
{
    private $update_message = '';

    public function created(Waybill $waybill){
        WaybillTrailLogs::create(['reference_no'=>$waybill->reference_no,'user_id'=>Auth::id(),'action_taken'=>'Booking with reference #'.$waybill->reference_no.' has been created']);
    }
    
    public function updated(Waybill $waybill){
        $message = '';
        if($waybill->isDirty('consignee_id')){
            $message = $message.'Consignee';
        }
        if($waybill->isDirty('consignee_address_id')){
            $message = $message.($message!='' ? ',' : '').'Consignee Address';
        }
        if($waybill->isDirty('shipper_id')){
            $message = $message.'Shipper';
        }
        if($waybill->isDirty('shipper_address_id')){
            $message = $message.($message!='' ? ',' : '').'Shipper Address';
        }
        if($waybill->isDirty('declared_value')){
            $message = $message.($message!='' ? ',' : '').'Declared Value';
        }
        if($waybill->isDirty('shipment_type')){
            $message = $message.($message!='' ? ',' : '').'Shipment Type';
        }
        if($waybill->isDirty('destinationbranch_id')){
            $message = $message.($message!='' ? ',' : '').'Destination';
        }
        if($waybill->isDirty('payment_type')){
            $message = $message.($message!='' ? ',' : '').'Transaction type';
        }
        $message=$message.' has been updated';

        WaybillTrailLogs::create(['reference_no'=>'OL-20538OB','user_id'=>Auth::id(),'action_taken'=>$message]);
    }

    

    public function deleted(Waybill $waybill){
        WaybillTrailLogs::create(['reference_no'=>$waybill->reference_no,'user_id'=>Auth::id(),'action_taken'=>'Booking with reference #'.$waybill->reference_no.' has been deleted']);
    }

}
