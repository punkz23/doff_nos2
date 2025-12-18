
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
        @if(
            ( session('pca_atype') == 'internal' && in_array("manage_account", session('pca_internal_access')) )
            ||
            session('pca_no') == Auth::user()->contact_id
        )
        <div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">

                @if( session('pca_no')==Auth::user()->contact_id )
                <li  data-tab="1" class="{{ session('pca_no')==Auth::user()->contact_id ? 'active' : '' }} pca_internal_external_accounts pca_internal_external_accounts1">
                    <a   data-toggle="tab" href="#pca-internal-external-accounts">
                        Internal
                    </a>
                </li>
                    @endif
                <li data-tab="2"  class="{{ session('pca_no')==Auth::user()->contact_id ? '' : 'active' }} pca_internal_external_accounts pca_internal_external_accounts2" >
                    <a  data-toggle="tab" href="#pca-internal-external-accounts">
                        External (Booking Only)
                    </a>
                </li>

			</ul>

			<div class="tab-content">
                <input type="hidden" name="pca-account-main-tab" value="1" />
                <input type="hidden" name="pca-account-sub-tab" value="1" />
				<div id="pca-internal-external-accounts" class="tab-pane fade in active">
                    <br>
                    <div class="btn-group">
                        <a type="button" data-tab="1"  class="btn btn-primary btn-sm a_pca_account_tab a_pca_account_tab1" ><small>ACTIVATE</small></a>
                        <a type="button" data-tab="2"  class="btn btn-default btn-sm a_pca_account_tab a_pca_account_tab2" ><small>DEACTIVATED</small></a>
                    </div>
                    <button type="button" data-toggle="modal" data-target=".pca_add_account_modal" class="btn btn-info btn-sm pca_add_account_modal_btn pull-right"><i class="fa fa-plus-circle"></i> ADD ACCOUNT</button>
                    <br><br>
                    <div id="pca-internal-external-accounts-table-loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                    <table id="pca-internal-external-accounts-table" style="table-layout: fixed;width:100%;" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Added By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
				</div>



			</div>
		</div>
        @endif


    </div>
</div>

<div class="modal fade pca_add_account_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close pca_add_account_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title pca_add_account_modal_h4" ><i class="fa fa-plus-circle"></i> ADD ACCOUNT</h4>
            </div>
            <div class="modal-body">

                <form  class="form-horizontal form-label-left pca_add_account_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input type="hidden" name="pca_ano" value="{{ session('pca_no') }}" />
                    <input type="hidden" name="pca_internal_external" value="1" />


                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Email:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required type="email" name="account_email" class="form-control" />
                        </div>

                    </div>
                    <div class="form-group div_pca_account_iname" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Name:</label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <input required placeholder="First Name" type="text" name="account_fname" class="form-control" />
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <input required placeholder="Middle Name" type="text" name="account_mname" class="form-control" />
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <input required placeholder="Last Name" type="text" name="account_lname" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group div_pca_account_ename" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Name:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input  readonly type="text" name="account_exist_name" class="form-control" />
                            <input  type="hidden" name="account_exist_cid" />
                        </div>
                    </div>
                    <div class="form-group div_pca_account_msg col-md-12 col-sm-12 col-xs-12" >

                    </div>

                    <div class="form-group div_pca_account_right" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Access:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <p><input class="pca_account_right" type="checkbox" value="booking" name="account_rights[]" /> Booking</p>
                            <p><input class="pca_account_right" type="checkbox" value="manage_account" name="account_rights[]"  /> Manage Account ( Add External Account Only)</p>
                            <p><input class="pca_account_right" type="checkbox" value="unpaid_transaction" name="account_rights[]" /> Unpaid Transaction</p>
                            <p><input class="pca_account_right" type="checkbox" value="ledger" name="account_rights[]"  /> Ledger</p>
                            <p><input class="pca_account_right" type="checkbox" value="deposit" name="account_rights[]" /> Deposit</p>
                        </div>

                    </div>

                    <div class="modal-footer " >
                        <button type="submit" id="pca_add_account_form_btn" class="btn btn-primary "><i class="fa fa-check"></i> SAVE </button>
                        <div id="pca_add_account_form_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade pca_edit_account_access_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title pca_edit_account_access_modal_h4" ><i class="fa fa-pencil"></i> EDIT INTERNAL ACCOUNT ACCESS</h4>
            </div>
            <div class="modal-body">

                <form  class="form-horizontal form-label-left pca_edit_account_access_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input type="hidden" name="pca_ano" value="{{ session('pca_no') }}" />
                    <input type="hidden" name="pca_internal_id"  />

                    <div class="form-group div_pca_account_right" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Access:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <p><input class="pca_edit_account_right pca_edit_account_right_booking" type="checkbox" value="booking" name="account_rights[]" /> Booking</p>
                            <p><input class="pca_edit_account_right pca_edit_account_right_manage_account" type="checkbox" value="manage_account" name="account_rights[]"  /> Manage Account ( Add External Account Only)</p>
                            <p><input class="pca_edit_account_right pca_edit_account_right_transaction_list" type="checkbox" value="transaction_list" name="account_rights[]" /> Transaction List</p>
                            @if(!(session('pca_pc') == 'publication'))
                            <p><input class="pca_edit_account_right pca_edit_account_right_unpaid_transaction" type="checkbox" value="unpaid_transaction" name="account_rights[]" /> Unpaid Transaction</p>
                            @endif
                            <p><input class="pca_edit_account_right pca_edit_account_right_ledger" type="checkbox" value="ledger" name="account_rights[]"  /> Ledger</p>
                            <p><input class="pca_edit_account_right pca_edit_account_right_deposit" type="checkbox" value="deposit" name="account_rights[]" /> Deposit</p>
                            @if(session('pca_pc') == 'publication')
                            <p><input class="pca_edit_account_right pca_edit_account_right_agent" type="checkbox" value="agent" name="account_rights[]" /> Agent List</p>
                            @endif

                        </div>

                    </div>

                    <div class="modal-footer " >
                        <button type="submit" id="pca_edit_account_access_form_btn" class="btn btn-primary "><i class="fa fa-check"></i> UPDATE </button>
                        <div id="pca_edit_account_access_form_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('plugins')

<script src="{{asset('/js')}}/sweetalert2.js"></script>
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

<script type="text/javascript">

$(".pca_add_account_modal_btn").click(function(){
    $('input[name="pca_internal_external"]').val($('input[name="pca-account-main-tab"]').val());
    $(".pca_account_right").prop('checked',false);
    $("#pca_add_account_form_loading").hide();
    $(".div_pca_account_ename").hide();
    $(".div_pca_account_iname").show();
    $('input[name="account_exist_name"]').val('');
    $('input[name="account_exist_cid"]').val('');
    $('input[name="account_fname"]').val('');
    $('input[name="account_mname"]').val('');
    $('input[name="account_lname"]').val('');
    $('input[name="account_email"]').val('');
    $('input[name="account_fname"]').prop('required',true);
    $('input[name="account_lname"]').prop('required',true);
    $('input[name="account_mname"]').prop('required',true);
    $(".div_pca_account_msg").html('');
    $(".div_pca_account_msg").hide();
    document.getElementById('pca_add_account_form_btn').disabled=false;
    if( Number($('input[name="pca_internal_external"]').val())==1 ){
        $(".pca_add_account_modal_h4").html('<i class="fa fa-plus-circle"></i> ADD INTERNAL ACCOUNT');
        $(".div_pca_account_right").show();
    }else{
        $(".pca_add_account_modal_h4").html('<i class="fa fa-plus-circle"></i> ADD EXTERNAL ACCOUNT');
        $(".div_pca_account_right").hide();
    }
});
$('input[name="account_email"]').keyup(function(){

    $('input[name="account_fname"]').prop('required',true);
    $('input[name="account_lname"]').prop('required',true);
    $('input[name="account_mname"]').prop('required',true);
    document.getElementById('pca_add_account_form_btn').disabled=false;
    $(".div_pca_account_ename").hide();
    $(".div_pca_account_iname").show();
    $(".div_pca_account_msg").hide();
    $(".div_pca_account_msg").html('');

    if( $('input[name="account_email"]').val() !='' ){

        $.ajax({
            url: "/pca-account-check-ie/"+btoa($('input[name="pca_ano"]').val())+'/'+btoa($('input[name="account_email"]').val()),
            type: "GET",
            dataType: "json",
            success: function(result){
                if(result.length > 0 ){
                    $.each(result,function(){
                        if( $('input[name="pca_ano"]').val() == this.contact_id ){
                            $(".div_pca_account_msg").html('<p style="color:red;"><i>*Sorry Invalid Email. Email already used by MAIN ACCOUNT.</i></p>');
                            document.getElementById('pca_add_account_form_btn').disabled=true;
                        }
                        else if(this.pca_internal_external_account_id != null && this.pca_internal_external_account_id !=''){
                            if(Number(this.internal_external)==1){
                                internal_external='INTERNAL';
                            }
                            else{
                                internal_external='EXTERNAL';
                            }
                            $(".div_pca_account_msg").html('<p style="color:red;"><i>*Sorry Invalid Email. Account already added as '+internal_external+' ACCOUNT.</i></p>');
                            document.getElementById('pca_add_account_form_btn').disabled=true;
                        }else{

                            $(".div_pca_account_msg").html('<p style="color:red;"><i>*Account already exist, name will be automatically supplied by system.</i></p>');
                            $('input[name="account_exist_name"]').val(this.fileas);
                            $('input[name="account_exist_cid"]').val(this.contact_id);

                            $('input[name="account_fname"]').prop('required',false);
                            $('input[name="account_lname"]').prop('required',false);
                            $('input[name="account_mname"]').prop('required',false);

                            $(".div_pca_account_ename").show();
                            $(".div_pca_account_iname").hide();

                        }
                        $(".div_pca_account_msg").show();
                    });
                }
            }, error: function(){
                swal({
                    icon: "error"
                });
            }
        });
    }

});
$(".pca_internal_external_accounts").click(function(){

    tab=$(this).data('tab');
    $('input[name="pca-account-main-tab"]').val(tab);
    get_pca_account_list();

});
$(".a_pca_account_tab").click(function(){

    tab=$(this).data('tab');
    $('input[name="pca-account-sub-tab"]').val(tab);

    $(".a_pca_account_tab").removeClass('btn-primary').addClass('btn-default').show();
    $(".a_pca_account_tab"+tab).removeClass('btn-default').addClass('btn-primary').show();
    get_pca_account_list();

});

$(".pca_add_account_form").submit(function(){

    event.preventDefault();
    document.getElementById("pca_add_account_form_btn").disabled=true;
    $("#pca_add_account_form_loading").show();
    if(
        document.querySelectorAll(".pca_account_right:checked").length ==0
        && Number($('input[name="pca_internal_external"]').val())==1
    ){
        alert('Please select at least 1 access.');
        document.getElementById("pca_add_account_form_btn").disabled=false;
        $("#pca_add_account_form_loading").hide();
    }
    else{
        $.ajax({
            url: "/pca-save-account",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData:false,
            success: function(result)
            {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message
                }).then((res) => {
                    get_pca_account_list();
                });
                document.getElementById("pca_add_account_form_btn").disabled=false;
                $("#pca_add_account_form_loading").hide();
                $(".pca_add_account_modal_close").click();

            },
            error: function(jqXHR, textStatus, errorThrown) {

                swal({
                    text:"An error occured. Please contact Customer Service.",
                    icon: 'error',
                    title: jqXHR.responseJSON.message,
                });
                document.getElementById("pca_add_account_form_btn").disabled=false;
                $("#pca_add_account_form_loading").hide();
            }
        });
    }

});
@if(
    ( session('pca_atype') == 'internal' && in_array("manage_account", session('pca_internal_access')) )
    ||
    session('pca_no') == Auth::user()->contact_id
)
    @if( session('pca_no') ==Auth::user()->contact_id )
        $(".pca_internal_external_accounts1").removeClass('active').show();
        $(".pca_internal_external_accounts2").removeClass('active').show();
        $(".pca_internal_external_accounts1").addClass('active').show();
        $(".pca_internal_external_accounts1").trigger('click');
    @else
        $(".pca_internal_external_accounts2").removeClass('active').show();
        $(".pca_internal_external_accounts1").removeClass('active').show();
        $(".pca_internal_external_accounts2").addClass('active').show();
        $(".pca_internal_external_accounts2").trigger('click');
    @endif
@endif
function get_pca_account_list(){

    $("#pca-internal-external-accounts-table-loading").show();
    $("#pca-internal-external-accounts-table tbody").html('');

    $.ajax({
        url: "/pca-account-list/"+btoa($('input[name="pca_ano"]').val())+
        '/'+btoa($('input[name="pca-account-main-tab"]').val())
        +'/'+btoa($('input[name="pca-account-sub-tab"]').val()),
        type: "GET",
        dataType: "json",
        success: function(result){

            $('#pca-internal-external-accounts-table').DataTable().destroy();
            $("#pca-internal-external-accounts-table-loading").show();
            $("#pca-internal-external-accounts-table tbody").html('');

            $.each(result,function(){
                btn='';
                if(Number(this.account_status)==1){
                    btn +='<button data-status="2" data-pca_no="'+$('input[name="pca_ano"]').val()+'" data-id="'+this.pca_internal_external_account_id+'" class="btn btn-xs btn-danger deactivate-pca-account" ><i class="fa fa-times"></i> DEACTIVATE</button>';
                }else{
                    btn +='<button data-status="1" data-pca_no="'+$('input[name="pca_ano"]').val()+'" data-id="'+this.pca_internal_external_account_id+'" class="btn btn-xs btn-success deactivate-pca-account" ><i class="fa fa-check"></i> ACTIVATE</button>';
                }
                if(Number(this.internal_external)==1 && Number(this.account_status)==1){
                    btn +='<button  data-toggle="modal" data-target=".pca_edit_account_access_modal" data-email="'+this.email+'"  data-id="'+this.pca_internal_external_account_id+'" class="btn btn-xs btn-primary access-pca-account" ><i class="fa fa-pencil"></i> EDIT ACCESS</button>';
                }
                $("#pca-internal-external-accounts-table tbody").append('<tr>'+
                '<td>'+this.fileas+'<br><small>'+this.email+'</small></td>'+
                '<td>'+this.added_by+'<br><small>'+this.added_date+'</small></td>'+
                '<td>'+btn+'</td>'+
                '</tr>');

            });
            $('#pca-internal-external-accounts-table').DataTable().draw();
            $("#pca-internal-external-accounts-table-loading").hide();

        }, error: function(){
            swal({
                icon: "error"
            });
            $("#pca-internal-external-accounts-table-loading").hide();
        }
    });
}

$(".pca_edit_account_access_form").submit(function(){

    event.preventDefault();

    document.getElementById("pca_edit_account_access_form_btn").disabled=true;
    $("#pca_edit_account_access_form_loading").show();
    if(
        document.querySelectorAll(".pca_edit_account_right:checked").length ==0
    ){
        alert('Please select at least 1 access.');
        document.getElementById("pca_edit_account_access_form_btn").disabled=false;
        $("#pca_edit_account_access_form_loading").hide();
    }
    else{
        $.ajax({
            url: "/pca-update-account-access",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData:false,
            success: function(result)
            {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message
                }).then((res) => {

                });
                document.getElementById("pca_edit_account_access_form_btn").disabled=false;
                $("#pca_edit_account_access_form_loading").hide();

            },
            error: function(jqXHR, textStatus, errorThrown) {

                swal({
                    text: "An error occured. Please contact Customer Service.",
                    icon: 'error',
                    title: jqXHR.responseJSON.message,
                });
                document.getElementById("pca_edit_account_access_form_btn").disabled=false;
                $("#pca_edit_account_access_form_loading").hide();
            }
        });
    }

});
$('#pca-internal-external-accounts-table').on('click', '.access-pca-account', function(){

    id=$(this).data('id');
    email=$(this).data('email');
    $(".pca_edit_account_access_modal_h4").html('<i class="fa fa-plus-circle"></i> EDIT INTERNAL ACCOUNT ACCESS<br>Email: '+email);
    $("#pca_edit_account_access_form_loading").hide();
    $('input[name="pca_internal_id"]').val(id);
    $(".pca_edit_account_right").prop('checked',false);

    $.ajax({
        url: "/pca-account-access/"+btoa(id),
        type: "GET",
        dataType: "json",
        success: function(result){

            $.each(result,function(){
                $(".pca_edit_account_right_"+this.access_name).prop('checked',true);
            });

        }, error: function(){
            swal({
                icon: "error"
            });
        }
    });

});
$('#pca-internal-external-accounts-table').on('click', '.deactivate-pca-account', function(){

    pca_no=$(this).data('pca_no');
    id=$(this).data('id');
    status=$(this).data('status');
    msg='ACTIVATE';
    if(Number(status)==2){
        msg='DEACTIVATE';
    }

    if(confirm('Are you sure you want to '+msg+' this?')){
        $.ajax({
            url: "/pca-deactivate-account/"+btoa(pca_no)+'/'+btoa(id)+'/'+btoa(status),
            type: "GET",
            dataType: "json",
            success: function(result)
            {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message
                }).then((res) => {
                    get_pca_account_list();
                })

            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal({
                    text:"An error occured. Please contact Customer Service.",
                    icon: 'error',
                    title: jqXHR.responseJSON.message,
                });
            }
        });
    }

});


</script>
@endsection
