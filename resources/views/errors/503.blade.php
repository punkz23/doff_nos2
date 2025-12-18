


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{asset('/images/icon.png')}}" rel="icon">
		<meta name="csrf-token" content="{{csrf_token()}}">
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

		<!-- ace settings handler -->
		<script src="{{asset('theme/js/ace-extra.min.js')}}"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="{{asset('theme/js/html5shiv.min.js')}}"></script>
		<script src="{{asset('theme/js/respond.min.js')}}"></script>
		<![endif]-->

		
	</head>

	<body class="no-skin">

		<br><br><br><br><br>
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						<!-- SETTING -->

						<!-- SETTING -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->

									<div class="error-container">
										<div class="">
											
											<h1 class="lighter smaller center">
												<span class="blue bigger-125">
													<img src="{{asset('/images/ICON.png')}}" width="40px" height="40px" alt="logo"/>
													<b>DOFF Online</b>
												</span>
												
											</h1>
											<hr />
											
											
											<h2 class="lighter smaller center">
												<i class="ace-icon fa fa-wrench icon-animated-wrench bigger-125"></i>
												Under Maintenance
											</h2>

											<div class="space"></div>


											<hr />
											<div class="space"></div>

											
										</div>
									</div>

									<!-- PAGE CONTENT ENDS -->
									</div><!-- /.col -->
								</div><!-- /.row -->
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
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
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
		<!-- ace scripts -->
		<script src="{{asset('theme/js/ace-elements.min.js')}}"></script>
		<script src="{{asset('theme/js/ace.min.js')}}"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<!-- inline scripts related to this page -->
		
	</body>
</html>






