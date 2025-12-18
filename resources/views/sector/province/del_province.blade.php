<select name="del_province" style="width:100%;" onchange="get_city('delivery')" disabled class="form-control del_province">
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
        
         @if( $del_province_sector != '')
            @if($del_province_sector->count() > 0)
             @foreach($del_province_sector as $del_province_sector )
                <option {{  strtoupper($data->delivery_sector->province)==strtoupper($del_province_sector->province_name) ? 'selected' : '' }} value="{{ $del_province_sector->province_id }}">{{ strtoupper($del_province_sector->province_name) }}</option>
             @endforeach
            @endif
         @endif  
 
        @endif
    @endisset
</select>