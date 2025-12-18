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

  function otp_send_func(){

    $(".otp_validation").html("");
    $('.reg_input').prop('readonly',true);
    document.getElementById('div-otp').hidden=true;
    $('#div-otp-resend').html('');
    $("#h5-input-otp").html('');
    $(".input-otp").val('');
    $('input[name="reg_otp_input_acnt"]').val('');
    $("#div-log-in-btn").html('<br><button id="reg_btn" type="submit" class="btn btn-info submit" disabled >Submit</button>');

    if(
      $("input[name=otp_registration]:checked").val() =='email' && $("#email").val() ==''
    ){
      $(".otp_validation").html("<br><font color='red'>Email is required for sending OTP.<br></font>");
    }
    else if(
      $("input[name=otp_registration]:checked").val() =='mobile' && $("#contact_no").val() ==''
    ){
      $(".otp_validation").html("<br><font color='red'>Mobile No. is required for sending OTP.<br></font>");
    }
    else{
      email=$("#email").val();
      email_txt='Email';
      if($("input[name=otp_registration]:checked").val()=='mobile'){
        email=$("#contact_no").val();
        email_txt='Mobile No.';
      }
      $("#h5-input-otp").html('<center><br>Enter the code sent to your '+email_txt+' to submit registration.</center>');
      $(".otp_validation").html('<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait while sending ..</div>');
      $.ajax({
        url: "/send-reg-otp/"+btoa('reg')+"/"+btoa($("input[name=otp_registration]:checked").val())+"/"+btoa(email),
        method: 'get',
        success: function(result){

          if(result.type=='success'){
            $('input[name="reg_otp_input_acnt"]').val(email);
            $(".otp_validation").html("<br><font color='green'>"+result.message+"<br></font>");
            document.getElementById('div-otp').hidden=false;
            otp_resend_func(result.date_exp);
          }else{
            $(".otp_validation").html("<br><font color='red'>"+result.message+"<br></font>");
            $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a>');
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
        $("input[name=otp_registration]").prop('disabled',false);
        $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a>');

      } else {

        var seconds = Math.floor(difference / 1000);
        var minutes = Math.floor(seconds / 60);
        var hours = Math.floor(minutes / 60);
        var days = Math.floor(hours / 24);

        hours %= 24;
        minutes %= 60;
        seconds %= 60;

        $("input[name=otp_registration]").prop('disabled',true);
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
          url: "/validate-reg-otp/"+btoa($("input[name=reg_otp_input_acnt]").val())+'/'+btoa(otp),
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
              $(".otp_validation").html("<h3><font color='green' style='font-weight:bold;'><image width='10%' height='10%' src='/images/check-green.gif' /> OTP Verified <br></font></h3>");
              document.getElementById('div-otp').hidden=true;
              document.getElementById('div-otp-email-mobile').hidden=true;
            }
            $(".submit").show();
        }});
    }

  }

  function reg_start(){
    $("#div-log-in-btn").html('<br><button id=""reg_btn"  disabled type="button" onclick="otp_send_func()" class="btn btn-info submit">Send One Time Registration Code</button>');
    $('.reg_input').prop('readonly',false);
    $("#h5-input-otp").html('');
    $('#div-otp-resend').html('');
    validate_form();
  }
  reg_start();
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

  $(".input-otp").keyup(function(){
    validate_form();
  });

  $("input[name=otp_registration]").click(function(){
    validate_form();
  });
  $("#contact_no").keyup(function(){
    validate_form();
  });
  $("#email").keyup(function(){
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
    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    if( $("#email").val() !='' && !document.getElementById('email').value.match(mailformat))
		{
      $(".email_validation").html("<font color='red'>Invalid email.<br></font>");
      document.getElementById("email").style.border="1px solid red";
      $(".submit").prop('disabled', true);
    }
    else if($("#email").val() !=''){
      $.ajax({
        url: "/validate-email/"+$("#email").val(),
        method: 'get',
        success: function(result){
          if(Number(result)==1){
            $(".email_validation").html("<font color='red'>Sorry email's already registered.<br></font>");
            document.getElementById("email").style.border="1px solid red";
            $(".submit").prop('disabled', true);
          }


      }});
    }


  }
  function validate_cno(){

    $(".mno_validation").html("");
    document.getElementById("contact_no").style.border="";
    var mobileno = /^\d{11}$/;
    if( $("#contact_no").val() !='' && !($("#contact_no").val().match(mobileno)) ){
      $(".mno_validation").html("<font color='red'>Invalid number. 11 digits is required (ex. 09XXXXXXXXX).<br></font>");
      document.getElementById("contact_no").style.border="1px solid red";
      $(".submit").prop('disabled', true);
    }
    else if($("#contact_no").val() !=''){
      $.ajax({
        url: "/validate-email/"+'cno-'+btoa($("#contact_no").val()),
        method: 'get',
        success: function(result){
          if(Number(result)==1){
            $(".mno_validation").html("<font color='red'>Sorry mobile already registered.<br></font>");
            document.getElementById("contact_no").style.border="1px solid red";
            $(".submit").prop('disabled', true);
          }


      }});
    }

  }
  function validate_cname(){

    $(".cname_validation").html("");
    $(".rcname").css('border', '');

    if(
      $('input[name="lname"]').val() !=''
      && $('input[name="fname"]').val() !=''
      && $('input[name="mname"]').val() !=''
    ){
      cname=$('input[name="lname"]').val()+', '+$('input[name="fname"]').val()+' '+$('input[name="mname"]').val();
      $.ajax({
        url: "/validate-email/"+'cname-'+btoa(cname),
        method: 'get',
        success: function(result){
          if(Number(result)==1){
            $(".cname_validation").html("<font color='red'>Sorry name already registered.<br></font>");
            $(".rcname").css('border', '1px solid red');
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
    $(".otp_validation").html("");
    $(".submit").prop('disabled', false);
    var type = $("#reg_btn").attr('type');
    var count_empty = $('.input-otp').not(function() { return this.value; }).length;

    if($("#email").val()=='' && $("#contact_no").val() !='' ){
      $("input[name=otp_registration][value=mobile]").attr('checked', true);
    }
    if($("#email").val() !='' && $("#contact_no").val() =='' ){
      $("input[name=otp_registration][value=email]").attr('checked', true);
    }

    if(
      ($("#email").val() =='' && $("#contact_no").val() =='')
      || $('input[name="lname"]').val() ==''
      || $('input[name="fname"]').val() ==''
      || $('input[name="mname"]').val() ==''
      || (type== 'submit' &&  count_empty > 0)
    ){
      $(".submit").prop('disabled', true);
    }
    if(
      $("input[name=otp_registration]:checked").val() =='email' && $("#email").val() ==''
    ){
      $(".submit").prop('disabled', true);
    }
    if(
      $("input[name=otp_registration]:checked").val() =='mobile' && $("#contact_no").val() ==''
    ){
      $(".submit").prop('disabled', true);
    }
    $(".otp_registration_email").show();
    $(".otp_registration_mobile").show();
    if($("#email").val() ==''){
      $(".otp_registration_email").hide();
    }
    if($("#contact_no").val() ==''){
      $(".otp_registration_mobile").hide();
    }


    validate_email();
    validate_cno();
    validate_cname();
    validate_password();
    validate_otp();

  }
