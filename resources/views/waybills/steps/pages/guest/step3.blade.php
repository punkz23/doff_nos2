<form id="form-step-3" class="form-horizontal form-label-left">

    <div class="form-group">
        <div class="control-label col-md-3 col-sm-3 col-xs-12"></div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="checkbox">
                <label>
                    <input name="use_company" type="checkbox" class="use_company ace" />
                    <span class="lbl"> Use Company</span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Lastname:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="lname" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Firstname:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="fname" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Middlename: <font color="red">*</font> 
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="mname" class="form-control">
        </div>
    </div>

    <div class="form-group" hidden>
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Company:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="company" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Email:  
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" name="email" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Contact Number: <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="number" name="contact_no" class="form-control mobile_no"  placeholder="09#########"  maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Business Type:  
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="business_category_id" class="select2">
                <option value="none" selected disabled>--Select business type--</option>
                @foreach($business_types as $business_type)
                    <optgroup label="{{$business_type->businesstype_description}}">
                        @foreach($business_type->business_type_category as $business_type_category)
                            <option data-province="{{$business_type_category->businesstype_category_description}}" value="{{$business_type_category->businesstype_category_id}}">{{$business_type_category->businesstype_category_description}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">City: <font color="red">*</font></label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="city" class="select2 cities" id="city">
                <option value="none" disabled selected>--Select City--</option>
                @foreach($provinces as $province)
                    <optgroup label="{{$province->province_name}}">
                        @foreach($province->city as $city)
                            <option data-province="{{$province->province_name}}" data-postal_code="{{$city->postal_code}}" value="{{$city->cities_id}}">{{$city->cities_name}}, {{$city->postal_code}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Barangay:  <font color="red">*</font>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <select name="barangay" id="consignee_brgy" class="form-control ">
                <option value="none" selected disabled="disabled">--Please select barangay--</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
        Street/Bldg/Room:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" name="street" class="form-control" placeholder="">
        </div>
    </div>

    

    

    

</form>