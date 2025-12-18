@extends('layouts.mobile')

@section('content')
<div class="page">
    <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
            <a href="javascript:history.back()" class="link back">
              <i class="icon icon-back"></i>
              <span class="if-not-md">Back</span>
            </a>
          </div>
        <div class="title">Account Setting</div>
        <div class="right">
          
        </div>
      </div>
    </div>
    <div class="toolbar tabbar tabbar-labels toolbar-bottom">
      <div class="toolbar-inner">
        <a href="#qrcode" class="tab-link tab-link-active">
          <i class="icon f7-icons">qrcode</i>
          <span class="tabbar-label">QRCode</span>
        </a>
        <a href="#tab-1" class="tab-link">
          <i class="icon material-icons">portrait</i>
          <span class="tabbar-label">Profile</span>
        </a>
        <a href="#tab-2" class="tab-link">
          <i class="icon f7-icons">lock_circle_fill</i>
          <span class="tabbar-label">Password</span>
        </a>
        <a href="#tab-3" class="tab-link">
          <i class="icon material-icons">person_pin_circle</i>
          <span class="tabbar-label">Address Book</span>
        </a>
      </div>
    </div>
    
    <div class="tabs">
      <div id="qrcode" class="page-content tab tab-active">
        <div class="block">
            <br><br>
            <center>
            <div id="qrcode1"></div>
            </center>
        </div>
      </div>
      <div id="tab-1" class="page-content tab ptr-content" data-ptr-mousewheel="true" @ptr:refresh="loadProfile">
        <div class="ptr-preloader">
            <div class="preloader"></div>
            <div class="ptr-arrow"></div>
        </div>

        <div class="block">
          <h1>Profile Information</h1>
          
          <div class="list no-hairlines-md">
            <form id="form-profile">
                <ul>
                <li>
                
                    <div class="item-input-wrap" style="margin-left:15px;">
                        <label class="toggle toggle-init color-blue">
                            <input type="checkbox" class="use-company" name="use_company" {{Auth::user()->contact->use_company==1 ? 'checked' : ''}}>
                            <span class="toggle-icon"></span>
                        </label>
                        <span>Use Company</span>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{Auth::user()->contact->use_company==0 ? '' : 'display:none;'}}">
                    <div class="item-inner">
                    <div class="item-title item-label">Lastname <font color="red">*</font></div>
                    <div class="item-input-wrap">
                        <input type="text" name="lname" class="input-name" value="{{Auth::user()->contact->lname}}" class="input-focused" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{Auth::user()->contact->use_company==0 ? '' : 'display:none;'}}">
                    <div class="item-inner">
                    <div class="item-title item-label">Firstname  <font color="red">*</font></div>
                    <div class="item-input-wrap">
                        <input type="text" name="fname" class="input-name" value="{{Auth::user()->contact->fname}}" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{Auth::user()->contact->use_company==0 ? '' : 'display:none;'}}">
                    <div class="item-inner">
                    <div class="item-title item-label">Middlename</div>
                    <div class="item-input-wrap">
                        
                        <input type="text" name="mname" value="{{Auth::user()->contact->mname}}">
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>

                <li class="item-content item-input item-input-with-info company" style="{{Auth::user()->contact->use_company==1 ? '' : 'display:none;'}}">
                    <div class="item-inner">
                    <div class="item-title item-label">Company <font color="red">*</font></div>
                    <div class="item-input-wrap">
                        <input type="text" name="company" class="input-company" value="{{Auth::user()->contact->company}}">
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                
                <li class="item-content item-input item-input-with-info">
                    <div class="item-inner">
                    <div class="item-title item-label">Contact # <font color="red">*</font></div>
                    <div class="item-input-wrap">
                        <input type="text" name="contact_no" value="{{Auth::user()->contact->contact_no}}">
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info">
                    <div class="item-inner">
                    <div class="item-title item-label">Email <font color="red">*</font></div>
                    <div class="item-input-wrap">
                        <input type="email" name="email"  value="{{Auth::user()->contact->email}}" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                
                <li>
                    <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search business type">
                        <select name="business_type">
                            @foreach($business_types as $row)
                                <optgroup label="{{$row->businesstype_description}}">
                                    @foreach($row->business_type_category as $r)
                                        <option {{Auth::user()->contact->business_category_id==$r->businesstype_category_id ? 'selected' : ''}} value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        
                        <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title">Business Type</div>
                            <div class="item-after business-type-after"></div>
                        </div>
                        </div>
                    </a>
                </li>
                
                <li class="item-content item-input item-input-with-info">
                    <div class="row">
                        <button type="submit" class="col button button-fill color-blue">Update</button>
                    </div>
                </li>
                </ul>
            </form>
          </div>
          
        </div>
      </div>
      <div id="tab-2" class="page-content tab">
        <div class="block">
          <h1>Password</h1>
          <div class="list no-hairlines-md">
            <form id="form-password">
                <ul>
                <li class="item-content item-input item-input-with-info">
                    <div class="item-inner">
                    <div class="item-title item-label">Password *</div>
                    <div class="item-input-wrap">
                        <input type="password" name="current_password" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info">
                    <div class="item-inner">
                    <div class="item-title item-label">New Password *</div>
                    <div class="item-input-wrap">
                        <input type="password" name="new_password" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info">
                    <div class="item-inner">
                    <div class="item-title item-label">Confirm Password *</div>
                    <div class="item-input-wrap">
                        <input type="password" name="new_password_confirmation" required>
                        <span class="input-clear-button"></span>
                    </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info">
                    <button type="submit" class="col button button-fill color-blue">Update</button>
                </li>
                </ul>
            </form>
          </div>
        </div>
      </div>

      <div id="tab-3" class="page-content tab ptr-content" data-ptr-mousewheel="true">
      <div class="ptr-preloader">
        <div class="preloader"></div>
        <div class="ptr-arrow"></div>
      </div>
      
        <div class="card data-table data-table-collapsible data-table-init">
            <div class="card-header">
              <div class="data-table-title"><b>MY ADDRESSBOOK</b><br><small><b>Note</b> : Swipe row to left for action button</small></div>
              <div class="data-table-actions"><a class="button add-address">Add</a></div>
            </div>
            <div class="card-content">
              
              <div class="list media-list no-safe-areas address-list">
                <ul>
                    <li>
                        <a href="" class="item-link item-content skeleton-text skeleton-effect-fade">
                        <div class="item-media">
                            <div class="skeleton-block" style="width: 40px; height: 40px; border-radius: 50%"></div>
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                            <div class="item-title">REFERENCE #</div>
                            <div class="item-after">TRANSACTION DATE</div>
                            </div>
                            <div class="item-subtitle">SHIPPER-CONSIGNEE</div>
                            <div class="item-text">PAYMENT TYPE</div>
                        </div>
                    </a>
                    </li>
                    <li>
                        <a href="" class="item-link item-content skeleton-text skeleton-effect-fade">
                        <div class="item-media">
                            <div class="skeleton-block" style="width: 40px; height: 40px; border-radius: 50%"></div>
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                            <div class="item-title">REFERENCE #</div>
                            <div class="item-after">TRANSACTION DATE</div>
                            </div>
                            <div class="item-subtitle">SHIPPER-CONSIGNEE</div>
                            <div class="item-text">PAYMENT TYPE</div>
                        </div>
                    </a>
                    </li>
                    <li>
                        <a href="" class="item-link item-content skeleton-text skeleton-effect-fade">
                        <div class="item-media">
                            <div class="skeleton-block" style="width: 40px; height: 40px; border-radius: 50%"></div>
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                            <div class="item-title">REFERENCE #</div>
                            <div class="item-after">TRANSACTION DATE</div>
                            </div>
                            <div class="item-subtitle">SHIPPER-CONSIGNEE</div>
                            <div class="item-text">PAYMENT TYPE</div>
                        </div>
                    </a>
                    </li>
                </ul>
              </div>
              
            </div>
          </div>

      </div>
      
     

    </div>
    
    <div class="popup demo-popup-swipe-handler">
      <div class="page">
        <div class="swipe-handler"></div>
        <div class="page-content">
          <div class="block-title block-title-large">ADDRESS</div>
          <div class="list list-inset">
            <form id="form-address">
                <input type="hidden" name="useraddress_no" id="useraddress_no">
                <ul>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Address Label <font color="red">*</font></div>
                        <div class="item-input-wrap">
                            <input type="text" name="address_caption" value="" placeholder="HOME/WORK" required>
                            <span class="input-clear-button"></span>
                        </div>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Street/Bldg/Others</div>
                        <div class="item-input-wrap">
                            <input type="text" name="street" value="">
                            <span class="input-clear-button"></span>
                        </div>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Barangay  <font color="red">*</font></div>
                        <div class="item-input-wrap">
                            <input type="text" name="barangay" value="" required>
                            <span class="input-clear-button"></span>
                        </div>
                        </div>
                    </li>
                    <li>
                        <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search city" data-virtual-list="true">
                            
                            
                            <select name="city">
                                {!! $ddCities !!}
                            </select>
                            
                            <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title">City  <font color="red">*</font></div>
                                <div class="item-after city-after"></div>
                            </div>
                            </div>
                        </a> 
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Province  <font color="red">*</font></div>
                        <div class="item-input-wrap">
                            <input type="hidden" name="province" value="">
                            <label id="province"></label>
                        </div>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Postal Code  <font color="red">*</font></div>
                        <div class="item-input-wrap">
                            <input type="hidden" name="postal_code" value="">
                            <label id="postal_code"></label>
                        </div>
                        </div>
                    </li>

                    <li class="item-content item-input item-input-with-info">
                        <button type="submit" class="col button button-fill color-blue">Save</button>
                    </li>
                </ul>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div> 
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
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

        new QRCode(document.getElementById("qrcode1"),"{{Auth::user()->contact_id}}");

        $('#form-profile').submit(function(e){
            if($(this).valid()){
                var form_data = {
                    _token : "{{csrf_token()}}",
                    use_company : $('#form-profile input[name="use_company"]').is(':checked') ? 'on' : 'off',
                    lname : $('#form-profile input[name="lname"]').val(),
                    fname : $('#form-profile input[name="fname"]').val(),
                    mname : $('#form-profile input[name="mname"]').val(),
                    contact_no : $('#form-profile input[name="contact_no"]').val(),
                    email: $('#form-profile input[name="email"]').val(),
                    company : $('#form-profile input[name="company"]').val(),
                    business_category_id : $('select[name="business_type"]').find('option:checked').val()
                };
                app.request({
                    url : "{{route('accounts.update_profile')}}",
                    method : "POST",
                    data : form_data,
                    success: function(data,status,xhr){
                        var obj = JSON.parse(data);
                        var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'person';
                        var toastBottom = app.toast.create({
                            icon: '<i class="icons f7-icons">'+icon+'</i>',
                            text: obj['message'],
                            position: 'center',
                            closeTimeout: 2000,
                        });
                        toastBottom.open();
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
                    }
                })
            }
            e.preventDefault();
        });

        $('#form-password').submit(function(e){
            if($(this).valid()){
                var form_data = {
                    _token : "{{csrf_token()}}",
                    current_password : $('#form-password input[name="current_password"]').val(),
                    new_password : $('#form-password input[name="new_password"]').val(),
                    new_password_confirmation : $('#form-password input[name="new_password_confirmation"]').val()
                    
                };
                app.request({
                    url : "{{route('accounts.update_password')}}",
                    method : "POST",
                    data : form_data,
                    success: function(data,status,xhr){
                        var obj = JSON.parse(data);
                        var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'person';
                        var toastBottom = app.toast.create({
                            icon: '<i class="icons f7-icons">'+icon+'</i>',
                            text: obj['message'],
                            position: 'center',
                            closeTimeout: 2000,
                        });
                        toastBottom.open();
                    }
                })
            }
            e.preventDefault();
        });

        $('.add-address').click(function(){
            $('#useraddress_no').val('');
            $('input[name="address_caption"]').val('');
            $('input[name="street"]').val('');
            $('input[name="barangay"]').val('');
            $('.city-after').html('');
            $('select[name="city"]').val('');
            $('input[name="province"]').val('');
            $('label#province').html('');
            $('input[name="postal_code"]').val('');
            $('label#postal_code').html('');
            app.popup.open('.demo-popup-swipe-handler',true);
        })

        app.request({
            url : "{{route('accounts.addresses')}}",
            method : "GET",
            beforeSend: function(xhr){
                app.progressbar.show('multi');
            },
            success: function(data,status,xhr){
            var obj = JSON.parse(data);
            
            var content = '';
            
            for(var i = 0;i<obj.length;i++){
                content = content + '<li class="swipeout" id="'+obj[i]['useraddress_no']+'">'+
                        '<div class="swipeout-content">'+
                        '<a href="#" class="item-link item-content">'+
                        '<div class="item-media">'+
                        '<i class="icon f7-icons">map_pin</i>'+
                        '</div>'+
                        '<div class="item-inner">'+
                        '<div class="item-title-row">'+
                        '<div class="item-title">'+obj[i]['address_caption']+'</div>'+
                        '<div class="item-after"></div>'+
                        '</div>'+
                        '<div class="item-subtitle">'+obj[i]['barangay']+','+obj[i]['city']+', '+obj[i]['province']+'</div>'+
                        '<div class="item-text"><br>Postal Code : '+obj[i]['postal_code']+'<b></b></div>'+
                        '</div>'+
                        '</a>'+
                        '</div>'+
                        
                        '<div class="swipeout-actions-right">'+
                        '<a href="#" class="color-orange edit-address" id="'+obj[i]['useraddress_no']+'">Update</a>'+
                        '<a href="#" class="color-green set-default" id="'+obj[i]['useraddress_no']+'" >Set as Default</a>'+
                        '</div>'+
                        '</li>';
            }
            // console.log(content);
            // console.log($('.address-list'));
            $('.address-list > ul').empty();
            $('.address-list > ul').append(content);
            $('.set-default').click(function(e){
                app.dialog.confirm('Set as default address?',function(){
                    var data = {_token: "{{csrf_token()}}", useraddress_no : e.target.id};
                    app.request.post("{{url('/set-default')}}",data,function(data,status,xhr){ 
                        var toastBottom = app.toast.create({
                            icon: '<i class="icons f7-icons">map_pin</i>',
                            text: 'Address has been set as default',
                            position: 'center',
                            closeTimeout: 2000,
                        });
                        toastBottom.open();
                    },function(xhr,status){

                    },'JSON');
                });
            });
            $('.edit-address').click(function(e){
                $('#useraddress_no').val(e.target.id);
                app.request.get("{{url('/get-useraddress')}}/"+e.target.id,{},function(data,status,xhr){
                    var obj = JSON.parse(data);
                    $('input[name="address_caption"]').val(obj['address_caption']);
                    $('input[name="street"]').val(obj['street']);
                    $('input[name="barangay"]').val(obj['barangay']);
                    $('.city-after').html(obj['city']);
                    $('select[name="city"]').val($('select[name="city"] option:contains("'+obj['city']+'")').val());
                    $('input[name="province"]').val(obj['province']);
                    $('label#province').html(obj['province']);
                    $('input[name="postal_code"]').val(obj['postal_code']);
                    $('label#postal_code').html(obj['postal_code']);
                    app.popup.open('.demo-popup-swipe-handler',true);
                },function(xhr,status){
                },'JSON');
            });
            },
            complete: function(xhr,status){
                if(status==200){
                app.progressbar.hide();
                }
            }
        });

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

        $('#form-address').submit(function(e){
            if($(this).valid()){
                var form_data = { _token : "{{csrf_token()}}", useraddress_no : $('#useraddress_no').val(), address_caption :  $('input[name="address_caption"]').val(), street : $('input[name="street"]').val(), barangay: $('input[name="barangay"]').val(), city : $('select[name="city"]').find('option:checked').text(),province : $('input[name="province"]').val(), postal_code : $('input[name="postal_code"]').val()};
                app.request.post("{{route('accounts.save_address')}}",form_data,function(data,xhr,status){
                    var obj = JSON.parse(data);
                    app.dialog.alert(obj.message,function(){
                        if(obj.type=='success'){
                            app.popup.close('.demo-popup-swipe-handler',true);
                        }
                    })
                },function(xhr,status){

                },'JSON');
            }
            e.preventDefault();
        });

        
    });
</script>
@endsection

