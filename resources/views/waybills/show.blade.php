@extends('layouts.gentelella')

@section('bread-crumbs')

@endsection

@section('content')

@include('waybills.print')
<script src="{{asset('/js/qrcode.js')}}"></script>
<script>
new QRCode(document.getElementById('qr_code'),{text: "{{$data->reference_no}}",width:60,height:60}); 
</script>
@endsection



