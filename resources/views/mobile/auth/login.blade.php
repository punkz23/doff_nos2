@extends('layouts.mobile')

@section('content')
<div class="page no-toolbar no-navbar no-swipeback login-screen-page">
    
    <div class="fab fab-left-bottom color-white">
      <a href="{{url('/')}}" class="external">
        <i class="icon f7-icons color-blue">house</i>
        
      </a>
    </div>

    <div class="fab fab-right-bottom color-white">
      <a href="{{route('guides.index')}}" class="external">
        <i class="icon f7-icons color-green">question</i>
        
      </a>
    </div>

    <div class="page-content login-screen-content">

      <div class="login-screen-title"><img src="{{asset('/images/doff logo.png')}}" width="330px" height="55px" alt="logo"></div>
      <form method="POST" action="{{route('login')}}">
        @csrf
        <div class="list">
          <ul>
            <li style="padding-left:15px;padding-right: 15px;">
                <p class="row" style="margin-bottom:4px;">
                  <a href="{{ url('auth/facebook') }}" class="col button button-fill color-blue text-align-left external"> <i class="icon f7-icons">logo_facebook</i> Login with Facebook</a>
                </p>
                <p class="row">
                    <a href="{{ url('auth/google') }}" class="col button button-fill color-red text-align-left external"> <i class="icon f7-icons">logo_google</i> Login with Google</a>
                </p>
            </li>
            <li class="item-content item-input item-input-with-info">
              <div class="item-inner">
                <div class="item-title item-label">Username</div>
                <div class="item-input-wrap">
                  <input type="email" placeholder="Your Email" id="demo-username-2" class="input-with-value" name="email" value="{{ old('email') }}" required>
                  <span class="input-clear-button"></span>
                  @error('email')
                  <div class="item-input-info"><font color="red">{{$message}}</font></div>
                  @enderror
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Password</div>
                <div class="item-input-wrap">
                  <input type="password" placeholder="Your password" id="demo-password-2" class="" name="password" required>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="list">
          <ul>
            <li><button type="submit" class="button">Sign In</button></li>
          </ul>
          <div class="block-footer"><a href="{{route('register')}}" class="external">Register</a></div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script>

</script>
@endsection

