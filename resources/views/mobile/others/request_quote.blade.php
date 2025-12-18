@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('home')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">REQUEST FOR QUOTATION</div>
        <div class="right">
          <a class="link popup-open" id="submit">
            <i class="icon f7-icons">location</i>
            Send Request
          </a>
        </div>
      </div>
  </div>
  <div class="page-content">
      <form id="form">
        @csrf
        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <h3 class="text-strong">Request by: </h3>
            </div>
            
          </div>
          <div class="card-content card-content-padding">
            <div class="list no-hairlines-md">
              <ul>
                
                <li class="item-content item-input item-input-outline {{Auth::check() ? 'item-input-with-value' : ''}}">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Lastname</div>
                        <div class="item-input-wrap">
                          <input type="text" name="lname" value="{{Auth::check() ? Auth::user()->contact->lname : ''}}" {{Auth::check() ? 'disabled' : ''}}>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline {{Auth::check() ? 'item-input-with-value' : ''}}">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Firstname : </div>
                        <div class="item-input-wrap">
                          <input type="text" name="fname" value="{{Auth::check() ? Auth::user()->contact->fname : ''}}" {{Auth::check() ? 'disabled' : ''}}>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline {{Auth::check() ? 'item-input-with-value' : ''}}"">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Middlename : </div>
                        <div class="item-input-wrap">
                          <input type="text" name="mname" value="{{Auth::check() ? Auth::user()->contact->mname : ''}}" {{Auth::check() ? 'disabled' : ''}}>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline {{Auth::check() ? 'item-input-with-value' : ''}}">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Contact # : </div>
                        <div class="item-input-wrap">
                          <input type="text" name="contact_no" value="{{Auth::check() ? Auth::user()->contact->contact_no : ''}}">
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline {{Auth::check() ? 'item-input-with-value' : ''}}"">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Email : </div>
                        <div class="item-input-wrap">
                          <input type="email" name="email" value="{{Auth::check() ? Auth::user()->contact->email : ''}}" {{Auth::check() ? 'disabled' : ''}}>
                        </div>
                    </div>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="list no-hairlines-md">
            <ul>
              <li>
                    <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search origin branch">
                      <select name="origin_branch">
                        @foreach($branches as $branch)
                          <option value="{{$branch->branchoffice_no}}">{{$branch->branchoffice_description}}</option>
                        @endforeach
                      </select>
                      <div class="item-content">
                        <div class="item-inner">
                          <div class="item-title">Origin Branch</div>
                        </div>
                      </div>
                  </a>
              </li>

              <li>
                  <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search destination branch">
                      <select name="destination_branch">
                        @foreach($branches as $branch)
                          <option value="{{$branch->branchoffice_no}}">{{$branch->branchoffice_description}}</option>
                        @endforeach
                      </select>
                      <div class="item-content">
                        <div class="item-inner">
                          <div class="item-title">Destination Branch</div>
                        </div>
                      </div>
                  </a>
              </li>
              <li class="item-content item-input item-input-outline">
                  <div class="item-inner">
                      <div class="item-title item-floating-label">Declared Value</div>
                      <div class="item-input-wrap">
                        <input type="number" name="declared_value">
                      </div>
                  </div>
              </li>
            </ul>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <h3 class="text-strong">Delivery</h3>
            </div>
            <div class="right">
              <label class="toggle toggle-init color-blue">
                <input type="checkbox" name="delivery" class="chk-address">
                <span class="toggle-icon"></span>
              </label>
            </div>
          </div>
          <div class="card-content card-content-padding card-content-delivery" style="display:none;">
            <div class="list no-hairlines-md">
              <ul>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Street/Bldg/Others</div>
                        <div class="item-input-wrap">
                          <input type="text" name="street_delivery" disabled>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Barangay</div>
                        <div class="item-input-wrap">
                          <input type="text" name="barangay_delivery" disabled>
                        </div>
                    </div>
                </li>
                <li>
                  <a disabled class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search province/city/postal code" data-virtual-list="true">
                    <select name="city_delivery">
                    <option value="none" selected disabled>--Please select province, city and postal code--</option>
                      @foreach($provinces as $province)
                          <optgroup label="{{$province->province_name}}">
                              @foreach($province->city as $city)
                                  <option value="{{$city->cities_id}}">{{$city->cities_name}},{{$province->province_name}}, {{$city->postal_code}}</option>
                              @endforeach
                          </optgroup>
                      @endforeach
                    </select>
                    <div class="item-content">
                      <div class="item-inner">
                        <div class="item-title">Province/City/Postal Code</div>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <h3 class="text-strong">Pickup</h3>
            </div>
            <div class="right">
              <label class="toggle toggle-init color-blue">
                <input type="checkbox" name="pickup" class="chk-address">
                <span class="toggle-icon"></span>
              </label>
            </div>
          </div>
          <div class="card-content card-content-padding  card-content-pickup" style="display:none;">
            <div class="list no-hairlines-md">
              <ul>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Street/Bldg/Others</div>
                        <div class="item-input-wrap">
                          <input type="text" name="street_pickup" disabled>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Barangay</div>
                        <div class="item-input-wrap">
                          <input type="text" name="barangay_pickup" disabled>
                        </div>
                    </div>
                </li>
                <li>
                  <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search province/city/postal code" data-virtual-list="true">
                    <select name="city_pickup" disabled>
                    <option value="none" selected disabled>--Please select province, city and postal code--</option>
                      @foreach($provinces as $province)
                          <optgroup label="{{$province->province_name}}">
                              @foreach($province->city as $city)
                                  <option value="{{$city->cities_id}}">{{$city->cities_name}},{{$province->province_name}}, {{$city->postal_code}}</option>
                              @endforeach
                          </optgroup>
                      @endforeach
                    </select>
                    <div class="item-content">
                      <div class="item-inner">
                        <div class="item-title">Province/City/Postal Code</div>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <div class="card-title">
              <h3 class="text-strong">Shipments</h3>
            </div>
            <div class="right">
              <a id="add-item">
                <i class="f7-icons">cart_badge_plus</i>
              </a>
            </div>
          </div>
          <div class="card-content card-content-padding">
            <div class="list shipments-list">
              <ul>

              </ul>
            </div>
          </div>
        </div>

      </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function(){

    $('.chk-address').change(function(){
      var type = $(this).attr('name');
      if($(this).is(':checked')==true){
          $('.card-content-'+type).removeAttr('style');
          $('input[name="street_'+type+'"]').removeAttr('disabled');
          $('input[name="barangay_'+type+'"]').removeAttr('disabled');
          $('select[name="city_'+type+'"]').removeAttr('disabled');
          $('input[name="street_'+type+'"]').attr('required',true);
          $('input[name="barangay'+type+'"]').attr('required',true);
          $('select[name="city_'+type+'"]').attr('required',true);
      }else{
        $('.card-content-'+type).attr('style','display:none');
          $('input[name="street_'+type+'"]').attr('disabled',true);
          $('input[name="barangay_'+type+'"]').attr('disabled',true);
          $('select[name="city_'+type+'"]').attr('disabled',true);
          $('input[name="street_'+type+'"]').removeAttr('required');
          $('input[name="barangay_'+type+'"]').removeAttr('required');
          $('select[name="city_'+type+'"]').removeAttr('required');
      }
    });

    $('#add-item').click(function(){
      var index = $('.shipments-list > ul > li').length;
      
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
                                '<div class="item-title">Description</div>'+
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
                                '<div class="item-title">Item name</div>'+
                                '<div class="item-after"><input type="text" name="item_name['+index+']" class="item-name" placeholder="Item name" style="text-align: right;"></div>'+
                              '</div>'+
                          '</div>'+
                        '</div>'+
                        '<div class="col-15" style="padding-top:10px;"></div>'+
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
                              '<div class="item-title">Unit</div>'+
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
                                '<div class="item-title">Quantity</div>'+
                                '<div class="item-after"><input type="text" name="quantity['+index+']" class="quantity" placeholder="Item quantity" style="text-align: right;" disabled></div>'+
                              '</div>'+
                          '</div>'+
                        '</div>'+
                        '<div class="col-15" style="padding-top:10px;"></div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="swipeout-actions-right">'+
                    '<a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete" style="">Delete</a>'+
                    '</div>'+
                    
                      '<div class="card data-table data-table-collapsible data-table-init">'+
                      '<div class="card-header">'+
                      '<div class="data-table-title">Dimension(s)</div>'+
                      '<div class="data-table-actions">'+
                      '<a id='+index+' class="link icon-only add-dimension"><i class="icon f7-icons">plus</i></a>'+
                      '</div>'+
                      '</div>'+
                      '<div class="card-content">'+
                      '<table class="sub-table table-'+index+'">'+
                        '<tbody>'+
                        '<tr>'+
                          '<td><input type="number" placeholder="Qty" class="sub_quantity"></td>'+
                          '<td><select><option selected value="kg">KILOGRAM</option></select></td>'+
                          '<td><input type="number" placeholder="Weight"></td>'+
                          '<td><select><option selected value="centi">CENTIMETER</option></select></td>'+
                          '<td><input type="number" placeholder="Height"></td>'+
                          '<td><input type="number" placeholder="Width"></td>'+
                          '<td><input type="number" placeholder="Length"></td>'+
                          '<td></td>'+
                        '</tr>'+
                        '</tbody>'+
                      '</table>'+
                      '</div>'+
                      '</div>'+
                    
                  '</li>';
      
      if($('.shipments-list > ul li').length<=5){
          var validated = 1;
          var last_li = $('.shipments-list > ul > li:last');
          if(last_li.find('select.description').val()!==null && last_li.find('select.unit').val()!==null && last_li.find('input.quantity').val()!=="") validated=1; else validated=0;
          if(validated===1) $('.shipments-list ul').append(swipeout);
          $('.sub_quantity').on('keyup',function(e){
              var sub_tr =$(this).closest('.sub-table').find('tbody > tr');
              
              var total_quantity = 0;
              for(var i=0; i < sub_tr.length; i++){
                  var myvalue = sub_tr.eq(i).find('.sub_quantity').val()=="" ? 0 : sub_tr.eq(i).find('.sub_quantity').val();
                  total_quantity = total_quantity + parseInt(myvalue);
              }
              
              $('input[name="quantity['+index+']').val(total_quantity);
              e.preventDefault();
          });
          
      }
      
      $('.add-dimension').click(function(e){
        var id = e.currentTarget.id;
        var new_row = '<tr>'+
                          '<td><input type="number" placeholder="Qty" class="sub_quantity"></td>'+
                          '<td><select><option selected value="kg">KILOGRAM</option></select></td>'+
                          '<td><input type="number" placeholder="Weight"></td>'+
                          '<td><select><option selected value="centi">CENTIMETER</option></select></td>'+
                          '<td><input type="number" placeholder="Height"></td>'+
                          '<td><input type="number" placeholder="Width"></td>'+
                          '<td><input type="number" placeholder="Length"></td>'+
                          '<td><a class="remove-subitem color-red"><i class="f7-icons">trash</i></a></td>'+
                        '</tr>';
        var last_row = $('.table-'+id+' > tbody > tr:last > td');
        
        if(last_row.eq(0).find(':first-child').val()!=="" && last_row.eq(2).find(':first-child').val()!=="" &&  last_row.eq(4).find(':first-child').val()!=="" && last_row.eq(5).find(':first-child').val()!=="" && last_row.eq(6).find(':first-child').val()!==""){
          $('.table-'+id+' > tbody').append(new_row);
          $('.sub_quantity').on('keyup',function(e){
              var sub_tr =$(this).closest('.sub-table').find('tbody > tr');
              
              var total_quantity = 0;
              for(var i=0; i < sub_tr.length; i++){
                  var myvalue = sub_tr.eq(i).find('.sub_quantity').val()=="" ? 0 : sub_tr.eq(i).find('.sub_quantity').val();
                  total_quantity = total_quantity + parseInt(myvalue);
              }
              
              $('input[name="quantity['+id+']').val(total_quantity);
              e.preventDefault();
          });
        }

        $('.remove-subitem').click(function(e){
          $(this).closest('tr').remove();
        })
      });

    });

    $('#submit').click(function(e){
      var form = $('#form');
      

      var subTable = [];
      var item_description =[];
      var item_name = [];
      var unit =[];
      var unit_description = [];
      for(var i =0; i< $('.shipments-list > ul > li').length; i++){
        var current_row_tds = $('.shipments-list > ul > li').eq(i).find('.row');
        
        var subTr = [];
        for(var j=0; j<$('.table-'+i+' > tbody > tr').length; j++){
            var td = $('.table-'+i+' > tbody > tr').eq(j).find('td');
            subTr.push({
                quantity : td.eq(0).find(':first-child').val(),
                unit_weight : td.eq(1).find(':first-child').val(),
                weight : td.eq(2).find(':first-child').val(),
                unit_dimension : td.eq(3).find(':first-child').val(),
                height : td.eq(4).find(':first-child').val(),
                width: td.eq(5).find(':first-child').val(),
                length : td.eq(6).find(':first-child').val()
            });
        }
        item_description.push(current_row_tds.eq(0).find('select').val());
        item_name.push(current_row_tds.eq(1).find('input').val());
        unit.push(current_row_tds.eq(2).find('select').val());
        subTable.push({
            item_code : current_row_tds.eq(0).find('select').val(),
            item_name : current_row_tds.eq(1).find('input').val(),
            unit_code : current_row_tds.eq(2).find('select').val(),
            shipments : subTr
        });
    }

    

    

      var form_data = {
        _token : "{{csrf_token()}}",
        lname : $('#form input[name="lname"]').val(),
        fname : $('#form input[name="fname"]').val(),
        mname : $('#form input[name="mname"]').val(),
        contact_no : $('#form input[name="contact_no"]').val(),
        email : $('#form input[name="email"]').val(),
        origin_branch : $('#form select[name="origin_branch"]').find('option:checked').val(),
        destination_branch : $('#form select[name="destination_branch"]').find('option:checked').val(),
        declared_value : $('#form input[name="declared_value"]').val(),
        street_delivery: $('input[name="delivery"]').is(':checked') ? $('input[name="street_delivery"]').val() : '',
        barangay_delivery: $('input[name="delivery"]').is(':checked') ? $('input[name="barangay_delivery"]').val() : '',
        city_delivery: $('input[name="delivery"]').is(':checked') ? $('#form select[name="city_delivery"]').find('option:checked').text() : '',
        item_description: item_description,
        item_name : item_name,
        unit: unit,
        street_pickup: $('input[name="pickup"]').is(':checked') ? $('input[name="street_pickup"]').val() : '',
        barangay_pickup: $('input[name="pickup"]').is(':checked') ? $('input[name="barangay_pickup"]').val() : '',
        city_pickup: $('input[name="pickup"]').is(':checked') ? $('#form select[name="city_pickup"]').find('option:checked').text() : '',
        sub_table : JSON.stringify(subTable)
      };

      

      if(form_data['contact_no']=="" || form_data["email"]=="" || form_data['origin_branch']=="" || form_data['destination_branch']=="" || form_data['declared_value']=="" || isNaN(form_data['declared_value'])){
        var toastBottom = app.toast.create({
          icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
          text: 'Please fill required fields',
          position: 'center',
          closeTimeout: 2000,
        });
        
        toastBottom.open();
      }else{
        app.request({
          url : "{{route('requests.quotation')}}",
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
                $('#form').trigger('reset');
                setTimeout(function(){
                  window.location.href="{{route('home')}}";
                },1500);
              }
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
          complete: function(xhr,status){
              if(status==200){
                app.dialog.close();
              }
          }
        })
      }

      

    });

  })
  
</script>
@endsection