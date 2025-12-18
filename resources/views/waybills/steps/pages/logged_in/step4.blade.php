<form id="form-step-4" class="form-horizontal">
    
<input type="hidden" name="reference_no" value="{{generateReferenceNumber()}}">

<div class="form-group div_rebate" >
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
<div class="div_rebate_text col-md-12 col-sm-12 col-xs-12  alert alert-info alert-dismissible " role="alert" style="text-align:left;">
</div>
<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"></label>
</div>
<div id="div_terms_ol" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' : '' ) : '' }}  >
<h4><b><i class="fa fa-list"></i> Terms and Conditions</b></h4>
@if($term!=null)
{!! $term->content !!}
@endif
</div>
<div id="div_terms_pasabox" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' : 'hidden' ) : 'hidden' }}  >
<h4><b><i class="fa fa-list"></i> Terms and Conditions of Pasabox Booking</b></h4>  
@if($term_pasabox!=null)
{!! $term_pasabox->content !!}
@endif
</div>
<h4 style="color:red;"><br><b><i class="fa fa-lightbulb-o"></i> Notice to Client</b></h4>
<p style="color:red;">*Please be reminded to ensure proper and secure packaging of all items prior to shipping, to prevent possible damage or delay in transit. Based on our experience, most damages occur due to improper packaging. Thank you for your cooperation.</p>
                    

<br>

<div class="form-group">
    <div class="">
    <label>
        <input type="checkbox" name="agree" class="js-switch" checked style="{{Route::currentRouteName()=='waybills.create' ? '' : 'display:none'}}"/> @if(Route::currentRouteName()=='waybills.create') I accept the terms and condition @endif
    </label>
    
        <!-- <label>
            <input name="agree" id="agree" type="checkbox" class="ace" {{Route::currentRouteName()=='waybills.edit' ? 'checked' : ''}}/>
            <span class="lbl"> I accept the terms</span>
        </label><br> -->
    </div>
</div>

</form>