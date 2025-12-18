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

@section('content')
<style>
    /* style.css */
      .container-otp {
          display: flex;
          justify-content: center;
          align-items: center;
          /* min-height: 100vh; */
      }

      .input-otp {
          width: 40px;
          border: none;
          border-bottom: 3px solid rgba(0, 0, 0, 0.5);
          margin: 0 10px;
          text-align: center;
          font-size: 36px;
          /* cursor: not-allowed; */
          /* pointer-events: none; */
      }

      .input-otp:focus {
          border-bottom: 3px solid orange;
          outline: none;
      }

      .input-otp:nth-child(1) {
          cursor: pointer;
          pointer-events: all;
      }

  </style>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
        <h2>Account Profile</h2>

        <div class="clearfix"></div>
        </div>
        <div class="x_content">

        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

            <div class="profile_img">

            <!-- end of image cropping -->
            <div id="crop-avatar">
                <!-- Current avatar -->
                <img class="avatar-view" src="{{asset('/images/default-avatar.png')}}" width="200px" height="200px" alt="Avatar"  title="Change the avatar">
                <!-- Loading state -->
                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
            </div>
            <!-- end of image cropping -->

            </div>
            <h3>{{Auth::user()->name}}</h3>

            <ul class="list-unstyled user_data">
            <li>
                <span id="change_email_span"><i class="fa fa-envelope user-profile-icon"></i> {{Auth::user()->email}}</span>
                <a data-em="email" data-toggle="modal" data-target=".change_email_mobile_modal" class="btn btn-success btn-xs change_email_mobile_modal_btn"><i class="fa fa-pencil"></i></a>
            </li>

            <li>
                <span id="change_mobile_span" ><i class="fa fa-mobile user-profile-icon"></i> {{Auth::user()->mobileNo}}</span>
                <a data-em="mobile" data-toggle="modal" data-target=".change_email_mobile_modal" class="btn btn-success btn-xs change_email_mobile_modal_btn"><i class="fa fa-pencil"></i></a>
            </li>
            <li class="pca_exp_date"></li>
            @has_doff_account
            <li>
                <i class="fa fa-link user-profile-icon"></i> {{Auth::user()->contact->doff_account_data->fileas}}
            </li>
            @endhas_doff_account
            @if($pca_details)
            <li>
                <i class="fa fa-money user-profile-icon"></i> Price (Main): {{ number_format($pca_details->publication_main_rate,2) }}<br>
                <i class="fa fa-money user-profile-icon"></i> Price (Tabloid): {{ number_format($pca_details->publication_tabloid_rate,2) }}
            </li>
            @endif
            </ul>
            <div style="width: 320px;height: 90px;padding: 10px;border: 1px solid #D1D5DC;margin: 0;" >
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form id="form-upload">
                        <input type="file" name="img_source"><br>
                    </form>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a style="width:100%;" class="btn btn-success btn-sm btn-upload"><i class="fa fa-edit"></i>Upload Photo</a>
                </div>
            </div>
            <br>

            @isset ( Auth::user()->contact->doff_account_data->charge_account )
            <!-- start skills -->
            <h4>Link Account</h4>
            <ul class="list-unstyled user_data">
            <li>
                <p>Credit Limit : {{number_format(Auth::user()->contact->doff_account_data->charge_account->creditlimit,2,'.',',')}}</p>
            </li>
            <li>
                <p>Terms : {{Auth::user()->contact->doff_account_data->charge_account->no_of_days}}</p>
            </li>
            </ul>
            <!-- end of skills -->
            @endisset
        </div>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Basic Info</a>
                </li>
                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Password</a>
                </li>
                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Address Book</a>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                <!-- start recent activity -->
                <form id="form-basic" class="form-horizontal form-label-left">

                    <div class="form-group">
                        <div class="col-sm-3 col-md-3 col-xs-12 no-padding-right">

                        </div>
                        <div class="col-sm-9 col-md-9 col-xs-12" style="text-align:left">
                            <div class="checkbox">
                                <label>
                                    <input name="use_company" type="checkbox" {{Auth::user()->contact->use_company==1 ? 'checked' : ''}} />
                                    <span class="lbl"> Use Company</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group name" {{Auth::user()->contact->use_company==1 ? 'style=display:none' : ''}}>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Lastname <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" name="lname" value="{{Auth::user()->contact->lname}}" {{Auth::user()->contact->use_company==0 ? 'required' : ''}} />
                        </div>
                    </div>

                    <div class="form-group name" {{Auth::user()->contact->use_company==1 ? 'style=display:none' : ''}}>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">First name : <font color="red">*</font></label>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" class="form-control" name="fname" value="{{Auth::user()->contact->fname}}" {{Auth::user()->contact->use_company==0 ? 'required' : ''}} />
                        </div>
                    </div>


                    <div class="form-group name" {{Auth::user()->contact->use_company==1 ? 'style=display:none' : ''}}>
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass2">Middle name</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="text" class="form-control" name="mname" value="{{Auth::user()->contact->mname}}"/>
                        </div>
                    </div>

                    <div class="form-group company" {{Auth::user()->contact->use_company==1 ? '' : 'style=display:none'}}>
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass2">Company : <font color="red">*</font></label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="text" class="form-control" name="company" value="{{Auth::user()->contact->company}}" {{Auth::user()->contact->use_company==1 ? 'required' : ''}}/>
                        </div>
                    </div>

                    <!--div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass2">Contact # : <font color="red">*</font></label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="number" class="form-control" name="contact_no" value="{{Auth::user()->contact->contact_no}}" required />
                        </div>
                    </div-->

                    <div class="form-group" hidden>
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Email Address : <font color="red">*</font></label>

                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}" required />
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass2">Business Type</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <select name="business_category_id" class="form-control">
                                <option selected disabled value="0">--Please select business type--</option>
                                @foreach($business_types as $row)
                                    <optgroup label="{{$row->businesstype_description}}">
                                        @foreach($row->business_type_category as $r)
                                            <option {{Auth::user()->contact->   business_category_id==$r->businesstype_category_id ? 'selected' : ''}} value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                            <div class="clearfix pull-right">
                                <button type="submit" class="btn btn-success submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end recent activity -->

                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

                <!-- start user projects -->
                <form id="form-password" class="form-horizontal form-label-left">
                    <!--div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Current Password</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="password" class="form-control" id="current_password" name="current_password" required/>
                        </div>
                    </div-->

                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">New Password</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="password" class="form-control" id="new_password" name="new_password" required />
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass2">Confirm Password</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                            <div class="clearfix pull-right">
                                <button type="submit" class="btn btn-success submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end user projects -->

                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                    <div>
                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                            </div>
                        </div>

                        <table id="dynamic-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace" />
                                            <span class="lbl"></span>
                                        </label>
                                    </th>
                                    <th>Label</th>
                                    <th>Address</th>

                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach(Auth::user()->user_address as $row)
                                    <tr class="{{$row->address_def==1 ? 'selected' : ''}}">
                                        <td class="center">
                                            {{$row->useraddress_no}}
                                        </td>
                                        <td>{{$row->address_caption}}</td>
                                        <td>{{$row->street!='' ? $row->street.', ' : ''}}{{$row->barangay!='' ? $row->barangay.', ' : ''}}{{$row->city!='' ? $row->city.', ' : ''}}{{$row->province!='' ? $row->province.', ' : ''}}{{$row->postal_code!='' ? $row->postal_code.', ' : ''}}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a class="green edit" title="Edit">
                                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                </a>
                                                <a class="blue set-default" title="Set Default"><i class="ace-icon fa fa-check bigger-130"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="modal-form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close form-address-close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-map-pin bigger-130"></i> ADDRESS</h4>
            </div>
            <div class="modal-body">
                <form id="form-address" class="form-horizontal">
                    <div class="message-container" style="display:none;">
                        <div class="alert">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>

                            <strong class="title">
                                <i class="ace-icon fa fa-times"></i>

                            </strong>

                            <font class="message"></font>
                            <br />
                        </div>
                    </div>
                    <input type="hidden" name="useraddress_no" id="useraddress_no">
                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Address Label :</label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="text" id="address_caption" name="address_caption"  class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Street/Bldg/Room : </label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="text" id="address_caption" name="street"  class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Barangay : <font color="red">*</font></label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="text" id="address_caption" name="barangay"  class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">City : <font color="red">*</font></label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <select name="city" class="form-control">
                                @foreach($provinces as $province)
                                        <optgroup label="{{$province->province_name}}">
                                            @foreach($province->city as $city)
                                                <option value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                                            @endforeach
                                        </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Province : </label>

                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="hidden" name="province">
                            <label id="province"></label>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-xs-12 control-label no-padding-right" for="form-field-pass1">Postal Code : </label>
                        <div class="col-sm-9 col-md-9 col-xs-12">
                            <input type="hidden" name="postal_code">
                            <label id="postal_code"></label>
                            <!-- <input type="number" id="postal_code" name="postal_code"  class="form-control" required/> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-md-12 col-sm-12">
                            <div class="clearfix pull-right">
                                <button type="submit" class="btn btn-success submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade change_email_mobile_modal"  role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" >
        <div class="modal-content">

            <div class="modal-header">

            <button type="button" class="close change_email_mobile_form_close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title change_email_mobile_modal_h4"> <i class="fa fa-envelope"></i> CHANGE EMAIL</h4>

            </div>
            <div class="modal-body">

                <form autocomplete="off" class="form-horizontal form-label-left change_email_mobile_form"  method="post" onkeyPress="return !(event.keyCode==13)">
                    @csrf

                    <div class="form-group row" >
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input  type="text" class="form-control col-md-12 col-xs-12"  id="change_email_mobile_input" name="change_email_mobile_input"   required />
                            <input  type="hidden"  id="change_email_mobile_type" name="change_email_mobile_type"  />
                            <center><b class="change_email_mobile_validation"></b></center>
                        </div>
                        <div id="div-otp-email-mobile" class="col-xs-12" style="text-align:left;">
                            <b id="div-otp-resend" style="text-align:left;"></b>
                            <h5 class="col-xs-12" id="h5-input-otp" ></h5>
                        </div>
                        <div class="col-xs-12 " id="div-otp" hidden>
                            <div class="col-xs-12 container-otp">
                            <div id="inputs-otp" class="inputs-otp">
                                <input style="width:40px;" id="input-digit1" class="input-otp" type="text"
                                    inputmode="numeric" maxlength="1" />
                                <input style="width:40px;" id="input-digit2" class="input-otp" type="text"
                                    inputmode="numeric" maxlength="1" />
                                <input style="width:40px;" id="input-digit3" class="input-otp" type="text"
                                    inputmode="numeric" maxlength="1" />
                                <input style="width:40px;" id="input-digit4" class="input-otp" type="text"
                                    inputmode="numeric" maxlength="1" />
                                <input style="width:40px;" id="input-digit5" class="input-otp" type="text"
                                    inputmode="numeric" maxlength="1" />
                            </div>
                            </div>
                        </div>
                        <center><b class="otp_validation"></b></center>
                    </div>

                    <div class="modal-footer " >
                        <div id="div_change_email_mobile_btn">
                        </div>
                        <div id="change_email_mobile_form_loading" class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('plugins')
<!-- page specific plugin scripts -->

<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-ui.custom.min.js')}}"></script>

<script src="{{asset('/theme/js/chosen.jquery.min.js')}}"></script>



<!-- <script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script> -->
<script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>

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
{{-- <script src="{{asset('/js/sweetalert.min.js')}}"></script> --}}
<script src="{{asset('/js')}}/sweetalert2.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">

    const inputs_otp = document.getElementById("inputs-otp");

    inputs_otp.addEventListener("input", function (e) {
        const target = e.target;
        const val = target.value;

        if (isNaN(val)) {
            target.value = "";
            return;
        }

        if (val != "") {
            const next = target.nextElementSibling;
            if (next) {
                next.focus();
            }
        }
    });

    inputs_otp.addEventListener("keyup", function (e) {
        const target = e.target;
        const key = e.key.toLowerCase();

        if (key == "backspace" || key == "delete") {
            target.value = "";
            const prev = target.previousElementSibling;
            if (prev) {
                prev.focus();
            }
            return;
        }
    });

    $(".change_email_mobile_modal_btn").click(function(){
        em=$(this).data('em');
        $("#change_email_mobile_type").val(em);
        $("#change_email_mobile_input").val('');
        $("#change_email_mobile_form_loading").hide();
        $(".otp_validation").html('');
        $(".input-otp").val('');
        $(".change_email_mobile_validation").html("");
        $("#change_email_mobile_input").prop('readonly',false);
        $("#div_change_email_mobile_btn").html('<button disabled id="change-em-in-btn" onclick="otp_send_func()" type="button" class="btn btn-default submit">Send One Time Verification Code</button>');
        document.getElementById('change_email_mobile_input').placeholder='Email';
        $(".change_email_mobile_modal_h4").html('<i class="fa fa-envelope"></i> CHANGE EMAIL');

        document.getElementById('div-otp').hidden=true;
        $('#div-otp-resend').html('');
        $("#h5-input-otp").html('');

        if(em=='mobile'){
            document.getElementById('change_email_mobile_input').placeholder='Mobile No.';
            $(".change_email_mobile_modal_h4").html('<i class="fa fa-mobile"></i> CHANGE MOBILE NO.');
        }
    });

    function validate_em_email(){

        $(".change_email_mobile_validation").html("");
        document.getElementById("change_email_mobile_input").style.border="";

        if( $("#change_email_mobile_type").val()=='mobile'){
            var mobileno = /^\d{11}$/;
            if( $("#change_email_mobile_input").val() !='' && !($("#change_email_mobile_input").val().match(mobileno)) ){
                $(".change_email_mobile_validation").html("<font color='red'>Invalid number. 11 digits is required (ex. 09XXXXXXXXX).<br></font>");
                document.getElementById("change_email_mobile_input").style.border="1px solid red";
                $(".submit").prop('disabled', true);
            }
            else if($("#change_email_mobile_input").val() !=''){
                $.ajax({
                    url: "/validate-email/"+'cno-'+btoa($("#change_email_mobile_input").val()),
                    method: 'get',
                    success: function(result){
                    if(Number(result)==1){
                        $(".change_email_mobile_validation").html("<font color='red'>Sorry mobile already registered.<br></font>");
                        document.getElementById("change_email_mobile_input").style.border="1px solid red";
                        $(".submit").prop('disabled', true);
                    }


                }});
            }
        }else{

            var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            if( $("#change_email_mobile_input").val() !='' && !document.getElementById('change_email_mobile_input').value.match(mailformat))
            {
                $(".change_email_mobile_validation").html("<font color='red'>Invalid email.<br></font>");
                document.getElementById("change_email_mobile_input").style.border="1px solid red";
                $(".submit").prop('disabled', true);
            }
            else if($("#change_email_mobile_input").val() !=''){
                $.ajax({
                    url: "/validate-email/"+$("#change_email_mobile_input").val(),
                    method: 'get',
                    success: function(result){
                    if(Number(result)==1){
                        $(".change_email_mobile_validation").html("<font color='red'>Sorry email's already registered.<br></font>");
                        document.getElementById("change_email_mobile_input").style.border="1px solid red";
                        $(".submit").prop('disabled', true);
                    }


                }});

            }
        }


    }
    function otp_send_func(){


        $(".otp_validation").html("");
        $('#change_email_mobile_input').prop('readonly',true);
        document.getElementById('div-otp').hidden=true;
        $('#div-otp-resend').html('');
        $("#h5-input-otp").html('');
        $(".input-otp").val('');


        $("#div_change_email_mobile_btn").html('<button disabled id="change-em-in-btn  type="submit" class="btn btn-danger submit"><i class="fa fa-check"></i>  Submit Changes</button>');

        email=$("#change_email_mobile_input").val();
        email_txt='Email';
        if( $("#change_email_mobile_type").val()=='mobile'){
            email_txt='Mobile No.';
        }
        $("#h5-input-otp").html('<center><br>Enter the code sent to your '+email_txt+' to proceed.</center>');
        $(".otp_validation").html('<br><div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait while sending ..</div>');
        $.ajax({
            url: "/send-reg-otp/"+btoa('change')+"/"+btoa($("#change_email_mobile_type").val())+"/"+btoa(email),
            method: 'get',
            success: function(result){

            if(result.type=='success'){

                $(".otp_validation").html("<br><font color='green'>"+result.message+"<br></font>");
                document.getElementById('div-otp').hidden=false;
                otp_resend_func(result.date_exp);
            }else{
                $(".otp_validation").html("<br><font color='red'>"+result.message+"<br></font>");
                $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a>');
            }

        }});


    }
    function otp_resend_func(date_exp){

        var timer;
        var compareDate = new Date(date_exp);

        timer = setInterval(function() {
        var dateEntered = compareDate;
        var now = new Date();
        var difference = dateEntered.getTime() - now.getTime();

        if (difference <= 0) {
            clearInterval(timer);
            $('#div-otp-resend').html('<a style="color:green;" onclick="otp_send_func()" >&emsp;<u>Resend Code</u></a>');

        } else {

            var seconds = Math.floor(difference / 1000);
            var minutes = Math.floor(seconds / 60);
            var hours = Math.floor(minutes / 60);
            var days = Math.floor(hours / 24);

            hours %= 24;
            minutes %= 60;
            seconds %= 60;
            $('#div-otp-resend').html('<a>&emsp;<u>Resend Code available in ('+(String(minutes).padStart(2, '0'))+':'+(String(seconds).padStart(2, '0'))+')</u></a>');
        }
        }, 1000);


    }

    function validate_em_otp(){

        var count_empty = $('.input-otp').not(function() { return this.value; }).length;

        if( count_empty == 0){
            $(".otp_validation").html('<br><div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Checking ..</div>');
            otp=$("#input-digit1").val()+$("#input-digit2").val()+$("#input-digit3").val()+$("#input-digit4").val()+$("#input-digit5").val();
            $(".submit").hide();
            $.ajax({
                url: "/validate-reg-otp/"+btoa($("input[name=change_email_mobile_input]").val())+'/'+btoa(otp),
                method: 'get',
                success: function(result){

                    if(Number(result)==2){
                        $(".otp_validation").html("<br><font color='red'><i class='fa fa-times'></i> OTP Expired.<br></font>");
                        $(".submit").prop('disabled', true);
                    }
                    else if(Number(result)==0){
                        $(".otp_validation").html("<br><font color='red'><i class='fa fa-times'></i> Invalid OTP.<br></font>");
                        $(".submit").prop('disabled', true);
                    }
                    else if(Number(result)==1){
                        $(".otp_validation").html("<br><h3><font color='green' style='font-weight:bold;'><image width='10%' height='10%' src='/images/check-green.gif' /> OTP Verified <br></font></h3>");
                        document.getElementById('div-otp').hidden=true;
                        document.getElementById('div-otp-email-mobile').hidden=true;
                    }
                    $(".submit").show();
            }});
        }

    }

    $(".input-otp").keyup(function(){
        validate_em_form();
    });
    $("#change_email_mobile_input").keyup(function(){
        validate_em_form();
    });
    function validate_em_form(){
        $(".submit").prop('disabled', false);
        var type = $("#change-em-in-btn").attr('type');
        var count_empty = $('.input-otp').not(function() { return this.value; }).length;
        if(
        $("#change_email_mobile_input").val() ==''
        || (type== 'submit' &&  count_empty > 0)
        ){
            $(".submit").prop('disabled', true);
        }
        validate_em_email();
        validate_em_otp();
    }
    $(".change_email_mobile_form").submit(function(){

        event.preventDefault();
        $("#change_email_mobile_form_loading").show();
        $("#change-em-in-btn").hide();

        $.ajax({
            url: "{{route('accounts.update_em')}}",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData:false,
            success: function(result)
            {
                swal(result.message,{
                    icon: 'success',
                    title: 'success'
                }).then(function(){
                    if(result.type == 1){
                        $(".change_email_mobile_form_close").click();
                        if( $("#change_email_mobile_type").val()=='mobile'){
                            $("#change_mobile_span").html('<i class="fa fa-mobile user-profile-icon"></i> '+$("#change_email_mobile_input").val());
                        }
                        else{
                            $("#change_email_span").html('<i class="fa fa-envelope user-profile-icon"></i> '+$("#change_email_mobile_input").val());
                        }
                    }
                });
                $("#change_email_mobile_form_loading").hide();
                $("#change-em-in-btn").show();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal("An error occured.",{
                    icon: 'error',
                    title: jqXHR.responseJSON.message,
                });
                $("#change_email_mobile_form_loading").hide();
                $("#change-em-in-btn").show();
            }
        });

    });

    jQuery(function($) {

        if(localStorage.getItem('useravatar')){
            $('.avatar-view')[0].src=localStorage.getItem('useravatar');
        }
        $('.btn-upload').click(function(e){
            e.preventDefault();
            $('#form-upload').submit();
        });

        $('#form-upload').on('submit',function(e){
            e.preventDefault();
            var ObjFileReader = new FileReader();
            ObjFileReader.readAsDataURL($('input[name="img_source"]')[0].files[0]);

            ObjFileReader.onload = function(obj){
                $('.avatar-view')[0].src = obj.target.result;
                swal({
                    title: "Are you sure?",
                    text: "Update your profile photo!",
                    icon: "warning",
                    buttons: {
                        cancel: "No",
                        catch: "Yes"
                    },
                    dangerMode: false,
                })
                .then((value) => {
                    if(value){
                        $.ajax({
                            url: "{{url('/upload-photo')}}",
                            type: "POST",
                            data : { _token : "{{csrf_token()}}", image : obj.target.result},
                            success: function(result){
                                $('.account-photo')[0].src = obj.target.result;
                                $('.account-photo')[1].src = obj.target.result;
                                localStorage.setItem('useravatar',obj.target.result);
                                $('#form-upload').trigger('reset');
                            }
                        })
                    }
                });


            }
        });

        $('input[name="img_source"]').on('change',function(e){
            e.preventDefault();

            var ObjFileReader = new FileReader();
            ObjFileReader.readAsDataURL($(this)[0].files[0]);

            ObjFileReader.onload = function(obj){
                $('.avatar-view')[0].src = obj.target.result;
            }
        });

        $('input[name="use_company"]').change(function(){
            // var form_parent = "#"+$(this).parents('form:first').attr('id');
            if($(this).is(':checked')==true){
                $('.name').attr('style','display:none');
                $('input[name="lname"]').removeAttr('required');
                $('input[name="fname"]').removeAttr('required');
                $('input[name="lname"]').val('');
                $('input[name="fname"]').val('');
                $('input[name="fname"]').val('');
                $('.company').removeAttr('style');
                $('input[name="company"]').attr('required',true);
                $('input[name="company"]').val("{{Auth::user()->contact->company}}");
            }else{
                $('.name').removeAttr('style');
                $('input[name="lname"]').attr('required',true);
                $('input[name="fname"]').attr('required',true);
                $('input[name="lname"]').val("{{Auth::user()->contact->lname}}");
                $('input[name="fname"]').val("{{Auth::user()->contact->fname}}");
                $('input[name="mname"]').val("{{Auth::user()->contact->mname}}");
                $('.company').attr('style','display:none');
                $('input[name="company"]').val("");
                $('input[name="company"]').removeAttr('required');
            }
        });

        //initiate dataTables plugin
        var myTable = $('#dynamic-table').DataTable( {
            bAutoWidth: false,
            bLengthChange: false,
            bInfo : false,
            paging: false,
            bFilter: false
        } );

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons( myTable, {
            buttons: [
                {
                    "text": "<i class='fa fa-plus bigger-110 green'></i> <span class='hidden'>Add Address</span>",
                    "className": "btn btn-white btn-primary btn-bold add-address"
                }
            ]
        } );
        myTable.buttons().container().appendTo( $('.tableTools-container') );

        $('.add-address').click(function(){


            $('#useraddress_no').val('');
            $('input[name="address_caption"]').val('');
            $('select[name="province"]').val('');
            $('#province').html('');
            $('select[name="city"]')[0].selectedIndex = 0;
            $('input[name="barangay"]').val('');
            $('input[name="street"]').val('');
            $('input[name="postal_code"]').val('');
            $('#postal_code').html('');
            $('#modal-form .modal-dialog modal-content .modal-header .modal-title').html('<i class="ace-icon fa fa-plus bigger-130"></i> ADD ADDRESS');
            $('#modal-form').modal('show');
        });

        // $('.edit').click(function(e){
        //     console.log(e);
        //     e.preventDefault();
        // });

        $('select[name="city"]').change(function(e){
            var province_for = 'province';
            var postal_for = 'postal_code';

            $.ajax({
            url : "{{url('/get-city-data')}}/"+$(this).val(),
            type: "GET",
            dataType: "JSON",
            success: function(result){
                var obj = result;
                $('input[name="'+province_for+'"]').val(obj['province']['province_name']);
                $('label#'+province_for).html(obj['province']['province_name']);
                $('input[name="'+postal_for+'"]').val(obj['postal_code']);
                $('label#'+postal_for).html(obj['postal_code']);
            }
            })
            e.preventDefault();
        })



        // Delete a record
        myTable.on('click', '.edit', function(e) {
            $tr = $(this).closest('tr');

            var data = myTable.row($tr).data();
            $('#useraddress_no').val(data[0]);
            $.ajax({
                url: "{{url('/get-useraddress')}}/"+data[0],
                type: "GET",
                dataType : "JSON",
                success: function(result){
                    var row = result;
                    $('input[name="address_caption"]').val(row['address_caption']);
                    $('input[name="barangay"]').val(row['barangay']);
                    $('input[name="province"]').val(row['province']);
                    $('#province').html(row['province']);
                    $('input[name="postal_code"]').val(row['postal_code']);
                    $('#postal_code').html(row['postal_code']);
                    $('select[name="city"] option:contains("'+row['city']+'")').attr('selected',true);
                    $('input[name="street"]').val(row['street']);
                    $('input[name="postal_code"]').val(row['postal_code']);

                }
            });
            // $('#modal-form .modal-dialog modal-content .modal-header .modal-title').html('<i class="ace-icon fa fa-refresh bigger-130"></i> UPDATE ADDRESS');

            $('#modal-form').modal('show');
            e.preventDefault();
        });

        $('.request-link').click(function(e){
            swal({
              title: "Are you sure?",
              text: "Continue request link account?",
              icon: "warning",
              buttons: true,
              dangerMode: false,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/request-link')}}",
                    type: "GET",
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        });
                    }
                })

              }
            });
            e.preventDefault();
        })

        myTable.on('click', '.set-default', function(e) {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Set this as default address!",
              icon: "warning",
              buttons: true,
              dangerMode: false,
            })
            .then((willDelete) => {
              if (willDelete) {
                $('#dynamic-table tbody tr.selected').removeClass('selected');

                $.ajax({
                    url: "{{url('/set-default')}}",
                    type: "POST",
                    data : { _token : "{{csrf_token()}}", useraddress_no : data[0]},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                // window.location.reload(true);
                                $tr.addClass('selected');
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



    $('#form-basic').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {

            email: {
                required: true,
                email:true
            },

            // contact_no: {
            //     required: true,
            //     number: true
            // }

        },

        messages: {

            email: {
                required: "Please provide a valid email.",
                email: "Please provide a valid email."
            },

            //contact_no: "Contact is required"

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

            $('#form-basic .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
            $('#form-basic .submit').attr('disabled',true);
            form_data.append('_token',"{{csrf_token()}}");
            $.ajax({
                url: "{{route('accounts.update_profile')}}",
                type: "POST",
                data: form_data,
                dataType: 'JSON',
                processData: false,
                contentType:false,
                cache:false,
                success: function(result){

                    if(result.type=="success"){
                        // $('#form-basic').trigger('reset');
                        $('#form-basic .submit').html('Save');
                        $('#form-basic .submit').removeAttr('disabled');
                    }

                    $.gritter.add({
                        title: result['title'],
                        text: result['message'],
                        class_name: 'gritter-'+result['type'],
                        time: 5000
                    });


                }
            });
            return false;
        },
        invalidHandler: function (form) {
        }
    });

    $('#form-password').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            password: {
                required: true
            },
            new_password: {
                required: true,
                equalTo: '#new_password_confirmation'
            },
        },

        messages: {
            password: "Password is required",

            new_password: {
                required: "New password is required",
                equalTo: "Mismatch password"
            }
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
            $('#form-password .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
            $('#form-password .submit').attr('disabled',true);
            $.ajax({
                url: "{{route('accounts.update_password')}}",
                type: "POST",
                data: form_data,
                dataType: 'JSON',
                processData: false,
                contentType:false,
                cache:false,
                success: function(result){

                    Swal.fire({
                        icon: result.type,
                        title: result.title,
                        text: result.message
                    }).then((res) => {

                        if(result.type=="success"){
                            $('#form-password').trigger('reset');
                        }
                        $('#form-password .submit').html('Save');
                        $('#form-password .submit').removeAttr('disabled');
                    });



                    // $.gritter.add({
                    //     title: result['title'],
                    //     text: result['message'],
                    //     class_name: 'gritter-'+result['type'],
                    //     time: 5000
                    // });
                }
            });
            return false;
        },
        invalidHandler: function (form) {
        }
    });

    $('select[name="province"]').change(function(){
        $.ajax({
            url: "{{url('/get-cities')}}/"+$(this).val(),
            type: 'GET',
            dataType: 'JSON',
            success: function(result){
                var obj = result;
                var innerHTML = '';
                for(var i=0; i<obj.length;i++){
                    innerHTML = innerHTML + "<option value='"+obj[i]['cities_id']+"'>"+obj[i]['cities_name']+"</option>";
                }
                $('select[name="city"]').html(innerHTML);
            }
        });
    });

    $('.modal-header .close').click(function(){
        $('.message-container').attr('style','display:none');
    });

    $('#form-address').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        ignore: "",
        rules: {
            address_label: {
                required: true
            },
            province: {
                required: true
            },
            city: {
                required: true
            },
            barangay: {
                required: true
            }
        },

        messages: {
           address_label: "Address label is required",
           province: "Province is required",
           city: "City is required",
           barangay: "Barangay is required"
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
            form_data.append('province',$('input[name="province"]').val());
            form_data.append('city',$('select[name="city"] option:selected').text());
            $('#form-address .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
            $('#form-address .submit').attr('disabled',true);
            $.ajax({
                url: "{{route('accounts.save_address')}}",
                type: "POST",
                data: form_data,
                dataType: 'JSON',
                processData: false,
                contentType:false,
                cache:false,
                success: function(result){

                    if(result.type=="success"){
                        $('#province').html('');
                        $('#postal_code').html('');
                        $('#form-address').trigger('reset');
                    }
                    $('.message-container').removeAttr('style');
                    var type = result.type=='error' ? 'alert-danger' : 'alert-success';
                    var icon = result.type=='error' ? 'fa-times' : 'fa-check';
                    $('.message-container .alert').addClass(type);
                    $('.message-container .title').html('<i class="ace-icon fa '+icon+'"></i> '+result.title);
                    $('.message-container .message').html(result.message);

                    $('#form-address .submit').html('Save');
                    $('#form-address .submit').removeAttr('disabled');

                    if($('#useraddress_no').val()==""){
                        myTable.row.add([
                        result.data['useraddress_no'],
                        result.data['address_caption'],
                        result.data['street']+', '+result.data['barangay']+', '+result.data['city']+', '+result.data['province']+', '+result.data['postal_code'],
                        '<div class="action-buttons"><a class="green edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-130"></i></a><a class="blue set-default" title="Set Default"><i class="ace-icon fa fa-check bigger-130"></i></a></div>'
                        ]).draw(false);
                    }


                }
            });
            return false;
        },
        invalidHandler: function (form) {
        }
    });


})
</script>
@endsection
