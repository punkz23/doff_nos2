<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Frequently Asked Question - {{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset(env('APP_IMG'))}}" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="{{asset('/regna/fonts/google_font.css')}}" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/regna')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('/regna')}}/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v10.0" nonce="t0A2wSJd"></script>
  <link href="{{asset('/css/fb.css')}}" rel="stylesheet">
  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
.page-content {
	max-width: 100%; 
	background: #fff;
}
a {
	color: #21D4FD; 
	transition: all 0.3s;
}
a:hover {
	color: #B721FF;
}

.tabbed {
	overflow-x: hidden; /* so we could easily hide the radio inputs */
	margin: 32px 0;
	padding-bottom: 16px;
	border-bottom: 1px solid #ccc;
}

.tabbed [type="radio"] {
	/* hiding the inputs */
	display: none;
}

.tabs {
	display: flex;
	align-items: stretch;
	list-style: none;
	padding: 0;
	border-bottom: 1px solid #ccc;
}
.tab > label {
	display: block;
	margin-bottom: -1px;
	padding: 12px 15px;
	border: 1px solid #ccc;
	background: #eee;
	color: #666;
	font-size: 12px; 
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 1px;
	cursor: pointer;	
	transition: all 0.3s;
}
.tab:hover label {
	border-top-color: #333;
	color: #333;
}

.tab-content {
	display: none;
	color: #777;
}

/* As we cannot replace the numbers with variables or calls to element properties, the number of this selector parts is our tab count limit */
.tabbed [type="radio"]:nth-of-type(1):checked ~ .tabs .tab:nth-of-type(1) label,
.tabbed [type="radio"]:nth-of-type(2):checked ~ .tabs .tab:nth-of-type(2) label,
.tabbed [type="radio"]:nth-of-type(3):checked ~ .tabs .tab:nth-of-type(3) label,
.tabbed [type="radio"]:nth-of-type(4):checked ~ .tabs .tab:nth-of-type(4) label,
.tabbed [type="radio"]:nth-of-type(5):checked ~ .tabs .tab:nth-of-type(5) label {
	border-bottom-color: #fff;
	border-top-color: #B721FF;
	background: #fff;
	color: #222;
}

.tabbed [type="radio"]:nth-of-type(1):checked ~ .tab-content:nth-of-type(1),
.tabbed [type="radio"]:nth-of-type(2):checked ~ .tab-content:nth-of-type(2),
.tabbed [type="radio"]:nth-of-type(3):checked ~ .tab-content:nth-of-type(3),
.tabbed [type="radio"]:nth-of-type(4):checked ~ .tab-content:nth-of-type(4) {
	display: block;
}

.bah-accordion {
	width: 100%;
	margin: 10px auto 30px auto;
  text-align: left;
}

.bah-accordion__element__header {
	height: 30px;
  padding: 5px 20px;
  position: relative;
  z-index: 20;
  display: block;
  height: 30px;
  cursor: pointer;
  height: 30px;
}

.bah-accordion__element__header:hover {
  background: #fff;
}

.bah-accordion__element__check:checked + .bah-accordion__element__header,
.bah-accordion__element__check:checked + .bah-accordion__element__header:hover {
  height: 30px;
}

.bah-accordion__element__header:hover:after,
.bah-accordion__element__check:checked + .bah-accordion__element__header:hover:after {
  content: '';
  position: absolute;
  width: 24px;
  height: 24px;
  right: 13px;
  top: 7px;
}

.bah-accordion__element__check {
  display: none;
}

.bah-accordion__element__content {
  overflow: hidden;
  height: 0;
  position: relative;
  z-index: 10;
  -webkit-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -moz-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -o-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -ms-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
}

.bah-accordion__element__check:checked ~ .bah-accordion__element__content {
  -webkit-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -moz-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -o-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -ms-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--small {
  height: auto;
  margin: 0 20px;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--medium {
  height: auto;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--large {
  height: auto;
}
</style>
<body>
  @include('fb')
  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
      @include('logo')
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
          <h2>Frequently Asked Question ({{strtoupper($dialect)}})</h2>
          <ol>
            <li><a href="index.html">Home</a></li>
            <li>Frequently Asked Question</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page pt-4">
      <div class="container">
      <div class="page-content">
            @if(strtoupper($dialect)=='ENGLISH')
            <p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">Say goodbye to your shipping hassles because this May 20, 2020, Daily Overland Freight Forwarder will be back on the run!.</p><p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">Questions? Want to know more about our services? Let our team who has real time knowledge on product shipping assist you. Send us a message. Chat us on our Facebook page&nbsp;<a href="https://www.facebook.com/dailyoverland/" style="color: rgb(69, 174, 214); transition: color 400ms ease 0s, background-color 400ms ease 0s;">https://www.facebook.com/dailyoverland/</a>
            @else
            <p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">May mga problema sa pagpapadala? Huwag mabahala sapagkat simula ngayong Hunyo 2020, ang Daily Overland Freight Forwarder ay muling aarangkada na Mayroon ka bang mga katanungan? Haka-haka sa aming mga serbisyo? Hayaan niyo ang aming team na may kaukulang kaalaman sa pagpapadala ng inyong mga kargamento ang tumulong sa inyo. Mangyaring malaman kung papaano? Padalhan niyo kami ng mensahe gamit ang aming Facebook page&nbsp;<a href="https://www.facebook.com/dailyoverland/" style="background-color: rgb(255, 255, 255); color: rgb(69, 174, 214); transition: color 400ms ease 0s, background-color 400ms ease 0s;">https://www.facebook.com/dailyoverland/</a><br></p>
            @endif
            
            
            <div class="tabbed">
                <input type="radio" id="tab1" name="css-tabs" checked>
                <input type="radio" id="tab2" name="css-tabs">
                
                
                <ul class="tabs">
                    <li class="tab"><label for="tab1">{{strtoupper($dialect) == 'ENGLISH' ? 'General' : 'Pangkalahatan'}}</label></li>
                    <li class="tab"><label for="tab2">Online Booking</label></li>
                    
                </ul>
                
                <div class="tab-content">
                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-1" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-1-1" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Helpful tips for hassle-free shipping' : 'Mga makakatulong na tip para sa madaliang pagpapadala'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.general.guide1')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-2" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-1-2" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Where are your branches located and thier schedules' : 'Saan matatagpuan ang inyong mga branches at ano ang mga schedules nito?'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide2')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-3" name="test-accordion" type="radio" class="bah-accordion__element__check">
                            <label for="question-1-3" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'What are your services' : 'Ano-ano ang inyong mga serbisyo'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide3')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-4" name="test-accordion" type="radio" class="bah-accordion__element__check">
                            <label for="question-1-4" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Shipping Requirement and Packaging guidelines' : 'Ano nga ba ang dapat kong gawin bago ko ipadala ang aking mga kargamento sa inyong mga branches?'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide4')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-5" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-1-5" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'I have a representative who will receive my shipment, what should I do' : 'Meron akong representante na tatanggap ng aking mga pinadalhang kargamento, ano ang dapat kong gawin'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.general.guide5')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-6" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-1-6" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How much will it cost you?' : 'Magkano nga ba ang magpadala sa inyo?'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.general.guide6')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-1-7" name="test-accordion" type="radio" class="bah-accordion__element__check">
                            <label for="question-1-7" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How do I track my shipments' : 'Paano ko nga ba itrack ang aking pinadalang mga kargamento'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.general.guide7')
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-content">
                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-2-1" name="test-accordion" type="radio" class="bah-accordion__element__check">
                            <label for="question-2-1" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to create online booking' : 'Paano gumawa ng online booking'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide1')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-2-2" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-2-2" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to add shipper/consignee' : 'Paano magdagdag ng shipper/consignee'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide2')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-2-3" name="test-accordion" type="radio" class="bah-accordion__element__check">
                            <label for="question-2-3" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to update account information' : 'Paano iupdate ang impormasyon ng account'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                                @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide3')
                            </div>
                        </div>
                    </div>

                    <div class="bah-accordion">
                        <div class="bah-accordion__element">
                            <input id="question-2-4" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                            <label for="question-2-4" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to register account' : 'Paano gumawa ng account'}}</label>
                            <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide4')
                            </div>
                        </div>
                    </div>

                </div>
                
            </div>
	
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
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

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  @include('fb')
  <!-- Vendor JS Files -->
  <script src="{{asset('/regna')}}/vendor/jquery/jquery.min.js"></script>
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
  <script src="{{asset('/js/messenger-plugin.js')}}"></script>
  <!-- Template Main JS File -->
  <script src="{{asset('/regna')}}/js/main.js"></script>
  <script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
  <script>
      $(document).ready(function(){
        $('#form-tracking').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",

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
                var form_data = new FormData(form);
                form_data.append('_token',"{{csrf_token()}}");
                $('#form-tracking input[name="search_type"]').attr('disabled',true);
                $('#form-tracking .submit').attr('disabled',true);
                $('.datatable > tfoot >tr.loading').removeAttr('hidden');
                $('.datatable > tbody').html('');
                // $('#form-tracking .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $.ajax({
                    url: "{{route('track_and_trace')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                        var obj = result;
                        
                        // var innerHtml ='';
                        // for(var i=0; i<obj.length; i++){
                        //     innerHtml = innerHtml + '<tr><td>'+obj[i]['remarks']+'</td></tr>';
                        // }

                        $('.datatable > tbody').html('<tr><td>'+obj['remarks']+'</td></tr>');
                        $('.datatable > tfoot > tr.loading').attr('hidden',true);

                    }
                });
                $('#form-tracking input[name="search_type"]').removeAttr('disabled');
                return false;
            },
            invalidHandler: function (form) {
            }
        });

        $('input[name="search_type"').change(function(){
            if($(this).val()=="tracking"){
                $('.tracking-div').removeAttr('hidden');
                $('.waybill-div').attr('hidden',true);
                $('input[name="tracking_no"').attr('required',true);
                $('input[name="name"]').removeAttr('required');
                $('input[name="waybill"]').removeAttr('required');
            }else{
                $('.tracking-div').attr('hidden',true);
                $('.waybill-div').removeAttr('hidden');
                $('input[name="tracking_no"').removeAttr('required');
                $('input[name="name"]').attr('required',true);
                $('input[name="waybill"]').attr('required',true);
            }
        })

        $('#search-tracking').click(function(){
            $('#form-tracking').submit();
        });

        $('#search-waybill').click(function(){
            $('#form-tracking').submit();
        });
      })
  </script>
</body>

</html>