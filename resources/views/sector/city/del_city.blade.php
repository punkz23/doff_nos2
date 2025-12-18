<select name="del_city" style="width:100%;" onchange="get_barangay('delivery')" disabled class="form-control del_city">
    <option value="">--Select City--</option>
    @isset($sector_list)
        @if($sector_list->count() > 0)
            @foreach($sector_list as $city )
                @if($city->cities_id != '')
                    <option value="{{ $city->cities_id }}">{{ strtoupper($city->cities_name.' '.$city->postal_code) }}</option>
                @endif
            @endforeach
        @endif
    @else
        @if( Route::currentRouteName()=='waybills.edit' )
            
         @if( $del_city_sector != '')
            @if($del_city_sector->count() > 0)
             @foreach($del_city_sector as $del_city_sector )
                <option {{  strtoupper($data->delivery_sector->city)==strtoupper($del_city_sector->cities_name) ? 'selected' : '' }} value="{{ $del_city_sector->cities_id }}">{{ strtoupper($del_city_sector->cities_name.' '.$del_city_sector->postal_code) }}</option>
             @endforeach
            @endif
         @endif  
 
        @endif
    @endisset
</select>
