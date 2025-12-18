<form id="form-step-4"  class="form-horizontal form-label-left">

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Transaction Type:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="payment_type" class="form-control payment_type" >
                <option value="none" selected disabled>--Please choose one--</option>
                <option value="CI">Prepaid</option>
                <option value="CD">Collect</option>
            </select>
        </div>
    </div>

    <div class="form-group div_mode_payment" hidden>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Mode of Payment: <span class="required">*</span>
        </label>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <select name="mode_payment"  class="form-control mode_payment" >
                <option  value="1" >CASH</option>
                <option  value="2" >GCASH</option>
                <option  value="0" >OTHERS</option>
            </select>
        </div>
        <div  class="col-md-3 col-sm-3 col-xs-12 div_mode_payment_io">
            <input   type="checkbox"  class="mode_payment_is" name="mode_payment_is" > IN-STORE &emsp;
            <input   type="checkbox" class="mode_payment_os" name="mode_payment_os" > OUT-STORE 
        </div>
    </div>
    <div class="form-group div_mode_payment_email" hidden >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Email for sending notif to pay:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input  name="mode_payment_email" class="form-control col-sm-5 mode_payment_email email-address"   type="text" >

        </div>
    </div>

    <div class="form-group div_mode_payment_msg" hidden >
        <div class="col-md-3"></div>
        <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
        * A notification to pay will be sent to your email upon successful creation of waybill. Please pay within the cut off time specified in the email notif to avoid auto-cancellation of transaction and payment of cancellation fees. Non-payment can also result to blocking of account.
        </div>
        <div class="col-md-3"></div>
    </div>



    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Destination:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="destinationbranch_id" class="form-control">
                <option value="">--Select destination--</option>
                @foreach($branches as $row)
                    <option value="{{$row->branchoffice_no}}">{{$row->branchoffice_description}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group div_discount_coupon">

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
            <input  type="text" name="discount_coupon" class="form-control discount_coupon">
            <input value="1" name="discount_coupon_action" type="hidden" />
            <input value="0" name="discount_coupon_pickup" id="discount_coupon_pickup" type="hidden" />
            <input value="0" name="discount_coupon_delivery" id="discount_coupon_delivery" type="hidden" />
        </div>
        
    </div>
    <div class="form-group div-discount-coupon-alert" hidden >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div style="margin-top:5px;" class="alert alert-danger discount-coupon-alert col-md-6 col-sm-6 col-xs-12">
            <strong id="discount-coupon-alert-msg"><i class="ace-icon fa fa-info-circle"></i> Invalid Discount Coupon</strong>
            <ul></ul>
        </div>   
    </div>   
    <div class="form-group div-discount-coupon-info" hidden  >
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
        <div style="margin-top:5px;" class="alert alert-info discount-coupon-alert col-md-6 col-sm-6 col-xs-12">
            <strong id="discount-coupon-info-msg"><i class="ace-icon fa fa-info-circle"></i> Invalid Discount Coupon</strong>
            <ul></ul>
        </div>   
    </div>            
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Shipment Type:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="shipment_type" class="form-control">
                <option value="">--Select shipment type--</option>
                <option>BREAKABLE</option>
                <option>PERISHABLE</option>
                <option>LETTER</option>
                <option>OTHERS</option>
            </select>
        </div>
    </div>

    

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Declared Value:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" class="form-control" name="declared_value">
            <input type="number" class="form-control declared-value-display" disabled value="">
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
        Pick-up: 
        <input  type="checkbox" class="ace" onclick="get_sector_province('pickup')" id="pu_checkbox" name="pu_checkbox" >
        </label>
        <input type="hidden" class="pu_sched_date" value="">
        <input type="hidden" class="pu_sched_action" value="">
        <input type="hidden" class="pu_booking_count" value="">
        <input type="hidden" class="pu_booking_quota" value="">
        <!--input type="hidden" class="count_pu_ddate" value="0"-->
  
        <div class="col-md-6 col-sm-6 col-xs-12 div-pu-province">
            @include('sector.province.pu_province')
  
        </div>

    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12 div-pu-city">
            @include('sector.city.pu_city')
            
        </div>

    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        </label>
        
        <div class="col-md-6 col-sm-6 col-xs-12 div-pu-brgy">
            @include('sector.barangay.pu_brgy')
            
        </div>
        
        
    </div>
    <div class="form-group">
        
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" placeholder="Street/Bldg/Room" name="pu_street" class="form-control pu_street">
        </div>
        
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        
        </label>
        <div class="col-sm-3 col-xs-12">
            <div class="col-md-12 xdisplay_inputx form-group has-feedback" >
                                    
                <input  type="text" name="pu_date" class="form-control has-feedback-left pu-date-display" value="<?php //echo date('m/d/Y'); ?>" readonly placeholder="Pick-up Date"  aria-describedby="inputSuccess2Status"/>
                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                <span id="inputSuccess2Status" class="sr-only">(success)</span>
            </div>
        </div>
        <p class="col-sm-6 col-xs-12">
       
        </p>
    </div>
    <div class="form-group">
        <div class="col-md-3"></div>
        <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
        *NOTE: Requested pick up date is subject to the availability of our facilities. Our Customer Service Representative will contact you shortly on your request. Confirmation on pick up schedule will also be sent to your email.</font>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12 " for="name">
        Delivery: 
        <input  type="checkbox" class="ace"  onclick="get_sector_delivery_province('delivery')" id="del_checkbox" name="del_checkbox" >
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
   
    <br>
    <div class="row"><div class="pull-right"><button type="button" class="btn btn-success add-item"><i class="menu-icon fa fa-plus"></i> Add Item</button></div></div>
    <br>
    <div class="row">
        <table id="datatable" width="100%" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="40%">Description</th>
                    <th width="30%">Unit</th>
                    <th width="20%">Quantity</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</form>

<div id="modal-item" class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel2">Please select item description</h4>
        </div>
        <div class="modal-body">
            <form id="form-search-item">
                <div class="col-12">
                    <div class="input-group">
                        <input type="hidden" id="parentTableIndex">
                        <input class="form-control" type="text" name="item_description" placeholder="Enter item description" />
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-success verify" type="button">
                                <i class="ace-icon fa fa-search bigger-110"></i>
                                Search
                            </button>
                        </span>
                    </div>
                </div>
            </form>
            <div style="height:400px;overflow:scroll;">
                <table id="table-items" width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Item description</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>

