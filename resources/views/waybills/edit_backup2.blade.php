@extends('layouts.gentelella')

@section('css')
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link href="{{asset('/gentelella')}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<style type="text/css">
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
  text-align:right;
}

.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #3e8e41;
}

#myInput {
  box-sizing: border-box;
  background-image: url('searchicon.png');
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 45px;
  border: none;
  border-bottom: 1px solid #ddd;
}

#myInput:focus {outline: 3px solid #ddd;}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  overflow: auto;
  border: 1px solid #ddd;
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}
</style>
@endsection

@section('bread-crumbs')
<h3>Update Online Booking</h3>
@endsection

@section('content')

<div id="wizard" class="form_wizard wizard_horizontal">
  <ul class="wizard_steps">
    <li>
      <a href="#step-1">
        <span class="step_no">1</span>
        <span class="step_descr">
            Step 1<br />
            <small>Step 1 description</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step-2">
        <span class="step_no">2</span>
        <span class="step_descr">
                          Step 2<br />
                          <small>Step 2 description</small>
                      </span>
      </a>
    </li>
    <li>
      <a href="#step-3">
        <span class="step_no">3</span>
        <span class="step_descr">
                          Step 3<br />
                          <small>Step 3 description</small>
                      </span>
      </a>
    </li>
    <li>
      <a href="#step-4">
        <span class="step_no">4</span>
        <span class="step_descr">
                          Step 4<br />
                          <small>Step 4 description</small>
                      </span>
      </a>
    </li>
  </ul>
  <div id="step-1">
    @include('waybills.steps.pages.logged_in.step1')
  </div>
  <div id="step-2">
    @include('waybills.steps.pages.logged_in.step2')
  </div>
  <div id="step-3">
    @include('waybills.steps.pages.logged_in.step3')
  </div>
  <div id="step-4">
    @include('waybills.steps.pages.logged_in.step4')
  </div>

</div>


    

@endsection

@section('plugins')
<script src="{{asset('/gentelella')}}/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $selectedTR = null;
    $('.select2').css('width','100%').select2({allowClear:true});
    
    
    var validation_1 = $('#form-step-1').validate({
            rules: {
                shipper_id: {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return true;
                        }
                    }
                },
                shipper_contact_no : {required : true},
                shipper_address_id : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return true;
                        }else{
                            if($('select[name="shipper_address_id"]').val()!=="new"){
                                return true;
                            }
                        }
                    }
                },
                "shipper.lname" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }
                },
                "shipper.fname" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }
                },
                "shipper.company" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==true){
                            return true;
                        }
                    }
                },
                "shipper.contact_no" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                "shipper.barangay" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                "shipper.city" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                
                consignee_id: {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return true;
                        }
                    }
                },
                consignee_contact_no : {required : true},
                consignee_address_id : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return true;
                        }else{
                            if($('select[name="consignee_address_id"]').val()!=="new"){
                                return true;
                            }
                        }
                    }
                },

                "consignee.lname" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }
                },
                "consignee.fname" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }
                },
                "consignee.company" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==true){
                            return true;
                        }
                    }
                },
                "consignee.contact_no" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                "consignee.barangay" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                "consignee.city" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },  
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
			}
    });
    
    var validation_2 = $('#form-step-2').validate({
            rules: {
                payment_type : {
                    required: true
                },
                destinationbranch_id : {
                    required: true
                },
                shipment_type : {
                    required: true
                },
                declared_value : {
                    required : true
                }
            },
            messages: {
                discount_coupon : {
                    remote: 'Invalid discount coupon'
                }
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
			}
		});

        $.validator.setDefaults({ 
            ignore: [],
            // any other default options and/or rules
        });

        // var descriptions = $('#form-step-3 .description').rules('add',{ required :true});
        
        jQuery.validator.addMethod("exactEqual", function(value, element, param) {
        return this.optional(element) || value == param;
        }, $.validator.format("Value must be equal to {0}"));

      var validation_3 = $('#form-step-3').validate({
          
        rules: {
                  "item_code[]" : {
                      required: true
                  },
                  "unit[]" : {
                      required: true
                  },
                  "quantity[]" : {
                      required: true,
                      number: true,
                      min : 1
                  },
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
        }
          });

    $('#wizard').smartWizard({
        onLeaveStep: function(obj,context){
            var direction = context.fromStep < context.toStep ? 'forward' : 'backward';
            if(direction=='forward'){
              var toValidate = context.fromStep == 3 ? table.$('input, select') : $('#form-step-'+context.fromStep);
              if(!toValidate.valid()) {
                return false;
              } else{
                $('.buttonFinish').removeAttr('disabled');
                return true;
              }
            }else{
              $('.buttonFinish').attr('disabled','true');
              return true;
            }
        },
        onFinish: function(){
          if(!$('#form-step-1').valid() || !$('#form-step-2').valid() || !table.$('input, select').valid() || !$('#form-step-4').valid()){
            return false;
          }else{
            var form_step_1 = {
                    shipper_id : $('#form-step-1 select[name="shipper_id"]').val(),
                    shipper_address_id : $('#form-step-1 select[name="shipper_address_id"]').val(),
                    shipper_contact_no : $('#form-step-1 input[name="shipper_contact_no"]').val(),
                    shipper_email : $('#form-step-1 input[name="shipper_email"]').val(),
                    shipper: {
                        use_company :  $('#form-step-1 input[name="shipper.use_company"]').is(':checked') ? 1 : 0,
                        lname :  $('#form-step-1 input[name="shipper.lname"]').val(),
                        fname :  $('#form-step-1 input[name="shipper.fname"]').val(),
                        mname :  $('#form-step-1 input[name="shipper.mname"]').val(),
                        company :  $('#form-step-1 input[name="shipper.company"]').val(),
                        email :  $('#form-step-1 input[name="shipper.email"]').val(),
                        contact_no :  $('#form-step-1 input[name="shipper.contact_no"]').val(),
                        business_category_id :  $('#form-step-1 select[name="shipper.business_category_id"]').val(),
                        street :  $('#form-step-1 input[name="shipper.street"]').val(),
                        barangay : $('#form-step-1 input[name="shipper.barangay"]').val(),
                        city :  $('#form-step-1 select[name="shipper.city"]').val(),
                    },
                     

                    consignee_id : $('#form-step-1 select[name="consignee_id"]').val(),
                    consignee_address_id : $('#form-step-1 select[name="consignee_address_id"]').val(),
                    consignee_contact_no : $('#form-step-1 input[name="consignee_contact_no"]').val(),
                    consignee_email : $('#form-step-1 input[name="consignee_email"]').val(),

                    consignee: {
                        use_company :  $('#form-step-1 input[name="consignee.use_company"]').is(':checked') ? 1 : 0,
                        lname :  $('#form-step-1 input[name="consignee.lname"]').val(),
                        fname :  $('#form-step-1 input[name="consignee.fname"]').val(),
                        mname :  $('#form-step-1 input[name="consignee.mname"]').val(),
                        company :  $('#form-step-1 input[name="consignee.company"]').val(),
                        email :  $('#form-step-1 input[name="consignee.email"]').val(),
                        contact_no :  $('#form-step-1 input[name="consignee.contact_no"]').val(),
                        business_category_id :  $('#form-step-1 select[name="consignee.business_category_id"]').val(),
                        street :  $('#form-step-1 input[name="consignee.street"]').val(),
                        barangay :  $('#form-step-1 input[name="consignee.barangay"]').val(),
                        city :  $('#form-step-1 select[name="consignee.city"]').val(),
                    },
                };

                var form_step_2 = {
                    payment_type: $('#form-step-2 select[name="payment_type"]').val(),
                    destinationbranch_id: $('#form-step-2 select[name="destinationbranch_id"]').val(),
                    shipment_type: $('#form-step-2 select[name="shipment_type"]').val(),
                    declared_value: $('#form-step-2 input[name="declared_value"]').val(),
                    discount_coupon: $('#form-step-2 input[name="discount_coupon"]').val(),
                };

                var item_codes=[];
                var unit_codes = [];
                var quantities = [];

                $('#datatable > tbody > tr').each(function(){
                    var td = $(this).find('td');
                    item_codes.push(td.eq(0).find('.description').val());
                    unit_codes.push(td.eq(1).find('.unit').val());
                    quantities.push(td.eq(2).find('.quantity').val());
                });

                var form_step_3 = {
                    item_description : item_codes,
                    unit : unit_codes,
                    quantity : quantities
                };

                var form_step_4 = {
                    agree: $('#form-step-4 input[name="agree"]').is(':checked') ? 1 : 0
                };
               
                $.ajax({
                  url: "{{route('waybills.store')}}",
                  type: "POST",
                  dataType: "JSON",
                  data : { _token: "{{csrf_token()}}", step1: form_step_1, step2: form_step_2,step3: form_step_3,step4: form_step_4},
                  cache: false,
                  success: function(result){
                    
                    swal(result.message, {
                      icon: result.type,
                      title: result.title
                    })
                              .then(function(){
                      
                                  $('#form-step-1').trigger('reset');
                                  $('#form-step-2').trigger('reset');
                                  $('#form-step-3').trigger('reset');
                                  $('#form-step-4').trigger('reset');
                                  $('#form-step-5').trigger('reset');
                                  window.location.href="{{url('/waybills/printable-reference')}}/"+result.key;
                      
                    });
                  },
                  error: function(xhr,status){
                    
                              
                              if(xhr.status==500){
                                  var responseJSON = xhr.responseJSON;
                                  swal(responseJSON.message, {
                                      icon: 'error',
                                      title: 'Ooops!'
                                  });
                              }else if(xhr.status==408){
                                  swal('Please check your internet', {
                                      icon: 'error',
                                      title: 'Connection time-out'
                                  });
                              }
                              else if(xhr.status=422){
                                  var errors = xhr.responseJSON.errors;
                                  var errorHTML = '';
                                  for (var key of Object.keys(errors)) {
                                      // console.log(key + " -> " + errors[key])
                                      errorHTML += "<p> <font color='red'>*</font> "+errors[key]+"</p>"
                                  }

                                  $('#modal-error .modal-body').html(errorHTML);
                                  $('#modal-error').modal('show');
                              }
                  }
                  });
          }
        }

    });
    
    $('.buttonNext').addClass('btn btn-success');
    $('.buttonPrevious').addClass('btn btn-primary');
    $('.buttonFinish').addClass('btn btn-default');

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

    $('.verify').on('click',function(){
      $('input[name="discount_coupon"]').rules( "add", {
          required: true,
          remote: {
              url : "{{url('/discount-coupon-verification')}}",
              type: "POST",
              data : { _token : "{{csrf_token()}}", discount_coupon_no : $('input[name="discount_coupon_no"]').val()}
          }
      });
      $('#form-step-2').validate().element('input[name="discount_coupon"]');
      $('#form-step-2 input[name="discount_coupon"]').rules("remove");
    });

    $('.contacts').on('change',function(e){
      var elementName = e.target.name=="shipper_id" ? "shipper" : "consignee";
      
      if(e.target.value!=="new"){
          var addresses = JSON.parse(e.target.selectedOptions[0].dataset.address);
          var innerHTML = '<option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option><option value="new">--ADD NEW--</option>';
          for(var i=0; i<addresses.length;i++){
              innerHTML = innerHTML + "<option value='"+addresses[i]['useraddress_no']+"'>"+addresses[i]['full_address']+"</option>";
          }
          $('select[name="'+elementName+'_address_id"]').html(innerHTML);
          $('input[name="'+elementName+'_contact_no"]').val(e.target.selectedOptions[0].dataset.contact_no)
          $('input[name="'+elementName+'_email"]').val(e.target.selectedOptions[0].dataset.email)
          $('select[name="'+elementName+'_address_id"]').closest('.form-group').show();
          $('input[name="'+elementName+'_email"]').closest('.form-group').show();
          $('input[name="'+elementName+'_contact_no"]').closest('.form-group').show();
          $('.'+elementName+'-form').hide();
          $('.'+elementName+'-info').hide();
          $('.'+elementName+'-address').hide();
      }else{
          $('select[name="'+elementName+'_address_id"]').closest('.form-group').hide();
          $('input[name="'+elementName+'_contact_no"]').closest('.form-group').hide();
          $('input[name="'+elementName+'_email"]').closest('.form-group').hide();
          $('.'+elementName+'-form').show();
          $('.'+elementName+'-info').show();
          $('.'+elementName+'-address').show();
      }
      var elementAddress = $('select[name="'+elementName+'_address_id"]').next().find('.select2-selection__rendered');
      elementAddress.removeAttr('title');
      elementAddress.empty();
  });

  $('.addresses').on('change',function(e){
      var elementName = e.target.name=="shipper_address_id" ? "shipper" : "consignee";
      if(e.target.value!=="new"){
          $('.'+elementName+'-form').hide();
          $('.'+elementName+'-info').hide();
          $('.'+elementName+'-address').hide();
      }else{
          $('.'+elementName+'-form').show();
          $('.'+elementName+'-info').hide();
          $('.'+elementName+'-address').show();
      }
  })

  $('.use_company').on('change',function(e){
      var parentEl = e.target.name=="shipper.use_company" ? "shipper" : "consignee";
      $('input[name="'+parentEl+'.lname"]').val('');
      $('input[name="'+parentEl+'.fname"]').val('');
      $('input[name="'+parentEl+'.mname"]').val('');
      $('input[name="'+parentEl+'.company"]').val('');
      if($(this).is(':checked')==true){
          $('input[name="'+parentEl+'.lname"]').closest('.form-group').hide();
          $('input[name="'+parentEl+'.fname"]').closest('.form-group').hide();
          $('input[name="'+parentEl+'.mname"]').closest('.form-group').hide();
          $('input[name="'+parentEl+'.company"]').closest('.form-group').show();
      }else{
          $('input[name="'+parentEl+'.lname"]').closest('.form-group').show();
          $('input[name="'+parentEl+'.fname"]').closest('.form-group').show();
          $('input[name="'+parentEl+'.mname"]').closest('.form-group').show();
          $('input[name="'+parentEl+'.company"]').closest('.form-group').hide();
      }
  });

  $('select[name="shipment_type"]').change(function(){
      console.log($(this).val());
      var val = $(this).val()==="OTHERS" ? 2000 : (($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE") ? 1000 : 500);
      var shipment_type_alert = $('.shipment-type-alert');
var content = '';
      $('input[name="declared_value"]').val(val);
      $('.declared-value-display').val(val);
      if($(this).val()==="OTHERS"){
          $('input[name="declared_value"]').attr('type','number');
          $('.declared-value-display').attr('type','hidden');
          content+='<li>Minimum declared value is &#8369; 2,000.00 but the shipper can declare higher valuation.</li>';
  content+='<li>Carriers liability in case of damages & losses is limited to the declared value appearing on the waybill.</li>';
  content+='<li>Insurance will be collected equivalent to 1.2% of declared value plus 12% VAT. This is in addition to freight charges.</li>';
      }else{
          $('input[name="declared_value"]').attr('type','hidden');
          $('.declared-value-display').attr('type','number');
          if($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
    content+='<li>The shipper cannot declare more than  &#8369; 1,000.00 declared value for breakable & perishable cargoes.</li>';
    content+="<li>Should you wish to continue you agree that the carrier's liablity is limited to &#8369; 1000.00 in case of breakage, damages & losses.</li>";
    content+='<li>Insurance will be collected in the amount of &#8369; 12.00 plus 1.2% VAT. This is in addition to freight charges.</li>';
  }
      }
      if($(this).val()==="OTHERS" || $(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
          shipment_type_alert.find('.shipment-type-name').html($(this).val());
          shipment_type_alert.find('ul').html(content);
          shipment_type_alert.show();
      }else{
          shipment_type_alert.hide();
      }
      
  });


  var table = $('#datatable').DataTable({
            bInfo : false,
            bLengthChange: false,
            bFilter : false,
            paging: false,
            columnDefs: [
                {width : '40%' , targets : [0]},
                {width : '30%' , targets : [1]},
                {width : '20%' , targets : [2]},
                {width : '10%' , targets : [2]}
            ]
        });

        table.on('click','.select-itemdesc',function(){
            $tr = $(this).closest('tr');
            $selectedTR=$tr;
            // $('#parentTableIndex').val($tr[0]['_DT_RowIndex']);
        })

        table.on('click','.remove-item',function(e){
            $tr = $(this).closest('tr');
            table.row($tr).remove().draw();
            e.preventDefault();
        })
        var counter = 0;
  $('.add-item').click(function(){

if($('#dynamic-table > tbody > tr:last td select.description').val()!=="" && $('#dynamic-table > tbody > tr:last td select.unit').val()!=="" && $('#dynamic-table > tbody > tr:last td input.quantity').val()!==""){
    if(counter<5){
        counter=counter+1;
        table.row.add([
        '<select class="chosen-select form-control description" name="description['+counter+']" required style="width:100%"><option value="">--Select description--</option>'+"{!! $ddStocks !!}"+'</select>',
        '<select class="chosen-select form-control unit" name="unit['+counter+']" style="width:100%"><option value="" required><option value="">--Select unit--</option>'+"{!! $ddUnits !!}"+'</select>',
        '<input type="number" name="quantity['+counter+']" class="form-control quantity" required  style="width:100%">',
        '<div class="hidden-sm hidden-xs action-buttons"><a class="red remove" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>'
        ]).draw(false);
        // $('.chosen-select').chosen();
        $('.chosen-select').css('width','100%').select2({allowClear:true});
        // alert($('#dynamic-table > tbody > tr:last td input[type=checkbox]').val());
    }
}

});
  $('.add-item').click();

        

        

        

        function fill_datatable_items(filter_item_description=""){
            var tblItems = $('#table-items').DataTable({
                ajax: {
                    url : "{{ url('/stocks') }}",
                    type: "GET",
                    data: {
                        item_description : filter_item_description
                    },
                },
                processing: true,
                // serverSide : true,
                rowId: "stock_no",
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bFilter : false,
                paging : false,
                bLengthChange: false,
                bInfo : false,
                order: [[ 0, "asc" ]],
                columnDefs: [
                    {className: 'item-desc', targets : [0]}
                ],
                columns: [
                    {data : 'stock_description'} 
                ]
            });

            
            tblItems.on('click','.item-desc',function(){
                $tr = $(this).closest('tr');
                if(!$tr.find('td').hasClass('dataTables_empty')){
                    $selectedTR.find('td').eq(0).find('.description').val($tr[0]['id']);
                    $selectedTR.find('td').eq(0).find('.error-portion').remove();
                    $selectedTR.find('td').eq(0).find('.portion-name').html($tr[0]['innerText']+'<br><center><a href="#" style="font-size:10px" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Change Description--</a></center>');
                    $('#modal-item').find('.close').click();
                    $('input[name="item_description"]').val('');
                    $('#table-items > tbody').html('<tr class="odd"><td valign="top" colspan="1" class="dataTables_empty">No data available in table</td></tr>');
                }
                
                
            });
        }

        fill_datatable_items();

        $('#form-search-item').on('submit',function(e){
            $('#table-items').DataTable().destroy();
            fill_datatable_items($(this).find('input[name="item_description"]').val());
            e.preventDefault();
        });

    

    
})

</script>
@endsection