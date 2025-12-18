@extends('layouts.theme')

@section('css')
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css" />

@endsection

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>

        <li>
            <a class="active" href="{{route('soa')}}">Statement of Accounts</a>
        </li>
        
    </ul><!-- /.breadcrumb -->
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div>
            <table id="soa-table" class="display dataTable">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>
                            <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                            Shipper
                        </th>
                        <th>
                            <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                            Consignee
                        </th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('plugins')
<script src="http://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    var myTable = $('#soa-table').DataTable( {
        bAutoWidth: false,
        bLengthChange: false,
        bInfo : false,
        responsive: true
    });
})
</script>
@endsection
