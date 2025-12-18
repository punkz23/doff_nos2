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
                        <td>
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

// $(document).ready(function(){

//     function formatDate(date) {
//         var d = new Date(date),
//             month = '' + (d.getMonth() + 1),
//             day = '' + d.getDate(),
//             year = d.getFullYear();

//         if (month.length < 2) month = '0' + month;
//         if (day.length < 2) day = '0' + day;

//         return [month, day, year].join('/');
//     }

//     var myTable = $('#pending-table').DataTable( {
//             ajax: {
//                     url : "{{ url('/get-recent-transacted') }}",
//                     type: "GET",
//                 },
//                 processing: true,
//                 // serverSide : true,
//                 rowId: "reference_no",
//                 language: {
//                     'loadingRecords': '&nbsp;',
//                     'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
//                 },
//                 bLengthChange: false,
//                 bInfo : false,
//                 bFilter: false,
//                 paging: false,
//                 order: [[ 1, "desc" ]],
//                 select: {
//                     style: 'multi'
//                 },
//                 responsive: true,

//                 columns: [
//                     // {data : 'reference_no',render(data,type){
//                     //     return '<a href="'+"{{url('/waybills')}}/"+data+'/edit"><font size="3" style="font-weight:bold;">'+data+'</font></a>'
//                     // }},
//                     {data:null,render(data,type){

//                         var returnHTML = '<div class="qrcode"></div>';

//                         var className = data['payment_type'] == 'CI' ? 'label-success' : (data == 'CD' ? 'label-warning' : 'label-primary');
//                         var text = data['payment_type'] == 'CI' ? 'PREPAID' : (data == 'CD' ? 'COLLECT' : 'CHARGE');
//                         if(data['pasabox']==1){
//                             text =text+' | PASABOX';
//                         }
//                         returnHTML += '<span class="label label-sm '+className+'">'+text+'</span><br>';
//                         if(data['pasabox']==1){
//                             returnHTML += '<a href="#"><font size="2" style="font-weight:bold;">'+data['reference_no']+'</font></a><br>';
//                         }else{
//                             returnHTML += '<a href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit"><font size="2" style="font-weight:bold;">'+data['reference_no']+'</font></a><br>';
//                         }
//                         if(data['booking_status']==1){
//                             $.each(data['waybill'],function(){
//                                 @if( Auth::user()->contact->verified_account==1 )
//                                     returnHTML += '<span class="label label-sm label-primary">TRANSACTED | Waybill #: '+this.waybill_no+'</span>';
//                                 @endif

//                             });
//                         }
//                         else{
//                             if(data['booking_status']==2){
//                                 @if( Auth::user()->contact->verified_account==1 )
//                                     returnHTML += '<span class="label label-sm label-info">ON PROCESS</span>';
//                                 @endif
//                             }
//                         }

//                         return returnHTML;
//                     }},
//                     {data : null,render(data,type){
//                         append_text='';
//                         if(data['pasabox']==1){
//                             if( data['shipping_company'] != null){
//                                 append_text +='<br>Shipping Comp.: '+data['shipping_company']['fileas']+'<br> <i class="fa fa-phone"></i> '+data['shipping_company']['contact_no']+'<br><i class="fa fa-envelope"></i> '+data['shipping_company']['email'];
//                             }



//                             tt_recent='';
//                             txt_append='';
//                             ttr_recent_data=data['track_trace_recent'];
//                             if(ttr_recent_data != null ){
//                                 tt_recent=ttr_recent_data['remarks'];
//                                 if(
//                                     ttr_recent_data['obr_details_id'] ==3
//                                     || ttr_recent_data['obr_details_id'] ==4
//                                     || ttr_recent_data['obr_details_id'] ==5
//                                     || ttr_recent_data['obr_details_id'] ==6
//                                     || ttr_recent_data['obr_details_id'] ==7
//                                     || ttr_recent_data['obr_details_id'] ==8

//                                 ){
//                                     txt_append='<br><a  onclick="pasabox_details_file(\''+data['reference_no']+'\')"  data-toggle="modal" data-target=".pasabox_details_file_modal"   title="Uploaded Pictures"><i><u>Click here to view uploaded pictures</i></u></a>';
//                                 }
//                             }

//                             if(tt_recent !=''){
//                                 append_text += '<br><i class="fa fa-map-marker"></i> '+tt_recent.toLowerCase()+txt_append;
//                             }
//                         }
//                         return formatDate(data['transactiondate'])+append_text;
//                         }},
//                     {data : null,render(data,type){
//                         return data['shipper']['fileas']+'<br><small>'+(data['shipper_address']['barangay']!='' ? data['shipper_address']['barangay']+' ' : '')+(data['shipper_address']['city']!='' ? data['shipper_address']['city']+' ' : '')+(data['shipper_address']['postal_code']!='' ? data['shipper_address']['postal_code']+' ' : '')+'<br><i class="fa fa-phone"></i> '+data['shipper']['contact_no']+'</small>';
//                     }},
//                     {data: null,render(data,type){
//                         return data['consignee']['fileas']+'<br><small>'+(data['consignee_address']['barangay']!='' ? data['consignee_address']['barangay']+' ' : '')+(data['consignee_address']['city']!='' ? data['consignee_address']['city']+' ' : '')+(data['consignee_address']['postal_code']!='' ? data['shipper_address']['postal_code']+' ' : '')+'<br><i class="fa fa-phone"></i> '+data['consignee']['contact_no']+'</small>';
//                     }},
//                     {data: null,render(data,type){
//                         var returnHTML = '';
//                         returnHTML += '<a style="font-size:18px" class="blue" target="_blank" href="'+"{{url('/waybills')}}/"+data['reference_no']+'" title="Print"><i class="ace-icon fa fa-print bigger-240"></i></a>';
//                         if( ( data['booking_status']==0 || data['booking_status']==2 ) && data['pasabox'] !=1 ){
//                             returnHTML += '<br>'+
//                             '<a style="font-size:18px" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>'+
//                             '&nbsp;<a style="font-size:18px" class="red delete" href="#" title="Cancel" id="'+data['reference_no']+'"><i class="ace-icon fa fa-ban bigger-130"></i></a>';
//                         }

//                         if(data['pasabox']==1){
//                             if( data['booking_status']==0 || data['booking_status']==2 ){
//                                 if(data['pasabox_received'] !=null){
//                                     if( Number(data['pasabox_received']['pasabox_to_receive_status']) ==0){
//                                         returnHTML += '<br><a style="font-size:18px" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>';
//                                     }
//                                 }else{
//                                     returnHTML += '<br><a style="font-size:18px" class="green" href="'+"{{url('/waybills')}}/"+btoa(data['reference_no'])+'/edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-240"></i></a>';
//                                 }

//                             }
//                         //returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
//                         }
//                         // else{
//                         //     returnHTML += '<a class="green track-and-trace" id="'+data['reference_no']+'" style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';
//                         // }
//                         returnHTML += '<a  onclick="track_trace_func(\''+data['reference_no']+'\')" class="green" data-toggle="modal" data-target=".pasabox_track_trace"  style="font-size:18px" title="Track and Trace"><i class="glyphicon glyphicon-map-marker bigger-240"></i></a>';

//                         if(data['pasabox_received'] !=null){
//                             if( Number(data['pasabox_received']['pasabox_to_receive_status']) ==1){
//                                 returnHTML += '<a   onclick="pasabox_details_file(\''+data['reference_no']+'\')" class="black" data-toggle="modal" data-target=".pasabox_details_file_modal"  style="font-size:18px" title="Uploaded Pictures"><i class="fa fa-picture-o bigger-240"></i></a>';
//                             }
//                         }

//                         return returnHTML;
//                     }}

//                     // ,
//                     // {data: 'reference_no',render(data,type){
//                     //     return '<div class="qrcode"></div>';
//                     // }}


//                 ],
//                 initComplete: function(){
//                     $('#pending-table > tbody > tr').each(function(e){
//                         $tr = $(this);
//                         var data = myTable.rows($tr).data()[0];
//                         // console.log($tr.find('.qrcode'));
//                         new QRCode($tr.find('.qrcode')[0],{text: data['reference_no'],width:50,height:50});
//                     });
//                 }
//         } );

//         myTable.on('click', '.track-and-trace', function() {
//             $tr = $(this).closest('tr');
//             var data = myTable.row($tr).data();

//             swal({
//             text: 'Track this transacted booking',
//             icon : 'warning',
//             // button: {
//             //     text: "Track!",
//             //     closeModal: false,
//             //     className: "btn btn-success"
//             // },
//             buttons: {
//                 close: {
//                     text: "Close",
//                     className: "btn btn-default"
//                 },
//                 track: {
//                     text: "Track!",
//                     closeModal: false,
//                     className: "btn btn-success"
//                 }
//             },

//             })
//             .then(name => {
//                 switch (name) {
//                     case "track" :

//                         return $.ajax({
//                         url: "{{url('/waybill-track-by-reference')}}",
//                         type : "POST",
//                         data: {_token : "{{csrf_token()}}", reference_no : data['reference_no']}
//                     });
//                     default:
//                     swal.close();
//                 }
//             })
//             .then(results => {
//                 return results;
//             })
//             .then(json => {
//                 if(json.type=='error'){
//                     const wrapper = document.createElement('div');
//                     wrapper.innerHTML = json.message;
//                     swal({
//                         title: json.title,
//                         content: wrapper,
//                         icon: 'error'
//                     });
//                 }else{
//                     swal({
//                         title: json.title,
//                         text: json.message,
//                         icon: 'success',
//                         timer: 5000,
//                         buttons: {}
//                     });
//                 }



//             })
//             .catch(err => {
//                 // if (err) {
//                 //     swal("Ooops!", "Network communication failed!", "error");
//                 // } else {
//                 //     swal.stopLoading();
//                 //     swal.close();
//                 // }
//             });


//         });

//     myTable.on('click','.delete',function(e){
//         $tr = $(this).closest('tr');
//         var data = myTable.row($tr).data();
//         swal({
//             title: "Are you sure?",
//             text: "Cancel this transaction!",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {

//             if (willDelete) {
//                 $.ajax({
//                     url: "{{url('/waybills')}}/"+data['reference_no'],
//                     type: "DELETE",
//                     data : { _token : "{{csrf_token()}}", reference_no : data['reference_no']},
//                     success: function(result){
//                         swal('Transaction has been cancelled', {
//                         icon: 'success',
//                         title: 'Success!'
//                         }).then(function(){
//                             if(result.type=="success"){
//                                 $tr.remove();
//                             }
//                         });
//                     }
//                 })

//             }

//         });
//         e.preventDefault();
//     })
//     myTable.on('click','.qrcode',function(){
//         $tr = $(this).closest('tr');
//         var data = myTable.rows($tr).data()[0];
//         $modalQRCode = $('#modal-qrcode');
//         $modalQRCode.find('.modal-title').html('<i class="ace-icon fa fa-qrcode bigger-130"></i> '+data['reference_no']);
//         if($modalQRCode.find('.qrcode').has('img')){
//             $modalQRCode.find('.qrcode').find('img').remove();
//         }
//         new QRCode($modalQRCode.find('.qrcode')[0],{text: "{{url('/waybills/printable-reference/')}}"+data['encryptedReference'],width:250,height:250});
//         $modalQRCode.modal('show');
//     })

//     $('.show-details-btn').on('click', function(e) {
//         e.preventDefault();
//         $(this).closest('tr').next().toggleClass('open');
//         $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
//     });

//     $('.delete').click(function(e){
//         var tr = $(this).closest('tr');
//         swal({
//             title: "Are you sure?",
//             text: "Cancel this transaction!",
//             icon: "warning",
//             buttons: true,
//             dangerMode: true,
//         })
//         .then((willDelete) => {

//             if (willDelete) {
//                 $.ajax({
//                     url: "{{url('/waybills')}}/"+e.currentTarget.id,
//                     type: "DELETE",
//                     data : { _token : "{{csrf_token()}}", reference_no : e.currentTarget.id},
//                     success: function(result){
//                         swal('Transaction has been cancelled', {
//                         icon: 'success',
//                         title: 'Success!'
//                         }).then(function(){
//                             if(result.type=="success"){
//                                 tr.remove();
//                             }
//                         });
//                     }
//                 })

//             }
//         });
//         e.preventDefault();
//     });
// })
