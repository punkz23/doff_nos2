<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="theme-color" content="#2196f3">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap:">
  <title>{{ config('app.name', 'Laravel') }}</title>
	<link href="{{asset('/images/icon.png')}}" rel="icon">
  <link rel="stylesheet" href="{{asset('/fr7/packages/core/css/framework7.bundle.min.css')}}">
  <link rel="stylesheet" href="{{asset('/fr7/css/app.css')}}">
  <link rel="stylesheet" href="{{asset('fr7/css/custom_theme.css')}}">
  <link rel="apple-touch-icon" href="{{asset('/fr7/img/f7-icon-square.png')}}">
  
</head>
<body>
  <div id="app">
    @if(Auth::check())
    <div class="panel panel-left panel-cover panel-resizable panel-init">
      <div class="page">
        <div class="page-content">
          <div class="block-title"><img src="{{asset('/images/doff logo.png')}}" width="200px" height="40px" alt="logo"></div>
          <div class="block demo-facebook-card">
            <div class="demo-facebook-avatar"><img src="{{asset('/images/default-avatar.png')}}" class="account-image" width="34" height="34"/></div>
            <div class="demo-facebook-name account-name">{{Auth::user()->name}}</div>
            <div class="demo-facebook-date account-email" style="text-align:justify;">{{Auth::user()->email}}</div>
          </div>
          
          <div class="block-title">Main Navigation</div>


          <div class="list">
            <ul>
              
              <li>
                <a class="item-content item-link panel-close external" href="{{route('waybills.index')}}">
                  <div class="item-media"><i class="icon f7-icons">cart</i></div>
                  <div class="item-inner">
                    <div class="item-title">Transactions</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('waybills.create')}}">
                  <div class="item-media"><i class="icon f7-icons">cart_badge_plus</i></div>
                  <div class="item-inner">
                    <div class="item-title">New Booking</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('contacts.index')}}">
                  <div class="item-media"><i class="icon f7-icons">person_3</i></div>
                  <div class="item-inner">
                    <div class="item-title">Shippers/Consignees</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('contact-us.complain')}}">
                  <div class="item-media"><i class="icon f7-icons">person_crop_circle_badge_exclam</i></div>
                  <div class="item-inner">
                    <div class="item-title">Complain</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('contact-us.feedback')}}">
                  <div class="item-media"><i class="icon f7-icons">arrow_right_arrow_left</i></div>
                  <div class="item-inner">
                    <div class="item-title">Feedback</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('contact-us.request-quote')}}">
                  <div class="item-media"><i class="icon f7-icons">square_list</i></div>
                  <div class="item-inner">
                    <div class="item-title">Request Qoutation</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="{{route('branches.list')}}">
                  <div class="item-media"><i class="icon f7-icons">map</i></div>
                  <div class="item-inner">
                    <div class="item-title">Branches</div>
                  </div>
                </a>
              </li>
              <li>
                <a class="item-content item-link panel-close external" href="http://facebook.com/dailyoverland">
                  <div class="item-media"><i class="icon f7-icons">logo_facebook</i></div>
                  <div class="item-inner">
                    <div class="item-title">Facebook CSR</div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
          
        </div>
      </div>
    </div>
    @endif

    @yield('content')
  </div>
  <script src="{{asset('/fr7/packages/core/js/framework7.bundle.min.js')}}"></script>
  <script src="{{asset('/fr7/js/routes.js')}}"></script>
  <script src="{{asset('/fr7/js/app.js')}}"></script>
  <script src="{{asset('/fr7/js/qrcode.js')}}"></script>
  <script src="{{asset('theme/js/jquery-2.1.4.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  <script src="{{asset('/theme/js/bootstrap-tag.min.js')}}"></script>
  @yield('scripts')
</body>
</html>