<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset(env('APP_IMG'))}}" rel="icon">
  <link href="{{asset('landing_pagev1/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Poppins:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('landing_pagev1/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('landing_pagev1/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
  <link href="{{asset('landing_pagev1/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{asset('landing_pagev1/vendor/venobox/venobox.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('landing_pagev1/css/style.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Regna - v2.0.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->


</head>

<body>
  @include('fb')
  <!-- ======= Header ======= -->
  <header id="header" class="header-fixed">
    <div class="container">

      <div id="logo" class="pull-left">
        <a href="#hero">@include('logo')</a>
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu sf-js-enabled sf-arrows" style="touch-action: pan-y;">
          <li class="menu-active"><a href="{{url('/')}}" style="font-color:black;">Home</a></li>

        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->


  <main id="main">




    <!-- ======= About Section ======= -->
    <section id="about">
      <div class="container">
        {!! $term->content !!}
      </div>
    </section><!-- End About Section -->






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



  <!-- Vendor JS Files -->
  <script src="{{asset('landing_pagev1/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/counterup/counterup.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/wow/wow.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/superfish/superfish.min.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/hoverIntent/hoverIntent.js')}}"></script>
  <script src="{{asset('landing_pagev1/vendor/venobox/venobox.min.js')}}"></script>
  <script src="{{asset('/js/messenger-plugin.js')}}"></script>
  <!-- Template Main JS File -->
  <script src="{{asset('landing_pagev1/js/main.js')}}"></script>

  <script type="text/javascript">
    $(document).ready(function(){

    //   if(localStorage.getItem("is_agree") === null){
        $('#modal-privacy').modal('show');
    //   }

      $('.accept').click(function(){
        // localStorage.setItem('is_agree',1);
        window.location.href="{{url('/privacy-policy')}}";
      });

    })
  </script>

</body>

</html>
