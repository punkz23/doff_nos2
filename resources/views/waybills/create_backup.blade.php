@extends('layouts.theme')

@section('css')
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
@endsection

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>

        <li>
            <a href="{{route('waybills.index')}}">Bookings</a>
        </li>
        <li class="active">New transaction</li>
    </ul><!-- /.breadcrumb -->

</div>
@endsection

@section('content')

    <form class="form-horizontal visible" id="form-booking">

    <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Shipper-Consignee</h4>

                        <div class="widget-toolbar">
                            
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <h1>Shipper</h1>
                                    <div>
                                        <label for="form-field-8">Name</label>
                                        <select name="shipper_id" class="form-control contacts select2" id="shipper_id" required>
                                            <option value="none" disabled selected>--Select Shipper--</option>
                                            <option value="new">--New Shipper--</option>
                                            @foreach($contacts as $row)
                                                <option value="{{$row->contact_id}}">{{$row->fileas}}</option>
                                            @endforeach
                                            
                                        </select>
                                    </div>

                                   

                                    <div>
                                        <label for="form-field-9">Address</label>
                                        <select name="shipper_address_id" class="form-control select2" id="shipper_address_id" required>

                                        </select>
                                    </div>

                                    

                                    <div>
                                        <label for="form-field-11">Contact #</label>
                                        <input type="text" name="shipper_contact_no" class="form-control" id="shipper_contact_no" readonly>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <h1>Consignee</h1>
                                    <div>
                                        <label for="form-field-8">Name</label>
                                        <select name="consignee_id" class="form-control contacts select2" id="consignee_id" required>
                                            <option value="none" selected disabled>--Select Consignee--</option>
                                            <option value="new">--New Consignee--</option>
                                            @foreach($contacts as $row)
                                                <option value="{{$row->contact_id}}">{{$row->fileas}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    

                                    <div>
                                        <label for="form-field-9">Address</label>
                                        <select name="consignee_address_id" class="form-control select2" id="consignee_address_id" required>

                                        </select>
                                    </div>

                                    

                                    <div>
                                        <label for="form-field-11">Contact #</label>
                                        <input type="text" name="consignee_contact_no" class="form-control" id="consignee_contact_no" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.span -->

            
                                        
        </div><!-- /.row -->

        <div class="hr hr-5"></div>

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Transaction Type:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <select name="payment_type" class="chosen-select form-control" >
                                <option value="none" selected disabled>--Please choose one--</option>
                                <option value="CI">Prepaid</option>
                                <option value="CD">Collect</option>
                                @if($is_charge==true)
                                <option value="CH">CHARGE</option>
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Destination:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <select name="destinationbranch_id" class="chosen-select form-control">
                                <option value="">--Select destination--</option>
                                {!! $ddBranches !!}
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="space-2"></div>

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Shipment Type:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <select name="shipment_type" class="chosen-select form-control">
                                <option value="">--Select shipment type--</option>
                                <option>BREAKABLE</option>
                                <option>PERISHABLE</option>
                                <option>LETTER</option>
                                <option>OTHERS</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Declared Amount:</label>

                    <div class="col-xs-12 col-sm-9">
                        <div class="clearfix">
                            <input type="number" class="form-control" name="declared_amount" value="0" disabled>
                            <input type="hidden" name="declared_value">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
        <div class="col-12">
            <label for="form-field-mask-1">
                <h3>Discount Coupon</h3>
                
            </label>

            <div class="input-group">
                <input class="form-control" type="text" name="discount_coupon" placeholder="Discount Coupon (Optional)" id="discount_coupon" />
                <span class="input-group-btn">
                    <button class="btn btn-sm btn-success verify" type="button">
                        <i class="ace-icon fa fa-search bigger-110"></i>
                        Go!
                    </button>
                </span>
            </div>
        </div>
        

        <div class="hr hr-24"></div>

        

        <h1>Shipments</h1>

        <div class="space-2"></div>

        <div class="clearfix">
            <div class="pull-right tableTools-container"></div>
        </div>

        <div class="row">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" />
                                <span class="lbl"></span>
                            </label>
                        </th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><center><label class="pos-rel"><input type="checkbox" class="ace" value="'0'"/><span class="lbl"></span></label></center></td>
                        <td><select class="chosen-select form-control description" name="description[0]" style="width:100%;" required><option value="">--Select description--</option></select></td>
                        <td><select class="chosen-select form-control unit" name="unit[0]"><option value="" required><option value="">--Select unit--</option></select></td>
                        <td><input type="number" name="quantity[0]" class="form-control quantity" required></td>
                        <td></td>
                    </tr>
                </tbody>

                <tfoot>
					<th class="center">
                                
                    </th>
                    <th id="description_ep"></th>
                    <th id="unit_ep"></th>
                    
                    <th id="quantity_ep"></th>
                    <th></th>
				</tfoot>
            </table>
        </div>


        <div class="space-10"></div>

        <div class="form-group">
			<div class="col-xs-12 col-sm-4">
                <label>
                    <input name="agree" id="agree" type="checkbox" class="ace" />
                    <span class="lbl"> I accept the <a href="#" data-toggle="modal" data-target="#modal-confirm">terms</a></span>
                </label>
			</div>
	    </div>
        

        <div class="col-xs-12">
            <div class="clearfix pull-right">
                <button type="button" class="btn btn-success open-note">Create</button>
            </div>
        </div>          
    </form>

    <div class="modal fade" id="modal-confirm" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="ace-icon fa fa-file-text bigger-130"></i> Terms and Condition in Online Booking</h4>
                </div>
                <div class="modal-body">
                    @if($term!=null)
                    {!! $term->content !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-note" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="ace-icon fa fa-file-text bigger-130"></i> Note</h4>
                </div>
                <div class="modal-body">
                    <p style="text-align:justify">
                    Before you enter the barangay where our Manila Branch is located, the barangay officials will facilitate two (2) queuing lines for our transactions, one of which is for those who’ve booked online. For accommodation, kindly present the online booking to our Manila Branch employee giving out the queuing numbers.</p> 
                    <p style="text-align:justify">
                    There will be separate lane to those who’ve booked online. However, it must be taken into account that our online booking service is not used for appointments, reservations or whatever of the sort as our transactions will be on a “first-come, first-serve” basis. Online booking is only valid for seven (7) days. We advised our customers who have opted to avail Lalamove or any other transport service providers to have their designated couriers comply with the queuing process from start to finish. Remind them not to leave your shipments behind if transaction is still on queue.
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary proceed submit">Proceed</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-error" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="ace-icon fa fa-exclamation-triangle bigger-130"></i> Please check</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-form" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="ace-icon fa fa-users bigger-130"></i> NEW SHIPPER/CONSIGNEE</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form class="form-horizontal visible" id="form-contact">
                            @csrf
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-3 no-padding-right">

                                    </div>
                                    <div class="col-xs-12 col-sm-9">
                                        <div class="checkbox">
                                            <label>
                                                <input name="use_company" type="checkbox" class="ace" />
                                                <span class="lbl"> Use Company</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 name">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Lastname: <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="lname" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-2 name"></div>

                            <div class="col-xs-12 name">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Firstname:  <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="fname" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2 name"></div>

                            <div class="col-xs-12 name">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Middlename:</label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="mname" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-2 name"></div>

                            <div class="col-xs-12 company" style="display:none;">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Company:  <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="company" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            

                            <div class="space-2 company" style="display:none;"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Email:</label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Contact No:  <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="contact_no" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2"></div>

                            

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Business Type:</label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <select name="business_category_id" class="select2">
                                                <option value="none" selected disabled>--Select business type--</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <!-- <div class="space-2"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Address Label:</label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="address_label" class="form-control" placeholder="HOME/WORK">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div> -->

                            <div class="space-2"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Street/Bldg/Room:</label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="street" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Barangay:  <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="text" name="barangay" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2"></div>
                            
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">City/Municipality: <font color="red">*</font></label>
                                    
                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <select name="city" class="select2 cities" id="city">
                                                <option value="none" disabled selected>--Select City--</option>
                                                @foreach($provinces as $province)
                                                    <optgroup label="{{$province->name}}">
                                                        @foreach($province->city as $city)
                                                            <option data-province="{{$province->province_name}}" data-postal_code="{{$city->postal_code}}" value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="space-2"></div>

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Province: <font color="red">*</font></label>
                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="hidden" name="province">

                                            <label id="province"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            

                            <div class="space-2"></div>

                            

                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Postal Code: <font color="red">*</font></label>

                                    <div class="col-xs-12 col-sm-9">
                                        <div class="clearfix">
                                            <input type="hidden" name="postal_code">
                                            <label id="postal_code"></label>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="clearfix pull-right">
                                    <button id="save-close" type="button" class="btn btn-success submit">Save Close</button>
                                    <button id="save-new" type="button" class="btn btn-primary submit">Save New</button>
                                </div>
                            </div>                                  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-address" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="ace-icon fa fa-map-pin bigger-130"></i> ADDRESS</h4>
                </div>
                <div class="modal-body">
                    <form id="form-address" class="form-horizontal">
                        <div class="message-container" style="display:none;">
                            <div class="alert">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>

                                <strong class="title">
                                    <i class="ace-icon fa fa-times"></i>
                                        
                                </strong>

                                <font class="message"></font>
                                <br />
                            </div>
                        </div>
                        <input type="hidden" name="contact_id">
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Address Label : </label>

                            <div class="col-sm-9">
                                <input type="text" id="address_caption" name="address_caption"  class="form-control"/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Street/Bldg/Room : </label>

                            <div class="col-sm-9">
                                <input type="text" id="address_caption" name="street"  class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Barangay : <font color="red">*</font></label>

                            <div class="col-sm-9">
                                <input type="text" id="address_caption" name="barangay"  class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">City : <font color="red">*</font></label>

                            <div class="col-sm-9">
                                <select name="city" class="form-control select2 cities">
                                    <option selected value="none" disabled>--Select City--</option>
                                    @foreach($provinces as $province)
                                        <optgroup label="{{$province->name}}">
                                            @foreach($province->city as $city)
                                            <option data-province="{{$province->province_name}}" data-postal_code="{{$city->postal_code}}" value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Province  <font color="red">*</font></label>

                            <div class="col-sm-9">
                                <input type="hidden" name="province">
                                <label id="province"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Postal Code  <font color="red">*</font></label>

                            <div class="col-sm-9">
                                <input type="hidden" name="postal_code">
                                <label id="postal_code"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="clearfix pull-right">
                                    <button type="submit" class="btn btn-success submit">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

    

@endsection

@section('plugins')

<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.maskedinput.min.js')}}"></script>

<script src="{{asset('/theme/js/chosen.jquery.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>

<script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script>

@endsection

@section('scripts')
<script type="text/javascript">

    $('select.description').append("{!! $ddStocks !!}");
    $('select.unit').append("{!! $ddUnits !!}");
    $('select[name="business_category_id"]').append(localStorage.getItem('dropdown_businesstypes'));

    $('input[name="use_company"').change(function(){
        if($(this).is(':checked')==true){
            $('.name').attr('style','display:none');
            $('input[name="lname"]').removeAttr('required');
            $('input[name="fname"]').removeAttr('required');
            $('.company').removeAttr('style');
            $('input[name="company"]').attr('required',true);
        }else{
            $('.name').removeAttr('style');
            $('input[name="lname"]').attr('required',true);
            $('input[name="fname"]').attr('required',true);
            $('.company').attr('style','display:none');
            $('input[name="company"]').removeAttr('required');
        }
    });

    $('.cities').change(function(){
        var form_id = $(this).closest('form').attr('id');
        var province_for = 'province';
        var postal_for = 'postal_code';
        $('#'+form_id+' input[name="'+province_for+'"]').val($(this).find('option:selected').data('province'));
        $('#'+form_id+' label#'+province_for).html($(this).find('option:selected').data('province'));
        $('#'+form_id+' input[name="'+postal_for+'"]').val($(this).find('option:selected').data('postal_code'));
        $('#'+form_id+' label#'+postal_for).html($(this).find('option:selected').data('postal_code'));
	});

    
    jQuery(function($) {

        $('.open-note').click(function(){
            // $('#form-booking').validate();
            if($('#form-booking').valid()){
                $('#modal-note').modal('show');
            }else{
                
                var messages = '';
                
                if($('select[name="shipper_id"]').val()===null || $('select[name="shipper_id"]').val()==="new"){
                    messages=messages+'<p><font color="red">*</font> Please select shipper</p>';
                }
                if($('select[name="shipper_address_id"]').val()===null || $('select[name="shipper_address_id"]').val()==="new"){
                    messages=messages+'<p><font color="red">*</font> Please select shipper address</p>';
                }
                if($('select[name="consignee_id"]').val()===null || $('select[name="consignee_id"]').val()==="new"){
                    messages=messages+'<p><font color="red">*</font> Please select consignee</p>';
                }
                if($('select[name="consignee_address_id"]').val()===null || $('select[name="consignee_address_id"]').val()==="new"){
                    messages=messages+'<p><font color="red">*</font> Please select consignee address</p>';
                }

                if($('select[name="payment_type"]').val()===null || $('select[name="payment_type"]').val()==="none"){
                    messages=messages+'<p><font color="red">*</font> Please select transaction type</p>';
                }
                
                if($('select[name="destinationbranch_id"]').val()===null || $('select[name="destinationbranch_id"]').val()===""){
                    messages=messages+'<p><font color="red">*</font> Please select destination</p>';
                }

                if($('select[name="shipment_type"]').val()===null || $('select[name="shipment_type"]').val()===""){
                    messages=messages+'<p><font color="red">*</font> Please select shipment type</p>';
                }

                if($('input[name="declared_value"]').val()==0){
                    messages=messages+'<p><font color="red">*</font> Declared value must have a value</p>';
                }

                if($('#dynamic-table > tbody > tr:last td select.description').val()==="" && $('#dynamic-table > tbody > tr:last td select.unit').val()==="" && $('#dynamic-table > tbody > tr:last td input.quantity').val()===""){
                    messages=messages+'<p><font color="red">*</font> Please complete the last row for shipments</p>';
                }

                if(!$('input[name="agree"]').is(':checked')){
                    messages=messages+'<p><font color="red">*</font> Please accept terms and condition</p>';
                }
                
                
                $('#modal-error .modal-body').html(messages);
                $('#modal-error').modal('show');
            }
        })

        $('#modal-note .submit').click(function(){
            $('#form-booking').submit();
        });

        $('.select2').css('width','100%').select2({allowClear:true});

        // $('select[name="city"]').change(function(e){
        //     var province_for = 'province';
        //     var postal_for = 'postal_code';
            
        //     $.ajax({
        //     url : "{{url('/get-city-data')}}/"+$(this).val(),
        //     type: "GET",
        //     dataType: "JSON",
        //     success: function(result){
        //         var obj = result;
        //         $('input[name="'+province_for+'"]').val(obj['province']['province_name']);
        //         $('label#'+province_for).html(obj['province']['province_name']);
        //         $('input[name="'+postal_for+'"]').val(obj['postal_code']);
        //         $('label#'+postal_for).html(obj['postal_code']);
        //     }
        //     })
        //     e.preventDefault();
        // })

        var counter=0;
        var myTable = $('#dynamic-table').DataTable( {
            bAutoWidth: false,
            "aaSorting": [],
            "bFilter" : false,
            "paging" : false,
            "bInfo" : false
        } );

        $('#dynamic-table > tbody').on('click','tr',function(){
            $tr = $(this).closest('tr');
            if($tr[0]['_DT_RowIndex']>0){
                if($tr.hasClass('selected')){
                    $tr.removeClass('selected');
                }else{
                    $tr.addClass('selected');
                }
            }
        })

        $('.chosen-select').chosen();
        
        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
                
            new $.fn.dataTable.Buttons( myTable, {
                buttons: [
                    {
                        "text": "<i class='fa fa-plus bigger-110 blue'></i> <span class='hidden'>Add Item</span>",
                        "className": "btn btn-white btn-primary btn-bold add-item",
                    },
                    {
                        "text": "<i class='fa fa-trash bigger-110 red'></i> <span class='hidden'>Remove Item</span>",
                        "className": "btn btn-white btn-primary btn-bold remove-item",
                    }       
                ]
        } );
        myTable.buttons().container().appendTo( $('.tableTools-container') );

        $('.add-item').click(function(){

            if($('#dynamic-table > tbody > tr:last td select.description').val()!=="" && $('#dynamic-table > tbody > tr:last td select.unit').val()!=="" && $('#dynamic-table > tbody > tr:last td input.quantity').val()!==""){
                if(counter<5){
                    counter=counter+1;
                    myTable.row.add([
                    '<center><label class="pos-rel"><input type="checkbox" class="ace" value="'+counter+'"/><span class="lbl"></span></label></center>',
                    '<select class="chosen-select form-control description" name="description['+counter+']" required style="width:100%"><option value="">--Select description--</option>'+"{!! $ddStocks !!}"+'</select>',
                    '<select class="chosen-select form-control unit" name="unit['+counter+']" style="width:100%"><option value="" required><option value="">--Select unit--</option>'+"{!! $ddUnits !!}"+'</select>',
                    '<input type="number" name="quantity['+counter+']" class="form-control quantity" required  style="width:100%">',
                    '<div class="hidden-sm hidden-xs action-buttons"><a class="red remove" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>'
                    ]).draw(false);
                    $('.chosen-select').chosen();
                    // alert($('#dynamic-table > tbody > tr:last td input[type=checkbox]').val());
                }
            }
            
        });

        $('.remove-item').click(function(){

            if(myTable.rows('.selected').count()==0){
                swal('No selected item(s)', {
                    icon: 'error',
                    title: 'Ooops!'
                })
            }else{
                var data = myTable.rows('.selected').data();
                swal({
                  title: "Are you sure?",
                  text: "Remove this item(s)!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                    myTable.rows('.selected').remove().draw();
                  }
                });
            }
        })

        $('.verify').click(function(){
            $('.verify').attr('disabled',true);
            $('.verify').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
            $.ajax({
                url : "{{url('/waybills/check-discount-coupon')}}/"+$('input[name="discount_coupon"]').val(),
                type : "GET",
                dataType : "JSON",
                success : function(result){
                    swal(result.message, {
                        icon: result.type,
                        title: result.title
                    });
                    $('.verify').removeAttr('disabled');
                    $('.verify').html('<i class="ace-icon fa fa-search bigger-110"></i>Go!');
                }
            });
        });

        myTable.on('click', '.remove', function(e) {
            $tr = $(this).closest('tr');
            counter=counter-1;
            myTable.row($tr).remove().draw();
            e.preventDefault();
        });

        $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
            var th_checked = this.checked;//checkbox inside "TH" table header
                    
            $('#dynamic-table').find('tbody > tr').each(function(){
                var row = this;
                if(th_checked) myTable.row(row).select();
                else  myTable.row(row).deselect();
            });
        });

        $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
            var row = $(this).closest('tr').get(0);
            if(this.checked) myTable.row(row).select();
            else myTable.row(row).deselect();
        });

        $('select[name="shipment_type"]').change(function(){
            var val = $(this).val();
            if(val==="OTHERS"){
                $('input[name="declared_amount"]').removeAttr('disabled');
                $('input[name="declared_amount"]').attr('value',2000);
                $('input[name="declared_value"]').val(2000);
            }else{
                $('input[name="declared_amount"]').attr('disabled',true);
                if(val==="BREAKABLE" || val==="PERISHABLE"){
                    $('input[name="declared_amount"]').attr('value',1000);
                    $('input[name="declared_value"]').val(1000);
                }else{
                    $('input[name="declared_amount"]').attr('value',500);
                    $('input[name="declared_value"]').val(500);
                }
            }
        });

        $('input[name="declared_amount"]').change(function(){
            $('input[name="declared_value"]').val($(this).val());
        });

        $('select[name="shipper_id"]').change(function(){

            if($(this).val()=="new"){
                $('#modal-form').modal('show');
            }else{
                $.ajax({
                    url : "{{url('/contacts')}}/"+$(this).val(),
                    type: "GET",
                    dataType: "JSON",
                    success: function(result){
                        var obj = result;

                        var shipper_innerHtml = '';
                        shipper_innerHtml = shipper_innerHtml + "<option value='none' selected disabled>--PLEASE SELECT ADDRESS--</option>";
                        shipper_innerHtml = shipper_innerHtml + "<option value='new'>--ADD NEW--</option>";
                        var addresses = obj['user_address'];
                        for(var i=0; i<addresses.length;i++){
                            shipper_innerHtml = shipper_innerHtml + "<option value='"+addresses[i]['useraddress_no']+"'>"+addresses[i]['full_address']+"</option>";
                        }
                        $('select[name="shipper_address_id"]').html(shipper_innerHtml);
                        $('input[name="shipper_contact_no"]').val(obj['contact_no']);


                    }
                })
            }
            
        });

        $('select[name="shipper_address_id"],select[name="consignee_address_id"]').change(function(){
            if($(this).val()=="new"){
                var contact_id = $(this).attr('name')=='shipper_address_id' ? 'shipper_id' : 'consignee_id';
                $('#form-address input[name="contact_id"]').val($('select[name="'+contact_id+'"]').val());
                $('#modal-address').modal('show');
            }
        });

        $('select[name="consignee_id"]').change(function(){
            if($(this).val()=="new"){
                $('#modal-form').modal('show');
            }else{
                $.ajax({
                    url : "{{url('/contacts')}}/"+$(this).val(),
                    type: "GET",
                    dataType: "JSON",
                    success: function(result){
                        var obj = result;
                        var shipper_innerHtml = '';
                        shipper_innerHtml = shipper_innerHtml + "<option value='none' selected disabled>--PLEASE SELECT ADDRESS--</option>";
                        shipper_innerHtml = shipper_innerHtml + "<option value='new'>--ADD NEW--</option>";
                        var addresses = obj['user_address'];
                        for(var i=0; i<addresses.length;i++){
                            shipper_innerHtml = shipper_innerHtml + "<option value='"+addresses[i]['useraddress_no']+"'>"+addresses[i]['full_address']+"</option>";
                        }
                        $('select[name="consignee_address_id"]').html(shipper_innerHtml);
                        $('input[name="consignee_contact_no"]').val(obj['contact_no']);

                    }
                })
            }
            
        });        

        $('[data-rel=tooltip]').tooltip();
        
            
                var $validation = false;
                $('#fuelux-wizard-container')
                .ace_wizard({
                    //step: 2 //optional argument. wizard will jump to step "2" at first
                    //buttons: '.wizard-actions:eq(0)'
                })
                .on('actionclicked.fu.wizard' , function(e, info){
                    if(info.step == 1 && $validation) {
                        if(!$('#validation-form').valid()) e.preventDefault();
                    }
                })
                //.on('changed.fu.wizard', function() {
                //})
                .on('finished.fu.wizard', function(e) {
                    bootbox.dialog({
                        message: "Thank you! Your information was successfully saved!", 
                        buttons: {
                            "success" : {
                                "label" : "OK",
                                "className" : "btn-sm btn-primary"
                            }
                        }
                    });
                }).on('stepclick.fu.wizard', function(e){
                    //e.preventDefault();//this will prevent clicking and selecting steps
                });
            
            
                //jump to a step
                /**
                var wizard = $('#fuelux-wizard-container').data('fu.wizard')
                wizard.currentStep = 3;
                wizard.setState();
                */
            
                //determine selected step
                //wizard.selectedItem().step
            
            
            
                //hide or show the other form which requires validation
                //this is for demo only, you usullay want just one form in your application
                $('#skip-validation').removeAttr('checked').on('click', function(){
                    $validation = this.checked;
                    if(this.checked) {
                        $('#sample-form').hide();
                        $('#validation-form').removeClass('hide');
                    }
                    else {
                        $('#validation-form').addClass('hide');
                        $('#sample-form').show();
                    }
                })
            
            
            
                //documentation : http://docs.jquery.com/Plugins/Validation/validate
                $('.modal-header .close').click(function(){
                    $('.message-container').attr('style','display:none');
                });
            
                $.mask.definitions['~']='[+-]';
                $('#phone').mask('(999) 999-9999');
            
                jQuery.validator.addMethod("phone", function (value, element) {
                    return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
                }, "Enter a valid phone number.");
                
                jQuery.validator.addMethod("notEqual", function(value, element, param) {
                return this.optional(element) || value != param;
                }, "Please specify a different (non-default) value");

                $('#form-booking').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        payment_type: {
                            required: true
                        },
                        destinationbranch_id: {
                            required: true
                        },
                        shipment_type: {
                            required: true
                        },
                        declared_value: {
                            required: true,
                            number : true,
                        },
                        shipper_id: {
                            required: true,
                            notEqual : "new"
                        },
                        consignee_id: {
                            required: true,
                            notEqual : "new"
                        },
                        shipper_address_id: {
                            required: true,
                            notEqual : "new"
                        },
                        consignee_address_id: {
                            required: true,
                            notEqual : "new"
                        },
                        agree: {
                            required: true
                        }

                    },
            
                    messages: {
                        payment_type: "Payment type is required",
                        destinationbranch_id: "Destination is required",
                        shipment_type: "Shipment type is required",
                        declared_value : {
                            required: "Declared value is required",
                            number : "Declared value must be number"
                        },
                        shipper_id : "Shipper is required",
                        consignee_id : "Consignee is required",
                        shipper_address_id : "Shipper Address is required",
                        consignee_address_id : "Consignee Address is required",
                        agree : "Please accept our terms"
                    },
            
            
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
            
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    },
            
                    errorPlacement: function (error, element) {
                        if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                            var controls = element.closest('div[class*="col-"]');
                            if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                            else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        }
                        else if(element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        }
                        else if(element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        }
                        else if (element.attr("class") == "description") {
						error.insertAfter("#description_ep");
                        }
                        else if (element.attr("class") == "unit") {
                            error.insertAfter("#unit_ep");
                        }
                        else if (element.attr("class") == "quantity") {
                            error.insertAfter("#quantity_ep");
                        }
                        else error.insertAfter(element.parent());
                    },
            
                    submitHandler: function (form) {
                        var shipments = [];
                        for(var i=0; i<myTable.rows().data().length; i++){
                            shipments.push({
                                description : myTable.cell(i,1).nodes().to$().find('select').val(),
                                preset : null,
                                unit : myTable.cell(i,2).nodes().to$().find('select').val(),
                                quantity : myTable.cell(i,3).nodes().to$().find('input').val()
                            });
                        }
                        var form_data = new FormData(form);
                        
                        form_data.append('_token',"{{csrf_token()}}");
                        form_data.append('shipments',JSON.stringify(shipments));
                        form_data.append('edit',0);
                        $('#modal-note .submit').attr('disabled',true);
                        $('#modal-note .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                        $.ajax({
                            url: "{{route('waybills.store')}}",
                            type: "POST",
                            data: form_data,
                            dataType: 'JSON',
                            processData: false,
                            contentType:false,
                            cache:false,
                            success: function(result){
                                
                                swal(result.message, {
                                  icon: result.type,
                                  title: result.title
                                }).then(function(){
                                    if(result.type=="success"){
                                        $('#form-booking').trigger('reset');
                                        $('select[name="shipper_address_id"]').html('');
                                        $('select[name="consignee_address_id"]').html('');
                                        $('#modal-note .submit').html('Proceed');
                                        $('#modal-note .submit').removeAttr('disabled');  
                                        myTable.rows().remove().draw();
                                        myTable.row.add([
                                        '<center><label class="pos-rel"><input type="checkbox" class="ace" value="'+counter+'"/><span class="lbl"></span></label></center>',
                                        '<select class="form-control description" name="description[0]" required><option value="">--Select description--</option>'+"{!! $ddStocks !!}"+'</select>',
                                        '<select class="form-control unit" name="unit[0]"><option value="" required><option value="">--Select unit--</option>'+"{!! $ddUnits !!}"+'</select>',
                                        '<input type="number" name="quantity[0]" class="form-control quantity" required>',
                                        '<div class="hidden-sm hidden-xs action-buttons"><a class="red remove" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>'
                                        ]).draw(false);
                                        window.location.href="{{url('/waybills/')}}/"+result.data['reference_no'];
                                    }
                                });
                            },
                            error: function(xhr,status){
                                swal('Server connection error!', {
                                  icon: 'error',
                                  title: 'Ooops!'
                                }).then(function(){
                                    $('#modal-note .submit').html('Proceed');
                                    $('#modal-note .submit').removeAttr('disabled');  
                                });
                            }
                            
                        });
                        return false;
                    },
                    invalidHandler: function (form) {
                    }
                });

                $('select[name="province"]').change(function(){
                    $.ajax({
                        url : "{{url('/get-cities')}}/"+$(this).val(),
                        type: "GET",
                        dataType: "JSON",
                        success: function(result){
                            var obj = result;
                            var cities_innerHtml = '';
                            // console.log(obj[0]['cities_name']);
                            
                            for(var i=0; i<obj.length;i++){
                                // console.log(i);
                                cities_innerHtml = cities_innerHtml + "<option value='"+obj[i]['cities_id']+"'>"+obj[i]['cities_name']+"</option>";
                            }
                            // console.log(result);
                            // console.log(cities_innerHtml);
                            $('select[name="city"]').html(cities_innerHtml);
                            

                        }
                    })
                });

                
                
                var form_contact = $('#form-contact').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        
                        email: {
                            email:true
                        },
                        
                        contact_no: {
                            required: true
                        },
                        province: {
                            required: true
                        },
                        city: {
                            required: true
                        },
                        barangay: {
                            required: true,
                        },
                        
                        postal_code: {
                            required: true,
                        }
                    },
            
                    messages: {
                       
                        email: {
                            
                            email: "Please provide a valid email."
                        },
                        
                        contact_no: "Contact is required",
                        province: "Province is required",
                        city: "City is required",
                        barangay: "Barangay is required",
                        
                        postal_code: "Postal Code is required",
                        
                    },
            
            
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
            
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    },
            
                    errorPlacement: function (error, element) {
                        if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                            var controls = element.closest('div[class*="col-"]');
                            if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                            else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        }
                        else if(element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        }
                        else if(element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        }
                        else error.insertAfter(element.parent());
                    },
                    submitHandler: function (form) {
                        var form_data = new FormData(form);
                        form_data.append('province',$('#form-contact input[name="province"]').val());
                        form_data.append('city',$('#form-contact select[name="city"]').find('option:selected').text());
                        
                        // $('#form-contact .submit').attr('disabled',true);
                        // $('#form-contact .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                        $.ajax({
                            url: "{{route('contacts.store')}}",
                            type: "POST",
                            data: form_data,
                            dataType: 'JSON',
                            processData: false,
                            contentType:false,
                            cache:false,
                            success: function(result){

                                if(result.type=="success"){
                                    $('#form-contact').trigger('reset');
                                }

                                swal(result.message, {
                                  icon: result.type,
                                  title: result.title
                                }).then(function(){
                                    var index = $('select[name="shipper_id"] > option').length;
                                    // $('#shipper_id_chosen').find('.chosen-results').append('<li class="active-result" data-option-array-index="'+(index-1)+'" style="">'+result.data['fileas']+'</li>');
                                    $('select.contacts').append('<option value="'+result.data['contact_id']+'">'+result.data['fileas']+'</option>');
                                    $('#form-contact').trigger('reset');
                                    $('#form-contact #province').html('');
                                    $('#form-contact #postal_code').html(''); 
                                    $('.company').attr('style','display:none');
                                    $('.name').removeAttr('style');
                                    $('#form-contact #select2-city-container').attr('title','--Select City--');
                                    $('#form-contact #select2-city-container').html('<span class="select2-selection__clear">×</span>');
                                    $('#form-contact input[name="lname"]').focus();
                                });
                            }
                        });
                        return false;
                    },
                    invalidHandler: function (form) {
                    }
                });

                $('#form-address').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        address_label: {
                            required: true
                        },
                        province: {
                            required: true
                        },
                        city: {
                            required: true
                        },
                        barangay: {
                            required: true
                        }
                    },
                        
                    messages: {
                    address_label: "Address label is required",
                    province: "Province is required",
                    city: "City is required",
                    barangay: "Barangay is required"
                    },
                        
                        
                    highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
                        
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    },
                        
                    errorPlacement: function (error, element) {
                        if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                            var controls = element.closest('div[class*="col-"]');
                            if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                            else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                        }
                        else if(element.is('.select2')) {
                            error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                        }
                        else if(element.is('.chosen-select')) {
                            error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                        }
                        else error.insertAfter(element.parent());
                    },
                        
                    submitHandler: function (form) {
                        var form_data = new FormData(form);
                        form_data.append('_token',"{{csrf_token()}}");
                        
                        form_data.append('city',$('#form-address select[name="city"]').find('option:selected').text());
                        
                        $('#form-address .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                        $('#form-address .submit').attr('disabled',true);

                        $.ajax({
                            url: "{{route('contacts.save_address')}}",
                            type: "POST",
                            data: form_data,
                            dataType: 'JSON',
                            processData: false,
                            contentType:false,
                            cache:false,
                            success: function(result){

                                if(result.type=="success"){
                                    $('#form-address').trigger('reset');   
                                    $('#form-address #province').html('');
                                    $('#form-address #postal_code').html('');
                                    $('#form-address #select2-city-container').attr('title','--Select City--');
                                    $('#form-address #select2-city-container').html('<span class="select2-selection__clear">×</span>');
                                    if($('select[name="shipper_id"]').val()==result.data['user_id']){
                                        var street = result.data['street']==null ? '' : result.data['street'];
                                        var separator = result.data['street']==null ? '' : ', ';
                                        $('select[name="shipper_address_id"]').append('<option value="'+result.data['useraddress_no']+'">'+street+separator+result.data['barangay']+', '+result.data['city']+', '+result.data['province']+', '+result.data['postal_code']+'</option>');
                                    }else{
                                        var street = result.data['street']==null ? '' : result.data['street'];
                                        var separator = result.data['street']==null ? '' : ', ';
                                        $('select[name="consignee_address_id"]').append('<option value="'+result.data['useraddress_no']+'">'+street+separator+result.data['barangay']+', '+result.data['city']+', '+result.data['province']+', '+result.data['postal_code']+'</option>');
                                    }
                                }
                                
                                $('.message-container').removeAttr('style');
                                var type = result.type=='error' ? 'alert-danger' : 'alert-success';
                                var icon = result.type=='error' ? 'fa-times' : 'fa-check';
                                $('.message-container .alert').addClass(type);
                                $('.message-container .title').html('<i class="ace-icon fa '+icon+'"></i>'+result.title);
                                $('.message-container .message').html(result.message);
                                
                                $('#form-address .submit').html('Save');
                                $('#form-address .submit').removeAttr('disabled');
                                 
                                
                                
                                

                            }
                        });
                        return false;
                    },
                    invalidHandler: function (form) {
                    }
                });

                
                
                $('#save-close').click(function(e){
                    $("#form-contact").submit();
                    $('#modal-form').modal('hide');
                    e.preventDefault();  
                })

                $('#save-new').click(function(e){
                    $("#form-contact").submit();
                    $('#form-contact input[name="lname"]').focus();
                    e.preventDefault(); 
                    
                })
                
                $('#modal-wizard-container').ace_wizard();
                $('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
                
                
                /**
                $('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
                    $(this).closest('form').validate().element($(this));
                });
                
                $('#mychosen').chosen().on('change', function(ev) {
                    $(this).closest('form').validate().element($(this));
                });
                */
                
                
                $(document).one('ajaxloadstart.page', function(e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });
            })
        </script>
@endsection