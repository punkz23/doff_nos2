@extends('layouts.theme')

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>

        
        <li class="active">Special Account</li>
    </ul>
</div>
@endsection

@section('content')
<div class="page-header">
   
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
            <div class="clearfix">
              

                <div class="pull-right">
                    
                </div>

            </div>

            

            <div>
                <div id="user-profile-1" class="user-profile row">
                    

                    <div class="col-xs-12 col-sm-12">
                        <div class="form-horizontal">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-16">
                                    @role('Client')
                                    @if (Auth::user()->contact->doff_account_data->charge_account != null)
                                        <li class="{{ Auth::user()->contact->doff_account_data->charge_account != null ? 'active' : ''}}">
                                            <a data-toggle="tab" href="#soa">
                                                <i class="red ace-icon fa fa-file-text-o bigger-125"></i>
                                                Statement of Accounts
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->contact->doff_account_data->specialrate_template_id != null)    
                                      
                                        <li class="{{ Auth::user()->contact->doff_account_data->charge_account == null ? 'active' : ''}}">
                                            <a data-toggle="tab" href="#track_trace" onclick="tab_func(2)">
                                                <i class="blue ace-icon fa fa-map-marker bigger-125"></i>
                                                Track & Trace
                                            </a>
                                        </li>
                                        
                                    
                                        <li>
                                            <a data-toggle="tab" href="#pod_transmittal" onclick="tab_func(3)">
                                                <i class="green ace-icon fa fa-cloud-upload bigger-125"></i>
                                            POD Transmittal
                                            </a>
                                        </li>
                                    @endif
                                    @endrole

                                </ul>

                                <div class="tab-content profile-edit-tab-content">
                                    @role('Client')
                                    <div id="soa" class="tab-pane @role('Client') in active @endrole">
                                        <div class="box">
                                            <div class="box-body">
                                              <table class="table table-striped">
                                                <tr>
                                                  
                                                  <th id="th_cl">Credit Limit: &#8369; {{number_format(Auth::user()->contact->doff_account_data->charge_account->creditlimit,2,'.',',')}}</th>
                                                  <th id="th_bal">Outstanding Balance: &#8369; <font id="outstanding_balance"></font></th>
                                                 
                                                </tr>
                                                
                                                
                                              </table>
                                            </div>
                                        </div>
                                        <div class="box">
                                            
                                            <table id="table-soa"  class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SOA #</th>
                                                        <th>Total Amount</th>
                                                        <th>Balance</th>
                                                        <th>Due Date</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                    </div>
                                    </div>
                                    

                                    <div id="track_trace" class="tab-pane @role('Admin') in active @endrole">
                                        <br>
                                        <div class="form-group">	
                                            <div class="col-md-3"> 
                                                
                                                <input max="{{ date('Y-m') }}" class="form-control col-lg-12 col-lg-12" value="{{ date('Y-m') }}" name="tt_month" id="tt_month"  type="month">
                                            </div>
                                            <div class="col-md-4"> 
                                                <form id="tt_form">
                                                    @csrf
                                                    <div class="input-group col-md-12"> 
                                                        
                                                            <input required class="form-control col-lg-12 col-lg-12" name="tt_no" id="tt_no" placeholder="Waybill/Tracking/Transaction/Reference No." type="text">
                                                            <span class="input-group-btn">
                                                                <button type="submit" id="search"   class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i></button>
                                                            </span>
                                                    
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                       
                                        <table  class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Waybill #</th>
                                                    <th>Consignee</th>
                                                    <th>Reference/s</th>
                                                    <th width="25%">Current Status</th>
                                                </tr>
                                                <tr id="tbl_tt_loading" hidden>
                                                    <td colspan="5" align="center"><i class="ace-icon fa fa-spinner fa-spin blue bigger-250"></i></td>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tbl_tt">
                                               
                                            </tbody>

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
                                                                <table class="table table-striped table-bordered">
                                                                    <tr>
                                                                        
                                                                        <th width="25%">Date & Time</th>
                                                                        <th>Status of Item / Location</th>
                                                                    </tr>
                                                                    <tr id="tbl_tt_details_loading" hidden>
                                                                        <td colspan="2" align="center"><i class="ace-icon fa fa-spinner fa-spin blue bigger-250"></i></td>
                                                                    </tr>
                                                                    <tbody id="tbl_tt_details">
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
                                            <div class="col-md-3"> 
                                                
                                                <input max="{{ date('Y-m') }}" class="form-control col-lg-12 col-lg-12" value="{{ date('Y-m') }}" name="pod_month" id="pod_month"  type="month">
                                            </div>
                                        </div>
                                        <table  class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>POD Transmittal #</th>
                                                    <th>Tracking #</th>
                                                </tr>
                                                <tr id="tbl_pod_loading" hidden>
                                                    <td colspan="3" align="center"><i class="ace-icon fa fa-spinner fa-spin blue bigger-250"></i></td>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_pod">
                                                
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
                                                                <table class="table table-striped table-bordered">
                                                                    <tr>
                                                                        
                                                                        <th width="10%">DATE</th>
                                                                        <th>WAYBILL</th>
                                                                        <th>CONSIGNEE</th>
                                                                    </tr>
                                                                    <tr id="tbl_pod_details_loading" hidden>
                                                                        <td colspan="3" align="center"><i class="ace-icon fa fa-spinner fa-spin blue bigger-250"></i></td>
                                                                    </tr>
                                                                    <tbody id="tbl_pod_details">
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
                                                                <table class="table table-striped table-bordered">
                                                                    
                                                                    <tr id="tbl_pod_upload_loading" hidden>
                                                                        <td align="center"><i class="ace-icon fa fa-spinner fa-spin blue bigger-250"></i></td>
                                                                    </tr>
                                                                    <tbody id="tbl_pod_upload">
                                                                    </tbody>											
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                            
                                
                                                            
                                                        
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    @endrole
                                </div>
                            </div>
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
<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
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
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script>
<!-- <script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script> -->
<script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.21/api/sum().js"></script>
<script>
    $(document).ready( function(){
        
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
            order: [[ 2, "desc" ]],
            columns: [
                {data : 'soa_no'},
                {data : 'total_amount',render(data,type){
                    return data.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }},
                {data : 'balance',render(data,type){
                    return data.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }},
                {data : 'soa_duedate'}
            ]
        });

        $('#outstanding_balance').html(tblSOA.column( 2 ).data().sum().toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        var tblTrackAndTrace = $('#tbl-track-and-trace').DataTable({
            
        });

        $('#pod_month').on('change',function(e){
            $("#tbl_pod").html('');
            $("#tbl_pod_loading").show();  
            $.getJSON("{{ url('pod-data') }}/"+e.target.value, function(result){
                var obj = result;
                r_length=Object.keys(result).length;
                if(r_length == 0){
                    $("#tbl_pod").append('<tr >'+
                        '<td colspan="3" align="center"><font color="red">NO RECORD FOUND</font></td>'+
                    '</tr>');
                }
                $.each(result, function(e) {
                  

                    //data-toggle="modal" data-target=".pod_details"
                    $("#tbl_pod").append('<tr >'+
                        '<td>'+this.pod_date+'</td>'+
                        '<td class="pod_data" data-details=\''+JSON.stringify(obj[e]['pod_transmittal_details'])+'\' >'+this.pod_transmittal_no+'</td>'+
                        '<td class="pod_upload" data-upload=\''+JSON.stringify(obj[e]['pod_transmittal_upload'])+'\'>'+this.tracking_no+'<br><small>'+this.date_sent+'</small></td>'+
                    '</tr>');

                    $('.pod_data').on('click',function(e){
                        
                        $('.pod_details').modal('show');   
                        $("#tbl_pod_details").html('');
                        $("#tbl_pod_details_loading").show();
                        var obj_details = JSON.parse(e.currentTarget.dataset.details);
                        
                        var transactioncode='';

                        for(var j =0 ; j<obj_details.length;j++){

                            if(transactioncode != obj_details[j]['waybill_reference_attachment']['waybill']['transactioncode'] ){
                               
                                $("#tbl_pod_details").append('<tr>' +
                                '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['transactiondate']+'</td>' +
                                '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['waybill_no']+'</td>' +
                                '<td>'+obj_details[j]['waybill_reference_attachment']['waybill']['consignee']['fileas']+'</td>' +
                                '</tr>');

                            }

                            $("#tbl_pod_details").append('<tr>' +
                                '<td><li class="fa fa-chevron-right pull-right" style="color:black;"></li></td>'+
                                '<td colspan="2">'+obj_details[j]['waybill_reference_attachment']['specialrate_reference_attachment']['specialrate_reference_attachment_desc']+'- '+obj_details[j]['waybill_reference_attachment']['reference_no']+'</td>' +
                                '</tr>');

                            transactioncode=obj_details[j]['waybill_reference_attachment']['waybill']['transactioncode'];
                        
                        }

                        $("#tbl_pod_details_loading").hide();

                    });

                    $('.pod_upload').on('click',function(e){
                        
                        $('.pod_details_upload').modal('show');   
                        $("#tbl_pod_upload").html('');
                        $("#tbl_pod_upload_loading").show();
                        var obj_details = JSON.parse(e.currentTarget.dataset.upload);
                        
                        console.log(e);
                        for(var j =0 ; j<obj_details.length;j++){

                            $("#tbl_pod_upload").append('<tr>' +
                            '<td><image width="100%" height="300px" src="'+obj_details[j]['file_name']+'" /></td>' +
                            '</tr>');

                        }

                        $("#tbl_pod_upload_loading").hide();

                    });


                });
                $("#tbl_pod_loading").hide();  
            });
            
        });

        $('#tt_month').on('change',function(e){

            var postData = e.target.value;
           
            var formData = new FormData();
            formData.append('tt_month',postData);
            formData.append('_token',"{{ csrf_token() }}");
            
            track_trace(formData);
        });

        $('#tt_form').on('submit', function () {
           
            track_trace(new FormData(this));
            return false;
        });
    });
    function track_trace(formData){

        $("#tbl_tt").empty();
        $("#tbl_tt_loading").show();
        $.ajax({
        url: "{{ url('track-trace-data') }}", 
        type: "POST",   
        dataType: 'JSON',          
        data: formData,
        contentType: false,       
        cache: false,             
        processData:false,        
        success: function(data)  
        {
            //console.log(data);
            var obj = data;
            if(obj.length ==0){
                $("#tbl_tt").append('<tr >'+
                    '<td colspan="5" align="center"><font color="red">NO RECORD FOUND</font></td>'+
                '</tr>');
            }else{
                for(var i =0 ; i<obj.length;i++){
                    var text='';

                    var obj2= obj[i]['waybill_reference'];
                    for(var j =0 ; j<obj2.length;j++){
                        
                        text +=(j>0 ? ', ' : '')+obj2[j]['reference_no'];
                    }
                    //alert(JSON.stringify(obj[i]));
                    $("#tbl_tt").append(' <tr class="row_data" data-logs=\''+JSON.stringify(obj[i]['track_trace'])+'\'>' +
                    '<td>'+obj[i]['transactiondate']+'</td>' +
                    '<td>'+obj[i]['waybill_no']+'</td>' +
                    '<td>'+obj[i]['consignee']['fileas']+'</td>' +
                    '<td>'+text+'</td>' +
                    '<td>'+obj[i]['track_trace'][0]['remarks']+'</td>' +
                    '</tr>');

                    $('.row_data').on('click',function(e){
                        $('.tt_details').modal('show');   
                        $("#tbl_tt_details").html('');
                        $("#tbl_tt_details_loading").show();
                        var obj_details = JSON.parse(e.currentTarget.dataset.logs);
                        
                        for(var j =0 ; j<obj_details.length;j++){

                            date_text=new Date(obj_details[j]['track_trace_date']);

                            $("#tbl_tt_details").append('<tr>' +
                            '<td>'+(date_text).toLocaleString()+'</td>' +
                            '<td>'+obj_details[j]['remarks']+'</td>' +
                            '</tr>');
                        
                        }

                        $("#tbl_tt_details_loading").hide();

                    });

                }
            }
            $("#tbl_tt_loading").hide();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
        }
        });						

    }

    

    function tab_func(val){
        if(val==2){
          $('#tt_month').trigger('change');
        }
        else if(val==3){
          $('#pod_month').trigger('change');
        }
        
    }
    
</script>
@endsection

