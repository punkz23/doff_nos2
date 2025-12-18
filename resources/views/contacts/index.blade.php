@extends('layouts.gentelella')

@section('css')
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

<div class="booking-action-button pull-right">
    <a href="{{route('contacts.create')}}" class="btn btn-sm btn-success add-booking"><i class="ace-icon fa fa-plus"></i> CREATE NEW</a>
    <button class="btn btn-sm btn-primary upload_qr"><i class="ace-icon fa fa-qrcode"></i> UPLOAD QR CODE</button>
</div>

<div class="modal fade" id="modal-upload-qr-sc" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-qrcode bigger-130"></i> Upload QR Code</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12 input-group">
                        <input type="file" accept="image/*" id="sc-decode-file" />
                        <span class="input-group-btn" hidden>
                            <button class="btn-xs btn-danger sc-decode-remove pull-right" type="button"><i class="fa fa-refresh"></i> Clear</button>
                        </span>

                        <div hidden><canvas style="height:100x;width:100px;"  id="sc-decode-canvas"></canvas></div>
                        <p hidden><button id="sc-decode-btn" type="button">Upload</button></p>
                        <input id="sc_qr_code"   name="sc_qr_code"   type="hidden">
                        <input id="sc_qr_code_cid"   name="sc_qr_code_cid"   type="hidden">
                    </div>
                </div>

                <div class="form-group" >
                    <br><br>
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <h5><b>SHIPPER/CONSIGNEE INFORMATION</b></h5>

                    </div>
                </div>
                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12  qr_sc_name">
                        NAME:

                    </div>
                </div>
                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12 qr_sc_email">
                        EMAIL ADDRESS:

                    </div>
                </div>
                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12 qr_sc_sno">
                        CONTACT #:

                    </div>
                </div>

                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <br><h5><b>SHIPPER/CONSIGNEE ADDRESS</b></h5>

                    </div>
                </div>

                <div class="form-group" >

                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <table id="qr-code-caddress-table" width="100%" class="table table-striped table-bordered">

                            <tbody>



                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_qr_sc">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <ul class="nav nav-tabs" id="myTab">
            <li class="active">
                <a id="activated_contacts" data-toggle="tab" href="#">
                    <i class="green ace-icon fa fa-check bigger-120"></i>
                    Active

                </a>
            </li>

            <li>
                <a id="deactivated_contacts"  data-toggle="tab" href="#">
                    <i class="green ace-icon fa fa-ban bigger-120"></i>
                    Deactivated
                    <span style="background-color:red;" class="badge badge-success deactivated_count">0</span>
                </a>
            </li>

        </ul>
        <input type="hidden" id="current_tab" value="1" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12 col-md-12 col-xs-12">
        <table id="dynamic-table" style="table-layout: fixed;width:100%;" class="table table-striped table-bordered">
            <thead>
                <tr>

                    <th >Name <span  class="badge badge-success pull-right table_count">0</span></th>
                    <th>Default address</th>
                    <th id="td_text"></th>
                </tr>
            </thead>

            <tbody id="list_contacts">



            </tbody>
        </table>
    </div>
</div>

@endsection

@section('plugins')
<script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>

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
<script type="text/javascript" src="{{asset('/gentelella')}}/vendors/qr_code/qrcode.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($) {

        //UPLOADING QR CODE
        var hasImage = false;
        var imageData = null;

        var canvas_sc = $('#sc-decode-canvas')[0];
        var context_sc = canvas_sc.getContext('2d');
        var decodeResult_sc = $("#sc_qr_code");

        function resetDecoder() {
            hasImage = false;
            imageData = null;

            decodeResult=decodeResult_sc;
            decodeResult.val('').trigger('change');
        }

        function drawImage(src) {

            canvas=canvas_sc;
            context=context_sc;

            var img = new Image();

            img.crossOrigin = 'anonymous';

            img.onload = function () {
            var width = img.width;
            var height = img.height;
            var actualWidth = Math.min(960, width);
            var actualHeight = height * (actualWidth / width);

            hasImage = true;
            canvas.width = actualWidth;
            canvas.height = actualHeight;

            context.drawImage(img, 0, 40, width, height-40, 0, 0, actualWidth, actualHeight);

            imageData = context.getImageData(0, 0, actualWidth, actualHeight);
            $('#sc-decode-btn').trigger('click');
            };

            img.src = src;
        }

        $('#sc-decode-file').on('change', function (e) {
            var file = e.target.files[0];

            if (file) {
            resetDecoder();

            var reader = new FileReader();

            reader.onload = function (e) {
                drawImage(e.target.result);
            };

            reader.readAsDataURL(file);

            }
        });

        function getImageData() {

            canvas=canvas_sc;
            context=context_sc;


            imageData && context.putImageData(imageData, 0, 0);

            return imageData || context.getImageData(0, 0, canvas.width, canvas.height);
        }

        function getModuleSize(location, version) {
            var topLeft = location.topLeft;
            var topRight = location.topRight;
            var a = Math.abs(topRight.x - topLeft.x);
            var b = Math.abs(topRight.y - topLeft.y);
            var c = Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2));

            return c / (version * 4 + 17);
        }

        function markFinderPattern(x, y, moduleSize) {

            context=context_sc;

            context.fillStyle = '#00ff00';

            context.beginPath();
            context.arc(x, y, moduleSize * 0.75, 0, 2 * Math.PI);
            context.fill();
        }

        function markQRCodeArea(location, version) {

            context=context_sc;

            context.lineWidth = 2;
            context.strokeStyle = '#00ff00';

            context.beginPath();
            context.moveTo(location.topLeft.x, location.topLeft.y);
            context.lineTo(location.topRight.x, location.topRight.y);
            context.lineTo(location.bottomRight.x, location.bottomRight.y);
            context.lineTo(location.bottomLeft.x, location.bottomLeft.y);
            context.lineTo(location.topLeft.x, location.topLeft.y);
            context.stroke();

            var moduleSize = getModuleSize(location, version,sc);

            markFinderPattern(location.topLeftFinder.x, location.topLeftFinder.y, moduleSize);
            markFinderPattern(location.topRightFinder.x, location.topRightFinder.y, moduleSize);
            markFinderPattern(location.bottomLeftFinder.x, location.bottomLeftFinder.y, moduleSize);
        }

        $('#sc-decode-btn').on('click', function () {
            if (!hasImage) {
            return alert('NO IMAGE FOUND.');
            }

            var imageData = getImageData();
            var result = new QRCode.Decoder()
            .setOptions({ canOverwriteImage: false })
            .decode(imageData.data, imageData.width, imageData.height);

            if (result) {
            decodeResult.val(result.data).trigger('change');
            markQRCodeArea(result.location, result.version);
            } else {

            alert('ERROR.');
            }
        });

        $("#sc_qr_code").change(function(){
            user_id='{{ Auth::user()->contact_id }}';
            $("#sc_qr_code_cid").val('');
            if(this.value !='' ){

                jQuery.ajax({
                    url: "{{url('/get-qr-code-profile')}}/"+this.value,
                    method: 'get',
                    success: function(result){

                        var result = JSON.parse(result);
                        if(result.length > 0){

                            $.each(result,function(){
                                if( Number(this.qrcode_profile_status)==1 ){
                                cdata=this.contact;
                                if(user_id != cdata['contact_id'] ){

                                    cdata_add=this.qr_code_details;
                                    address_d='';
                                    $.each(cdata_add,function(){

                                       add_d=this.qr_code_profile_address;
                                       address_d +='<tr>'+
                                        '<td>'+add_d['full_address']+'</td>'+
                                        '</tr>';

                                    });

                                    $(".qr_sc_name").html('Name: <u>'+cdata['fileas']+'</u>');
                                    $(".qr_sc_email").html('Email Address: <u>'+cdata['email']+'</u>');
                                    $(".qr_sc_sno").html('Contact #: <u>'+cdata['contact_no']+'</u>');


                                    $("#qr-code-caddress-table tbody").html(address_d);

                                    $("#sc_qr_code_cid").val(cdata['contact_id']);
                                    document.getElementById('save_qr_sc').disabled=false;
                                }else{
                                    alert('Invalid QR Code.');
                                    clear_qr_form();
                                }

                                }else{
                                    alert('Sorry but QR Code has been deactivated.');
                                    clear_qr_form();


                                }

                            });
                    }else{
                        alert('Sorry QR Code not found.');

                        clear_qr_form();
                    }

                    }});

            }
            else{

                clear_qr_form();


            }
        });

        function clear_qr_form(){


            $(".qr_sc_email").html('Email Address: ');
            $(".qr_sc_name").html('Name:');
            $(".qr_sc_sno").html('Contact #: ');
            $("#qr-code-caddress-table tbody").html('');

            document.getElementById('save_qr_sc').disabled=true;
        }

        $(".sc-decode-remove").click(function(){
            $("#sc_qr_code").val('').trigger('change');
            $("#sc-decode-file").val('').trigger('change');
        });

        $('#save_qr_sc').click(function(){
            $.ajax({
                url : "{{url('/save-sc-qrcode')}}/"+$('#sc_qr_code_cid').val()+"/"+$('#sc_qr_code').val(),
                type: "GET",
                dataType: "JSON",
                success: function(result){

                    alert(result);
                    $(".sc-decode-remove").click();
                    $("#activated_contacts").click();

                }
            })
        });

        //initiate dataTables plugin

        $(".upload_qr").click(function(){

            $('#modal-upload-qr-sc').modal('show');
            $("#sc_qr_code").val('').trigger('change');
            $("#sc-decode-file").val('').trigger('change');


        });
        $("#activated_contacts").click(function(){
            document.getElementById('current_tab').value=1;
            list();
            count_deactivated();
            $("#td_text").html('');
        });

        $("#deactivated_contacts").click(function(){
            document.getElementById('current_tab').value=2;
            list();
            count_deactivated();
            update_view_deactivated();
            $("#td_text").html('Date Deactivated');
        });

        var myTable = $('#dynamic-table').DataTable();
        var isMobile = window.matchMedia("(max-width: 767px)").matches;

        function list(){

            // Clear table first
            $("#list_contacts").html("");
            $(".table_count").html("0");

            if(!isMobile){
                // ===============================
                // DESKTOP VIEW — USE DATATABLE
                // ===============================

                myTable.clear().destroy();

                myTable = $('#dynamic-table').DataTable({
                    ajax: {
                        url : "{{ url('/get-contacts') }}/" + $("#current_tab").val(),
                        type: "GET",
                    },
                    processing: true,
                    rowId: "contact_id",
                    language: {
                        'loadingRecords': '&nbsp;',
                        'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                    },
                    bInfo : false,
                    order: [[ 0, "asc" ]],
                    select: { style: 'multi' },

                    columns: [
                        {data : null,render(data,type){
                            return data['fileas']+(data['shipper_consignee']['default_customer'] == 1
                            ? '<br><small><font color="red">Default</font></small>' : '');
                        }},
                        {data : 'user_address',render(data,type){
                            if(typeof data[0] === "undefined") return '';
                            var a = data[0];
                            return (
                                (a['street'] ? a['street']+', ' : '')+
                                (a['sectorate_no'] != null ? a['sector']['barangay'] : a['barangay'])+', '+
                                a['city']+', '+a['province']+', '+a['postal_code']
                            ).toUpperCase();
                        }},
                        {data:null,render(data,type){
                            var html = "";
                            if($("#current_tab").val()==1){
                                html += '<a class="btn-xs green" href="'+"{{url('/contacts')}}/"+data['contact_id']+'/edit"><i class="ace-icon fa fa-pencil"></i> VIEW/ EDIT</a>';
                                html += '&nbsp;<a class="btn-xs red delete" href="#" id="'+data['contact_id']+'"><i class="ace-icon fa fa-user-times"></i> REMOVE</a>';
                                html += (data['shipper_consignee']['default_customer'] != 1
                                    ? '&nbsp;<a class="btn-xs blue set-default" href="#"><i class="ace-icon fa fa-check"></i> SET AS DEFAULT</a>'
                                    : '');
                            } else {
                                html += new Date(data['shipper_consignee']['date_deactivated']).toLocaleString('en-US');
                            }
                            return html;
                        }}
                    ],

                    initComplete: function(){
                        var count = $('#dynamic-table tr').length - 1;
                        $(".table_count").html(count);
                    }
                });

                return; // END DESKTOP
            }

            // ===============================
            // MOBILE VIEW — MANUAL RENDER
            // ===============================

            $.ajax({
                url : "{{ url('/get-contacts') }}/" + $("#current_tab").val(),
                type: "GET",
                success: function(res){

                    let arr = res.data ?? [];

                    $(".table_count").html(arr.length);

                    arr.forEach(function(data, index){

                        let address = "";
                        if(data.user_address[0]){
                            var a = data.user_address[0];
                            address = (
                                (a.street ? a.street + ", " : "") +
                                (a.sectorate_no != null ? a.sector.barangay : a.barangay) + ", " +
                                a.city + ", " + a.province + ", " + a.postal_code
                            ).toUpperCase();
                        }

                        let isDefault = data.shipper_consignee.default_customer == 1
                            ? `<small><span style="color:red;">Default</span></small>`
                            : "";

                        let bg = index % 2 == 0 ? `style="background:#f4f4f4;"` : "";

                        let actions = "";
                        if($("#current_tab").val()==1){
                            actions = `
                                <a class="btn-xs green" href="{{url('/contacts')}}/${data.contact_id}/edit">
                                    <i class="ace-icon fa fa-pencil"></i> VIEW/EDIT
                                </a>
                                <a class="btn-xs red delete" href="#" data-id="${data.contact_id}">
                                    <i class="ace-icon fa fa-user-times"></i> REMOVE
                                </a>
                                ${data.shipper_consignee.default_customer != 1
                                    ? `<a class="btn-xs blue set-default" href="#" data-id="${data.contact_id}">
                                        <i class="ace-icon fa fa-check"></i> SET DEFAULT
                                    </a>`
                                    : ""}
                            `;
                        } else {
                            actions = new Date(data.shipper_consignee.date_deactivated).toLocaleString('en-US');
                        }

                        let html = `
                            <tr ${bg}>
                                <td colspan="3">
                                    <div><strong>Name: </strong>${data.fileas} ${isDefault}</div>
                                    <div><strong>Address: </strong>${address}</div>
                                    <div style="margin-top:8px;">${actions}</div>
                                </td>
                            </tr>
                        `;

                        $("#list_contacts").append(html);
                    });

                }
            });
        }

        function count_deactivated(){

            $(".deactivated_count").hide();
            $(".deactivated_count").html('');

            $.ajax({
                    url: "{{url('/get-contacts-deactivated')}}",
                    type: "GET",
                    success: function(result){

                        $(".deactivated_count").html(result);
                        if( Number(result) > 0 ){
                            $(".deactivated_count").show();
                        }
                    }
            })
        }

        function update_view_deactivated(){
            $.ajax({
                    url: "{{url('/update-contacts-deactivated')}}",
                    type: "GET",
                    success: function(result){

                        count_deactivated();
                    }
            })
        }
        list();
        count_deactivated();

        myTable.on('click','.set-default',function(e){
                $tr = $(this).closest('tr');
                var data = myTable.row($tr).data();

                $.ajax({
                    url: "{{url('/contacts-default')}}/"+data['contact_id'],
                    type: "GET",
                    success: function(result){
                        alert(result.message);
                        myTable.ajax.url("{{ url('/get-contacts') }}").load();

                    }
                })
                //console.log(data);
            });




        $('.add-contact').click(function(){
            window.location.href="{{route('contacts.create')}}";
        });

        $('.delete-contacts').click(function(){
            var data = myTable.rows('.selected').data();
            if(data.length==0){
                swal('No selected item', {
                    icon: 'error',
                    title: 'Ooops!'
                });
            }else{
                swal({
                  title: "Are you sure?",
                  text: "Delete this contact(s)!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {

                    for(var i=0; i<data.length; i++){
                        $.ajax({
                            url: "{{url('/contacts')}}/"+data[i]['contact_id'],
                            type: "DELETE",
                            data : { _token : "{{csrf_token()}}", useraddress_no : data[i][0]},
                            success: function(result){
                            }
                        })
                    }

                    swal('Contact(s) has been deleted', {
                        icon: 'success',
                        title: 'Success!'
                    }).then(function(){
                        // if(result.type=="success"){
                            myTable.rows('.selected').remove().draw();
                        // }
                    });


                  }
                });
            }

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

        myTable.on('click', '.delete', function(e) {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Remove this contact!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/contacts')}}/"+data['contact_id'],
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}", reference_no : data['contact_id']},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                myTable.row($tr).remove().draw();
                            }
                        });
                    }
                })

              }
            });
            e.preventDefault();
        });
    })
</script>
@endsection
