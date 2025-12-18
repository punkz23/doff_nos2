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
    <link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />

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
    #menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    #menu li {
      display: inline-block;
    }

  </style>
  <body class="nav-md">
    @include('fb')
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div  class="left_col scroll-view left-div-resize">

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
                  <small hidden class="pca_exp_date" style='color:white'></small>
                  <small style='color:#dfed42'>{{ date("F d, Y") }}</small><br>
                </h5>
              </div>
              @if(Auth::user()->personal_corporate==1)
              <div hidden class="col-md-12 col-sm-12 col-xs-12 div_pca_selection">
                  <select class="form-control pca_selection">
                  </select>
              </div>
              @endif
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 " id="menu" >
                  <!--style>
                  @media screen and (max-width: 1000px) {
                    .button-text {
                        display: none;
                    }
                  }

                  </style-->

                  <script>
                    const resize_ob = new ResizeObserver(function(entries) {
                          // since we are observing only a single element, so we access the first element in entries array
                          let rect = entries[0].contentRect;
                          // current width & height
                          let width = rect.width;
                          let height = rect.height;

                          if(width <= 70){
                            $(".button-text").hide();
                          }else{
                            $(".button-text").show();
                          }
                        });

                        // start observing for resize
                        resize_ob.observe(document.querySelector(".left-div-resize"));
                  </script>
                  @if( Auth::user()->personal_corporate==0  )
                  <center>
                    <ul>
                      <li><font color="green"><i class="fa fa-check-circle-o"></i></font><div style="height: 1px;background-color:green;"></div> <font color="white" size="1px;">registered</font>&emsp;</li>
                      <li><font color="{{ Auth::user()->contact->verified_account==1 || Auth::user()->contact->verified_account==2  ? 'green' : ''}}"><i class="fa fa-{{ Auth::user()->contact->verified_account==1 || Auth::user()->contact->verified_account==2 ? 'check-circle-o' : 'circle-o'}}"></i></font><div style="height: 1px;background-color:{{ Auth::user()->contact->verified_account==1 || Auth::user()->contact->verified_account==2  ? 'green' : 'white'}};"></div> <font color="white" size="1px;">pending verification</font>&emsp;</li>
                      <li><font color="{{ Auth::user()->contact->verified_account==1  ? 'green' : ''}}"><i class="fa fa-{{ Auth::user()->contact->verified_account==1  ? 'check-circle-o' : 'circle-o'}}"></i></font><div style="height: 1px;background-color:{{ Auth::user()->contact->verified_account==1  ? 'green' : 'white'}};"></div> <font color="white" size="1px;">verified</font></li>
                    </ul>
                    @if( Auth::user()->contact->verified_account==0  )
                        <button  style="width: fit-content;white-space:normal !important;word-wrap: break-word;" title="*Verify your account to view status of your bookings." type="button" data-toggle="modal" data-target=".verify_account" class="btn btn-default btn-xs verify_account_btn_pending"><i class="fa fa-check"></i><span class="button-text"> Apply for verification</span></button>
                        <div class="alert alert-danger alert-dismissible button-text" role="alert" style="text-align:left;">
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <small>* Verify your account to view status of your bookings.</small>
                        </div>
                    @elseif( Auth::user()->contact->verified_account==2 || Auth::user()->contact->verified_account==1   )
                      <button   style="width: fit-content;white-space:normal !important;word-wrap: break-word;" type="button" data-toggle="modal" data-target=".verify_account" class="btn btn-default btn-xs verify_account_btn_verify"><i class="fa fa-eye"></i> <span class="button-text">{{ Auth::user()->contact->verified_account==1  ? 'View QR Code' : 'View for verification details' }}</span></button>
                      @if( Auth::user()->contact->verified_account==1  )
                      <div class="alert alert-danger alert-dismissible button-text" role="alert" style="text-align:left;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <small>* Send your contact details to others by simply generating and sending QR Code. Online Bookers with verified account can earn points by using/uploading QR Codes of verified contacts. QR Codes can be permanently saved to address book.</small>
                      </div>
                      @endif
                    @endif

                  </center>
                  @endif
                  <div class="modal fade verify_account" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-{{ Auth::user()->contact->verified_account != 1 ? 'md' : 'lg' }}">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>

                          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-{{ Auth::user()->contact->verified_account != 1 ? 'check' : 'qrcode' }}" ></i> {{ Auth::user()->contact->verified_account==1  ? 'QR CODE' : ( Auth::user()->contact->verified_account==2  ? 'PENDING FOR VERIFICATION ACCOUNT' : 'VERIFY ACCOUNT' ) }} </h4>
                        </div>
                        <div class="modal-body">
                          <div class="col-xs-12 col-sm-12 col-md-12 div_qr_code_profile" {{ Auth::user()->contact->verified_account != 1 ? 'hidden' : '' }} ></div>
                          @if( Auth::user()->contact->verified_account==0  )
                            <form class="form-horizontal form-label-left"  enctype="multipart/form-data" method="POST" action="/save-apply-verification"  id="save_av_form" >
                              @csrf
                                <div class="form-group">
                                  <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                                    <select style="width:100%;" class="selectdata_group form-control av_id_type" required name="av_id_type">
                                      <option value="">--Select Type of ID--</option>
                                    </select>


                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                                    ID #:
                                    <input required  type="text" class="form-control av_id_no" name="av_id_no" /><br><br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                                    Picture of ID:
                                    <input required accept="image/*" type="file" class="av_id_pic" name="av_id_pic" />
                                    <div style="height:200px">
                                      <img  class="img-responsive" name="photo_av_id_pic" id="photo_av_id_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
                                    </div>

                                  </div>
                                  <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                                    Picture with ID:
                                    <input required accept="image/*" type="file" class="av_id_w_pic" name="av_id_w_pic" />
                                    <div style="height:200px">
                                      <img class="img-responsive" name="photo_av_id_w_pic" id="photo_av_id_w_pic" src="{{asset('/images/default-avatar.png')}}" alt="Item Image" width="150px" height="150px">
                                    </div>

                                  </div>
                                </div>

                              <div class="modal-footer">

                                <button type="submit"  class="btn btn-primary">Submit</button>
                              </div>
                            </form>

                          @elseif( Auth::user()->contact->verified_account==2  )
                          <div class="form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                              ID Type: <u>{{ Auth::user()->contact_verified->valid_id->valid_id_desc }}</u>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                              ID #: <u>{{ Auth::user()->contact_verified->id_no }}</u>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                              Picture of ID:
                              <img src="data:image/png;base64,{{ base64_encode(Auth::user()->contact_verified->contacts_verification_pic_id) }}"  width="200px" height="200px" />

                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6" style="text-align:left;border-color:#CCD1D1;background-color:white;">
                              Picture with ID:
                              <img src="data:image/png;base64,{{ base64_encode(Auth::user()->contact_verified->contacts_verification_pic_with_id) }}"  width="200px" height="200px" />

                            </div>
                          </div>
                          @endif
                          @if( Auth::user()->contact->verified_account==2 || Auth::user()->contact->verified_account==1 )
                            <div class="modal-footer">
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
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
                      @if(
                        Auth::user()->personal_corporate==0 ||
                        (
                          Auth::user()->personal_corporate==1 &&
                          (
                            (
                              session('pca_atype') == 'internal' && in_array("booking", session('pca_internal_access'))
                            )
                            || session('pca_atype') == 'external'
                            || session('pca_no') == Auth::user()->contact_id
                          )
                        )
                      )
                      <li class="{{Route::currentRouteName()=='waybills.create' ? 'current-page' : ''}}"><a href="{{route('waybills.create')}}">Create Online Booking</a>
                      </li>
                      @endif

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
                  @if(Auth::user()->personal_corporate==1 && session('pca_atype') != 'external' )
                    @if(

                      (
                        session('pca_atype') == 'internal'
                        && !empty(array_intersect(['unpaid_transaction','ledger','deposit','transaction_list','agent'], session('pca_internal_access') ))
                      )
                      ||
                      session('pca_no') == Auth::user()->contact_id
                    )
                    <li class="{{Route::currentRouteName()=='pca.transactions' ? 'active' : ''}}">
                        <a href="{{route('pca.transactions')}}"><i class="fa fa-file"></i> DOFF Transaction(s)</a>
                    </li>
                    @endif
                    @if(
                      ( session('pca_atype') == 'internal' && in_array("manage_account", session('pca_internal_access')) )
                      ||
                      session('pca_no') == Auth::user()->contact_id
                    )
                    <li class="{{Route::currentRouteName()=='pca.accounts' ? 'active' : ''}}">
                        <a href="{{route('pca.accounts')}}"><i class="fa fa-users"></i> Manage Account</a>
                    </li>
                    @endif
                  @else
                    @has_doff_account
                    <li class="{{Route::currentRouteName()=='doff-transactions' ? 'active' : ''}}">
                        <a href="{{route('doff-transactions')}}"><i class="fa fa-file"></i> DOFF Transaction(s)</a>
                    </li>
                    @endhas_doff_account
                  @endif
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
                    @if( Auth::user()->personal_corporate==1 && session('pca_no') == Auth::user()->contact_id )
                    <li>
                      <a data-action="2" data-pca_no="{{ session('pca_no') }}" class="pca_deactivate" data-toggle="modal" data-target=".pca_deactivate_modal" >
                        <i class="glyphicon glyphicon-file pull-right"></i>
                        Apply for Termination
                      </a>
                    </li>
                    <li hidden class="pca_apply_renewal">
                      <a data-action="3" data-pca_no="{{ session('pca_no') }}" class="pca_deactivate" data-toggle="modal" data-target=".pca_deactivate_modal" >
                        <i class="glyphicon glyphicon-check pull-right"></i>
                        Apply for Renewal
                      </a>
                    </li>
                    <li>
                      <a data-pca_no="{{ session('pca_no') }}" class="pca_on_off_notif" data-toggle="modal" data-target=".pca_on_off_notif_modal" >
                        <i class="glyphicon glyphicon-bell pull-right"></i>
                        On/Off Notification
                      </a>
                    </li>
                    @endif
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

        <div class="modal fade pca_on_off_notif_modal"  role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-md" >
              <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <i class="fa fa-bell-o"></i> NOTIFICATION</h4>

                  </div>
                  <div class="modal-body">

                      <div id="div_on_off_notif_loading" class="spinner"><center><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</center></div>
                      <table class="table table-bordered table_on_off_notif">
                        <tbody>
                        </tbody>
                      </table>

                      <div class="modal-footer " ></div>


                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade pca_deactivate_modal"  role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg" >
              <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close pca_deactivate_modal_close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title pca_deactivate_modal_h4"> <i class="fa fa-file-o"></i> APPLY TERMINATION OF ACCOUNT</h4>

                  </div>
                  <div class="modal-body">

                      <div id="div_deactivate_account_loading" class="spinner"><center><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</center></div>
                      <div class="alert alert-danger alert-dismissible button-text div_deactivate_account" role="alert" style="text-align:left;">

                        <h5 class="h5_div_deactivate_account"></h5>
                      </div>
                      <form enctype="multipart/form-data" class="form-horizontal form-label-left deactivate_account_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                          @csrf
                          <input type="hidden" name="deactivate_account_no" />
                          <input type="hidden" name="pca_deactivate_action" />
                          <input type="hidden" name="pca_renewal_email_id" />

                          <div class="alert alert-info">
                              <strong><i class="fa fa-upload"></i> REQUIREMENTS </strong><i>(image only)</i>
                          </div>
                          <div class="form-group row" >
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                  <div id="tbl_deactivate_account_req_loading" class="spinner"><center><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</center></div>
                                  <table class="table table-striped">
                                      <tbody id="tbl_deactivate_account_req"></tbody>
                                  </table>
                              </div>
                          </div>
                          <div class="form-group row" >
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                  <textarea style="height:250px;" class="form-control col-md-12 col-xs-12"  name="deactivate_account_remarks" placeholder="Remarks"  required ></textarea>
                              </div>
                          </div>

                          <div class="modal-footer " >
                              <br><br>
                              <button type="submit" id="deactivate_account_form_btn" class="btn btn-danger "><i class="fa fa-check"></i> Submit</button>
                              <div id="deactivate_account_form_loading" class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                          </div>

                      </form>
                  </div>
              </div>
          </div>
      </div>


        <!-- footer content -->
        <footer>
          @if (newsletterSubscription()!= null)
              <a href="#" class="newsletterSubscribe" name="unsubscribe">Click here to <strong class="text-danger">UNSUBSCRIBE </strong>to our newsletter.</a>
          @else
             <a href="#" class="newsletterSubscribe" name="subscribe">Keep updated with our latest updates. Click here to <strong class="text-success">SUBSCRIBE.</strong></a>
          @endif

          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    {{-- <div class="fab-wrapper">
        <input id="fabCheckbox" type="checkbox" class="fab-checkbox" />
        <label class="btn btn-primary fab button" data-toggle='modal' data-target='#modal-fb' for="fabCheckbox">
        <i style="font-size:30px;margin-top:10px;" class="fa fa-facebook"></i>
        <!-- <span class="fab-dots fab-dots-1"></span>
        <span class="fab-dots fab-dots-2"></span>
        <span class="fab-dots fab-dots-3"></span> -->
        </label>
        <!-- <label class="fab"><i style="font-size:24px;color:white;">&#xf39f;</i></label> -->
        <div class="fab-wheel">
          <div>

          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-fb" role="dialog">
      <div class="modal-dialog modal-md">
            <button type="button" class="btn btn-danger" data-dismiss="modal">&times; Close</button><br>
            <div class="fb-page"
            data-href="https://www.facebook.com/dailyoverland/"
            data-tabs="timeline"
            data-width=""
            data-height=""
            data-small-header="false"
            data-adapt-container-width="false"
            data-hide-cover="false"
            data-show-facepile="true"
            >
              <blockquote cite="https://www.facebook.com/dailyoverland/" class="fb-xfbml-parse-ignore">
                  <a href="https://www.facebook.com/dailyoverland/">Daily Overland Freight Forwarder</a>
              </blockquote>
            </div>



      </div>
    </div> --}}

    <!-- jQuery -->
    <script src="{{asset('/gentelella')}}/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{asset('/gentelella')}}/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{{asset('/gentelella')}}/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{{asset('/gentelella')}}/vendors/nprogress/nprogress.js"></script>
    <!-- SweetAlert -->
    <script src="{{asset('/js/sweetalert.min.js')}}"></script>
    <script src="{{asset('/js/messenger-plugin.js')}}"></script>
    <script src="{{asset('/theme/js/select2.min.js')}}"></script>
    @yield('plugins')

    @yield('scripts')

    <!-- Custom Theme Scripts -->
    <script src="{{asset('/gentelella')}}/js/custom.js"></script>

    <script>
      $('.account-photo')[0].src=localStorage.getItem('useravatar');
      $('.account-photo')[1].src=localStorage.getItem('useravatar');

      $(".newsletterSubscribe").on('click',function(event){
        event.preventDefault();

        let type = $(this).attr('name');

        $.ajax({
          url: "{{route('newsletter.subscribe-customer')}}",
          type: "POST",
          data: {
            _token : "{{csrf_token()}}",
            type : type,
          },
          success: function(result){

              new swal({
                text:result.message,
                icon: result.type,
                title: result.title
              }).then(function(){
                // window.location.reload();
                if(type=="subscribe"){
                  $(".newsletterSubscribe").html('Click here to <strong class="text-danger">UNSUBSCRIBE </strong>to our newsletter.');
                  $(".newsletterSubscribe").attr('name','unsubscribe');
                }else{
                  $(".newsletterSubscribe").html('<a href="#" class="newsletterSubscribe" name="subscribe">Keep updated with our latest updates. Click here to <strong class="text-success">SUBSCRIBE.</strong>');
                  $(".newsletterSubscribe").attr('name','subscribe');
                }

              });
          }
        });

        // alert(type);
      })

      function newsletterSubscribe($email){
        //alert();
      }

      $(".verify_account_btn_pending").click(function(){
        get_id_type();

      });
      $(".verify_account_btn_verify").click(function(){

        $(".div_qr_code_profile").html('<div class="qr_code_profile_loader"><center> Please wait while Loading.....</center></div>');
        $('.div_qr_code_profile').load('/profile-qr-code');
      });



      @if (\Session::has('success'))
        alert('{!! \Session::get('success') !!}');
      @endif

      @if(Auth::user()->personal_corporate==1)
        get_pca_selection();

      @endif
      @if( Auth::user()->personal_corporate==1 && session('pca_no') == Auth::user()->contact_id )
        get_pca_exp_date_func();
        pca_check_apply_for_renewal_func();
      @else
        $(".pca_exp_date").hide();
        $(".pca_apply_renewal").hide();
      @endif
      function pca_check_apply_for_renewal_func(action=''){
        $(".pca_apply_renewal").hide();
        $('input[name="pca_renewal_email_id"]').val();
        $.ajax({
            url: "/pca-account-check-apply-renewal/"+btoa('{{ session('pca_no') }}'),
            type: "GET",
            dataType: "json",
            success: function(result){

              if(result.length > 0){

                $.each(result,function(){
                  $('input[name="pca_renewal_email_id"]').val(this.pca_accounts_emailing_id);
                  if(
                    (
                      this.pca_account_renewal_id != null
                      && this.pca_account_renewal_id  !=''
                      && Number(this.pca_account_renewal_status) !=1
                    )
                    || this.pca_account_renewal_id == null
                    || this.pca_account_renewal_id ==''
                  ){
                    $(".pca_apply_renewal").show();
                  }else{
                    if(action =='renewal_cs'){
                      $(".deactivate_account_form").hide();
                      $(".div_deactivate_account").show();
                      $(".h5_div_deactivate_account").html('* Application for Renewal already submitted. You will be notified once your request has been approved.');
                    }
                  }
                });
              }


            }, error: function(){
              new swal({
                text:"An error occured.",
                icon: 'error',
                title: jqXHR.responseJSON.message,
              });
            }
        });
      }
      function get_pca_exp_date_func(){

        $.ajax({
            url: "/pca-account-exp-date/"+btoa('{{ session('pca_no') }}'),
            type: "GET",
            dataType: "json",
            success: function(result){

              $.each(result, function(){
                $(".pca_exp_date").html('Expiration Date: '+this.exp_date+'<br>');
                $(".pca_exp_date").show();
              });

            }, error: function(){
              new swal({
                text:"An error occured.",
                icon: 'error',
                title: jqXHR.responseJSON.message,
              });
            }
        });
      }
      function get_pca_selection(){

        $.ajax({
            url: "/pca-account-selected-update/"+btoa('{{ session('pca_no') }}'),
            type: "GET",
            dataType: "json",
            success: function(result){
            }, error: function(){
              new swal({
                text:"An error occured.",
                icon: 'error',
                title: jqXHR.responseJSON.message,
              });
            }
        });
        $(".pca_selection").html('');
        $.ajax({
            url: "/pca-account-selection-list",
            type: "GET",
            dataType: "json",
            success: function(result){
              if(Number(result['count'])==0){
                alert('No Account Found.');
                document.getElementById('logout-form').submit();
              }else{
                $(".pca_selection").html(result['option']);
                if(Number(result['count'])==1){
                  @if(session('pca_no') == Auth::user()->contact_id)
                    $(".div_pca_selection").hide();
                  @else
                    $(".div_pca_selection").show();
                  @endif
                }
                else{
                  $(".div_pca_selection").show();
                }
              }
            }, error: function(){
               new swal({
                    icon: "error"
                });
            }
        });

      }
      $(".pca_selection").change(function(){
        $.ajax({
            url: "/pca-account-selected-update/"+btoa(this.value),
            type: "GET",
            dataType: "json",
            success: function(result){
              location.reload();
            }, error: function(){
              new swal({
                text:"An error occured.",
                icon: 'error',
                title: jqXHR.responseJSON.message,
              });
            }
        });
      });

      $(".pca_deactivate").click(function(){

        pca_no=$(this).data('pca_no');
        action=$(this).data('action');

        if(Number(action)==3){
          $(".pca_deactivate_modal_h4").html('<i class="fa fa-check"></i> APPLY RENEWAL OF ACCOUNT');
        }else{
          $(".pca_deactivate_modal_h4").html('<i class="fa fa-file-o"></i> APPLY TERMINATION OF ACCOUNT');
        }
        $('input[name="pca_deactivate_action"]').val(action);
        $('textarea[name="deactivate_account_remarks"]').val('');
        $('input[name="deactivate_account_no"]').val(pca_no);
        $("#deactivate_account_form_loading").hide();
        $("#tbl_deactivate_account_req").html('');
        $("#tbl_deactivate_account_req_loading").hide();
        $(".deactivate_account_form").hide();
        $(".div_deactivate_account").hide();
        $("#div_deactivate_account_loading").show();

        $.ajax({
            url: "/get-pca-deactivation-pending-count/"+btoa(pca_no)+'/'+btoa(action),
            type: "GET",
            dataType: "json",
            success: function(result){

              if(Number(result) > 0 ){
                $(".div_deactivate_account").show();
                if(Number(action)==3){
                  $(".h5_div_deactivate_account").html('* Application for Renewal already submitted. You will be notified once your request has been approved.');
                }else{
                  $(".h5_div_deactivate_account").html('* Application for Termination already submitted. You will be notified once your request has been approved.');
                }
              }else{
                $(".deactivate_account_form").show();
                get_pca_deactivation_req(pca_no,action);
              }
              $("#div_deactivate_account_loading").hide();

            }, error: function(){
                new swal({
                    icon: "error"
                });
                $("#div_deactivate_account_loading").hide();
            }
        });
        if(Number(action)==3){
          pca_check_apply_for_renewal_func('renewal_cs');
        }


      });
      function get_pca_deactivation_req(pca_no,action){


        $("#tbl_deactivate_account_req_loading").show();

        $.ajax({
            url: "/get-pca-requirements/"+btoa(pca_no)+"/"+btoa(action),
            type: "GET",
            dataType: "json",
            success: function(result){
                $("#tbl_deactivate_account_req").html('');
                $("#tbl_deactivate_account_req_loading").show();
                $.each(result,function(){

                    upload_file_required=0;
                    upload_file_required_txt='';
                    if(Number(this.upload_file)==1){
                        upload_file_required=1;
                        upload_file_required_txt='<small><font color="red"><i> (*required uploading file)</i></font></small></i>';
                    }

                    $("#tbl_deactivate_account_req").append('<tr>'+
                        '<td width="5%" align="center" >'+
                            '<input onclick="return false;" checked value="'+this.pca_requirements_id+'" type="checkbox" name="deactivate_account_req[]" />'+
                            '<input value="'+this.pca_requirements_name+'" type="hidden" name="deactivate_account_req_name_'+this.pca_requirements_id+'" />'+
                            '<input value="'+upload_file_required+'" type="hidden" name="deactivate_account_req_file'+this.pca_requirements_id+'" />'+
                        '</td>'+
                        '<td>'+
                        this.pca_requirements_name+upload_file_required_txt+
                        '<button  type="button" data-id="'+this.pca_requirements_id+'" class="btn btn-info btn-xs pull-right btn_deactivate_account_req_file" ><i class="fa fa-plus"> </i> UPLOAD FILE</button>'+
                        '<table width="100%"><tbody id="tbl_deactivate_account_req_file_'+this.pca_requirements_id+'" ></tbody></table>'+
                        '</td>'+
                    '</tr>');


                });
                $("#tbl_deactivate_account_req_loading").hide();

            }, error: function(){
                new swal({
                    icon: "error"
                });
                $("#tbl_deactivate_account_req_loading").hide();
            }
        });
      }
      termination_req_count=0;
      $('#tbl_deactivate_account_req').on('click', '.btn_deactivate_account_req_file', function(){

          id=$(this).data('id');
          $("#tbl_deactivate_account_req_file_"+id).append('<tr id="tr_deactivate_account_req_file_remove_'+termination_req_count+'" >'+
              '<td width="5%"><button type="button" data-req_count="'+termination_req_count+'" class="btn btn-danger btn-xs btn_deactivate_account_req_file_remove" ><i class="fa fa-trash"></i> </button></td>'+
              '<td>'+
              '<input  name="deactivate_account_req_file_id_'+id+'[]" type="hidden" />'+
              '<input required onchange="pca_deactivate_readURL(this,'+termination_req_count+','+id+');"  accept="image/*" name="deactivate_account_req_file_'+id+'[]" type="file" class="form-control" /></td>'+
              '<td><img class="img-responsive avatar-view"  width="150" height="150" id="photo_'+id+'_'+termination_req_count+'" /></td>'+
              '</tr>');
              termination_req_count++;
      });
      $('#tbl_deactivate_account_req').on('click', '.btn_deactivate_account_req_file_remove', function(){
        tr_count=$(this).data('req_count');
        $("#tr_deactivate_account_req_file_remove_"+tr_count).remove();
      });

      function pca_deactivate_readURL(input,rcount,id) {
          if (input.files && input.files[0]) {

              var reader = new FileReader();

              reader.onload = function (e) {
                  $('#photo_'+id+'_'+rcount)
                      .attr('src', e.target.result);
              };

              reader.readAsDataURL(input.files[0]);
          }
      }

      $(".deactivate_account_form").submit(function(){

        event.preventDefault();
        cannot_proceed_req=0;
        deactivate_account_req=document.getElementsByName('deactivate_account_req[]');
        for(i=0;i< deactivate_account_req.length;i++){

            deactivate_account_req_file=$('input[name="deactivate_account_req_file'+deactivate_account_req[i].value+'"]').val();
            req_file=document.getElementsByName('deactivate_account_req_file_'+deactivate_account_req[i].value+'[]');
            if(Number(deactivate_account_req_file)==1 && req_file.length==0 ){
                cannot_proceed_req++;
            }
        }
        if(cannot_proceed_req > 0){
            alert('Please upload at least 1 file on requirements with required uploading file label.');
        }else{
            document.getElementById("deactivate_account_form_btn").disabled=true;
            $("#deactivate_account_form_loading").show();

            $.ajax({
                url: "/deactivate-pca-account",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                processData:false,
                success: function(result)
                {
                    type_result=Number(result.type);
                    new swal({
                        text:result.message,
                        icon: 'success',
                        title: 'success'
                    }).then(function(){
                        if( type_result== 1){
                          $(".pca_deactivate_modal_close").click();
                        }
                    });
                    document.getElementById("deactivate_account_form_btn").disabled=false;
                    $("#deactivate_account_form_loading").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    new swal({
                        text:"An error occured.",
                        icon: 'error',
                        title: jqXHR.responseJSON.message,
                    });
                    document.getElementById("deactivate_account_form_btn").disabled=false;
                    $("#deactivate_account_form_loading").hide();
                }
            });
        }


        });
        $(".pca_on_off_notif").click(function(){
          get_pca_on_off_notif_func();
        });
        function get_pca_on_off_notif_func(){

          $(".table_on_off_notif tbody").html('');
          $("#div_on_off_notif_loading").show();
          $.ajax({
              url: "/get-pca-notif",
              type: "GET",
              dataType: "json",
              success: function(result){

                  $(".table_on_off_notif tbody").html('');
                  $("#div_on_off_notif_loading").show();
                  if(result.length > 0){
                    $(".table_on_off_notif tbody").html('<tr><td colspan="2">'+
                    '<input data-notif_id="ALL" class="checkbox_on_off checkbox_on_off_ALL" type="checkbox" /> CHECK ALL'+
                    '</td></tr>');
                  }
                  count_checked=0;
                  $.each(result,function(){
                    notif_desc='';

                    if(this.notif_desc !='' && this.notif_desc != null){
                      notif_desc= ' (<small>'+this.notif_desc+'</small>)';
                    }
                    checked='checked';
                    count_checked++;
                    if(this.pca_off_notif_details_id != null){
                      checked='';
                      count_checked--;
                    }
                    soa_range='';
                    if(Number(this.soa_notif)==1){
                        m_check='checked';
                        bm_check='';
                        if(Number(this.soa_range)==2){
                            m_check='';
                            bm_check='checked';
                        }
                        disabled_sr='';
                        if(checked==''){
                            disabled_sr='disabled';
                        }
                        soa_range='<br><p><input '+disabled_sr+' '+bm_check+' data-notif_id="'+this.pca_on_off_notif_id+'" value="2" type="radio" class="pca_soa_range" name="pca_soa_range" /> Bi-Monthly (1-15)&emsp;<input '+disabled_sr+' data-notif_id="'+this.pca_on_off_notif_id+'" class="pca_soa_range" '+m_check+' value="1" type="radio" name="pca_soa_range" /> Monthly</p>';
                    }
                    $(".table_on_off_notif tbody").append('<tr>'+
                    '<td width="5%"  >'+
                    '<input id="pca_notif_id_'+this.pca_on_off_notif_id+'" data-soa_notif="'+this.soa_notif+'" data-notif_id="'+this.pca_on_off_notif_id+'" '+checked+' class="checkbox_on_off" type="checkbox" />'+
                    '</td>'+
                    '<td>'+this.notif_name+notif_desc+soa_range+'</td>'+
                    '</tr>');

                  });

                  if( result.length == count_checked ){
                    $(".checkbox_on_off_ALL").prop('checked',true);
                  }
                  $("#div_on_off_notif_loading").hide();

              }, error: function(){
                  new swal({
                      icon: "error"
                  });
                  $("#div_on_off_notif_loading").hide();
              }
          });
        }
        $('.table_on_off_notif').on('click', '.pca_soa_range', function(){

            notif_id=$(this).data('notif_id');
            if(document.getElementById('pca_notif_id_'+notif_id).checked){
                pca_soa_range_func();
            }

        });
        function pca_soa_range_func(){
            $.ajax({
                url: "/pca-account-soa-range",
                type: "GET",
                data:{
                    soa_range:btoa($("input[name='pca_soa_range']:checked").val())
                },
                dataType: "json",
                success: function(result){
                    get_pca_on_off_notif_func();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    new swal({
                        text:"An error occured.",
                        icon: 'error',
                        title: jqXHR.responseJSON.message,
                    });

                }
            });
        }
        $('.table_on_off_notif').on('click', '.checkbox_on_off_ALL', function(){

          if(this.checked){
            $(".checkbox_on_off").prop('checked',true);
          }else{
            $(".checkbox_on_off").prop('checked',false);
          }

        })
        $('.table_on_off_notif').on('click', '.checkbox_on_off', function(){

            off_notif=1;
            if(this.checked){
              off_notif=0;
            }
            notif_id=$(this).data('notif_id');
            $.ajax({
                  url: "/on-off-notif-pca-account/"+btoa(off_notif)+"/"+btoa(notif_id),
                  type: "GET",
                  dataType: "json",
                  success: function(result){
                    get_pca_on_off_notif_func();
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    new swal({
                        text:"An error occured.",
                        icon: 'error',
                        title: jqXHR.responseJSON.message,
                    });

                  }
            });
        });
        function doff_check_pwd(){
            $.ajax({
                url: "/login-doff-check-pwd",
                type: "GET",
                dataType: "json",
                success: function(result){

                    if(Number(result)==1){
                        new swal({
                            text:"You may have encountered issues receiving your One-Time Password (OTP). As the OTP services is managed by an external provider, delivery is unfortunately beyond our direct control. To ensure uninterrupted access to your account, we will require setting a secure password instead.",
                            icon: 'info',
                            title: 'Alternative Log In Option- Set your Account Password.',
                        }).then((res) => {
                            window.location='/doff-set-password/'+btoa('doff_user_login');
                        });
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    new swal({
                        text:"An error occured.",
                        icon: 'error',
                        title: jqXHR.responseJSON.message,
                    });

                }
            });
        }
        doff_check_pwd();

      </script>

    @include ('waybills.id_type_validation');
  </body>
</html>
