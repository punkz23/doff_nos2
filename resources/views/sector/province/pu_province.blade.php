<select name="pu_province" style="width:100%;" onchange="get_city('pickup')" disabled class=" form-control pu_province">
    <option value="">--Select Province--</option>
    @isset($sector_list)
        @if($sector_list->count() > 0)
            @foreach($sector_list as $province )
                @if($province->province_id != '')
                    <option value="{{ $province->province_id }}">{{ strtoupper($province->province_name) }}</option>
                @endif
            @endforeach
        @endif
    @else
        @if( Route::currentRouteName()=='waybills.edit' )
        
         @if( $pu_province_sector != '')
            @if($pu_province_sector->count() > 0)
             @foreach($pu_province_sector as $pu_province_sector )
                <option {{  strtoupper($data->pick_up_sector->province)==strtoupper($pu_province_sector->province_name) ? 'selected' : '' }} value="{{ $pu_province_sector->province_id }}">{{ strtoupper($pu_province_sector->province_name) }}</option>
             @endforeach
            @endif
         @endif  
 
        @endif
    @endisset
</select>

