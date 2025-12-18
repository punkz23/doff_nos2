<select name="pu_city" style="width:100%;" onchange="get_barangay('pickup')" disabled class="form-control pu_city">
    <option value="">--Select City--</option>
    @isset($sector_list)
        @if($sector_list->count() > 0)
            @foreach($sector_list as $city )
                @if($city->cities_id != '')
                    <option value="{{ $city->cities_id }}">{{ strtoupper($city->cities_name).' '.$city->postal_code }}</option>
                @endif
            @endforeach
        @endif
    @else
        @if( Route::currentRouteName()=='waybills.edit' )
         @if( $pu_city_sector != '')
            @if($pu_city_sector->count() > 0)
             @foreach($pu_city_sector as $pu_city_sector )
                <option {{  strtoupper($data->pick_up_sector->city)==strtoupper($pu_city_sector->cities_name) ? 'selected' : '' }} value="{{ $pu_city_sector->cities_id }}">{{ strtoupper($pu_city_sector->cities_name).' '.$pu_city_sector->postal_code  }}</option>
             @endforeach
            @endif
         @endif  
 
        @endif
    @endisset
</select>