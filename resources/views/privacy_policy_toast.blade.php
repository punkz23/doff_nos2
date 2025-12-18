<!-- cookie warning toast -->
<div class="fixed-bottom">
    <div class="toast bg-dark text-white w-100 mw-100" role="alert" data-autohide="false">
        <div class="toast-body  d-flex flex-column">
            <h4 class="text-center">Privacy Policy</h4>
            <p class="text-center">
                This <a href="{{url('/privacy-policy')}}">privacy policy</a> will help you understand how Daily Overland Freight Forwarder uses and protects the data you provide to us when you visit and use our website and mobile application......
                {{-- This website stores data such as cookies to enable site functionality including analytics and personalization. By using this website, you automatically accept that we use cookies.  --}}
            </p>
            <div class="ml-auto">
                {{-- <a href="{{url('/privacy-policy')}}" type="button" class="btn btn-outline-light mr-3">
                    View Privacy Policy
                </a> --}}
                <button type="button" class="btn btn-light" id="btnAccept">
                    Accept
                </button>
            </div>
        </div>
    </div>
</div>
