@extends('layouts.theme')

@section('css')
<link rel="stylesheet" href="{{asset('/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>
        <li>
            <a href="#">Maintenance</a>
        </li>
        <li>
            <a href="{{route('branches.edit',['id'=>$data->id])}}">View Branch</a>
        </li>

    </ul>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
            <div class="clearfix">
                <!-- <div class="pull-left alert alert-success no-margin alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>

                    <i class="ace-icon fa fa-umbrella bigger-120 blue"></i>
                    Click on the image below or on profile fields to edit them ...
                </div> -->

                <div class="pull-right">

                </div>

            </div>

            <div class="hr dotted"></div>

            <div>
                <div id="user-profile-1" class="user-profile row">
                    <div class="col-xs-12 col-sm-3 center">

                        <div>

                            <span class="profile-picture">
                                <!-- <img id="avatar" class="editable img-responsive" alt="Alex's Avatar" src="{{asset('/theme/images/avatars/profile-pic.jpg')}}" /> -->
                                <font size="50">
                                <i class="ace-icon fa fa-map green"></i>
                                </font>
                            </span>

                            <div class="space-4"></div>

                            <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                                <div class="inline position-relative">
                                    <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                                        <i class="ace-icon fa fa-circle light-green"></i>
                                        &nbsp;
                                        <span class="white">{{$data->name}} Branch</span>

                                    </a>


                                </div>

                            </div>
                            <br>{{$data->address}}
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-9 center">
                        <div class="form-horizontal">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-16">

                                    <li class="active">
                                        <a data-toggle="tab" href="#edit-basic">
                                            <i class="green ace-icon fa fa-map bigger-125"></i>
                                            Google Map
                                        </a>
                                    </li>

                                    <li class="">
                                        <a data-toggle="tab" href="#edit-password">
                                            <i class="blue ace-icon fa fa-mobile bigger-125"></i>
                                            Contact #
                                        </a>
                                    </li>

                                    <li>
                                        <a data-toggle="tab" href="#address-book">
                                            <i class="purple ace-icon fa fa-list bigger-125"></i>
                                            Schedules
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content profile-edit-tab-content">

                                    <div id="edit-basic" class="tab-pane in active">
                                        <iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{$data->google_maps_api}}" allowfullscreen></iframe>

                                    </div>

                                    <div id="edit-password" class="tab-pane">
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="clearfix">
                                                    <div class="pull-right tableTools-container-contact"></div>
                                                </div>


                                                <div>
                                                    <table id="table-contact" class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="center">
                                                                    <!-- <label class="pos-rel">
                                                                        <input type="checkbox" class="ace" />
                                                                        <span class="lbl"></span>
                                                                    </label> -->
                                                                </th>
                                                                <th>Contact #</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($data->branch_contact as $row)
                                                            <tr>
                                                                <td>
                                                                    {{$row->id}}
                                                                </td>
                                                                <td>{{$row->contact_no}}</td>
                                                                <td>
                                                                    <div class="action-buttons">
                                                                        <a class="green edit" href="#">
                                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                        </a>

                                                                        <a class="red delete" href="#">
                                                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                                        </a>
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

                                    <div id="address-book" class="tab-pane">
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="clearfix">
                                                    <div class="pull-right tableTools-container-schedule"></div>
                                                </div>


                                                <div>
                                                    <table id="table-schedule" class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="center">
                                                                    <!-- <label class="pos-rel">
                                                                        <input type="checkbox" class="ace" />
                                                                        <span class="lbl"></span>
                                                                    </label> -->
                                                                </th>
                                                                <th>Schedules</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($data->branch_schedule as $row)
                                                            <tr>
                                                                <td>
                                                                    {{$row->id}}
                                                                </td>
                                                                <td>{{$row->days_from==$row->days_to ? $row->days_from.' & Holidays' : $row->days_from.'-'.$row->days_to}}
                                                                    <span>{{date('h:i A',strtotime($row->time_from)).'-'.date('h:i A',strtotime($row->time_to))}}
                                                                </td>
                                                                <td>
                                                                    <div class="action-buttons">
                                                                        <a class="green edit" href="#">
                                                                            <i class="ace-icon fa fa-pencil bigger-130"></i>
                                                                        </a>

                                                                        <a class="red delete" href="#">
                                                                            <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                                        </a>
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
            </div>



    <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->

<div class="modal fade" id="modal-form-contact" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-mobile bigger-130"></i> CONTACT</h4>
            </div>
            <div class="modal-body">
                <form id="form-contact" class="form-horizontal">
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

                    <input type="hidden" name="id" id="id-contact">



                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Contact #</label>

                        <div class="col-sm-9">
                            <input type="text" id="contact_no" name="contact_no"  class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
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

<div class="modal fade" id="modal-form-schedule" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-list bigger-130"></i> SCHEDULE</h4>
            </div>
            <div class="modal-body">
                <form id="form-schedule" class="form-horizontal">
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

                    <input type="hidden" name="id" id="id-schedule">



                    <div class="form-group">
                        <label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Schedule days</label>

                        <div class="col-sm-4">
                            <select name="days_from" class="form-control">
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <select name="days_to" class="form-control">
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label no-padding-right" for="form-field-pass1">Schedule Time</label>

                        <div class="col-sm-4">
                            <input type="time" class="form-control" name="time_from">
                        </div>

                        <div class="col-sm-4">
                            <input type="time" class="form-control" name="time_to">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
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
@endsection

@section('plugins')
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('/theme/js/markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.hotkeys.index.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-wysiwyg.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>


<script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script>


@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        var table_contact = $('#table-contact').DataTable( {
            bAutoWidth: false,
            "aoColumns": [
                    null,null,
                { "bSortable": false }
            ],
            "aaSorting": [],
            select: {
                style: 'multi'
            },
            "searching" : false,
            "paging" : false
        } );

        var table_schedule = $('#table-schedule').DataTable( {
            bAutoWidth: false,
            "aoColumns": [
                    null,null,
                { "bSortable": false }
            ],
            "aaSorting": [],
            select: {
                style: 'multi'
            },
            "searching" : false,
            "paging" : false
        } );

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons( table_contact, {
            buttons: [
                {

                    "text": "<i class='fa fa-plus bigger-110 green'></i> <span class='hidden'>Create New</span>",
                    "className": "btn btn-white btn-primary btn-bold add-contact"
                },
                {
                    "text": "<i class='fa fa-trash bigger-110 red'></i> <span class='hidden'>Delete Branches</span>",
                    "className": "btn btn-white btn-primary btn-bold delete-contacts"
                },

                {
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "print",
                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    autoPrint: false,
                    message: 'This print was produced using the Print button for DataTables'
                }
            ]
        } );

        new $.fn.dataTable.Buttons( table_schedule, {
            buttons: [
                {

                    "text": "<i class='fa fa-plus bigger-110 green'></i> <span class='hidden'>Create New</span>",
                    "className": "btn btn-white btn-primary btn-bold add-schedule"
                },
                {
                    "text": "<i class='fa fa-trash bigger-110 red'></i> <span class='hidden'>Delete Branches</span>",
                    "className": "btn btn-white btn-primary btn-bold delete-schedules"
                },

                {
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold"
                },
                {
                    "extend": "print",
                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    autoPrint: false,
                    message: 'This print was produced using the Print button for DataTables'
                }
            ]
        } );

        table_contact.buttons().container().appendTo( $('.tableTools-container-contact') );
        table_schedule.buttons().container().appendTo( $('.tableTools-container-schedule') );

        $('.add-contact').click(function(){
            $('#id-contact').val('');
            $('#form-contact .message-container').attr('style','display:none');
            $('#modal-form-contact').modal('show');
        });

        $('.delete-contacts').click(function(){
            var data = table_contact.rows('.selected').data();
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
                            url: "{{url('/branches')}}/"+data[i][0],
                            type: "DELETE",
                            data : { _token : "{{csrf_token()}}", branchoffice_no : data[i][0]},
                            success: function(result){
                            }
                        })
                    }

                    swal('Contact(s) has been deleted', {
                        icon: 'success',
                        title: 'Success!'
                    }).then(function(){

                        table_contact.rows('.selected').remove().draw();

                    });


                  }
                });
            }
        });

        $('.add-schedule').click(function(){
            $('#id-schedule').val('');
            $('#form-schedule .message-container').attr('style','display:none');
            $('#modal-form-schedule').modal('show');
        });

        $('.delete-schedules').click(function(){
            var data = table_schedule.rows('.selected').data();
            if(data.length==0){
                swal('No selected item', {
                    icon: 'error',
                    title: 'Ooops!'
                });
            }else{
                swal({
                  title: "Are you sure?",
                  text: "Delete this schedule(s)!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {

                    for(var i=0; i<data.length; i++){

                        $.ajax({
                            url: "{{url('/branches')}}/"+data[i][0],
                            type: "DELETE",
                            data : { _token : "{{csrf_token()}}", id : data[i][0]},
                            success: function(result){
                            }
                        })
                    }

                    swal('Schedule(s) has been deleted', {
                        icon: 'success',
                        title: 'Success!'
                    }).then(function(){
                        // if(result.type=="success"){
                            table_schedule.rows('.selected').remove().draw();
                        // }
                    });


                  }
                });
            }
        });

        table_contact.on('click', '.edit', function(e) {
            $tr = $(this).closest('tr');
            var data = table_contact.row($tr).data();
            $('#form-contact #id-contact').val(data[0]);
            $('#form-contact input[name="contact_no"]').val(data[1]);
            $('#modal-form-contact').modal('show');
            e.preventDefault();
        });

        table_contact.on('click', '.delete', function(e) {
            $tr = $(this).closest('tr');
            var data = table_contact.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Delete this contact!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/branch-contact-delete')}}/"+data[0],
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}", id : data[0]},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                table_contact.row($tr).remove().draw();
                            }
                        });
                    }
                })

              }
            });
            e.preventDefault();
        });

        table_schedule.on('click', '.edit', function(e) {
            $tr = $(this).closest('tr');
            var data = table_schedule.row($tr).data();
            $('#form-schedule #id-schedule').val(data[0]);

            $.ajax({
                url: "{{url('/branch-schedule-show')}}/"+data[0],
                type: "GET",
                dataType : "JSON",
                success: function(result){
                    $('select[name="days_from"]').val(result['days_from']);
                    $('select[name="days_to"]').val(result['days_to']);
                    $('input[name="time_from"]').val(result['time_from']);
                    $('input[name="time_to"]').val(result['time_to']);
                }
            })
            $('#modal-form-schedule').modal('show');
            e.preventDefault();
        });

        table_schedule.on('click', '.delete', function(e) {
            $tr = $(this).closest('tr');
            var data = table_schedule.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Delete this schedule!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/branch-schedule-delete')}}/"+data[0],
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}", id : data[0]},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                table_schedule.row($tr).remove().draw();
                            }
                        });
                    }
                })

              }
            });
            e.preventDefault();
        });

        $('#form-contact').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                contact_no: {
                    required: true
                }
            },

            messages: {
                contact_no: {
                    required: "Contact # is required"

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
                form_data.append('branch_id',"{{$data->id}}");
                $('#form-contact .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $('#form-contact .submit').attr('disabled',true);
                $.ajax({
                    url: "{{route('branches.contact_no_store')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){

                        if(result.type=="success"){
                            $('#form-contact').trigger('reset');
                        }
                        $('#form-contact .message-container').removeAttr('style');
                        var type = result.type=='error' ? 'alert-danger' : 'alert-success';
                        var icon = result.type=='error' ? 'fa-times' : 'fa-check';
                        $('#form-contact .message-container .alert').addClass(type);
                        $('#form-contact .message-container .title').html('<i class="ace-icon fa '+icon+'"></i>'+result.title);
                        $('#form-contact .message-container .message').html(result.message);

                        $('#form-contact .submit').html('Save');
                        $('#form-contact .submit').removeAttr('disabled');

                        if($('#id-contact').val()==""){
                            table_contact.row.add([
                            result.data['id'],
                            result.data['contact_no'],
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

        $('#form-schedule').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                days_from: {
                    required: true
                },
                days_to: {
                    required: true
                },
                time_from: {
                    required: true
                },
                time_to: {
                    required: true
                }
            },

            messages: {
                days_from: {
                    required: "Schedule days(from) is required"
                },
                days_to: {
                    required: "Schedule days(to) is required"
                },
                time_from: {
                    required: "Schedule time(from) is required"
                },
                time_from: {
                    required: "Schedule time(to) is required"
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
                form_data.append('branch_id',"{{$data->id}}");
                $('#form-schedule .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $('#form-schedule .submit').attr('disabled',true);
                $.ajax({
                    url: "{{route('branches.schedule_store')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){

                        if(result.type=="success"){
                            $('#form-schedule').trigger('reset');
                        }
                        $('#form-schedule .message-container').removeAttr('style');
                        var type = result.type=='error' ? 'alert-danger' : 'alert-success';
                        var icon = result.type=='error' ? 'fa-times' : 'fa-check';
                        $('#form-schedule .message-container .alert').addClass(type);
                        $('#form-schedule .message-container .title').html('<i class="ace-icon fa '+icon+'"></i>'+result.title);
                        $('#form-schedule .message-container .message').html(result.message);

                        $('#form-schedule .submit').html('Save');
                        $('#form-schedule .submit').removeAttr('disabled');

                        if($('#id-schedule').val()==""){
                            table_schedule.row.add([
                            result.data['id'],
                            result.data['schedule'],
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

