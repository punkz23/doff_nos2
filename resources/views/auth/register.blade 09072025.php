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

  <body style="background:#F7F7F7;">
    @include('fb')
  
  <div class="col-xs-3"></div> 
  <div class="col-xs-6">
    <div id="login" class=" form">
      <section class="login_content">
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" >
          @csrf
          <h1>Create Account</h1>
          <div style="margin-bottom: 20px;" class="col-xs-12">
              <select name="contact_status" class="form-control" required>
                  <option selected disabled value="none">--SELECT ACCOUNT TYPE--</option>
                  <option {{old('contact_status')==0 ? 'selected' : ''}} value="0">NEW ACCOUNT</option>
                  <option  {{old('contact_status')==1 ? 'selected' : ''}} value="1">CHARGE ACCOUNT</option>
              </select>
          </div>
          <div class="col-xs-4">
              <input type="text" class="form-control" name="lname" placeholder="*Lastname" value="{{old('lname')}}" required/>
              @error('lname')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="col-xs-4">
              <input type="text" class="form-control" name="fname" placeholder="*Firstname" value="{{old('fname')}}" required/>
              @error('fname')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="col-xs-4">
              <input type="text" class="form-control" name="mname" placeholder="*Middlename"  value="{{old('mname')}}" required/>
          </div>
          <div class="col-xs-12">
              <input type="text" class="form-control" name="company" placeholder="Company"  value="{{old('company')}}" />
          </div>
          <div class="col-xs-4">
              <input type="text" class="form-control" name="contact_no" placeholder="*Contact #"  value="{{old('contact_no')}}" required/>
              @error('contact_no')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>

          <div class="col-xs-8">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="*Email" required/>
              <b class="email_validation"></b>
                @error('email') 
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              
          </div>
          
          <div class="col-xs-6">
              <input id="password" minlength="8" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="*Password" required/>
              <b class="password_validation"></b>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>

          <div class="col-xs-6">
              <input id="password-confirm" minlength="8" type="password" class="form-control" name="password_confirmation" required placeholder="*Confirm Password" autocomplete="new-password"/>
          </div>  
          <div class="col-xs-12" style="text-align:left;"  >
            
              <input type="checkbox" name="checkbox_av"  class="ace checkbox_av" />
              <span class="lbl">
                  Apply for Account Verification&emsp;<font color="red"><i>(*Verify your account to view status of your bookings.</i>)</font>
              </span>
             
          </div>
          <div class="col-xs-6 av_div" style="text-align:left;" hidden>
            ID Type:
            <select class="select2_group form-control av_id_type" name="av_id_type">
              <option value="">--Select Type of ID--</option>
            </select>
            <br><br>
          </div>
          <div class="col-xs-6 av_div" style="text-align:left;" hidden>
            ID #:
            <input  type="text" class="form-control av_id_no" name="av_id_no" />
            <br><br>
          </div>
          <div class="col-xs-1 av_div" hidden></div>
          <div class="col-xs-5 av_div" hidden style="text-align:left;border-color:#CCD1D1;background-color:white;">
            Picture of ID:
            <input accept="image/*" type="file" class="av_id_pic" name="av_id_pic" />
            <div style="height:200px">
              <img  class="img-responsive" name="photo_av_id_pic" id="photo_av_id_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
            </div>
            
          </div>
         <div class="col-xs-1 av_div" hidden></div>
          <div class="col-xs-5 av_div" hidden style="text-align:left;border-color:#CCD1D1;background-color:white;">
              Picture with ID:
              <input accept="image/*" type="file" class="av_id_w_pic" name="av_id_w_pic" />
              <div style="height:200px">
                <img class="img-responsive" name="photo_av_id_w_pic" id="photo_av_id_w_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
              </div>
          </div>
          <div class="col-xs-12" style="text-align:left;">
              <br>
              <input type="checkbox" class="ace" />
              <span class="lbl">
                  I accept the
                  <a href="{{url('/terms-and-condition')}}"> <font color="blue">Terms and Condition</font></a>
              </span>
            
          </div>
          
          <div class="col-xs-12" style="text-align:right;">
            <br>
            <button type="submit" class="btn btn-info submit" disabled >Submit</button>
          </div>
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

<script type="text/javascript">
$(document).ready(function(){
  $(".checkbox_av").click(function(){
    if(this.checked){
      $('.av_id_type').prop('required', true);
      $('.av_id_no').prop('required', true);      
      $('.av_id_pic').prop('required', true);
      $('.av_id_w_pic').prop('required', true);
      get_id_type();
      $(".av_div").show();
    }else{
      $('.av_id_type').prop('required', false);
      $('.av_id_no').prop('required', false);
      $('.av_id_pic').prop('required', false);
      $('.av_id_w_pic').prop('required', false);
      $(".av_div").hide();
    }
  });
  $("#email").keyup(function(){
    //validate_email();
    validate_form();
  });
  $("#password").keyup(function(){
    validate_form();
  });
  $("#password-confirm").keyup(function(){
    validate_form();
  });

  function validate_email(){
    
    $(".email_validation").html("");
    document.getElementById("email").style.border="";	
    //not_proceed=0;
    //$(".submit").prop('disabled', false);
    if($("#email").val() !=''){
      $.ajax({
        url: "{{url('/validate-email')}}/"+$("#email").val(), 
        method: 'get',
        success: function(result){
          if(Number(result)==1){
            $(".email_validation").html("<font color='red'>Sorry email's already registered.</font>");
            document.getElementById("email").style.border="1px solid red";	
            $(".submit").prop('disabled', true);
            
            
          }
          

      }});
    } 

  }
  function validate_password(){

    $(".password_validation").html("");
    document.getElementById("password").style.border="";	
    document.getElementById("password-confirm").style.border="";

    if( $("#password").val() != $("#password-confirm").val() && $("#password").val() != '' && $("#password-confirm").val() !='' ){

      $(".password_validation").html("<font color='red'>Password don't match.</font>");
      document.getElementById("password").style.border="1px solid red";	
      document.getElementById("password-confirm").style.border="1px solid red";
      $(".submit").prop('disabled', true);

    }
  }
  function validate_form(){
    $(".submit").prop('disabled', false);
    validate_email();
    validate_password();
  }
  

  

  

});
</script>
@include ('waybills.id_type_validation');
