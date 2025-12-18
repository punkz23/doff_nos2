<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="theme-color" content="#2196f3">
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
                <a class="item-content item-link panel-close external" href="{{route('contacts.index')}}">
                  <div class="item-media"><i class="icon f7-icons">person_3</i></div>
                  <div class="item-inner">
                    <div class="item-title">Contacts</div>
                  </div>
                </a>
              </li>
              
            </ul>
          </div>
          
        </div>
      </div>
    </div>
    

    
  </div>
  <script src="{{asset('/fr7/packages/core/js/framework7.bundle.min.js')}}"></script>
  
  <script>
      var routes = [
        {
          path: '/',
          url: './index.html',
          name: 'home',
        }
      ]
  </script>
  <script src="{{asset('/fr7/js/app.js')}}"></script>
</body>
</html>