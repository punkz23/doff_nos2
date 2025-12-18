<select name="pu_sector" style="width:100%;" onchange="pu_sector_func()" disabled class="form-control pu_sector">
    <option value="">--Select barangay--</option>
    @isset($sector_list)
        @if($sector_list->count() > 0)
            @foreach($sector_list as $brgy )
                @if($brgy->barangay != '')
                    <option value="{{ $brgy->sectorate_no }}">{{ strtoupper($brgy->barangay) }}</option>
                @endif
            @endforeach
        @endif
    @else
        @if( Route::currentRouteName()=='waybills.edit' )
         @if( $pu_brgy_sector != '')
            @if($pu_brgy_sector->count() > 0)
             @foreach($pu_brgy_sector as $pu_brgy_sector )
                <option {{  $data->pickup_sector_id==$pu_brgy_sector->sectorate_no ? 'selected' : '' }} value="{{ $pu_brgy_sector->sectorate_no }}">{{ strtoupper($pu_brgy_sector->barangay) }}</option>
             @endforeach
            @endif
         @endif

        @endif
    @endisset
</select>
