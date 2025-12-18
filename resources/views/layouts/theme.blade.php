
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{asset('/images/ICON.png')}}" rel="icon">

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{asset('theme/css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('theme/font-awesome/4.5.0/css/font-awesome.min.css')}}" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="{{asset('theme/css/fonts.googleapis.com.css')}}" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{asset('theme/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="{{asset('theme/css/ace-part2.min.css')}}" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="{{asset('theme/css/ace-skins.min.css')}}" />
		<link rel="stylesheet" href="{{asset('theme/css/ace-rtl.min.css')}}" />
		<link rel="stylesheet" href="{{asset('/theme/css/jquery.gritter.min.css')}}" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="{{asset('theme/css/ace-ie.min.css')}}" />
		<![endif]-->

		<!-- inline styles related to this page -->
		@yield('css')

        

		<!-- ace settings handler -->
		<script src="{{asset('theme/js/ace-extra.min.js')}}"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="{{asset('theme/js/html5shiv.min.js')}}"></script>
		<script src="{{asset('theme/js/respond.min.js')}}"></script>
		<![endif]-->


	</head>

	<body class="skin-1">
		@if(Auth::check())
		<div id="navbar" class="navbar navbar-default ace-save-state navbar-fixed-top">
			<div class="navbar-container ace-save-state " id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<!-- <i class="fa fa-leaf"></i> -->
							<img class="company-logo" width="25px" height="25px" alt="">
							<noscript>
								<img src="{{asset('/images/icon.png')}}" width="25px" height="25px" alt="">
							</noscript>
							{{ config('app.name', 'Laravel') }}
						</small>
					</a>
				</div>

				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">

						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo user-avatar" alt="Jason's Photo" />
								<noscript>
									<img class="nav-user-photo" src="{{asset('/images/default-avatar.png')}}" alt="Jason's Photo" />
								</noscript>
								<span class="user-info">
									<small>Welcome,</small>
									{{Auth::user()->name}}
								</span>
						
								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">


								<li>
									<a href="{{route('account')}}">
										<i class="ace-icon fa fa-user"></i>
										Profile
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="#" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
										<i class="ace-icon fa fa-power-off"></i>
										Logout
									</a>
									
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
		@endif
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>
            @if(Auth::check())
			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					
				
					<div class="clearfix" style="padding: 10px 4px;position: relative;">
						<div>
						<img class="pull-left user-avatar" style="border: 2px solid #C9D6E5;border-radius: 100%;max-width: 50px;margin-right: 10px;margin-left: 0;box-shadow: none;"  />
							<noscript>
								<img class="pull-left" style="border: 2px solid #C9D6E5;border-radius: 100%;max-width: 50px;margin-right: 10px;margin-left: 0;box-shadow: none;" src="{{asset('/images/default-avatar.png')}}" />
							</noscript>
							<a class="user" style="color:white;"> <small>{{Auth::user()->name}}</small> </a>
						</div>
						<div class="space-2"></div>
						<div class="tools action-buttons">
							<a href="{{route('account')}}" class="blue">
								<i class="ace-icon fa fa-user bigger-125"></i>
							</a>

							<a href="{{route('logout')}}" class="red" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
								<i class="ace-icon fa fa-power-off bigger-125"></i>
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
						</div>
                    </div>
					

					
				</div><!-- /.sidebar-shortcuts -->

				<div class="space-10"></div>
				<ul class="nav nav-list">

					<li class="{{Route::currentRouteName()=='home' ? 'active' : ''}}">
						<a href="{{route('home')}}">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Dashboard </span>
						</a>

						<b class="arrow"></b>
					</li>

					@role('Client')

					<li class="{{Route::currentRouteName()=='waybills.index' || Route::currentRouteName()=='waybills.create' ? 'active open' : ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-shopping-cart"></i>
							<span class="menu-text"> Booking </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{Route::currentRouteName()=='waybills.index' ? 'active' : ''}}">
								<a href="{{route('waybills.index')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Transaction list
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{Route::currentRouteName()=='waybills.create' ? 'active' : ''}}">
								<a href="{{route('waybills.create')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Create Online Booking
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>

					

					<li class="{{Route::currentRouteName()=='contacts.index' || Route::currentRouteName()=='contacts.create' ? 'active open' : ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-users"></i>
							<span class="menu-text"> Shipper/Consignee </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{Route::currentRouteName()=='contacts.index' ? 'active' : ''}}">
								<a href="{{route('contacts.index')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									List
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{Route::currentRouteName()=='contacts.create' ? 'active' : ''}}">
								<a href="{{route('contacts.create')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Create new
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					@has_doff_account					
					<li class="{{Route::currentRouteName()=='doff-transactions' ? 'active' : ''}}">
						<a href="{{route('doff-transactions')}}">
							<i class="menu-icon fa fa-file"></i>
							<span class="menu-text"> DOFF Transaction/s </span>
						</a>

						<b class="arrow"></b>
					</li>
					@endhas_doff_account
					
					<li class="{{Route::currentRouteName()=='contact-us.complain' || Route::currentRouteName()=='contact-us.feedback' || Route::currentRouteName()=='contact-us.request-quote' ? 'active open' : ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-tty"></i>
							<span class="menu-text"> Contact Us </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{Route::currentRouteName()=='contact-us.complain' ? 'active' : ''}}">
								<a href="{{route('contact-us.complain')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Complain
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{Route::currentRouteName()=='contact-us.feedback' ? 'active' : ''}}">
								<a href="{{route('contact-us.feedback')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Feedback
								</a>

								<b class="arrow"></b>
							</li>
							<li class="{{Route::currentRouteName()=='contact-us.request-quote' ? 'active' : ''}}">
								<a href="{{route('contact-us.request-quote')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Request Quotation
								</a>

								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					@endrole

					@hasanyrole('Admin|Client')
					<li class="{{Route::currentRouteName()=='guides.index' || Route::currentRouteName()=='guides.create' ? 'active open' : ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-file-o"></i>
							<span class="menu-text"> Other Page </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{Route::currentRouteName()=='guides.index' ? 'active' : ''}}">
								<a href="{{route('guides.index')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									FAQ
								</a>

								<b class="arrow"></b>
							</li>

							<li class="{{Route::currentRouteName()=='branches.list' ? 'active' : ''}}">
								<a href="{{route('branches.list')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Branches
								</a>

								<b class="arrow"></b>
							</li>

							<!-- <li class="{{Route::currentRouteName()=='chats.index' ? 'active' : ''}}">
								<a href="{{route('chats.index')}}">
									<i class="menu-icon fa fa-caret-right"></i>
									Tech Support
								</a>

								<b class="arrow"></b>
							</li> -->

							<li>
								<a href="http://facebook.com/dailyoverland">
									<i class="menu-icon fa fa-caret-right"></i>
									Facebook CSR
								</a>

								<b class="arrow"></b>
							</li>

						</ul>
					</li>

					
					@endrole

					@role('Admin')
					<li class="{{Route::currentRouteName()=='branches.index' ? 'active open' : ''}}">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa fa-gears"></i>
							<span class="menu-text"> Maintenance </span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
							<li class="{{Route::currentRouteName()=='branches.index' ? 'active' : ''}}">
								<a href="{{route('branches.index')}}">
									<i class="menu-icon fa fa-map-pin"></i>
									Branches
								</a>
                                <a href="{{route('terms.maintenance')}}">
									<i class="menu-icon fa fa-file-text"></i>
									Terms and Condition
								</a>
                                <a href="{{route('guides.create')}}">
									<i class="menu-icon fa fa-plus-square"></i>
									FAQ Create
								</a>
								<b class="arrow"></b>
							</li>
						</ul>
					</li>
					@endrole


				</ul><!-- /.nav-list -->


				<!-- <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div> -->
			</div>
            @endif
			<div class="main-content">
				<div class="main-content-inner">
					@yield('bread-crumbs')

					<div class="page-content">
						<!-- SETTING -->

						<!-- SETTING -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								@yield('content')
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Ace</span>
							Application &copy; 2013-2014
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

        



		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="{{asset('theme/js/jquery-2.1.4.min.js')}}"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="{{asset('theme/js/jquery-1.11.3.min.js')}}"></script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="{{asset('theme/js/bootstrap.min.js')}}"></script>

		<!-- page specific plugin scripts -->
		@yield('plugins')

		<!-- ace scripts -->
		<script src="{{asset('theme/js/ace-elements.min.js')}}"></script>
		<script src="{{asset('theme/js/ace.min.js')}}"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		
		<script src="{{asset('/js/aes.js')}}"></script>
		<script src="{{asset('/js/script.js')}}"></script>
		<!-- inline scripts related to this page -->
		@yield('scripts')

		<script>
			setInterval(function(){
				$.ajax({
					url: "{{url('/auth-check')}}",
					type : "GET",
					success: function(result){
						if(result==0){
							window.location.href="{{route('login')}}";
						}
					}
				});
			},900000);
			
			
		</script>


        
	</body>
</html>

