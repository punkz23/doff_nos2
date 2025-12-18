<div class="block">
    <h2 class="block-title-medium">Consignee Info</h2>
    <div class="list no-hairlines-md">
        <form id="step-3">
            <ul>
                <li>
                    <div class="item-input-wrap">
                        <label class="toggle toggle-init color-blue">
                            <input type="checkbox" class="use-company" name="use_company_consignee">
                            <span class="toggle-icon"></span>
                        </label>
                        <span>Use Company</span>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline name">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Lastname *</div>
                        <div class="item-input-wrap">
                        <input type="text" name="lname" class="input-name" required>
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline name">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Firstname *</div>
                        <div class="item-input-wrap">
                        <input type="text" name="fname" class="input-name" required>
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline name">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Middlename</div>
                        <div class="item-input-wrap">
                        <input type="text" name="mname">
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline company"  style="display:none;">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Company *</div>
                        <div class="item-input-wrap">
                        <input type="text" name="company" class="input-company">
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Email *</div>
                        <div class="item-input-wrap">
                        <input type="email" name="email" required>
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Contact # *</div>
                        <div class="item-input-wrap">
                        <input type="text" name="contact_no" required>
                        <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search business type">
                        <select name="business_category_id">
                            <option selected disabled style="display:none;" value="">--Select business type--</option>
                            @foreach($business_types as $row)
                                <optgroup label="{{$row->businesstype_description}}">
                                    @foreach($row->business_type_category as $r)
                                        <option value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title">Business Type</div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Address Label *</div>
                        <div class="item-input-wrap">
                            <input type="text" name="address_caption" value="" placeholder="HOME/WORK" required>
                            <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Street/Bldg/Others</div>
                        <div class="item-input-wrap">
                            <input type="text" name="street" value="">
                            <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                
                <li class="item-content item-input item-input-outline">
                    <div class="item-inner">
                        <div class="item-title item-floating-label">Barangay *</div>
                        <div class="item-input-wrap">
                            <input type="text" name="barangay" value="" required>
                            <span class="input-clear-button"></span>
                        </div>
                    </div>
                </li>
                
                <li>
                    <a class="item-link smart-select smart-select-init" data-open-in="popup"  data-searchbar="true" data-searchbar-placeholder="Search city" data-virtual-list="true">
                        <select name="city" class="cities" id="consignee_city">
                            <option selected disabled value="none"></option>
                            {!! $ddCities !!}
                        </select>
                                
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title">City</div>
                                <div class="item-after city-after"></div>
                            </div>
                        </div>
                    </a> 
                </li>
                
                <li class="item-content item-input">
                    <div class="item-inner">
                        <div class="item-title item-label">Province</div>
                        <div class="item-input-wrap">
                            <input type="hidden" name="consignee_province" value="">
                            <label id="consignee_province"></label>
                        </div>
                    </div>
                </li>
                <li class="item-content item-input">
                    <div class="item-inner">
                        <div class="item-title item-label">Postal Code</div>
                        <div class="item-input-wrap">
                            <input type="hidden" name="consignee_postal_code" value="">
                            <label id="consignee_postal_code"></label>
                        </div>
                    </div>
                </li>
                
            </ul>
        </form>
    </div>
</div>