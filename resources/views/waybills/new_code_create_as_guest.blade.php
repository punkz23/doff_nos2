@extends('layouts.theme1')

@section('css')
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css" />
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

@section('content')
<div class="page-header">
    <div class="row">
        <h1>
        <a href="javascript:history.back()"><i class="ace-icon fa fa-backward"></i></a>
            &nbsp;&nbsp;
            Create Booking(Guest)
        </h1>
    </div>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="widget-box">
			<div class="widget-header widget-header-blue widget-header-flat">
				<h4 class="widget-title lighter">Create your booking</h4>		
			</div>

			<div class="widget-body">
				<div class="widget-main">
					<div id="fuelux-wizard-container">
						<div>
							<ul class="steps">
								<li data-step="1" class="active">
									<span class="step">1</span>
									<span class="title">Booking for someone</span>
								</li>

								<li data-step="2">
									<span class="step">2</span>
									<span class="title">Shipper Info</span>
								</li>

								<li data-step="3">
									<span class="step">3</span>
									<span class="title">Consignee Info</span>
								</li>

								<li data-step="4">
									<span class="step">4</span>
									<span class="title">Booking Details</span>
								</li>
								<li data-step="5">
									<span class="step">5</span>
									<span class="title">Terms agreement</span>
								</li>
							</ul>
						</div>

						<hr />

						<div class="step-content pos-rel">
							<div class="step-pane active" data-step="1">
								
							</div>

							<div class="step-pane" data-step="2">
								<h3 class="lighter block green">Enter the shipper information</h3>
								
							</div>

							<div class="step-pane" data-step="3">
								<h3 class="lighter block green">Enter the consignee information</h3>
								
							</div>

							<div class="step-pane" data-step="4">
								
							</div>

							<div class="step-pane" data-step="5">
								
							</div>
            			</div>
                        
					</div>

					<hr />
          
          			<div class="wizard-actions">
						<button class="btn btn-prev">
							<i class="ace-icon fa fa-arrow-left"></i>
							Prev
						</button>

						<button class="btn btn-success btn-next" data-last="Finish">
							Next
							<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
						</button>
					</div>

				</div><!-- /.widget-main -->
			</div><!-- /.widget-body -->
    	</div>
	</div><!-- /.col -->
</div><!-- /.row -->



@endsection

@section('plugins')
<script src="{{asset('theme/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
@endsection

@section('scripts')
<script>


	var validation_1 = $('#form-step-1').validate({
			rules: {
				lname: {
					required: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==0){
							return true;
						}
					}
				},
				fname: {
					required: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==0){
							return true;
						}
					}
				},
				email: {
					required: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==0){
							return true;
						}
					}
				},
				contact_no: {
					required: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==0){
							return true;
						}
					}
				},
			},
			messages: {

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
			},
			invalidHandler: function (form) {
			}

		});

	$('#fuelux-wizard-container')
		.ace_wizard({
		})
		.on('actionclicked.fu.wizard' , function(e, info){
            
			if(info.step == 1) {
				if(!$('#form-step-1').valid()) e.preventDefault();
			}
			if(info.step == 2) {
                if(info.direction=="next"){
                    
                    
                if(!$('#form-step-2').valid()) e.preventDefault();

                $('input[name="declared_value"]').rules("remove");
                }
                    
			}
			if(info.step == 3) {
                if(info.direction=="next")
                    if(!table.$('input, select').valid()) e.preventDefault();
			}
			if(info.step == 4) {
                if(info.direction=="next"){
					if($('#form-step-4 select[name="shipment_type"]').val()=="OTHERS"){
                        $('#form-step-4 input[name="declared_value"]').rules( "add", {
                            required: true,
                            min: 2000
                        });
                    }else{
                        var equalTo = $('#form-step-4 select[name="shipment_type"]').val()=="BREAKABLE" || $('#form-step-4 select[name="shipment_type"]').val()=="PERISHABLE" ? 1000 : 500;
                        $('#form-step-4 input[name="declared_value"]').rules( "add", {
                            required: true,
                            exactEqual: equalTo
                        });
					}
					if(!$('#form-step-4').valid()) e.preventDefault();
					$('#form-step-4 input[name="declared_value"]').rules("remove");
				}
				    
			}
		})
		.on('changed.fu.wizard', function(e, info) {

		})
		.on('finished.fu.wizard', function(e) {
				// $('#modal-loading').modal('show');
				var form_step_1 = {
					type : $('#form-step-1 select[name="who_is_contact_person"]').val(),
					lname : $('#form-step-1 input[name="lname"]').val(),
					fname : $('#form-step-1 input[name="fname"]').val(),
					mname : $('#form-step-1 input[name="mname"]').val(),
					gender : $('#form-step-1 input[name="gender"]').val(),
					email : $('#form-step-1 input[name="email"]').val(),
					contact_no : $('#form-step-1 input[name="contact_no"]').val()
				};

                var form_step_2 = {
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
                };

				var form_step_3 = {
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
				}

				var item_codes=[];
                var unit_codes = [];
                var quantities = [];

                $('#datatable > tbody > tr').each(function(){
                    var td = $(this).find('td');
                    item_codes.push(td.eq(0).find('.description').val());
                    unit_codes.push(td.eq(1).find('.unit').val());
                    quantities.push(td.eq(2).find('.quantity').val());
                });

                var form_step_4 = {
                    payment_type: $('#form-step-2 select[name="payment_type"]').val(),
                    destinationbranch_id: $('#form-step-2 select[name="destinationbranch_id"]').val(),
                    shipment_type: $('#form-step-2 select[name="shipment_type"]').val(),
					declared_value: $('#form-step-2 input[name="declared_value"]').val(),
					item_description : item_codes,
                    unit : unit_codes,
                    quantity : quantities
                };

                var form_step_5 = {
                    agree: $('#form-step-4 input[name="agree"]').is(':checked') ? 1 : 0
                };
               
                $.ajax({
				url: "{{route('waybills.store')}}",
				type: "POST",
				dataType: "JSON",
				data : { _token: "{{csrf_token()}}", step1: form_step_1, step2: form_step_2,step3: form_step_3,step4: form_step_4, step5: form_step_5},
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
			
		})
		.on('stepclick.fu.wizard', function(e){
			//e.preventDefault();//this will prevent clicking and selecting steps
		});

</script>
@endsection
