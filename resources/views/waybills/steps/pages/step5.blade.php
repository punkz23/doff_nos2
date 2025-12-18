<form id="form-step-5">
    <h1>Terms and Condition in Online Booking</h1>

    @if($term!=null)
        {!! $term->content !!}
    @endif
                  
    <div class="form-group">
		<div class="col-xs-12 col-sm-4">
            <label>
                <input name="agree" id="agree" type="checkbox" class="ace" />
                <span class="lbl"> I accept the terms</span>
            </label>
		</div>
	</div>
</form>