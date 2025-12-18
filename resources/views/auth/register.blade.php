<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}} | Register</title>
    <link href="{{asset(env('APP_IMG'))}}" rel="icon">
    <!-- Bootstrap -->
    <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
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

  <div class="col-xs-3"></div>
  <div class="col-xs-6">
    <div id="login" class=" form">
      <section class="login_content">
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" >
          @csrf
          <h1>Create Account <br><small>(Regular)</small></h1>
          <div style="margin-bottom: 20px;" class="col-xs-12">
              <select name="contact_status" class="form-control" required>
                  <option selected disabled value="none">--SELECT ACCOUNT TYPE--</option>
                  <option {{old('contact_status')==0 ? 'selected' : ''}} value="0">NEW ACCOUNT</option>
                  <option  {{old('contact_status')==1 ? 'selected' : ''}} value="1">CHARGE ACCOUNT</option>
              </select>
          </div>
          <div class="col-md-4 col-xs-12">
              <input type="text" class="form-control rcname reg_input" name="lname" placeholder="*Lastname" value="{{old('lname')}}" required/>
              @error('lname')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="col-md-4 col-xs-12">
              <input type="text" class="form-control rcname reg_input" name="fname" placeholder="*Firstname" value="{{old('fname')}}" required/>
              @error('fname')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="col-md-4 col-xs-12">
              <input type="text" class="form-control rcname reg_input" name="mname" placeholder="*Middlename"  value="{{old('mname')}}" required/>
          </div>
          <b class="cname_validation"></b>
          <div class="col-xs-12">
              <input type="text" class="form-control reg_input" name="company" placeholder="Company"  value="{{old('company')}}" />
          </div>
          <div class="col-md-8 col-xs-12">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror reg_input" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" />
              <b class="email_validation"></b>
                @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror

          </div>
          <div class="col-md-4 col-xs-12">
              <input type="text" class="form-control reg_input" id="contact_no"  name="contact_no" placeholder="Mobile No."  value="{{old('contact_no')}}" />
              <b class="mno_validation"></b>
              @error('contact_no')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="col-xs-6">
              <input id="password" minlength="8" type="password" class="form-control @error('password') is-invalid @enderror reg_input" name="password" required autocomplete="new-password" placeholder="*Password" required/>
              <b class="password_validation"></b>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>

          <div class="col-xs-6">
              <input id="password-confirm" minlength="8" type="password" class="form-control reg_input" name="password_confirmation" required placeholder="*Confirm Password" autocomplete="new-password"/>
          </div>


          <div class="col-xs-12" style="text-align:left;"  >

              <input type="checkbox" name="checkbox_av"  class="ace checkbox_av" />
              <span class="lbl">
                  Apply for Account Verification&emsp;<font color="red"><i>(*Verify your account to view status of your bookings.</i>)</font>
              </span>

          </div>
          <div class="col-md-6 col-xs-12 av_div" style="text-align:left;" hidden>
            ID Type:<br>
            <select class="select2_group form-control av_id_type" name="av_id_type">
              <option value="">--Select Type of ID--</option>
            </select>
            <br><br>
          </div>
          <div class="col-md-6 col-xs-12 av_div" style="text-align:left;" hidden>
            ID #:<br>
            <input  type="text" class="form-control av_id_no" name="av_id_no" />
            <br><br>
          </div>
          <div class="col-md-1 col-xs-12 av_div" hidden></div>
          <div class="col-md-5 col-xs-12 av_div" hidden style="text-align:left;border-color:#CCD1D1;background-color:white;">
            Picture of ID:
            <input accept="image/*" type="file" class="av_id_pic" name="av_id_pic" />
            <div style="height:200px">
              <img  class="img-responsive" name="photo_av_id_pic" id="photo_av_id_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
            </div>

          </div>
         <div class="col-md-1 col-xs-12 av_div" hidden></div>
          <div class="col-md-5 col-xs-12 av_div" hidden style="text-align:left;border-color:#CCD1D1;background-color:white;">
              Picture with ID:
              <input accept="image/*" type="file" class="av_id_w_pic" name="av_id_w_pic" />
              <div style="height:200px">
                <img class="img-responsive" name="photo_av_id_w_pic" id="photo_av_id_w_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
              </div>
          </div>
          <div class="col-xs-12" style="text-align:left;">
              <br>
              <input type="checkbox" required name="reg_terms_cond" class="ace" />
              <span class="lbl">
                  I accept the
                  <a href="{{url('/terms-and-condition')}}"> <font color="blue">Terms and Condition</font></a>
              </span>

          </div>
          <div id="div-otp-email-mobile" class="col-xs-12" style="text-align:left;">
              <br>
              <span class="lbl otp_registration_email">
                  <input checked type="radio" value="email" name="otp_registration" class="ace" /> Send OTP to Email (suggested)&emsp;
              </span>
              <span class="lbl otp_registration_mobile">
                  <input type="radio" value="mobile" name="otp_registration" class="ace" /> Send OTP to Mobile No.
              </span>
              <b id="div-otp-resend" style="text-align:left;"></b>
              <h4  id="h5-input-otp" ></h4>
          </div>
          <div class="col-xs-12 " id="div-otp" hidden>
            <input  type="hidden"  name="reg_otp_input_acnt" />
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

          <div class="clearfix"></div>
          <div class="separator">

            <p class="change_link">Already a member ?
              <a href="{{route('login')}}" class="to_register"> Log in </a>
            </p>
            <div class="clearfix"></div>
            <br />
            <div>
              <h1                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ><img src="{{asset(env('APP_IMG'))}}" width="25px" height="25px" alt=""> {{env('APP_NAME')}}</h1>

              <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
            </div>
          </div>
        </form>
      </section>
    </div>
  </div>
  <div class="col-xs-3"></div>

  </body>
</html>


<script src="{{asset('/js/messenger-plugin.js')}}"></script>
<script src="{{asset('/theme/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/js/register-other.js')}}"></script>

@include ('waybills.id_type_validation');
