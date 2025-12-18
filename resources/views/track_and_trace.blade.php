<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Track your shipment - {{ config('app.name', 'Laravel') }}</title>
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
  
  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
* {
    margin:0;
    padding:0;
    font:12pt Arial;
}

.field {
  display:flex;
  position:realtive;
  margin:5em auto;
  width:500px;
  flex-direction:row;
  -moz-box-shadow:    0px 0px 2px 0px rgba(0,0,0,0.2);
  -webkit-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.2);
  box-shadow:         0px 0px 2px 0px rgba(0,0,0,0.2);
}

.field>input[type=text],
.field>button {
  display:block;
  font:1.2em 'Open sans';
}

.field>input[type=text] {
  flex:1;
  padding:0.6em;
  border:0.2em solid #819090;
  border-left: none;
  border-top: none;
}

.field>button {
  padding:0.6em 0.8em;
  background-color: #217192;
  color: #fff;
  border:none;
}
</style>
<body>
  
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
          <h2>Track your shipment</h2>
          <ol>
            <li><a href="index.html">Home</a></li>
            <li>Track your shipment</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page pt-4">
      <div class="container">
        <form id="form-tracking" class="form-horizontal">
            @csrf
            <div class="form-group">
                <center>
                <input type="radio" name="search_type" value="tracking" checked> Tracking No &nbsp;&nbsp;&nbsp;
                <input type="radio" name="search_type" value="waybill"> Waybill No
                </center>
            </div>
            <div class="field tracking-div">
                <input type="text" id="searchterm" placeholder="Tracking Number" name="tracking_no" />
                <button type="button" id="search-tracking">Track</button>
            </div>
            <div class="field waybill-div" hidden>
                <input type="text" name="name"  placeholder="Shipper/Consignee (Name/Company)">
                <input type="text" name="waybill_no"  placeholder="Waybill Number">
                <button type="button" id="search-waybill">Track</button>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="datatable table table-striped">
                        <thead>
                            <tr>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr class="loading" hidden>
                                <td>
                                <center><h1><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><br><font size="2">Please wait..</font></span></h1></center>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
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

  <!-- Template Main JS File -->
  <script src="{{asset('/regna')}}/js/main.js"></script>
  <script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="{{asset('/js/messenger-plugin.js')}}"></script>
  
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
                        if(obj['url_text'] !=''){
                          window.open(obj['url_text']);
                          $("#searchterm").val('');                         
                          $('.datatable > tfoot > tr.loading').attr('hidden',true);
                        }else{
                          $('.datatable > tbody').html('<tr><td>'+obj['remarks']+'</td></tr>');
                          $('.datatable > tfoot > tr.loading').attr('hidden',true);
                        }

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