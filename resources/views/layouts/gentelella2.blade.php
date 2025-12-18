<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{asset(env('APP_IMG'))}}" rel="icon">

    <!-- Bootstrap -->
    <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    @yield('css')
    <!-- Custom Theme Style -->
    <link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v10.0" nonce="t0A2wSJd"></script>
    
  </head>
  <style>
    .img-circle.profile_img {
        width: 100%;
        background: #1F618D;
        margin-left: 45%;
        z-index: 1000;
        position: inherit;
        margin-top: 0px;
        border: 1px solid #1F618D;
        padding: 2px;
    }
    .profile_info h2 {
        font-size: 14px;
        color: #ECF0F1;
        margin: 0;
        font-weight: 300;
    }
    .profile_info {
        padding: 5px 0px 0px;
        width: 100%;
        align: center;
    }
    .profile_pic {
        width: 50%;
    }
    
    * {
    box-sizing: border-box;
  }
  
  .fab-wrapper {
    position: fixed;
    bottom: 3rem;
    left: 3rem;
  }
  .fab-checkbox {
    display: none;
  }
  .fab {
    position: absolute;
    bottom: -1rem;
    right: -1rem;
    width: 4rem;
    height: 4rem;
    background: blue;
    border-radius: 50%;
    background: #126ee2;
    box-shadow: 0px 5px 20px #81a4f1;
    transition: all 0.3s ease;
    z-index: 1;
    border-bottom-right-radius: 6px;
    border: 1px solid #0c50a7;
  }
  
  .fab:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
  }
  .fab-checkbox:checked ~ .fab:before {
    width: 90%;
    height: 90%;
    left: 5%;
    top: 5%;
    background-color: rgba(255, 255, 255, 0.2);
  }
  .fab:hover {
    background: #2c87e8;
    box-shadow: 0px 5px 20px 5px #81a4f1;
  }
  
  .fab-dots {
    
    position: absolute;
    height: 8px;
    width: 8px;
    background-color: white;
    border-radius: 50%;
    top: 50%;
    transform: translateX(0%) translateY(-50%) rotate(0deg);
    opacity: 1;
    animation: blink 3s ease infinite;
    transition: all 0.3s ease;
  }
  
  .fab-dots-1 {
    left: 15px;
    animation-delay: 0s;
  }
  .fab-dots-2 {
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    animation-delay: 0.4s;
  }
  .fab-dots-3 {
    right: 15px;
    animation-delay: 0.8s;
  }
  
  .fab-checkbox:checked ~ .fab .fab-dots {
    height: 6px;
  }
  
  .fab .fab-dots-2 {
    transform: translateX(-50%) translateY(-50%) rotate(0deg);
  }
  
  .fab-checkbox:checked ~ .fab .fab-dots-1 {
    width: 32px;
    border-radius: 10px;
    left: 50%;
    transform: translateX(-50%) translateY(-50%) rotate(45deg);
  }
  .fab-checkbox:checked ~ .fab .fab-dots-3 {
    width: 32px;
    border-radius: 10px;
    right: 50%;
    transform: translateX(50%) translateY(-50%) rotate(-45deg);
  }
  
  @keyframes blink {
    50% {
      opacity: 0.25;
    }
  }
  
  .fab-checkbox:checked ~ .fab .fab-dots {
    animation: none;
  }
  
  .fab-wheel {
    position: absolute;
    bottom: 0;
    
    border: 1px solid;
    width: 10rem;
    /* height: 50rem; */
    transition: all 0.3s ease;
    transform-origin: bottom right;
    transform: scale(0);
  }
  
  .fab-checkbox:checked ~ .fab-wheel {
    transform: scale(1);
  }
  .fab-action {
    position: absolute;
    background: #0f1941;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: White;
    box-shadow: 0 0.1rem 1rem rgba(24, 66, 154, 0.82);
    transition: all 1s ease;
  
    opacity: 0;
  }
  
  .fab-checkbox:checked ~ .fab-wheel .fab-action {
    opacity: 1;
  }
  
  .fab-action:hover {
    background-color: #f16100;
  }
  
  .fab-wheel .fab-action-1 {
    right: -1rem;
    top: 0;
  }
  
  .fab-wheel .fab-action-2 {
    right: 3.4rem;
    top: 0.5rem;
  }
  .fab-wheel .fab-action-3 {
    left: 0.5rem;
    bottom: 3.4rem;
  }
  .fab-wheel .fab-action-4 {
    left: 0;
    bottom: -1rem;
  }

  .button {
      width: 72px;
      height: 72px;
      line-height: 60px;
      display: block;
      position: relative;
      -moz-border-radius: 50%;
      -webkit-border-radius: 50%;
      border-radius: 50%;
      border: 2px solid #444;
      text-align: center;
      display: inline-block;
      vertical-align: middle;
      position: relative;
      z-index: 10;
    }

    #modal-fb{
      position: fixed;
      top: auto;
      right: auto;
      bottom: 0;
      z-index: 1050;
      outline: 0;
      width: auto;
      height: auto;
      margin-left: 10px;
    }
  </style>
  <body class="nav-md">
    @include('fb')
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            
            <div class="navbar nav_title" style="background: black;" style="border: 0;">
              <a href="{{route('home')}}" class="site_title"><img src="{{asset(env('APP_IMG'))}}" width="35px" height="35px">
                <!--span>DOFF Online</span-->
                <span><font  face='Candara,Calibri,Segoe,"Segoe UI",Optima,Arial,sans-serif'>DOFF Online</font></span>
              </a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            
            
            <div class="profile">
              <div class="profile_pic">
                <img src="" alt="..." class="img-circle profile_img account-photo">
              </div>
              <div class="profile_info">
                <h2 align="center">{{ ucwords(strtolower(Auth::user()->name)) }}</h2>
                <h5 align="center">
                  <small style='color:#dfed42'>{{ date("F d, Y") }}</small><br>
                    
                  
                </h5>
              </div>
              
            </div>

            <!-- /menu profile quick info -->
            
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <div class="clearfix"></div>
                <ul class="nav side-menu">
                  <li class="{{Route::currentRouteName()=='home' ? 'active' : ''}}">
                      <a href="{{route('home')}}"><i class="fa fa-bar-chart-o"></i> Dashboard</a>
                  </li>
                  <li class="{{Route::currentRouteName()=='waybills.create' || Route::currentRouteName()=='waybills.index' ? 'active' : ''}}">
                    <a><i class="fa fa-shopping-cart"></i> Bookings <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="{{Route::currentRouteName()=='waybills.create' ? 'current-page' : ''}}"><a href="{{route('waybills.create')}}">Create Online Booking</a>
                      </li>
                      <li class="{{Route::currentRouteName()=='waybills.index' ? 'current-page' : ''}}"><a href="{{route('waybills.index')}}">Transaction List</a>
                      </li>
                    </ul>
                  </li>
                  <li class="{{Route::currentRouteName()=='contacts.create' || Route::currentRouteName()=='contacts.index' ? 'active' : ''}}">
                    <a><i class="fa fa-users"></i> Shippers/Consignees <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li class="{{Route::currentRouteName()=='contacts.create' ? 'current-page' : ''}}">
                          <a href="{{route('contacts.create')}}">Create new</a>
                      </li>
                      <li class="{{Route::currentRouteName()=='contacts.index' ? 'current-page' : ''}}">
                          <a href="{{route('contacts.index')}}">List</a>
                      </li>
                    </ul>
                  </li>
                  @has_doff_account					
                  <li class="{{Route::currentRouteName()=='doff-transactions' ? 'active' : ''}}">
                      <a href="{{route('doff-transactions')}}"><i class="fa fa-file"></i> DOFF Transaction(s)</a>
                  </li>
                  @endhas_doff_account
                  <li><a><i class="fa fa-tty"></i> Contact Us <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="{{route('contact-us.complain')}}">Complain</a>
                      </li>
                      <li><a href="{{route('contact-us.feedback')}}">Feedback</a>
                      </li>
                      <li><a href="{{route('contact-us.request-quote')}}">Request Quotation</a>
                      </li>
                    </ul>
                  </li>
                  

                  <li class="{{Route::currentRouteName()=='guides.show' ? 'active' : ''}}">
                    <a><i class="fa fa-question"></i> FAQ <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="{{route('guides.show','English')}}">English</a>
                      </li>
                      <li><a href="{{route('guides.show','Tagalog')}}">Tagalog</a>
                      </li>
                    </ul>
                  </li>

                  <li>
                      <a href="{{route('branches.list')}}"><i class="fa fa-map"></i> Branches</a>
                  </li>
                  <li>
                      <a href="http://facebook.com/dailyoverland"><i class="fa fa-facebook"></i> Facebook CSR</a>
                  </li>
                </ul>
              </div>
              

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <!-- <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Profile">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Password">
                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Address Book">
                <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div> -->
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{asset('/images/'.Auth::user()->avatar)}}" alt="" class="account-photo">{{Auth::user()->name}}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li>
                      <a href="{{url('/account')}}">
                        <i class="glyphicon glyphicon-user pull-right"></i>
                        Profile
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();localStorage.removeItem('useravatar');"><i class="glyphicon glyphicon-off pull-right"></i> Log Out</a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                    </li>
                  </ul>
                </li>

              </ul>
            </nav>
          </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                @yield('bread-crumbs')
              </div>

            </div>
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                @yield('content')

                
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>
    
    

    

    
    <!-- Bootstrap -->
    <script src="{{asset('/gentelella')}}/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{{asset('/gentelella')}}/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{{asset('/gentelella')}}/vendors/nprogress/nprogress.js"></script>
    
    <script src="{{asset('/js/messenger-plugin.js')}}"></script>
    @yield('plugins')

    @yield('scripts')
    <!-- Custom Theme Scripts -->
    <script src="{{asset('/gentelella')}}/js/custom.js"></script>
    <script>
      $('.account-photo')[0].src=localStorage.getItem('useravatar');
      $('.account-photo')[1].src=localStorage.getItem('useravatar');
    </script>
  </body>
</html>