@extends('layouts.gentelella')

@section('css')
<script>
localStorage.setItem('useravatar',"{{$image_base64}}");
</script>
@endsection

@section('bread-crumbs')
<!--h3>Dashboard</h3-->
@endsection

@section('content')

@role('Client')
<!--div class="row top_tiles">
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">

	</div>
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">

	</div>

	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="tile-stats">
			<div class="icon"><i class="fa fa-shopping-cart"></i>
			</div>
			<div class="count">{{Auth::user()->waybill->count()}}</div>

			<h3>Bookings</h3>
		</div>
	</div>
	<div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="tile-stats">
			<div class="icon"><i class="fa fa-users"></i>
			</div>
			<div class="count">{{Auth::user()->shipper_consignee->count()-1}}</div>

			<h3>Shippers/Consignees</h3>
		</div>
	</div>

</div-->

<div class="modal fade rebate_details" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-gift" ></i> REBATE</h4>
        </div>
        <div class="modal-body">

            <table id="table_tab1"  class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th width="10%">DATE</th>
                    <th>WAYBILL</th>
                    <th width="15%">ADD</th>
                    <th width="15%">LESS</th>
                    <th width="15%">BALANCE (₱)</th>
                  </tr>

                </thead>
                <tbody>
                    @php $balance_rebate =0; @endphp
                    @foreach ( Auth::user()->rebate_transaction as $rebate_transaction )

                    @php
                    if( $rebate_transaction->rebate_point_in > 0){
                        $balance_rebate =$balance_rebate+$rebate_transaction->rebate_point_in;
                    }
                    if( $rebate_transaction->rebate_point_out > 0){
                        $balance_rebate =$balance_rebate-$rebate_transaction->rebate_point_out;
                    }
                    @endphp
                    <tr>
                        <td>{{ date('Y/m/d',strtotime($rebate_transaction->rebate_transaction_date)) }}</td>
                        <td>{{ $rebate_transaction->waybill->waybill_no }}
                            @if($rebate_transaction->particulars != null)
                            <br>{{  $rebate_transaction->particulars }}
                            @endif
                        </td>
                        <td>{{ $rebate_transaction->rebate_point_in > 0 ? number_format($rebate_transaction->rebate_point_in,2) : '-' }}</td>
                        <td>{{ $rebate_transaction->rebate_point_out > 0 ? number_format($rebate_transaction->rebate_point_out,2) : '-' }}</td>
                        <td>{{ number_format($balance_rebate,2) }}</td>
                    </tr>

                    @endforeach


                </tbody>
              </table>

            <div class="modal-footer">

            </div>

        </div>
      </div>
    </div>
</div>

<div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-list-alt"></i> Bookings</span>
      <div class="count">{{Auth::user()->waybill->count()}}</div>
      <span class="count_bottom"><i class="green">* </i> All transactions</span>
    </div>

    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-users"></i> Shippers/Consignees</span>
      <div class="count">{{ Auth::user()->shipper_consignee->count() ==0 ? 0 : Auth::user()->shipper_consignee->count()-1 }}</div>
      <span class="count_bottom"><i class="green">*</i> Saved </span>
    </div>


    <div hidden {{ Auth::user()->contact->verified_account==1  ? '' : 'hidden'}} class="col-md-2 col-sm-4 col-xs-6 tile_stats_count" data-toggle="modal" data-target=".rebate_details" >
        <span class="count_top"><i class="fa fa-gift"></i> Rebate</span>
        <div class="count">₱{{ number_format($balance_rebate,2) }}</div>
        <span class="count_bottom"><i class="green">*</i> With Waybill </span>
    </div>



  </div>



<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-list"></i> RECENT TRANSACTIONS</h4>
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive hidden-xs">
                    <table id="pending-table" style="table-layout: fixed;width: 100%;" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ref. #</th>
                                <th>Date</th>
                                <th>Shipper</th>
                                <th>Consignee</th>
                                <th></th>
                                <!--th></th-->
                            </tr>
                        </thead>
                        <tbody id="pending-data"></tbody>
                    </table>
                </div>
            </div>

            <div id="pending-cards" class="visible-xs" style="display:none;"></div>
            <div class="clearfix"></div>
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

<!--h4 class="widget-title lighter">
    <i class="ace-icon fa fa-shopping-cart green"></i>
    Recent Transaction
</h4>
<div class="row">
    <div class="col-12">
        <table id="pending-table" class="table  table-bordered table-hover">
		    <thead>
                <tr>
                    <th>Reference #</th>
                    <th>Shipper</th>
                    <th>Consignee</th>
                    <th></th>
                    <th></th>
                </tr>

            </thead>

			<tbody>


			</tbody>
		</table>
    </div>
</div-->

@endrole
@endsection

@section('plugins')
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
<script src="{{asset('/js/sweetalert.min.js')}}"></script>
<script src="{{asset('/js/qrcode.js')}}"></script>
@endsection

@section('scripts')
@include('waybills.track_trace')
@include('waybills.pasabox_file')
<script>
$(document).ready(function () {
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [month, day, year].join('/');
    }

    $.ajax({
        url: "/get-recent-transacted",
        type: "GET",
        success: function (response) {

            $("#pending-data").html("");
            $("#pending-cards").html("");

            if (!response.data || response.data.length === 0) {
                $("#pending-data").append(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">No pending transactions found.</td>
                    </tr>
                `);
                return;
            }

            response.data.forEach(row => {

                let paymentClass = row.payment_type === "CI"
                    ? "label-success"
                    : (row.payment_type === "CD")
                        ? "label-warning"
                        : "label-primary";

                let paymentText = row.payment_type === "CI"
                    ? "PREPAID"
                    : (row.payment_type === "CD")
                        ? "COLLECT"
                        : "CHARGE";

                // let paymentClass = row['payment_type'] == 'CI' ? 'label-success' : (row == 'CD' ? 'label-warning' : 'label-primary');
                // let paymentText = row['payment_type'] == 'CI' ? 'PREPAID' : (row == 'CD' ? 'COLLECT' : 'CHARGE');

                if (row.pasabox == 1) paymentText += " | PASABOX";

                let referenceLink =
                    row.pasabox == 1
                        ? `<a href="#"><b>${row.reference_no}</b></a>`
                        : `<a href="/waybills/${btoa(row.reference_no)}/edit"><b>${row.reference_no}</b></a>`;

                let dateAppend = "";
                if (row.pasabox == 1) {
                    if (row.shipping_company != null) {
                        dateAppend += `
                            <br>Shipping Comp.: ${row.shipping_company.fileas}
                            <br><i class="fa fa-phone"></i> ${row.shipping_company.contact_no}
                            <br><i class="fa fa-envelope"></i> ${row.shipping_company.email}
                        `;
                    }

                    if (row.track_trace_recent != null) {
                        let tt = row.track_trace_recent.remarks.toLowerCase();
                        let clickable = "";
                        if ([3, 4, 5, 6, 7, 8].includes(row.track_trace_recent.obr_details_id)) {
                            clickable = `
                                <br><a onclick="pasabox_details_file('${row.reference_no}')"
                                    data-toggle="modal"
                                    data-target=".pasabox_details_file_modal">
                                    <u>Click here to view uploaded pictures</u>
                                </a>`;
                        }
                        dateAppend += `<br><i class="fa fa-map-marker"></i> ${tt}${clickable}`;
                    }
                }

                let shipper = `
                    ${row.shipper.fileas}
                    <br><small>
                        ${row.shipper_address.barangay ?? ""}
                        ${row.shipper_address.city ?? ""}
                        ${row.shipper_address.postal_code ?? ""}
                        <br><i class="fa fa-phone"></i> ${row.shipper.contact_no}
                    </small>
                `;

                let consignee = `
                    ${row.consignee.fileas}
                    <br><small>
                        ${row.consignee_address.barangay ?? ""}
                        ${row.consignee_address.city ?? ""}
                        ${row.consignee_address.postal_code ?? ""}
                        <br><i class="fa fa-phone"></i> ${row.consignee.contact_no}
                    </small>
                `;

                let buttons = `
                    <a href="/waybills/${row.reference_no}" class="btn btn-info btn-xs" target="_blank">PRINT</a>
                `;

                if ((row.booking_status == 0 || row.booking_status == 2) && row.pasabox != 1) {
                    buttons += `
                        <a href="/waybills/${btoa(row.reference_no)}/edit" class="btn btn-success btn-xs">EDIT</a>
                        <a class="btn btn-danger btn-xs delete" data-id="${row.reference_no}">CANCEL</a>
                    `;
                }

                buttons += `
                    <a class="btn btn-warning btn-xs track" data-ref="${row.reference_no}" data-toggle="modal" data-target=".pasabox_track_trace">TRACK</a>
                `;

                if (row.pasabox_received && Number(row.pasabox_received.pasabox_to_receive_status) === 1) {
                    buttons += `
                        <a onclick="pasabox_details_file('${row.reference_no}')"
                            class="btn btn-default btn-xs" data-toggle="modal"
                            data-target=".pasabox_details_file_modal">IMAGE
                        </a>
                    `;
                }

                $("#pending-data").append(`
                    <tr>
                        <td>
                            <div class="qrcode" data-ref="${row.reference_no}" id="qr-${row.reference_no}"></div>
                            <span class="label label-sm ${paymentClass}">${paymentText}</span><br>
                            ${referenceLink}
                        </td>
                        <td>${formatDate(row.transactiondate)}${dateAppend}</td>
                        <td>${shipper}</td>
                        <td>${consignee}</td>
                        <td class="text-center">
                            <div class="btn-group-vertical">
                                ${buttons}
                            </div>
                        </td>
                    </tr>
                `);

                // responsive in mobile
                $("#pending-cards").append(`
                    <div class="list-tile">
                        <div class="mt-2">
                            <div id="qr-mobile-${row.reference_no}" style="width:80px;"></div>
                        </div>

                        <div class="listtile-header">
                            ${referenceLink}
                            <span class="label ${paymentClass}">${paymentText}</span>
                        </div>

                        <div class="listtile-subtitle">
                            <b>Date:</b> ${formatDate(row.transactiondate)}<br>
                            <b>Shipper:</b> ${row.shipper.fileas}<br>
                            <b>Consignee:</b> ${row.consignee.fileas}<br>
                        </div>

                        <div class="listtile-actions">
                            ${buttons}
                        </div>
                    </div>
                `);
                // Generate QR For Mobile
                new QRCode(document.getElementById(`qr-mobile-${row.reference_no}`), {
                    text: row.reference_no,
                    width: 60,
                    height: 60
                });

                // Generate QR Code
                new QRCode(document.getElementById(`qr-${row.reference_no}`), {
                    text: row.reference_no,
                    width: 50,
                    height: 50
                });

            });

        },
        error: function () {
            $("#pending-data").html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">Error loading data.</td>
                </tr>
            `);
        }
    });


    // DELETE
    $(document).on("click", ".delete", function () {
        let ref = $(this).data("id");
        let row = $(this).closest("tr");

        swal({
            title: "Are you sure?",
            text: "Cancel this transaction!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((ok) => {
            if (!ok) return;

            $.ajax({
                url: `/waybills/${ref}`,
                type: "DELETE",
                data: { _token: $("meta[name=csrf-token]").attr("content") },
                success: function (result) {
                    swal("Transaction has been cancelled", { icon: "success" });
                    row.remove();
                }
            });
        });
    });

    // TRACK & TRACE
    $(document).on("click", ".track", function () {
        let ref = $(this).data("ref");

        swal({
            text: 'Track this transacted booking',
            icon: 'warning',
            buttons: {
                close: { text: "Close", className: "btn btn-default" },
                track: { text: "Track!", className: "btn btn-success" }
            }
        }).then(name => {
            if (name === "track") {
                return $.ajax({
                    url: "/waybill-track-by-reference",
                    type: "POST",
                    data: { _token: $("meta[name=csrf-token]").attr("content"), reference_no: ref }
                });
            }
        }).then(results => {
            if (!results) return;

            if (results.type === 'error') {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = results.message;
                swal({ title: results.title, content: wrapper, icon: 'error' });
            } else {
                swal({ title: results.title, text: results.message, icon: 'success', timer: 5000, buttons: {} });
            }
        }).catch(err => {});
    });

    // QR CODE MODAL
    $(document).on("click", ".qrcode", function () {
        let ref = $(this).data("ref");
        let modal = $('#modal-qrcode');
        modal.find('.modal-title').html(`<i class="ace-icon fa fa-qrcode"></i> ${ref}`);
        modal.find('.qrcode').html(''); // Clear previous QR
        new QRCode(modal.find('.qrcode')[0], {
            text: `/waybills/printable-reference/${ref}`,
            width: 250,
            height: 250
        });
        modal.modal('show');
    });

    // SHOW DETAILS BUTTON (if you have toggle rows)
    $(document).on("click", ".show-details-btn", function () {
        $(this).closest("tr").next().toggleClass("open");
        $(this).find("i").toggleClass("fa-angle-double-down fa-angle-double-up");
    });

});
</script>
@endsection


