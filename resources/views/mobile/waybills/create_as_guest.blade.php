@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
    <div class="navbar-bg"></div>
    <div class="navbar-inner">
      <div class="left sliding">
        <a href="{{url('/')}}" class="link back external">
          <i class="icon icon-back"></i>
          <span class="if-not-md">Back</span>
        </a>
      </div>
      <div class="title sliding">CREATE BOOKING(GUEST)</div>
      <div class="right">
        
        <a href="#" class="link icon-only submit">
            <i class="icon f7-icons">cart_badge_plus</i>
        </a>
      </div>
      
    </div>
  </div>

  <div class="toolbar toolbar-bottom tabbar">
    <div class="toolbar-inner">
      <a href="#tab-1" class="tab-link tab-1 tab-link-active">Step 1</a>
      <a href="#tab-2" class="tab-link tab-2">Step 2</a>
      <a href="#tab-3" class="tab-link tab-3">Step 3</a>
      <a href="#tab-4" class="tab-link tab-4">Step 4</a>
      <a href="#tab-5" class="tab-link tab-5">Step 5</a>
    </div>
  </div>

  <div class="tabs">
    <div id="tab-1" class="page-content tab tab-active">
      @include('mobile.waybills.steps.step1')
    </div>
    <div id="tab-2" class="page-content tab">
    @include('mobile.waybills.steps.step2')
    </div>

    <div id="tab-3" class="page-content tab">
    @include('mobile.waybills.steps.step3')
    </div>

    <div id="tab-4" class="page-content tab">
    @include('mobile.waybills.steps.step4')
    </div>

    <div id="tab-5" class="page-content tab">
    @include('mobile.waybills.steps.step5')
    </div>
    
  </div>
  
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        var description = [];
        var unit = [];
        var quantity = [];

        $('select[name="who_is_contact_person"]').change(function(){
            if($(this).val()==1){
                $('.details').attr('style','display:none');
                $('#step-1 input[name="lname"]').removeAttr('required');
                $('#step-1 input[name="fname"]').removeAttr('required');
                // $('input[name="mname"]').removeAttr('required');
                $('#step-1 input[name="gender"]').removeAttr('required');
                $('#step-1 input[name="email"]').removeAttr('required');
                $('#step-1 input[name="contact_no"]').removeAttr('required');
            }else{
                $('.details').removeAttr('style');
                $('#step-1 input[name="lname"]').attr('required',true);
                $('#step-1 input[name="fname"]').attr('required',true);
                // $('input[name="mname"]').attr('required',true);
                $('#step-1 input[name="gender"]').attr('required',true);
                $('#step-1 input[name="email"]').attr('required',true);
                $('#step-1 input[name="contact_no"]').attr('required',true);
            }
        });

        $('select[name="shipment_type"]').change(function(){
            var val = $(this).val();
            $('#step-4 .declared-value').addClass('item-input-focused');
            if(val==="OTHERS"){
                $('#step-4 input[name="declared_value"]').removeAttr('readonly');
                $('#step-4 input[name="declared_value"]').val(2000);
            }else{
                $('#step-4 input[name="declared_amount"]').attr('readonly',true);
                if(val==="BREAKABLE" || val==="PERISHABLE"){
                    $('#step-4 input[name="declared_value"]').val(1000);
                }else{
                    $('#step-4 input[name="declared_value"]').val(500);
                }
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
                complete: function(xhr,status){
                    if(status==200){
                    app.progressbar.hide();
                    }
                }
            });
        });

        $('.cities').change(function(){
            var province_for = $(this).attr('id')=='shipper_city' ? 'shipper_province' : 'consignee_province';
            var postal_for = $(this).attr('id')=='shipper_city' ? 'shipper_postal_code' : 'consignee_postal_code';
            
            $.ajax({
            url : "{{url('/get-city-data')}}/"+$(this).val(),
            type: "GET",
            dataType: "JSON",
            success: function(result){
                var obj = result;
                $('input[name="'+province_for+'"]').val(obj['province']['province_name']);
                $('label#'+province_for).html(obj['province']['province_name']);
                $('input[name="'+postal_for+'"]').val(obj['postal_code']);
                $('label#'+postal_for).html(obj['postal_code']);
            }
            })
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

        $('.submit').click(function(){
            var valid_step1 = 0;
            var contact_person = {
				type : $('#step-1 select[name="who_is_contact_person"]').val(),
				lname : $('#step-1 input[name="lname"]').val(),
				fname : $('#step-1 input[name="fname"]').val(),
				mname : $('#step-1 input[name="mname"]').val(),
				gender : $('#step-1 select[name="gender"]').val(),
				email : $('#step-1 input[name="email"]').val(),
				contact_no : $('#step-1 input[name="contact_no"]').val()
			};

            if(contact_person.type==1){
                valid_step1=1;
            }else{
                if(contact_person.lname!=="" && contact_person.fname!=="" && contact_person.gender!=="" && contact_person.email!=="" && contact_person.contact_no!==""){
                    valid_step1=1;             
                }
            }

            var valid_step2=0;
			var shipper = {
                use_company : $('#step-2 input[name="use_company_shipper"]').is(':checked') ? 1 : 0,
                lname : $('#step-2 input[name="lname"]').val(),
                fname : $('#step-2 input[name="fname"]').val(),
                mname : $('#step-2 input[name="mname"]').val(),
                gender : $('#step-2 select[name="gender"]').val(),
                email : $('#step-2 input[name="email"]').val(),
                company : $('#step-2 input[name="company"]').val(),
                contact_no : $('#step-2 input[name="contact_no"]').val(),
                business_category_id : $('#step-2 select[name="business_category_id"]').find('option:checked').val(),
                address_label : '',
                province : $('#step-2 input[name="shipper_province"]').val(),
                city : $('#step-2 select[name="city"]').find('option:checked').text(),
                barangay : $('#step-2 input[name="barangay"]').val(),
                street : $('#step-2 input[name="street"]').val(),
                postal_code : $('#step-2 input[name="shipper_postal_code"]').val()
			};
            var required_email = contact_person.type==1 ? 1 : 0;
            if(shipper.use_company==1){
                if(shipper.company!=="" && required_email==1 && shipper.contact_no!=="" && shipper.city!=="" ){
                    valid_step2=1;
                }
            }else{
                if(shipper.lname!=="" && shipper.fname!=="" && shipper.gender!=="" && required_email==1 && shipper.contact_no!=="" && shipper.city!=="" ){
                    valid_step2=1;
                }
            }
            

            var valid_step3=0;
			var consignee = {
                use_company : $('#step-3 input[name="use_company_consignee"]').is(':checked') ? 1 : 0,
                lname : $('#step-3 input[name="lname"]').val(),
                fname : $('#step-3 input[name="fname"]').val(),
                mname : $('#step-3 input[name="mname"]').val(),
                gender : $('#step-3 select[name="gender"]').val(),
                email : $('#step-3 input[name="email"]').val(),
                company : $('#step-3 input[name="company"]').val(),
                contact_no : $('#step-3 input[name="contact_no"]').val(),
                business_category_id : $('#step-3 select[name="business_category_id"]').find('option:checked').val(),
                address_label : '',
                province : $('#step-3 input[name="consignee_province"]').val(),
                city : $('#step-3 select[name="city"]').find('option:checked').text(),
                barangay : $('#step-3 input[name="barangay"]').val(),
                street : $('#step-3 input[name="street"]').val(),
                postal_code : $('#step-3 input[name="consignee_postal_code"]').val()
			};
            
            if(consignee.use_company==1){
                if(consignee.company!=="" && consignee.contact_no!=="" && consignee.city!==""){
                    valid_step3=1;
                }
            }else{
                if(consignee.lname!=="" && consignee.fname!=="" && consignee.contact_no!=="" && consignee.city!==""){
                    valid_step3=1;
                }
            }

            

			var waybill = {
                payment_type : $('#step-4 select[name="payment_type"]').val(),
                shipment_type : $('#step-4 select[name="shipment_type"]').val(),
                destinationbranch_id : $('#step-4 select[name="destinationbranch_id"]').val(),
                declared_value : $('#step-4 input[name="declared_value"]').val()
			};

            var valid_step4=0;
            if(waybill.payment_type!=="" && waybill.shipment_type!=="" && waybill.destinationbranch_id!=="" && waybill.declared_value!==""){
                valid_step4=1;
            }
			var shipments = [];
            if($('.shipments-list > ul li').length>0){
                for(var i=0;i<$('.shipments-list > ul li').length;i++){
                    description[i]=$('.shipments-list > ul li select.description').eq(i).find('option:checked').val();
                    unit[i]=$('.shipments-list > ul li select.units').eq(i).find('option:checked').val();
                    quantity[i]=$('.shipments-list > ul li input.quantity').eq(i).val();
                    shipments.push({
                        description: $('.shipments-list > ul li select.description').eq(i).find('option:checked').val(),
                        unit: $('.shipments-list > ul li select.units').eq(i).find('option:checked').val(),
                        quantity :$('.shipments-list > ul li input.quantity').eq(i).val()
                    });
                    valid_step4=1;
                    if($('.shipments-list > ul li select.description').eq(i).find('option:checked').val()==="" || $('.shipments-list > ul li select.units').eq(i).find('option:checked').val()==="" || $('.shipments-list > ul li input.quantity').eq(i).val()===""){
                        valid_step4=0;
                        break;
                    }
                }
            }else{
                valid_step4=0;
            }

            var valid_step5=$('input[name="accept"]').is(':checked')==true ? 1 : 0;
            
            var form_data = { _token: "{{csrf_token()}}", contact_person: contact_person, shipper: shipper, consignee: consignee, waybill: waybill, shipments : shipments };
            
            if(valid_step1==1 && valid_step2==1 && valid_step3==1 && valid_step4==1 && valid_step5==1){
                app.dialog.confirm('Do you want to continue?',function(){
                    app.request({
                        url: "{{route('waybills.create_as_guest_post')}}",
                        type: "POST",
                        dataType: "JSON",
                        data : { _token: "{{csrf_token()}}", contact_person: contact_person, shipper: shipper, consignee: consignee, waybill: waybill, shipments : shipments },
                        beforeSend: function(xhr,status){
                            app.dialog.progress('Please wait while processing and sending you an email of your online booking');
                        },
                        success: function(data,xhr,status){
                            var obj = JSON.parse(data);
                            var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'cart_badge_plus';
                            var toastBottom = app.toast.create({
                                icon: '<i class="icons f7-icons">'+icon+'</i>',
                                text: obj['message'],
                                position: 'center',
                                closeTimeout: 2000,
                            });
                            if(obj.type=='success'){
                                $('.shipments-list > ul').empty();
                                $('.item-after').html('');
                                $('#step-1').trigger('reset');
                                $('#step-2').trigger('reset');
                                $('#step-3').trigger('reset');
                                $('#step-4').trigger('reset');
                                $('#step-5').trigger('reset');
                                window.location.href="{{url('/waybills/printable-reference')}}/"+obj.reference_no;
                            }
                            toastBottom.open();
                            app.dialog.close();
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
                            app.dialog.close();
                        }

                    })
                    
                })
            }
            else{
                var message1 = "";
                var message2 = "";
                var message3 = "";
                var message4 = "";
                var message5 = "";
                if(valid_step1==0){
                    message1=contact_person.type=="" ? "<p style='color:red'>Please choose if who is the contact person in step 1</p>" : "<p style='color:red'>Please complete the contact person information in step 1</p>";
                }
                if(valid_step2==0){
                    message2="<p style='color:red'>Please check shipper information in step 2</p>";
                }
                if(valid_step3==0){
                    message3="<p style='color:red'>Please check consignee information in step 3</p>";
                }
                if(valid_step4==0){
                    message4="<p style='color:red'>Please check booking details in step 4</p>";
                }
                if(valid_step5==0){
                    message5="<p style='color:red'>Please accept our agreement in step 5</p>";
                }

                var dynamicSheet = app.sheet.create({
                content: '<div class="sheet-modal">'+
                            '<div class="toolbar">'+
                                '<div class="toolbar-inner">'+
                                '<div class="left"></div>'+
                                '<div class="right">'+
                                    '<a class="link sheet-close">Close</a>'+
                                '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="sheet-modal-inner">'+
                                '<div class="block">'+
                                '<div class="block-title block-title-large">Ooops!</div>'+
                                message1+
                                message2+
                                message3+
                                message4+
                                message5+
                                
                                '</div>'+
                            '</div>'+
                            '</div>',
                
                // Events
                on: {
                    open: function (sheet) {
                    // console.log('Sheet open');
                    },
                    opened: function (sheet) {
                    // console.log('Sheet opened');
                    },
                }
                });

                dynamicSheet.open();
            }

            

        });

        

        var description = [];
        var unit = [];
        var quantity = [];

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
                                      '<div class="item-title">Description</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</a>'+
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
                                      '<div class="item-title">Quantity</div>'+
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

    });
</script>
@endsection