@extends('layouts.gentelella')

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

@section('bread-crumbs')
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active">
					<a data-toggle="tab" href="#pending">
						<i class="green ace-icon fa fa-clock-o bigger-120"></i>
                        Pending
                        <span class="badge badge-warning pending-count">{{count($pending)}}</span>
					</a>
				</li>

				<li>
					<a data-toggle="tab" href="#transacted">
                        <i class="green ace-icon fa fa-files-o bigger-120"></i>
						Transacted
						<span class="badge badge-success badge-transacted-count">{{count($transacted)}}</span>
					</a>
				</li>
                <div class="booking-action-button pull-right">
                    <button class="btn btn-sm btn-primary search"><i class="ace-icon fa fa-search"></i> SEARCH</button>
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
                    <a href="{{route('waybills.create')}}" class="btn btn-sm btn-success add-booking"><i class="ace-icon fa fa-cart-plus"></i> CREATE</a>
                    @endif
                    <!--button class="btn btn-sm btn-danger delete-bookings"><i class="ace-icon fa fa-ban"></i> CANCEL BOOKING</button-->

                </div>
			</ul>

			<div class="tab-content">
				<div id="pending" class="tab-pane fade in active">
                    <br>
                    <table id="pending-table" style="table-layout: fixed;width:100%;" class="table table-striped table-bordered">
                        <thead>
                            <tr>

                                <th>Ref. #</th>
                                <th>Date</th>

                                <!--th>Type</th-->
                                <th >
                                    <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                                    Shipper
                                </th>
                                <th >
                                    <i class="ace-icon fa fa-clock-o bigger-110 hidden-480"></i>
                                    Consignee
                                </th>


                                <th ></th>
                                <!--th width="5%"></th-->
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
				</div>



				<div id="transacted" class="tab-pane fade">
                    <br>
                    <div class="clearfix">
                        <div class="pull-right tableTools-container-transacted"></div>
                    </div>
                    For the month of: <input type="month" id="month-filter-transacted" value="{{ date('Y-m') }}" max="{{ date('Y-m') }}" />
                    <button type="button" class="btn btn-sm btn-info month-filter-transacted-btn"><i class="fa fa-search"></i> </button>
                    <table id="transacted-table" style="table-layout: fixed;width:100%;"  class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Ref #</th>
                                <th>Date</th>
                                <!--th>Type</th-->
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



    </div>
</div>

<div class="modal fade" id="modal-qrcode" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-qrcode bigger-130"></i> QRCode</h4>
            </div>
            <div class="modal-body">
                <center>
                    <div class="qrcode">
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-search" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-search bigger-130"></i> FILTER DATE</h4>
            </div>
            <div class="modal-body">
                <form id="form-search" class="form-search">
                    <span class="input-icon">
                        <input type="date" placeholder="From" class="nav-search-input" id="nav-search-input" name="datefrom" autocomplete="off" />
                        -
                        <input type="date" placeholder="To" class="nav-search-input" id="nav-search-input" name="dateto" autocomplete="off" />
                        <button type="submit" class="btn btn-success btn-sm submit">Search</button>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade pasabox_cf_modal" role="dialog"   aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close pasabox_cf_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"> <img src="images/gcash-logo1.png" width="100"  /></h4>
			</div>
			<div class="modal-body">
                <form method="post" id="pasabox_cf_form"  onkeyPress="return !(event.keyCode==13)" autocomplete="off" >

                    <div class="form-group">
                        <div class="col-md-5">

                            <center><img id="gcash_qr"  width="300" width="300"  /></center>
                            <br>
                            <table width="100%">
                                <tr>

                                    <td align="center">
                                        <h4 id="h4_gcash_name"></h4>

                                    </td>
                                </tr>
                                <tr>

                                    <td align="center">
                                        Please Scan QR Code using Gcash App.

                                    </td>
                                </tr>

                            </table>


                        </div>
                        <div class="col-md-7">
                            <b><font size="3">Amount to be Paid: Php <input    readonly name="gcash_amount" id="gcash_amount" type="text" style="border-color:transparent;font-weight: bold;width:15%;"  > </font></b>
                            <i>*(does not include freight charges)</i>
                            <br>
                            <br>
                            *Pay now or Pay Later is part of Gcash advertisement, however our Pasabox transactions requires immediate payment of convenience fee.
                            <br>
                            <br>
                            <input  type="hidden" id="gcash_branch_aname"   name="gcash_branch_aname">
                            <input  type="hidden" id="gcash_branch_ano"   name="gcash_branch_ano">
                            <input  type="hidden" id="gcash_id"   name="gcash_id">
                            <input  type="hidden" id="gcash_pfee"   name="gcash_pfee">
                            <input  type="hidden" id="rp_online_booking_ref"   name="rp_online_booking_ref">
                            <input  type="hidden" id="pasabox_branch_receiver"   name="pasabox_branch_receiver">

                            <div class="form-group row" >
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <b>Payment Reference # <font color="red">*</font></b>
                                </div>
                                <div class="col-md-8 col-sm-12 col-xs-12">
                                    <input  required id="gcash_reference_no" type="text"  name="gcash_reference_no" class="form-control col-md-12 col-xs-12" >
                                </div>
                                <br><br>
                            </div>
                            <div class="form-group row" >
                                <div class="col-md-4 col-sm-12 col-xs-12"">
                                    <b>Payment Date <font color="red">*</font></b>
                                </div>
                                <div class="col-md-8 col-sm-12 col-xs-12"">
                                    <input required type="date" id="gcash_pdate"  name="gcash_pdate"  type="date"  value="{{  date('Y-m-d') }}"  max="{{  date('Y-m-d') }}" class="form-control col-md-12 col-xs-12" >
                                </div>
                                <br><br>
                            </div>
                            <div class="form-group row" >
                                <div class="col-md-4 col-sm-12 col-xs-12"">
                                    <b>Email <font color="red">*</font></b>
                                </div>
                                <div class="col-md-8 col-sm-12 col-xs-12"">
                                    <input  required value="{{ Auth::user()->contact->email }}" type="email" id="gcash_cemail" name="gcash_cemail"   class="form-control col-md-12 col-xs-12 email-address" >
                                </div>
                                <div class="col-md-12">
                                *A confirmation will be sent to your email once payment has been confirmed by our staff.
                                </div>
                            </div>
                            <div class="form-group" >

                                <div class="col-md-12">
                                    <br><h5><i class="fa fa-lightbulb-o"></i> Branch Receiver: </h5>
                                    <p class="pasabox_branch_receiver_emp"></p>
                                </div>
                            </div>
                            <div class="form-group" >

                                <div class="col-md-12">
                                    <table width="100%">
                                        <tr>
                                            <td colspan="2">
                                                <h5><i class="fa fa-lightbulb-o"></i> Note: </h5>

                                                <b>Security Reminders:</b>
                                                <br>1.	Ensure that your browser shows our verified URL <a href="https://dailyoverland.com" target="_blank">https://dailyoverland.com</a> or <a href="https://track.dailyoverland.com" target="_blank">https://track.dailyoverland.com</a></u>.
                                                <br><br>2.	Don’t click links from suspicious email, check the sender first. Our official email address is <u>booking@dailyoverland.com</u>.
                                                <br><br>3.	Be alert of PHISHING. Fraudsters use a fake website and email address to get your personal information and create fraudulent transactions.
                                                <br><br>4.	Match the account name shown above the QR Code with your Gcash screen before transferring payment.
                                                <br><br>5.	Never share messages you received from Daily Overland containing the unique URL.
                                                <br><br>6.	Our company will not be responsible for any payment made from suspicious sites.

                                                <br><br><b>Refund Policy:</b>
                                                <br><br>1.	Erroneous transactions shall follow a no refund policy if the erroneous transaction is proven to be due to customer error after investigation (e.g. wrong account details, wrong biller).
                                                <br><br>2.	Overpayment made to our account should be reported to us and Gcash Customer Support the same day payment was made. However, only FULL AMOUNT reversal is honored by Gcash. Thus if you wish to proceed with your transaction pending reversal, you will be required to re-transact and pay the correct amount.
                                                <br><br>3.  Convenience Fees are non-refundable.

                                            </td>

                                        </tr>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>

				<div class="modal-footer">
                    <br><br><br><br>
                    <button type="submit" class="btn btn-info btn-md">Submit</button>
				</div>

                </form>


			</div>
		</div>
	</div>
</div>

@endsection

@section('plugins')

<!-- <script src="{{asset('/js/js/wizard.min.js')}}"></script> -->
<!-- <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/theme')}}/js/jquery.gritter.min.js"></script> -->
<!-- page specific plugin scripts -->
<!-- <script src="{{asset('/js/jquery.dataTables.min.js')}}"></script> -->



<!-- <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script> -->

<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>

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
<script src="{{asset('/js/qrcode.js')}}"></script>

@endsection



@section('scripts')
@include('waybills.track_trace')
@include('waybills.pasabox_file')
@include('waybills.waybill_details_modal')
<script type="text/javascript">

    function get_gcash_rp_qr(ref_no,tpayment){
        $("#rp_online_booking_ref").val(ref_no);
        $.ajax({
            url: "{{url('/get-gcash-rp-qr')}}/"+ref_no,
            method: 'get',
            success: function(result){
                var result = JSON.parse(result);
                $.each(result,function(){

                    $("#h4_gcash_name").html('<b>'+this.gcash_account_name+'</b>');
                    $('#gcash_qr').attr('src',this.gcash_qr_code);

                    ap=this.pasabox_authorized_employee;
                    ap_fileas='';
                    if( ap != null && ap !='' ){
                        ap_fileas=ap['fileas'];
                    }

                    $(".pasabox_branch_receiver_emp").html(this.branchoffice_description+' BRANCH'+
                    '<br><i class="fa fa-user"></i> '+ap_fileas+
                    '<br><i class="fa fa-phone"></i> '+this.pasabox_incharge_employee_cno+
                    '<br><i class="fa fa-map-marker"></i> '+this.branch_address
                    );
                    $("#gcash_pfee").val(Number(this.pasabox_convinience_fee).toFixed(2));
                    $("#gcash_amount").val(Number(Number(this.pasabox_convinience_fee )- tpayment ).toFixed(2));
                    $("#gcash_branch_aname").val(this.gcash_account_name);
                    $("#gcash_branch_ano").val(this.gcash_account_no);
                    $("#pasabox_branch_receiver").val(this.branchoffice_no);

                });


        }});
        get_gcash_info();
    }

    function get_gcash_info(){
        $("#gcash_id").val('');
        $.ajax({
            url: "{{url('/get-gcash-info')}}",
            method: 'get',
            success: function(result){
                var result = JSON.parse(result);
                $.each(result,function(){
                    $("#gcash_id").val(this.bank_no);
                });


        }});
    }

    $('#pasabox_cf_form').on('submit',function(e){
        e.preventDefault();

        $.ajax({
            url: "/save-gcash-reposting-payment",
            type:"POST",
            data:{
            "_token": "{{ csrf_token() }}",
            gcash_branch_aname:$("#gcash_branch_aname").val(),
            gcash_branch_ano:$("#gcash_branch_ano").val(),
            gcash_id:$("#gcash_id").val(),
            gcash_pfee:$("#gcash_pfee").val(),
            gcash_amount:$("#gcash_amount").val(),
            rp_online_booking_ref:$("#rp_online_booking_ref").val(),
            gcash_reference_no:$("#gcash_reference_no").val(),
            gcash_pdate:$("#gcash_pdate").val(),
            gcash_cemail:$("#gcash_cemail").val(),
            pasabox_branch_receiver:$("#pasabox_branch_receiver").val(),
            },
            success:function(result){
                swal(result.message, {
                    icon: result.type,
                    title: result.title
                })
                .then(function(){
                    if(result.type=='success'){
                        $(".pasabox_cf_modal_close").click();
                        location.reload();
                   }

                });
            },
            error: function(result) {

            }
        });

    });

    jQuery(function($) {



        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [month, day, year].join('/');
        }
        //initiate dataTables plugin
        // var myTable = $('#pending-table').DataTable( {
        //     bAutoWidth: false,
        //     "aoColumns": [
        //             null, null,null, null,null,
        //         { "bSortable": false }
        //     ],
        //     "aaSorting": [],
        //     "bLengthChange": false,
        //     "bInfo" : false,
        //     select: {
        //         style: 'multi'
        //     },
        //     responsive: true
        // } );
        var myTable = $('#pending-table').DataTable( {
            ajax: {
                    url : "{{ url('/get-pendings') }}",
                    type: "GET",
                },
                processing: true,
                // serverSide : true,
                rowId: "reference_no",
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bLengthChange: false,
                bInfo : false,
                order: [[ 1, "desc" ]],
                select: {
                    style: 'multi'
                },
                columnDefs : [
                    //{ className: "text-center", "targets": [ 0 ] },
                    { className: "", "targets": [ 0 ] },
                    { className: "shipper-info", "targets": [ 3 ] },
                    { className: "consignee-info", "targets": [ 4 ] }
                ],
                columns: [
                    {data : null,render(data,type){
                        var className = data.payment_type == 'CI' ? 'label-success' : (data.payment_type == 'CD' ? 'label-warning' : 'label-primary');
                        var font_color = data.payment_type == 'CI' ? 'green' : (data.payment_type == 'CD' ? 'orange' : 'blue');
                        var text = data.payment_type == 'CI' ? 'PREPAID' : (data.payment_type == 'CD' ? 'COLLECT' : 'CHARGE');
                        if(data['pasabox']==1){
                            text =text+' PASABOX';
                        }
                        text_append='';
                        if( data['confirm_pickup_date'] !=null){
                            text_append += '<br><p>CONFIRMED PICK-UP DATE: <u>'+data['confirm_pickup_date']+'</u></p>';
                        }
                        if(data['booking_status']==2){
                            if(data['booking_status']==2){
                                @if( Auth::user()->contact->verified_account==1 )
                                    text_append += '<br><span class="label label-sm label-info">ON PROCESS</span>';
                                @endif
                            }
                        }
                        if( data['confirm_pickup_date'] !=null){
                            return '<div class="qrcode"></div><font color="'+font_color+'"><b><small>'+text+'</small></b></font><br><a href="#"><font size="2" style="font-weight:bold;">'+data.reference_no+'</font></a>'+text_append;
                            //<span class="label label-sm '+className+'"><small>'+text+'</small>
                        }else{
                            //if(data['pasabox']==1){
                                return '<div class="qrcode"></div><font color="'+font_color+'"><b><small>'+text+'</small></b></font><br><a href="#"><font size="2" style="font-weight:bold;">'+data.reference_no+'</font></a>'+text_append;
                                //<span class="label label-sm '+className+'"><small>'+text+'</small></span><
                            //}else{
                            //    return '<span class="label label-sm '+className+'">'+text+'</span><br><a href="'+"{{url('/waybills')}}/"+data.reference_no+'/edit"><font size="2" style="font-weight:bold;">'+data.reference_no+'</font></a>'+text_append;
                           // }
                        }
                    }},
                    {data : null,render(data,type){


                        append_text='';
                        if(data['pasabox']==1){
                            if( data['shipping_company'] != null){
                                append_text +='<br>Shipping Comp.: '+data['shipping_company']['fileas']+'<br><small><i class="fa fa-phone"></i> '+data['shipping_company']['contact_no']+'<br><i class="fa fa-envelope"></i> '+data['shipping_company']['email']+'</small>';
                            }

                            tt_recent='';
                            txt_append='';
                            ttr_recent_data=data['track_trace_recent'];
                            if(ttr_recent_data != null ){
                                tt_recent=ttr_recent_data['remarks'];
                                if(
                                    ttr_recent_data['obr_details_id'] ==3
                                    || ttr_recent_data['obr_details_id'] ==4
                                    || ttr_recent_data['obr_details_id'] ==5
                                    || ttr_recent_data['obr_details_id'] ==6
                                    || ttr_recent_data['obr_details_id'] ==7
                                    || ttr_recent_data['obr_details_id'] ==8

                                ){
                                    txt_append='<br><a  onclick="pasabox_details_file(\''+data['reference_no']+'\')"  data-toggle="modal" data-target=".pasabox_details_file_modal"   title="Uploaded Pictures"><i><u>Click here to view uploaded pictures</i></u></a>';
                                }
                            }

                            if(tt_recent !=''){
                                append_text += '<br><i class="fa fa-map-marker"></i> '+tt_recent.toLowerCase()+txt_append;
                            }
                        }
                        return formatDate(data['transactiondate'])+append_text;
                    }},
                    // {data: 'payment_type',render(data,type){
                    //     var className = data == 'CI' ? 'label-success' : (data == 'CD' ? 'label-warning' : 'label-primary');
                    //     var text = data == 'CI' ? 'PREPAID' : (data == 'CD' ? 'COLLECT' : 'CHARGE');
                    //     return '<span class="label label-sm '+className+'">'+text+'</span>';
                    // }},
                    {data: 'shipper',render(data,type){
                        return data['fileas']+'<br><small><i class="fa fa-phone"></i> '+data['contact_no']+'</small>';
                    }},
                    {data: 'consignee',render(data,type){
                        return data['fileas']+'<br><small><i class="fa fa-phone"></i> '+data['contact_no']+'</small>';
                    }},
                    {data:null,render(data,type){

                        var returnHTML = '';

                        returnHTML += '<a style="font-size:18px" class="blue" target="_blank" href="'+"{{url('/waybills')}}/"+data['reference_no']+'" title="Print"><i class="ace-icon fa fa-print bigger-240"></i></a>';

                        //if(data['booking_status']==0){

                            if( data['confirm_pickup_date'] !=null){

                            }
                           // else if(data['pasabox']==0){
                            else{
                                if(data['pasabox']==1){

                                    if(data['pasabox_received'] !=null){
                                        if( Number(data['pasabox_received']['pasabox_to_receive_status']) ==0){
                                            returnHTML += '&nbsp;<a style="font-size:18px" target="_blank" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>';
                                        }
                                    }else{
                                        returnHTML += '&nbsp;<a style="font-size:18px" target="_blank" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>';
                                    }
                                }
                                else{
                                    returnHTML += '&nbsp;<a style="font-size:18px" target="_blank" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>';
                                    returnHTML += '&nbsp;<a style="font-size:18px" class="red delete" href="#" title="Cancel" id="'+data['reference_no']+'"><i class="ace-icon fa fa-ban bigger-130"></i></a>';
                                }
                            }



                            //returnHTML += '<br><span class="label label-sm label-danger">Unsuccessful Convinience Fee Payment</span>';
                            //returnHTML += '<br><span data-toggle="modal" data-target=".pasabox_track_trace"  class="label label-sm label-info"><font color="black"><i class="fa fa-truck"></i> Shipment processed by Manila Branch.</font></span>';
                        //}
                        // else{

                        //     if(data['booking_status']==2){
                        //         @if( Auth::user()->contact->verified_account==1 )
                        //             returnHTML += '&nbsp;<span class="label label-sm label-info">ON PROCESS</span>';
                        //         @endif
                        //     }
                        // }
                        returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
                        if(data['pasabox']==1){
                            tpayment=0;
                            pasabox_cf=data['pasabox_cf'];
                            if(data['pasabox_cf_adv_payment'] !='' && data['pasabox_cf_adv_payment'] != null){
                                $.each(data['pasabox_cf_adv_payment'],function(){
                                    tpayment += Number(this.advancepayment);
                                });
                            }
                            else if(pasabox_cf !='' && pasabox_cf != null){

                                $.each(pasabox_cf,function(){
                                    online_payment=this.online_payment;
                                    tpayment += Number(online_payment['onlinepayment_amount']);
                                });
                            }

                            //returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';

                            if(data['pasabox_received'] !=null){
                                if( Number(data['pasabox_received']['pasabox_to_receive_status']) ==1){
                                    returnHTML += '<a   onclick="pasabox_details_file(\''+data['reference_no']+'\')" class="black" data-toggle="modal" data-target=".pasabox_details_file_modal"  style="font-size:18px" title="Uploaded Pictures"><i class="fa fa-picture-o bigger-240"></i></a>';
                                }
                            }
                            if( Number(data['pasabox_cf_amt']) > tpayment  ){
                                returnHTML += '<br><a onclick="get_gcash_rp_qr(\''+data['reference_no']+'\','+tpayment+')" data-toggle="modal" data-target=".pasabox_cf_modal" class="green" title="Repost Payment"><i class="ace-icon fa fa-money bigger-150"></i> Repost Payment</a>';
                            }
                        }
                        // else if( data['pca_account_no'] !='' &&  data['pca_account_no'] !=null ){
                        //     returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
                        // }


                        return returnHTML;
                    }}
                    // ,
                    // {data: 'reference_no',render(data,type){
                    //     return '<div class="qrcode"></div>';
                    // }}

                ],
                initComplete: function(){
                    $('#pending-table > tbody > tr').each(function(e){
                        $tr = $(this);
                        var data = myTable.rows($tr).data()[0];
                        // console.log($tr.find('.qrcode'));
                        new QRCode($tr.find('.qrcode')[0],{text: data['reference_no'],width:50,height:50});
                    });
                }
        } );


        myTable.on('click', '.shipper-info', function() {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            $('#tbl-track-and-trace-details tbody').empty();
            var shipper = data.shipper;

            $('.tt_details').modal('show');
        });

        myTable.on('click','.qrcode',function(){
            $tr = $(this).closest('tr');
            var data = myTable.rows($tr).data()[0];
            $modalQRCode = $('#modal-qrcode');
            $modalQRCode.find('.modal-title').html('<i class="ace-icon fa fa-qrcode bigger-130"></i> '+data['reference_no']);
            if($modalQRCode.find('.qrcode').has('img')){
                $modalQRCode.find('.qrcode').find('img').remove();
            }
            new QRCode($modalQRCode.find('.qrcode')[0],{text: "{{url('/waybills/printable-reference/')}}"+data['encryptedReference'],width:250,height:250});
            $modalQRCode.modal('show');
        })

        $('ul.nav li a').click(function(e){
            if(e.currentTarget.hash=="#transacted"){
                $('.delete-bookings').attr('style','display:none');
            }else{
                $('.delete-bookings').removeAttr('style');
            }
        });
        function get_transacted_list(){
            $('#transacted-table').dataTable().fnClearTable();
            $('#transacted-table').dataTable().fnDestroy();
            var myTableTransacted = $('#transacted-table').DataTable( {
                ajax: {
                        url : "{{ url('/get-transacted') }}/"+$("#month-filter-transacted").val(),
                        type: "GET",
                    },
                    processing: true,
                    // serverSide : true,
                    rowId: "reference_no",
                    language: {
                        'loadingRecords': '&nbsp;',
                        'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                    },
                    bLengthChange: false,
                    bInfo : false,
                    order: [[ 1, "desc" ]],
                    columnDefs : [
                        //{ className: "text-center", "targets": [ 0 ] },
                        { className: "", "targets": [ 0 ] },
                        { className: "shipper-info", "targets": [ 3 ] },
                        { className: "consignee-info", "targets": [ 4 ] }
                    ],
                    columns: [
                        {data : null,render(data,type){
                            var className = data.payment_type == 'CI' ? 'label-success' : (data.payment_type == 'CD' ? 'label-warning' : 'label-primary');
                            var text = data.payment_type == 'CI' ? 'PREPAID' : (data.payment_type == 'CD' ? 'COLLECT' : 'CHARGE');

                            if(data['pasabox']==1){
                                text =text+' | PASABOX';
                            }
                            text_append='';
                            if( data['confirm_pickup_date'] !=null){
                                text_append += '<br><p>CONFIRMED PICK-UP DATE: <u>'+data['confirm_pickup_date']+'</u></p>';

                            }

                            if(data['booking_status']==3){
                                text_append += '<br><span class="label label-sm label-danger">CANCELLED BOOKING</span>';
                            }else{
                                @if( Auth::user()->contact->verified_account==1 )
                                $.each(data['waybill'],function(){

                                    if(data['pasabox']==1){
                                        text_append += '<br><span onclick="waybill_details_func(\''+this.transactioncode+'\')" data-toggle="modal" data-target=".waybill_details_modal"  class="label label-sm label-primary">Waybill #: '+this.waybill_no+'</span>';
                                    }else{
                                        text_append += '<br><span class="label label-sm label-primary">Waybill #: '+this.waybill_no+'</span>';
                                    }
                                });
                                @endif
                            }

                            return '<span class="label label-sm '+className+'">'+text+'</span><br><a ><font size="2" style="font-weight:bold;">'+data.reference_no+'</font></a>'+text_append;
                        }},
                        {data : null,render(data,type){
                            append_text='';
                            if(data['pasabox']==1){
                                if( data['shipping_company'] != null){
                                    append_text +='<br>Shipping Comp.: '+data['shipping_company']['fileas']+'<br> <i class="fa fa-phone"></i> '+data['shipping_company']['contact_no']+'<br><i class="fa fa-envelope"></i> '+data['shipping_company']['email'];
                                }

                                tt_recent='';
                                txt_append='';
                                ttr_recent_data=data['track_trace_recent'];
                                if(ttr_recent_data != null ){
                                    tt_recent=ttr_recent_data['remarks'];
                                    if(
                                        ttr_recent_data['obr_details_id'] ==3
                                        || ttr_recent_data['obr_details_id'] ==4
                                        || ttr_recent_data['obr_details_id'] ==5
                                        || ttr_recent_data['obr_details_id'] ==6
                                        || ttr_recent_data['obr_details_id'] ==7
                                        || ttr_recent_data['obr_details_id'] ==8

                                    ){
                                        txt_append='<br><a  onclick="pasabox_details_file(\''+data['reference_no']+'\')"  data-toggle="modal" data-target=".pasabox_details_file_modal"   title="Uploaded Pictures"><i><u>Click here to view uploaded pictures</i></u></a>';
                                    }
                                }

                                if(tt_recent !=''){
                                    append_text += '<br><i class="fa fa-map-marker"></i> '+tt_recent.toLowerCase()+txt_append;
                                }
                            }
                            return formatDate(data['transactiondate'])+append_text;
                        }},
                        // {data: 'payment_type',render(data,type){
                        //     var className = data == 'CI' ? 'label-success' : (data == 'CD' ? 'label-warning' : 'label-primary');
                        //     var text = data == 'CI' ? 'PREPAID' : (data == 'CD' ? 'COLLECT' : 'CHARGE');
                        //     return '<span class="label label-sm '+className+'">'+text+'</span>';
                        // }},
                        {data: 'shipper',render(data,type){
                            return data['fileas']+'<br><i class="fa fa-phone"></i> '+data['contact_no'];
                        }},
                        {data: 'consignee',render(data,type){
                            return data['fileas']+'<br><i class="fa fa-phone"></i> '+data['contact_no'];
                        }},
                        {data: null,render(data,type){
                            var returnHTML = '';
                            returnHTML += '<a class="blue" target="_blank" href="'+"{{url('/waybills')}}/"+data['reference_no']+'" style="font-size:18px" title="Print"><i class="ace-icon fa fa-print bigger-240"></i></a>';

                            returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
                            if(data['pasabox']==1){
                                if(data['pasabox_received'] !=null){
                                    if( Number(data['pasabox_received']['pasabox_to_receive_status']) ==1){
                                        returnHTML += '<a   onclick="pasabox_details_file(\''+data['reference_no']+'\')" class="black" data-toggle="modal" data-target=".pasabox_details_file_modal"  style="font-size:18px" title="Uploaded Pictures"><i class="fa fa-picture-o bigger-240"></i></a>';
                                    }
                                }
                            }
                            // else if( data['pca_account_no'] !='' &&  data['pca_account_no'] !=null ){
                            //     returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
                            // }else{
                            //     returnHTML += '<a class="green track-and-trace" id="'+data['reference_no']+'" style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
                            // }


                            // if(data['booking_status']==3){
                            //     returnHTML += '<br><span class="label label-sm label-danger">CANCELLED BOOKING</span>';
                            // }
                            // else{
                            //     @if( Auth::user()->contact->verified_account==1 )
                            //     $.each(data['waybill'],function(){
                            //         returnHTML += '<br><span class="label label-sm label-primary">Waybill #: '+this.waybill_no+'</span>';
                            //     });
                            //     @endif
                            // }

                            return returnHTML;

                        }}

                    ],
                    initComplete: function(row, data, index) {
                        $(".badge-transacted-count").html( myTableTransacted.rows().count() );
                        //alert( myTableTransacted.rows().count());
                    }
            } );

            myTableTransacted.on('click', '.track-and-trace', function() {

                //$tr = $(this).closest('tr');
                //var data = myTableTransacted.row($tr).data();

                id=this.id;

                swal({
                text: 'Track this transacted booking',
                icon : 'warning',
                // button: {
                //     text: "Track!",
                //     closeModal: false,
                //     className: "btn btn-success"
                // },
                buttons: {
                    close: {
                        text: "Close",
                        className: "btn btn-default"
                    },
                    track: {
                        text: "Track!",
                        closeModal: false,
                        className: "btn btn-success"
                    }
                },

                })
                .then(name => {
                    switch (name) {
                        case "track" :

                            return $.ajax({
                            url: "{{url('/waybill-track-by-reference')}}",
                            type : "POST",
                            //data: {_token : "{{csrf_token()}}", reference_no : data['reference_no']}
                            data: {_token : "{{csrf_token()}}", reference_no : id}
                        });
                        default:
                        swal.close();
                    }
                })
                .then(results => {
                    return results;
                })
                .then(json => {
                    if(json.type=='error'){
                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = json.message;
                        swal({
                            title: json.title,
                            content: wrapper,
                            icon: 'error'
                        });
                    }else{
                        swal({
                            title: json.title,
                            text: json.message,
                            icon: 'success',
                            timer: 5000,
                            buttons: {}
                        });
                    }



                })
                .catch(err => {
                    // if (err) {
                    //     swal("Ooops!", "Network communication failed!", "error");
                    // } else {
                    //     swal.stopLoading();
                    //     swal.close();
                    // }
                });


            });
        }
        get_transacted_list();

        $(".month-filter-transacted-btn").click(function(){
            //alert();
            get_transacted_list();
        });

        $('.add-booking').click(function(){
            window.location.href="{{route('waybills.create')}}";

        });



        $('.delete-bookings').click(function(){
            var data = myTable.rows('.selected').data();

            if(data.length==0){
                swal('No selected item', {
                    icon: 'error',
                    title: 'Ooops!'
                });
            }else{
                swal({
                  title: "Are you sure?",
                  text: "Cancel this transaction(s)!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {

                    for(var i=0; i<data.length; i++){

                        $.ajax({
                            url: "{{url('/waybills')}}/"+data[i]['reference_no'],
                            type: "DELETE",
                            data : { _token : "{{csrf_token()}}", reference_no : data[i]['reference_no']},
                            success: function(result){
                            }
                        })
                    }

                    swal('Transaction(s) has been cancelled', {
                        icon: 'success',
                        title: 'Success!'
                    }).then(function(){
                        // if(result.type=="success"){
                            myTable.rows('.selected').remove().draw();
                            pcount=Number($('.pending-count').html());
                            if(pcount > 0){
                                pcount -=data.length;
                                $('.pending-count').html(pcount);
                            }
                        // }
                    });


                  }
                });
            }

        });

        $('.track-and-trace').click(function(e){
            swal({
            text: 'Track this transacted booking',
            icon : 'warning',
            // button: {
            //     text: "Track!",
            //     closeModal: false,
            //     className: "btn btn-success"
            // },
            buttons: {
                close: {
                    text: "Close",
                    className: "btn btn-default"
                },
                track: {
                    text: "Track!",
                    closeModal: false,
                    className: "btn btn-success"
                }
            },

            })
            .then(name => {
                switch (name) {
                    case "track" :
                        return $.ajax({
                        url: "{{url('/waybill-track-by-reference')}}",
                        type : "POST",
                        data: {_token : "{{csrf_token()}}", reference_no : e.currentTarget.id}
                    });
                    default:
                    swal.close();
                }
            })
            .then(results => {
                return results;
            })
            .then(json => {
                if(json.type=='error'){
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = json.message;
                    swal({
                        title: json.title,
                        content: wrapper,
                        icon: 'error'
                    });
                }else{
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'success',
                        timer: 2000,
                        buttons: {}
                    });
                }



            })
            .catch(err => {
                // if (err) {
                //     swal("Ooops!", "Network communication failed!", "error");
                // } else {
                //     swal.stopLoading();
                //     swal.close();
                // }
            });

        });

        $('.search').click(function(){
            $('#modal-search').modal('show');
        })

        // $('#form-search').on('submit', function(e) {

        //     // $.ajax({
        //     //     url: "{{route('waybills.search')}}",
        //     //     type : "POST",
        //     //     dataType: "JSON",
        //     //     success: function(result){
        //     //         var obj = result;
        //     //         // myTable.rows().remove().draw();
        //     //         console.log(obj);
        //     //         // for(var i=0; i<obj.length; i++){
        //     //         //     myTable.row.add([
        //     //         //     obj[i]['reference_no'],
        //     //         //     obj[i]['transactiondate'],
        //     //         //     obj[i]['payment_type'],
        //     //         //     obj[i]['shipper']['fileas'],
        //     //         //     obj[i]['consignee']['fileas'],

        //     //         //     'div  action-buttons"><a class="blue" href="#"><i class="ace-icon fa fa-print bigger-130"></i></a><a class="green" href="#"><i class="ace-icon fa fa-pencil bigger-130"></i></a><a class="red delete" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>'
        //     //         //     ]).draw(false);
        //     //         // }
        //     //     }
        //     // });
        //     e.preventDefault();
        //     $('#modal-search').modal('hide');
        // });

        $('#form-search').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                datefrom: {
                    required: true,
                    date : true
                },
                dateto: {
                    required: true,
                    date: true
                },

            },

            messages: {

                datefrom : {
                    required: "Date-from is required",
                    date : "Date-from must be date"
                },
                dateto : {
                    required: "Date-to is required",
                    date : "Date-to must be date"
                },

            },


            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else if(element.is('.select2')) {
                    error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
                }
                else if(element.is('.chosen-select')) {
                    error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
                }
                else error.insertAfter(element.parent());
            },

            submitHandler: function (form) {
                var form_data = new FormData(form);

                form_data.append('_token',"{{csrf_token()}}");
                    $('#form-search .submit').attr('disabled',true);
                    $('#form-search .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                    $.ajax({
                        url: "{{route('waybills.search')}}",
                        type: "POST",
                        data: form_data,
                        dataType: 'JSON',
                        processData: false,
                        contentType:false,
                        cache:false,
                        success: function(result){

                            var obj = result;
                            myTable.rows().remove().draw();

                            for(var i=0; i<obj.length; i++){
                                myTable.row.add([
                                obj[i]['reference_no'],
                                obj[i]['transactiondate'],
                                obj[i]['payment_type']=="CI" ? "<span class='label label-sm label-success'>Prepaid</span>" : "<span class='label label-sm label-warning'>Collect</span>",
                                obj[i]['shipper']['fileas']+'<br><b>'+obj[i]['shipper_address']['address_caption']+'</b> : '+obj[i]['shipper_address']['full_address']+'<br>Contact # :' + obj[i]['shipper']['contact_no'],
                                obj[i]['consignee']['fileas']+'<br>'+obj[i]['consignee_address']['address_caption']+'</b> : '+obj[i]['consignee_address']['full_address']+'<br>Contact # :' + obj[i]['shipper']['contact_no'],

                                '<div  action-buttons"><a class="blue" href="'+"{{url('/waybills')}}"+'/'+obj[i]['reference_no']+'"><i class="ace-icon fa fa-print bigger-130"></i></a> &nbsp;<a class="green" href="#"><i class="ace-icon fa fa-pencil bigger-130"></i></a> &nbsp;<a class="red delete" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a></div>'
                                ]).draw(false);
                            }

                            $('#form-search .submit').html('Search');
                            $('#form-search .submit').removeAttr('disabled');
                            $('#modal-search').modal('hide');



                        }
                    });
                    return false;
                },
                invalidHandler: function (form) {
                }
            });

                $('select[name="province"]').change(function(){
                    $.ajax({
                        url : "{{url('/get-cities')}}/"+$(this).val(),
                        type: "GET",
                        dataType: "JSON",
                        success: function(result){
                            var obj = result;
                            var cities_innerHtml = '';
                            // console.log(obj[0]['cities_name']);

                            for(var i=0; i<obj.length;i++){
                                // console.log(i);
                                cities_innerHtml = cities_innerHtml + "<option value='"+obj[i]['cities_id']+"'>"+obj[i]['cities_name']+"</option>";
                            }
                            // console.log(result);
                            // console.log(cities_innerHtml);
                            $('select[name="city"]').html(cities_innerHtml);


                        }
                    })
                });

        myTable.on('click', '.delete', function(e) {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Cancel this transaction!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/waybills')}}/"+e.currentTarget.id,
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}", reference_no : e.currentTarget.id},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                myTable.row($tr).remove().draw();
                                pcount=Number($('.pending-count').html());
                                if(pcount > 0){
                                    pcount -=1;
                                    $('.pending-count').html(pcount);
                                }
                            }
                        });
                    }
                })

              }
            });
            e.preventDefault();
        });

        myTable.on( 'select', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        $( myTable.row( index ).node() ).find('input:checkbox').prop('checked', true);
                    }
                } );
                myTable.on( 'deselect', function ( e, dt, type, index ) {
                    if ( type === 'row' ) {
                        $( myTable.row( index ).node() ).find('input:checkbox').prop('checked', false);
                    }
                } );
        $('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);

                //select/deselect all rows according to table header checkbox
        $('#dynamic-table > thead > tr > th input[type=checkbox], #dynamic-table_wrapper input[type=checkbox]').eq(0).on('click', function(){
            var th_checked = this.checked;//checkbox inside "TH" table header

            $('#dynamic-table').find('tbody > tr').each(function(){
                var row = this;
                if(th_checked) myTable.row(row).select();
                else  myTable.row(row).deselect();
            });
        });

                //select/deselect a row when the checkbox is checked/unchecked
        $('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
            var row = $(this).closest('tr').get(0);
            if(this.checked) myTable.row(row).deselect();
            else myTable.row(row).select();
        });

        $(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
            e.stopImmediatePropagation();
            e.stopPropagation();
            e.preventDefault();
        });
})
</script>
@endsection
