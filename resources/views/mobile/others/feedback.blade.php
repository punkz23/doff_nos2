@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('home')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">FEEDBACK</div>
        <div class="right">
          <a class="link popup-open" id="submit-form">
            <i class="icon f7-icons">arrow_right_arrow_left</i>
            Send Feedback
          </a>
        </div>
      </div>
  </div>
  <div class="page-content">
    
    
      <form id="form">
        @csrf
        <div class="list no-hairlines-md">
            <ul>
                
                <li class="item-content item-input">
                    <div class="item-media">
                        <i class="icon f7-icons">arrow_right_arrow_left</i>
                    </div>
                    <div class="item-inner">
                        <div class="item-title item-label">Feedback</div>
                        <div class="item-input-wrap">
                            <textarea name="incident_subject" placeholder="Enter your feedback here"></textarea>
                        </div>
                    </div>
                </li>
                
            </ul>
        </div>
      </form>
    

  </div>
</div>
@endsection

@section('scripts')
    <script>

        $('#submit-form').click(function(){
          $('#form').submit();
        });

        $('#form').on('submit',function(e){
          var form_data = {
            _token : "{{csrf_token()}}",
            incident_subject : $('textarea[name="incident_subject"]').val()
          };
          app.request({
          url : "{{route('incident.store_feedback')}}",
          method : "POST",
          data : form_data,
          beforeSend: function(xhr){
            app.dialog.progress('Please wait..');
          },
          success: function(data,status,xhr){
              var obj = JSON.parse(data);
              var icon = obj['type']=='error' ? 'exclamationmark_triangle' : 'cart_badge_plus';
              var toastBottom = app.toast.create({
                icon: '<i class="icons f7-icons">'+icon+'</i>',
                text: obj['message'],
                position: 'center',
                closeTimeout: 2000,
              });
              if(obj.type=='success'){
                $('#form').trigger('reset');
              }
              toastBottom.open();
          },
          error: function(xhr,status){
            app.dialog.close();
            var toastBottom = app.toast.create({
                icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                text: 'Server connection error',
                position: 'center',
                closeTimeout: 3000,
            });
            toastBottom.open();
          },
          complete: function(xhr,status){
              if(status==200){
                app.dialog.close();
              }
            }
          })
          
          e.preventDefault();
        });
        
    </script>
@endsection