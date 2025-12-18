<form class="form-horizontal" id="form-step-2">

	<div class="col-xs-12">
        <div class="form-group">
            <div class="col-xs-12 col-sm-3 no-padding-right">

            </div>
            <div class="col-xs-12 col-sm-9">
                <div class="checkbox">
                    <label>
                        <input name="use_company" type="checkbox" class="ace" />
                        <span class="lbl"> Use Company</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

	<div class="form-group name">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Lastname: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="lname" class="col-xs-12 col-sm-6" required/>
			</div>
		</div>
	</div>

    <div class="space-2 name"></div>
                  
    <div class="form-group name">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Firstname: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="fname" class="col-xs-12 col-sm-6" required/>
			</div>
		</div>
	</div>

    <div class="space-2 name"></div>
                  
    <div class="form-group name">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Middlename:</label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="mname" class="col-xs-12 col-sm-6" />
			</div>
		</div>
	</div>

	<div class="space-2 name"></div>

	<div class="form-group company" style="display:none">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Company: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="company" class="col-xs-12 col-sm-6" />
			</div>
		</div>
	</div>

    <div class="space-2 company" style="display:none"></div>
                  
    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Email Address: <font color="red" class="shipper_itself" style="display:none">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="email" name="email" id="shipper-email" class="col-xs-12 col-sm-6 require-email" />
			</div>
		</div>
	</div>

	<div class="space-2"></div>
                  
    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="phone">Phone Number: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="ace-icon fa fa-phone"></i>
				</span>
			    <input type="text" class="form-control" name="contact_no" />
			</div>
		</div>

	</div>

    <div class="space-2"></div>
                  

    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Business Type</label>

		<div class="col-xs-12 col-sm-9">
			<select name="business_category_id" class="form-control select2">
				<option value="0">--Select business type--</option>
				@foreach($business_types as $row)
                    <optgroup label="{{$row->businesstype_description}}">
                        @foreach($row->business_type_category as $r)
                            <option value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                        @endforeach
                    </optgroup>
                @endforeach
			</select>
		</div>
	</div>

	<div class="space-2"></div>

	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Street/Bldg/Others:</label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="street" class="col-xs-12 col-sm-5" />
			</div>
		</div>
	</div>

	<div class="space-2"></div>

	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="password2">Barangay: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="barangay" class="col-xs-12 col-sm-4" />
			</div>
		</div>
	</div>
            
    
    <div class="space-2"></div>
                  
    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">City: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<select name="city" id="shipper_city" class="form-control select2 cities">
				<option value="none" disabled selected>--Please select city--</option>
				@foreach($provinces as $province)
                        <optgroup label="{{$province->province_name}}">
                            @foreach($province->city as $city)
                                <option value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                            @endforeach
                        </optgroup>
                @endforeach						
			</select>
		</div>
	</div>

	<div class="space-2"></div>
                  
    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Province:</label>

		<div class="col-xs-12 col-sm-9">
			<input type="hidden" name="shipper_province">
			<label id="shipper_province"></label>
		</div>
	</div>
    

	<div class="space-2"></div>

    <div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Postal Code:</label>

		<div class="col-xs-12 col-sm-9">
		<input type="hidden" name="shipper_postal_code">
			<label id="shipper_postal_code"></label>
		</div>
	</div>

</form>