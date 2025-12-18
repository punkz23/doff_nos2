<form id="form-step-1" class="form-horizontal form-label-left">
    <div hidden>
    <select class="select2 address_city">
        <option value="none" disabled selected>--Select City--</option>
        @foreach($provinces as $province)
            <optgroup label="{{$province->province_name}}">
                 @foreach($province->city as $city)
                     <option data-province="{{$province->province_name}}" data-postal_code="{{$city->postal_code}}" value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                 @endforeach
             </optgroup>
         @endforeach
    </select>

    <select  class="select2 business_category">
        <option value="none" selected disabled>--Select business type--</option>
        @foreach($business_types as $business_type)
            <optgroup label="{{$business_type->businesstype_description}}">
                @foreach($business_type->business_type_category as $business_type_category)
                    <option data-province="{{$business_type_category->businesstype_category_description}}" value="{{$business_type_category->businesstype_category_id}}">{{$business_type_category->businesstype_category_description}}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>

    </div>

    @php
    $city='';
    $rebate_point=0;
    @endphp


    @foreach($rebate_factor as $rfactor)
        @php $rebate_point=$rfactor->rebate_point; @endphp
    @endforeach

    @if( $rebate_point > 0 && Auth::user()->personal_corporate==0 )
        <div class="form-group" >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div class=" col-md-6 col-sm-6 col-xs-12  alert alert-danger alert-dismissible " role="alert" style="text-align:left;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            *Verify your account to earn {{ number_format($rfactor->rebate_point,2) }} point for Online Booking and another {{ number_format($rfactor->rebate_point,2) }} point for using/uploading QR Codes of verified contacts. Earned points will be automatically deducted from your freight charges upon creation of waybill.
        </div>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        </div>
    @endif
    <div class="form-group" >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div class=" col-md-6 col-sm-6 col-xs-12  alert alert-info alert-dismissible " role="alert" style="text-align:left;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <i class="fa fa-lightbulb-o"></i><b> REGULAR</b>- Book your cargoes before shipping<br>
            <i class="fa fa-lightbulb-o"></i><b> PASABOX</b>- Book transfer of your cargoes from Third Party Shipping Company/Delivery Service to Daily Overland and let us take care of the delivery to your consignee.
        </div>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        </div>
    <input type="hidden" value="{{ $rebate_point }}" id="rebate_point" />
    <div class="form-group"  >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div class="col-md-6 col-sm-6 col-xs-12 input-group">
        <h5>
            <input {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==0 ? 'checked' : '' ) : 'checked' }} type="checkbox" value="1" id="online_booking_type_1" name="online_booking_type_1" > Regular
            &emsp;<input {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'checked' :  '' ) : '' }} type="checkbox" value="2" id="online_booking_type_2" name="online_booking_type_2" > Pasabox
        </h5>
        </div>
    </div>
    <div class="form-group div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }} >
        <center>
            <h3>Shipping Company</h3>
            <i>(NOT required)</i>
        </center>
    </div>

    <div class="form-group shipping-company div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Name <span class="required"></span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipping_cid" class="form-control select2 col-md-7 col-xs-12" id="shipping_cid">
                <option value="none" selected>--Select Shipping Company--</option>
                <option value="new">--New Shipping Company--</option>
                @foreach($contacts_pasabox as $row)

                    @php
                        $address_qr=0;
                        if($row->shipper_consignee->qr_code !='' && $row->shipper_consignee->qr_code !=null){
                            $address=array();
                            $address_qr=1;
                            $qr_profile=$row->shipper_consignee->qr_profile;
                            foreach($qr_profile->qr_code_details as $row_qr){
                                array_push($address,$row_qr->qr_code_profile_address);
                            }

                        }

                    @endphp
                    <option {{ $row->shipper_consignee->default_customer == 1 ? 'selected' : '' }} value="{{$row->contact_id}}" data-qr="{{$row->shipper_consignee->qr_code}}" data-address="{{ $address_qr ==1 ? json_encode($address) : $row->user_address }}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->shipping_company_id==$row->contact_id ? 'selected' : '') : ''}}>{{$row->fileas}}</option>
                @endforeach
            </select>
            <p id="p_shipping_cname" hidden>
            <br>
            <input type="text" placeholder="Shipping Company Name" name="shipping_cname" class="form-control shipping_cname">
            </p>
        </div>
    </div>

    <div class="form-group shipping-info div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Email <span class="required"></span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input value="{{Route::currentRouteName()=='waybills.edit' ? $data->shipping_company_email : ''}}" type="email" name="shipping_cemail" class="form-control email-address shipping_cemail">
        </div>
    </div>
    <div class="form-group shipping-info div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Mobile Number
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
             <input value="{{Route::currentRouteName()=='waybills.edit' ? $data->shipping_company_cno : ''}}" type="number" class="form-control mobile_no shipping_cmobile_no" name="shipping_cmobile_no" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
        </div>
    </div>

    <div class="form-group shipping-info div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Landline Number
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input value="{{Route::currentRouteName()=='waybills.edit' ? $data->shipping_company_lno : ''}}" type="text" class="form-control telephone_no shipping_clandline_no" name="shipping_clandline_no" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
        </div>
    </div>
    <div class="form-group shipping-info div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Address: <span class="required"></span>

        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select  name="shipping_address_id" class="form-control select2 col-md-7 col-xs-12 shipping_address_id">
                <option value="none" selected >--PLEASE SELECT ADDRESS--</option>
                @php $shipping_qr=0; @endphp
                @if(Route::currentRouteName()=='waybills.edit' && $data['pasabox']==1 && $data->shipping_company !='' )

                    @if( isset($data->shipping_sc->qr_code) && $data->shipping_sc->qr_code !='' && $data->shipping_sc->qr_code  != null )
                         @php $shipping_qr=1; @endphp
                        @foreach( $data->shipping_sc->qr_profile->qr_code_details  as $row)
                            <option {{$data->shipper_address_id==$row->qr_code_profile_address->useraddress_no ? 'selected' : ''}} value="{{$row->qr_code_profile_address->useraddress_no}}">{{$row->qr_code_profile_address->full_address}}</option>
                        @endforeach
                    @else
                        <option value="new">--ADD NEW--</option>
                        @foreach($data->shipping_company->user_address as $row)
                            <option {{$data->shipping_company_address_id==$row->useraddress_no ? 'selected' : ''}} value="{{$row->useraddress_no}}">{{$row->full_address}}</option>
                        @endforeach
                        @endif
                @endif
            </select>
        </div>
    </div>
    <div class="form-group shipping-address" hidden>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            City: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipping_address_city" class="select2 cities_shipping shipping_address_city">
                <option value="none" disabled selected>--Select City--</option>
            </select>
        </div>
    </div>
    <div class="form-group shipping-address" hidden>

        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Barangay: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipping_address_brgy"  class="form-control shipping_address_brgy">
            </select>
        </div>

    </div>

    <div class="form-group shipping-address" hidden>

        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Street/Bldg/Room:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input onkeypress="return max_street(event)"  maxlength="100" type="text" name="shipping_address_street" class="form-control shipping_address_street">
        </div>

    </div>

    <center>
        <h3>Shipper Information</h3>
    </center>
    <div class="form-group" >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Upload QR Code
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12 input-group">
            <input type="file" accept="image/*" id="shipper-decode-file" />
            <span class="input-group-btn" hidden>
                <button class="btn-xs btn-danger shipper-decode-remove" type="button"><i class="fa fa-refresh"></i> Clear</button>
            </span>
            <div hidden><canvas style="height:100x;width:100px;"  id="shipper-decode-canvas"></canvas></div>
            <p hidden><button id="shipper-decode-btn" type="button">Upload</button></p>
            <input id="shipper_qr_code"   name="shipper_qr_code"   type="hidden">
            <input id="shipper_qr_code_cid"   name="shipper_qr_code_cid"   type="hidden">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Name <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipper_id" class="form-control contacts selectdata_group col-md-7 col-xs-12" id="shipper_id">
                <option data-icon="" value="none" disabled selected>--Select Shipper--</option>
                <option data-icon=""  value="new">--New Shipper--</option>
                @foreach($contacts as $row)

                    @php
                        $address_qr=0;
                        $text_icon='';
                        if($row->shipper_consignee->qr_code !='' && $row->shipper_consignee->qr_code !=null){
                            $text_icon ='fa-qrcode';
                            $address=array();
                            $address_qr=1;
                            $qr_profile=$row->shipper_consignee->qr_profile;
                            foreach($qr_profile->qr_code_details as $row_qr){
                                array_push($address,$row_qr->qr_code_profile_address);
                            }

                        }

                    @endphp
                    <option data-lname="{{$row->lname}}" data-mname="{{$row->mname}}" data-fname="{{$row->fname}}" data-icon="{{ $text_icon }}"  {{ $row->shipper_consignee->default_customer == 1 ? 'selected' : '' }} value="{{$row->contact_id}}" data-qr="{{$row->shipper_consignee->qr_code}}" data-address="{{ $address_qr ==1 ? json_encode($address) : $row->user_address }}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->shipper_id==$row->contact_id ? 'selected' : '') : ''}}>{{$row->fileas}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group div_shipper_mname_update" hidden>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Middle Name <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="shipper_mname_update" name="shipper_mname_update" value="0" type="hidden">
            <input type="text" name="shipper_mname_update_text" class="form-control shipper_mname_update_text">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Address <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select id="shipper_address_id" name="shipper_address_id" class="form-control addresses select2 col-md-7 col-xs-12">
                @php $shipper_qr=0; @endphp
                @if(Route::currentRouteName()=='waybills.edit')
                <option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option>
                    @if( isset($data->shipper_sc->qr_code) && $data->shipper_sc->qr_code !='' && $data->shipper_sc->qr_code  != null )
                         @php $shipper_qr=1; @endphp
                        @foreach( $data->shipper_sc->qr_profile->qr_code_details  as $row)
                            <option {{$data->shipper_address_id==$row->qr_code_profile_address->useraddress_no ? 'selected' : ''}} value="{{$row->qr_code_profile_address->useraddress_no}}">{{$row->qr_code_profile_address->full_address}}</option>
                        @endforeach
                    @else
                        <option value="new">--ADD NEW--</option>
                        @foreach($data->shipper->user_address as $row)
                            <option {{$data->shipper_address_id==$row->useraddress_no ? 'selected' : ''}} value="{{$row->useraddress_no}}">{{$row->full_address}}</option>
                        @endforeach
                        @endif
                @endif
            </select>
        </div>
    </div>



    <div class="new-contact shipper-form" hidden>
        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">

            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input name="shipper.use_company" type="checkbox" class="use_company ace" />
                <span class="lbl"> Use Company</span>
            </div>

        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Lastname <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="shipper.lname" class="form-control">
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Firstname <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="shipper.fname" class="form-control">
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Middlename <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="shipper.mname" class="form-control">
            </div>
        </div>

        <div class="form-group shipper-company" hidden>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Company <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="shipper.company" class="form-control">
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Email <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="shipper.email" class="form-control email-address">
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div>
                    <table class="table-mobile" width="100%">
                        <tr>
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input type="number" class="form-control mobile_no" name="shipper.shipper_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary new-add-mobile" data-for="shipper"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Landline Number</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div>
                    <table class="table-telephone" width="100%">
                        <tr>
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input type="text" class="form-control telephone_no" name="shipper.shipper_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary new-add-telephone" data-for="shipper"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group shipper-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Business Type
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="shipper.business_category_id" class="select2 biz_category">
                    <option value="none" selected disabled>--Select business type--</option>

                </select>
            </div>
        </div>


        <div class="form-group shipper-address">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                City: <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="shipper.city" class="select2 cities" id="city">
                    <option value="none" disabled selected>--Select City--</option>

                </select>
            </div>
        </div>
        <div class="form-group shipper-address">

            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Barangay: <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                {{-- <input type="text" name="shipper.barangay" class="form-control"> --}}
                <select name="shipper.barangay" id="shipper_brgy" class="form-control barangay">

                </select>
            </div>

        </div>

        <div class="form-group shipper-address">

            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Street/Bldg/Room:
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input onkeypress="return max_street(event)"  maxlength="100" type="text" name="shipper.street" class="form-control">
            </div>

        </div>


    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Email Address: <span class="required"></span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input {{ $shipper_qr==1 ? 'readonly' : '' }} type="text"  name="shipper_email" class="form-control  email-address" id="shipper_email" value="{{Route::currentRouteName()=='waybills.edit' ? $data->shipper->email : ''}}">
        </div>
    </div>



    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
                <table class="table-mobile" width="100%">
                    <tr>
                        <td>
                            <div class="input-group mobile-number" style="margin-bottom:0;">
                                <input {{ $shipper_qr==1 ? 'readonly' : '' }} type="number" class="form-control mobile_no mobile_no_shipper" name="shipper_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{Route::currentRouteName()=='waybills.edit' ? (count($shipper_contacts_mobile)>0 ? $shipper_contacts_mobile[0]['waybill_contacts_no'] : '') : ''}}">
                                <span class="input-group-btn">
                                    <button {{ $shipper_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-mobile add-mobile-shipper" data-for="shipper"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @if(Route::currentRouteName()=='waybills.edit' && count($shipper_contacts_mobile)>1)
                        <tr class="shipper_add_mn">
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input {{ $shipper_qr==1 ? 'readonly' : '' }}  type="number" class="form-control mobile_no" name="shipper_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{$shipper_contacts_mobile[1]['waybill_contacts_no']}}">
                                    <span class="input-group-btn">
                                        <button  {{ $shipper_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Landline Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
                <table class="table-telephone" width="100%">
                    <tr>
                        <td>
                            <div class="input-group telephone-number" style="margin-bottom:0;">
                                <input {{ $shipper_qr==1 ? 'readonly' : '' }} type="text" class="form-control telephone_no telephone_no_shipper" name="shipper_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{Route::currentRouteName()=='waybills.edit' ? (count($shipper_contacts_telephone)>0 ? $shipper_contacts_telephone[0]['waybill_contacts_no'] : '') : ''}}">
                                <span class="input-group-btn">
                                    <button {{ $shipper_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-telephone add-telephone-shipper" data-for="consignee"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @if(Route::currentRouteName()=='waybills.edit' && count($shipper_contacts_telephone)>1)
                        <tr class="shipper_add_tn">
                            <td>
                                <div class="input-group telephone-number" style="margin-bottom:0;">
                                    <input {{ $shipper_qr==1 ? 'readonly' : '' }} type="text" class="form-control telephone_no" name="shipper_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{$shipper_contacts_telephone[1]['waybill_contacts_no']}}">
                                    <span class="input-group-btn">
                                        <button {{ $shipper_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-telephone" data-for="consignee"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>



    <center>
        <h3>Consignee Information</h3>
    </center>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="alert alert-info alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <strong>NOTE:</strong> If you want your consignee to receive an email notification once your cargo is ready for pickup/delivery, please fill up the consignee email field.
              </div>
        </div>
    </div>
    <div class="form-group" >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Upload QR Code
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12 input-group">
            <input type="file" accept="image/*" id="consignee-decode-file" />
            <span class="input-group-btn" hidden>
                <button class="btn-xs btn-danger consignee-decode-remove" type="button"><i class="fa fa-refresh"></i> Clear</button>
            </span>
            <div hidden><canvas style="height:100x;width:100px;"  id="consignee-decode-canvas"></canvas></div>
            <p hidden><button id="consignee-decode-btn" type="button">Upload</button></p>
            <input id="consignee_qr_code"   name="consignee_qr_code"   type="hidden">
            <input id="consignee_qr_code_cid"   name="consignee_qr_code_cid"   type="hidden">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Name <span class="required">*</span>
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="consignee_id" class="form-control contacts selectdata_group col-md-7 col-xs-12" id="consignee_id">
                @if(
                    Auth::user()->personal_corporate==1
                    && session('pca_no') != ''
                    && session('pca_atype') =='external'
                )
                    @foreach($contacts_external as $row)
                        @php
                        $address_qr=0;
                        $text_icon ='';
                        @endphp
                        <option  data-lname="{{$row->lname}}" data-mname="{{$row->mname}}" data-fname="{{$row->fname}}" data-icon="{{ $text_icon }}" value="{{$row->contact_id}}" data-qr="" data-address="{{ $address_qr ==1 ? json_encode($address) : $row->user_address }}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" >{{$row->fileas}}</option>
                    @endforeach

                @else
                    <option data-icon="" value="none" disabled selected>--Select Consignee--</option>
                    <option data-icon="" value="new">--New Consignee--</option>
                    @foreach($contacts as $row)
                        @php
                        $address_qr=0;
                        $text_icon ='';
                            if($row->shipper_consignee->qr_code !='' && $row->shipper_consignee->qr_code !=null){
                                $text_icon ='fa-qrcode';
                                $address=array();
                                $address_qr=1;
                                $qr_profile=$row->shipper_consignee->qr_profile;
                                foreach($qr_profile->qr_code_details as $row_qr){
                                    array_push($address,$row_qr->qr_code_profile_address);
                                }

                            }

                        @endphp
                        <option data-lname="{{$row->lname}}" data-mname="{{$row->mname}}" data-fname="{{$row->fname}}" data-icon="{{ $text_icon }}" value="{{$row->contact_id}}" data-qr="{{$row->shipper_consignee->qr_code}}" data-address="{{ $address_qr ==1 ? json_encode($address) : $row->user_address }}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->consignee_id==$row->contact_id ? 'selected' : '') : ''}}>{{$row->fileas}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="form-group div_consignee_mname_update" hidden>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Middle Name <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input id="consignee_mname_update" name="consignee_mname_update" value="0" type="hidden">
            <input type="text" name="consignee_mname_update_text" class="form-control consignee_mname_update_text">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Address <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="consignee_address_id" id="consignee_address_id" class="form-control addresses select2 col-md-7 col-xs-12">
                @php $consignee_qr=0; @endphp
                @if(Route::currentRouteName()=='waybills.edit')
                    <option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option>
                    @if( isset($data->consignee_sc->qr_code) && $data->consignee_sc->qr_code !='' && $data->consignee_sc->qr_code  != null )
                        @php $consignee_qr=1; @endphp
                        @foreach( $data->consignee_sc->qr_profile->qr_code_details  as $row)
                            <option {{$data->consignee_address_id==$row->qr_code_profile_address->useraddress_no ? 'selected' : ''}} value="{{$row->qr_code_profile_address->useraddress_no}}">{{$row->qr_code_profile_address->full_address}}</option>
                        @endforeach
                    @else
                        @if(
                            !(
                                Auth::user()->personal_corporate==1
                                && session('pca_no') != ''
                                && session('pca_atype') =='external'
                            )
                        )
                        <option value="new">--ADD NEW--</option>
                        @endif
                        @foreach($data->consignee->user_address as $row)
                            <option {{$data->consignee_address_id==$row->useraddress_no ? 'selected' : ''}} value="{{$row->useraddress_no}}">{{$row->full_address}}</option>
                        @endforeach
                    @endif
                @endif
            </select>
        </div>
    </div>

    <div class="new-contact consignee-form" hidden>
        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">

            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input name="consignee.use_company" type="checkbox" class="use_company ace" />
                <span class="lbl"> Use Company</span>
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Lastname <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="consignee.lname" class="form-control">
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Firstname <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="consignee.fname" class="form-control">
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Middlename <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="consignee.mname" class="form-control">
            </div>
        </div>

        <div class="form-group consignee-company" hidden>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Company <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" name="consignee.company" class="form-control">
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Email
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input  type="text" name="consignee.email" class="form-control email-address">
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div>
                    <table class="table-mobile" width="100%">
                        <tr>
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input type="number" class="form-control mobile_no" name="consignee.consignee_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary new-add-mobile" data-for="consignee"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Landline Number</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div>
                    <table class="table-telephone" width="100%">
                        <tr>
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input  type="text" class="form-control telephone_no" name="consignee.consignee_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <span class="input-group-btn">
                                        <button  type="button" class="btn btn-primary new-add-telephone" data-for="consignee"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-group consignee-info">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Business Type
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="consignee.business_category_id" class="select2 biz_category">
                    <option value="none" selected disabled>--Select business type--</option>

                </select>
            </div>
        </div>





        <div class="form-group consignee-address">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                City: <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="consignee.city" class="select2 cities" id="city">
                    <option value="none" disabled selected>--Select City--</option>
                </select>
            </div>
        </div>

        <div class="form-group consignee-address">

            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Barangay: <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                {{-- <input type="text" name="consignee.barangay" class="form-control"> --}}
                <select name="consignee.barangay" id="consignee_brgy" class="form-control barangay">

                </select>
            </div>

        </div>

        <div class="form-group consignee-address">

            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Street/Bldg/Room:
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <input onkeypress="return max_street(event)"  maxlength="100" type="text" name="consignee.street" class="form-control">
            </div>

        </div>

    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Email Address: <span class="required"></span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" {{ $consignee_qr==1 ? 'readonly' : '' }} name="consignee_email" class="form-control email-address" id="consignee_email" value="{{Route::currentRouteName()=='waybills.edit' ? $data->consignee->email : ''}}">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
                <table class="table-mobile" width="100%">
                    <tr>
                        <td>
                            <div class="input-group mobile-number" style="margin-bottom:0;">
                                <input {{ $consignee_qr==1 ? 'readonly' : '' }} type="number" class="form-control mobile_no mobile_no_consignee" name="consignee_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{Route::currentRouteName()=='waybills.edit' ? (count($consignee_contacts_mobile)>0 ? $consignee_contacts_mobile[0]['waybill_contacts_no'] : '') : ''}}">
                                <span class="input-group-btn">
                                    <button {{ $consignee_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-mobile add-mobile-consignee" data-for="consignee"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @if(Route::currentRouteName()=='waybills.edit' && count($consignee_contacts_mobile)>1)
                        <tr class="consignee_add_mn">
                            <td>
                                <div class="input-group mobile-number" style="margin-bottom:0;">
                                    <input {{ $consignee_qr==1 ? 'readonly' : '' }} type="number" class="form-control mobile_no" name="shipper_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{$consignee_contacts_mobile[1]['waybill_contacts_no']}}">
                                    <span class="input-group-btn">
                                        <button {{ $consignee_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Landline Number</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div>
                <table class="table-telephone" width="100%">
                    <tr>
                        <td>
                            <div class="input-group telephone-number" style="margin-bottom:0;">
                                <input {{ $consignee_qr==1 ? 'readonly' : '' }} type="text" class="form-control telephone_no telephone_no_consignee" name="consignee_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{Route::currentRouteName()=='waybills.edit' ? (count($consignee_contacts_telephone)>0 ? $consignee_contacts_telephone[0]['waybill_contacts_no'] : '') : ''}}">
                                <span class="input-group-btn">
                                    <button {{ $consignee_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-telephone add-telephone-consignee" data-for="consignee"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @if(Route::currentRouteName()=='waybills.edit' && count($consignee_contacts_telephone)>1)
                        <tr class="consignee_add_tn">
                            <td>
                                <div class="input-group telephone-number" style="margin-bottom:0;">
                                    <input {{ $consignee_qr==1 ? 'readonly' : '' }} type="text" class="form-control telephone_no" name="shipper_telephone_no[]" placeholder="########"  maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{$consignee_contacts_telephone[1]['waybill_contacts_no']}}">
                                    <span class="input-group-btn">
                                        <button {{ $consignee_qr==1 ? 'disabled' : '' }} type="button" class="btn btn-primary add-telephone" data-for="consignee"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>


</form>

