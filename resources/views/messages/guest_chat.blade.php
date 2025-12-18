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
  <style>
        ul{
            margin:0;
            padding:0;
        }
        .user-wrapper, .message-wrapper{
            border: 1px solid #dddddd;
            overflow-y: auto;
        }
        .user-wrapper {
            height:600px;
        }

        .user{
            cursor : pointer;
            padding: 5px 0;
            position:relative;
        }
        .user:hover{
            background: #eeeeee;
        }
        .user:last-child{
            margin-bottom:0;
        }
        .pending{
            position: absolute;
            left: 13px;
            top:9px;
            background: #b600ff;
            margin:0;
            border-radius:50%;
            width:18px;
            height:18px;
            line-height:18px;
            padding-left:5px;
            color: #ffffff;
            font-size:12px;
        }
        .media-left{
            margin:0 10px;
        }

        .media-left img{
            width:64px;
            border-radius:64px;
        }

        .media-body{
            padding: 6px 0;
        }

        .media-body p{
            margin:6px 0;
        }

        .message-wrapper{
            padding:10px;
            height:536px;
            background: #eeeeee;
        }

        .messages .message{
            margin-bottom: 15px;
        }

        .messages .message:last-child{
            margin-bottom:0;
        }

        .received, .sent{
            width: 45%;
            padding:3px 10px;
            border-radius: 10px;
        }

        .received{
            background : #ffffff;
        }

        .sent{
            background: #3bebff;
            float: right;
            text-align: right;
        }

        .message p{
            margin: 5px 0;
        }

        .date{
            color: #777777;
            font-size:12px;
        }

        .active{
            background: #eeeeee;
        }

        input[type=text]{
            width: 100%;
            padding: 12px 20px;
            margin: 15px 0 0 0;
            display: inline-block;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
            border: 1px solid #cccccc;
        }

        input[type=text]:focus{
            border: 1px solid #aaaaaa;
        }

    </style>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header-fixed">
    <div class="container">

      <div id="logo" class="pull-left">
        <a href="#hero"><img src="{{asset('/images/doff logo.png')}}" height="50px" alt=""></a>
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
        <div class="row">
            <div class="col-md-12" id="messages">
            
            </div>
        </div>
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

  <!-- Template Main JS File -->
  <script src="{{asset('landing_pagev1/js/main.js')}}"></script>
  <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

  <script>
    $(document).ready(function(){
        var receiver_id="{{$admin->id}}";var my_id="{{$data->anonymous_id}}";
        

        Pusher.logToConsole = true;

        var pusher = new Pusher('3ae2d59446059a57508f', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            ajaxChat();
        });

        ajaxChat();
                
            

            $('.user').click(function(){
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.pending').remove();
                receiver_id=$(this).attr('id');
                $.ajax({
                    url: "{{url('/chats/create')}}/"+receiver_id,
                    type: "GET",
                    // cache: false,
                    success: function(data){
                        
                        // console.log($('#messages'));
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                })
            })

            $(document).on('keyup','.input-text input',function(e){
                var message = $(this).val();
                if(e.keyCode==13 && message!='' && receiver_id!=''){
                    // alert(message);
                    $(this).val('');
                    
                    $.ajax({
                        url: "{{url('/send-message')}}",
                        type: "POST",
                        data : {_token: "{{csrf_token()}}",from: my_id, to: receiver_id, message: message},
                        cache: false,
                        success: function(data){

                        },
                        error: function(xhr,status,err){

                        },
                        complete: function(){
                            scrollToBottomFunc();
                        }
                    })
                }
            })

            function scrollToBottomFunc(){
                $('.message-wrapper').animate({
                    scrollTop : $('.message-wrapper').get(0).scrollHeight
                },50);
            }

            function ajaxChat(){
                $.ajax({
                    url: "{{url('/chats/create')}}/{{$data->session_key}}",
                    type: "GET",
                    cache: false,
                    success: function(data){
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                })
            }

            
        })
    </script>

</body>

</html>
