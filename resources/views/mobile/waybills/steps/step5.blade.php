<div class="block">
    <h2 class="block-title-medium">Terms and Condition in Online Booking</h2>
    <form id="step-5">
        @if($term!=null)
            {!! $term->content !!}
        @endif
        <!-- Toggle element -->
        <label class="toggle">
        <!-- Toggle input -->
        <input type="checkbox" name="accept">
        <!-- Toggle icon -->
        <span class="toggle-icon"></span>
        </label>
        I accept the terms
    </form>
</div>