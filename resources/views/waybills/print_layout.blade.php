
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{asset('/images/icon.png')}}" rel="icon">

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{asset(env('APP_IMG'))}}" rel="icon">

		<!-- Bootstrap -->
		<link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		@yield('css')
		<!-- Custom Theme Style -->
		<link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
		<style>
			p, li, tr {
				font-size: 12px;
			}
		</style>
	</head>

	<body>
		
		@include('waybills.print')
		
		
		<script src="{{asset('/js/qrcode.js')}}"></script>
		<script>
		new QRCode(document.getElementById('qr_code'),{text: "{{$data->reference_no}}",width:60,height:60}); 
		</script>
	</body>
	
</html>

