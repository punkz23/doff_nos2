@extends('layouts.theme')

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>

        <li>
            <a href="{{route('waybills.index')}}">Maintenance</a>
        </li>
        <li class="active">Branches</li>
    </ul>
</div>

@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="dashboard_graph">
            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-pencil"></i> LIST OF BRANCHES</h4>
                </div>
                
            </div>   
         

            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>


            <div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Branch</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($branches as $row)
                            <tr>
                            <td>{{$row->id}}</td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->address}}</td>

                            <td>
                                <div class="action-buttons">

                                        <a class="blue" href="{{route('branches.edit',['id'=>$row->id])}}">
                                            <i class="ace-icon fa fa-eye bigger-130"></i>
                                        </a>

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

<div class="modal fade" id="modal-form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-map-pin bigger-130"></i> BRANCH</h4>
            </div>
            <div class="modal-body">
                <form id="form" class="form-horizontal">
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

                    <input type="hidden" name="id" id="id">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Location</label>

                        <div class="col-sm-9">
                            <select name="branch_filter_id" class="form-control select2">
                                @foreach($branch_filters as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Name</label>

                        <div class="col-sm-9">
                            <input type="text" id="name" name="name"  class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Address</label>

                        <div class="col-sm-9">
                            <input type="text" id="address" name="address"  class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Maps URL</label>

                        <div class="col-sm-9">
                            <textarea class="form-control" name="google_maps_api"></textarea>
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


<!-- page specific plugin scripts -->
<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>

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
    jQuery(function($) {
        //initiate dataTables plugin


        var myTable = $('#dynamic-table').DataTable( {
            bAutoWidth: false,
            "aoColumns": [
                    null, null,null,
                { "bSortable": false }
            ],
            "aaSorting": [],
            select: {
                style: 'multi'
            }
        } );

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons( myTable, {
            buttons: [
                {

                    "text": "<i class='fa fa-plus bigger-110 green'></i> <span class='hidden'>Create New</span>",
                    "className": "btn btn-white btn-primary btn-bold add-branch"
                },
                {
                    "text": "<i class='fa fa-trash bigger-110 red'></i> <span class='hidden'>Delete Branches</span>",
                    "className": "btn btn-white btn-primary btn-bold delete-branches"
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
        myTable.buttons().container().appendTo( $('.tableTools-container') );

        $('.add-branch').click(function(){
            // window.location.href="{{route('branches.create')}}";
            $('#id').val('');
            $('select[name="branch_filter_id"]').val('');
            $('input[name="name"]').val('');
            $('input[name="address"]').val('');
            $('textarea[name="google_maps_api"]').val('');
            $('#modal-form').modal('show');
        });

        $('.delete-branches').click(function(){
            var data = myTable.rows('.selected').data();
            if(data.length==0){
                swal('No selected item', {
                    icon: 'error',
                    title: 'Ooops!'
                });
            }else{
                swal({
                  title: "Are you sure?",
                  text: "Delete this branch(es)!",
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

                    swal('Branch(es) has been deleted', {
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

        myTable.on('click', '.edit', function(e) {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            $.ajax({
                url : "{{url('/branches')}}/"+data[0],
                type: "GET",
                dataType: "JSON",
                success: function(result){
                    var obj = result;
                    $('#id').val(data[0]);
                    $('select[name="branch_filter_id"]').val(obj['branch_filter_id']);
                    $('input[name="name"]').val(data[1]);
                    $('input[name="address"]').val(data[2]);
                    $('textarea[name="google_maps_api"]').val(obj['google_maps_api']);
                    $('#modal-form').modal('show');

                }
            });
            e.preventDefault();
        });

        myTable.on('click', '.delete', function(e) {
            $tr = $(this).closest('tr');
            var data = myTable.row($tr).data();
            swal({
              title: "Are you sure?",
              text: "Delete this branch!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/branches')}}/"+data[0],
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}", branchoffice_no : data[0]},
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

        $('#form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                branch_filter_id: {
                    required: true
                },
                name: {
                    required: true
                },
                address: {
                    required: true
                },
                google_maps_api: {
                    required: true
                }
            },

            messages: {
                branch_filter_id: {
                    required: "Location is required"

                },
                name: {
                    required: "Name is required",

                },
                address: {
                    required: "Address is required",

                },
                google_maps_api: {
                    required: "Google maps URL is required",

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
                $('#form .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $('#form .submit').attr('disabled',true);
                $.ajax({
                    url: "{{route('branches.store')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){

                        if(result.type=="success"){
                            $('#form').trigger('reset');
                        }
                        $('.message-container').removeAttr('style');
                        var type = result.type=='error' ? 'alert-danger' : 'alert-success';
                        var icon = result.type=='error' ? 'fa-times' : 'fa-check';
                        $('.message-container .alert').addClass(type);
                        $('.message-container .title').html('<i class="ace-icon fa '+icon+'"></i>'+result.title);
                        $('.message-container .message').html(result.message);

                        $('#form .submit').html('Save');
                        $('#form .submit').removeAttr('disabled');

                        if($('#branchoffice_no').val()==""){
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
