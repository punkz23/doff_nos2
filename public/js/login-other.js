  const inputs_otp = document.getElementById("inputs-otp");

  inputs_otp.addEventListener("input", function (e) {
      const target = e.target;
      const val = target.value;

      if (isNaN(val)) {
          target.value = "";
          return;
      }

      if (val != "") {
          const next = target.nextElementSibling;
          if (next) {
              next.focus();
          }
      }
  });

  inputs_otp.addEventListener("keyup", function (e) {
      const target = e.target;
      const key = e.key.toLowerCase();

      if (key == "backspace" || key == "delete") {
          target.value = "";
          const prev = target.previousElementSibling;
          if (prev) {
              prev.focus();
          }
          return;
      }
  });


  function get_web_otp(){
    $('input[name="enable_disable_otp"]').val(0);
    $.getJSON('/check-web-otp', function(result){
        $('input[name="enable_disable_otp"]').val(0);
        $.each(result, function() {

            $('input[name="enable_disable_otp"]').val(this.setting_value);
            if(Number(this.setting_value)==0){
                $('input[name="use_pwd"]').val(1);
            }
        });
        get_doff_cat();
    });

  }
  get_web_otp();
  function get_doff_cat(){
    $(".switch_pc_reg").html('Switch to Premium Account');
    document.getElementById('email').removeAttribute('readonly');
    document.getElementById('div-log-in-btn').innerHTML ='<button disabled id="log-in-btn" onclick="otp_send_func()" type="button" class="btn btn-default submit">Send One Time Log-In Code</button>';
    document.getElementById('h_doff_acnt').innerHTML='REGULAR ACCOUNT<br>';
    document.getElementById('rpc_account_0').checked=true;
    $(".p_acnt_em").show();
    $(".div_google_login").show();
    if( atob(localStorage.getItem('doff_cat'))=='pca' ){
      $(".div_google_login").hide();
      document.getElementById('rpc_account_1').checked=true;
      document.getElementById('h_doff_acnt').innerHTML='PREMIUM ACCOUNT<br>';
      $(".p_acnt_em").hide();
      $(".switch_pc_reg").html('Switch to Regular Account');
    }
    $('input[name="login_otp_input_acnt_type"]').val($("input[name=acnt_em]:checked").val());
    validate_form();
    if( Number($('input[name="use_pwd"]').val())==1 ){
        $(".div_use_password").show();
        log_in_pwd();
    }else{
        $(".div_use_password").hide();
    }

  }
  function log_in_pwd(){


        $('input[name="use_pwd"]').val(1);
        $("#password").prop('required',true);
        $(".div_use_password").show();
        $("#div-log-in-btn").html('<button  onclick="log_in_btn_pwd_func()" id="log-in-btn"  type="button" class="btn btn-default submit ">Log In</button><div hidden><button  id="log-in-pwd-btn"  type="submit" class="btn btn-default submit">Log In</button></div>');
        $('#div-otp-resend').html('');
        if(Number($('input[name="enable_disable_otp"]').val())==1){
            $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Back to One Time Log-in Code</u></a>');
        }
        document.getElementById('div-otp').hidden=true;
        $("#h5-input-otp").html('');
        validate_form();


  }
  function log_in_btn_pwd_func(){
    $(".password_validation").html("");
    $(".email_validation").html("");
    document.getElementById("email").style.border="";
    document.getElementById("password").style.border="";

    if( $('input[name="email"]').val() !='' &&  $('input[name="password"]').val() !=''  ){

       $.ajax({
        url: "/validate-user-pwd/"+btoa($("input[name=rpc_account]:checked").val())
        +"/"+btoa($("input[name=acnt_em]:checked").val())
        +"/"+btoa($("#email").val())
        +"/"+btoa($("#password").val())
        ,
        method: 'get',
        success: function(result){
          if(Number(result)==0){
            $(".email_validation").html("<font color='red'>Sorry account does not exist.<br></font>");
            document.getElementById("email").style.border="1px solid red";
          }
          else if(Number(result)==2){
            $(".password_validation").html("<font color='red'>Sorry incorrect password.<br></font>");
            document.getElementById("password").style.border="1px solid red";
          }else{
            $("#log-in-pwd-btn").trigger('click');
          }

      }});

    }else{
        if($('input[name="email"]').val() ==''){
            $(".email_validation").html("<font color='red'>Empty user.<br></font>");
            document.getElementById("password").style.border="1px solid red";
        }
        if($('input[name="password"]').val() ==''){
            $(".password_validation").html("<font color='red'>Empty Password.<br></font>");
            document.getElementById("password").style.border="1px solid red";
        }
    }

  }

  $(".switch_pc_reg").click(function(){
    if( atob(localStorage.getItem('doff_cat'))=='regular' ){
        localStorage['doff_cat']=btoa('pca');
    }else{
        localStorage['doff_cat']=btoa('regular');
    }
    window.location="/home";
  });

  $('input[name="acnt_em"]').click(function(){
    acnt_em_func();
  });
  function acnt_em_func(){
    $("#email").val('');
    $(".email_validation").html("");
    $(".password_validation").html("");
    document.getElementById("email").style.border="";
    val=$("input[name=acnt_em]:checked").val();
    document.getElementById('email').placeholder='Email';
    $("#email").attr('type', 'email');
    if(val=='mobile'){
        $("#email").attr('type', 'text');
        document.getElementById('email').placeholder='Mobile No.';
    }
  }

  function otp_send_func(){

    $('input[name="use_pwd"]').val(0);
    $("#password").prop('required',false);
    $(".div_use_password").hide();

    $(".otp_validation").html("");
    $('.login_input').prop('readonly',true);
    document.getElementById('div-otp').hidden=true;
    $('#div-otp-resend').html('');
    $("#h5-input-otp").html('');
    $(".input-otp").val('');
    $('input[name="login_otp_input_acnt"]').val('');
    $('input[name="login_otp_input_acnt_type"]').val('');

    $("#div-log-in-btn").html('<button disabled id="log-in-btn"  type="submit" class="btn btn-default submit">Log In</button>');
    if(
      $("input[name=acnt_em]:checked").val() =='email' && $("#email").val() ==''
    ){
      $(".otp_validation").html("<br><font color='red'>Email is required for sending OTP.<br></font>");
    }
    else if(
      $("input[name=acnt_em]:checked").val() =='mobile' && $("#email").val() ==''
    ){
      $(".otp_validation").html("<br><font color='red'>Mobile No. is required for sending OTP.<br></font>");
    }
    else{
      email=$("#email").val();
      email_txt='Email';
      if($("input[name=acnt_em]:checked").val()=='mobile'){
        email_txt='Mobile No.';
      }
      $("#h5-input-otp").html('<center><br>Enter the code sent to your '+email_txt+' to proceed.</center>');
      $(".otp_validation").html('<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait while sending ..</div>');
      $.ajax({
        url: "/send-reg-otp/"+btoa('login')+"/"+btoa($("input[name=acnt_em]:checked").val())+"/"+btoa(email),
        method: 'get',
        success: function(result){

          if(result.type=='success'){
            $('input[name="login_otp_input_acnt"]').val(email);
            $('input[name="login_otp_input_acnt_type"]').val($("input[name=acnt_em]:checked").val());
            $(".otp_validation").html("<br><font color='green'>"+result.message+"<br></font>");
            document.getElementById('div-otp').hidden=false;
            otp_resend_func(result.date_exp);
          }else{
            $(".otp_validation").html("<br><font color='red'>"+result.message+"<br></font>");
            $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a> or <a style="color:blue;" onclick="log_in_pwd()" >&emsp;<u>Log-in Using Password</u></a> ');
            $(".otp_validation").html("");
          }

      }});

    }
  }
  function otp_resend_func(date_exp){

    var timer;
    var compareDate = new Date(date_exp);

    timer = setInterval(function() {
      var dateEntered = compareDate;
      var now = new Date();
      var difference = dateEntered.getTime() - now.getTime();
      if (difference <= 0) {
        clearInterval(timer);
        $("input[name=acnt_em]").prop('disabled',false);
        $('.login_input').prop('readonly',false);
        $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a> or <a style="color:blue;" onclick="log_in_pwd()" >&emsp;<u>Log-in Using Password</u>');
        $(".otp_validation").html("");
      } else {

        var seconds = Math.floor(difference / 1000);
        var minutes = Math.floor(seconds / 60);
        var hours = Math.floor(minutes / 60);
        var days = Math.floor(hours / 24);

        hours %= 24;
        minutes %= 60;
        seconds %= 60;

        $("input[name=acnt_em]").prop('disabled',true);
        $('.login_input').prop('readonly',true);
        $('#div-otp-resend').html('<a>&emsp;<u>Resend Code available in ('+(String(minutes).padStart(2, '0'))+':'+(String(seconds).padStart(2, '0'))+')</u></a>');
      }
    }, 1000);


  }

  function validate_otp(){

    var count_empty = $('.input-otp').not(function() { return this.value; }).length;

    if( count_empty == 0){
      $(".otp_validation").html('<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Checking ..</div>');
      otp=$("#input-digit1").val()+$("#input-digit2").val()+$("#input-digit3").val()+$("#input-digit4").val()+$("#input-digit5").val();
      $(".submit").hide();
      $.ajax({
          url: "/validate-reg-otp/"+btoa($("input[name=login_otp_input_acnt]").val())+'/'+btoa(otp),
          method: 'get',
          success: function(result){

            if(Number(result)==2){
              $(".otp_validation").html("<font color='red'><i class='fa fa-times'></i> OTP Expired.<br></font>");
              $(".submit").prop('disabled', true);
            }
            else if(Number(result)==0){
              $(".otp_validation").html("<font color='red'><i class='fa fa-times'></i> Invalid OTP.<br></font>");
              $(".submit").prop('disabled', true);
            }
            else if(Number(result)==1){
              $(".otp_validation").html("<h3><font color='green' style='font-weight:bold;'><image width='15%' height='15%' src='/images/check-green.gif' /> OTP Verified <br></font></h3>");
              document.getElementById('div-otp').hidden=true;
              document.getElementById('div-otp-email-mobile').hidden=true;
            }
            $(".submit").show();
        }});
    }

  }

  function validate_email(){

    $(".email_validation").html("");
    document.getElementById("email").style.border="";
    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    var mobileno = /^\d{11}$/;
    if(
      $("input[name=acnt_em]:checked").val() =='email'
      && $("#email").val() !=''
      && !document.getElementById('email').value.match(mailformat)
    ){
      $(".email_validation").html("<font color='red'>Invalid Email.<br></font>");
      document.getElementById("email").style.border="1px solid red";
      $(".submit").prop('disabled', true);
    }
    else if(
      $("input[name=acnt_em]:checked").val() =='mobile'
      && $("#email").val() !=''
      && !document.getElementById('email').value.match(mobileno)
    ){
      $(".email_validation").html("<font color='red'>Invalid number. 11 digits is required (ex. 09XXXXXXXXX).<br></font>");
      document.getElementById("email").style.border="1px solid red";
      $(".submit").prop('disabled', true);
    }
    else if($("#email").val() !=''){
      $.ajax({
        url: "/login-validate-email/"+btoa($("input[name=rpc_account]:checked").val())+"/"+btoa($("input[name=acnt_em]:checked").val())+"/"+btoa($("#email").val()),
        method: 'get',
        success: function(result){
          if(Number(result)==0){
            $(".email_validation").html("<font color='red'>Sorry account does not exist.<br></font>");
            document.getElementById("email").style.border="1px solid red";
            $(".submit").prop('disabled', true);
          }
      }});
    }
  }
  $(".input-otp").keyup(function(){
    validate_form();
  });
  $("#email").keyup(function(){
    validate_form();
  });
  $("#password").keyup(function(){
    $(".password_validation").html("");
    document.getElementById("password").style.border="";
    validate_form();
  });

  function validate_form(){

    $(".submit").prop('disabled', false);
    var type = $("#log-in-btn").attr('type');
    var count_empty = $('.input-otp').not(function() { return this.value; }).length;

    if(  Number($('input[name="use_pwd"]').val())==1 ){
        if(
          $("#email").val() =='' || $("#password").val() ==''
        ){
            $(".submit").prop('disabled', true);
        }
        validate_email();
    }else{

        if(
            $("#email").val() ==''
            || (type== 'submit' &&  count_empty > 0)
        ){
            $(".submit").prop('disabled', true);
        }
        validate_email();
        validate_otp();
    }

  }
