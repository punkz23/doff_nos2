
<script type="text/javascript" src="{{asset('/gentelella')}}/vendors/qr_code/qrcode.js"></script>
<script type="text/javascript">
  
  var hasImage = false;
  var imageData = null;

  var canvas_consignee = $('#consignee-decode-canvas')[0];
  var context_consignee = canvas_consignee.getContext('2d');
  var decodeResult_consignee = $("#consignee_qr_code");


  var canvas_shipper = $('#shipper-decode-canvas')[0];
  var context_shipper = canvas_shipper.getContext('2d');
  var decodeResult_shipper = $("#shipper_qr_code");

  function resetDecoder(sc) {
    hasImage = false;
    imageData = null;
    if(sc=='shipper'){
      decodeResult=decodeResult_shipper;
    }else{
      decodeResult=decodeResult_consignee;
    }
    decodeResult.val('').trigger('change');
  }

  function drawImage(src,sc) {

    if(sc=='shipper'){
      canvas=canvas_shipper;
      context=context_shipper;
    }else{
      canvas=canvas_consignee;
      context=context_consignee;
    }

    var img = new Image();

    img.crossOrigin = 'anonymous';

    img.onload = function () {
      var width = img.width;
      var height = img.height;
      var actualWidth = Math.min(960, width);
      var actualHeight = height * (actualWidth / width);

      hasImage = true;
      canvas.width = actualWidth;
      canvas.height = actualHeight;

      context.drawImage(img, 0, 40, width, height-40, 0, 0, actualWidth, actualHeight);

      imageData = context.getImageData(0, 0, actualWidth, actualHeight);
      $('#'+sc+'-decode-btn').trigger('click');
    };

    img.src = src;
  }


  $('#consignee-decode-file').on('change', function (e) {
    var file = e.target.files[0];

    if (file) {
      resetDecoder('consignee');

      var reader = new FileReader();

      reader.onload = function (e) {
        drawImage(e.target.result,'consignee');
      };

      reader.readAsDataURL(file);

    }
  });

  $('#shipper-decode-file').on('change', function (e) {
    var file = e.target.files[0];

    if (file) {
      resetDecoder('shipper');

      var reader = new FileReader();

      reader.onload = function (e) {
        drawImage(e.target.result,'shipper');
      };

      reader.readAsDataURL(file);

    }
  });

  function getImageData(sc) {

    if(sc=='shipper'){
      canvas=canvas_shipper;
      context=context_shipper;
    }else{
      canvas=canvas_consignee;
      context=context_consignee;
    }
    
    imageData && context.putImageData(imageData, 0, 0);

    return imageData || context.getImageData(0, 0, canvas.width, canvas.height);
  }

  function getModuleSize(location, version) {
    var topLeft = location.topLeft;
    var topRight = location.topRight;
    var a = Math.abs(topRight.x - topLeft.x);
    var b = Math.abs(topRight.y - topLeft.y);
    var c = Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2));

    return c / (version * 4 + 17);
  }

  function markFinderPattern(x, y, moduleSize,sc) {

    if(sc=='shipper'){
      context=context_shipper;
    }else{
      context=context_consignee;
    }

    context.fillStyle = '#00ff00';

    context.beginPath();
    context.arc(x, y, moduleSize * 0.75, 0, 2 * Math.PI);
    context.fill();
  }

  function markQRCodeArea(location, version,sc) {

    if(sc=='shipper'){
      context=context_shipper;
    }else{
      context=context_consignee;
    }

    context.lineWidth = 2;
    context.strokeStyle = '#00ff00';

    context.beginPath();
    context.moveTo(location.topLeft.x, location.topLeft.y);
    context.lineTo(location.topRight.x, location.topRight.y);
    context.lineTo(location.bottomRight.x, location.bottomRight.y);
    context.lineTo(location.bottomLeft.x, location.bottomLeft.y);
    context.lineTo(location.topLeft.x, location.topLeft.y);
    context.stroke();

    var moduleSize = getModuleSize(location, version,sc);

    markFinderPattern(location.topLeftFinder.x, location.topLeftFinder.y, moduleSize);
    markFinderPattern(location.topRightFinder.x, location.topRightFinder.y, moduleSize);
    markFinderPattern(location.bottomLeftFinder.x, location.bottomLeftFinder.y, moduleSize);
  }

  $('#consignee-decode-btn').on('click', function () {
    if (!hasImage) {
      return alert('NO IMAGE FOUND.');
    }

    var imageData = getImageData('consignee');
    var result = new QRCode.Decoder()
      .setOptions({ canOverwriteImage: false })
      .decode(imageData.data, imageData.width, imageData.height);

    if (result) {
      decodeResult.val(result.data).trigger('change');
      markQRCodeArea(result.location, result.version,'consignee');
    } else {
        
      alert('ERROR.');
    }
  });

  $('#shipper-decode-btn').on('click', function () {
    if (!hasImage) {
      return alert('NO IMAGE FOUND.');
    }

    var imageData = getImageData('shipper');
    var result = new QRCode.Decoder()
      .setOptions({ canOverwriteImage: false })
      .decode(imageData.data, imageData.width, imageData.height);

    if (result) {
      decodeResult.val(result.data).trigger('change');
      markQRCodeArea(result.location, result.version,'shipper');
    } else {
        
      alert('ERROR.');
    }
  });


  $("#consignee_qr_code").change(function(){
      user_id='{{ Auth::user()->contact_id }}';
      $("#consignee_qr_code_cid").val('');
      if(this.value !='' ){
        $(".consignee-decode-remove").show();
        jQuery.ajax({
            url: "{{url('/get-qr-code-profile')}}/"+this.value, 
            method: 'get',
            success: function(result){
             
                var result = JSON.parse(result);
                if(result.length > 0){
                   
                    $.each(result,function(){
                        if( Number(this.qrcode_profile_status)==1 ){
                          cdata=this.contact;
                          if(user_id != cdata['contact_id'] ){
                            cdata_add=this.qr_code_details;
                            address_d=new Array();
                            $.each(cdata_add,function(){
                              address_d.push(this.qr_code_profile_address);
                            });
                            
                            if ( $("#consignee_id option[value='"+cdata['contact_id']+"']").length > 0 ){
                              $("#consignee_id option[value='"+cdata['contact_id']+"']").remove();
                            }

                            text_icon='';
                            if(this.qrcode_profile_id !=null && this.qrcode_profile_id !='' ){
                              text_icon='fa-qrcode';
                            }

                            $('#consignee_id')
                            .append($("<option></option>")
                            .attr("value",cdata['contact_id'])
                            .attr("data-qr",this.qrcode_profile_id)
                            .attr("data-icon",text_icon)
                            .attr("data-address",JSON.stringify(address_d))
                            .attr("data-contact_no",cdata['contact_no'])
                            .attr("data-email",cdata['email'])
                            .attr("data-contact_numbers",JSON.stringify(cdata['contact_number']))
                            .text(cdata['fileas']));

                            $("#consignee_qr_code_cid").val(cdata['contact_id']);
                            $("#consignee_id").val(cdata['contact_id']).trigger('change');
                          }else{
                            alert('Invalid QR Code.');
                            $("#consignee-decode-file").val('').trigger('change');
                            $("#consignee_qr_code").val('').trigger('change');
                          }

                        }else{
                          alert('Sorry but QR Code has been deactivated.');
                          $("#consignee-decode-file").val('').trigger('change');
                          $("#consignee_qr_code").val('').trigger('change');
                          
                         
                        }
                                           
                    });
               }else{
                  alert('Sorry QR Code not found.');
                  $("#consignee-decode-file").val('').trigger('change');
                  $("#consignee_qr_code").val('').trigger('change');
                  
                }

            }});

      }else{

        
        $(".consignee-decode-remove").hide();
        default_consignee_data('consignee');
        

      }
  });


  $("#shipper_qr_code").change(function(){
      user_id='{{ Auth::user()->contact_id }}';
      $("#shipper_qr_code_cid").val('');
      if(this.value !='' ){
        $(".shipper-decode-remove").show();
        jQuery.ajax({
            url: "{{url('/get-qr-code-profile')}}/"+this.value, 
            method: 'get',
            success: function(result){
             
                var result = JSON.parse(result);
                if(result.length > 0){
                   
                    $.each(result,function(){
                        if( Number(this.qrcode_profile_status)==1 ){
                          cdata=this.contact;
                          if(user_id != cdata['contact_id'] ){
                            cdata_add=this.qr_code_details;
                            address_d=new Array();
                            $.each(cdata_add,function(){
                              address_d.push(this.qr_code_profile_address);
                            });
                            
                            if ( $("#shipper_id option[value='"+cdata['contact_id']+"']").length > 0 ){
                              $("#shipper_id option[value='"+cdata['contact_id']+"']").remove();
                            }
                            text_icon='';
                            if(this.qrcode_profile_id !=null && this.qrcode_profile_id !='' ){
                              text_icon='fa-qrcode';
                            }
                            $('#shipper_id')
                            .append($("<option></option>")
                            .attr("value",cdata['contact_id'])
                            .attr("data-qr",this.qrcode_profile_id)
                            .attr("data-icon",text_icon)
                            .attr("data-address",JSON.stringify(address_d))
                            .attr("data-contact_no",cdata['contact_no'])
                            .attr("data-email",cdata['email'])
                            .attr("data-contact_numbers",JSON.stringify(cdata['contact_number']))
                            .text(cdata['fileas']));

                            $("#shipper_qr_code_cid").val(cdata['contact_id']);
                            $("#shipper_id").val(cdata['contact_id']).trigger('change');
                          }else{
                            alert('Invalid QR Code.');
                            $("#shipper-decode-file").val('').trigger('change');
                            $("#shipper_qr_code").val('').trigger('change');
                          }

                        }else{
                          alert('Sorry but QR Code has been deactivated.');
                          $("#shipper-decode-file").val('').trigger('change');
                          $("#shipper_qr_code").val('').trigger('change');
                          
                         
                        }
                                           
                    });
               }else{
                  alert('Sorry QR Code not found.');
                  $("#shipper-decode-file").val('').trigger('change');
                  $("#shipper_qr_code").val('').trigger('change');
                  
                }

            }});

      }else{

        
        $(".shipper-decode-remove").hide();
        default_consignee_data('shipper');
        

      }
  });

  function default_consignee_data(sc){

    if(sc=='shipper'){
      $("#shipper_id").html('<option value="none" disabled selected>--Select Shipper--</option>'+
      '<option value="new">--New Shipper--</option>');
      @foreach($contacts as $row)
          $("#shipper_id").append('<option value="{{$row->contact_id}}" data-address="{{$row->user_address}}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->shipper_id==$row->contact_id ? 'selected' : '') : ''}}>{{$row->fileas}}</option>');
      @endforeach
    }else{
      $("#consignee_id").html('<option value="none" disabled selected>--Select Consignee--</option>'+
      '<option value="new">--New Consignee--</option>');
      @foreach($contacts as $row)
          $("#consignee_id").append('<option value="{{$row->contact_id}}" data-address="{{$row->user_address}}" data-contact_no="{{$row->contact_no}}" data-email="{{$row->email}}" data-contact_numbers="{{$row->contact_number}}" {{Route::currentRouteName()=='waybills.edit' ? ($data->consignee_id==$row->contact_id ? 'selected' : '') : ''}}>{{$row->fileas}}</option>');
      @endforeach
    }
    $("#"+sc+"_id").trigger('change');
  
  }

  $(".consignee-decode-remove").click(function(){
    $("#consignee_qr_code").val('').trigger('change');
    $("#consignee-decode-file").val('').trigger('change');
  });

  $(".shipper-decode-remove").click(function(){
    $("#shipper_qr_code").val('').trigger('change');
    $("#shipper-decode-file").val('').trigger('change');
  });

</script>