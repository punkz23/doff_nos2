<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Rule;
use App\Rules\CheckDiscountCoupon;
class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'step1.shipper_id'=>"required",
            'step1.shipper_address_id'=>$this->step1['shipper_id']!="new" ? "required" : "",
            'step1.shipper_email'=>$this->step1['shipper_id']!="new" ? ($this->step1['shipper_email']!=null || $this->step1['shipper_email']!='' ? "email" : "") : "",
            'step1.shipper.lname'=>$this->step1['shipper_id']=="new" && $this->step1['shipper']['use_company']==0 ? "required" : "",
            'step1.shipper.fname'=>$this->step1['shipper_id']=="new" && $this->step1['shipper']['use_company']==0 ? "required" : "",
            'step1.shipper.company'=>$this->step1['shipper_id']=="new" && $this->step1['shipper']['use_company']==1 ? "required" : "",
            'step1.shipper.email'=>$this->step1['shipper_id']=="new" ? ($this->step1['shipper']['email']!=null || $this->step1['shipper']['email']!='' ? "email" : "") : "",
            // 'step1.shipper.contact_no'=>$this->step1['shipper_id']=="new" ? "required" : "",
            'step1.shipper.barangay'=>$this->step1['shipper_id']=="new" || $this->step1['shipper_address_id']=="new" ? "required" : "",
            'step1.shipper.city'=>$this->step1['shipper_id']=="new" || $this->step1['shipper_address_id']=="new" ? "required|exists:waybill.tblcitiesminicipalities,cities_id" : "",

            'step1.consignee_id'=>"required",
            'step1.consignee_address_id'=>$this->step1['consignee_id']!="new" ? "required" : "",
            'step1.consignee_email'=>$this->step1['consignee_id']!="new" ? ($this->step1['consignee_email']!=null || $this->step1['consignee_email']!='' ? "email" : "") : "",
            'step1.consignee.lname'=>$this->step1['consignee_id']=="new" && $this->step1['consignee']['use_company']==0 ? "required" : "",
            'step1.consignee.fname'=>$this->step1['consignee_id']=="new" && $this->step1['consignee']['use_company']==0 ? "required" : "",
            'step1.consignee.company'=>$this->step1['consignee_id']=="new" && $this->step1['consignee']['use_company']==1 ? "required" : "",
            'step1.consignee.email'=>$this->step1['consignee_id']=="new" ? ($this->step1['consignee']['email']!=null || $this->step1['consignee']['email']!='' ? "email" : "") : "",
            'step1.consignee.barangay'=>$this->step1['consignee_id']=="new" || $this->step1['consignee_address_id']=="new" ? "required" : "",
            'step1.consignee.city'=>$this->step1['consignee_id']=="new" || $this->step1['consignee_address_id']=="new" ? "required|exists:waybill.tblcitiesminicipalities,cities_id" : "",

            'step2.payment_type'=>'required|in:CI,CD,CH',
            'step2.destinationbranch_id'=>'required|exists:doff_configuration.tblbranchoffice,branchoffice_no',
            'step2.shipment_type'=>'required|in:BREAKABLE,PERISHABLE,LETTER,OTHERS',
            'step2.declared_value'=>'required|numeric|'.($this->step2['shipment_type']=='OTHERS' ? 'min:2000' : 'in:'.(in_array($this->step2['shipment_type'],['BREAKABLE','PERISHABLE']) ? 1000: 500)),
            // 'step2.discount_coupon'=>$this->step2['discount_coupon']!=null ? new CheckDiscountCoupon : '',

            'step3.item_description'=>'required|array|min:1|max:5',
            'step3.unit'=>'required|array|min:1|max:5',
            'step3.quantity'=>'required|array|min:1|max:5',

            'step3.item_description.*'=>'required|exists:waybill.tblstocks,stock_no',
            'step3.unit.*'=>'required|exists:waybill.tblunit,unit_no',
            'step3.quantity.*'=>'required|numeric',

            'step4.agree'=>'in:1'
        ];
    }

    public function messages(){
        return [
            'step2.declared_value.in'=>'Invalid declared value',
            'step4.agree.in'=>'Please accept our terms and condition'
        ];
    }

    public function attributes(){
        return [
            'step1.shipper_id'=>"Shipper's name",
            'step1.shipper_address_id'=>"Shipper's Address",
            'step1.shipper.lname'=>"Shipper's lastname",
            'step1.shipper.fname'=>"Shipper's firstname",
            'step1.shipper.company'=>"Shipper's company",
            'step1.shipper.contact_no'=>"Shipper's contact number",
            'step1.shipper.barangay'=>"Shipper's barangay",
            'step1.shipper.city'=>"Shipper's city",
            'step1.consignee_id'=>"Consignee's name",
            'step1.consignee_address_id'=>"Consignee's Address",
            'step1.consignee.lname'=>"Consignee's lastname",
            'step1.consignee.fname'=>"Consignee's firstname",
            'step1.consignee.company'=>"Consignee's company",
            'step1.consignee.contact_no'=>"Consignee's contact number",
            'step1.consignee.barangay'=>"Consignee's barangay",
            'step1.consignee.city'=>"Consignee's city",
            'step2.payment_type'=>"Transaction type",
            'step2.shipment_type'=>'Shipment type',
            'step2.declared_value'=>'Declared value',
            'step2.destinationbranch_id'=>'Destination',
            'step3.item_description.0'=>'Item Description 1',
            'step3.unit.0'=>'Unit 1',
            'step3.quantity.0'=>'Quantity 1',
            'step3.item_description.1'=>'Item Description 2',
            'step3.unit.1'=>'Unit 2',
            'step3.quantity.1'=>'Quantity 2',
            'step3.item_description.2'=>'Item Description 3',
            'step3.unit.2'=>'Unit 3',
            'step3.quantity.2'=>'Quantity 3',
            'step3.item_description.3'=>'Item Description 4',
            'step3.unit.3'=>'Unit 4',
            'step3.quantity.3'=>'Quantity 4',
            'step3.item_description.4'=>'Item Description 5',
            'step3.unit.4'=>'Unit 5',
            'step3.quantity.4'=>'Quantity 5',
        ];
    }
}
