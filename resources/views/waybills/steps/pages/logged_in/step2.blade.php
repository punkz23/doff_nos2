<form id="form-step-2" class="form-horizontal">

    <div {{ Auth::user()->personal_corporate==1 ? '' : 'hidden'  }} class="form-group" >
        <input type="hidden" name="pca_no" value="{{ Auth::user()->personal_corporate==1 ? session('pca_no') : ''  }}" />
        <input type="hidden" name="pca_bal" value="{{ round($pca_adv_bal,2)  }}" />
        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="name">
        </label>
        <label class="col-md-6 col-sm-6 col-xs-6" for="name">
           <input  {{ $pca_adv_bal <=0 ? 'disabled' :'' }} {{Route::currentRouteName()=='waybills.edit' ? ($data->pca_advance_payment==1 && $pca_adv_bal > 0 ? 'checked' : '') : ''}}  type="checkbox" name="pca_use_adv_payment" class="pca_use_adv_payment" /> Use Advance Payment
        </label>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Transaction Type: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="payment_type" class="select2 form-control payment_type" >
                <option value="none" selected disabled>--Please choose one--</option>
                <option value="CI" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CI' ? 'selected' : '') : ''}}>Prepaid</option>
                <option value="CD" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CD' ? 'selected' : '') : ''}}>Collect</option>
                @if($is_charge==true)
                <option value="CH" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CH' ? 'selected' : '') : ''}}>CHARGE</option>
                @endif
            </select>
        </div>
    </div>
    <div class="form-group div_mode_payment" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CI' && $data->pca_advance_payment==0 ? '' : 'hidden') : 'hidden'}}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Mode of Payment: <span class="required">*</span>
        </label>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <select name="mode_payment"  class="select2 form-control mode_payment" >
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->mode_payment==1 ? 'selected' : '') : ''}} value="1" >CASH</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->mode_payment==2 ? 'selected' : '') : ''}} value="2" >GCASH</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->mode_payment==0 ? 'selected' : '') : ''}}  value="0" >OTHERS</option>
            </select>
        </div>
        <div {{Route::currentRouteName()=='waybills.edit' ? ($data->mode_payment==2 ? '' : 'hidden') : 'hidden'}} class="col-md-3 col-sm-3 col-xs-12 div_mode_payment_io">
            <input   {{Route::currentRouteName()=='waybills.edit' ? ( $data->mode_payment_io==1  ? 'checked' : '') : ''}}  type="checkbox"  class="mode_payment_is" name="mode_payment_is" > IN-STORE &emsp;
            <input  {{Route::currentRouteName()=='waybills.edit' ? ( $data->mode_payment_io==2  ? 'checked' : '') : ''}}   type="checkbox" class="mode_payment_os" name="mode_payment_os" > OUT-STORE
        </div>
    </div>
    <div class="form-group div_mode_payment_email" {{Route::currentRouteName()=='waybills.edit' ? ( $data->mode_payment_io==2  ? '' : 'hidden') : 'hidden'}} >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Email for sending notif to pay:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input  name="mode_payment_email" class="form-control col-sm-5 mode_payment_email email-address" value="{{Route::currentRouteName()=='waybills.edit' ? $data->mode_payment_email  : '' }}"   type="text" >

        </div>
    </div>

    <div class="form-group div_mode_payment_msg" {{Route::currentRouteName()=='waybills.edit' ? ( $data->mode_payment_io==2  ? '' : 'hidden') : 'hidden'}} >
        <div class="col-md-3"></div>
        <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
        * A notification to pay will be sent to your email upon successful creation of waybill. Please pay within the cut off time specified in the email notif to avoid auto-cancellation of transaction and payment of cancellation fees. Non-payment can also result to blocking of account.
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="form-group div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Branch Receiver: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12 div_shipping_company" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }}>
            <select  name="pasabox_branch_receiver" class="select2 form-control pasabox_branch_receiver">

            </select>
            <p class="pasabox_branch_receiver_emp"></p>

        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Destination: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="destinationbranch_id" class="select2 form-control">
                <option value="">--Select destination--</option>
                @foreach($branches as $row)
                    <option value="{{$row->branchoffice_no}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->destinationbranch_id==$row->branchoffice_no ? 'selected' :'') : ''}}>{{$row->branchoffice_description}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div {{ Auth::user()->personal_corporate==0 ? '' : 'hidden' }} class="form-group div_discount_coupon">

        <input type="hidden" name="s_dc_fname" />
        <input type="hidden" name="s_dc_mname" />
        <input type="hidden" name="s_dc_lname" />

        <input type="hidden" name="c_dc_fname" />
        <input type="hidden" name="c_dc_mname" />
        <input type="hidden" name="c_dc_lname" />

        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Discount Coupon:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input value="{{Route::currentRouteName()=='waybills.edit' ? $data->discount_coupon : ''}}" type="text" name="discount_coupon" class="form-control discount_coupon">
            <input value="1" name="discount_coupon_action" type="hidden" />
            <input value="0" name="discount_coupon_pickup" id="discount_coupon_pickup" type="hidden" />
            <input value="0" name="discount_coupon_delivery" id="discount_coupon_delivery" type="hidden" />
        </div>

    </div>
    <div class="form-group div-discount-coupon-alert"  hidden >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div style="margin-top:5px;" class="alert alert-danger discount-coupon-alert col-md-6 col-sm-6 col-xs-12">
            <strong id="discount-coupon-alert-msg"><i class="ace-icon fa fa-info-circle"></i> Invalid Discount Coupon</strong>
            <ul></ul>
        </div>
    </div>
    <div class="form-group div-discount-coupon-info" hidden >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div style="margin-top:5px;" class="alert alert-info discount-coupon-alert col-md-6 col-sm-6 col-xs-12">
            <strong id="discount-coupon-info-msg"><i class="ace-icon fa fa-info-circle"></i> Invalid Discount Coupon</strong>
            <ul></ul>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Shipment Type: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipment_type" class="select2 form-control shipment_type">
                <option value="">--Select shipment type--</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->shipment_type=='BREAKABLE' ? 'selected' :'') : ''}}>BREAKABLE</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->shipment_type=='PERISHABLE' ? 'selected' :'') : ''}}>PERISHABLE</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->shipment_type=='LETTER' ? 'selected' :'') : ''}}>LETTER</option>
                <option {{Route::currentRouteName()=='waybills.edit' ? ($data->shipment_type=='OTHERS' ? 'selected' :'') : ''}}>OTHERS</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Declared Value: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="{{Route::currentRouteName()=='waybills.edit' ? ($data->shipment_type!='OTHERS' ? 'hidden' : 'number') : 'hidden'}}" class="form-control" name="declared_value" value="{{Route::currentRouteName()=='waybills.edit' ? $data->declared_value : ''}}" >
            <input type="number" class="form-control declared-value-display" disabled value="{{Route::currentRouteName()=='waybills.edit' ? $data->declared_value : ''}}">
            <div style="margin-top:5px;" class="alert alert-info shipment-type-alert" hidden>
                <button type="button" class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                </button>
                <strong><i class="ace-icon fa fa-info-circle"></i> <font class="shipment-type-name"></font></strong>
                <ul>

                </ul>
                <br />
            </div>
        </div>
    </div>

    <div class="form-group pickup_div"  {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' :  '' ) : '' }} >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        REQUEST PICK UP AT SENDER's ADDRESS :
        <input {{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? 'checked' : '') : ''}} type="checkbox" class="ace" onclick="get_sector_province('pickup')" id="pu_checkbox" name="pu_checkbox">

        <br>
        {{-- <div style="width:250px;text-align:justify;" class="pull-right">
            <small></small>
        </div> --}}
        </label>
        <input type="hidden" class="pu_sched_date" value="">
        <input type="hidden" class="pu_sched_action" value="">
        <input type="hidden" class="pu_booking_count" value="">
        <input type="hidden" class="pu_booking_quota" value="">

        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group div-pu-province">
                @include('sector.province.pu_province')
            </div>
            <div class="form-group div-pu-city">
                @include('sector.city.pu_city')
            </div>
            <div class="form-group div-pu-brgy">
                @include('sector.barangay.pu_brgy')
            </div>
        </div>

    </div>

    <div class="form-group pickup_div" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' :  '' ) : '' }} >

        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Street/Bldg/Room:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input onkeypress="return max_street(event)"  maxlength="100" placeholder="Street/Bldg/Room" type="text" value="{{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? $data->pickup_sector_street : '') : ''}}" name="pu_street" class="form-control pu_street">
        </div>

    </div>
    <div class="form-group pickup_div" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' :  '' ) : '' }} >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">

        </label>
        <div class="col-md-3 xdisplay_inputx form-group has-feedback" >

            <input  type="text" onchange="del_est_sched()" name="pu_date" class="form-control has-feedback-left pu-date-display" value="{{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? date('m/d/Y',strtotime($data->pickup_date)) : '') : ''}}" readonly placeholder="Date"  aria-describedby="inputSuccess2Status"/>
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>
        </div>
        <div class="col-md-6"></div>
    </div>


    <div class="form-group pickup_div" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' :  '' ) : '' }} >
        <div class="col-md-3"></div>
        <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
        * Requested pick up date is subject to the availability of our facilities. Our Customer Service Representative will contact you shortly on your request. Confirmation on pick up schedule will also be sent to your email.
        </div>
        <div class="col-md-3"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="name">
        REQUEST DELIVERY AT RECEIVER's ADDRESS:
        <input   {{Route::currentRouteName()=='waybills.edit' ? ($data->delivery==1 ? 'checked' : '') : ''}} type="checkbox" class="ace"  onclick="get_sector_delivery_province('delivery')" id="del_checkbox" name="del_checkbox" >
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12 div-del-province">
            @include('sector.province.del_province')
        </div>


    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="name">
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12 div-del-city">
            @include('sector.city.del_city')

        </div>


    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="name">
        </label>

        <div class="col-md-6 col-sm-6 col-xs-12 div-del-brgy">
            @include('sector.barangay.del_brgy')
        </div>


    </div>


</form>
