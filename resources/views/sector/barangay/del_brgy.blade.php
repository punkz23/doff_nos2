<select name="del_sector" style="width:100%;" class="form-control del_sector" disabled onchange="del_est_sched()">
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
         @if( $del_brgy_sector != '')
            @if($del_brgy_sector->count() > 0)
             @foreach($del_brgy_sector as $del_brgy_sector )
                <option {{  $data->delivery_sector_id==$del_brgy_sector->sectorate_no ? 'selected' : '' }} value="{{ $del_brgy_sector->sectorate_no }}">{{ strtoupper($del_brgy_sector->barangay) }}</option>
             @endforeach
            @endif
         @endif

        @endif
    @endisset
</select>

<br>
<input onkeypress="return max_street(event)"  maxlength="100" placeholder="Street/Bldg/Room" type="text"  value="{{Route::currentRouteName()=='waybills.edit' ? ($data->delivery==1 ? $data->delivery_sector_street : '') : ''}}" name="del_street" class="form-control del_street">
<br>
<p id="p_del_est_sched" ></p>
