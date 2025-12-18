
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
<style>
	.modal {
	  overflow-y:auto;
	}


    .variants {
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .variants > div {
    margin-right: 5px;
    }

    .variants > div:last-of-type {
    margin-right: 0;
    }

    .btn-file {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .btn-file > input[type='file'] {
    display: none
    }

    .btn-file > label {
    font-size: 1rem;
    font-weight: 300;
    cursor: pointer;
    outline: 0;
    user-select: none;
    border-color: rgb(216, 216, 216) rgb(209, 209, 209) rgb(186, 186, 186);
    border-style: solid;
    border-radius: 4px;
    border-width: 1px;
    background-color: hsl(0, 0%, 100%);
    color: hsl(0, 0%, 29%);
    padding-left: 16px;
    padding-right: 16px;
    padding-top: 16px;
    padding-bottom: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .btn-file > label:hover {
    border-color: hsl(0, 0%, 21%);
    }

    .btn-file > label:active {
    background-color: hsl(0, 0%, 96%);
    }

    .btn-file > label > i {
    padding-right: 5px;
    }

    .file--upload > label {
    color: hsl(204, 86%, 53%);
    border-color: hsl(204, 86%, 53%);
    }

    .file--upload > label:hover {
    border-color: hsl(204, 86%, 53%);
    background-color: hsl(204, 86%, 96%);
    }

    .file--upload > label:active {
    background-color: hsl(204, 86%, 91%);
    }

    .file--uploading > label {
    color: hsl(48, 100%, 67%);
    border-color: hsl(48, 100%, 67%);
    }

    .file--uploading > label > i {
    animation: pulse 5s infinite;
    }

    .file--uploading > label:hover {
    border-color: hsl(48, 100%, 67%);
    background-color: hsl(48, 100%, 96%);
    }

    .file--uploading > label:active {
    background-color: hsl(48, 100%, 91%);
    }


</style>
<div class="row">
    <div class="col-xs-12">
        @if(
            (
                session('pca_atype') == 'internal'
                && !empty(array_intersect(['unpaid_transaction','ledger','deposit','transaction_list','agent'], session('pca_internal_access') ))
            )
            ||
            session('pca_no') == Auth::user()->contact_id
        )
            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab">
                    @if(
                        session('pca_no') == Auth::user()->contact_id
                        ||
                        ( session('pca_atype') == 'internal' && in_array("transaction_list", session('pca_internal_access')) )
                    )
                    <li data-pca_action="transacted" data-pca_no="{{ session('pca_no') }}" data-pc_type="{{ session('pca_pc') }}" class="active pca_transacted_list li_doff_transaction">
                        <a data-toggle="tab" href="#pca-unpaid-transaction">
                            <i class="green ace-icon fa fa-files-o bigger-120"></i>
                            @if( session('pca_pc') == 'publication')
                                Transaction
                            @else
                                Transacted List
                            @endif

                        </a>
                    </li>
                    @endif
                    @if(
                        !(session('pca_pc') == 'publication')
                        &&
                        (
                            session('pca_no') == Auth::user()->contact_id
                            ||
                            ( session('pca_atype') == 'internal' && in_array("unpaid_transaction", session('pca_internal_access')) )
                        )
                    )
                    <li data-pca_action="unpaid" id="pca_unpaid_transaction" data-pca_no="{{ session('pca_no') }}" data-pc_type="{{ session('pca_pc') }}" class="pca_unpaid_transaction li_doff_transaction">
                        <a data-toggle="tab" href="#pca-unpaid-transaction">
                            <i class="green ace-icon fa fa-files-o bigger-120"></i>
                            Unpaid Transaction
                        </a>
                    </li>
                    @endif
                    @if(
                        session('pca_no') == Auth::user()->contact_id
                        ||
                        ( session('pca_atype') == 'internal' && in_array("deposit", session('pca_internal_access')) )
                    )
                    <li data-pca_no="{{ session('pca_no') }}" class="pca_deposit li_doff_transaction" >
                        <a data-toggle="tab" href="#pca-deposit">
                            <i class="green ace-icon fa fa-money bigger-120"></i>
                            Deposit
                        </a>
                    </li>
                    @endif

                    @if(
                        session('pca_no') == Auth::user()->contact_id
                        ||
                        ( session('pca_atype') == 'internal' && in_array("ledger", session('pca_internal_access')) )
                    )
                    <li class="pca_view_ledger li_doff_transaction" data-pca_no="{{ session('pca_no') }}">
                        <a data-toggle="tab" href="#pca-ledger">
                            <i class="green ace-icon fa fa-list bigger-120"></i>
                            Ledger
                        </a>
                    </li>

                    @endif
                    @if(
                        session('pca_pc') == 'publication'
                        &&
                        (
                            session('pca_no') == Auth::user()->contact_id
                            ||
                            ( session('pca_atype') == 'internal' && in_array("agent", session('pca_internal_access')) )
                        )
                    )
                        <li class="pca_view_agents li_doff_transaction" data-pca_no="{{ session('pca_no') }}">
                            <a data-toggle="tab" href="#pca-agents">
                                <i class="green ace-icon fa fa-users bigger-120"></i>
                                Agents
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div id="pca-unpaid-transaction" class="div_doff_transaction tab-pane fade in active">
                        <br>
                        <div class="div_publication_transaction">
                            <input type="hidden" id="tab_pub_transction_ctab" value="add" />
                            <div class="btn-group">
                                <a type="button" data-tab="list"  class="btn btn-default btn-sm tab_pub_transction tab_pub_transction_list" ><small>LIST</small></a>
                                <a type="button"  data-tab="add"  class="btn btn-primary  btn-sm tab_pub_transction tab_pub_transction_add" ><small>ADD TRANSACTION</small></a>
                            </div>
                            <div id="div_publication_transaction_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                            <div class="div_pub_transaction div_pub_transaction_list">
                                <br><br>
                                <p>
                                    Month: <input id="filter_pub_dr_month" type="month" value="{{ date('Y-m') }}" max="{{ date('Y-m') }}"/>
                                    <button class="btn btn-xs btn-info filter_pub_dr_btn "><i class="fa fa-search"></i></button>
                                </p>
                                <table  style="table-layout: fixed;width:100%;" class="table table-striped table-bordered table_pub_dr">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Prepared Date</th>
                                            <th>Status</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_pub_dr"></tbody>
                                </table>
                            </div>
                            <div class="div_pub_transaction div_pub_transaction_add">
                                <div class="pull-right variants">
                                    <form class='upload_csv_dr_form btn-file file--upload' >
                                        @csrf
                                        <label for='input-file-pub'>
                                            <b><i class="fa fa-upload"></i> Upload CSV Delivery Receipt</b>
                                        </label>
                                        <input type="hidden" name="pub_no" value="{{ session('pca_no') }}" />
                                        <input accept=".xlsx, .xls, .csv" name="input_file" id="input-file-pub" type="file"  />
                                        <div hidden><button type="submit" id="upload_csv_dr_form_btn"></button></div>
                                    </form>
                                    <div class='btn-file file--upload publication_download_csv_template_btn'>
                                        <label>
                                            <b><i class="fa fa-download"></i> Download CSV Template</b>
                                        </label>
                                    </div>
                                </div>
                                <form autocomplete="off" class="form-horizontal form-label-left form_pub_transaction_add"  method="post" onkeyPress="return !(event.keyCode==13)">
                                    @csrf
                                    <input type="hidden" name="pub_transaction_pno" value="{{ session('pca_no') }}" />
                                    <br><br>
                                    <table ><tr><td>Issue Date:&emsp;</td><td><input required name="pub_transaction_add_date" class="form-control" type="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m') }}" /></td></tr></table>
                                    <br >
                                    <i><h5><i class="fa fa-lightbulb-o"></i> Note: Please drag the row if you want to sort.</h5></i>
                                    <table  style="table-layout: fixed;width:100%;" class="table table-striped table-bordered table-tbl_pub_add_transaction">
                                        <thead>
                                            <tr>
                                                <th>Agent</th>
                                                <th>Contact Person</th>
                                                <th>Contact Number</th>
                                                <th>Address</th>
                                                <th>Main</th>
                                                <th>Tabloid</th>
                                                <th width="5%"><button type="button" data-action="add" data-toggle="modal" data-target=".pub_transaction_add_agent_modal" class="btn btn-md btn-info pull-right pub_transaction_add_agent_modal_btn"><i class="fa fa-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody  id="tbl_pub_add_transaction"></tbody>
                                    </table>
                                    <button type="submit" id="form_pub_transaction_add_btn"  class="btn btn-md btn-success pull-right"><i class="fa fa-check"></i> Submit </button>

                                </form>
                            </div>
                        </div>
                        <div class="div_premium_booking_transaction">

                            <button  data-toggle="modal" data-pca_no="{{ session('pca_no') }}" data-action="apply" data-target=".wtax_breakdown_modal" class="btn btn-sm btn-primary pull-right wtax_breakdown_modal_btn" ><i class="fa fa-file"></i> Apply Witholding Tax</button>
                            <button  data-toggle="modal" data-pca_no="{{ session('pca_no') }}" data-action="view" data-target=".wtax_breakdown_modal" class="btn btn-sm btn-default pull-right wtax_breakdown_modal_btn" ><i class="fa fa-list"></i> View Witholding Tax Application</button>
                            <div id="div_filter_transacted">
                                <p>
                                    Month: <input id="filter_transacted_month" type="month" value="{{ date('Y-m') }}" max="{{ date('Y-m') }}"/>
                                    <button class="btn btn-xs btn-info search_filter_transacted"><i class="fa fa-search"></i></button>
                                </p>
                            </div>
                            <br>
                            <div id="pca-unpaid-transaction-table-loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                            <table id="pca-unpaid-transaction-table" style="table-layout: fixed;width:100%;" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Waybill/Reference</th>
                                        <th>Shipper</th>
                                        <th>Consignee</th>
                                        <th>Balance</th>
                                        <!--th></th-->
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div id="pca-deposit" class="div_doff_transaction tab-pane fade">
                        <br>
                        <div class="btn-group tab-pca-deposit">
                            <a type="button" data-tab="1" data-pca_no="{{ session('pca_no') }}" class="btn btn-primary btn-sm a_pca_deposit_tab a_pca_deposit_tab1" ><small>FOR VALIDATION</small></a>
                            <a type="button" data-tab="2" data-pca_no="{{ session('pca_no') }}" class="btn btn-default btn-sm a_pca_deposit_tab a_pca_deposit_tab2" ><small>POSTED</small></a>
                        </div>
                        <br><br>
                        <button type="button" data-toggle="modal" data-target=".pca_deposit_modal" class="btn btn-info btn-sm pca_deposit_modal_btn"><i class="fa fa-plus-circle"></i> ADD DEPOSIT</button>
                        <br><br>
                        <div id="pca-deposit-table-loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                        <table id="pca-deposit-table" style="table-layout: fixed;width:100%;" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>DOFF Bank Account Number</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="pca-deposit-tbl"></tbody>
                        </table>
                    </div>

                    <div id="pca-ledger" class="div_doff_transaction tab-pane fade">
                        <br>
                        <div class="clearfix">
                            <div class="pull-right tableTools-container-transacted"></div>
                        </div>
                        <br>
                        <div class="form-group" >
                            <h5>
                            <input type="hidden" id="ledger_pca_no"  />
                            <table>
                                <tr>
                                <td><br>
                                    <select class="form-control" id="pca_filter_ledger_date">
                                    <option value="today">TODAY</option>
                                    <option value="yesterday">YESTERDAY</option>
                                    <option value="last7">LAST 7 DAYS</option>
                                    <option value="last30">LAST 30 DAYS</option>
                                    <option value="custom">CUSTOM</option>
                                    </select>
                                </td>
                                <td class="td_pca_filter_ledger_range" width="2%"></td>
                                <td class="td_pca_filter_ledger_range">FROM:<br><input class="form-control"  id="pca_filter_ledger_from" type="date" min="{{ date('Y-m-d',strtotime(date('Y-m-d').' -6 months')) }}" value="{{ date('Y-m-d',strtotime(date('Y-m-d').' -6 months')) }}"  /></td>
                                <td class="td_pca_filter_ledger_range" width="2%"></td>
                                <td class="td_pca_filter_ledger_range">TO:<br><input  class="form-control"  id="pca_filter_ledger_to" type="date" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" /></td>
                                <td width="2%"></td>
                                <td><br><button style="height:38px;" class="btn btn-sm btn-success pca_filter_ledger_btn"><i class="fa fa-search"></i> SHOW</button></td>
                                </tr>
                            </table>
                            </h5>
                        </div>

                        <div class="form-group">
                            <div id="tbody_pca_ledger_details_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                            <button  class="btn btn-sm btn-default pca_print_ledger_btn pull-right"><i class="fa fa-print"></i> PRINT</button>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Particular</th>
                                        <th>Reference</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Running Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_pca_ledger_details"></tbody>
                            </table>
                        </div>

                    </div>

                    <div id="pca-agents" class="div_doff_transaction tab-pane fade">
                        <br>
                        <button  data-toggle="modal"  data-target=".sort_agent_modal" class="btn btn-sm btn-default pull-right sort_agent_modal_btn" ><i class="fa fa-sort-amount-asc"></i> Delivery Receipt Agent Sorting</button>
                        <button  data-toggle="modal"  data-target=".add_agent_modal" class="btn btn-sm btn-primary pull-right add_agent_modal_btn" ><i class="fa fa-plus"></i> Add Agent</button>
                        <div id="tbl_agent_list_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                        <table style="table-layout: fixed;width:100%;" class="table table-striped table-bordered table_agent_list">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Details</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="tbl_agent_list"></tbody>
                        </table>
                    </div>

                </div>
            </div>
        @endif


    </div>
</div>


<div class="modal fade pca_deposit_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close pca_deposit_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-plus-circle"></i> ADD DEPOSIT</h4>
            </div>
            <div class="modal-body">

                <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-label-left pca_deposit_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input type="hidden" name="pca_deposit_no" value="{{ session('pca_no') }}" />

                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">DOFF Bank Account Name:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required readonly  name="pca_deposit_account_name"  class="form-control"  />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">DOFF Bank Account No.:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required readonly  name="pca_deposit_account_no"  class="form-control"  />
                        </div>
                    </div>
                    <hr>
                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">
                            <button type="button" class="additional_deposit_btn btn btn-sm btn-primary pull-right "><i class="fa fa-plus"></i> Add Additional Deposit</button>
                        </label>
                    </div>
                    <div class="well form-group" style="border-color:#71717B;" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Deposit Date:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" type="date" name="pca_deposit_date" class="form-control"  />
                        </div>
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Reference No.</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input  required  type="text"  name="pca_deposit_ref_no" class="form-control"  />
                        </div>
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Amount</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required  step="any" type="number"  name="pca_deposit_amt" class="form-control" />
                            <input type="hidden"  name="pca_deposit_amt_min"  />
                        </div>
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Upload Proof of Deposit Slip: <br><i><small>(image only)</small></i> </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input accept="image/*" required type="file" name="pca_deposit_file" class="form-control" />
                        </div>
                    </div>
                    <div class="div_additional_deposit">

                    </div>
                    <div class="modal-footer " >
                        <button type="submit" id="pca_deposit_form_btn" class="btn btn-success "><i class="fa fa-pencil"></i> SUBMIT </button>
                        <div id="pca_deposit_form_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade wtax_breakdown_modal" role="dialog" aria-hidden="true" style="overflow: scroll !important;">
	<div class="modal-dialog modal-md modal-pca-wtax">
	  <div class="modal-content">

		<div class="modal-header">
		  <button type="button" class="wtax_breakdown_modal_close close" data-dismiss="modal"><span aria-hidden="true">×</span>
		  </button>
		  <h4 class="modal-title wtax_breakdown_modal_h4"> WITHOLDING TAX APPLICATION</h4>
		</div>
		<div class="modal-body">

            <form autocomplete="off" enctype="multipart/form-data" class="form-horizontal form-label-left wtax_breakdown_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                @csrf
                 <input type="hidden" name="pca_wtax_cno" value="{{ session('pca_no') }}" />
                <div  class="form-group view_wtax">
                    <table class="table table-bordered table_pca_wtax_breakdown" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Waybill #/s</th>
                                <th>Tax Code</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="fv_edit_wtax_breakdown"></tbody>
                    </table>

                </div>
                <div  class="apply_wtax well" >
				    <table><tr><td><i class="fa fa-upload"></i> Upload Witholding Tax <i><small>(image only)</small></i>:&emsp;</td><td><input  accept="image/*" required type="file" name="wtax_file" ></td></tr></table>
                </div>
                <div  class="form-group well apply_wtax">
                    <h5 ><b><i class="fa fa-list"></i> Waybill List</b></h5>
                    <table class="table table-bordered" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                <div class="col-md-12 input-group">
                                    <select name="wtax_waybill_selection" class="form-control select2 " style="width:100%;" >
                                        <option selected value="">--Select Waybill--</option>
                                    </select>
                                     <span class="input-group-btn">
                                        <button style="height:38px;" type="button"  class="btn btn-info btn-md wtax_waybill_selection_btn"><i class="fa fa-plus-circle"></i></button>
                                    </span>
                                </div>

                            </th>
                        </tr>
                        <tr>
                            <th>Waybill No.</th>
                            <th width="5%"></th>
                        </tr>
                        </thead>
                        <tbody id="tbl_wtax_waybill"></tbody>

                    </table>
                    <table class="table table-bordered" >
                        <thead>
                        <tr>
                            <th colspan="3">
                                <h4>Expanded Witholding Tax (EWTC):</h4>
                                <div class="col-md-6 ">
                                    <select class="form-control select2 wtax_tax_code" style="width:100%;" name="wtax_tax_code_ewtc" >
                                        <option selected value="">--Select Tax Code--</option>
                                    </select>
                                </div>
                                <div class="input-group col-md-6 ">
                                    <input  step="any" class="form-control col-lg-12 col-lg-12 wtax_tax_code_amt" placeholder="Amount" name="wtax_tax_code_amt_ewtc" type="number">
                                    <span class="input-group-btn">
                                        <button type="button"   onclick="wtax_tax_code_btn_func('ewtc')" class="btn btn-info btn-md"><i class="fa fa-plus-circle"></i></button>
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Tax Code</th>
                            <th>Amount</th>
                            <th width="5%"></th>
                        </tr>
                        </thead>
                        <tbody id="tbl_witholding_tax_breakdown_ewtc"></tbody>

                    </table>

                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h4>Business Tax:</h4>
                                    <div class="col-md-6 ">
                                        <select class="form-control select2 wtax_tax_code" style="width:100%;" name="wtax_tax_code_bt" >
                                            <option selected value="">--Select Tax Code--</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-md-6 ">
                                        <input  step="any" class="form-control col-lg-12 col-lg-12 wtax_tax_code_amt" placeholder="Amount" name="wtax_tax_code_amt_bt" type="number">
                                        <span class="input-group-btn">
                                            <button type="button"  onclick="wtax_tax_code_btn_func('bt')" class="btn btn-info btn-md"><i class="fa fa-plus-circle"></i></button>
                                        </span>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th>Tax Code</th>
                                <th>Amount</th>
                                <th width="5%" ></th>
                            </tr>

                        </thead>
                        <tbody id="tbl_witholding_tax_breakdown_bt"></tbody>

                    </table>
                    <table class="table">
                            <tr>
                                <th>TOTAL</th>
                                <th>
                                <input readonly  id="wtax_breakdown_total" style="text-align:right;border-color:transparent;width:100%;" class="form-control col-md-7 col-xs-12" value="0" min="0" step="any" type="number">
                                </th>
                            </tr>

                    </table>
                </div>

                <div hidden id="wtax_breakdown_form_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                <div hidden class="form-group td_edit_wtax_breakdown apply_wtax">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button id="witholding_tax_breakdown_submit" type="submit" class="btn btn-primary  pull-right">SUBMIT</button>
                    </div>
                </div>


			</form>

			<div class="modal-footer">


			</div>

		</div>
	  </div>
	</div>
</div>
<div class="modal fade view_pca_deposit_upload"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-file-o"></i> UPLOAD</h4>
            </div>
            <div class="modal-body">

                <div class="form-group" >
                    <image style="width:100%;height:100%;" id="view_pca_deposit_upload_file" />

                </div>
                <div class="modal-footer " >

                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade view_waybill"  role="dialog"   aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">WAYBILL DETAILS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">

                <table class="table table-striped ">
                    <tr style="background-color:#AED6F1;">
                        <td colspan="2">WAYBILL DETAILS</td>
                    </tr>
                    <tr >
                        <td id="view_waybill" colspan="2"></td>
                    </tr>
                    <tr>
                        <td id="view_customer_sname"></td>
                        <td id="view_customer_saddress"></td>
                    </tr>
                    <tr>
                        <td id="view_customer_cname"></td>
                        <td id="view_customer_caddress"></td>
                    </tr>
                    <tr id="tr_view_customer_chname">
                        <td id="view_customer_chname" colspan="2"></td>
                    </tr>
                    <tr id="tr_view_customer_delivery">
                        <td id="view_customer_delivery" colspan="2"></td>
                    </tr>
                    <tr id="tr_view_customer_pickup">
                        <td id="view_customer_pickup" colspan="2"></td>
                    </tr>
                </table>
                <table class="table table-striped ">
                    <tr style="background-color:#AED6F1;">
                        <td colspan="3">SHIPMENT DETAILS</td>
                    </tr>
                    <tr >
                        <td >Item Description</td>
                        <td >Item Unit</td>
                        <td >Cargo Type</td>
                    </tr>
                    <tr >
                        <td colspan="3"></td>
                    </tr>
                    <tbody id="view_shipment_details">

                    </tbody>
                    <tr >
                        <td colspan="3"></td>
                    </tr>
                    <tr style="background-color:#E5E7E9;" >
                        <td  id="tcargo_qty" style="display:none;"></td>
                        <td colspan="3" id="tcargo"></td>
                    </tr>
                </table>

                <table class="table table-striped table-bordered">
                    <tr style="background-color:#AED6F1;" >
                        <td colspan="12">BILL BREAKDOWN</td>
                    </tr>
                    <tr>
                        <td >Freight</td>
                        <td >Discount</td>
                        <td >Subtotal</td>
                        <td >Pick Up</td>
                        <td >Delivery</td>
                        <td >Other/s</td>
                        <td >Declared Amount</td>
                        <td >Insurance</td>
                        <td >BIR 2307</td>
                        <td >BIR 2306</td>
                        <td >VAT</td>
                        <td >Total Amount</td>
                    </tr>
                    <tbody id="tbl_bill">

                    </tbody>

                </table>




            </div>
            <div class="modal-footer" >

            </div>

        </div>
    </div>
</div>

<div class="modal fade add_agent_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close add_agent_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="add_agent_modal_h4"><i class="fa fa-plus-circle"></i> ADD AGENT</h4>
            </div>
            <div class="modal-body">

                <form autocomplete="off"  class="form-horizontal form-label-left add_agent_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input type="hidden" name="agent_pca_no" value="{{ session('pca_no') }}" />
                    <input type="hidden" name="agent_id"  />
                    <input type="hidden" name="agent_default_brgy"  />
                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Agent Name:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required  class="form-control"  name="agent_name" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Address:</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            City/Municipality: *
                            <select required  class="form-control select2" name="agent_city" >
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            Barangay: *
                            <select required  class="form-control select2" name="agent_brgy" >
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                           Street/Bldg/Room:
                            <input required   name="agent_street"   class="form-control"  />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label class="col-md-12 col-sm-12 col-xs-12" for="fname">Contact Person:</label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input required  name="agent_cperson" placeholder="Name"  class="form-control"  />
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <br><input required  name="agent_cperson_no"   placeholder="Contact No."  class="form-control"  />
                        </div>
                    </div>

                     <div id="add_agent_form_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                    <div class="modal-footer " >
                        <button type="submit" id="add_agent_form_btn" class="btn btn-success "><i class="fa fa-check"></i> SAVE </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade sort_agent_modal"  role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close sort_agent_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-sort-amount-asc"></i> DELIVERY RECEIPT AGENT SORTING</h4>
            </div>
            <div class="modal-body">

                <form autocomplete="off"  class="form-horizontal form-label-left sort_agent_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input type="hidden" name="agent_sorting_no" value="{{ session('pca_no') }}" />
                    <div class="form-group" >
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <i><h5><i class="fa fa-lightbulb-o"></i> Note: Please drag the row to sort.</h5></i><br>
                            <table id="table-agent-sort" class="table table-striped table-bordered">
                                <tbody id="sort_agent_list"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="sort_agent_list_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                    <div class="modal-footer " >
                        <button type="submit" id="sort_agent_form_btn" class="btn btn-success "><i class="fa fa-check"></i> SAVE </button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade view_dr_details_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:1200px;" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-list"></i> VIEW TRANSACTION</h4>
            </div>
            <div class="modal-body">
                <div id="view_dr_details_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                <div class="form-group" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p><b>Issue Date:</b> <u id="view_dr_issue_date"></u></p>
                        <small>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Agent</th>
                                    <th colspan="2">Qty</th>
                                    <th colspan="2">Confirmed</th>
                                    <th colspan="2">Delivered</th>
                                    <th colspan="2">Returned/Undelivered</th>
                                </tr>
                                <tr>
                                    <th>Main</th>
                                    <th>Tabloid</th>
                                    <th>Main</h>
                                    <th>Tabloid</th>
                                    <th>Main</th>
                                    <th>Tabloid</th>
                                    <th>Main</th>
                                    <th>Tabloid</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_dr_details"></tbody>
                        </table>
                        </small>
                    </div>
                </div>
                <div class="modal-footer " ></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade edit_dr_details_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:1200px;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close edit_dr_details_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> EDIT TRANSACTION</h4>
            </div>
            <div class="modal-body">
                <div id="edit_dr_details_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                <form autocomplete="off" class="form-horizontal form-label-left edit_dr_details_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf
                    <input name="dr_edit_id" type="hidden" />
                    <div class="form-group" >

                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <b>Issue Date:</b>
                            <input class="form-control" name="dr_edit_issue_date" type="date" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" />
                        </div>

                    </div>
                    <div class="form-group" >
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <i><h5><i class="fa fa-lightbulb-o"></i> Note: Please drag the row if you want to sort.</h5></i>
                            <table  class="table table-bordered table-tbl_dr_edit_details">
                                <thead>
                                    <tr>
                                        <th>Agent</th>
                                        <th>Contact Person</th>
                                        <th>Contact No.</th>
                                        <th>Address</th>
                                        <th>Main</th>
                                        <th>Tabloid</th>
                                        <th><button type="button" data-action="edit" data-toggle="modal" data-target=".pub_transaction_add_agent_modal" class="btn btn-md btn-info pull-right pub_transaction_add_agent_modal_btn"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_dr_edit_details"></tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer " >
                        <button type="submit" id="edit_dr_details_btn" class="btn btn-success "><i class="fa fa-check"></i> UPDATE </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade pub_transaction_add_agent_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">
                <button type="button"  class="close pub_transaction_add_agent_modal_close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> ADD AGENT</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" name="pub_transaction_add_agent_modal_action" value="add" />
                        <select required  class="form-control select2" name="pub_transaction_add_agent_name" >
                        </select>
                    </div>
                </div>
                <div id="pub_transaction_add_agent_modal_loading" class="spinner" style="text-align:center;" ><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                <div class="modal-footer " ><hr>
                    <button type="button" id="pub_transaction_add_agent_modal_btn" class="btn btn-success "><i class="fa fa-check"></i> ADD </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade view_proof_upload"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">

                <button type="button"  class="close " data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2"><i class="fa fa-file"></i> UPLOAD</h4>
            </div>
            <div class="modal-body">

                <div class="form-group div_view_proof" >
                </div>
                <div class="modal-footer " >

                </div>


            </div>
        </div>
    </div>
</div>

@endsection

@section('plugins')

<script src="{{asset('/js')}}/sweetalert2.js"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
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
<script src="{{asset('/js/transaction-other.js')}}"></script>
<script src="{{asset('/js/sort/js/jquery.tablednd.js')}}"></script>

@endsection


@section('scripts')

<script type="text/javascript">
@if(
    session('pca_no') == Auth::user()->contact_id
    ||
    ( session('pca_atype') == 'internal' && in_array("transaction_list", session('pca_internal_access')) )
)
    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();


    $(".pca_transacted_list").addClass('active').show();
    $("#pca-unpaid-transaction").addClass('in').show();
    $("#pca-unpaid-transaction").addClass('active').show();
    $('.pca_transacted_list').trigger('click');

@elseif(
    session('pca_no') == Auth::user()->contact_id
    ||
    ( session('pca_atype') == 'internal' && in_array("unpaid_transaction", session('pca_internal_access')) )
)

    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();


    $(".pca_unpaid_transaction").addClass('active').show();
    $("#pca-unpaid-transaction").addClass('in').show();
    $("#pca-unpaid-transaction").addClass('active').show();
    $('.pca_unpaid_transaction').trigger('click');

@elseif(
    session('pca_no') == Auth::user()->contact_id
    ||
    ( session('pca_atype') == 'internal' && in_array("deposit", session('pca_internal_access')) )
)
    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();
    $(".pca_deposit").addClass('active').show();
    $("#pca-deposit").addClass('in').show();
    $("#pca-deposit").addClass('active').show();
    $('.pca_deposit').trigger('click');

@elseif(
    session('pca_no') == Auth::user()->contact_id
    ||
    ( session('pca_atype') == 'internal' && in_array("ledger", session('pca_internal_access')) )
)
    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();
    $(".pca_view_ledger").addClass('active').show();
    $("#pca-ledger").addClass('in').show();
    $("#pca-ledger").addClass('active').show();
    $('.pca_view_ledger').trigger('click');

@elseif(
    session('pca_no') == Auth::user()->contact_id
    ||
    ( session('pca_atype') == 'internal' && in_array("agent", session('pca_internal_access')) )
)
    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();
    $(".pca_view_agents").addClass('active').show();
    $("#pca-agents").addClass('in').show();
    $("#pca-agents").addClass('active').show();
    $('.pca_view_agents').trigger('click');
@else

    $(".li_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('active').show();
    $(".div_doff_transaction").removeClass('in').show();
    $(".div_doff_transaction").hide();
@endif


</script>
@endsection
