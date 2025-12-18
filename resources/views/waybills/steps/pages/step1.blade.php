
<form class="form-horizontal" id="form-step-1">

	<div class="form-group">
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Who is your contact person:</label>
		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<select name="who_is_contact_person" class="form-control">
					<option selected disabled value="none">--PLEASE SELECT ONE--</option>
					<option value="0">OTHERS</option>
					<option value="1">SHIPPER ITSELF</option>
				</select>
			</div>
		</div>
	</div>
	
	<div class="form-group" hidden>
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Lastname: <font color="red">*</font></label>
		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="lname" class="col-xs-12 col-sm-6"/>
			</div>
		</div>
	</div>

    
                  
    <div class="form-group" hidden>
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Firstname: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="fname" class="col-xs-12 col-sm-6"/>
			</div>
		</div>
	</div>

    <div class="space-2"></div>
                  
    <div class="form-group" hidden>
		<label class="control-label col-xs-12 col-sm-3 no-padding-right">Middlename:</label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="text" name="mname" class="col-xs-12 col-sm-6" />
			</div>
		</div>
	</div>


	<div class="space-2"></div>
                  
    <div class="form-group" hidden>
		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Email Address: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="clearfix">
				<input type="email" name="email" id="shipper-email" class="col-xs-12 col-sm-6" />
			</div>
		</div>
	</div>

    <div class="space-2"></div>
                  
    <div class="form-group" hidden>
		<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="phone">Phone Number: <font color="red">*</font></label>

		<div class="col-xs-12 col-sm-9">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="ace-icon fa fa-phone"></i>
				</span>
			    <input type="tel" class="phone" name="contact_no" />
			</div>
		</div>

	</div>

</form>