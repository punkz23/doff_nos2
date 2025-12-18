@isset($serviceable_branch_map)
@if($serviceable_branch_map->count() > 0)

<div class="col-md-12 col-sm-12 col-xs-12">
    @foreach($serviceable_branch_map as $sbm )
    <div class="profile_img" align="center">
     
        <?php
            $image_path=url('/images/address.png');
           
            if($sbm->serviceable_map !=''){
                if( Storage::disk('maps')->exists('Branch/'.$sbm->serviceable_map) ){
                    $image_path = Storage::disk('maps')->get('Branch/'.$sbm->serviceable_map);
                   
		            $data = base64_encode($image_path);
                    $image_path = 'data:image/png;base64,'.$data;
                }
            }
            
        ?>
       
        
        <img class="img-responsive avatar-view branch-map" data-url="{{$image_path}}" width="50%;" height="50%;" src="{{$image_path}}">
       
  
      
    </div>
    @endforeach
</div>
<script>

$('.branch-map').click(function(){
    var image = new Image();
    image.src = $(this).data('url');

    var w = window.open("");
    w.document.write(image.outerHTML);
    
});
</script>



@endif
@endisset