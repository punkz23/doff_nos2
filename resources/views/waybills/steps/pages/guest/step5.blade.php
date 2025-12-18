<form id="form-step-5">
@if($term!=null)
{!! $term->content !!}
@endif
<br>
<div class="form-group">
    <div class="">
        <h4>
            <input name="agree" class="ace ace-switch ace-switch-2" type="checkbox" />
            <span class="lbl">&nbsp;I accept the terms and condition</span>
        </h4>
    </div>
</div>
</form>