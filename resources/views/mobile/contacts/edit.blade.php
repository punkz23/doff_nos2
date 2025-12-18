@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
    <div class="navbar-bg"></div>
    <div class="navbar-inner">
      <div class="left sliding">
        <a href="{{route('contacts.index')}}" class="link back external">
          <i class="icon icon-back"></i>
          <span class="if-not-md">Back</span>
        </a>
      </div>
      <div class="title sliding">Contacts</div>
      
      <div class="right">
        <a class="link popup-open" id="add-address">
            <i class="icon f7-icons">map_pin_ellipse</i>
             <small>NEW ADDRESS</small>
        </a>
      </div>
      
      
    </div>
  </div>

  <div class="toolbar toolbar-bottom tabbar">
    <div class="toolbar-inner">
      <a href="#tab-1" class="tab-link tab-link-active">Profile</a>
      <a href="#tab-2" class="tab-link">Address Book</a>
    </div>
  </div>

  <div class="tabs">
    <div id="tab-1" class="page-content tab tab-active">
        
    <div class="list no-hairlines-md">
        <form id="form">
            <ul>
                <li>
                
                    <div class="item-input-wrap" style="margin-left:15px;">
                        <label class="toggle toggle-init color-blue">
                            <input type="checkbox" class="use-company" name="use_company" {{$data->use_company==1 ? 'checked' : ''}}>
                            <span class="toggle-icon"></span>
                        </label>
                        <span>Use Company</span>
                    </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{$data->use_company==1 ? 'display:none' : ''}}">
                <div class="item-inner">
                    <div class="item-title item-label">Lastname *</div>
                    <div class="item-input-wrap">
                    <input type="text" name="lname" class="input-name" value="{{$data->lname}}" {{$data->use_company==1 ? '' : 'required'}}>
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{$data->use_company==1 ? 'display:none' : ''}}">
                <div class="item-inner">
                    <div class="item-title item-label">Firstname *</div>
                    <div class="item-input-wrap">
                    <input type="text" name="fname" class="input-name" value="{{$data->fname}}" {{$data->use_company==1 ? '' : 'required'}}>
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>
                <li class="item-content item-input item-input-with-info name" style="{{$data->use_company==1 ? 'display:none' : ''}}">
                <div class="item-inner">
                    <div class="item-title item-label">Middlename</div>
                    <div class="item-input-wrap">
                    <input type="text" name="mname" value="{{$data->mname}}">
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>

                <li class="item-content item-input item-input-with-info company" style="{{$data->use_company==1 ? '' : 'display:none'}}">
                <div class="item-inner">
                    <div class="item-title item-label">Company</div>
                    <div class="item-input-wrap">
                    <input type="text" name="company" value="{{$data->company}}">
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>
                
                <li class="item-content item-input item-input-with-info">
                <div class="item-inner">
                    <div class="item-title item-label">Email *</div>
                    <div class="item-input-wrap">
                    <input type="email" name="email" value="{{$data->email}}">
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>
                <li class="item-content item-input item-input-with-info">
                <div class="item-inner">
                    <div class="item-title item-label">Contact #</div>
                    <div class="item-input-wrap">
                    <input type="text" name="contact_no" value="{{$data->contact_no}}" required>
                    <span class="input-clear-button"></span>
                    </div>
                </div>
                </li>
                
                <li>
                <a class="item-link smart-select smart-select-init" data-open-in="popup" data-searchbar="true" data-searchbar-placeholder="Search business type">
                    
                    <select name="business_type">
                        <option selected disabled style="display:none;" value="">--Select business type--</option>
                        @foreach($business_types as $row)
                            <optgroup label="{{$row->businesstype_description}}">
                                @foreach($row->business_type_category as $r)
                                    <option {{$data->business_category_id==$r->businesstype_category_id ? 'selected' : ''}} value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
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

                <li>
                    <div class="row">
                        <button type="submit" class="col button color-blue">Save</button>
                    </div>
                </li>
            </ul>
        </form>
        </div>
      
    </div>
    <div id="tab-2" class="page-content tab">
        <!-- Searchbar with auto init -->
        <form class="searchbar">
          <div class="searchbar-inner">
            <div class="searchbar-input-wrap">
              <input type="search" placeholder="Search" name="search">
              <i class="searchbar-icon"></i>
              <span class="input-clear-button"></span>
            </div>
          </div>
        </form>
        <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Swipe left to see action button(s)</div>
        <div  class="list media-list address-list">
            <ul>
                
            </ul>
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
                        <div class="item-title item-label">Address Label *</div>
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
                        <div class="item-title item-label">Barangay *</div>
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
                                <div class="item-title">City</div>
                                <div class="item-after city-after"></div>
                            </div>
                            </div>
                        </a> 
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Province</div>
                        <div class="item-input-wrap">
                            <input type="hidden" name="province" value="">
                            <label id="province"></label>
                        </div>
                        </div>
                    </li>
                    <li class="item-content item-input item-input-with-info">
                        <div class="item-inner">
                        <div class="item-title item-label">Postal Code</div>
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
      app.request({
      url : "{{url('/contacts/addresses-list/'.$id)}}",
      method : "GET",
      beforeSend: function(xhr){
        app.progressbar.show('multi');
      },
      success: function(data,status,xhr){
          var obj = JSON.parse(data);
          var addresses = obj.addresses;
          
          var content = '';
          for(var i = 0;i<addresses.length;i++){
            var default_address = addresses[i]['address_def']==1 ? 'DEFAULT' : '';
            content = content + '<li class="swipeout" id="'+addresses[i]['useraddress_no']+'">'+
                      '<div class="swipeout-content">'+
                      '<a href="#" class="item-link item-content">'+
                      '<div class="item-media">'+
                      '<i class="icon f7-icons">map_pin</i>'+
                      '</div>'+
                      '<div class="item-inner">'+
                      '<div class="item-title-row">'+
                      '<div class="item-title">'+addresses[i]['address_caption']+'</div>'+
                      '<div class="item-after"></div>'+
                      '</div>'+
                      '<div class="item-subtitle">'+addresses[i]['full_address']+'</div>'+
                      '<div class="item-text">'+ default_address +'</div>'+
                      '</div>'+
                      '</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-right">'+
                      '<a href="#" class="color-orange edit-address" id="'+addresses[i]['useraddress_no']+'">Update</a>'+
                      '<a href="#" class="color-green set-default" id="'+addresses[i]['useraddress_no']+'" >Set as Default</a>'+
                      '</div>'+
                      '</li>';
          }
          
          $('.address-list > ul').empty()
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
        })

        app.on('swipeoutDeleted',function(e)
        {
            app.request({
                url : "{{url('/waybills')}}/"+e.id,
                method : "DELETE",
                data : { _token : "{{csrf_token()}}", reference_no : e.id},
                success: function(data,status,xhr){
                  var toastBottom = app.toast.create({
                    icon: '<i class="icons f7-icons">person_badge_minus</i>',
                    text: 'Contact has been remove',
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
                },
            })
        });

    });

    $('input[name="search"]').keyup(function(){
        var searchText = $(this).val();
        $('ul > li.swipeout').each(function(){
            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            $(this).toggle(showCurrentLi);
        });     
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

    $('#form').submit(function(e){
        if($(this).valid()){
            var form_data = { _token : "{{csrf_token()}}", contact_id: "{{$id}}", use_company: $('input[name="use_company"]').is(':checked') ? 'on' : 'off', lname : $('input[name="lname"]').val(), fname :  $('input[name="fname"]').val(), mname : $('input[name="mname"]').val(), gender: $('select[name="gender"]').val(), email : $('input[name="email"]').val(), contact_no : $('input[name="contact_no"]').val(), company: $('input[name="company"]').val(),business_category_id : $('select[name="business_type"]').find('option:checked').val(), address_label : $('input[name="address_caption"]').val(), street : $('input[name="street"]').val(), barangay : $('input[name="barangay"]').val(), city : $('select[name="city"]').find('option:checked').text(),province : $('input[name="province"]').val(), postal_code : $('input[name="postal_code"]').val()};
            
            app.request({
                  url : "{{url('contact-update')}}",
                  method : "POST",
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
                      
                      toastBottom.open();
                  },
                  error: function(){
                    var toastBottom = app.toast.create({
                        icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                        text: 'Server connection error',
                        position: 'center',
                        closeTimeout: 2000,
                    });
                  },
                  complete: function(xhr,status){
                      if(status==200){
                        app.dialog.close();
                      }
                    }
                })

            
        }
        e.preventDefault();
    });

    $('#add-address').click(function(){
        app.popup.open('.demo-popup-swipe-handler',true);
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

</script>
@endsection