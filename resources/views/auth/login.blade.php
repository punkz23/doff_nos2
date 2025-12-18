<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}} | Login</title>
    <link href="{{asset(env('APP_IMG'))}}" rel="icon">
    <!-- Bootstrap -->
    <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
    <script src="{{asset('/gentelella')}}/vendors/additional/jquery-1.9.1.js"></script>
    <script src="{{asset('/gentelella')}}/vendors/additional/jquery-ui.js"></script>
  </head>
  <style>
    /* style.css */
      .container-otp {
          display: flex;
          justify-content: center;
          align-items: center;
          /* min-height: 100vh; */
      }

      .input-otp {
          width: 40px;
          border: none;
          border-bottom: 3px solid rgba(0, 0, 0, 0.5);
          margin: 0 10px;
          text-align: center;
          font-size: 36px;
          /* cursor: not-allowed; */
          /* pointer-events: none; */
      }

      .input-otp:focus {
          border-bottom: 3px solid orange;
          outline: none;
      }

      .input-otp:nth-child(1) {
          cursor: pointer;
          pointer-events: all;
      }

  </style>
  <body style="background:#F7F7F7;">
    @include('fb')
    <div class="">

      <div id="wrapper">
        <div id="login" class="form">
          <section class="login_content">
            <form method="POST" autocomplete="off" action="{{ route('login') }}">
              @csrf

              <h1>Login Form</h1>
              <h4 id="h_doff_acnt">REGULAR ACCOUNT</h4>
              <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .
              <br><br>
              <input  value="{{ old('use_pwd')=='' ? 0 : old('use_pwd') }}" type="hidden"  name="use_pwd" />
              <input   type="hidden"  name="enable_disable_otp" />
              <div hidden>
                <p>
                  <input  type="radio" id="rpc_account_0" name="rpc_account" value="0" /> Regular Account &emsp;
                  <input  type="radio" id="rpc_account_1"  name="rpc_account" value="1"  /> Premium Account
                </p>
              </div>
              @if(session()->has('pca_no_account_found'))
                <span style="color:red;" class="invalid-feedback" role="alert">
                  <strong>{{ session()->get('pca_no_account_found') }}</strong>
                </span>
              @endif

              <a class="col-xs-12" style="text-align:right;cursor: pointer;" ><small><u style="cursor: pointer;" class="switch_pc_reg"></u></small></a>

              <div class="col-xs-12" style="text-align:left;">
                <p class="p_acnt_em">
                  <input checked type="radio"   name="acnt_em" value="email" /> Email&nbsp;
                  <input  type="radio"  name="acnt_em" value="mobile"  /> Mobile No.
                </p>
              </div>
              <div class="col-xs-12">
                <input id="email" type="email" class="form-control login_input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                    <span style="color:red;" class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <b class="email_validation"></b>
              </div>
              <div class="col-xs-12 div_use_password" >
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password"  autocomplete="current-password">
                @error('password')
                    <span style="color:red;" class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <b class="password_validation"></b>
              </div>
              <div id="div-otp-email-mobile" class="col-xs-12" style="text-align:left;">
                <b id="div-otp-resend" style="text-align:left;"></b>
                <h5 id="h5-input-otp" ></h5>
              </div>
              <div class="col-xs-12 " id="div-otp" hidden>
                <input  type="hidden"  name="login_otp_input_acnt" />
                <input  type="hidden"  name="login_otp_input_acnt_type" />
                <div class="col-xs-12 container-otp">
                  <div id="inputs-otp" class="inputs-otp">
                      <input style="width:40px;" id="input-digit1" class="input-otp" type="text"
                          inputmode="numeric" maxlength="1" />
                      <input style="width:40px;" id="input-digit2" class="input-otp" type="text"
                          inputmode="numeric" maxlength="1" />
                      <input style="width:40px;" id="input-digit3" class="input-otp" type="text"
                          inputmode="numeric" maxlength="1" />
                      <input style="width:40px;" id="input-digit4" class="input-otp" type="text"
                          inputmode="numeric" maxlength="1" />
                      <input style="width:40px;" id="input-digit5" class="input-otp" type="text"
                          inputmode="numeric" maxlength="1" />
                  </div>
                </div>
              </div>
              <b class="otp_validation"></b>
              <div id="div-log-in-btn" class="col-xs-12" style="text-align:right;"></div>

              <!--div id="div-log-in-btn">
                <button id="log-in-btn" type="submit" class="btn btn-default submit">Log in</button>
              </div-->
              <div class="clearfix div_google_login"></div>
              <div class="separator div_google_login">
                <div class="social-login center">
                    <!-- <a href="{{ url('auth/facebook') }}" class="btn btn-primary social-button" title="Facebook">
                        <i class="ace-icon fa fa-facebook"></i>
                        Login with Facebook
                    </a> -->

                    <a href="{{ url('auth/google') }}" class="btn btn-danger social-button" title="Google">
                        <i class="ace-icon fa fa-google-plus"></i>
                        Login with Google
                    </a>
                </div>
              </div>
              <div class="clearfix"></div>


              <div class="separator">

                <p class="change_link">New to site?
                  <a href="{{url('register')}}" class="to_register"> Register for Regular Account </a> | &nbsp;<a href="{{url('/')}}" class="to_register"> Landing Page </a> <!-- | &nbsp;&nbsp;&nbsp;<a href="{{url('/create-booking')}}" class="to_register"> Book as Guest </a-->
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
      </div>
    </div>

    <script src="{{asset('/js/messenger-plugin.js')}}"></script>
    <script src="{{asset('/js/login-other.js')}}"></script>
  </body>
</html>
