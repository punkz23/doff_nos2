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
        <div class="title">COMPLAIN</div>
        <div class="right">
          <a class="link popup-open" id="submit-form">
            <i class="icon f7-icons">location</i>
            Send Complain
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
                        <a id="add_tracking">
                          <i class="icon f7-icons">plus_app</i>
                        </a>
                    </div>
                    <div class="item-inner">
                        <div class="item-title item-label">Tracking #(s)</div>
                        <div class="item-input-wrap">
                          <table id="table-tracking">
                            <tbody>
                              <tr>
                                <td>
                                  <input type="text" style="border-bottom:1px solid;" class="tracking_no">
                                </td>
                                <td>

                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input">
                    <div class="item-media">
                        <i class="icon f7-icons">person_crop_circle_badge_exclam</i>
                    </div>
                    <div class="item-inner">
                        <div class="item-title item-label">Complain</div>
                        <div class="item-input-wrap">
                            <textarea placeholder="Enter your complain here" name="incident_subject" rows="5"></textarea>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input">
                    <div class="item-media">
                        <i class="icon f7-icons">tray_arrow_down</i>
                    </div>
                    <div class="item-inner">
                        <div class="item-title item-label">Attachment</div>
                        <div class="item-input-wrap">
                            <input type="file"  name="attachments[]" multiple>
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

        $('#add_tracking').click(function(){
            if($('#table-tracking tbody > tr:last').find('input').val()!=''){
              $('#table-tracking tbody').append('<tr><td><input type="text" style="border-bottom:1px solid;" class="tracking_no"></td><td>&nbsp;&nbsp;<a class="remove"><i class="color-red icon f7-icons">trash</i></a></td></tr>');
              $('.remove').click(function(){
                $(this).closest('tr').remove();
              });
            }
        });

        $('#form').on('submit',function(e){
          var tracking_length = $('#table-tracking tbody tr').length;
          var concat_tracking ='';
          for(var i=0; i<tracking_length; i++){
            var separator = i<(tracking_length-1) ? ',' : '';
            concat_tracking = concat_tracking +  $('#table-tracking tbody tr').eq(i).find('.tracking_no').val() + separator;
          }
          
          var form = $('#form')[0];
          var form_data = new FormData(form);
          form_data.append('_token',"{{csrf_token()}}");
          form_data.append('tracking_no',concat_tracking);
          app.request({
          url : "{{route('incident.store_complain')}}",
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