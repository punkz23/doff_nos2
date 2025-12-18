<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingAsGuestRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'step1.type'=>"required",
            'step1.lname'=>$this->step1['type']==0 ? "required" : "",
            'step1.fname'=>$this->step1['type']==0 ? "required" : "",
            'step1.email'=>$this->step1['type']==0 ? "required" : "",
            'step1.contact_no'=>$this->step1['type']==0 ? "required" : "",
            
            'step2.lname'=>$this->step2['use_company']==0 ? "required" : "",
            'step2.fname'=>$this->step2['use_company']==0 ? "required" : "",
            'step2.company'=>$this->step2['use_company']==1 ? "required" : "",
            'step2.email'=>$this->step1['type']==1 ? "required" : "",
            'step2.contact_no'=>'required',
            'step2.barangay'=>'required',
            'step2.city'=>"required|exists:waybill.tblcitiesminicipalities,cities_id",

            'step3.lname'=>$this->step3['use_company']==0 ? "required" : "",
            'step3.fname'=>$this->step3['use_company']==0 ? "required" : "",
            'step3.company'=>$this->step3['use_company']==1 ? "required" : "",
            'step3.contact_no'=>'required',
            'step3.barangay'=>'required',
            'step3.city'=>"required|exists:waybill.tblcitiesminicipalities,cities_id",

            'step4.payment_type'=>'required|in:CI,CD,CH',
            'step4.destinationbranch_id'=>'required|exists:doff_configuration.tblbranchoffice,branchoffice_no',
            'step4.shipment_type'=>'required|in:BREAKABLE,PERISHABLE,LETTER,OTHERS',
            'step4.declared_value'=>'required|numeric|'.($this->step4['shipment_type']=='OTHERS' ? 'min:2000' : 'in:'.(in_array($this->step4['shipment_type'],['BREAKABLE','PERISHABLE']) ? 1000: 500)),
            

            'step4.item_description'=>'required|array|min:1|max:5',
            'step4.unit'=>'required|array|min:1|max:5',
            'step4.quantity'=>'required|array|min:1|max:5',

            'step4.item_description.*'=>'required|exists:waybill.tblstocks,stock_no',
            'step4.unit.*'=>'required|exists:waybill.tblunit,unit_no',
            'step4.quantity.*'=>'required|numeric',

            'step5.agree'=>'in:1'
        ];
    }

    public function messages(){
        return [
            'step4.declared_value.in'=>'Invalid declared value',
            'step5.agree.in'=>'Please accept our terms and condition'
        ];
    }

    public function attributes(){
        return [
            'step1.lname'=>"Shipper's lastname",
            'step1.fname'=>"Shipper's firstname",
            'step1.company'=>"Shipper's company",
            'step1.contact_no'=>"Shipper's contact number",
            'step1.barangay'=>"Shipper's barangay",
            'step1.city'=>"Shipper's city",

            'step2.lname'=>"Shipper's lastname",
            'step2.fname'=>"Shipper's firstname",
            'step2.company'=>"Shipper's company",
            'step2.contact_no'=>"Shipper's contact number",
            'step2.barangay'=>"Shipper's barangay",
            'step2.city'=>"Shipper's city",
            
            'step3.lname'=>"Consignee's lastname",
            'step3.fname'=>"Consignee's firstname",
            'step3.company'=>"Consignee's company",
            'step3.contact_no'=>"Consignee's contact number",
            'step3.barangay'=>"Consignee's barangay",
            'step3.city'=>"Consignee's city",

            'step4.payment_type'=>"Transaction type",
            'step4.shipment_type'=>'Shipment type',
            'step4.declared_value'=>'Declared value',
            'step4.destinationbranch_id'=>'Destination',

            'step4.item_description.0'=>'Item Description 1',
            'step4.unit.0'=>'Unit 1',
            'step4.quantity.0'=>'Quantity 1',
            'step4.item_description.1'=>'Item Description 2',
            'step4.unit.1'=>'Unit 2',
            'step4.quantity.1'=>'Quantity 2',
            'step4.item_description.2'=>'Item Description 3',
            'step4.unit.2'=>'Unit 3',
            'step4.quantity.2'=>'Quantity 3',
            'step4.item_description.3'=>'Item Description 4',
            'step4.unit.3'=>'Unit 4',
            'step4.quantity.3'=>'Quantity 4',
            'step4.item_description.4'=>'Item Description 5',
            'step4.unit.4'=>'Unit 5',
            'step4.quantity.4'=>'Quantity 5',
        ];
    }
}
