@extends('layouts.theme')

@section('css')
<!-- page specific plugin styles -->
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />
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


<div class="row">
	<div class="col-xs-12">

		<div class="widget-box">
			<div class="widget-header widget-header-blue widget-header-flat">
				<h4 class="widget-title lighter"><a href="{{url('/')}}"><i class="ace-icon fa fa-backward"></i></a> Create your booking (Guest)</h4>		
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
								@include('waybills.steps.pages.guest.step1')
							</div>

							<div class="step-pane" data-step="2">
								<h3 class="lighter block green">Enter the shipper information</h3>
								@include('waybills.steps.pages.guest.step2')
							</div>

							<div class="step-pane" data-step="3">
								<h3 class="lighter block green">Enter the consignee information</h3>
								@include('waybills.steps.pages.guest.step3')
							</div>

							<div class="step-pane" data-step="4">
								@include('waybills.steps.pages.guest.step4')
							</div>

							<div class="step-pane" data-step="5">
								@include('waybills.steps.pages.guest.step5')
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

<div class="modal fade" id="modal-loading" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-md">
        <div class="modal-content ">
			<div class="modal-body">
				<center>
					<h1><i class="ace-icon fa fa-spinner fa-spin orange bigger-220"></i><span class="bigger-220"></h1>
					<h2>Please wait while processing and sending your booking to your email</h2>
				</center>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-error" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:red;"> <i class="ace-icon fa fa-exclamation-triangle bigger-130"></i> Please check</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
@endsection

@section('scripts')
<script>
	$(document).ready(function(){

		$('.select2').css('width','100%').select2({allowClear:true});

		$('select[name="who_is_contact_person"]').change(function(){
			if($(this).val()==1){
				$('#form-step-1 input[name="lname"]').closest('.form-group').hide();
				$('#form-step-1 input[name="fname"]').closest('.form-group').hide();
				$('#form-step-1 input[name="mname"]').closest('.form-group').hide();
				$('#form-step-1 input[name="gender"]').closest('.form-group').hide();
				$('#form-step-1 input[name="email"]').closest('.form-group').hide();
				$('#form-step-1 input[name="contact_no"]').closest('.form-group').hide();
				$('#form-step-2 input[name="email"]').closest('.form-group').find('label').html('Email: <font color="red">*</font>');
			}else{
				$('#form-step-1 input[name="lname"]').closest('.form-group').show();
				$('#form-step-1 input[name="fname"]').closest('.form-group').show();
				$('#form-step-1 input[name="mname"]').closest('.form-group').show();
				$('#form-step-1 input[name="gender"]').closest('.form-group').show();
				$('#form-step-1 input[name="email"]').closest('.form-group').show();
				$('#form-step-1 input[name="contact_no"]').closest('.form-group').show();
				$('#form-step-2 input[name="email"]').closest('.form-group').find('label').html('Email:');
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

		$('select[name="shipment_type"]').change(function(){
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
			shipment_type_alert.find('ul').html(content);
			shipment_type_alert.find('.shipment-type-name').html($(this).val());
			if($(this).val()==="OTHERS" || $(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
				shipment_type_alert.show();
			}else{
				shipment_type_alert.hide();
			}
        });

		var validation_1 = $('#form-step-1').validate({
			rules: {
				who_is_contact_person : {
					required: true
				},
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
		


		$('.use_company').change(function(){
			var form = $(this).closest('form');
			var lastname = form.find('input[name="lname"]');
			var firstname = form.find('input[name="fname"]');
			var middlename = form.find('input[name="mname"]');
			var company = form.find('input[name="company"]');
			lastname.closest('.form-group').removeClass('has-error');
			firstname.closest('.form-group').removeClass('has-error');
			company.closest('.form-group').removeClass('has-error');
			lastname.closest('.form-group').find('.error').remove();
			firstname.closest('.form-group').find('.error').remove();
			company.closest('.form-group').find('.error').remove();
			if($(this).is(':checked')==true){
				company.closest('.form-group').show();
				lastname.closest('.form-group').hide();
				firstname.closest('.form-group').hide();
				middlename.closest('.form-group').hide();
			}else{
				company.closest('.form-group').hide();
				lastname.closest('.form-group').show();
				firstname.closest('.form-group').show();
				middlename.closest('.form-group').show();
			}
		});

		var validation_2 = $('#form-step-2').validate({
			rules: { 
				lname: {
					required: function(){
                        if(!$('#form-step-2 input[name="use_company"]').is(':checked')){
                            return true;
                        }
                    }
				},
				fname: {
					required: function(){
						if(!$('#form-step-2 input[name="use_company"]').is(':checked')){
							return true;
						}
					}
				},
				company: {
					required: function(){
						if($('#form-step-2 input[name="use_company"]').is(':checked')){
							return true;
						}
					}
				},
				email: {
					email: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==1){
							return true;
						}
					},
					required: function(){
						if($('#form-step-1 select[name="who_is_contact_person"]').val()==1){
							return true;
						}
					}
				},		
				contact_no: {
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
				email: {
					email: "Please provide a valid email.",
				},
				city: "Please choose city",
				barangay: "Please provide barangay"
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

		var validation_3 = $('#form-step-3').validate({
			rules: { 
				lname: {
					required: function(){
                        if(!$('#form-step-3 input[name="use_company"]').is(':checked')){
                            return true;
                        }
                    }
				},
				fname: {
					required: function(){
						if(!$('#form-step-3 input[name="use_company"]').is(':checked')){
							return true;
						}
					}
				},
				company: {
					required: function(){
						if($('#form-step-3 input[name="use_company"]').is(':checked')){
							return true;
						}
					}
				},		
				contact_no: {
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
				email: {
					email: "Please provide a valid email.",
				},
				city: "Please choose city",
				barangay: "Please provide barangay"
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
		
		jQuery.validator.addMethod("exactEqual", function(value, element, param) {
        return this.optional(element) || value == param;
        }, $.validator.format("Value must be equal to {0}"));

		var validation_4 = $('#form-step-4').validate({
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
                },
                "item_code[]" : {
                    required: true
                },
                "unit_code[]" : {
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

		$('.add-item').on('click',function(){
            var rows_count = table.rows().count();
            // if(rows_count<5)
            var validated = 0;
            console.log(rows_count);
            if(rows_count==0){
                validated=1;
            }
            if(rows_count>0 && rows_count<5){
                var lastRow = $('#datatable > tbody > tr:last > td');
                
                var field1 = lastRow.eq(0).find('.description').val()!=="" ? 1 : 0;
                var field2 = lastRow.eq(1).find('.unit').valid() ? 1 : 0;
                var field3 = lastRow.eq(2).find('.quantity').valid() ? 1 : 0;

                if(field1==0){
                    lastRow.eq(0).find('.error-portion').html('Please provide item descrption');
                }

                if(field1==1 && field2==1 && field3==1){
                    validated=1;
                    lastRow.eq(0).find('.form-group').removeClass('has-error');
                    lastRow.eq(0).find('.form-group').removeClass('has-error')
                    lastRow.eq(1).find('.form-group').removeClass('has-error')
                }else{
                    validated=0;
                }
            }
            
            if(validated==1){
                var delete_btn = rows_count>0 ? '<a href="#" class="btn btn-sm btn-danger remove-item"><i class="ace-icon fa fa-trash"></i></a>' : '';
                table.row.add([
                    '<div class="row"><div class="col-12"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="form-group"><center><div class="portion-name"><a href="#" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Select Description--</a></div><div class="error-portion"></div></center><input type="hidden" name="item_code[]" class="form-control description" required></div></div></div></div>',
                    '<div class="row"><div class="form-group"><select name="unit_code[]" class="form-control unit select2">'+"{!! $ddUnits !!}"+'</select></div></div>',
                    '<div class="row"><div class="form-group"><input type="number" class="form-control quantity" name="quantity[]"></div></div>',
                    '<div class="row">'+delete_btn+'</div>'
                ]).draw(false);
                $('.select2').css('width','100%').select2({allowClear:true});
            }  
        });

        $('.add-item').click();


		$('#fuelux-wizard-container')
		.ace_wizard({
		})
		.on('actionclicked.fu.wizard' , function(e, info){
            
			if(info.step == 1) {
                if(info.direction=="next")
					if(!$('#form-step-1').valid()) e.preventDefault();
			}

			if(info.step == 2) {
                if(info.direction=="next")
					if(!$('#form-step-2').valid()) e.preventDefault();
			}

			if(info.step == 3) {
                if(info.direction=="next")
					if(!$('#form-step-3').valid()) e.preventDefault();
			}

			if(info.step == 4) {
				if(info.direction=="next"){
                    if($('select[name="shipment_type"]').val()=="OTHERS"){
                        $('input[name="declared_value"]').rules( "add", {
                            required: true,
                            min: 2000
                        });
                    }else{
                        var equalTo = $('select[name="shipment_type"]').val()=="BREAKABLE" || $('select[name="shipment_type"]').val()=="PERISHABLE" ? 1000 : 500;
                        $('input[name="declared_value"]').rules( "add", {
                            required: true,
                            exactEqual: equalTo
                        });
                    }

					$('input[name="quantity[]"]').rules( "add", {
                            required: true,
                            number: true
                    });
                    
					if(!$('#form-step-4').valid() || !table.$('input, select').valid()) e.preventDefault();

					$('input[name="declared_value"]').rules("remove");
					$('input[name="quantity[]"]').rules("remove");
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
					use_company :  $('#form-step-2 input[name="use_company"]').is(':checked') ? 1 : 0,
					lname :  $('#form-step-2 input[name="lname"]').val(),
					fname :  $('#form-step-2 input[name="fname"]').val(),
					mname :  $('#form-step-2 input[name="mname"]').val(),
					company :  $('#form-step-2 input[name="company"]').val(),
					email :  $('#form-step-2 input[name="email"]').val(),
					contact_no :  $('#form-step-2 input[name="contact_no"]').val(),
					business_category_id :  $('#form-step-2 select[name="business_category_id"]').val(),
					street :  $('#form-step-2 input[name="street"]').val(),
					barangay : $('#form-step-2 input[name="barangay"]').val(),
					city :  $('#form-step-2 select[name="city"]').val(),
                };

				var form_step_3 = {
					use_company :  $('#form-step-3 input[name="use_company"]').is(':checked') ? 1 : 0,
					lname :  $('#form-step-3 input[name="lname"]').val(),
					fname :  $('#form-step-3 input[name="fname"]').val(),
					mname :  $('#form-step-3 input[name="mname"]').val(),
					company :  $('#form-step-3 input[name="company"]').val(),
					email :  $('#form-step-3 input[name="email"]').val(),
					contact_no :  $('#form-step-3 input[name="contact_no"]').val(),
					business_category_id :  $('#form-step-3 select[name="business_category_id"]').val(),
					street :  $('#form-step-3 input[name="street"]').val(),
					barangay :  $('#form-step-3 input[name="barangay"]').val(),
					city :  $('#form-step-3 select[name="city"]').val(),
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
                    payment_type: $('#form-step-4 select[name="payment_type"]').val(),
                    destinationbranch_id: $('#form-step-4 select[name="destinationbranch_id"]').val(),
                    shipment_type: $('#form-step-4 select[name="shipment_type"]').val(),
					declared_value: $('#form-step-4 input[name="declared_value"]').val(),
					item_description : item_codes,
                    unit : unit_codes,
                    quantity : quantities
                };

                var form_step_5 = {
                    agree: $('#form-step-5 input[name="agree"]').is(':checked') ? 1 : 0
                };
               
                $.ajax({
				url: "{{route('waybills.create_as_guest_post')}}",
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
	});
</script>
@endsection
