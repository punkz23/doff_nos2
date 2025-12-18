<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}} | Activation</title>
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

        @if($result)
        <div id="login" class="form">

          <section class="login_content">
            <form method="POST" id="pca_activation_form">
              <h1>Activation Form</h1>
              <h3>Hi! {{ $result== true ? $result->full_name : '' }}</h3>
              <p {{ $result== true && $result->contact_id !='' ? 'hidden':''  }} ><i>Please input the following credentials, to activate your {{ $result->personal_corporate=='publication' ? 'premium publication' : 'premium' }} account. Thank you.</i></p>
              <p {{ $result== true && $result->contact_id !='' ? '':'hidden'  }}><i>Account already activated. Go to Log-in Page to access your account.</i></p>
              <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .

              <div {{ $result== true && $result->contact_id !='' ? 'hidden':''  }} >
                <br><br>
                <input  value="{{ $result== true ? $result->pca_account_no :''  }}" id="pca_no" type="hidden"  name="pca_no"  >
                <input  value="{{ $result== true ? $result->account_code : '' }}" id="info_pca_code" type="hidden"  name="info_pca_code"  >

                <input readonly value="{{ $result== true ? $result->email : '' }}" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required autocomplete="email" autofocus>
              </div>
              <div {{ $result== true && $result->contact_id !='' ? 'hidden':''  }}>
                <input onKeyup="pca_validation_func()" id="pca_account_code" type="text"  name="pca_account_code" class="form-control" placeholder="Account Code" required>
                <span class="invalid-feedback pca-acnt-code-msg" role="alert"></span>
              </div>
              <div {{ $result== true && $result->contact_id !='' ? 'hidden':''  }}>
                <input minlength="8"  onKeyup="pca_validation_func()" id="pca_password" type="password"  name="pca_password" class="form-control" placeholder="Password" required >
                <span class="invalid-feedback pca-pwd-msg" role="alert"></span>
              </div>
              <div {{ $result== true && $result->contact_id !='' ? 'hidden':''  }}>
                <input minlength="8"  onKeyup="pca_validation_func()" id="pca_retype_password" type="password"  name="pca_retype_password" class="form-control" placeholder="Re-Type Password" required >

              </div>
              <div {{ $result== true && $result->contact_id !='' ? 'hidden':''  }} >
                <button type="submit" id="pca_submit_btn" class="btn btn-default submit">Submit</button>
              </div>
              <div class="clearfix"></div>

              <div class="separator">

                <p class="change_link">
                  <a onclick="customer_login_func('pca')" > Log-In Page</a> | &nbsp;&nbsp;&nbsp;<a href="{{url('/')}}" class="to_register"> Landing Page </a>
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
    function customer_login_func(acnt){
      localStorage['doff_cat']=btoa(acnt);
      window.location="{{route('home')}}";
    }
    function pca_validation_func(){

        not_proceed=0;
        if(
            $("#pca_retype_password").val()=='' ||
            $("#pca_password").val()=='' ||
            $("#pca_account_code").val()==''

        ){
            not_proceed++;
        }
        $(".pca-pwd-msg").hide();
        $(".pca-pwd-msg").html('');
        if(
            $("#pca_retype_password").val() != $("#pca_password").val()
            && $("#pca_password").val() !=''
            && $("#pca_retype_password").val() !=''
        ){
            $(".pca-pwd-msg").show();
            $(".pca-pwd-msg").html('<strong style="color:red;" >Password Mismatch</strong>');
            not_proceed++;
        }
        $(".pca-acnt-code-msg").hide();
        $(".pca-acnt-code-msg").html('');
        if(
            $("#info_pca_code").val() != $("#pca_account_code").val()
            && $("#pca_account_code").val() !=''
        ){
            $(".pca-acnt-code-msg").show();
            $(".pca-acnt-code-msg").html('<strong style="color:red;">Invalid Account Code</strong>');
            not_proceed++;
        }

        if(not_proceed > 0){
            $("#pca_submit_btn").prop('disabled',true);
        }else{
            $("#pca_submit_btn").prop('disabled',false);
        }
    }
    pca_validation_func();
    $("#pca_activation_form").submit(function(){

        event.preventDefault();

        $.ajax({
            url: "/pc-activate-account",
            type: "POST",
            data:
            {
                _token: "{{csrf_token()}}",
                pca_no:$("#pca_no").val(),
                email:$("#email").val(),
                pca_password:$("#pca_password").val()
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
                    if(result.type=='success'){
                        window.location='/login';
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
