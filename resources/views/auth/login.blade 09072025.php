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
  </head>

  <body style="background:#F7F7F7;">
    @include('fb')
    <div class="">
    
      <div id="wrapper">
        <div id="login" class="form">
          
          <section class="login_content">
            <form method="POST" autocomplete="off" action="{{ route('login') }}">
              @csrf
              <h1>Login Form</h1>
              <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .
              <br><br>
              <div>
                <p>
                  <input {{ old('rpc_account')==0 ? 'checked' : '' }} type="radio"  name="rpc_account" value="0" /> Regular Account &emsp;
                  <input {{ old('rpc_account')==1 || session()->has('pca_no_account_found') ? 'checked' : '' }} type="radio" name="rpc_account" value="1"  /> Personal/Corporate Account
                </p>
               
              </div>
              @if(session()->has('pca_no_account_found'))
                <span style="color:red;" class="invalid-feedback" role="alert">
                  <strong>{{ session()->get('pca_no_account_found') }}</strong>
                </span>
              @endif
              <div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                    <span style="color:red;" class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                @error('password')
                    <span style="color:red;" class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              
              <div>
                <button type="submit" class="btn btn-default submit">Log in</button>
              </div>
              <div class="clearfix"></div>
              <div class="separator">
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
                  <a href="{{url('register')}}" class="to_register"> Create Regular Account </a> | &nbsp;&nbsp;&nbsp;<a href="{{url('/')}}" class="to_register"> Landing Page </a> | &nbsp;&nbsp;&nbsp;<a href="{{url('/create-booking')}}" class="to_register"> Book as Guest </a>
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
  </body>
</html>