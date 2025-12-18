@extends('layouts.gentelella')

@section('bread-crumbs')

@endsection

@section('css')

<!-- <link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css" />
<link rel="stylesheet" href="{{asset('/theme')}}/css/jquery.gritter.min.css" /> -->

<!-- Datatables -->
<link href="{{asset('/gentelella')}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
            <div class="col-xs-12 col-sm-12">
                <div class="form-horizontal">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-16">
                            
                            @if (Auth::user()->contact->doff_account_data->charge_account != null)
                                <li class="{{ Auth::user()->contact->doff_account_data->charge_account != null ? 'active' : ''}}">
                                    <a data-toggle="tab" href="#soa" id="a_soa" >
                                        <i class="red ace-icon fa fa-file-text-o bigger-125"></i>
                                        Statement of Accounts
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->contact->doff_account_data->specialrate_template_id != null)    
                                
                                <li class="{{ Auth::user()->contact->doff_account_data->charge_account == null ? 'active' : ''}}">
                                    <a data-toggle="tab" href="#track_trace" id="a_track_trace">
                                        <i class="blue ace-icon fa fa-map-marker bigger-125"></i>
                                        Track & Trace
                                    </a>
                                </li>
                                
                            
                                <li>
                                    <a data-toggle="tab" href="#pod_transmittal" id="a_pod_transmittal">
                                        <i class="green ace-icon fa fa-cloud-upload bigger-125"></i>
                                    POD Transmittal
                                    </a>
                                </li>
                            @endif
                            

                        </ul>

                        <div class="tab-content profile-edit-tab-content">
                            @if (Auth::user()->contact->doff_account_data->charge_account != null)
                                <div id="soa" class="tab-pane {{Auth::user()->contact->doff_account_data->charge_account != null && Auth::user()->contact->doff_account_data->specialrate_template_id != null ? 'in active' : ''}}">
                                
                                    <table class="table table-striped">
                                        <tr>
                                        
                                        <!--th id="th_cl">Credit Limit: &#8369; number_format(Auth::user()->contact->doff_account_data->charge_account->creditlimit,2,'.',',')</th-->
                                        <th id="th_unbilled">Unbilled: &#8369; <font id="unbilled"></font></th>
                                        <th id="th_unpaid">Unpaid: &#8369; <font id="unpaid"></font></th>
                                        <th id="th_bal">Total Outstanding Balance: &#8369; <font id="outstanding_balance"></font></th>
                                        
                                        </tr>
                                        
                                        
                                    </table>
                                    <select style="width:25%" class="form-control" id="tcode_status" name="tcode_status">
                                        
                                        <!--option value="0">ALL</option-->
                                        <option value="1">UNBILLED</option>
                                        <option selected value="2">UNPAID</option>
                                    </select>
                                    <table id="table-soa" width="100%"  class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Waybill/SOA #</th>
                                                    <th>Total Amount</th>
                                                    <th>Balance</th>
                                                    <th>Due Date</th>
                                                </tr>
                                            </thead>
                                        </table>
                                </div>
                            @endif

                            @if (Auth::user()->contact->doff_account_data->specialrate_template_id != null)    
                                <div id="track_trace" class="tab-pane {{Auth::user()->contact->doff_account_data->charge_account == null && Auth::user()->contact->doff_account_data->specialrate_template_id != null ? 'in active' : ''}}">
                                    <br>
                                    <div class="form-group">	
                                        <div class="col-md-2"> 
                                           
                                            <select class="form-control" id="tt_month" name="tt_month" >
                                                <option  @if (date('m')=='01') {{ 'selected' }} @endif value='01'>January</option>
                                                <option  @if (date('m')=='02') {{ 'selected' }} @endif value='02'>February</option>
                                                <option  @if (date('m')=='03') {{ 'selected' }} @endif value='03'>March</option>
                                                <option  @if (date('m')=='04') {{ 'selected' }} @endif value='04'>April</option>
                                                <option  @if (date('m')=='05') {{ 'selected' }} @endif value='05'>May</option>
                                                <option  @if (date('m')=='06') {{ 'selected' }} @endif value='06'>June</option>
                                                <option  @if (date('m')=='07') {{ 'selected' }} @endif value='07'>July</option>
                                                <option  @if (date('m')=='08') {{ 'selected' }} @endif value='08'>August</option>
                                                <option  @if (date('m')=='09') {{ 'selected' }} @endif value='09'>September</option>
                                                <option  @if (date('m')=='10') {{ 'selected' }} @endif value='10'>October</option>
                                                <option  @if (date('m')=='11') {{ 'selected' }} @endif value='11'>November</option>
                                                <option  @if (date('m')=='12') {{ 'selected' }} @endif value='12'>December</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"> 
                                            <select class="form-control" id="tt_year" name="tt_year">
                                                    @php
                                                    $from=2016;
													$to=date('Y');
                                                    @endphp
													@while($to >= $from)
                                                        <option value="{{$to}}">{{$to}}</option>
                                                        
                                                        @php $to--; @endphp
                                                    @endwhile
													
                                            </select>
                                          </p>
                                            <!--input max=" date('Y-m') " class="form-control col-lg-12 col-lg-12" value=" date('Y-m') " name="tt_month" id="tt_month"  type="month"-->
                                        </div>
                                        <div class="col-md-3"> 
                                        <select class="form-control" id="tt_status" name="tt_status">
                                            <option value="0">ALL</option>
                                            <option value="1">ACCEPTED</option>
                                            <option value="2">RECEIVED</option>
                                            <option value="3">CLAIMED</option>
                                            <option value="4">WITH POD TRANSMITTAL</option>
                                        </select>
                                         </div>
                                        <div class="col-md-4 pull-right"> 
                                            <form id="form-track-and-trace">
                                                @csrf
                                                <div class="input-group col-md-12"> 
                                                    <input required class="form-control col-lg-12 col-lg-12" name="tt_no" id="tt_no" placeholder="Waybill/Tracking/Transaction/Reference No." type="text">
                                                    <span class="input-group-btn">
                                                        <button type="submit" id="search"  style="height:35px;" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i></button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <table id="tbl-track-and-trace" width="100%" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Waybill #</th>
                                                <th>Consignee</th>
                                                <th>Reference/s</th>
                                                <th width="25%">Current Status </th>
                                            </tr>
                                        </thead>
                                    </table>

                                    <div class="modal fade tt_details"  role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title"  >TRACK AND TRACE</h4>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    
                                                    <div class="form-group">	
                                                        
                                                        <div class="col-md-12"> 
                                                            <table class="table table-striped table-bordered" width="100%" id="tbl-track-and-trace-details">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="25%">Date & Time</th>
                                                                        <th>Status of Item / Location</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>											
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div id="pod_transmittal" class="tab-pane">
                                    <br> 
                                    <div class="form-group">
                                        <div class="col-md-2"> 
                                           
                                            <select class="form-control" id="pod_month" name="pod_month">
                                                <option  @if (date('m')=='01') {{ 'selected' }} @endif value='01'>January</option>
                                                <option  @if (date('m')=='02') {{ 'selected' }} @endif value='02'>February</option>
                                                <option  @if (date('m')=='03') {{ 'selected' }} @endif value='03'>March</option>
                                                <option  @if (date('m')=='04') {{ 'selected' }} @endif value='04'>April</option>
                                                <option  @if (date('m')=='05') {{ 'selected' }} @endif value='05'>May</option>
                                                <option  @if (date('m')=='06') {{ 'selected' }} @endif value='06'>June</option>
                                                <option  @if (date('m')=='07') {{ 'selected' }} @endif value='07'>July</option>
                                                <option  @if (date('m')=='08') {{ 'selected' }} @endif value='08'>August</option>
                                                <option  @if (date('m')=='09') {{ 'selected' }} @endif value='09'>September</option>
                                                <option  @if (date('m')=='10') {{ 'selected' }} @endif value='10'>October</option>
                                                <option  @if (date('m')=='11') {{ 'selected' }} @endif value='11'>November</option>
                                                <option  @if (date('m')=='12') {{ 'selected' }} @endif value='12'>December</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"> 
                                            <select class="form-control" id="pod_year" name="pod_year">
                                                    @php
                                                    $from=2016;
													$to=date('Y');
                                                    @endphp
													@while($to >= $from)
                                                        <option value="{{$to}}">{{$to}}</option>
                                                        
                                                        @php $to--; @endphp
                                                    @endwhile
													
                                            </select>
                                          </p>
                                        </div>	
                                        <!--div class="col-md-3"> 
                                            <input max=" date('Y-m') " class="form-control col-lg-12 col-lg-12" value=" date('Y-m') " name="pod_month" id="pod_month"  type="month">
                                        </div-->
                                    </div>
                                    <table id="tbl-pod" width="100%"  class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>POD Transmittal #</th>
                                                <th>Tracking #</th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>

                                    </table>

                                    <div class="modal fade pod_details"  role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title" >POD TRANSMITTAL DETAILS</h4>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    
                                                    <div class="form-group">	
                                                        
                                                        <div class="col-md-12"> 
                                                            <table id="tbl-pod-transmital-details" width="100%" class="table table-striped table-bordered">
                                                                <thead>    
                                                                    <tr>
                                                                        <th width="10%">DATE</th>
                                                                        <th>WAYBILL</th>
                                                                        <th>CONSIGNEE</th>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody>
                                                                </tbody>											
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                        
                            
                                                        
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade pod_details_upload"  role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title" >POD TRANSMITTAL UPLOAD</h4>
                                                </div>
                                                
                                                <div class="modal-body">
                                                    
                                                    <div class="form-group">	
                                                        
                                                        <div class="col-md-12"> 
                                                            <table id="tbl-pod-upload" class="table table-striped table-bordered">
                                                                <tbody>
                                                                </tbody>											
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                        
                            
                                                        
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>

            </div>
        
         

            
        </div>

    <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('plugins')
<!-- page specific plugin scripts -->
<!-- <script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>

<script src="{{asset('/theme/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('/theme/js/chosen.jquery.min.js')}}"></script>

<script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script> -->
<!-- <script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script> -->
<!-- <script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>
<script src="{{asset('/theme/js/sum.js')}}"></script> -->

<script src="{{asset('/gentelella')}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/jszip/dist/jszip.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="{{asset('/gentelella')}}/vendors/pdfmake/build/vfs_fonts.js"></script>
<script>
   // $(document).ready( function(){
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [month, day, year].join('/');
        }
        function get_transaction(){
            var balance=0;
            var unbilled=0;
            var unpaid=0;
            var tblSOA = $('#table-soa').DataTable({
                ajax: "{{url('/doff-transactions-data')}}",
                processing: true,
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bFilter : false,
                paging : false,
                bLengthChange: false,
                bInfo : false,
                order: [[ 1, "desc" ]],
                columns: [
                    {data: null,render(data,type){
                        return data.ttype == 'CH' ? data.tdate : data.soa_from+' - '+data.soa_to;
                    } },
                    {data : null,render(data,type){
                        return data.ttype == 'CH' ? data.waybill_no : data.soa_no;
                    }},
                    {data : 'total_amount',render(data,type){
                        return data.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }},
                    {data : 'balance',render(data,type){
                        return data.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        //return data;
                    }},
                    {data : 'soa_duedate'}
                ],
                initComplete: function(){
                   
                    $('#table-soa > tbody > tr').each(function(){
                    
                        $tr = $(this);
                        var data = tblSOA.row($tr).data();
                        if(data['ttype']=='CH' && data['balance'] > 0){
                            unbilled=unbilled+data['balance'];
                        }
                        else if(data['ttype']=='AR' && data['balance'] > 0){
                            unpaid=unpaid+data['balance'];
                        }
                        if(Number($("#tcode_status").val())==1 ){
                            if( Number(data['balance'].toFixed(2)) > 0 && data['ttype']=='CH' ){

                            }else{
                                $tr.remove();
                            }
                            
                        }
                        else if(Number($("#tcode_status").val())==2 ){
                            if( Number(data['balance'].toFixed(2)) > 0 && data['ttype']=='AR' ){

                            }else{
                                $tr.remove();
                            }
                            
                        }

                        // if( data['balance'] <= 0  ){
                        //     $tr.remove();
                        // }
                    
                    });
                    $('#unbilled').html(unbilled.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#unpaid').html(unpaid.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#outstanding_balance').html((unpaid+unbilled).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                }
            });
            
        }
        get_transaction();
        //$('#outstanding_balance').html();
        // tblSOA.column( 2 ).data().sum().toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        
        $('#tcode_status').on('change',function(e){
                $('#table-soa').DataTable().destroy();
                get_transaction();
            
        });
        $('#a_soa').on('click',function(e){
                $('#tcode_status').val(2);
                $('#table-soa').DataTable().destroy();
                get_transaction();
            
        });


        function fill_datatable_track_and_trace(filter_tt_month='',filter_tt_no='',filter_tt_status=0){
            var tblTrackAndTrace = $('#tbl-track-and-trace').DataTable({
                ajax: {
                    url : "{{ url('track-and-trace-data') }}",
                    type: "GET",
                    data: {
                        tt_month : filter_tt_month,
                        tt_no : filter_tt_no,
                        tt_status : filter_tt_status
                    },
                },
                processing: true,
                // serverSide : true,
                rowId: "transactioncode",
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bFilter : false,
                paging : false,
                bLengthChange: false,
                bInfo : false,
                order: [[ 0, "desc" ]],
                columns: [
                    {data : 'transactiondate',render(data,type){
                        return formatDate(data);
                    }},
                    {data : 'waybill_no'},
                    {data : 'consignee.fileas'},
                    {data : 'waybill_reference',render(data,type){
                        var text = '';
                        for(var i=0; i<data.length; i++){
                            text += (i>0 ? ',' : '')+data[i]['reference_no'];
                        }
                        return text;
                    }},
                    {
                        data : 'track_trace', render(data,type){
                            return data.length>0 ? data[0]['remarks'] : '';                            
                          
                        }
                    }
                ],
                initComplete: function(){
                    $('#tbl-track-and-trace > tbody > tr').each(function(){
                        $tr = $(this);
                        var data = tblTrackAndTrace.row($tr).data()['track_trace'];
                        if(Number($('#tt_status').val())==1){
                            if( (data[0]['remarks']).indexOf('ACCEPTED') < 0 )
                                 $tr.remove();
                        }
                        else if(Number($('#tt_status').val())==2){
                            if( (data[0]['remarks']).indexOf('RECEIVED') < 0 )
                                 $tr.remove();
                        }
                        else if(Number($('#tt_status').val())==3){
                            if( (data[0]['remarks']).indexOf('CLAIMED') < 0 )
                                 $tr.remove();
                        }
                        else if(Number($('#tt_status').val())==4){
                            if( (data[0]['remarks']).indexOf('POD TRANSMITTAL') < 0 )
                                 $tr.remove();
                        }
                        else if(Number($('#tt_status').val())==0){
                            for(var i=0; i<data.length;i++){
                                if( (data[i]['remarks']).indexOf('CLAIMED') > -1 )
                                    $tr.attr('style','background-color:#EAFAF1');
                            }
                        }
                        
                    });
                }
            });

            tblTrackAndTrace.on('click', 'tr', function() {
                $tr = $(this).closest('tr');
                var data = tblTrackAndTrace.row($tr).data();
                $('#tbl-track-and-trace-details tbody').empty();
                var track_trace = data.track_trace;
                for(var i=0; i<track_trace.length; i++){
                    $('#tbl-track-and-trace-details tbody').append(
                        '<tr>'+
                        '<td>'+track_trace[i]['track_trace_date']+'</td>'+
                        '<td>'+track_trace[i]['remarks']+'</td>'+
                        '</tr>'
                    );
                }
                $('.tt_details').modal('show');
            });
        }

        

        function fill_datatable_pod(filter_tt_month=""){
            var tblPOD = $('#tbl-pod').DataTable({
                ajax: {
                    url : "{{ url('pod-data') }}",
                    type: "GET",
                    data: {
                        tt_month : filter_tt_month
                    },
                },
                processing: true,
                // serverSide : true,
                rowId: "pod_transmittal_no",
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bFilter : false,
                paging : false,
                bLengthChange: false,
                bInfo : false,
                order: [[ 0, "desc" ]],
                "columnDefs": [
                    { className: "pod-transmital", "targets": [ 1 ] },
                    { className: "track-and-trace-attachments", "targets": [ 2 ] }
                ],
                columns: [
                    {data : 'pod_date'},
                    {data : 'pod_transmittal_no'},
                    {data : null,render(data,type){
                        return data['tracking_no']+'<br>'+formatDate(data['pod_date']);
                    }} 
                ]
            });

            tblPOD.on('click', '.pod-transmital', function() {
                $tr = $(this).closest('tr');
                var data = tblPOD.row($tr).data();
                $('#tbl-pod-transmital-details tbody').empty();
                var obj_details = data.pod_transmittal_details;
                var transactioncode='';
                for(var j =0 ; j<obj_details.length;j++){
                
                if(transactioncode != obj_details[j]['waybill_reference_attachment']['waybill']['transactioncode'] ){
                
                    $('#tbl-pod-transmital-details tbody').append('<tr>' +
                    '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['transactiondate']+'</td>' +
                    '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['waybill_no']+'</td>' +
                    '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['consignee']['fileas']+'</td>' +
                    '</tr>');

                }

                $('#tbl-pod-transmital-details tbody').append('<tr>' +
                    '<td><li class="fa fa-chevron-right pull-right" style="color:black;"></li></td>'+
                    '<td colspan="2">'+obj_details[j]['waybill_reference_attachment']['specialrate_reference_attachment']['specialrate_reference_attachment_desc']+'- '+obj_details[j]['waybill_reference_attachment']['reference_no']+'</td>' +
                    '</tr>');

                transactioncode=obj_details[j]['waybill_reference_attachment']['waybill']['transactioncode'];

                }
                
                $('.pod_details').modal('show');
            });

            tblPOD.on('click', '.track-and-trace-attachments', function() {
                $tr = $(this).closest('tr');
                var data = tblPOD.row($tr).data();
                $('#tbl-pod-upload > tbody').empty();
                var obj_details = data['pod_transmittal_upload'];
                for(var j =0 ; j<obj_details.length;j++){
                $('#tbl-pod-upload > tbody').append('<tr>' +
                '<td><image width="100%" height="300px" src="'+obj_details[j]['file_name']+'" /></td>' +
                '</tr>');
                }
                $('.pod_details_upload').modal('show'); 
                
            });
            
        }
        
        $('#pod_month').on('change',function(e){
            var filter_tt_month = $("#pod_year").val()+'-'+(e.target.value);
            if(filter_tt_month!=''){
                $('#tbl-pod').DataTable().destroy();
                fill_datatable_pod(filter_tt_month);
            }
        });
        $('#pod_year').on('change',function(e){
            var filter_tt_month = (e.target.value)+'-'+$("#pod_month").val();
            if(filter_tt_month!=''){
                $('#tbl-pod').DataTable().destroy();
                fill_datatable_pod(filter_tt_month);
            }
        });
        $('#a_pod_transmittal').on('click',function(e){
            $('#pod_year').val({{date('Y')}});
            $('#pod_month').val("{{date('m')}}");
            $('#tbl-pod').DataTable().destroy();
            fill_datatable_pod("{{date('Y-m')}}");
        });
        

        $('#tt_month').on('change',function(e){
            var filter_tt_month = $('#tt_year').val()+'-'+$('#tt_month').val();
            var filter_tt_status = $('#tt_status').val();  
            var filter_tt_no ='';
            if(filter_tt_month!=''){
                $('#tbl-track-and-trace').DataTable().destroy();
                fill_datatable_track_and_trace(filter_tt_month,filter_tt_no,filter_tt_status);
            }
        });
        $('#a_track_trace').on('click',function(e){
            $('#tt_status').val(0);
            $('#tt_year').val({{date('Y')}});
            $('#tt_month').val("{{date('m')}}");
            $('#tbl-track-and-trace').DataTable().destroy();
            fill_datatable_track_and_trace("{{date('Y-m')}}","",0)
        });
        

        $('#tt_year').on('change',function(e){
            var filter_tt_month = $('#tt_year').val()+'-'+$('#tt_month').val();
            var filter_tt_status = $('#tt_status').val();  
            var filter_tt_no ='';
            if(filter_tt_month!=''){
                $('#tbl-track-and-trace').DataTable().destroy();
                fill_datatable_track_and_trace(filter_tt_month,filter_tt_no,filter_tt_status);
            }
        });
        $('#tt_status').on('change',function(e){
            var filter_tt_month = $('#tt_year').val()+'-'+$('#tt_month').val();
            var filter_tt_status = $('#tt_status').val();  
            var filter_tt_no ='';
            if(filter_tt_month!=''){
                $('#tbl-track-and-trace').DataTable().destroy();
                fill_datatable_track_and_trace(filter_tt_month,filter_tt_no,filter_tt_status);
            }
        });

        $('#form-track-and-trace').on('submit', function () {
            var filter_tt_month = $('#tt_year').val()+'-'+$('#tt_month').val();
            var filter_tt_status = $('#tt_status').val();  
            var filter_tt_no =$('#tt_no').val();
            if(filter_tt_no!=''){
                $('#tbl-track-and-trace').DataTable().destroy();
                fill_datatable_track_and_trace(filter_tt_month,filter_tt_no,filter_tt_status);
            }
            return false;
        });
   //});
    

    

    // function tab_func(val){
    //     if(val==2){
    //       $('#tt_month').trigger('change');
    //     }
    //     else if(val==3){
    //       $('#pod_month').trigger('change');
    //     }  
    // }
    
</script>
@endsection

