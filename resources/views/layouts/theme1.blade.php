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
		<script src="{{asset('theme/js/ace-extra.min.js')}}"></script>
	</head>

	<body class="no-skin">
		@yield('content')
		<script src="{{asset('theme/js/jquery-2.1.4.min.js')}}"></script>
		@yield('plugins')
		<script src="{{asset('theme/js/ace-elements.min.js')}}"></script>
		<script src="{{asset('theme/js/ace.min.js')}}"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		
		<script src="{{asset('/js/aes.js')}}"></script>
		<script src="{{asset('/js/script.js')}}"></script>
		@yield('scripts')
	</body>

</html>

