<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="{{csrf_token()}}" name="csrf-token">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('/landing_pagev1/img/favicon.png')}}" rel="icon">
  <link href="{{asset('/landing_pagev1/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Poppins:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/landing_pagev1/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('/landing_pagev1/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('/landing_pagev1/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('/landing_pagev1/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{asset('/landing_pagev1/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{asset('/landing_pagev1/vendor/aos/aos.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('/landing_pagev1/css/style.css')}}" rel="stylesheet">

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
        <a href="{{url('/')}}"><img src="{{asset('/images/doff logo.png')}}" height="50px" alt=""></a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li><a href="{{url('/')}}">Home</a></li>
          <li class="menu-has-children"><a href="">Search</a>
            <ul>
              <li><a href="#" data-toggle='modal' data-target='#modal-track-and-trace'>Track and Trace</a></li>
            </ul>
          </li>
          <li><a href="{{url('/')}}#services">Services</a></li>
          <li class="menu-has-children"><a href="">Information</a>
            <ul>
              <li><a href="{{route('faqs.outside')}}">FAQ</a></li>
              <li><a href="{{url('/terms-and-condition')}}">Terms</a></li>
            </ul>
          </li>
          <li class="menu-has-children"><a href="">About Us</a>
            <ul>
              <li><a href="{{url('/')}}#about">Our Company</a></li>
              <li><a href="https://dailyoverland.com/career/">Career</a></li>
              <li><a href="{{url('/')}}#portfolio">Branches</a></li>
            </ul>
          </li>
          <li><a href="{{url('/')}}#contact">Contact</a></li>
          <li><a href="{{route('home')}}">Customer Login</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs Section ======= -->
    @yield('breadcrumbs')
    

    <section class="inner-page pt-4">
      <div class="container">
        @yield('content')
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
  
  @include('includes.track_and_trace_modal')

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('/landing_pagev1/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/counterup/counterup.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/superfish/superfish.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/hoverIntent/hoverIntent.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/venobox/venobox.min.js')}}"></script>
  <script src="{{asset('/landing_pagev1/vendor/aos/aos.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/landing_pagev1/js/main.js')}}"></script>

  <script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  
  @yield('scripts')
  
</body>

</html>