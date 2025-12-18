<form id="form-step-2" class="form-horizontal">
    
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Transaction Type: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="payment_type" class="select2 form-control" >
                <option value="none" selected disabled>--Please choose one--</option>
                <option value="CI" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CI' ? 'selected' : '') : ''}}>Prepaid</option>
                <option value="CD" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CD' ? 'selected' : '') : ''}}>Collect</option>
                @if($is_charge==true)
                <option value="CH" {{Route::currentRouteName()=='waybills.edit' ? ($data->payment_type=='CH' ? 'selected' : '') : ''}}>CHARGE</option>
                @endif
            </select>
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

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Shipment Type: <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipment_type" class="select2 form-control">
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

    
        
    

    

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Request for Pick-up @ shippers address: 
        <input {{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? 'checked' : '') : ''}} type="checkbox" class="ace checkbox" onclick="get_sector_province('pickup')" id="pu_checkbox" name="pu_checkbox" >
        <br>
        <div style="width:250px;text-align:justify;" class="pull-right">
            <small>*NOTE:  Check if you want us to pick up your shipment at Shippers Address, we will collect additional fees for Pick Up.</small>
        </div>
        </label>
        <input type="hidden" class="pu_sched_date" value="">
        <input type="hidden" class="pu_sched_action" value="">
        <input type="hidden" class="pu_booking_count" value="">
        <input type="hidden" class="pu_booking_quota" value="">
        <!--input type="hidden" class="count_pu_ddate" value="0"-->
  
        <div class="col-md-6 col-sm-6 col-xs-12 div-pu-province">
            <div class="form-group">
                @include('sector.province.pu_province')
            </div>
            <div class="form-group">
                @include('sector.city.pu_city')
            </div>
            <div class="form-group">
                @include('sector.barangay.pu_brgy')
            </div>
        </div>

    </div>
    
    <div class="form-group">
        
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Street/Bldg/Room:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input placeholder="Street/Bldg/Room" type="text" value="{{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? $data->pickup_sector_street : '') : ''}}" name="pu_street" class="form-control pu_street">
        </div>
        
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        
        </label>
        <div class="col-md-3 xdisplay_inputx form-group has-feedback" > 
            <input  type="text" onchange="del_est_sched()" name="pu_date" class="form-control has-feedback-left pu-date-display" value="{{Route::currentRouteName()=='waybills.edit' ? ($data->pickup==1 ? date('m/d/Y',strtotime($data->pickup_date)) : '') : ''}}" readonly placeholder="Date"  aria-describedby="inputSuccess2Status"/>
            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
            <span id="inputSuccess2Status" class="sr-only">(success)</span>
        </div>
        <div class="col-md-6"></div>
    </div>
    

    <div class="form-group">
        <div class="col-md-3"></div>
        <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
        * Requested pick up date is subject to the availability of our facilities. Our Customer Service Representative will contact you shortly on your request. Confirmation on pick up schedule will also be sent to your email.
        </div>
        <div class="col-md-3"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="name">
        Delivery: 
        <input   {{Route::currentRouteName()=='waybills.edit' ? ($data->delivery==1 ? 'checked' : '') : ''}} type="checkbox" class="ace"  onclick="get_sector_province('delivery')" id="del_checkbox" name="del_checkbox" >
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
