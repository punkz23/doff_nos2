<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('/images/icon.png')}}" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Poppins:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/regna/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/aos/aos.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('/regna/css/style.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
      <a href="#hero"><img src="{{asset('/images/doff logo.png')}}" height="50px" alt=""></a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
        <li class="menu-active"><a href="{{url('/')}}">Home</a></li>
          <li><a href="#" data-toggle='modal' data-target='#modal-track-and-trace'>Track and Trace</a></li>
         
          <li><a href="{{url('/')}}#services">Services</a></li>
          <li class="menu-has-children"><a href="">Information</a>
            <ul>
              <li><a href="{{route('faqs.outside')}}">FAQ</a></li>
              <li><a href="{{url('/privacy-policy')}}">Privacy Policy</a></li>
            </ul>
          </li>
          <li class="menu-has-children"><a href="">About Us</a>
            <ul>
              <li><a href="{{url('/')}}#about">Our Company</a></li>
              <li><a href="https://dailyoverland.com/career/">Career</a></li>
              <li><a href="{{url('/')}}#portfolio">Branches</a></li>
            </ul>
          </li>
          <li><a href="{{url('/')}}#contact">Contact Us</a>
            <!-- <ul>
              <li><a href="#">Complain</a></li>
              <li><a href="#">Feedback</a></li>
            </ul> -->
          </li>
          <li class="menu-has-children"><a>Customer Login</a>
            <ul class="online-booking">
              <li><a href="{{route('home')}}">With Account</a></li>
              <li><a href="{{url('/create-booking')}}">Guest</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Branch Details</h2>
          <ol>
            <li><a href="index.html">Home</a></li>
            <li><a href="portfolio.html">Branches</a></li>
            <li>Branch Details</li>
          </ol>
        </div>

      </div>
    </section><!-- Breadcrumbs Section -->

    <!-- ======= Portfolio Details Section ======= -->
    <section class="portfolio-details">
      <div class="container">

        <div class="portfolio-details-container">

          <div class="owl-carousel portfolio-details-carousel">
            <!-- <img src="assets/img/portfolio/portfolio-details-1.jpg" class="img-fluid" alt="">
            <img src="assets/img/portfolio/portfolio-details-2.jpg" class="img-fluid" alt="">
            <img src="assets/img/portfolio/portfolio-details-3.jpg" class="img-fluid" alt=""> -->
            <iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{$data ->google_maps_api}}" allowfullscreen></iframe>
          </div>

          <div class="portfolio-info">
            <h3>Schedules</h3>
            <ul>
              @foreach($data->branch_schedule as $r)
                  <li>
                       {{$r->days_from==$r->days_to ? $r->days_from.' & Holidays' : $r->days_from.'-'.$r->days_to}} ({{date('h:i A',strtotime($r->time_from))}} - {{date('h:i A',strtotime($r->time_to))}}) <br>
                  </li>
              @endforeach
            </ul>
          </div>

        </div>

        <div class="portfolio-description">
          <h2>Daily Overland Freight Forwarder {{$data->name}}</h2>
          <p>
            {{$data->address}}<br>
            <small><b>Contact #:</b>{{$data->branch_contact->count()>0 ? '(' : ''}} @foreach($data->branch_contact as $k=>$c) {{$k>0 ? '/' : ''}}{{$c->contact_no}} @endforeach {{$data->branch_contact->count()>0 ? ')' : ''}}</small>
          </p>
        </div>
      </div>
    </section><!-- End Portfolio Details Section -->

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
  
  @include('includes.track_and_trace_modal')

  <!-- Vendor JS Files -->

  <script src="{{asset('/regna/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('/regna/vendor/counterup/counterup.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/superfish/superfish.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/hoverIntent/hoverIntent.js')}}"></script>
  <script src="{{asset('/regna/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/venobox/venobox.min.js')}}"></script>
  <script src="{{asset('/regna/vendor/aos/aos.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/regna/js/main.js')}}"></script>
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

        $('.close-track-and-trace').click(function(){
            $('#form-tracking input[name="search_type"]').removeAttr('disabled');
            $('#form-tracking .submit').removeAttr('disabled');
            $('.datatable > tbody >tr.loading').attr('hidden',true);
            $('.tracking-div').attr('hidden',true);
            $('.waybill-div').attr('hidden',true);
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
      })
  </script>
</body>

</html>