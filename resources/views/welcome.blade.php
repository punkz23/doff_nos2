<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('/images/ICON.png')}}" rel="icon">
  <link href="{{asset('/regna/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="{{asset('/regna/fonts/google_font.css')}}" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/regna/vendor/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{asset('/regna/vendor/aos/aos.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{asset('/tagsinput/jquery.tagsinput.css')}}" />
  <!-- Template Main CSS File -->
  <link href="{{asset('/regna/css/style.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <style>
    .custom{
    position: fixed;
    top: auto;
    left: 0;
    bottom: 0;
    z-index: 1050;
    outline: 0;
    width: auto;
    height: auto;
    margin-left: 10px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header-transparent">
    <div class="container">

      <div id="logo" class="pull-left">
        <a href="#hero">
          <!-- <img src="about:blank" class="company-logo-with-name" height="50px" alt=""> -->
          <img src="{{asset('/images/daily overland standard logo for web 280x60.png')}}" style="margin-top:-15px;" height="60px" width="280px" alt="">

        </a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>
      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="{{url('/')}}">Home</a></li>
          <li><a href="https://track.dailyoverland.com">Track your shipment</a></li>

          <li><a href="#services">Services</a></li>
          <li><a href="{{route('faqs.outside')}}">FAQ</a></li>
          <li class="menu-has-children"><a href="#">About Us</a>
            <ul>
              <li><a href="#about">Our Company</a></li>
              <li><a href="https://career.dailyoverland.com">Career</a></li>
              <li><a href="#portfolio">Branches</a></li>
              <li><a href="{{url('/privacy-policy')}}">Privacy Policy</a></li>
            </ul>
          </li>
          <li>
            <a class="menu-has-children" href="#">Contact Us</a>
            <ul>
              <li><a href="#complain-feedback" class="contact-us-navi" data-value="complain">Complain</a></li>
              <li><a href="#complain-feedback" class="contact-us-navi" data-value="feedback">Feedback</a></li>
              <li><a href="{{route('request.qoutation')}}" target="_blank">Request Quotation</a></li>
            </ul>
          </li>
          <li class="menu-has-children"><a>Customer Login</a>
            <ul class="online-booking">
              <li><a href="{{env('APP_HOST')}}/login">With Account</a></li>
              <li><a href="{{url('/create-booking')}}">Guest</a></li>
            </ul>
          </li>
        </ul>
      </nav><!-- #nav-menu-container -->
      @include('navbar')
    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
<section id="hero">
    <div class="hero-container" data-aos="zoom-in" data-aos-delay="100">

    </div>
  </section><!-- End Hero Section -->

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h3 class="section-title">Services</h3>
          <p class="section-description">Daily Overland Freight Forwarder has several areas of specialized service where we are gladly looking forward to assist you from start to finish; online booking, pre-loading presentation, careful loading, smooth transportation, pickups and assisted deliveries. We will continue to reciprocate your needs, whatever they may be, and deliver that exceeds your expectations.</p>
        </div>
        <div class="row">
          <div class="col-lg-6 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-truck"></i></a></div>
              <h4 class="title"><a href="">Trucking</a></h4>
              <p class="description">The Daily Overland Freight Forwarder offers various logistic solutions for your general and express shipments. You would be amazed what DOFF can do for you.<br>Let our team who has applicable knowledge in product shipping transport your goods. You'll be rest assured with DOFF's years of industry experience.</p>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href="" onclick="showMenu()"><i class="fa fa-desktop"></i></a></div>
              <h4 class="title"><a href="" onclick="showMenu()">Online Booking</a></h4>
              <p class="description">We strongly encourage you to book online using our website,  you can login with your google and fb account at <a href="https://www.dailyoverland.com/login">https://www.dailyoverland.com/login</a> or you can login as guest at <a href="https://www.dailyoverland.com/create-booking">https://www.dailyoverland.com/create-booking</a>. You can also download our Daily Overland Freight Forwarder (DOFF) Mobile App available at play store. Online booking lets you save or store important details of your shipments so you do not need to fill-out shipping form at Manila branch like walk-ins. However this is not an appointment, scheduling or reservation system. Booking are valid only for seven (7) days, make sure to transact your online booking with our Manila branch at your convenient time before it expires. Print a copy of your online booking or provide screenshot showing the booking reference number and present to our branch representative to get priority queue at the counter.</p>
            </div>
          </div>
          <div class="col-lg-6 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-paper-plane"></i></a></div>
              <h4 class="title"><a href="">Pick up</a></h4>
              <p class="description">You need to schedule a pick-up? Worry no more! The Daily Overland Freight Forwarder offers pick-up services to our different serviceable areas. Contact us now.</p>
            </div>
          </div>

          <div class="col-lg-6 col-md-6" data-aos="zoom-in">
            <div class="box">
              <div class="icon"><a href=""><i class="fa fa-photo"></i></a></div>
              <h4 class="title"><a href="">Deliver</a></h4>
              <p class="description">You want to upgrade your shipment that is within your reach? Yes, you heard it right! We also offer deliveries to our serviceable areas near you. Contact us now explain your needs, and we will do our best to fulfill your specialized transport requests.</p>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End Services Section -->

    <!-- ======= About Section ======= -->
    <section id="about">
      <div class="container" data-aos="fade-up">
        <div class="row about-container">

          <div class="col-lg-6 content order-lg-1 order-2">
            <h2 class="title">Our Company</h2>
            <p>
            Inland South Cargo Express Inc., doing business under the trade name DAILY OVERLAND FREIGHT FORWARDER began its operations and continues to pursue for success as a general and express carrier way back year 2000. We are proud Bicol-based domestic corporation duly organized and existing under Philippine law that is currently servicing clients in Manila to Southern Luzon and plans to expand our capabilities to cater shipment related needs nationwide.
            </p>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="100">
              <div class="icon"><i class="fa fa-line-chart"></i></div>
              <h4 class="title"><a href="">Our Mission</a></h4>
              <p class="description">Maintain our leadership in the trucking industry in the Bicol region by providing efficient service to customers at all times and develop a highly motivated, efficient, professionalized and God-centered workforce.</p>
            </div>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="200">
              <div class="icon"><i class="fa fa-eye"></i></div>
              <h4 class="title"><a href="">Our Vision</a></h4>
              <p class="description">Pursue excellence in providing efficient service to customers and contribute to the development of the trucking industry in the Bicol region and all other areas it serves.</p>
            </div>

            <div class="icon-box" data-aos="fade-up" data-aos-delay="300">
              <div class="icon"><i class="fa fa-users"></i></div>
              <h4 class="title"><a href="">Customer Service</a></h4>
              <p class="description">CUSTOMER SERVICE is what we mean business. It is not just fulfilling our duties, catering your shipment needs and all but we also take our sincerest approach for each individual customer. The highest compliment that you can give us is your appreciation for a JOB WELL DONE.
              <br>Thank you for sending your cargoes via DAILY OVERLAND FREIGHT FORWARDER.</p>
            </div>

          </div>

          <div class="col-lg-6 background order-lg-2 order-1" data-aos="fade-left" data-aos-delay="100"></div>
        </div>

      </div>
    </section><!-- End About Section -->


    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h3 class="section-title">Branches</h3>
          <p class="section-description">The Daily Overland Freight Forwarder is a Manila to Bicol-bound transportation service that operates within the following eleven (11) branches near you.
          <br>
          Accross towns, cities or accross the region, we will carefully transport and cater your shipment needs from start to finish.</p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              @foreach($branch_filters as $key=>$row)
                <li data-filter=".filter-{{strtolower($row->name)}}">{{ucfirst($row->name)}}</li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

          @foreach($branches as $key=>$row)
            <div class="col-lg-4 col-md-6 portfolio-item filter-{{strtolower($row->branch_filter->name)}}">
            <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{$row->google_maps_api}}" allowfullscreen></iframe>
              <div class="portfolio-info">
                <h4>{{ucfirst($row->name)}}</h4>
                <p>{{$row->address}}</p>
                <a href="{{url('/branch-details/'.$row->id)}}" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>
          @endforeach



        </div>

      </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact">
      <div class="container">
        <div class="section-header">
          <h3 class="section-title">Contact Us</h3>
          <p class="section-description">For any questions concerned, feel free to contact us today!</p>
        </div>
      </div>

      <!-- Uncomment below if you wan to use dynamic maps -->
      <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d343.40879791410447!2d123.71331911748533!3d13.148074251022441!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x5837d108ea4a4732!2sDaily%20Overland!5e0!3m2!1sen!2sus!4v1591853196708!5m2!1sen!2sus" width="100%" height="380" frameborder="0" style="border:0" allowfullscreen></iframe>
    </section><!-- End Contact Section -->

    <section id="complain-feedback">
    <div class="container mt-5">
        <div class="row justify-content-center">

          <div class="col-lg-3 col-md-4">

            <div class="info">
              <div>
                <i class="fa fa-map-marker"></i>
                <p>Brgy. Ilawod,Lotivio Street, JRE Bldg, Room 209<br>Daraga, Albay, Philippines, 4501</p>
              </div>

              <div>
                <i class="fa fa-envelope"></i>
                <p>csr@dailyoverland.com</p>
              </div>

              <div>
                <i class="fa fa-phone"></i>
                <p>(052) 732-7360</p>
              </div>
              <div>
                <i class="fa fa-send"></i>
                <p><a href="{{route('request.qoutation')}}" target="_blank"> Request Quotation</a> </p>
              </div>
            </div>

            <div class="social-links">
              <a href="http://facebook.com/dailyoverland.com" class="facebook"><i class="fa fa-facebook"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
            </div>

          </div>

          <div class="col-lg-5 col-md-8">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link contact-us-nav active" data-toggle="tab" href="#complain">Compain</a>
              </li>
              <li class="nav-item">
                <a class="nav-link contact-us-nav" data-toggle="tab" href="#feedback">Feedback</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tech-support">Tech Support</a>
              </li> -->
            </ul>

            <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane container contact-us-tab active" id="complain">
              <div class="form-support">
              <br>
              <form id="form-complain" role="form" class="php-email-form-custom">
                  @csrf
                  <div class="form-group">
                    <input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" name="fname" class="form-control" id="fname" placeholder="First Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" name="mname" class="form-control" id="mname" placeholder="Middle Name"/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Contact #" data-rule="minlen:4" data-msg="Please enter your contact #" required/>
                    <div class="validate"></div>
                  </div>

                  <div class="form-group">
                    <select name="incident_category_id" class="form-control">
                      <option selected value="none" disabled>--Please select category--</option>
                      @foreach($incident_categories as $category)
                        <option value="{{$category->incident_category_id}}">{{$category->incident_category_name}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Tracking #(s)</label>
                    <input id="tracking_no" name="tracking_no" type="text" class="tags" value="" /></p>
                  </div>

                  <div class="form-group">
                    <textarea class="form-control" name="incident_subject" rows="5" data-rule="required" data-msg="Please write your complain here" placeholder="Complain Here" required></textarea>
                    <div class="validate"></div>
                  </div>

                  <div class="form-group">
                    <label>Attachment/s</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                    <div class="validate"></div>
                  </div>

                  <div class="text-center"><button class="btn btn-success" type="submit">Submit</button></div>
                </form>
              </div>
            </div>
            <div class="tab-pane container contact-us-tab fade" id="feedback">
            <div class="form-support">
              <br>
              <form id="form-feedback" role="form" class="php-email-form-custom">
                  @csrf
                  <div class="form-group">
                    <input type="text" name="lname" class="form-control" placeholder="Last Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" name="fname" class="form-control" placeholder="First Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="text" name="mname" class="form-control" placeholder="Middle Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <input type="number" class="form-control" name="contact_no" placeholder="Contact #" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" required/>
                    <div class="validate"></div>
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" name="incident_subject" rows="5" data-rule="required" data-msg="Please write elaborate for us" placeholder="Feedback Here" required></textarea>
                    <div class="validate"></div>
                  </div>

                  <div class="text-center"><button class="btn btn-success" type="submit">Submit</button></div>
                </form>
              </div>
            </div>
          </div>
          </div>
          </div>

        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <div class="modal custom" id="modal-privacy" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-md">
          <div class="modal-content">

              <div class="modal-header">

                  <h3 class="getlaid" id="trackModalLabel">Data Privacy Policy</h3>

              </div>

              <div class="modal-body">
                <p style="text-align: justify;">
                This <a href="{{route('privacy-policy')}}">privacy policy</a> will help you understand how Daily Overland Freight Forwarder uses and protects the data you provide to us when you visit and use our website and mobile application......
                </p>
              </div>

              <div class="modal-footer">
                  <!-- <button type="button" class="btn close-privacy btn-secondary" data-dismiss="modal">Close</button> -->
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>

              </div>
          </div>
      </div>
  </div>

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

  @include('includes.track_and_trace_modal')

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <script src="https://apps.elfsight.com/p/platform.js" defer></script>
  <div class="elfsight-app-92d76865-c15b-4911-bd79-beb001a1601f"></div>

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

  <script type="text/javascript" src="{{asset('/tagsinput/jquery.tagsinput.js')}}"></script>

  <script type="text/javascript" src="{{asset('/js/script.js')}}"></script>

  <script type="text/javascript">

    @php
        if(isset($_GET['track'])){
          @endphp
            $('#modal-track-and-trace').modal('show');
          @php
        }else{
          @endphp
            $('#modal-privacy').modal('show');
          @php
        }
    @endphp

    $('.contact-us-navi').click(function(){
      $('.contact-us-tab.fade').removeClass('fade');
      $('.contact-us-tab.active').removeClass('active');
      $('.contact-us-nav.active').removeClass('active');
      $('.contact-us-nav[href="#'+$(this).data('value')+'"]').addClass('active');
      $('.contact-us-tab[id="'+$(this).data('value')+'"]').addClass('active');
    })

    function showMenu(e){
      $('.online-booking').attr('style','display:block');
      e.preventDefault();
    }

    $('#tracking_no').tagsInput({width:'auto'});
    $(document).ready(function(){
        var index=0;
        var myTable = $('#datatable').DataTable( {
            bAutoWidth: false,
            "aoColumns": [
                    null
            ],
            "aaSorting": [],
            select: {
                style: 'multi'
            }
        } );

        var dynamic_table = $('#dynamic-table').DataTable();

        // var dynamic_table = $('#dynamic-table').DataTable( {
        //     "searching": false,
        //     "paging" : false,
        // } );

        // $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        //     new $.fn.dataTable.Buttons( dynamic_table, {
        //         buttons: [
        //             {
        //                 "text": "<i class='fa fa-plus bigger-110 blue'></i> <span class='hidden'>Add Item</span>",
        //                 "className": "btn btn-white btn-primary btn-bold add-item",
        //             },
        //             {
        //                 "text": "<i class='fa fa-trash bigger-110 red'></i> <span class='hidden'>Remove Item</span>",
        //                 "className": "btn btn-white btn-primary btn-bold remove-item",
        //             }
        //         ]
        // } );
        // dynamic_table.buttons().container().appendTo( $('.tableTools-container') );

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

        $('#form-support').validate({
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

                $('#form-support .submit').attr('disabled',true);


                $.ajax({
                    url: "{{route('chats.guest_request')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                        // console.log("{{url('/start-conversation/')}}/"+result.data['session_key']);
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                          if(result.type=="success"){
                            window.open("{{url('/start-conversation')}}/"+result.data['session_key'],"_blank");
                          }
                        });

                    }
                });

                return false;
            },
            invalidHandler: function (form) {
            }
        });

        $('#form-complain').validate({
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
                else if (element.attr("class") == "tracking_no") {
                  error.insertAfter(element.parent());
                }
                else error.insertAfter(element.parent());
            },

            submitHandler: function (form) {
                var form_data = new FormData(form);
                form_data.append('_token',"{{csrf_token()}}");

                $('#form-complain .submit').attr('disabled',true);


                $.ajax({
                    url: "{{route('incident.store_complain')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                      swal(result.message, {
                        icon: result.type,
                        title: result.title
                      }).then(function(){
                        if(result.type=='error'){
                          var $validator = $('#form-complain').validate();
                          var errors;
                          Object.entries(result.errors).forEach(([key,value])=>{
                            errors = { [key] : value[0] };
                          });
                          $validator.showErrors(errors);
                        }else{
                          $('#form-complain .submit').removeAttr('disabled');
                          $('#form-complain div#tracking_no_tagsinput').find('span.tag').remove();
                          $('#form-complain').trigger('reset');
                        }
                      });
                    }
                });

                return false;
            },
            invalidHandler: function (form) {
            }
        });

        $('#form-feedback').validate({
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

                $('#form-feedback .submit').attr('disabled',true);


                $.ajax({
                    url: "{{route('incident.store_feedback')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                        // console.log("{{url('/start-conversation/')}}/"+result.data['session_key']);
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                          $('#form-feedback').trigger('reset');
                          $('#form-feedback .submit').removeAttr('disabled');
                        });

                    }
                });

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



        $('.add-tracking').click(function(){
          $('#dynamic-table').append('<tr><td><div class="form-group"><input type="text" class="form-control tracking_no" required></div></td><td><button type="button" class="btn btn-sm btn-danger remove"><i class="ace-icon fa fa-trash"></i></button></td></tr>');
          $('.remove').click(function(){
            $(this).closest('tr').remove();
          });
        });







    //   if(localStorage.getItem("is_agree") === null){

    //   }

      $('.accept').click(function(){
        // localStorage.setItem('is_agree',1);
        window.location.href="{{url('/privacy-policy')}}";
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
