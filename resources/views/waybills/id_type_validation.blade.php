<script>
function get_id_type(){
    $(".av_id_type").html('<option value="">--Select Type of ID--</option>');
    $.ajax({
      url: "{{url('/get-id-type')}}", 
      method: 'get',
      success: function(result){
        var result = JSON.parse(result);
        $.each(result,function(){
            
          $(".av_id_type").append('<option value="'+this.valid_id_no+'">'+this.valid_id_desc+'</option>');
    
        });
        $(".select2_group").select2({});

    }});
  }
  

  $(".av_id_pic").change(function(){
    input=this;
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#photo_av_id_pic')
          .attr('src', e.target.result)
          .width(150) 
          .height(150);
      };

      reader.readAsDataURL(input.files[0]);
    }
  });
  $(".av_id_w_pic").change(function(){
    input=this;
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#photo_av_id_w_pic')
          .attr('src', e.target.result)
          .width(150) 
          .height(150);
      };

      reader.readAsDataURL(input.files[0]);
    }
  });
</script>