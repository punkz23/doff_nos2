<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Book as Guest - {{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset(env('APP_IMG'))}}" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="{{asset('/regna/fonts/google_font.css')}}" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link href="{{asset('/regna')}}/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/aos/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />

  <!-- Template Main CSS File -->
  <link href="{{asset('/regna')}}/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v10.0" nonce="t0A2wSJd"></script>
  <link href="{{asset('/css/fb.css')}}" rel="stylesheet">

  <link rel="stylesheet" href="{{asset('/gentelella')}}/vendors/additional/jquery-ui.css">
  <script src="{{asset('/gentelella')}}/vendors/additional/jquery-1.9.1.js"></script>
  <script src="{{asset('/gentelella')}}/vendors/additional/jquery-ui.js"></script>

  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>

.disabled .step_no{
	background: #ccc;
}
.buttonDisabled {
	display:none;
}
.container .unselect {
	display:none;
}
.form_wizard .stepContainer {
  display: block;
  position: relative;
  margin: 0;
  padding: 0;
  border: 0 solid #CCC;
  overflow-x: hidden;
}

.wizard_horizontal ul.wizard_steps {
  display: table;
  list-style: none;
  position: relative;
  width: 100%;
  margin: 0 0 20px;
}

.wizard_horizontal ul.wizard_steps li {
  display: table-cell;
  text-align: center;
}

.wizard_horizontal ul.wizard_steps li a, .wizard_horizontal ul.wizard_steps li:hover {
  display: block;
  position: relative;
  -moz-opacity: 1;
  filter: alpha(opacity= 100);
  opacity: 1;
  color: #666;
}

.wizard_horizontal ul.wizard_steps li a:before {
  content: "";
  position: absolute;
  height: 4px;
  background: #ccc;
  top: 20px;
  width: 100%;
  z-index: 4;
  left: 0;
}

.wizard_horizontal ul.wizard_steps li a.disabled .step_no {
  background: #ccc;
}

.wizard_horizontal ul.wizard_steps li a .step_no {
  width: 40px;
  height: 40px;
  line-height: 40px;
  border-radius: 100px;
  display: block;
  margin: 0 auto 5px;
  font-size: 16px;
  text-align: center;
  position: relative;
  z-index: 5;
}

.wizard_horizontal ul.wizard_steps li a.selected:before, .step_no {
  background: #34495E;
  color: #fff;
}

.wizard_horizontal ul.wizard_steps li a.done:before, .wizard_horizontal ul.wizard_steps li a.done .step_no {
  background: #1ABB9C;
  color: #fff;
}

.wizard_horizontal ul.wizard_steps li:first-child a:before {
  left: 50%;
}

.wizard_horizontal ul.wizard_steps li:last-child a:before {
  right: 50%;
  width: 50%;
  left: auto;
}

.wizard_verticle .stepContainer {
  width: 80%;
  float: left;
  padding: 0 10px;
}

.actionBar {
  width: 100%;
  border-top: 1px solid #ddd;
  padding: 10px 5px;
  text-align: right;
  margin-top: 10px;
}

.actionBar .buttonDisabled {
  cursor: not-allowed;
  pointer-events: none;
  opacity: .65;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
  box-shadow: none;
}

.actionBar a {
  margin: 0 3px;
}

.wizard_verticle .wizard_content {
  width: 80%;
  float: left;
  padding-left: 20px;
}

.wizard_verticle ul.wizard_steps {
  display: table;
  list-style: none;
  position: relative;
  width: 20%;
  float: left;
  margin: 0 0 20px;
}

.wizard_verticle ul.wizard_steps li {
  display: list-item;
  text-align: center;
}

.wizard_verticle ul.wizard_steps li a {
  height: 80px;
}

.wizard_verticle ul.wizard_steps li a:first-child {
  margin-top: 20px;
}

.wizard_verticle ul.wizard_steps li a, .wizard_verticle ul.wizard_steps li:hover {
  display: block;
  position: relative;
  -moz-opacity: 1;
  filter: alpha(opacity= 100);
  opacity: 1;
  color: #666;
}

.wizard_verticle ul.wizard_steps li a:before {
  content: "";
  position: absolute;
  height: 100%;
  background: #ccc;
  top: 20px;
  width: 4px;
  z-index: 4;
  left: 49%;
}

.wizard_verticle ul.wizard_steps li a.disabled .step_no {
  background: #ccc;
}

.wizard_verticle ul.wizard_steps li a .step_no {
  width: 40px;
  height: 40px;
  line-height: 40px;
  border-radius: 100px;
  display: block;
  margin: 0 auto 5px;
  font-size: 16px;
  text-align: center;
  position: relative;
  z-index: 5;
}

.wizard_verticle ul.wizard_steps li a.selected:before, .step_no {
  background: #34495E;
  color: #fff;
}

.wizard_verticle ul.wizard_steps li a.done:before, .wizard_verticle ul.wizard_steps li a.done .step_no {
  background: #1ABB9C;
  color: #fff;
}

.wizard_verticle ul.wizard_steps li:first-child a:before {
  left: 49%;
}

.wizard_verticle ul.wizard_steps li:last-child a:before {
  left: 49%;
  left: auto;
  width: 0;
}

.form_wizard .loader {
  display: none;
}

.form_wizard .msgBox {
  display: none;
}


input.mobile_no::-webkit-outer-spin-button,
input.mobile_no::-webkit-inner-spin-button {
-webkit-appearance: none;
margin: 0;
}

/* Firefox */
.mobile_no {
-moz-appearance: textfield;
}

.ui-highlight .ui-state-default{
    background: #82E0AA !important;
    border-color: #82E0AA !important;
    color: white !important;
}

.ui-highlight2 .ui-state-default{
    background: #7FB3D5 !important;
    border-color: #7FB3D5 !important;
    color: white !important;
}

.ui-highlight3 .ui-state-default{
    background: #CD6155 !important;
    border-color: #CD6155 !important;
    color: white !important;
}
.ui-highlight4 .ui-state-default{
    background: #F8C471  !important;
    border-color: #F8C471  !important;
    color: white !important;
}
    

</style>
<body >

  <!-- ======= Header ======= -->
  <header id="header" style="background-color:#2980B9;">
    <div class="container" >

      <div id="logo" class="pull-left">
        <a href="#hero">
          <!-- <img src="about:blank" class="company-logo-with-name" height="50px" alt=""> -->
          <img src="{{asset('/images/daily overland standard logo for web 280x60.png')}}" style="margin-top:-15px;margin-left:-100px;" height="60px" width="280px" alt="">
          
        </a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>

      @include('navbar')
    </div>
  </header><!-- End Header -->
  
  <main id="main" style=" position: relative;min-height: 70vh;">

    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <br><h2 style="text-align:center;"><i class="fa fa-user"></i> Book as Guest</h2>
         
        </div>

      </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page pt-4">
      <div class="container">
          <br><br>
		  <div id="wizard"  class="form_wizard wizard_horizontal">
			<ul class="wizard_steps anchor">
      <li>
				<a href="#step1" class="step-link selected">
					<span class="step_no">1</span>
					<span class="step_descr">
						Step 1<br />
						<small>Contact Person</small>
					</span>
				</a>
				</li>
				<li>
				<a href="#step2" class="step-link disabled">
					<span class="step_no">2</span>
					<span class="step_descr">
						Step 2<br />
						<small>Shipper Information</small>
					</span>
				</a>
				</li>
				<li>
				<a href="#step3" class="step-link disabled">
					<span class="step_no">3</span>
					<span class="step_descr">
						Step 3<br />
						<small>Consignee Information</small>
					</span>
				</a>
				</li>
				<li>
				<a href="#step4" class="step-link disabled">
					<span class="step_no">4</span>
					<span class="step_descr">
						Step 4<br />
						<small>Booking Details</small>
					</span>
				</a>
				</li>
				<li>
				<a href="#step5" class="step-link disabled">
					<span class="step_no">5</span>
					<span class="step_descr">
						Step 5<br />
						<small>Terms and Condition</small>
					</span>
				</a>
				</li>
			</ul>
			<div class="container">
				<div id="step-1" class="content start selected">
					@include('waybills.steps.pages.guest.step1')
				</div>
				<div id="step-2" class="content unselect">
					@include('waybills.steps.pages.guest.step2')
				</div>
				<div id="step-3" class="content unselect">
					@include('waybills.steps.pages.guest.step3')
				</div>
				<div id="step-4" class="content unselect">
					@include('waybills.steps.pages.guest.step4')
				</div>
				<div id="step-5" class="content end unselect">
					@include('waybills.steps.pages.guest.step5')
				</div>
			</div>
			<div class="actionBar">
				<div class="msgBox">
					<div class="content"></div>
					<a href="#" class="close">X</a>
				</div>
				<div class="loader">
					Loading
				</div>
				<button class="buttonPrevious buttonDisabled btn btn-default">Previous</button>
				<button class="buttonNext btn btn-primary">Next</button>
				<button class="buttonFinish buttonDisabled btn btn-success">Finish</button>
			</div>
		</div>
		</div>
    </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" >
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Regna</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Regna
      -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="modal-note" class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel2"><i class="ace-icon fa fa-file-text bigger-130"></i> Note</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:justify">
          Before you enter the barangay where our Manila Branch is located, the barangay officials will facilitate two (2) queuing lines for our transactions, one of which is for those who’ve booked online. For accommodation, kindly present the online booking to our Manila Branch employee giving out the queuing numbers.</p> 
          <p style="text-align:justify">
          There will be separate lane to those who’ve booked online. However, it must be taken into account that our online booking service is not used for appointments, reservations or whatever of the sort as our transactions will be on a “first-come, first-serve” basis. Online booking is only valid for seven (7) days. We advised our customers who have opted to avail Lalamove or any other transport service providers to have their designated couriers comply with the queuing process from start to finish. Remind them not to leave your shipments behind if transaction is still on queue.
          </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success proceed">Submit Booking</button>
        </div>

        </div>
    </div>
</div>

<div id="modal-error" class="modal fade bs-example-modal-sm in" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel2">Oooops!</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:justify">
          Please accept terms and condition.</p> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>

        </div>
    </div>
</div>

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

 @include('fb')
  <!-- Vendor JS Files -->
  <!--script src="{{asset('/regna')}}/vendor/jquery/jquery.min.js"></script-->
  <script src="{{asset('/regna')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/php-email-form/validate.js"></script>
  <script src="{{asset('/regna')}}/vendor/counterup/counterup.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/superfish/superfish.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/hoverIntent/hoverIntent.js"></script>
  <script src="{{asset('/regna')}}/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/venobox/venobox.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/regna')}}/js/main.js"></script>
  <script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   --}}
   <script src="{{asset('/js')}}/sweetalert2.js"></script>
  <script src="{{asset('/theme/js/select2.min.js')}}"></script>
  
  <script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "142972375887115");
    chatbox.setAttribute("attribution", "biz_inbox");
    window.fbAsyncInit = function() {
      FB.init({
        xfbml            : true,
        version          : 'v11.0'
      });
    };
  
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  
  <script>
      $(document).ready(function(){
        $selectedTR=null;

        $('.select2').css('width','100%').select2({allowClear:true});

        $('select[name="who_is_contact_person"]').change(function(){
          
          if($(this).val()==1){
            $('#form-step-1 input[name="lname"]').closest('.form-group').attr('hidden',true);
            $('#form-step-1 input[name="fname"]').closest('.form-group').attr('hidden',true);
            $('#form-step-1 input[name="mname"]').closest('.form-group').attr('hidden',true);
            $('#form-step-1 input[name="gender"]').closest('.form-group').attr('hidden',true);
            $('#form-step-1 input[name="email"]').closest('.form-group').attr('hidden',true);
            $('#form-step-1 input[name="contact_no"]').closest('.form-group').attr('hidden',true);
            $('#form-step-2 input[name="email"]').closest('.form-group').find('label').html('Email: <font color="red">*</font>');
          }else{
            $('#form-step-1 input[name="lname"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-1 input[name="fname"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-1 input[name="mname"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-1 input[name="gender"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-1 input[name="email"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-1 input[name="contact_no"]').closest('.form-group').removeAttr('hidden');
            $('#form-step-2 input[name="email"]').closest('.form-group').find('label').html('Email:');
          }
        });

        $('.cities').on('change',function(){
            
            $form = $(this).closest('form');
            // $id = $(this).find('option:selected').data('postal_code');
            $id=$(this).val();
            $.ajax({
                url: "{{url('/get-sector')}}/"+$id,
                type: "GET",
                success: function(data){
                    $select = $form.find('select[name="barangay"]');
                    $select.html('<option value="none" selected disabled>--Please select barangay--</option>');
                    $.each(data.data,function(){
                        $select.append('<option value="'+this.sectorate_no+'" data-sector_no="'+this.sectorate_no+'">'+this.barangay+'</option>');
                    });
                    
                }
            });
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
            $('#modal-item').modal('show');
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

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    $('#form-step-2').find('input[name="email"]').on('change keyup',function(e){
      if(e.target.value!==""){
        if(validateEmail(e.target.value)==true){
          if($('div.subscription').length==0){
            $(this).closest('div.form-group').after('<div class="form-group subscription"><label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label><div class="col-md-6 col-sm-6 col-xs-12"><input name="subscribe" class="ace ace-switch ace-switch-2" type="checkbox" /><span class="lbl">&nbsp;Subscribe to newsletter</span></div></div>');
          }
        }
      }else{
        if($('div.subscription').length>0){
          $('div.subscription').remove();
        }
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
        mname: {
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
        mname: {
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
                mode_payment: {
                    required: function(){
                    if($('#form-step-4 select[name="payment_type"]').val()=='CI'){
                        return true;
                    }
                    }
                },
                mode_payment_email: {
                  email : function(){
                        if
                        (   $('#form-step-4 select[name="payment_type"]').val()=='CI' 
                            && 
                            $('#form-step-4 input[name="mode_payment_os"]').is(':checked')
                        ){
                        return true;
                        }
                    },                  
                    required: function(){
                        if
                        (   $('#form-step-4 select[name="payment_type"]').val()=='CI' 
                            && 
                            $('#form-step-4 input[name="mode_payment_os"]').is(':checked')
                        ){
                        return true;
                        }
                    }
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
                pu_province: {
                  required: function(){
                    if($('#form-step-4 input[name="pu_checkbox"]').is(':checked')){
                      return true;
                    }
                  }
                },
                del_province: {
                  required: function(){
                    if($('#form-step-4 input[name="del_checkbox"]').is(':checked')){
                      return true;
                    }
                  }
                },
                pu_city: {
                  required: function(){
                    if($('#form-step-4 input[name="pu_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                pu_date: {
                  required: function(){
                    if($('#form-step-4 input[name="pu_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                del_city: {
                  required: function(){
                    if($('#form-step-4 input[name="del_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                pu_sector: {
                  required: function(){
                    if($('#form-step-4 input[name="pu_checkbox"]').is(':checked')){
                   
                      return true;
                    }
                  }
                },
                del_sector: {
                  required: function(){
                    if($('#form-step-4 input[name="del_checkbox"]').is(':checked')){
                    
                      return true;
                      
                    }
                  }
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
                    '<div class="col-12"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="form-group"><center><div class="portion-name"><a href="#" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Select Description--</a></div><div class="error-portion"></div></center><input type="hidden" name="item_code[]" class="form-control description" required></div></div></div>',
                    '<div><select name="unit_code[]" class="form-control unit select2">'+"{!! $ddUnits !!}"+'</select></div>',
                    '<div><input type="number" onchange="if(this.value<0){this.value= this.value * -1}"  onkeyup="if(this.value<0){this.value= this.value * -1}" class="form-control quantity" name="quantity[]"></div>',
                    '<div>'+delete_btn+'</div>'
                ]).draw(false);
                $('.select2').css('width','100%').select2({allowClear:true});
                
            }  
        });

        $('.add-item').click();

        $wizard_steps = $('.wizard_steps');
        $container = $('.container');

        $('.buttonPrevious').click(function(e){
            $selected_step = $wizard_steps.find('li').find('a.selected');
            $prev_step = $wizard_steps.find('li').has('a.selected').prev('li').find('a');

            $selected_step.removeClass('selected').addClass('done');
            $prev_step.removeClass('done').addClass('selected');

            $selected = $container.find('div.selected');
            $prev = $container.find('div.selected').prev('div.content');
            if($selected.hasClass('start')==false){
                $selected.removeClass('selected').addClass('unselect');
                $prev.removeClass('unselect').addClass('selected');
            }
            if($selected.hasClass('end')){
                $('.buttonNext').removeClass('buttonDisabled');
                $('.buttonFinish').addClass('buttonDisabled');
            }
            if($prev.hasClass('start')){
                $(this).addClass('buttonDisabled');
            }
            
        })

        $('.buttonNext').click(function(e){
            $selected_step = $wizard_steps.find('li').find('a.selected');
            $next_step = $wizard_steps.find('li').has('a.selected').next('li').find('a');


            $selected = $container.find('div.selected');
            $next = $container.find('div.selected').next('div.content');
            if(!$container.find('div.selected').find('form').valid()){
                e.preventDefault();
            }else{
                $proceed = true;
                if(parseInt($selected_step.find('.step_no')[0].innerHTML)==3){
                   
                  $('input[name="s_dc_fname"]').val($('#form-step-2 input[name="fname"]').val());
                  $('input[name="s_dc_mname"]').val($('#form-step-2 input[name="mname"]').val());
                  $('input[name="s_dc_lname"]').val($('#form-step-2 input[name="lname"]').val());

                  $('input[name="c_dc_fname"]').val($('#form-step-3 input[name="fname"]').val());
                  $('input[name="c_dc_mname"]').val($('#form-step-3 input[name="mname"]').val());
                  $('input[name="c_dc_lname"]').val($('#form-step-3 input[name="lname"]').val());

                  $('input[name="discount_coupon"]').trigger('keyup');
                }
                if(parseInt($selected_step.find('.step_no')[0].innerHTML)==4){
                    if(parseInt($('input[name="discount_coupon_action"]').val())==0){
                        $proceed=false;
                        return $proceed;
                    }
                }
                if($proceed==true){

                  if($selected.hasClass('end')==false){
                      $selected.removeClass('selected').addClass('unselect');
                      $next.removeClass('unselect').addClass('selected');
                      $('.buttonPrevious').removeClass('buttonDisabled');
                  }
                  if($next.removeClass('unselect').hasClass('end')){
                      $(this).addClass('buttonDisabled');
                      $('.buttonFinish').removeClass('buttonDisabled');
                  }
                  $selected_step.removeClass('selected').addClass('done');
                  if($next_step.hasClass('disabled')){
                      $next_step.removeClass('disabled').addClass('selected');
                  }else{
                      $next_step.removeClass('done').addClass('selected');
                  }
                }else{
                    swal('Please review inputted data.',{
                        icon: 'error',
                        title: "Ooops!"
                    });
                }
                
            }
            
        })

        $('.buttonFinish').click(function(e){
            if(!$('#form-step-1').valid() || !$('#form-step-2').valid() || !$('#form-step-3').valid() || !$('#form-step-4').valid() || !$('#form-step-5 input[name="agree"]').is(':checked')){
              
              $('#modal-error').modal('show');
            }else{
              $('#modal-note').modal('show');
            }
        })

        $('.proceed').click(function(e){

          $button = $(this);
          $button.attr('disabled',true);
          $button.html('Please wait ...');

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
            subscribe : $('#form-step-2 input[name="subscribe"]').length > 0 ? $('#form-step-2 input[name="subscribe"]').is(':checked') : false,
            contact_no :  $('#form-step-2 input[name="contact_no"]').val(),
            business_category_id :  $('#form-step-2 select[name="business_category_id"]').val(),
            street :  $('#form-step-2 input[name="street"]').val(),
            barangay : $('#form-step-2 select[name="barangay"]').find('option:selected').text(),
            sectorate_no : $('#form-step-2 select[name="barangay"]').find('option:selected').data('sector_no'),
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
            barangay :  $('#form-step-3 select[name="barangay"]').find('option:selected').text(),
            sectorate_no : $('#form-step-3 select[name="barangay"]').find('option:selected').data('sector_no'),
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
              mode_payment: $('#form-step-4 select[name="mode_payment"]').val(),
              mode_payment_io: $('#form-step-4 input[name="mode_payment_is"]').is(':checked') ? 1 : 2,
              mode_payment_email: $('#form-step-4 input[name="mode_payment_email"]').val(),
              destinationbranch_id: $('#form-step-4 select[name="destinationbranch_id"]').val(),
              shipment_type: $('#form-step-4 select[name="shipment_type"]').val(),
              pu_checkbox :  $('#form-step-4 input[name="pu_checkbox"]').is(':checked') ? 1 : 0,
              pu_sector: $('#form-step-4 select[name="pu_sector"]').val(),
              pu_date: $('#form-step-4 input[name="pu_date"]').val(),
              pu_street: $('#form-step-4 input[name="pu_street"]').val(),
              del_checkbox :  $('#form-step-4 input[name="del_checkbox"]').is(':checked') ? 1 : 0,
              del_sector: $('#form-step-4 select[name="del_sector"]').val(),
              del_street: $('#form-step-4 input[name="del_street"]').val(),
              declared_value: $('#form-step-4 input[name="declared_value"]').val(),
              discount_coupon: $('#form-step-4 input[name="discount_coupon"]').val(),
              discount_coupon_action: $('#form-step-4 input[name="discount_coupon_action"]').val(),
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
              // swal(result.message, {
              //   icon: result.type,
              //   title: result.title
              // })
              //           .then(function(){
              //               $('#form-step-1').trigger('reset');
              //               $('#form-step-2').trigger('reset');
              //               $('#form-step-3').trigger('reset');
              //               $('#form-step-4').trigger('reset');
              //               $('#form-step-5').trigger('reset');
              //               window.location.href="{{url('/waybills/printable-reference')}}/"+result.key;
              // });
              Swal.fire({
                  icon: result.type,
                  title: result.title,
                  text: result.message
              }).then((res) => {
                $('#form-step-1').trigger('reset');
                $('#form-step-2').trigger('reset');
                $('#form-step-3').trigger('reset');
                $('#form-step-4').trigger('reset');
                $('#form-step-5').trigger('reset');
                window.location.href="{{url('/waybills/printable-reference')}}/"+result.key;

                //window.open("{{url('/waybills/printable-reference')}}/"+result.key);
                //window.location.href="{{url('/create-booking')}}";
                
              });
            },
            error: function(xhr,status){          
              if(xhr.status==500){
                  var responseJSON = xhr.responseJSON;
                  Swal.fire({
                      icon: 'error',
                      title: 'Ooops!',
                      text: responseJSON.message
                  })
              }else if(xhr.status==408){
                  
                  Swal.fire({
                      icon: 'error',
                      title: 'Connection time-out',
                      text: 'Please check your internet'
                  })
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

        });
      })

</script>
@include('waybills.payment_type_script')
@include('sector.sector_script')
</body>

</html>