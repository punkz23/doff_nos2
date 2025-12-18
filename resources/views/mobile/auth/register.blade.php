@extends('layouts.mobile')

@section('content')
<div class="page no-toolbar no-navbar no-swipeback login-screen-page">
    <!-- <div class="fab fab-left-bottom color-white">
      <a href="/bookings/guest-create/">
        <i class="icon f7-icons color-blue">cart_fill_badge_plus</i>
        <i class="icon material-icons color-blue">add_shopping_cart</i>
      </a>
    </div> -->
    <div class="page-content login-screen-content">

    <div class="login-screen-title"><img src="{{asset('/images/doff logo.png')}}" width="330px" height="55px" alt="logo"></div>
      <form method="POST" action="{{route('register')}}">
        @csrf
        <div class="list">
          <ul>
            <li class="item-content item-input item-input-with-value">
                
                <div class="item-inner">
                    <div class="item-title item-label">Contact Status</div>
                    <div class="item-input-wrap">
                    <select name="contact_status" class="input-with-value" required>
                        <option value="0">NEW ACCOUNT</option>
                        <option value="1">CHARGE ACCOUNT</option>
                    </select>
                    
                    </div>
                </div>
            </li>
            <li class="item-content item-input item-input-with-value">
              <div class="item-inner">
                <div class="item-title item-label">Lastname</div>
                <div class="item-input-wrap">
                  <input type="text" placeholder="Lastname" class="input-with-value" name="lname" required>
                  <span class="input-clear-button"></span>
                  
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Firstname</div>
                <div class="item-input-wrap">
                    <input type="text" placeholder="Firstname" class="input-with-value" name="fname" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Middlename</div>
                <div class="item-input-wrap">
                    <input type="text" placeholder="Middlename" class="input-with-value" name="mname" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Company</div>
                <div class="item-input-wrap">
                    <input type="text" placeholder="Company" class="input-with-value" name="company" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Company</div>
                <div class="item-input-wrap">
                    <input type="text" placeholder="Contact #" class="input-with-value" name="contact_no" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Email</div>
                <div class="item-input-wrap">
                    <input type="email" placeholder="Your image" class="input-with-value" name="email" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Password</div>
                <div class="item-input-wrap">
                    <input type="password" class="input-with-value" name="password" required>
                </div>
              </div>
            </li>
            <li class="item-content item-input">
              <div class="item-inner">
                <div class="item-title item-label">Confirm Password</div>
                <div class="item-input-wrap">
                    <input type="password" placeholder="New password" class="input-with-value" name="password_confirmation" required>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="list">
          <ul>
            <li><button type="submit" class="button">Register</button></li>
          </ul>
          <div class="block-footer"><a href="{{route('login')}}" class="external">Back to login</a></div>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script>

</script>
@endsection

