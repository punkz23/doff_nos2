@isset($sector_list)
@if($sector_list->count() > 0)
<small>
<div class="col-md-12">
   <input type="text" id="search_sector_table" placeholder="Search..." />
    <br><br>
    <table  width="100%" class="table table-striped table-bordered sector_table">
      <tr>
        <td width="5%"></td>
        <td></td>
        <td align="center">Delivery/Pick-up Schedule</td>
      </tr>
      <tbody>
      @php 
      $city='';
      
      @endphp
      @foreach($sector_list as $sector )
        @php 
        $schedule_array=array();
        $count_sched=0;
        @endphp
        @if($city != ($sector->city.' '.$sector->province) )

          <?php
            $image_path=url('/images/address.png');
            if($sector->city_map !=''){
              if(Storage::disk('maps')->exists('City/'.$sector->province.'/'.$sector->city_map)) {
                $image_path = Storage::disk('maps')->get('City/'.$sector->province.'/'.$sector->city_map);
                $data = base64_encode($image_path);
                $image_path = 'data:image/png;base64,'.$data;
                
              }
            }
            
          ?>
          <tr class="city-map" data-url="{{$image_path}}">
            <td colspan="3">
              <b>{{ $sector->city.' '.$sector->province.' '.$sector->postalcode }}</b>
              
              
              
              <img class="img-responsive avatar-view pull-right" width="100px;" height="100px;" src="{{$image_path}}">
             
          </td></tr>
        @endif
        <?php
          $image_path=url('/images/address.png');
          if($sector->city_map !='' ){
            if( Storage::disk('maps')->exists('Brgy/'.$sector->sectorate_no.'/'.$sector->brgy_map) ) {
              $image_path = Storage::disk('maps')->get('Brgy/'.$sector->sectorate_no.'/'.$sector->brgy_map);
              $data = base64_encode($image_path);
              $image_path = 'data:image/png;base64,'.$data;
            }
          }
          
        ?>
        <tr  class="brgy-map" data-url="{{$image_path}}">
          <td width="5%"> 
            
           
            <img class="img-responsive avatar-view" width="100px;" height="100px;" src="{{$image_path}}">
            
            

          </td>
          <td>{{ $sector->barangay }} </td>
          <td> 
            @foreach($sector->route_template_details as $details )
               
                @foreach($details->route_template->sector_schedule as $schedule )
                  @if( !in_array($schedule->day_of_the_week, $schedule_array) )              
                   
                    {{ $count_sched <= 0 ? "".ucfirst(strtolower($schedule->day_of_the_week)) : ','.ucfirst(strtolower($schedule->day_of_the_week)) }}
                    @php $count_sched++; @endphp
                    
                  @endif
                  @php 
                    $schedule_array[]=$schedule->day_of_the_week;
                    
                  @endphp
                @endforeach
            @endforeach
            
               
            
          </td>
         
          <td style="display:none;">{{ $sector->city.' '.$sector->province }} </td>
        </tr>

      @php 
      $city=$sector->city.' '.$sector->province;
      @endphp

     @endforeach
      </tbody>
    </table>
        
</div>
</small>
<script>
$("#search_sector_table").keyup(function(){
		
    var searchText = $(this).val().toLowerCase();
    // Show only matching TR, hide rest of them
    $.each($(".sector_table tbody tr"), function() {
      if($(this).text().toLowerCase().indexOf(searchText) === -1)
        $(this).hide();
      else
        $(this).show();                
    });
});


$('.city-map').click(function(){
    var image = new Image();
    image.src = $(this).data('url');

    var w = window.open("");
    w.document.write(image.outerHTML);
    
});

$('.brgy-map').click(function(){
    var image = new Image();
    image.src = $(this).data('url');

    var w = window.open("");
    w.document.write(image.outerHTML);
    
});

</script>
@endif
@endisset