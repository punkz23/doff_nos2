@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
    <div class="navbar-bg"></div>
    <div class="navbar-inner">
      <div class="left sliding">
        <a href="{{route('home')}}" class="link back external">
          <i class="icon icon-back"></i>
          <span class="if-not-md">Back</span>
        </a>
      </div>
      <div class="title sliding">Contacts</div>
      
        
      
      
    </div>
  </div>

  <div class="toolbar toolbar-bottom tabbar">
    <div class="toolbar-inner">
      <a href="#tab-1" class="tab-link tab-link-active">List</a>
      <a href="#tab-2" class="tab-link">New</a>
    </div>
  </div>

  <div class="tabs">
    <div id="tab-1" class="page-content tab tab-active">
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
        <div  class="list media-list contact-list">
            <ul>
                
            </ul>
                
        </div>

      
    </div>
    <div id="tab-2" class="page-content tab">
        <div class="list no-hairlines-md">
        <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Input field with (<font color="red">*</font>) is required</div>
        <form id="form">
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
      url : "{{url('/get-contacts')}}",
      method : "GET",
      beforeSend: function(xhr){
        app.progressbar.show('multi');
      },
      success: function(data,status,xhr){
          var obj = JSON.parse(data);
          var content = '';
          
          for(var i = 0;i<obj.length;i++){
            
            content = content + '<li class="swipeout" id="'+obj[i]['contact_id']+'">'+
                      '<div class="swipeout-content">'+
                      '<a href="#" class="item-link item-content">'+
                      '<div class="item-media">'+
                      '<i class="icon f7-icons">person</i>'+
                      '</div>'+
                      '<div class="item-inner">'+
                      '<div class="item-title-row">'+
                      '<div class="item-title">'+obj[i]['contact_id']+'</div>'+
                      '<div class="item-after"></div>'+
                      '</div>'+
                      '<div class="item-subtitle">'+obj[i]['fileas']+'</div>'+
                      '<div class="item-text">'+obj[i]['email']+'</div>'+
                      '</div>'+
                      '</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-right">'+
                      '<a href="'+"{{url('/contacts')}}/"+obj[i]['contact_id']+"/edit"+'" class="color-orange edit-contact external">Update</a>'+
                      '<a href="#" data-confirm="Are you sure you want to remove this contact?" class="swipeout-delete" id="'+obj[i]['contact_id']+'" >Remove</a>'+
                      '</div>'+
                      '</li>';
          }
          
          $('.contact-list > ul').empty()
          $('.contact-list > ul').append(content);
        
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
                url : "{{url('/contacts')}}/"+e.id,
                method : "DELETE",
                data : { _token : "{{csrf_token()}}", contact_id : e.id},
                beforeSend: function(xhr){
                    app.dialog.progress('Removing contact..');
                },
                success: function(data,status,xhr){
                  if(status==200){
                    app.dialog.close();
                    var toastBottom = app.toast.create({
                      icon: '<i class="icons f7-icons">cart_badge_minus</i>',
                      text: 'Contact has been removed',
                      position: 'center',
                      closeTimeout: 3000,
                    });
                    toastBottom.open();
                  }
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
                var form_data = { _token : "{{csrf_token()}}", use_company: $('input[name="use_company"]').is(':checked') ? 'on' : 'off', lname : $('input[name="lname"]').val(), fname :  $('input[name="fname"]').val(), mname : $('input[name="mname"]').val(), gender : $('select[name="gender"]').val(), email : $('input[name="email"]').val(), contact_no : $('input[name="contact_no"]').val(), company: $('input[name="company"]').val(),business_category_id : $('select[name="business_type"]').find('option:checked').val(), address_label : $('input[name="address_caption"]').val(), street : $('input[name="street"]').val(), barangay : $('input[name="barangay"]').val(), city : $('select[name="city"]').find('option:checked').text(),province : $('input[name="province"]').val(), postal_code : $('input[name="postal_code"]').val()};

                app.request({
                  url : "{{route('contacts.store')}}",
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
                      if(obj.type=='success'){
                        var new_row = obj.data;
                        var content = '<li class="swipeout" id="'+new_row['contact_id']+'">'+
                        '<div class="swipeout-content">'+
                        '<a href="#" class="item-link item-content">'+
                        '<div class="item-media">'+
                        '<i class="icon f7-icons">person</i>'+
                        '</div>'+
                        '<div class="item-inner">'+
                        '<div class="item-title-row">'+
                        '<div class="item-title">'+new_row['contact_id']+'</div>'+
                        '<div class="item-after"></div>'+
                        '</div>'+
                        '<div class="item-subtitle">'+new_row['fileas']+'</div>'+
                        '<div class="item-text">'+new_row['email']+'</div>'+
                        '</div>'+
                        '</a>'+
                        '</div>'+
                        '<div class="swipeout-actions-right">'+
                        '<a href="#" class="color-orange">Update</a>'+
                        '<a href="#" data-confirm="Are you sure you want to remove this contact?" class="swipeout-delete" id="'+new_row['contact_id']+'" >Remove</a>'+
                        '</div>'+
                        '</li>';
                        $('.contact-list > ul').append(content);
                        
                        $('#form').trigger('reset');
                      }
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

</script>
@endsection