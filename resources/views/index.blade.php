<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="theme-color" content="#2196f3">
  <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap:">
  <title>DOFF-Mobile</title>
  <link rel="stylesheet" href="{{asset('/fr7/packages/core/css/framework7.bundle.min.css')}}">
  <link rel="stylesheet" href="{{asset('/fr7/css/app.css')}}">
  <link rel="apple-touch-icon" href="{{asset('/fr7/img/f7-icon-square.png')}}">
  <link rel="icon" href="{{asset('/fr7/img/f7-icon.png')}}">
</head>
<body>
  <div id="app">
  <div class="page no-toolbar no-navbar no-swipeback login-screen-page">
    <div class="page-content login-screen-content">
      <div class="login-screen-title">Framework7</div>
      <form>
        <div class="list">
          <ul>
            <li class="item-content item-input item-input-with-value">
              <div class="item-inner">
                <div class="item-title item-label">Username</div>
                <div class="item-input-wrap">
                  <input type="text" placeholder="Your username" id="demo-username-2" class="input-with-value">
                  <span class="input-clear-button"></span>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Password</div>
                <div class="item-input-wrap">
                  <input type="password" placeholder="Your password" id="demo-password-2" class="">
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="list">
          <ul>
            <li><a href="" class="list-button">Sign In</a></li>
          </ul>
          <div class="block-footer">Some text about login information.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
        </div>
      </form>
    </div>
  </div>
  </div>
  <script src="{{asset('/fr7/packages/core/js/framework7.bundle.min.js')}}"></script>
  <script src="{{asset('/fr7/js/routes.js')}}"></script>
  <script src="{{asset('/fr7/js/app.js')}}"></script>
</body>
</html>
