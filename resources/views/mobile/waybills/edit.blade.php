@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('waybills.index')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">UPDATE BOOKING</div>
        <!-- <div class="right">
          <a class="link popup-open" id="review">
            <i class="icon f7-icons">doc_text_search</i>
            Review
          </a>
        </div> -->
      </div>
  </div>

  <div class="fab fab-extended fab-center-bottom color-green">
    <a href="#" id="submit-form">
      <i class="icon f7-icons color-white">cart</i>
      <div class="fab-text">Update</div>
    </a>
  </div>

  <div class="page-content">
  
    <div class="list">
    <div class="block">
    <h1>Ref # : {{$data->reference_no}}</h1>
    <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Input field with (<font color="red">*</font>) is required</div>
    </div>
      <form id="form">
      @csrf
      <div class="block-header">Payment Type <font color="red">*</font></div>
      <div class="media-list">
        <ul>
            <li>
              <label class="item-radio item-radio-icon-start item-content">
                <input type="radio" name="payment_type" value="CI" required {{$data->payment_type=='CI' ? 'checked' : ''}} />
                <i class="icon icon-radio"></i>
                <div class="item-inner">
                  <div class="item-title-row">
                    <div class="item-title">Prepaid</div>
                  </div>
                  <div class="item-text">Payment will be made at the shipping branch</div>
                </div>
              </label>
            </li>
          <li>
            <label class="item-radio item-radio-icon-start item-content">
              <input type="radio" name="payment_type" value="CD" required {{$data->payment_type=='CD' ? 'checked' : ''}}/>
                <i class="icon icon-radio"></i>
                <div class="item-inner">
                  <div class="item-title-row">
                    <div class="item-title">Collect</div>
                  </div>
                  <div class="item-text">Payment will be made at the destination branch</div>
                </div>
              </label>
          </li>
        </ul>
      </div>

      
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <h3 class="text-strong">Shipper Details</h3>
          </div>
          <div class="right">
            <a href="#" class="link icon-only popup-open" data-popup=".popup-contact">
              <i class="icon f7-icons">person_badge_plus</i>
            </a>
          </div>
        </div>
        <div class="card-content card-content-padding">
          <div class="list no-hairlines-md">
            <ul>
              <li>
                <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search shipper" data-virtual-list="true">
                  <select name="shipper" class="contacts">
                      <option>--PLEASE SELECT SHIPPER--</option>
                      @foreach($contacts as $row)
                        <option  {{$row->contact_id==$data->shipper_id ? 'selected' : ''}} data-contact_no="{{$row->contact_no}}" data-user_address="{{$row->user_address}}" value="{{$row->contact_id}}">{{$row->fileas}}</option>
                      @endforeach
                  </select>
                  <div class="item-content">
                    <div class="item-inner">
                      <div class="item-title">Shipper Name <font color="red">*</font></div>
                      <div class="item-after">{{$data->shipper->fileas}}</div>
                    </div>
                  </div>
                </a>

              </li>

              <li>
                <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search address" data-virtual-list="true">
                  <select name="shipper_address" class="shipper_address" required validate>
                      @foreach($data->shipper->user_address as $row)
                        <option {{$row->useraddress_no==$data->shipper_address_id ? 'selected' : ''}} value="{{$row->useraddress_no}}">{{$row->full_address}}</option>
                      @endforeach
                  </select>
                  <div class="item-content">
                    <div class="item-inner">
                      <div class="item-title">Shipper Address <font color="red">*</font></div>
                      <div class="item-after">{{$data->shipper_address->full_address}}</div>
                    </div>
                  </div>
                </a>
              </li>

              <li class="item-content item-input">
                <div class="item-inner">
                  <div class="item-title item-label">Contact #</div>
                  <div class="item-input-wrap">
                    <input type="text" name="shipper_contact_no" class="shipper_contact_no" value="{{$data->shipper->contact_no}}" readonly>
                    <span class="input-clear-button"></span>
                  </div>
                </div>
              </li>

            </ul>
          </div>
        </div>
      </div>

      
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <h3 class="text-strong">Consignee Details</h3>
          </div>
          <div class="right">
            <a href="/contacts/create/" class="link icon-only">
              <i class="icon f7-icons">person_badge_plus</i>
            </a>
          </div>
        </div>
        <div class="card-content card-content-padding">
          <div class="list no-hairlines-md">
            <ul>
              <li>
                
                <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search consignee" data-virtual-list="true">
                  <select name="consignee" class="contacts">
                      <option>--PLEASE SELECT CONSIGNEE--</option>
                      @foreach($contacts as $row)
                        <option {{$row->contact_id==$data->consignee_id ? 'selected' : ''}}  data-contact_no="{{$row->contact_no}}" data-user_address="{{$row->user_address}}" value="{{$row->contact_id}}">{{$row->fileas}}</option>
                        
                      @endforeach
                    </select>
                  <div class="item-content">
                    <div class="item-inner">
                      <div class="item-title">Consignee Name <font color="red">*</font></div>
                      <div class="item-after">{{$data->shipper->fileas}}</div>
                    </div>
                  </div>
                </a>

              </li>

              <li>
                
                <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search consignee" data-virtual-list="true">
                  <select name="consignee_address" required>
                    @foreach($data->consignee->user_address as $row)
                        <option {{$row->useraddress_no==$data->consignee_address_id ? 'selected' : ''}} value="{{$row->useraddress_no}}">{{$row->full_address}}</option>
                      @endforeach
                  </select>
                  <div class="item-content">
                    <div class="item-inner">
                      <div class="item-title">Consignee Address <font color="red">*</font></div>
                      <div class="item-after">{{$data->consignee_address->full_address}}</div>
                    </div>
                  </div>
                </a>
              </li>

              <li class="item-content item-input">
                <div class="item-inner">
                  <div class="item-title item-label">Contact #</div>
                  <div class="item-input-wrap">
                    <input type="text" name="consignee_contact_no" class="consignee_contact_no" value="{{$data->consignee->contact_no}}" readonly>
                    <span class="input-clear-button"></span>
                  </div>
                </div>
              </li>

            </ul>
          </div>
        </div>
      </div>

      <div class="card data-table data-table-collapsible data-table-init">
        <div class="card-header">
          <div class="data-table-title">SHIPMENTS <font color="red">*</font></div>
          <div class="data-table-actions"><a class="button" id="add-item">Add</a></div>
        </div>
        <div class="card-content">
          

          <div class="list shipments-list">
            <ul>
                @foreach($data->waybill_shipment as $key=>$row)
                    <li class="swipeout">
                        <div class="swipeout-content" style="">
                          <div class="row">
                              <div class="col-85" style="padding-left:20px">
                              <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search stock" data-virtual-list="true">
                              <select name="stocks" class="description">
                                <option value="" selected disabled>--Select Description--</option>
                                {!! $arrddStocks[$key] !!}
                              </select>
                                  <div class="item-content">
                                    <div class="item-inner">
                                      <div class="item-title">Description <font color="red">*</font></div>
                                      <div class="item-after">{{$row->item_description}}</div>
                                    </div>
                                  </div>
                                </a>
                              </div>
                              <div class="col-15" style="padding-top:10px;"></div>
                          </div>
                          <div class="row">
                              <div class="col-85" style="padding-left:20px">
                              <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search unit" data-virtual-list="true">
                                <select name="unit" class="units">
                                 <option value="" selected disabled>--Select Units--</option>
                                {!! $arrddUnits[$key] !!}
                                </select>
                                <div class="item-content">
                                  <div class="item-inner">
                                    <div class="item-title">Unit <font color="red">*</font></div>
                                    <div class="item-after">{{$row->unit_description}}</div>
                                  </div>
                                </div>
                              </a>
                              </div>
                              <div class="col-15" style="padding-top:10px;"><center><i class="icons f7-icons">arrow_left</i></center></div>
                          </div>

                          <div class="row">
                              <div class="col-85" style="padding-left:20px">
                              <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search preset" data-virtual-list="true">
                                <select name="preset" class="preset">
                                  <option value="none" selected disabled>--Select Preset--</option>
                                  
                                </select>
                                <div class="item-content">
                                  <div class="item-inner">
                                    <div class="item-title">Preset</div>
                                  </div>
                                </div>
                              </a>
                              </div>
                              <div class="col-15" style="padding-top:10px;"><center><small>Swipe here</small><center></div>
                          </div>

                          <div class="row">
                              <div class="col-85" style="padding-left:20px">
                                <div class="item-content">
                                    <div class="item-inner">
                                      <div class="item-title">Quantity <font color="red">*</font></div>
                                      <div class="item-after"><input type="text" name="quantity" class="quantity" value="{{$row->quantity}}" placeholder="Item quantity" style="text-align: right;"></div>
                                    </div>
                                </div>
                              </div>
                              <div class="col-15" style="padding-top:10px;"></div>
                          </div>
                          </div>
                          <div class="swipeout-actions-right">
                          <a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete" style="">Delete</a>
                        </div>
                    </li>
                @endforeach
            </ul>
          </div>
          
          
        </div>
      </div>

      
      <ul>
        <li class="item-content item-input">
          <div class="item-inner">
            <div class="item-title item-label">Destination <font color="red">*</font></div>
            <div class="item-input-wrap">
              <select name="destination" required validate>
              <option value="none" selected disabled>--Select branch--</option>
                @foreach($branches as $row)
                    <option {{$data->destinationbranch_id==$row->branchoffice_no ? 'selected' : ''}} value="{{$row->branchoffice_no}}">{{$row->branchoffice_description}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </li>

        <li class="item-content item-input">
          <div class="item-inner">
            <div class="item-title item-label">Shipment Type <font color="red">*</font></div>
            <div class="item-input-wrap">
              <select name="shipment_type">
                <option value="none" selected disabled>--Select shipment type--</option>
                <option {{$data->shipment_type=='BREAKABLE' ? 'selected' : ''}} data-declared_value="1000">BREAKABLE</option>
                <option {{$data->shipment_type=='PERISHABLE' ? 'selected' : ''}} data-declared_value="1000">PERISHABLE</option>
                <option {{$data->shipment_type=='LETTER' ? 'selected' : ''}} data-declared_value="500">LETTER</option>
                <option {{$data->shipment_type=='OTHERS' ? 'selected' : ''}} data-declared_value="2000">OTHERS</option>
              </select>
            </div>
          </div>
        </li>

        <li class="item-content item-input">
          <div class="item-inner">
            <div class="item-title item-label">Declared Value <font color="red">*</font></div>
            <div class="item-input-wrap">
              <input type="number" class="declare-amount" name="declared_value" value="{{$data->declared_value}}">
            </div>
          </div>
        </li>


        <li class="item-content item-input">
          
          <div class="item-inner">
            <div class="item-title item-label">Discount Coupon</div>
            <div class="item-input-wrap">
              <input type="text" name="discount_coupon" class="discount-coupon" value="{{$data->discount_coupon}}">
              <span class="input-clear-button"></span>
            </div>
          </div>

          <div class="item-media">
            <a class="button" id="verify">
              <i class="icon f7-icons">search</i>
            </a>
          </div>
        </li>

      </ul>
      </form>
    </div>

    <div class="popup demo-popup-swipe-handler popup-contact">
      <div class="page">
        <div class="swipe-handler"></div>
        <div class="page-content">
          <div class="block-title block-title-large">SHIPPER/CONSIGNEE</div>
          <div class="list list-inset">
          <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Input field with (<font color="red">*</font>) is required</div>
            <form id="form-contact">
                <ul>
                    <li>
                        <div class="item-input-wrap" style="margin-left:15px;">
                            <label class="toggle toggle-init color-blue">
                                <input type="checkbox" class="use-company" name="use_company">
                                <span class="toggle-icon"></span>
                            </label>
                            <span>Use Company</span>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-outline name">
                      <div class="item-inner">
                          <div class="item-title item-floating-label">Lastname <font color="red">*</font></div>
                          <div class="item-input-wrap">
                          <input type="text" name="lname" class="input-name" required>
                          <span class="input-clear-button"></span>
                          </div>
                      </div>
                    </li>
                    <li class="item-content item-input item-input-outline name">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Firstname <font color="red">*</font></div>
                        <div class="item-input-wrap">
                        <input type="text" name="fname" class="input-name" required>
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                    </li>
                    <li class="item-content item-input item-input-outline name">
                      <div class="item-inner">
                          <div class="item-title item-floating-label">Middlename</div>
                          <div class="item-input-wrap">
                          <input type="text" name="mname">
                          <span class="input-clear-button"></span>
                          </div>
                      </div>
                    </li>
                    <li class="item-content item-input item-input-outline company" style="display:none;">
                      <div class="item-inner">
                          <div class="item-title item-floating-label">Company <font color="red">*</font></div>
                          <div class="item-input-wrap">
                          <input type="text" name="company" class="input-company">
                          <span class="input-clear-button"></span>
                          </div>
                      </div>
                    </li>
                    <li class="item-content item-input item-input-outline">
                      <div class="item-inner">
                          <div class="item-title item-floating-label">Email</div>
                          <div class="item-input-wrap">
                          <input type="email" name="email">
                          <span class="input-clear-button"></span>
                          </div>
                      </div>
                    </li>
                    <li class="item-content item-input item-input-outline">
                      <div class="item-inner">
                          <div class="item-title item-floating-label">Contact # <font color="red">*</font></div>
                          <div class="item-input-wrap">
                          <input type="text" name="contact_no" required>
                          <span class="input-clear-button"></span>
                          </div>
                      </div>
                    </li>
                    
                    <li>
                      <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search business type">
                          <select name="business_type">
                              <option selected disabled style="display:none;" value="">--Select business type--</option>
                              @foreach($business_types as $row)
                                  <optgroup label="{{$row->businesstype_description}}">
                                      @foreach($row->business_type_category as $r)
                                          <option value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                                      @endforeach
                                  </optgroup>
                              @endforeach
                          </select>
                          
                          <div class="item-content">
                          <div class="item-inner">
                              <div class="item-title">Business Type</div>
                          </div>
                          </div>
                      </a>
                    </li>

                    <li class="item-content item-input item-input-outline">
                        <div class="item-inner">
                            <div class="item-title item-floating-label">Address Label</div>
                            <div class="item-input-wrap">
                                <input type="text" name="address_caption" value="" placeholder="HOME/WORK">
                                <span class="input-clear-button"></span>
                            </div>
                        </div>
                    </li>
                    
                    <li class="item-content item-input item-input-outline">
                        <div class="item-inner">
                            <div class="item-title item-floating-label">Street/Bldg/Others</div>
                            <div class="item-input-wrap">
                                <input type="text" name="street" value="">
                                <span class="input-clear-button"></span>
                            </div>
                        </div>
                    </li>
                    
                    <li class="item-content item-input item-input-outline">
                        <div class="item-inner">
                            <div class="item-title item-floating-label">Barangay <font color="red">*</font></div>
                            <div class="item-input-wrap">
                                <input type="text" name="barangay" value="" required>
                                <span class="input-clear-button"></span>
                            </div>
                        </div>
                    </li>
                    
                    <li>
                        <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search city" data-virtual-list="true"> 
                            <select name="city">
                                <option value="none" disabled selected>--Please select city--</option>
                                {!! $ddCities !!}
                            </select>
                            <div class="item-content">
                                <div class="item-inner">
                                    <div class="item-title">City <font color="red">*</font></div>
                                    <div class="item-after city-after"></div>
                                </div>
                            </div>
                        </a> 
                    </li>
                    
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                            <div class="item-title item-label">Province</div>
                            <div class="item-input-wrap">
                                <input type="text" name="province" readonly value="">
                                
                            </div>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                            <div class="item-title item-label">Postal Code</div>
                            <div class="item-input-wrap">
                                <input type="text" name="postal_code" readonly value="">
                                
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row" style="padding-right:15px;padding-left:15px;">
                            <button type="submit" class="col button button-fill">Save</button>
                        </div>
                    </li>
                </ul>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="popup demo-popup-swipe-handler">
      <div class="page">
        <div class="swipe-handler"></div>
        <div class="page-content">
          <div class="block-title block-title-large">Booking Details</div>
          <div class="list no-hairlines-md">
            <ul>
              <li class="item-content item-input">
                <div class="item-inner">
                  <div class="item-title item-label">Payment Type</div>
                  <div class="item-input-wrap">
                  <b id="payment_type_value"></b>
                  </div>
                </div>
              </li>
            </ul>
            <div class="block-title">Shipper</div>
            <div class="card demo-facebook-card">
              <div class="card-header">
                <div class="demo-facebook-avatar"><img src="{{asset('/images/default-avatar.png')}}" width="34" height="34"/></div>
                <div class="demo-facebook-name" style="font-weight:bold" id="shipper_name_value"></div>
                <div class="demo-facebook-date" id="shipper_address_value"></div>
              </div>
              <div class="card-content card-content-padding">
                <p></p>
                <p class="likes">Contact # : <b id="shipper_contact_no_value"></b></p>
              </div>
            </div> 
            <div class="block-title">Consignee</div>
            <div class="card demo-facebook-card">
              <div class="card-header">
                <div class="demo-facebook-avatar"><img src="{{asset('/images/default-avatar.png')}}" width="34" height="34"/></div>
                <div class="demo-facebook-name" style="font-weight:bold" id="consignee_name_value"></div>
                <div class="demo-facebook-date" id="consignee_address_value"></div>
              </div>
              <div class="card-content card-content-padding">
                <p></p>
                <p class="likes">Contact # : <b id="consignee_contact_no_value"></b></p>
              </div>
            </div> 
          </div>
          <div class="list media-list">
            <ul>
              <li>
                <a href="#" class="item-content">
                  <div class="item-inner">
                    <div class="item-title-row">
                      <div class="item-title" style="color:black;font-size:15px;">SHIPMENT TYPE : <b id="shipment_type_value"></b></div>
                      <div class="item-after" style="color:black;font-size:15px;">Php. <b id="declared_amount_value"></b></div>
                    </div>
                    <div class="item-subtitle" style="color:black;font-size:15px;">
                          
                      <p>Destination : <b id="destination_value"></b></p>
                      <p>Discount Coupon: <b id="discount_coupon_value"></b></p>
                    </div>
                        
                      
                  </div>
                </a>
              </li>
            </ul>
          </div>

          
          <div class="card data-table data-table-collapsible data-table-init">
            <div class="card-header">
              <div class="data-table-title">Shipments</div>
            </div>
            <div class="card-content">
              <table id="table-shipments">
                <thead>
                  <tr>
                    <th class="label-cell">Item</th>
                    <th class="numeric-cell">Unit</th>
                    <!-- <th class="numeric-cell">Preset</th> -->
                    <th class="numeric-cell">Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
          <div class="list inset">
            <ul>

              <li><button id="submit-form" class="button button-fill">Save</button></li>
              
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
    <script>
        
        var shipments = [];
        var description = [];
        var unit = [];
        var quantity = [];
        var description_new='';
        var unit_new='';
        var preset_new ='';
        var quantity_new='';
        $('.contacts').change(function(){
            var name = $(this).attr('name');
            var address = name==="shipper" ? "shipper_address" : "consignee_address";
            // $('select[name="'+address+'"]').html(innerHtml);
            $('input[name="'+name+'_contact_no"]').val($(this).find('option:checked').data('contact_no'));
            var obj = $(this).find('option:checked').data('user_address');
            
            var innerHtml = '<option value="none" disabled selected>--SELECT ADDRESS--</option>';
            for(var i=0; i<obj.length;i++){
              innerHtml = innerHtml + "<option value='"+obj[i]['useraddress_no']+"'>"+obj[i]['full_address']+"</option>";
            }
            $('select[name="'+address+'"]').html(innerHtml);
        });

        $('.use-company').change(function(){
            var check = $(this).is(':checked');
            var formID = $(this).closest('form')[0].id
            if(check){
                $('#'+formID+' .name').attr('style','display:none');
                $('#'+formID+' .input-name').removeAttr('required');
                $('#'+formID+' .company').removeAttr('style');
                $('#'+formID+' .input-company').attr('required',true);
            }else{
                $('#'+formID+' .name').removeAttr('style');
                $('#'+formID+' .input-name').attr('required',true);
                $('#'+formID+' .company').attr('style','display:none');
                $('#'+formID+' .input-company').removeAttr('required');
            }
        });

        // $('.back').click(function(){
        //   window.location.href="{{URL::previous()}}";
        // });

        $('#add-item').click(function(){
            var swipeout =  '<li class="swipeout">'+
                          '<div class="swipeout-content" style="">'+
                          '<div class="row">'+
                              '<div class="col-85" style="padding-left:20px">'+
                              '<a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search stock" data-virtual-list="true">'+
                              '<select name="stocks" class="description">'+
                                '<option value="" selected disabled>--Select Description--</option>'+
                                "{!! $ddStocks !!}"+
                              '</select>'+
                                  '<div class="item-content">'+
                                    '<div class="item-inner">'+
                                      '<div class="item-title">Description <font color="red">*</font></div>'+
                                    '</div>'+
                                  '</div>'+
                                '</a>'+
                              '</div>'+
                              '<div class="col-15" style="padding-top:10px;"><center><i class="icons f7-icons">arrow_left</i></center></div>'+
                          '</div>'+
                          '<div class="row">'+
                              '<div class="col-85" style="padding-left:20px">'+
                              '<a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search unit" data-virtual-list="true">'+
                                '<select name="unit" class="units">'+
                                 '<option value="" selected disabled>--Select Units--</option>'+
                                  "{!! $ddUnits !!}"+
                                '</select>'+
                                '<div class="item-content">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title">Unit <font color="red">*</font></div>'+
                                  '</div>'+
                                '</div>'+
                              '</a>'+
                              '</div>'+
                              '<div class="col-15" style="padding-top:10px;"><center><small>Swipe here</small><center></div>'+
                          '</div>'+

                          '<div class="row">'+
                              '<div class="col-85" style="padding-left:20px">'+
                              '<a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search preset" data-virtual-list="true">'+
                                '<select name="preset">'+
                                  '<option value="" selected disabled>--Select Preset--</option>'+
                                  
                                '</select>'+
                                '<div class="item-content">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title">Preset</div>'+
                                  '</div>'+
                                '</div>'+
                              '</a>'+
                              '</div>'+
                              '<div class="col-15" style="padding-top:10px;"></div>'+
                          '</div>'+

                          '<div class="row">'+
                              '<div class="col-85" style="padding-left:20px">'+
                                '<div class="item-content">'+
                                    '<div class="item-inner">'+
                                      '<div class="item-title">Quantity <font color="red">*</font></div>'+
                                      '<div class="item-after"><input type="text" name="quantity" class="quantity" placeholder="Item quantity" style="text-align: right;"></div>'+
                                    '</div>'+
                                '</div>'+
                              '</div>'+
                              '<div class="col-15" style="padding-top:10px;"></div>'+
                          '</div>'+
                          '</div>'+
                          '<div class="swipeout-actions-right">'+
                          '<a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete" style="">Delete</a>'+
                          '</div>'+
                        '</li>';
            
            if($('.shipments-list > ul li').length<=5){
                var validated = 1;
                var last_li = $('.shipments-list > ul > li:last');
                if(last_li.find('select.description').val()!==null && last_li.find('select.unit').val()!==null && last_li.find('input.quantity').val()!=="") validated=1; else validated=0;
                if(validated===1) $('.shipments-list ul').append(swipeout);
            }
            
        });

        $('#verify').click(function(){
            app.request({
                url : "{{url('/waybills/check-discount-coupon')}}/"+$('input[name="discount_coupon"]').val(),
                method : "GET",
                beforeSend: function(xhr){
                    app.progressbar.show('multi');
                },
                success: function(data,status,xhr){
                  var obj = JSON.parse(data);
                  app.dialog.alert(obj.message);
                },
                error: function(xhr,status){
                  app.dialog.close();
                  var toastBottom = app.toast.create({
                      icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                      text: 'Server connection error',
                      position: 'center',
                      closeTimeout: 3000,
                  });
                  toastBottom.open();
                },
                complete: function(xhr,status){
                    if(status==200){
                    app.progressbar.hide();
                    }
                }
            });
        });
        $('select[name="shipment_type"]').change(function(){
            $('input[name="declared_amount"]').val(($(this).find('option:selected').data('declared_value')));
            $('input[name="declared_value"]').val(($(this).find('option:selected').data('declared_value')));

            if($(this).val()=="OTHERS"){
                $('.declare-amount').removeAttr('disabled');
          
            }else{
                $('.declare-amount').attr('disabled',true);
            }
        });

        $('#review-1').click(function(){
            if($('#form').valid()){
                var validated = 0;
                if($('.shipments-list > ul li').length>0){
                    var last_li = $('.shipments-list > ul > li:last');
                    if(last_li.find('select.description').val()!==null && last_li.find('select.unit').val()!==null && last_li.find('input.quantity').val()!=="") validated=1; else validated=0;
                }
                if(validated==1){
                    var payment_type_text = $('input[name="payment_type"]').val()=="CI" ? "Prepaid" : "Collect";
                    $('#payment_type_value').html(payment_type_text);
                    $('#shipper_name_value').html($('select[name="shipper"]').find('option:checked').text());
                    $('#shipper_address_value').html($('select[name="shipper_address"]').find('option:checked').text());
                    $('#shipper_contact_no_value').html($('input[name="shipper_contact_no"]').val());
                    $('#consignee_name_value').html($('select[name="consignee"]').find('option:checked').text());
                    $('#consignee_address_value').html($('select[name="consignee_address"]').find('option:checked').text());
                    $('#consignee_contact_no_value').html($('input[name="consignee_contact_no"]').val());
                    $('#shipment_type_value').html($('select[name="shipment_type"] option:selected').text());
                    $('#destination_value').html($('select[name="destination"] option:selected').text()+" BRANCH");
                    $('#declared_amount_value').html($('input[name="declared_value"]').val()+".00");
                    $('#discount_coupon_value').html($('input[name="discount_coupon"]').val());
                    // shipments=[];
                    $('#table-shipments > tbody').empty();
                    for(var i=0;i<$('.shipments-list > ul li').length;i++){
                        var separator = i===0 ? '' : ',';
                        var preset_value = $('.shipments-list > ul li select.preset').eq(i).val()==null ? '' : $('.shipments-list > ul li select.preset').eq(i).val();
                        description_new += separator + $('.shipments-list > ul li select.description').eq(i).val();
                        unit_new += separator + $('.shipments-list > ul li select.units').eq(i).val();
                        preset_new += separator + preset_value;
                        quantity_new += separator + $('.shipments-list > ul li input.quantity').eq(i).val();
                        var innerHtml = '<tr><td>'+$('.shipments-list > ul li select.description option:selected').eq(i).text()+'</td><td>'+$('.shipments-list > ul li select.units option:selected').eq(i).text()+'</td><td>'+$('.shipments-list > ul li input.quantity').eq(i).val()+'</td></tr>';
                        $('#table-shipments > tbody').append(innerHtml);
                    }
                    
                    
                    app.popup.open('.demo-popup-swipe-handler', true);
                }
            }
        });

        $('#submit-form').click(function(){
          $('#form').submit();
        });

        $('#form').on('submit',function(e){
          if($('#form').valid()){
                var validated = 0;
                if($('.shipments-list > ul li').length>0){
                    var last_li = $('.shipments-list > ul > li:last');
                    if(last_li.find('select.description').find('option:checked').val()!=="" && last_li.find('select.unit').find('option:checked').val()!=="" && last_li.find('input.quantity').val()!==""){
                      validated=1;
                    }else{
                      var toastBottom = app.toast.create({
                        icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                        text: 'Please check your shipments',
                        position: 'center',
                        closeTimeout: 2000,
                      });
                      toastBottom.open();
                    }  
                }else{
                  var toastBottom = app.toast.create({
                    icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                    text: 'Please check your shipments',
                    position: 'center',
                    closeTimeout: 2000,
                  });
                  toastBottom.open();
                }
                if(validated==1){
                    for(var i=0;i<$('.shipments-list > ul li').length;i++){
                        var separator = i===0 ? '' : ',';
                        var preset_value = $('.shipments-list > ul li select.preset').eq(i).val()==null ? '' : $('.shipments-list > ul li select.preset').eq(i).val();
                        description_new += separator + $('.shipments-list > ul li select.description').eq(i).val();
                        unit_new += separator + $('.shipments-list > ul li select.units').eq(i).val();
                        preset_new += separator + preset_value;
                        quantity_new += separator + $('.shipments-list > ul li input.quantity').eq(i).val();
                        var innerHtml = '<tr><td>'+$('.shipments-list > ul li select.description option:selected').eq(i).text()+'</td><td>'+$('.shipments-list > ul li select.units option:selected').eq(i).text()+'</td><td>'+$('.shipments-list > ul li input.quantity').eq(i).val()+'</td></tr>';
                        $('#table-shipments > tbody').append(innerHtml);
                    }

                    var form_data = {
                    _token : "{{csrf_token()}}",
                    payment_type : $('input[name="payment_type"]:checked').val(),
                    shipper_id : $('select[name="shipper"]').find('option:checked').val(),
                    shipper_address_id: $('select[name="shipper_address"]').find('option:checked').val(),
                    consignee_id : $('select[name="consignee"]').find('option:checked').val(),
                    consignee_address_id : $('select[name="consignee_address"]').find('option:checked').val(),
                    shipment_type : $('select[name="shipment_type"] option:selected').val(),
                    destinationbranch_id : $('select[name="destination"] option:selected').val(),
                    declared_value : $('input[name="declared_value"]').val(),
                    discount_coupon : $('input[name="discount_coupon"]').val(),
                    description: description_new,
                    unit : unit_new,
                    preset : preset_new,
                    quantity : quantity_new
                  };
                    
                  app.request({
                  url : "{{route('waybills.update',['id'=>$data->reference_no])}}",
                  method : "PUT",
                  data : form_data,
                  beforeSend: function(xhr){
                    app.dialog.progress('Please wait..');
                  },
                  success: function(data,status,xhr){
                      var obj = JSON.parse(data);
                      var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'cart_badge_plus';
                      var toastBottom = app.toast.create({
                        icon: '<i class="icons f7-icons">'+icon+'</i>',
                        text: obj['message'],
                        position: 'center',
                        closeTimeout: 2000,
                      });
                      if(obj.type=='success'){
                        app.popup.close('.demo-popup-swipe-handler', true);
                        $('.shipments-list > ul').empty();
                        $('.item-after').html('');
                        $('#form').trigger('reset');
                        
                      }
                      toastBottom.open();
                      setTimeout(function(){
                        window.location.href="{{route('waybills.index')}}";
                      },1500);
                  },
                  error: function(xhr,status){
                    app.dialog.close();
                    var toastBottom = app.toast.create({
                        icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                        text: 'Server connection error',
                        position: 'center',
                        closeTimeout: 3000,
                    });
                    toastBottom.open();
                  },
                  complete: function(xhr,status){
                      if(status==200){
                        app.dialog.close();
                      }
                    }
                  })
                }
            }

          e.preventDefault();
        })

        $('select[name="city"]').change(function(e){
            app.request.get("{{url('/get-city-data')}}/"+$(this).find('option:checked').val(),{},function(data,status,xhr){
                var obj = JSON.parse(data);
                $('input[name="province"]').val(obj['province']['province_name']);
                $('label#province').html(obj['province']['province_name']);
                $('input[name="postal_code"]').val(obj['postal_code']);
                $('label#postal_code').html(obj['postal_code']);
            },function(xhr,status){
            },'JSON');
        });

        $('#form-contact').submit(function(e){
            if($(this).valid()){
                var form_data = { _token : "{{csrf_token()}}", lname : $('input[name="lname"]').val(), fname :  $('input[name="fname"]').val(), mname : $('input[name="mname"]').val(), gender : $('select[name="gender"]').val(), email : $('input[name="email"]').val(), contact_no : $('input[name="contact_no"]').val(), company: $('input[name="company"]').val(),business_category_id : $('select[name="business_type"]').find('option:checked').val(), address_label : $('input[name="address_caption"]').val(), street : $('input[name="street"]').val(), barangay : $('input[name="barangay"]').val(), city : $('select[name="city"]').find('option:checked').text(),province : $('input[name="province"]').val(), postal_code : $('input[name="postal_code"]').val()};
                
                app.request.post("{{route('contacts.store')}}",form_data,function(data,xhr,status){
                    var obj = JSON.parse(data);
                    var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'person_badge_plus';
                    var toastBottom = app.toast.create({
                      icon: '<i class="icons f7-icons">'+icon+'</i>',
                      text: obj['message'],
                      position: 'center',
                      closeTimeout: 2000,
                    });
                    if(obj.type=='success'){
                        var new_row = obj.data;
                        $('.contacts').append('<option value="'+new_row['contact_id']+'">'+new_row['fileas']+'</option>');
                        app.popup.close('.popup-contact',true);
                        $('#form-contact').trigger('reset');
                    }
                    toastBottom.open();
                    
                },function(xhr,status){

                },'JSON');
            }
            e.preventDefault();
        });
    </script>
@endsection