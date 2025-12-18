<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}} | DOFF Website Set Account Password</title>
    <link href="{{asset(env('APP_IMG'))}}" rel="icon">
    <!-- Bootstrap -->
    <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
  </head>

  <body style="background:#F7F7F7;">
    @include('fb')
    <div class="">

      <div id="wrapper">


        @if($result && $result->password=='')

        <div id="login" class="form">
            <section class="login_content">
                <form method="POST" id="doff_pwd_form">
                <h4>Alternative Log In Option</h4>
                <h1> Set your Account Password</h1>

                <h3>Hi! {{ strtoupper($result->name) }}</h3>
                <h4>{{ $result->personal_corporate==1 ? 'Premium' : 'Regular' }} Account</h4>

                <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .
                 <hr>
                <div >
                    <input  value="{{ $result->contact_id }}" id="doff_contact_id" type="hidden"  name="doff_contact_id" />
                     <input  value="{{ $url_code }}" id="url_code" type="hidden"  name="url_code" />
                    <input minlength="8"  onKeyup="doff_validation_func()" id="doff_password" type="password"  name="doff_password" class="form-control" placeholder="Password" required >
                    <span class="invalid-feedback doff-pwd-msg" role="alert"></span>
                </div>
                <div>
                    <input minlength="8"  onKeyup="doff_validation_func()" id="doff_retype_password" type="password"  name="doff_retype_password" class="form-control" placeholder="Re-Type Password" required >

                </div>
                <div  >
                    <button type="submit" id="doff_submit_btn" class="btn btn-default submit">Submit</button>
                </div>
                <div class="clearfix"></div>

                <div class="separator">

                    <p class="change_link">
                    <a href="{{url('/')}}" class="to_register"> Landing Page </a>
                    </p>
                    <div class="clearfix"></div>
                    <br />
                    <div>
                    <h1><img src="{{asset(env('APP_IMG'))}}" width="25px" height="25px" alt=""> {{env('APP_NAME')}}</h1>

                    <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                    </div>
                </div>
                </form>
            </section>
        </div>
        @elseif($result && $result->password !='')
        <div id="login" class="form">
            <section class="login_content">
                <form method="POST" id="doff_pwd_form">
                <h4>Alternative Log In Option</h4>
                <h1> Set your Account Password</h1>

                <h3>Hi! {{ strtoupper($result->name) }}</h3>
                <h4>{{ $result->personal_corporate==1 ? 'Premium' : 'Regular' }} Account</h4>

                <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .


                <h1 style="color:green;"><br><i class="fa fa-check"></i> Password Successfully Updated</h1>

                <div class="clearfix"></div>

                <div class="separator">

                    <p class="change_link">
                    <a href="{{url('/')}}" class="to_register"> Landing Page </a>
                    </p>
                    <div class="clearfix"></div>
                    <br />
                    <div>
                    <h1><img src="{{asset(env('APP_IMG'))}}" width="25px" height="25px" alt=""> {{env('APP_NAME')}}</h1>

                    <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                    </div>
                </div>
                </form>
            </section>
        </div>
        @else
        <div class="col-middle">
            <div class="text-center text-center">
                <h1><i class="fa fa-cogs"></i> Page not found.</h1>
            </div>
        </div>

        @endif

      </div>
    </div>
    <script src="{{asset('/js/messenger-plugin.js')}}"></script>
    <script src="{{asset('/js/jquery.min.js')}}"></script>
    <script src="{{asset('/js')}}/sweetalert2.js"></script>

  </body>
</html>

<script>

    function doff_validation_func(){

        not_proceed=0;
        if(
            $("#doff_retype_password").val()=='' ||
            $("#doff_password").val()==''

        ){
            not_proceed++;
        }
        $(".doff-pwd-msg").hide();
        $(".doff-pwd-msg").html('');
        if(
            $("#doff_retype_password").val() != $("#doff_password").val()
            && $("#doff_password").val() !=''
            && $("#doff_retype_password").val() !=''
        ){
            $(".doff-pwd-msg").show();
            $(".doff-pwd-msg").html('<strong style="color:red;" >Password Mismatch</strong>');
            not_proceed++;
        }
        if(not_proceed > 0){
            $("#doff_submit_btn").prop('disabled',true);
        }else{
            $("#doff_submit_btn").prop('disabled',false);
        }
    }
    doff_validation_func();
    $("#doff_pwd_form").submit(function(){

        event.preventDefault();

        $.ajax({
            url: "/doff-set-pwd",
            type: "POST",
            data:
            {
                _token: "{{csrf_token()}}",
                id:$("#doff_contact_id").val(),
                doff_password:$("#doff_password").val()
            },
            async: true,
            dataType: 'json',
            cache: false,
            success: function(result){

                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message
                }).then((res) => {

                    if(atob($("#url_code").val())=='doff_user_login'){
                        window.location='/login';
                    }else{
                        location.reload();
                    }

                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong'
                });
            }
        });

    });
</script>
