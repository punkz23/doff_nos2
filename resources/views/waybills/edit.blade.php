@extends('layouts.gentelella')

@section('css')
<link href="{{asset('/gentelella')}}/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link href="{{asset('/gentelella')}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/css/wizard.css')}}">
<script src="{{asset('/gentelella')}}/vendors/additional/jquery-1.9.1.js"></script>
<script src="{{asset('/gentelella')}}/vendors/additional/jquery-ui.js"></script>
<style>
    .swal-wide{
        width:850px !important;
    }
</style>
@endsection

@section('bread-crumbs')
<h3>Edit Online Booking</h3>
@endsection

@section('content')

<div id="wizard"  class="form_wizard wizard_horizontal">
<ul class="wizard_steps anchor">
    <li>
      <a href="#step1" class="step-link selected">
        <span class="step_no">1</span>
        <span class="step_descr">
            Step 1<br />
            <small>Shipper-Consignee</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step2" class="step-link disabled">
        <span class="step_no">2</span>
        <span class="step_descr">
            Step 2<br />
            <small>Booking Details</small>
        </span>
      </a>
    </li>
    <li>
      <a href="#step3" class="step-link disabled">
        <span class="step_no">3</span>
        <span class="step_descr">
            Step 3<br />
            <small>Shipments</small>
        </span>
      </a>
    </li>
    <li id="li_step4" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '' :  'hidden' ) : 'hidden' }} >
    <a href="#step4" class="step-link disabled">
        <span class="step_no step_no4">4</span>
        <span class="step_descr step_descr4">
            Step 4<br />
            <small>Convinience Fee</small>
        </span>
    </a>
    </li>
    <li>
        <a href="#step5" class="step-link disabled">
        <span class="step_no step_no5">{{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '5' :  '4' ) : '4' }}</span>
        <span class="step_descr step_descr5">
            Step {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? '5' :  '4' ) : '4' }}<br />
            <small>Terms and Condition</small>
        </span>
        </a>
    </li>

</ul>
<div class="container">
    <div id="step-1" class="content start selected">
    @include('waybills.steps.pages.logged_in.step1')
    </div>
    <div id="step-2" class="content unselect">
    @include('waybills.steps.pages.logged_in.step2')
    </div>
    <div id="step-3" class="content unselect">
    @include('waybills.steps.pages.logged_in.step3')
    </div>
    <div id="step-4" class="content unselect">
        @include('waybills.steps.pages.logged_in.stepcf')
    </div>
    <div id="step-5" class="content end unselect">
    @include('waybills.steps.pages.logged_in.step4')
    </div>

</div>
<div class="actionBar">
    <div style="width:75%;">
        <div class="msgBox">
            <div class="content"></div>
            <a href="#" class="close">X</a>
        </div>
        <div class="loader">
            Loading
        </div>
        <button class="buttonPrevious buttonDisabled btn btn-default">Previous</button>
        <button class="buttonNext btn btn-primary">Next</button>
        <button class="buttonFinish buttonDisabled btn btn-success">Finish</button>
    </div>
</div>
</div>

<div class="modal fade" id="modal-error" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:red;"> <i class="ace-icon fa fa-exclamation-triangle bigger-130"></i> Please check</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-note" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-file-text bigger-130"></i> Note</h4>
            </div>
            <div class="modal-body">
                <!--p style="text-align:justify">
                Before you enter the barangay where our Manila Branch is located, the barangay officials will facilitate two (2) queuing lines for our transactions, one of which is for those who’ve booked online. For accommodation, kindly present the online booking to our Manila Branch employee giving out the queuing numbers.
                </p-->
                <p style="text-align:justify">
                There will be separate lane to those who’ve booked online. However, it must be taken into account that our online booking service is not used for appointments, reservations or whatever of the sort as our transactions will be on a “first-come, first-serve” basis. Online booking is only valid for seven (7) days. We advised our customers who have opted to avail Lalamove or any other transport service providers to have their designated couriers comply with the queuing process from start to finish. Remind them not to leave your shipments behind if transaction is still on queue.
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary proceed submit">Proceed</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('plugins')
<!-- jQuery Smart Wizard -->
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
{{-- <script src="{{asset('/js/sweetalert.min.js')}}"></script> --}}
<script src="{{asset('/js')}}/sweetalert2.js"></script>
<script src="{{asset('/theme/js/jquery-ui.min.js')}}"></script>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // VARIABLES
            var default_table_data = {
                item_code: '',
                item_description : '',
                unit_no : 'BG',
                unit_description: 'BAG(S)',
                quantity: 0
            };
            $activeTR = null;
        // VARIABLES

        // Initialization of elements
        $('.select2').css('width','100%').select2({allowClear:true});

        function fill_datatable_items(filter_item_description=""){
            var tblItems = $('#table-items').DataTable({
                ajax: {
                    url : "{{ url('/stocks') }}",
                    type: "GET",
                    data: {
                        item_description : filter_item_description
                    },
                },
                processing: true,
                // serverSide : true,
                rowId: "stock_no",
                language: {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div>'
                },
                bFilter : false,
                paging : false,
                bLengthChange: false,
                bInfo : false,
                order: [[ 0, "asc" ]],
                columnDefs: [
                    {className: 'item-desc', targets : [0]}
                ],
                columns: [
                    {data : 'stock_description'}
                ]
            });

            tblItems.off('click','tr');

            tblItems.on('click','tr',function(e){
                e.preventDefault();
                $tr = $(this);
                var data = tblItems.row($tr).data();
                var temp = table.row($activeTR).data();
                temp['item_code']=data['stock_no'];
                temp['item_description']=data['stock_description'];
                table.row($activeTR).data(temp).invalidate();
                $tr.closest('.modal').find('.close').click();
                $activeTR=null;
                console.log(table.rows().data());
            });

        }

        var tblUnits = $('#table-units').DataTable({
            bFilter: true,
            paging : false,
            bLengthChange: false,
            bInfo : false,
            order: [[ 0, "asc" ]],
            columnDefs: [
                {className: 'item-desc', targets : [0]}
            ]
        });

        tblUnits.on('click','tr',function(e){
            e.preventDefault();
            $tr = $(this);
            var temp = table.row($activeTR).data();
            temp['unit_no']=$tr.attr('id');
            temp['unit_description']=$tr.find('td').eq(0)[0].innerHTML;
            table.row($activeTR).data(temp).invalidate();
            $tr.closest('.modal').find('.close').click();
            $activeTR=null;
        });

        // $('input[name="unit_description"]').on('keyup',function(e){
        //     console.log('test');
        //     // e.preventDefault();
        //     tblUnits.search($(this).val()).draw() ;
        // })

        var table = $('#datatable').DataTable({
            bInfo : false,
            bLengthChange: false,
            bFilter : false,
            paging: false,
            rowCallback: function(row,data){
                if(data['item_code']!=""){
                    $(row).find('td').eq(0).find('input').val(data['item_description']);
                }
                if(data['unit_no']!=""){
                    $(row).find('td').eq(1).find('input').val(data['unit_description']);
                }
            },
            columnDefs: [
                {width : '40%' , targets : [0]},
                {width : '30%' , targets : [1]},
                {width : '20%' , targets : [2]},
                {width : '10%' , targets : [2]}
            ],
            columns : [
                {data: null,render(data,type){
                    return  data['item_description']!="" ?
                            '<div class="input-group">'+
                                '<input type="text" class="form-control search-description" disabled value="'+data['item_description']+'">'+
                                '<span class="input-group-btn">'+
                                    '<button type="button" class="btn btn-info edit-item"><i class="fa fa-edit"></i></button>'+
                                '</span>'+
                            '</div>'
                            :
                            '<div class="input-group">'+
                                '<input type="text" class="form-control search-description" value="">'+
                                '<span class="input-group-btn">'+
                                    '<button type="button" class="btn btn-primary search-item"><i class="fa fa-search"></i></button>'+
                                '</span>'+
                            '</div>';
                }},
                {data: null,render(data,type){
                    return '<div class="input-group">'+
                                '<input type="text" class="form-control search-description" disabled value="'+data['unit_description']+'">'+
                                '<span class="input-group-btn">'+
                                    '<button type="button" class="btn btn-info edit-unit"><i class="fa fa-edit"></i></button>'+
                                '</span>'+
                            '</div>';
                }},
                {data: 'quantity',render(data){
                    return '<input type="number" class="form-control quantity" value="'+(data>0 ? data : '')+'">';
                }},
                {data : null, render(data,type,row,meta){
                    return parseInt(meta.row + meta.settings._iDisplayStart)>0  ? '<button class="btn btn-sm btn-danger row-delete"><i class="fa fa-trash"></i></button>' : '';
                }},
            ]
        });

        $('.add-item').off('click');

        $('.add-item').on('click',function(e){
            e.preventDefault();
            if(table.rows().count()>0 && table.rows().count()<5){
                $tr = $('#datatable tbody tr:last');
                var last_data = table.row($tr).data();
                if(last_data['item_code']!="" && last_data['unit_no']!="" && parseInt(last_data['quantity'])>0){
                    table.row.add({
                        item_id : null,
                        waybill_shipment_id : null,
                        item_code: '',
                        item_description : '',
                        unit_no : 'BG',
                        unit_description: 'BAG(S)',
                        quantity: 0,
                        freight_amount : 0,
                        weight: 0,
                        lenght: 0,
                        height: 0,
                        width: 0,
                        cargo_type_id: ''
                    }).draw(false);
                }else{
                    swal( {
                        text:"Please supply the last row data",
                        icon: 'error',
                        title: "Ooops!"
                    });
                }
            }else{
                if(table.rows().count()==0){
                    table.row.add({
                        item_id : null,
                        waybill_shipment_id : null,
                        item_code: '',
                        item_description : '',
                        unit_no : 'BG',
                        unit_description: 'BAG(S)',
                        quantity: 0,
                        freight_amount : 0,
                        weight: 0,
                        lenght: 0,
                        height: 0,
                        width: 0,
                        cargo_type_id: ''
                    }).draw(false);
                }
            }
        })

        table.rows().clear().draw();
        @foreach($data->waybill_shipment->toArray() as $ws)

            table.row.add({
                item_id : "{{$ws['item_id']}}",
                waybill_shipment_id : "{{$ws['waybill_shipment_id']}}",
                item_code : "{{$ws['item_code']}}",
                item_description : "{{$ws['item_description']}}",
                unit_no : "{{$ws['unit_no']}}",
                unit_description : "{{$ws['unit_description']}}",
                quantity : "{{$ws['quantity']}}",
                freight_amount : 0,
                weight: 0,
                lenght: 0,
                height: 0,
                width: 0,
                cargo_type_id: ''
            }).draw(false);
        @endforeach


        $('#form-search-item').on('submit',function(e){
            $('#table-items').DataTable().destroy();
            fill_datatable_items($(this).find('input[name="item_description"]').val());
            e.preventDefault();
        });

        table.off('click','.search-item');

        table.on('click','.search-item',function(){
            $this = $(this);
            $activeTR = $this.closest('tr');
            $input = $this.closest('.input-group').find('input');
            $modal = $('#modal-item');
            $modal.find('input[name="item_description"]').val($input.val());
            $('#table-items').DataTable().destroy();
            fill_datatable_items($input.val());
            $modal.modal('show');
        });

        table.on('click','.edit-item',function(){
            $this = $(this);
            $activeTR = $this.closest('tr');
            $modal = $('#modal-item');
            $modal.modal('show');
        });

        table.on('click','.row-delete',function(e){
            e.preventDefault();
            $tr = $(this).closest('tr');
            table.row($tr).remove().draw(false);
        });

        table.on('click','.edit-unit',function(e){
            $this = $(this);
            $activeTR = $this.closest('tr');
            $modal = $('#modal-unit');
            $modal.modal('show');
        })

        table.on('focusout','input.quantity',function(e){
            $tr = $(this).closest('tr');
            var temp = table.row($tr).data();
            temp['quantity']=$(this).val();
            table.row($tr).data(temp).invalidate();
        })

        // Initialization of elements

        // // Populate dropdown
        // $('.cities').change(function(){
        //     var form_id = $(this).closest('form').attr('id');
        //     $select=$(this).closest('div.new-contact').find('.barangay');
        //     $id=$(this).val();
        //     $.ajax({
        //         url: "{{url('/get-sector')}}/"+$id,
        //         type: "GET",
        //         success: function(data){
        //             $select.html('<option value="none" selected disabled>--Please select barangay--</option>');
        //             $.each(data.data,function(){
        //                 $select.append('<option value="'+this.sectorate_no+'" data-sectorate_no="'+this.sectorate_no+'">'+this.barangay+'</option>');
        //             });

        //         }
        //     });
        // });
        // // Populate dropdown

        // // ON CHANGE SHIPPER/CONSIGNEE
        // $('.contacts').on('change',function(e){
        //     var elementName = e.target.name=="shipper_id" ? "shipper" : "consignee";

        //     if(e.target.value!=="new"){
        //         if(e.target.value!="none"){
        //             var addresses = JSON.parse(e.target.selectedOptions[0].dataset.address);
        //             var innerHTML = '<option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option><option value="new">--ADD NEW--</option>';
        //             for(var i=0; i<addresses.length;i++){
        //                 innerHTML = innerHTML + "<option "+ @if(Route::currentRouteName() !='waybills.edit')
        //                 (addresses[i]['address_def'] ==1 ? 'selected' : '' ) @endif +" value='"+addresses[i]['useraddress_no']+"'>"+addresses[i]['full_address']+"</option>";
        //             }
        //         }
        //         var contact_numbers = $(this).find('option:selected').data('contact_numbers');

        //         if(contact_numbers!==undefined){

        //             var mobile_numbers = contact_numbers.filter(function(obj){ return obj.type==1; });
        //             var telephone_numbers = contact_numbers.filter(function(obj){ return obj.type==2; });

        //             if(mobile_numbers.length>0){
        //                 $('input[name="'+elementName+'_mobile_no[]"]').eq(0).val(mobile_numbers[0]['contact_no']);
        //             }
        //             if(mobile_numbers.length>1){
        //                 $table = $('input[name="'+elementName+'_mobile_no[]"]').closest('table');
        //                 $html = '<tr>'+
        //                             '<td>'+
        //                                 '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                                     '<input type="number" class="form-control mobile_no" name="'+elementName+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="'+mobile_numbers[1]['contact_no']+'">'+
        //                                     '<span class="input-group-btn">'+
        //                                         '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
        //                                     '</span>'+
        //                                 '</div>'+
        //                             '</td>'+
        //                         '</tr>';
        //                 $table.append($html);
        //             }

        //             if(telephone_numbers.length>0){
        //                 $('input[name="'+elementName+'_telephone_no[]"]').eq(0).val(telephone_numbers[0]['contact_no']);
        //             }
        //             if(telephone_numbers.length>1){
        //                 $table = $('input[name="'+elementName+'_telephone_no[]"]').closest('table');
        //                 $table = $(this).closest('table');
        //                 $html = '<tr>'+
        //                         '<td>'+
        //                             '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                                 '<input type="number" class="form-control telephone_no" name="'+elementName+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="'+telephone_numbers[1]['contact_no']+'">'+
        //                                 '<span class="input-group-btn">'+
        //                                     '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
        //                                 '</span>'+
        //                             '</div>'+
        //                         '</td>'+
        //                     '</tr>';
        //                 $table.append($html);
        //             }
        //         }
        //         $('select[name="'+elementName+'_address_id"]').html(innerHTML);
        //         $('input[name="'+elementName+'_contact_no"]').val(e.target.selectedOptions[0].dataset.contact_no)
        //         $('input[name="'+elementName+'_email"]').val(e.target.selectedOptions[0].dataset.email)
        //         $('select[name="'+elementName+'_address_id"]').closest('.form-group').show();
        //         $('input[name="'+elementName+'_email"]').closest('.form-group').show();
        //         $('input[name="'+elementName+'_contact_no"]').closest('.form-group').show();
        //         $('input[name="'+elementName+'_mobile_no[]"]').closest('.form-group').show();
        //         $('input[name="'+elementName+'_telephone_no[]"]').closest('.form-group').show();
        //         $('.'+elementName+'-form').hide();
        //         $('.'+elementName+'-info').hide();
        //         $('.'+elementName+'-address').hide();

        //     }else{
        //         $('select[name="'+elementName+'_address_id"]').closest('.form-group').hide();
        //         $('input[name="'+elementName+'_contact_no"]').closest('.form-group').hide();
        //         $('input[name="'+elementName+'_mobile_no[]"]').closest('.form-group').hide();
        //         $('input[name="'+elementName+'_telephone_no[]"]').closest('.form-group').hide();
        //         $('input[name="'+elementName+'_email"]').closest('.form-group').hide();
        //         $('.'+elementName+'-form').show();
        //         $('.'+elementName+'-info').show();
        //         $('.'+elementName+'-address').show();
        //     }
        //     var elementAddress = $('select[name="'+elementName+'_address_id"]').next().find('.select2-selection__rendered');
        //     elementAddress.removeAttr('title');
        //     elementAddress.empty();
        // });
        // // ON CHANGE SHIPPER/CONSIGNEE

        // // ON CHANGE SHIPPER/CONSIGNEE ADDRESS
        // $('.addresses').on('change',function(e){
        //     var elementName = e.target.name=="shipper_address_id" ? "shipper" : "consignee";
        //     if(e.target.value!=="new"){
        //         $('.'+elementName+'-form').hide();
        //         $('.'+elementName+'-info').hide();
        //         $('.'+elementName+'-address').hide();
        //     }else{
        //         $('.'+elementName+'-form').show();
        //         $('.'+elementName+'-info').hide();
        //         $('.'+elementName+'-address').show();
        //     }
        // })
        // // ON CHANGE SHIPPER/CONSIGNEE ADDRESS

        // // ON CHANGE CHECKBOX USE COMPANY
        // $('.use_company').on('change',function(e){
        //     var parentEl = e.target.name=="shipper.use_company" ? "shipper" : "consignee";
        //     $('input[name="'+parentEl+'.lname"]').val('');
        //     $('input[name="'+parentEl+'.fname"]').val('');
        //     $('input[name="'+parentEl+'.mname"]').val('');
        //     $('input[name="'+parentEl+'.company"]').val('');
        //     if($(this).is(':checked')==true){
        //         $('input[name="'+parentEl+'.lname"]').closest('.form-group').hide();
        //         $('input[name="'+parentEl+'.fname"]').closest('.form-group').hide();
        //         $('input[name="'+parentEl+'.mname"]').closest('.form-group').hide();
        //         $('input[name="'+parentEl+'.company"]').closest('.form-group').show();
        //     }else{
        //         $('input[name="'+parentEl+'.lname"]').closest('.form-group').show();
        //         $('input[name="'+parentEl+'.fname"]').closest('.form-group').show();
        //         $('input[name="'+parentEl+'.mname"]').closest('.form-group').show();
        //         $('input[name="'+parentEl+'.company"]').closest('.form-group').hide();
        //     }
        // });
        // // ON CHANGE CHECKBOX USE COMPANY

        // // ON CHANGE SHIPMENT TYPE
        // $('select[name="shipment_type"]').change(function(){
        //     console.log($(this).val());
        //     var val = $(this).val()==="OTHERS" ? 2000 : (($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE") ? 1000 : 500);
        //     var shipment_type_alert = $('.shipment-type-alert');
		// 	var content = '';
        //     $('input[name="declared_value"]').val(val);
        //     $('.declared-value-display').val(val);
        //     if($(this).val()==="OTHERS"){
        //         $('input[name="declared_value"]').attr('type','number');
        //         $('.declared-value-display').attr('type','hidden');
        //         content+='<li>Minimum declared value is &#8369; 2,000.00 but the shipper can declare higher valuation.</li>';
		// 		content+='<li>Carriers liability in case of damages & losses is limited to the declared value appearing on the waybill.</li>';
		// 		content+='<li>Insurance will be collected equivalent to 1.2% of declared value plus 12% VAT. This is in addition to freight charges.</li>';
        //     }else{
        //         $('input[name="declared_value"]').attr('type','hidden');
        //         $('.declared-value-display').attr('type','number');
        //         if($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
		// 			content+='<li>The shipper cannot declare more than  &#8369; 1,000.00 declared value for breakable & perishable cargoes.</li>';
		// 			content+="<li>Should you wish to continue you agree that the carrier's liablity is limited to &#8369; 1000.00 in case of breakage, damages & losses.</li>";
		// 			content+='<li>Insurance will be collected in the amount of &#8369; 12.00 plus 1.2% VAT. This is in addition to freight charges.</li>';
		// 		}
        //     }
        //     if($(this).val()==="OTHERS" || $(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
        //         shipment_type_alert.find('.shipment-type-name').html($(this).val());
        //         shipment_type_alert.find('ul').html(content);
        //         shipment_type_alert.show();
        //     }else{
        //         shipment_type_alert.hide();
        //     }

        // });
        // // ON CHANGE SHIPMENT TYPE

        // @if(Route::currentRouteName() !='waybills.edit')
        //     $("#shipper_id").trigger('change');
        // @endif

        // // JQUERY VALIDATIONS

        // jQuery.validator.addMethod("notEqualToGroup", function (value, element, options) {

        //     var elems = $(element).parents('form').find(options[0]);
        //     var valueToCompare = value;
        //     var matchesFound = 0;

        //     jQuery.each(elems, function () {
        //         thisVal = $(this).val();
        //         if($('#form-step-1').find('select[name="shipper_id"]').val()!==$('#form-step-1').find('select[name="consignee_id"]').val()){
        //             if (thisVal == valueToCompare) {
        //                 matchesFound++;
        //             }
        //         }
        //     });

        //     // count should be either 0 or 1 max
        //     if (this.optional(element) || matchesFound <= 1) {
        //         //elems.removeClass('error');
        //         return true;
        //     } else {
        //         //elems.addClass('error');
        //     }
        // }, "Please enter a unique email.");

        // var validation_1 = $('#form-step-1').validate({
        //     rules: {
        //         shipper_id: {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()!=="new"){
        //                     return true;
        //                 }
        //             }
        //         },
        //         shipper_email : {
        //             email : true,
        //             notEqualToGroup : ['.email-address']
        //         },
        //         // shipper_contact_no : {required : true, maxlength: 55},
        //         "shipper_mobile_no[]" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()!=="new"){
        //                     return $('#form-step-1 input[name="shipper_telephone_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 11

        //         },
        //         "shipper_telephone_no[]" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()!=="new"){
        //                     return $('#form-step-1 input[name="shipper_mobile_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 10,
        //             minlength: 7

        //         },
        //         shipper_address_id : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()!=="new"){
        //                     return true;
        //                 }else{
        //                     if($('select[name="shipper_address_id"]').val()!=="new"){
        //                         return true;
        //                     }
        //                 }
        //             }
        //         },
        //         "shipper.lname" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 50
        //         },
        //         "shipper.fname" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 50
        //         },
        //         "shipper.mname" : {
        //             maxlength: 50
        //         },
        //         "shipper.company" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==true){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 100
        //         },
        //         "shipper.email" : {
        //             email : true,
        //             notEqualToGroup : ['.email-address']
        //         },
        //         "shipper.shipper_mobile_no[]" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new"){
        //                     return $('#form-step-1 select[name="shipper.shipper_telephone_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 11,
        //             minlength: 11
        //         },
        //         "shipper.shipper_telephone_no[]" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new"){
        //                     return $('#form-step-1 select[name="shipper.shipper_mobile_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 10,
        //             minlength: 7
        //         },
        //         "shipper.barangay" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 100
        //         },
        //         "shipper.city" : {
        //             required: function(){
        //                 if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
        //                     return true;
        //                 }
        //             }
        //         },

        //         consignee_id: {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()!=="new"){
        //                     return true;
        //                 }
        //             }
        //         },
        //         consignee_email : {
        //             email : true,
        //             notEqualToGroup : ['.email-address']
        //         },
        //         // consignee_contact_no : {required : true, maxlength: 55},
        //         "consignee_mobile_no[]" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()!=="new"){
        //                     return $('#form-step-1 input[name="consignee_telephone_no[]"]').val()==="";
        //                 }

        //             },
        //             maxlength: 11,
        //             minlength: 11

        //         },
        //         "consignee_telephone_no[]" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()!=="new"){
        //                     return $('#form-step-1 input[name="consignee_mobile_no[]"]').val()==="";
        //                 }

        //             },
        //             maxlength: 10,
        //             minlength: 7

        //         },
        //         consignee_address_id : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()!=="new"){
        //                     return true;
        //                 }else{
        //                     if($('select[name="consignee_address_id"]').val()!=="new"){
        //                         return true;
        //                     }
        //                 }
        //             }
        //         },

        //         "consignee.lname" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 50
        //         },
        //         "consignee.fname" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 50
        //         },
        //         "consignee.mname" : {
        //             maxlength: 50
        //         },
        //         "consignee.company" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==true){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 100
        //         },
        //         'consignee.email' : {
        //             email : true,
        //             notEqualToGroup : ['.email-address']
        //         },
        //         "consignee.consignee_mobile_no[]" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new"){
        //                     return $('#form-step-1 select[name="consignee.consignee_telephone_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 11,
        //             minlength: 11
        //         },
        //         "consignee.consignee_telephone_no[]" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new"){
        //                     return $('#form-step-1 select[name="consignee.consignee_mobile_no[]"]').val()==="";
        //                 }
        //             },
        //             maxlength: 10,
        //             minlength: 7
        //         },
        //         "consignee.barangay" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
        //                     return true;
        //                 }
        //             },
        //             maxlength: 100
        //         },
        //         "consignee.city" : {
        //             required: function(){
        //                 if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
        //                     return true;
        //                 }
        //             }
        //         },
        //     },
        //     messages: {
        //         "shipper_mobile_no[]" : {
        //             required: "Mobile number is required once telephone is not filled out"
        //         },
        //         "shipper_telephone_no[]" : {
        //             required: "Telephone number is required once mobile is not filled out"
        //         },
        //         "consignee_mobile_no[]" : {
        //             required: "Mobile number is required once telephone is not filled out"
        //         },
        //         "consignee_telephone_no[]" : {
        //             required: "Telephone number is required once mobile is not filled out"
        //         },
        //         "shipper.shipper_mobile_no[]" : {
        //             required: "Mobile number is required once telephone is not filled out"
        //         },
        //         "shipper.shipper_telephone_no[]" : {
        //             required: "Telephone number is required once mobile is not filled out"
        //         },
        //         "consignee.consignee_mobile_no[]" : {
        //             required: "Mobile number is required once telephone is not filled out"
        //         },
        //         "consignee.consignee_telephone_no[]" : {
        //             required: "Telephone number is required once mobile is not filled out"
        //         }
        //     },
		// 	highlight: function (e) {
		// 		$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		// 	},
		// 	success: function (e) {
		// 		$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
		// 		$(e).remove();
		// 	},
		// 	errorPlacement: function (error, element) {
		// 		if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
		// 			var controls = element.closest('div[class*="col-"]');
		// 			if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
		// 			else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
		// 		}
		// 		else if(element.is('.select2')) {
		// 			error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
		// 		}
		// 		else if(element.is('.chosen-select')) {
		// 			error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
		// 		}
		// 		else error.insertAfter(element.parent());
		// 	}
		// });

        // jQuery.validator.addMethod("exactEqual", function(value, element, param) {
        // return this.optional(element) || value == param;
        // }, $.validator.format("Value must be equal to {0}"));

        // $('.add-mobile').click(function(){
        //     $for = $(this).data('for');
        //     $input = $(this).closest('.input-group').find('input.mobile_no');
        //     $input.rules( "add", {
        //         required: true,
        //         number: true,
        //         minlength: 11,
        //         maxlength: 11,
        //     });
        //     if($input.valid()){
        //         $table = $(this).closest('table');
        //         if($table.find('tbody').find('tr').length<2){
        //             $html = '<tr>'+
        //                     '<td>'+
        //                         '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                             '<input type="number" class="form-control mobile_no" name="'+$for+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
        //                             '<span class="input-group-btn">'+
        //                                 '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
        //                             '</span>'+
        //                         '</div>'+
        //                     '</td>'+
        //                 '</tr>';
        //             $table.append($html);
        //             $input.rules('remove');
        //         }
        //     }
        // });

        // $('.new-add-mobile').click(function(){
        //     $for = $(this).data('for');
        //     $input = $(this).closest('.input-group').find('input.mobile_no');
        //     $input.rules( "add", {
        //         required: true,
        //         number: true,
        //         minlength: 11,
        //         maxlength: 11,
        //     });
        //     if($input.valid()){
        //         $table = $(this).closest('table');
        //         if($table.find('tbody').find('tr').length<2){
        //             $html = '<tr>'+
        //                         '<td>'+
        //                             '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                                 '<input type="number" class="form-control mobile_no" name="'+$for+'.'+$for+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
        //                                 '<span class="input-group-btn">'+
        //                                     '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
        //                                 '</span>'+
        //                             '</div>'+
        //                         '</td>'+
        //                     '</tr>';
        //             $table.append($html);
        //             $input.rules('remove');
        //         }
        //     }
        // });

        // $('.table-mobile').on('click','.delete-mobile',function(){
        //     $(this).closest('tr').remove();
        // });

        // $('.add-telephone').click(function(){
        //     $for = $(this).data('for');
        //     $input = $(this).closest('.input-group').find('input.telephone_no');
        //     $input.rules( "add", {
        //         required: true,
        //         number: true,
        //         minlength: 7,
        //         maxlength: 10,
        //     });
        //     if($input.valid()){
        //         $table = $(this).closest('table');
        //         if($table.find('tbody').find('tr').length<2){
        //             $html = '<tr>'+
        //                         '<td>'+
        //                             '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                                 '<input type="number" class="form-control telephone_no" name="'+$for+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
        //                                 '<span class="input-group-btn">'+
        //                                     '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
        //                                 '</span>'+
        //                             '</div>'+
        //                         '</td>'+
        //                     '</tr>';
        //             $table.append($html);
        //             $input.rules('remove');
        //         }
        //     }
        // });

        // $('.new-add-telephone').click(function(){
        //     $for = $(this).data('for');
        //     $input = $(this).closest('.input-group').find('input.telephone_no');

        //     $input.rules( "add", {
        //         required: true,
        //         number: true,
        //         minlength: 7,
        //         maxlength: 10,
        //     });
        //     if($input.valid()){
        //         $table = $(this).closest('table');
        //         if($table.find('tbody').find('tr').length<2){
        //             $html = '<tr>'+
        //                         '<td>'+
        //                             '<div class="input-group mobile-number" style="margin-bottom:0;">'+
        //                                 '<input type="number" class="form-control mobile_no" name="'+$for+'.'+$for+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
        //                                 '<span class="input-group-btn">'+
        //                                     '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
        //                                 '</span>'+
        //                             '</div>'+
        //                         '</td>'+
        //                     '</tr>';
        //             $table.append($html);
        //             $input.rules('remove');
        //         }
        //     }
        // });

        // $('.table-telephone').on('click','.delete-telephone',function(){
        //     $(this).closest('tr').remove();
        // });
        // // JQUERY VALIDATIONS

        // WIZARD
        $wizard_steps = $('.wizard_steps');
        $container = $('.container');

        $('.buttonPrevious').click(function(e){
            $selected_step = $wizard_steps.find('li').find('a.selected');
            $prev_step = $wizard_steps.find('li').has('a.selected').prev('li').find('a');

            $selected_step.removeClass('selected').addClass('done');
            $prev_step.removeClass('done').addClass('selected');

            $selected = $container.find('div.selected');
            $prev = $container.find('div.selected').prev('div.content');
            if($selected.hasClass('start')==false){
                $selected.removeClass('selected').addClass('unselect');
                $prev.removeClass('unselect').addClass('selected');
            }
            if($selected.hasClass('end')){
                $('.buttonNext').removeClass('buttonDisabled');
                $('.buttonFinish').addClass('buttonDisabled');
            }
            if($prev.hasClass('start')){
                $(this).addClass('buttonDisabled');
            }

            if(document.getElementById('online_booking_type_1').checked){
                if( parseInt($prev_step.find('.step_no')[0].innerHTML) == 4 ){
                   $('.buttonPrevious').trigger('click');
                }
            }

        })

        $('.buttonNext').click(function(e){
            $selected_step = $wizard_steps.find('li').find('a.selected');
            $next_step = $wizard_steps.find('li').has('a.selected').next('li').find('a');

            $selected = $container.find('div.selected');
            $next = $container.find('div.selected').next('div.content');
            if(!$container.find('div.selected').find('form').valid()){
                e.preventDefault();
            }else{
                $proceed = true;

                if(parseInt($selected_step.find('.step_no')[0].innerHTML)==1){
                    rebate_point_func();
                }

                if(parseInt($selected_step.find('.step_no')[0].innerHTML)==3){
                    $.each(table.rows().data().toArray(),function(){
                        if(this.item_code=="" || parseInt(this.quantity)==0){
                            $proceed=false;
                            return $proceed;
                        }
                    })
                }
                if($proceed==true){
                    if($selected.hasClass('end')==false){
                        $selected.removeClass('selected').addClass('unselect');
                        $next.removeClass('unselect').addClass('selected');
                        $('.buttonPrevious').removeClass('buttonDisabled');
                    }
                    if($next.removeClass('unselect').hasClass('end')){
                        $(this).addClass('buttonDisabled');
                        $('.buttonFinish').removeClass('buttonDisabled');
                    }
                    $selected_step.removeClass('selected').addClass('done');
                    if($next_step.hasClass('disabled')){
                        $next_step.removeClass('disabled').addClass('selected');
                    }else{
                        $next_step.removeClass('done').addClass('selected');
                    }

                    if(document.getElementById('online_booking_type_1').checked && parseInt($selected_step.find('.step_no')[0].innerHTML)==3 ){
                        $('.buttonNext').trigger('click');
                    }
                    else if(
                        document.getElementById('online_booking_type_2').checked
                        && parseInt($selected_step.find('.step_no')[0].innerHTML)==4
                        &&  $("#gcash_reference_no").val() ==''
                        &&  !($('input[name="pca_use_adv_payment_cf"]').is(':checked'))
                    ){
                        $('.buttonPrevious').trigger('click');
                    }

                }else{
                    swal({
                        text:'Please supply missing shipment data',
                        icon: 'error',
                        title: "Ooops!"
                    });
                }



            }
        })

        $('.buttonFinish').click(function(e){
            // $('#modal-note').modal('show');
            $button = $(this);
            $button.attr('disabled',true);
            $button.html('Please wait ...');
            var shipper_mobile_nos = [],shipper_telephone_nos = [],shipper_sub_mobile_nos = [],shipper_sub_telephone_nos = [];
            $('#form-step-1 input[name="shipper_mobile_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    shipper_mobile_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="shipper_telephone_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    shipper_telephone_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="shipper.shipper_mobile_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    shipper_sub_mobile_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="shipper.shipper_telephone_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    shipper_sub_telephone_nos.push($this.val());
                }
            });

            var consignee_mobile_nos = [],consignee_telephone_nos = [],consignee_sub_mobile_nos = [],consignee_sub_telephone_nos = [];
            $('#form-step-1 input[name="consignee_mobile_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    consignee_mobile_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="consignee_telephone_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    consignee_telephone_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="consignee.consignee_mobile_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    consignee_sub_mobile_nos.push($this.val());
                }
            });
            $('#form-step-1 input[name="consignee.consignee_telephone_no[]"]').each(function(){
                $this = $(this);
                if($this.val()!==""){
                    consignee_sub_telephone_nos.push($this.val());
                }
            });

            var form_step_1 = {

                pasabox :  $('#form-step-1 input[name="online_booking_type_2"]').is(':checked') ? 1 : 0,
                shipping_cid : $('#form-step-1 select[name="shipping_cid"]').val(),
                shipping_cname : $('#form-step-1 input[name="shipping_cname"]').val(),
                shipping_cmobile_no : $('#form-step-1 input[name="shipping_cmobile_no"]').val(),
                shipping_clandline_no : $('#form-step-1 input[name="shipping_clandline_no"]').val(),
                shipping_cemail : $('#form-step-1 input[name="shipping_cemail"]').val(),
                shipping_address_id : $('#form-step-1 select[name="shipping_address_id"]').val(),
                shipping_street :  $('#form-step-1 input[name="shipping_address_street"]').val(),
                shipping_barangay : $('#form-step-1 select[name="shipping_address_brgy"]').find('option:selected').text(),
                shipping_sectorate_no : $('#form-step-1 select[name="shipping_address_brgy"]').find('option:selected').data('sectorate_no'),
                shipping_city :  $('#form-step-1 select[name="shipping_address_city"]').val(),
                shipping_city_text : $('#form-step-1 select[name="shipping_address_city"]').find('option:selected').text(),
                shipping_province : $('#form-step-1 select[name="shipping_address_city"]').find('option:selected').data('province'),
                shipping_postal_code : $('#form-step-1 select[name="shipping_address_city"]').find('option:selected').data('postal_code'),

                shipper_id : $('#form-step-1 select[name="shipper_id"]').val(),
                shipper_qr_code : $('#form-step-1 input[name="shipper_qr_code"]').val(),
                shipper_qr_code_cid : $('#form-step-1 input[name="shipper_qr_code_cid"]').val(),
                shipper_mname_update : $('#form-step-1 input[name="shipper_mname_update"]').val(),
                shipper_mname_update_text : $('#form-step-1 input[name="shipper_mname_update_text"]').val(),
                shipper_address_id : $('#form-step-1 select[name="shipper_address_id"]').val(),
                shipper_email : $('#form-step-1 input[name="shipper_email"]').val(),
                shipper_mobile_no : shipper_mobile_nos,
                shipper_telephone_no : shipper_telephone_nos,
                shipper: {
                    use_company :  $('#form-step-1 input[name="shipper.use_company"]').is(':checked') ? 1 : 0,
                    lname :  $('#form-step-1 input[name="shipper.lname"]').val(),
                    fname :  $('#form-step-1 input[name="shipper.fname"]').val(),
                    mname :  $('#form-step-1 input[name="shipper.mname"]').val(),
                    company :  $('#form-step-1 input[name="shipper.company"]').val(),
                    email :  $('#form-step-1 input[name="shipper.email"]').val(),
                    business_category_id :  $('#form-step-1 select[name="shipper.business_category_id"]').val(),
                    shipper_mobile_no : shipper_sub_mobile_nos,
                    shipper_telephone_no : shipper_sub_telephone_nos,
                    street :  $('#form-step-1 input[name="shipper.street"]').val(),
                    barangay : $('#form-step-1 select[name="shipper.barangay"]').find('option:selected').text(),
                    sectorate_no : $('#form-step-1 select[name="shipper.barangay"]').find('option:selected').data('sectorate_no'),
                    city :  $('#form-step-1 select[name="shipper.city"]').val(),
                    city_text : $('#form-step-1 select[name="shipper.city"]').find('option:selected').text(),
                    province : $('#form-step-1 select[name="shipper.city"]').find('option:selected').data('province'),
                    postal_code : $('#form-step-1 select[name="shipper.city"]').find('option:selected').data('postal_code'),
                },


                consignee_id : $('#form-step-1 select[name="consignee_id"]').val(),
                consignee_qr_code : $('#form-step-1 input[name="consignee_qr_code"]').val(),
                consignee_qr_code_cid : $('#form-step-1 input[name="consignee_qr_code_cid"]').val(),
                consignee_mname_update : $('#form-step-1 input[name="consignee_mname_update"]').val(),
                consignee_mname_update_text : $('#form-step-1 input[name="consignee_mname_update_text"]').val(),
                consignee_address_id : $('#form-step-1 select[name="consignee_address_id"]').val(),
                consignee_contact_no : $('#form-step-1 input[name="consignee_contact_no"]').val(),
                consignee_email : $('#form-step-1 input[name="consignee_email"]').val(),
                consignee_mobile_no : consignee_mobile_nos,
                consignee_telephone_no : consignee_telephone_nos,
                consignee: {
                    use_company :  $('#form-step-1 input[name="consignee.use_company"]').is(':checked') ? 1 : 0,
                    lname :  $('#form-step-1 input[name="consignee.lname"]').val(),
                    fname :  $('#form-step-1 input[name="consignee.fname"]').val(),
                    mname :  $('#form-step-1 input[name="consignee.mname"]').val(),
                    company :  $('#form-step-1 input[name="consignee.company"]').val(),
                    email :  $('#form-step-1 input[name="consignee.email"]').val(),
                    business_category_id :  $('#form-step-1 select[name="consignee.business_category_id"]').val(),
                    consignee_mobile_no : consignee_sub_mobile_nos,
                    consignee_telephone_no : consignee_sub_telephone_nos,
                    street :  $('#form-step-1 input[name="consignee.street"]').val(),
                    barangay :  $('#form-step-1 select[name="consignee.barangay"]').find('option:selected').text(),
                    sectorate_no : $('#form-step-1 select[name="consignee.barangay"]').find('option:selected').data('sectorate_no'),
                    city :  $('#form-step-1 select[name="consignee.city"]').val(),
                    city_text : $('#form-step-1 select[name="consignee.city"]').find('option:selected').text(),
                    province : $('#form-step-1 select[name="consignee.city"]').find('option:selected').data('province'),
                    postal_code : $('#form-step-1 select[name="consignee.city"]').find('option:selected').data('postal_code'),
                },
            };


            var form_step_2 = {
                payment_type: $('#form-step-2 select[name="payment_type"]').val(),
                pasabox_branch_receiver: $('#form-step-2 select[name="pasabox_branch_receiver"]').val(),
                mode_payment: $('#form-step-2 select[name="mode_payment"]').val(),
                mode_payment_io: $('#form-step-2 input[name="mode_payment_is"]').is(':checked') ? 1 : 2,
                mode_payment_email: $('#form-step-2 input[name="mode_payment_email"]').val(),
                destinationbranch_id: $('#form-step-2 select[name="destinationbranch_id"]').val(),
                shipment_type: $('#form-step-2 select[name="shipment_type"]').val(),
                pu_checkbox :  $('#form-step-2 input[name="pu_checkbox"]').is(':checked') ? 1 : 0,
                pu_sector: $('#form-step-2 select[name="pu_sector"]').val(),
                pu_date: $('#form-step-2 input[name="pu_date"]').val(),
                pu_street: $('#form-step-2 input[name="pu_street"]').val(),
                del_checkbox :  $('#form-step-2 input[name="del_checkbox"]').is(':checked') ? 1 : 0,
                del_sector: $('#form-step-2 select[name="del_sector"]').val(),
                del_street: $('#form-step-2 input[name="del_street"]').val(),
                declared_value: $('#form-step-2 input[name="declared_value"]').val(),
                discount_coupon: $('#form-step-2 input[name="discount_coupon"]').val(),
                discount_coupon_action: $('#form-step-2 input[name="discount_coupon_action"]').val(),
                pca_use_adv_payment :  $('#form-step-2 input[name="pca_use_adv_payment"]').is(':checked') ? 1 : 0,
                pca_no: $('#form-step-2 input[name="pca_no"]').val(),
            };



            var form_step_3 = table.rows().data().toArray();

            var form_step_4 = {
                agree: $('#form-step-4 input[name="agree"]').is(':checked') ? 1 : 0,
                reference_no : $('#form-step-4 input[name="reference_no"]').val()
            };
            var form_step_5 = {

                gcash_amount: $('#form-step-5 input[name="gcash_amount"]').val(),
                gcash_reference_no: $('#form-step-5 input[name="gcash_reference_no"]').val(),
                gcash_pdate: $('#form-step-5 input[name="gcash_pdate"]').val(),
                gcash_cemail: $('#form-step-5 input[name="gcash_cemail"]').val(),
                gcash_branch_aname:$('#form-step-5 input[name="gcash_branch_aname"]').val(),
                gcash_branch_ano:$('#form-step-5 input[name="gcash_branch_ano"]').val(),
                gcash_id:$('#form-step-5 input[name="gcash_id"]').val(),
                cf_onl_id:$('#form-step-5 input[name="cf_onl_id"]').val(),
                pca_use_adv_payment_cf :  $('#form-step-5 input[name="pca_use_adv_payment_cf"]').is(':checked') ? 1 : 0,
            };

            $.ajax({
                url: "{{route('waybills.update',$id)}}",
				type: "PUT",
				dataType: "JSON",
                data : { _token: "{{csrf_token()}}", step1: form_step_1, step2: form_step_2,step3: form_step_3,step4: form_step_4,step5: form_step_5},
                success: function(result){

                    // swal(result.message, {
                    //     icon: result.type,
                    //     title: result.title
                    // })
                    // .then(function(){
                    //     window.location.href="{{url('/waybills')}}";
                    // });
                    Swal.fire({
                        icon: result.type,
                        title: result.title,
                        text: result.message
                    }).then((res) => {
                        if(result.type =='error'){
                            $button.removeAttr('disabled');
                            $button.html('Proceed');
                        }else{
                            window.location.href="{{url('/waybills')}}";
                        }
                    });
                },
                error: function(xhr,status){

                    $button.removeAttr('disabled');
                    $button.html('Proceed');
                    if(xhr.status==500){
                        var responseJSON = xhr.responseJSON;
                        // swal(responseJSON.message, {
                        //     icon: 'error',
                        //     title: 'Ooops!'
                        // });
                        Swal.fire({
                            icon: 'error',
                            title: 'Ooops!',
                            text: responseJSON.message
                        });
                    }else if(xhr.status==408){
                        // swal('Please check your internet', {
                        //     icon: 'error',
                        //     title: 'Connection time-out'
                        // });
                        Swal.fire({
                            icon: 'error',
                            title: 'Connection time-out',
                            text: 'Please check your internet'
                        });
                    }
                    else if(xhr.status=422){
                        var errors = xhr.responseJSON.errors;
                        var errorHTML = '';
                        for (var key of Object.keys(errors)) {
                            // console.log(key + " -> " + errors[key])
                            errorHTML += "<p> <font color='red'>*</font> "+errors[key]+"</p>"
                        }

                        $('#modal-error .modal-body').html(errorHTML);
                        $('#modal-error').modal('show');
                    }
                }
            });
        })

        // $('.proceed').off('click');

        // $('.proceed').on('click',function(e){


        // });
        // WIZARD
    });
</script>
@include('waybills.booking_script')
@include('waybills.payment_type_script')
@include('sector.sector_script')
@include('qr_code_decoder');
@endsection


