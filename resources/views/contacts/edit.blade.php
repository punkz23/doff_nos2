@extends('layouts.gentelella')

@section('bread-crumbs')
<!--h3>
        Update Shipper/Consignee

</h3-->
@endsection

@section('css')

<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />

@endsection

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">

            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-pencil"></i> UPDATE SHIPPER/CONSIGNEE</h4>
                </div>

            </div>
            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" href="#basic">
                            <i class="green ace-icon fa fa-user bigger-120"></i>
                            Home
                        </a>
                    </li>

                    <li>
                        <a data-toggle="tab" id="address_book" href="#address-book">
                            <i class="green ace-icon fa fa-map-pin bigger-120"></i>
                            Address Book
                            <span class="badge badge-info"></span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="basic" class="tab-pane fade in active">
                        <div class="row">
                            <form class="form-horizontal visible" id="form-basic">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <div class="col-xs-12 col-sm-2 no-padding-right">

                                        </div>
                                        <div class="col-xs-12 col-sm-9">
                                            <div class="checkbox">
                                                <label>
                                                    <input {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'disabled' : '' }} name="use_company" type="checkbox" {{$data->use_company==1 ? 'checked' : ''}} class="ace" />
                                                    <span class="lbl"> Use Company</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 name" {{$data->use_company==1 ? 'style=display:none' : ''}}>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Lastname:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} type="text" name="lname" class="form-control" value="{{$data->lname}}" {{$data->use_company==1 ? '' : 'required'}}>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="space-2 name" {{$data->use_company==1 ? 'style=display:none' : ''}}></div>

                                <div class="col-xs-12 name" {{$data->use_company==1 ? 'style=display:none' : ''}}>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Firstname:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input type="text" {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} name="fname" class="form-control" value="{{$data->fname}}" {{$data->use_company==1 ? '' : 'required'}}>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="space-2 name" {{$data->use_company==1 ? 'style=display:none' : ''}}></div>

                                <div class="col-xs-12 name" {{$data->use_company==1 ? 'style=display:none' : ''}}>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Middlename:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input type="text" {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} name="mname" class="form-control" value="{{$data->mname}}" {{$data->use_company==1 ? '' : 'required'}}>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xs-12 company" {{$data->use_company==1 ? '' : 'style=display:none'}}>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Company:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} type="text" name="company" class="form-control" value="{{$data->company}}" {{$data->use_company==1 ? 'required' : ''}}>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="space-2 company" {{$data->use_company==1 ? '' : 'style="display:none"'}}></div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Email:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} type="email" name="email" class="form-control" value="{{$data->email}}">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="space-2"></div>

                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Contact #:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <input {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'readonly' : '' }} type="number" name="contact_no" class="form-control" value="{{$data->contact_no}}" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="space-2"></div>


                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Business Type:</label>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="clearfix">
                                                <select {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'disabled' : '' }} name="business_category_id" class="form-control select2">
                                                    <option selected value="0">--Please select--</option>
                                                    @foreach($business_types as $row)
                                                        <optgroup label="{{$row->businesstype_description}}">
                                                            @foreach($row->business_type_category as $r)
                                                                <option {{$data->businesstype_category_id==$r->businesstype_category_id ? 'selected' : ''}} value="{{$r->businesstype_category_id}}">{{$r->businesstype_category_description}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="col-xs-2" {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'hidden' : '' }} >
                                </div>
                                <div class="col-xs-9" {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'hidden' : '' }} >
                                    <div class="clearfix pull-right">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div id="address-book" class="tab-pane fade">

                        <div class="row">
                            <div class="col-xs-12">
                                <br>
                                <div class="clearfix">
                                    <div class="pull-right tableTools-container" {{ $data->shipper_consignee->qr_code !='' && $data->shipper_consignee->qr_code !=null  ? 'hidden' : '' }} >
                                        <button class="btn btn-white btn-primary btn-bold add-address"><i class='fa fa-plus bigger-110 green'></i> <span class='hidden'>Add Address</span></button>
                                    </div>
                                </div>
                                <br>

                                <div>
                                    <table width="100%" id="dynamic-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="center">
                                                    #
                                                </th>
                                                <th>Label</th>
                                                <th>Address</th>
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


                <div class="modal fade" id="modal-form" role="dialog">
                    <div class="modal-dialog modal-md">
                            <div class="modal-content ">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">Address Label :</label>

                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                <input type="text" id="address_caption" name="address_caption"  class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">Street/Bldg/Room : </label>

                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                <input type="text" onkeypress="return max_street(event)"  maxlength="100" id="street" name="street"  class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">City : <font color="red">*</font></label>

                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                <select name="city" class="form-control select2 cities">
                                                    @foreach($provinces as $province)
                                                            <optgroup label="{{$province->province_name}}">
                                                                @foreach($province->city as $city)
                                                                    <option value="{{$city->cities_id}}" data-province="{{$province->province_name}}" data-postal-code="{{$city->postal_code}}">{{$city->cities_name}}</option>
                                                                @endforeach
                                                            </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">Barangay : <font color="red">*</font></label>

                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                {{-- <input type="text" id="address_caption" name="barangay"  class="form-control" required/>
                                                 --}}
                                                 <select name="barangay" id="" class="form-control"></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">Province : </label>

                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                <input type="hidden" name="province">
                                                <label id="province"></label>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label class="col-sm-3 col-md-3  col-xs-12  control-label no-padding-right" for="form-field-pass1">Postal Code : </label>
                                            <div class="col-sm-9 col-md-9  col-xs-12 ">
                                                <input type="hidden" name="postal_code">
                                                <label id="postal_code"></label>
                                                <!-- <input type="number" id="postal_code" name="postal_code"  class="form-control" required/> -->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12 col-md-12  col-sm-12 ">
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
<!-- <script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script> -->
<script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>
<script src="/theme/js/sweetalert.min.js"></script>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($) {
        $('.select2').css('width','100%').select2({allowClear:true});

        $('select.cities').change(function(){
            var form_id = $(this).closest('form').attr('id');
            // var province_for = 'province';
            // var postal_for = 'postal_code';
            // $('#'+form_id+' input[name="'+province_for+'"]').val($(this).find('option:selected').data('province'));
            // $('#'+form_id+' label#'+province_for).html($(this).find('option:selected').data('province'));
            // $('#'+form_id+' input[name="'+postal_for+'"]').val($(this).find('option:selected').data('postal_code'));
            // $('#'+form_id+' label#'+postal_for).html($(this).find('option:selected').data('postal_code'));
            $select=$(this).closest('form').find('select[name="barangay"]');
            $('#province').html($(this).find('option:selected').data('province'));
            $('#postal_code').html($(this).find('option:selected').data('postal-code'));
            // $id = $(this).find('option:selected').data('postal-code');
            $id=$(this).val();
            $.ajax({
                url: "{{url('/get-sector')}}/"+$id,
                type: "GET",
                success: function(data){

                    $select.html('<option value="none" selected disabled>--Please select barangay--</option>');
                    $.each(data.data,function(){
                        $select.append('<option value="'+this.sectorate_no+'" data-sectorate_no="'+this.sectorate_no+'">'+this.barangay+'</option>');
                    });

                }
            });

        });

        $('input[name="use_company"]').change(function(){
            // var form_parent = "#"+$(this).parents('form:first').attr('id');
            if($(this).is(':checked')==true){
                $('.name').attr('style','display:none');
                $('input[name="lname"]').removeAttr('required');
                $('input[name="fname"]').removeAttr('required');
                $('input[name="mname"]').removeAttr('required');
                $('input[name="lname"]').val('');
                $('input[name="mname"]').val('');
                $('input[name="fname"]').val('');
                $('.company').removeAttr('style');
                $('input[name="company"]').attr('required',true);
                $('input[name="company"]').val("{{$data->company}}");
            }else{
                $('.name').removeAttr('style');
                $('input[name="lname"]').attr('required',true);
                $('input[name="fname"]').attr('required',true);
                $('input[name="mname"]').attr('required',true);
                $('input[name="lname"]').val("{{$data->lname}}");
                $('input[name="fname"]').val("{{$data->fname}}");
                $('input[name="mname"]').val("{{$data->mname}}");
                $('.company').attr('style','display:none');
                $('input[name="company"]').val("");
                $('input[name="company"]').removeAttr('required');
            }
        });

        //initiate dataTables plugin
        // var myTable = $('#dynamic-table').DataTable( {
        //     bAutoWidth: false,
        //     "aoColumns": [
        //         { "bSortable": false },
        //             null, null,
        //         { "bSortable": false }
        //     ],
        //     "aaSorting": [],
        //     select: {
        //         style: 'multi'
        //     }
        // } );
        $('#address_book').click(function(){
            $('#dynamic-table').DataTable().destroy();
            getAlladdress();
        });
        function getAlladdress(){

            var myTable = $('#dynamic-table').DataTable( {
                ajax:"{{ url('/get-alluseraddress/'.$id) }}",
                columns:[
                    {data:'useraddress_no'},
                    {
                        data:'address_caption',render (data,type){

                            return data.toUpperCase();
                        }


                    },
                    {
                        data:null,render (data,type){

                            return ( (data['street']!== null ?data['street']+', ' : '')+(data['sectorate_no']!=null ? data['sector']['barangay'] : data['barangay'])+', '+data['city']+', '+data['province']+', '+data['postal_code']).toUpperCase()+( data['address_def'] ==1 ? '<br><font color="red">Default</font>' : '' );
                        }
                    },
                    {
                        data:null,render (data,type){
                            btn='';
                            @if( $data->shipper_consignee->qr_code =='' || $data->shipper_consignee->qr_code ==null  ? 'readonly' : '' )
                                btn='<a class="btn btn-success btn-xs edit" title="Edit">'+
                                    '<i class="ace-icon fa fa-pencil bigger-130"></i> EDIT'+
                                    '</a>';

                                btn += data['address_def'] !=1 ? '<a class="btn btn-info btn-xs set-default" title="Set Default"><i class="ace-icon fa fa-check bigger-130"></i> SET AS DEFAULT</a>' :'';

                            @endif
                            return btn;
                        }
                    }
                ]
            } );



            // Delete a record
            myTable.on('click', '.edit', function(e) {
                $tr = $(this).closest('tr');

                var data = myTable.row($tr).data();
                $('input[name="address_caption"]').val(data['address_caption']);
                $('input[name="barangay"]').val(data['barangay']);
                $('input[name="province"]').val(data['province']);
                $('#province').html(data['province']);
                $('input[name="postal_code"]').val(data['postal_code']);
                $('#postal_code').html(data['postal_code']);
                $('select[name="city"] option:contains("'+data['city']+'")').attr('selected',true);
                $('.select2-selection__rendered').attr('title',data['city']);
                $('.select2-selection__rendered').html('<span class="select2-selection__clear">×</span>'+data['city']);
                $('input[name="street"]').val(data['street']);
                $('input[name="postal_code"]').val(data['postal_code']);
                $('#useraddress_no').val(data['useraddress_no']);
                // $.ajax({
                //     url: "{{url('/get-useraddress')}}/"+data[0],
                //     type: "GET",
                //     dataType : "JSON",
                //     beforeSend: function(){
                //         $('#form-address .submit').attr('disabled',true);
                //     },
                //     success: function(result){
                //         var row = result;
                //         $('input[name="address_caption"]').val(row['address_caption']);
                //         $('input[name="barangay"]').val(row['barangay']);
                //         $('input[name="province"]').val(row['province']);
                //         $('#province').html(row['province']);
                //         $('input[name="postal_code"]').val(row['postal_code']);
                //         $('#postal_code').html(row['postal_code']);
                //         $('select[name="city"] option:contains("'+row['city']+'")').attr('selected',true);
                //         $('.select2-selection__rendered').attr('title',row['city']);
                //         $('.select2-selection__rendered').html('<span class="select2-selection__clear">×</span>'+row['city']);
                //         $('input[name="street"]').val(row['street']);
                //         $('input[name="postal_code"]').val(row['postal_code']);
                //     },
                //     complete: function(){
                //         $('#form-address .submit').removeAttr('disabled');
                //     }
                // });
                // $('#modal-form .modal-dialog modal-content .modal-header .modal-title').html('<i class="ace-icon fa fa-refresh bigger-130"></i> UPDATE ADDRESS');
                $('#modal-form').modal('show');
                e.preventDefault();
            });

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
                        url: "{{url('/set-default/'.$id)}}",
                        type: "POST",
                        data : { _token : "{{csrf_token()}}", useraddress_no : data['useraddress_no']},
                        success: function(result){
                            swal(result.message, {
                            icon: result.type,
                            title: result.title
                            }).then(function(){
                                if(result.type=="success"){
                                    // window.location.reload(true);
                                    // $tr.addClass('selected');
                                    myTable.ajax.url("{{ url('/get-alluseraddress/'.$id) }}").load();

                                }
                            });

                        }
                    })

                }
                });
                e.preventDefault();
            });
        }
        getAlladdress();





        $('.add-address').click(function(){

            $('#useraddress_no').val('');
            $('input[name="address_caption"]').focus();
            $('input[name="address_caption"]').val('');
            $('input[name="province"]').val('');
            $('#province').html('');
            $('select[name="city"]')[0].selectedIndex = 0;
            $('input[name="barangay"]').val('');
            $('input[name="street"]').val('');
            $('input[name="postal_code"]').val('');
            $('#postal_code').html('');

            $('#modal-form .modal-dialog modal-content .modal-header .modal-title').html('<i class="ace-icon fa fa-plus bigger-130"></i> ADD ADDRESS');
            $('#modal-form').modal('show');
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

        // $('select[name="city"]').change(function(e){

        //     var province_for = 'province';
        //     var postal_for = 'postal_code';


        //     $.ajax({
        //     url : "{{url('/get-city-data')}}/"+$(this).val(),
        //     type: "GET",
        //     dataType: "JSON",
        //     success: function(result){
        //         var obj = result;
        //         $('input[name="'+province_for+'"]').val(obj['province']['province_name']);
        //         $('label#'+province_for).html(obj['province']['province_name']);
        //         $('input[name="'+postal_for+'"]').val(obj['postal_code']);
        //         $('label#'+postal_for).html(obj['postal_code']);
        //     }
        //     })
        //     e.preventDefault();
        // })

        $('.modal-header .close').click(function(){
            $('.message-container').attr('style','display:none');
        });

        $('#form-address').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                city: {
                    required: true
                },
                barangay: {
                    required: true
                }
            },

            messages: {
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
                form_data.append('contact_id',"{{$id}}");
                form_data.append('city',$('select[name="city"]')[0]['selectedOptions'][0].label);
                form_data.append('sectorate_no',$('select[name="barangay"]').find('option:selected').data('sectorate_no'));
                form_data.append('province',$('select[name="city"]').find('option:selected').data('province'));
                form_data.append('barangay',$('select[name="barangay"]').find('option:selected').text())
                $('#form-address .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $('#form-address .submit').attr('disabled',true);

                $.ajax({
                    url: "{{route('contacts.save_address')}}",
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
                        $('.message-container .title').html('<i class="ace-icon fa '+icon+'"></i>'+result.title);
                        $('.message-container .message').html(result.message);

                        $('#form-address .submit').html('Save');
                        $('#form-address .submit').removeAttr('disabled');

                        if($('#useraddress_no').val()==""){
                            myTable.row.add([
                            result.data['useraddress_no'],
                            result.data['address_caption'],
                            result.data['street']+', '+result.data['barangay']+', '+result.data['city']+', '+result.data['province']+', '+result.data['postal_code'],
                            '<div class="action-buttons"><a class="btn btn-success btn-xs edit" title="Edit"><i class="ace-icon fa fa-pencil bigger-130"></i></a><a class="btn btn-info btn-xs set-default" title="Set Default"><i class="ace-icon fa fa-check bigger-130"></i></a></div>'
                            ]).draw(false);
                        }
                        $(".edit").click();
                        $('#dynamic-table').DataTable().destroy();
                        getAlladdress();
                    }
                });
                return false;
            },
            invalidHandler: function (form) {
            }
        });












        jQuery.validator.addMethod("phone", function (value, element) {
            return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
        }, "Enter a valid phone number.");

        $('#form-basic').validate({
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

                email: {
                    email: "Please provide a valid email."
                },

                contact_no: "Contact is required",


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
                form_data.append('contact_id',"{{$id}}");
                $('#form-basic .submit').attr('disabled',true);
                $('#form-basic .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                $.ajax({
                    url: "{{url('/contact-update')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){

                        if(result.type=="success"){
                            $('#form').trigger('reset');
                            $('#form-basic .submit').html('Save');
                            $('#form-basic .submit').removeAttr('disabled');
                        }

                        // $.gritter.add({
                        //     // (string | mandatory) the heading of the notification
                        //     title: result['title'],
                        //     // (string | mandatory) the text inside the notification
                        //     text: result['message'],
                        //     class_name: 'gritter-'+result['type'],
                        //     time: 5000
                        // });

                        swal(result.message, {
                            icon: result.type,
                            title: result.title
                        });





                    }
                });
                return false;
            },
            invalidHandler: function (form) {
            }
        });







                /**
                $('#date').datepicker({autoclose:true}).on('changeDate', function(ev) {
                    $(this).closest('form').validate().element($(this));
                });

                $('#mychosen').chosen().on('change', function(ev) {
                    $(this).closest('form').validate().element($(this));
                });
                */


                $(document).one('ajaxloadstart.page', function(e) {
                    //in ajax mode, remove remaining elements before leaving page
                    $('[class*=select2]').remove();
                });
            });

            function max_street(evt) {

                if (evt.target.value.length >= 100) {
                    return false;
                }
                return true;
            }
        </script>
@endsection
