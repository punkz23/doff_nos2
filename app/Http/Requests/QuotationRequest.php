<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->has('sub_quantity') && $this->has('unit_weight') && $this->has('weight') && $this->has('unit_dimension') && $this->has('height') && $this->has('width') && $this->has('length')){
            $this->merge([
                'sub_quantity'=>array_values($this->sub_quantity),
                'unit_weight'=>array_values($this->unit_weight),
                'weight'=>array_values($this->weight),
                'unit_dimension'=>array_values($this->unit_dimension),
                'height'=>array_values($this->height),
                'width'=>array_values($this->width),
                'length'=>array_values($this->length)
            ]);
        }
        
        return [
            'contact_no'=>'required',
            'email'=>'required|email',
            'origin_branch'=>'required',
            'destination_branch'=>'required',
            'declared_value'=>'required|numeric',
            'street_delivery'=>$this->has('delivery') ? 'required' : '',
            'barangay_delivery'=>$this->has('delivery') ? 'required' : '',
            'city_delivery'=>$this->has('delivery') ? 'required|exists:waybill.tblcitiesminicipalities,cities_id' : '',
            'street_pickup'=>$this->has('pickup') ? 'required' : '',
            'barangay_pickup'=>$this->has('pickup') ? 'required' : '',
            'city_pickup'=>$this->has('pickup') ? 'required|exists:waybill.tblcitiesminicipalities,cities_id' : '',
            'item_code'=>'required',
            'item_name'=>'required',
            'unit_code'=>'required',

            'sub_quantity'=>'required',
            'unit_weight'=>'required',
            'weight'=>'required',
            'unit_dimension'=>'required',
            'height'=>'required',
            'width'=>'required',
            'length'=>'required',

            'item_code.*'=>'required|exists:waybill.tblstocks,stock_no',
            'item_name.*'=>'required|min:1',
            'unit_code.*'=>'required|exists:waybill.tblunit,unit_no|min:1',
            'sub_quantity.*.*'=>'required|numeric',
            'unit_weight.*.*'=>'required|in:kg',
            'weight.*.*'=>'required|numeric',
            'unit_dimension.*.*'=>'required',
            'height.*.*'=>'required|numeric',
            'width.*.*'=>'required|numeric',
            'length.*.*'=>'required|numeric',
        ];
    }

    public function messages(){
        
        return [
            'street_delivery.required'=>'Street for delivery is required',
            'barangay_delivery.required'=>'Barangay for delivery is required',
            'city_delivery.required'=>'City for delivery is required',
            'city_delivery.exists'=>'The selected city for delivery is invalid',
            'street_pickup.required'=>'Street for pickup is required',
            'barangay_pickup.required'=>'Barangay for pickup is required',
            'city_pickup.required'=>'City for pickup is required',
            'city_pickup.exists'=>'The selected city for pickup is invalid',
            'length.*.*.required'=>'Length is required',
            'sub_quantity.*.*.required'=>'Sub quantity is required',
            'unit_weight.*.*.required'=>'Unit weight is required',
            'weight.*.*.required'=>'Weight is required',
            'unit_dimension.*.*.required'=>'Unit Dimension is required',
            'height.*.*.required'=>'Height is required',
            'width.*.*.required'=>'Width is required'
        ];
    }

    public function attribute(){
        return [
            'item_code'=>'Description',
            'unit_code'=>'Unit'
        ];
    }
}
