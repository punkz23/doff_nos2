@extends('layouts.gentelella2')

@section('css')
<link href="{{asset('/gentelella')}}/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />
<link href="{{asset('/gentelella')}}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="{{asset('/gentelella')}}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/gentelella')}}/vendors/additional/jquery-ui.css">
<script src="{{asset('/gentelella')}}/vendors/additional/jquery-1.9.1.js"></script>
<script src="{{asset('/gentelella')}}/vendors/additional/jquery-ui.js"></script>
<style>
    .disabled .step_no{
        background: #ccc;
    }
    .buttonDisabled {
        display:none;
    }
    .container .unselect {
        display:none;
    }
    .ui-highlight .ui-state-default{
        background: #82E0AA !important;
        border-color: #82E0AA !important;
        color: white !important;
    }
    
    .ui-highlight2 .ui-state-default{
        background: #7FB3D5 !important;
        border-color: #7FB3D5 !important;
        color: white !important;
    }
    
    .ui-highlight3 .ui-state-default{
        background: #CD6155 !important;
        border-color: #CD6155 !important;
        color: white !important;
    }
    .ui-highlight4 .ui-state-default{
        background: #F8C471  !important;
        border-color: #F8C471  !important;
        color: white !important;
    }
</style>

@endsection

@section('bread-crumbs')
<h3>Update Online Booking</h3>
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
    <li>
      <a href="#step4" class="step-link disabled">
        <span class="step_no">4</span>
        <span class="step_descr">
            Step 4<br />
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
    <div id="step-4" class="content end unselect">
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
        <button class="buttonPrevious buttonDisabled btn btn-primary">Previous</button>
        <button class="buttonNext btn btn-success">Next</button>
        <button class="buttonFinish buttonDisabled btn btn-default">Finish</button>
    </div>
</div>
</div>

@endsection

@section('plugins')
<!-- jQuery Smart Wizard -->
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/gentelella')}}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- PNotify -->
<script src="{{asset('/gentelella')}}/vendors/pnotify/dist/pnotify.js"></script>
<script src="{{asset('/gentelella')}}/vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="{{asset('/gentelella')}}/vendors/pnotify/dist/pnotify.nonblock.js"></script>
<script src="{{asset('/js/sweetalert.min.js')}}"></script>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){

        $selectedTR = null;
        $('.select2').css('width','100%').select2({allowClear:true});

        $('input[name="use_company"').change(function(){
            if($(this).is(':checked')==true){
                $('.name').attr('style','display:none');
                $('input[name="lname"]').removeAttr('required');
                $('input[name="fname"]').removeAttr('required');
                $('.company').removeAttr('style');
                $('input[name="company"]').attr('required',true);
                
            }else{
                $('.name').removeAttr('style');
                $('input[name="lname"]').attr('required',true);
                $('input[name="fname"]').attr('required',true);
                $('.company').attr('style','display:none');
                $('input[name="company"]').removeAttr('required');
            }
        });

        $('.cities').change(function(){
            var form_id = $(this).closest('form').attr('id');
            
            $select=$(this).closest('div.new-contact').find('.barangay');
            $id = $(this).val();
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

        $('.verify').on('click',function(){
            $('input[name="discount_coupon"]').rules( "add", {
                required: true,
                remote: {
                    url : "{{url('/discount-coupon-verification')}}",
                    type: "POST",
                    data : { _token : "{{csrf_token()}}", discount_coupon_no : $('input[name="discount_coupon_no"]').val()}
                }
            });
            $('#form-step-2').validate().element('input[name="discount_coupon"]');
            $('#form-step-2 input[name="discount_coupon"]').rules("remove");
        });

        $('.contacts').on('change',function(e){
            var elementName = e.target.name=="shipper_id" ? "shipper" : "consignee";
            
            if(e.target.value!=="new"){
                var addresses = JSON.parse(e.target.selectedOptions[0].dataset.address);
                var innerHTML = '<option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option><option value="new">--ADD NEW--</option>';
                for(var i=0; i<addresses.length;i++){
                    innerHTML = innerHTML + "<option value='"+addresses[i]['useraddress_no']+"'>"+addresses[i]['full_address']+"</option>";
                }
                var contact_numbers = $(this).find('option:selected').data('contact_numbers');
                
                if(contact_numbers!==undefined){
                    
                    var mobile_numbers = contact_numbers.filter(function(obj){ return obj.type==1; });
                    var telephone_numbers = contact_numbers.filter(function(obj){ return obj.type==2; });
                    
                    if(mobile_numbers.length>0){
                        $('input[name="'+elementName+'_mobile_no[]"]').eq(0).val(mobile_numbers[0]['contact_no']);
                    }
                    if(mobile_numbers.length>1){
                        $table = $('input[name="'+elementName+'_mobile_no[]"]').closest('table');
                        $html = '<tr>'+
                                    '<td>'+
                                        '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                            '<input type="number" class="form-control mobile_no" name="'+elementName+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="'+mobile_numbers[1]['contact_no']+'">'+
                                            '<span class="input-group-btn">'+
                                                '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
                                            '</span>'+
                                        '</div>'+
                                    '</td>'+
                                '</tr>';
                        $table.append($html);
                    }

                    if(telephone_numbers.length>0){
                        $('input[name="'+elementName+'_telephone_no[]"]').eq(0).val(telephone_numbers[0]['contact_no']);
                    }
                    if(telephone_numbers.length>1){
                        $table = $('input[name="'+elementName+'_telephone_no[]"]').closest('table');
                        $table = $(this).closest('table');
                        $html = '<tr>'+
                                '<td>'+
                                    '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                        '<input type="number" class="form-control telephone_no" name="'+elementName+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="'+telephone_numbers[1]['contact_no']+'">'+
                                        '<span class="input-group-btn">'+
                                            '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
                                        '</span>'+
                                    '</div>'+
                                '</td>'+
                            '</tr>';
                        $table.append($html);
                    }
                }
                $('select[name="'+elementName+'_address_id"]').html(innerHTML);
                $('input[name="'+elementName+'_contact_no"]').val(e.target.selectedOptions[0].dataset.contact_no)
                $('input[name="'+elementName+'_email"]').val(e.target.selectedOptions[0].dataset.email)
                $('select[name="'+elementName+'_address_id"]').closest('.form-group').show();
                $('input[name="'+elementName+'_email"]').closest('.form-group').show();
                $('input[name="'+elementName+'_contact_no"]').closest('.form-group').show();
                $('.'+elementName+'-form').hide();
                $('.'+elementName+'-info').hide();
                $('.'+elementName+'-address').hide();
            }else{
                $('select[name="'+elementName+'_address_id"]').closest('.form-group').hide();
                $('input[name="'+elementName+'_contact_no"]').closest('.form-group').hide();
                $('input[name="'+elementName+'_email"]').closest('.form-group').hide();
                $('.'+elementName+'-form').show();
                $('.'+elementName+'-info').show();
                $('.'+elementName+'-address').show();
            }
            var elementAddress = $('select[name="'+elementName+'_address_id"]').next().find('.select2-selection__rendered');
            elementAddress.removeAttr('title');
            elementAddress.empty();
        });

        $('.addresses').on('change',function(e){
            var elementName = e.target.name=="shipper_address_id" ? "shipper" : "consignee";
            if(e.target.value!=="new"){
                $('.'+elementName+'-form').hide();
                $('.'+elementName+'-info').hide();
                $('.'+elementName+'-address').hide();
            }else{
                $('.'+elementName+'-form').show();
                $('.'+elementName+'-info').hide();
                $('.'+elementName+'-address').show();
            }
        })

        $('.use_company').on('change',function(e){
            var parentEl = e.target.name=="shipper.use_company" ? "shipper" : "consignee";
            $('input[name="'+parentEl+'.lname"]').val('');
            $('input[name="'+parentEl+'.fname"]').val('');
            $('input[name="'+parentEl+'.mname"]').val('');
            $('input[name="'+parentEl+'.company"]').val('');
            if($(this).is(':checked')==true){
                $('input[name="'+parentEl+'.lname"]').closest('.form-group').hide();
                $('input[name="'+parentEl+'.fname"]').closest('.form-group').hide();
                $('input[name="'+parentEl+'.mname"]').closest('.form-group').hide();
                $('input[name="'+parentEl+'.company"]').closest('.form-group').show();
            }else{
                $('input[name="'+parentEl+'.lname"]').closest('.form-group').show();
                $('input[name="'+parentEl+'.fname"]').closest('.form-group').show();
                $('input[name="'+parentEl+'.mname"]').closest('.form-group').show();
                $('input[name="'+parentEl+'.company"]').closest('.form-group').hide();
            }
        });

        $('select[name="shipment_type"]').change(function(){
            console.log($(this).val());
            var val = $(this).val()==="OTHERS" ? 2000 : (($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE") ? 1000 : 500);
            var shipment_type_alert = $('.shipment-type-alert');
			var content = '';
            $('input[name="declared_value"]').val(val);
            $('.declared-value-display').val(val);
            if($(this).val()==="OTHERS"){
                $('input[name="declared_value"]').attr('type','number');
                $('.declared-value-display').attr('type','hidden');
                content+='<li>Minimum declared value is &#8369; 2,000.00 but the shipper can declare higher valuation.</li>';
				content+='<li>Carriers liability in case of damages & losses is limited to the declared value appearing on the waybill.</li>';
				content+='<li>Insurance will be collected equivalent to 1.2% of declared value plus 12% VAT. This is in addition to freight charges.</li>';
            }else{
                $('input[name="declared_value"]').attr('type','hidden');
                $('.declared-value-display').attr('type','number');
                if($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
					content+='<li>The shipper cannot declare more than  &#8369; 1,000.00 declared value for breakable & perishable cargoes.</li>';
					content+="<li>Should you wish to continue you agree that the carrier's liablity is limited to &#8369; 1000.00 in case of breakage, damages & losses.</li>";
					content+='<li>Insurance will be collected in the amount of &#8369; 12.00 plus 1.2% VAT. This is in addition to freight charges.</li>';
				}
            }
            if($(this).val()==="OTHERS" || $(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE"){
                shipment_type_alert.find('.shipment-type-name').html($(this).val());
                shipment_type_alert.find('ul').html(content);
                shipment_type_alert.show();
            }else{
                shipment_type_alert.hide();
            }
            
        });

        var table = $('#datatable').DataTable({
            bInfo : false,
            bLengthChange: false,
            bFilter : false,
            paging: false,
            columnDefs: [
                {width : '40%' , targets : [0]},
                {width : '30%' , targets : [1]},
                {width : '20%' , targets : [2]},
                {width : '10%' , targets : [2]}
            ]
        });

        table.on('click','.select-itemdesc',function(){
            $tr = $(this).closest('tr');
            $selectedTR=$tr;
            // $('#parentTableIndex').val($tr[0]['_DT_RowIndex']);
        })

        table.on('click','.remove-item',function(e){
            $tr = $(this).closest('tr');
            table.row($tr).remove().draw();
            e.preventDefault();
        })

        

        

        

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

            
            tblItems.on('click','.item-desc',function(){
                $tr = $(this).closest('tr');
                if(!$tr.find('td').hasClass('dataTables_empty')){
                    $selectedTR.find('td').eq(0).find('.description').val($tr[0]['id']);
                    $selectedTR.find('td').eq(0).find('.error-portion').remove();
                    $selectedTR.find('td').eq(0).find('.portion-name').html($tr[0]['innerText']+'<br><center><a href="#" style="font-size:10px" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Change Description--</a></center>');
                    $('#modal-item').find('.close').click();
                    $('input[name="item_description"]').val('');
                    $('#table-items > tbody').html('<tr class="odd"><td valign="top" colspan="1" class="dataTables_empty">No data available in table</td></tr>');
                }
                
                
            });
        }

        fill_datatable_items();

        $('#form-search-item').on('submit',function(e){
            $('#table-items').DataTable().destroy();
            fill_datatable_items($(this).find('input[name="item_description"]').val());
            e.preventDefault();
        });

        $('.add-mobile').click(function(){
            $for = $(this).data('for');
            $input = $(this).closest('.input-group').find('input.mobile_no');
            $input.rules( "add", {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11,
            });
            if($input.valid()){
                $table = $(this).closest('table');
                $html = '<tr>'+
                            '<td>'+
                                '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                    '<input type="number" class="form-control mobile_no" name="'+$for+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                $table.append($html);
                $input.rules('remove');
            }
        });

        $('.new-add-mobile').click(function(){
            $for = $(this).data('for');
            $input = $(this).closest('.input-group').find('input.mobile_no');
            $input.rules( "add", {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11,
            });
            if($input.valid()){
                $table = $(this).closest('table');
                $html = '<tr>'+
                            '<td>'+
                                '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                    '<input type="number" class="form-control mobile_no" name="'+$for+'.'+$for+'_mobile_no[]" placeholder="09#########" maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger delete-mobile"><i class="fa fa-trash"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                $table.append($html);
                $input.rules('remove');
            }
        });

        $('.table-mobile').on('click','.delete-mobile',function(){
            $(this).closest('tr').remove();
        });

        $('.add-telephone').click(function(){
            $for = $(this).data('for');
            $input = $(this).closest('.input-group').find('input.telephone_no');
            $input.rules( "add", {
                required: true,
                number: true,
                minlength: 7,
                maxlength: 10,
            });
            if($input.valid()){
                $table = $(this).closest('table');
                $html = '<tr>'+
                            '<td>'+
                                '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                    '<input type="number" class="form-control mobile_no" name="'+$for+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                $table.append($html);
                $input.rules('remove');
            }
        });

        $('.new-add-telephone').click(function(){
            $for = $(this).data('for');
            $input = $(this).closest('.input-group').find('input.telephone_no');
            
            $input.rules( "add", {
                required: true,
                number: true,
                minlength: 7,
                maxlength: 10,
            });
            if($input.valid()){
                $table = $(this).closest('table');
                $html = '<tr>'+
                            '<td>'+
                                '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                    '<input type="number" class="form-control mobile_no" name="'+$for+'.'+$for+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                $table.append($html);
                $input.rules('remove');
            }
        });

        $('.table-telephone').on('click','.delete-telephone',function(){
            $(this).closest('tr').remove();
        });

        var step1=null;var step2=null;var step3=null;var step4=null;

        jQuery.validator.addMethod("notEqualToGroup", function (value, element, options) {
            var elems = $(element).parents('form').find(options[0]);
            var valueToCompare = value;
            var matchesFound = 0;
            jQuery.each(elems, function () {
                thisVal = $(this).val();
                if (thisVal == valueToCompare) {
                    matchesFound++;
                }
            });
            // count should be either 0 or 1 max
            if (this.optional(element) || matchesFound <= 1) {
                //elems.removeClass('error');
                return true;
            } else {
                //elems.addClass('error');
            }
        }, "Please enter a unique email.");

        var validation_1 = $('#form-step-1').validate({
            rules: {
                shipper_id: {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return true;
                        }
                    }
                },
                shipper_email : {
                    email : true,
                    notEqualToGroup : ['.email-address']
                },
                // shipper_contact_no : {required : true, maxlength: 55},
                "shipper_mobile_no[]" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return $('#form-step-1 input[name="shipper_telephone_no[]"]').val()==="";
                        }
                    },
                    maxlength: 11,
                    minlength: 11
                },
                "shipper_telephone_no[]" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return $('#form-step-1 input[name="shipper_mobile_no[]"]').val()==="";
                        }
                        
                    },
                    maxlength: 10,
                    minlength : 7
                    
                },
                shipper_address_id : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()!=="new"){
                            return true;
                        }else{
                            if($('select[name="shipper_address_id"]').val()!=="new"){
                                return true;
                            }
                        }
                    }
                },
                "shipper.lname" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }, 
                    maxlength: 50
                },
                "shipper.fname" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }, 
                    maxlength: 50
                },
                "shipper.mname" :{
                    maxlength : 50
                },
                "shipper.company" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==true){
                            return true;
                        }
                    }, 
                    maxlength: 100
                },
                "shipper.email" : {
                    email : true,
                    notEqualToGroup : ['.email-address']
                },
                "shipper.shipper_mobile_no[]" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new"){
                            return $('#form-step-1 select[name="shipper.shipper_telephone_no[]"]').val()==="";
                        }
                    }, 
                    maxlength: 11,
                    minlength: 11
                },
                "shipper.shipper_telephone_no[]" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new"){
                            return $('#form-step-1 select[name="shipper.shipper_mobile_no[]"]').val()==="";
                        }
                    },
                    maxlength: 10,
                    minlength : 7
                },
                "shipper.barangay" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
                            return true;
                        }
                    }, 
                    maxlength: 100
                },
                "shipper.city" : {
                    required: function(){
                        if($('select[name="shipper_id"]').val()==="new" || $('select[name="shipper_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },
                
                consignee_id: {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return true;
                        }
                    }
                },
                consignee_email : {
                    email : true,
                    notEqualToGroup : ['.email-address']
                },
                // consignee_contact_no : {required : true,maxlength: 55},
                "consignee_mobile_no[]" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return $('#form-step-1 input[name="consignee_telephone_no[]"]').val()==="";
                        }
                    },
                    maxlength: 11,
                    minlength: 11
                },
                "consignee_telephone_no[]" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return $('#form-step-1 input[name="consignee_mobile_no[]"]').val()==="";
                        }
                    },
                    maxlength: 8
                    
                },
                consignee_address_id : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()!=="new"){
                            return true;
                        }else{
                            if($('select[name="consignee_address_id"]').val()!=="new"){
                                return true;
                            }
                        }
                    }
                },

                "consignee.lname" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }, 
                    maxlength: 50
                },
                "consignee.fname" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
                            return true;
                        }
                    }, 
                    maxlength: 50
                },
                "consignee.mname": {
                    maxlength: 50
                },
                "consignee.company" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==true){
                            return true;
                        }
                    }, 
                    maxlength: 100
                },
                "consignee.email" : {
                    email : true,
                    notEqualToGroup : ['.email-address']
                },
                "consignee.consignee_mobile_no[]" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new"){
                            return $('#form-step-1 select[name="consignee.consignee_telephone_no[]"]').val()==="";
                        }
                    }, 
                    maxlength: 11,
                    minlength: 11
                },
                "consignee.consignee_telephone_no[]" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new"){
                            return $('#form-step-1 select[name="consignee.consignee_mobile_no[]"]').val()==="";
                        }
                    }, 
                    maxlength: 8
                },
                "consignee.barangay" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
                            return true;
                        }
                    }, 
                    maxlength: 100
                },
                "consignee.city" : {
                    required: function(){
                        if($('select[name="consignee_id"]').val()==="new" || $('select[name="consignee_address_id"]').val()==="new"){
                            return true;
                        }
                    }
                },  
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
			}
		});

        var validation_2 = $('#form-step-2').validate({
            rules: {
                payment_type : {
                    required: true
                },
                destinationbranch_id : {
                    required: true
                },
                shipment_type : {
                    required: true
                },
                pu_province: {
                  required: function(){
                    if($('#form-step-2 input[name="pu_checkbox"]').is(':checked')){
                      return true;
                    }
                  }
                },
                del_province: {
                  required: function(){
                    if($('#form-step-2 input[name="del_checkbox"]').is(':checked')){
                      return true;
                    }
                  }
                },
                pu_city: {
                  required: function(){
                    if($('#form-step-2 input[name="pu_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                pu_date: {
                  required: function(){
                    if($('#form-step-2 input[name="pu_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                del_city: {
                  required: function(){
                    if($('#form-step-2 input[name="del_checkbox"]').is(':checked')){
                    
                      return true;
                    }
                  }
                },
                pu_sector: {
                  required: function(){
                    if($('#form-step-2 input[name="pu_checkbox"]').is(':checked')){
                   
                      return true;
                    }
                  }
                },
                del_sector: {
                  required: function(){
                    if($('#form-step-2 input[name="del_checkbox"]').is(':checked')){
                    
                      return true;
                      
                    }
                  }
                },
                declared_value : {
                    required : true
                }
            },
            messages: {
                discount_coupon : {
                    remote: 'Invalid discount coupon'
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
			}
		});

        $.validator.setDefaults({ 
            ignore: [],
            // any other default options and/or rules
        });

        // var descriptions = $('#form-step-3 .description').rules('add',{ required :true});
        
        jQuery.validator.addMethod("exactEqual", function(value, element, param) {
        return this.optional(element) || value == param;
        }, $.validator.format("Value must be equal to {0}"));

        var validation_3 = $('#form-step-3').validate({
            
			rules: {
                "item_code[]" : {
                    required: true
                },
                "unit[]" : {
                    required: true
                },
                "quantity[]" : {
                    required: true,
                    number: true,
                    min : 1
                },
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
			}
        });
        
        $('.add-item').on('click',function(){
            var rows_count = table.rows().count();
            // if(rows_count<5)
            var validated = 0;
            console.log(rows_count);
            if(rows_count==0){
                validated=1;
            }
            if(rows_count>0 && rows_count<5){
                var lastRow = $('#datatable > tbody > tr:last > td');
                
                var field1 = lastRow.eq(0).find('.description').val()!=="" ? 1 : 0;
                var field2 = lastRow.eq(1).find('.unit').valid() ? 1 : 0;
                var field3 = lastRow.eq(2).find('.quantity').valid() ? 1 : 0;

                if(field1==0){
                    lastRow.eq(0).find('.error-portion').html('Please provide item descrption');
                }

                if(field1==1 && field2==1 && field3==1){
                    validated=1;
                    lastRow.eq(0).find('.form-group').removeClass('has-error');
                    lastRow.eq(0).find('.form-group').removeClass('has-error')
                    lastRow.eq(1).find('.form-group').removeClass('has-error')
                }else{
                    validated=0;
                }
            }
            
            if(validated==1){
                var delete_btn = rows_count>0 ? '<center><a href="#" class="btn btn-sm btn-danger remove-item"><i class="ace-icon fa fa-trash"></i></a></center>' : '';
                table.row.add([
                    '<div class="row"><div class="col-12"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="form-group"><center><div class="portion-name"><a href="#" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Select Description--</a></div><div class="error-portion"></div></center><input type="hidden" name="item_code[]" class="form-control description" required></div></div></div></div>',
                    '<div><select name="unit_code[]" class="form-control unit select2">'+"{!! $ddUnits !!}"+'</select></div>',
                    '<div><input type="number" class="form-control quantity" name="quantity[]"></div>',
                    '<div class="row">'+delete_btn+'</div>'
                ]).draw(false);
                $('.select2').css('width','100%').select2({allowClear:true});
            }  
        });


        var validation_4 = $('#form-step-4').validate({
            
			rules: {
                "agree" : {
                    required: true
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
                var controls = element.closest('div[class*="col-"]');
				error.insertAfter(controls);
			}
		});

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
            
        })

        $('.buttonNext').click(function(e){
            $selected_step = $wizard_steps.find('li').find('a.selected');
            $next_step = $wizard_steps.find('li').has('a.selected').next('li').find('a');


            $selected = $container.find('div.selected');
            $next = $container.find('div.selected').next('div.content');
            if(!$container.find('div.selected').find('form').valid()){
                e.preventDefault();
            }else{
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
                
            }
            
        })

        $('.buttonFinish').click(function(e){
            if(!$('#form-step-1').valid() || !$('#form-step-2').valid() || !$('#form-step-3').valid() || !$('#form-step-4').valid()){
                
            }else{
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
                    shipper_id : $('#form-step-1 select[name="shipper_id"]').val(),
                    shipper_address_id : $('#form-step-1 select[name="shipper_address_id"]').val(),
                    // shipper_contact_no : $('#form-step-1 input[name="shipper_contact_no"]').val(),
                    shipper_mobile_no : shipper_mobile_nos,
                    shipper_telephone_no : shipper_telephone_nos,
                    shipper_email : $('#form-step-1 input[name="shipper_email"]').val(),
                    shipper: {
                        use_company :  $('#form-step-1 input[name="shipper.use_company"]').is(':checked') ? 1 : 0,
                        lname :  $('#form-step-1 input[name="shipper.lname"]').val(),
                        fname :  $('#form-step-1 input[name="shipper.fname"]').val(),
                        mname :  $('#form-step-1 input[name="shipper.mname"]').val(),
                        company :  $('#form-step-1 input[name="shipper.company"]').val(),
                        email :  $('#form-step-1 input[name="shipper.email"]').val(),
                        // contact_no :  $('#form-step-1 input[name="shipper.contact_no"]').val(),
                        shipper_mobile_no : shipper_sub_mobile_nos,
                        shipper_telephone_no : shipper_sub_telephone_nos,
                        business_category_id :  $('#form-step-1 select[name="shipper.business_category_id"]').val(),
                        street :  $('#form-step-1 input[name="shipper.street"]').val(),
                        barangay : $('#form-step-1 select[name="shipper.barangay"]').find('option:selected').text(),
                        sectorate_no : $('#form-step-1 select[name="shipper.barangay"]').find('option:selected').data('sectorate_no'),
                        city :  $('#form-step-1 select[name="shipper.city"]').val(),
                    },
                     

                    consignee_id : $('#form-step-1 select[name="consignee_id"]').val(),
                    consignee_address_id : $('#form-step-1 select[name="consignee_address_id"]').val(),
                    // consignee_contact_no : $('#form-step-1 input[name="consignee_contact_no"]').val(),
                    consignee_mobile_no : consignee_mobile_nos,
                    consignee_telephone_no : consignee_telephone_nos,
                    consignee_email : $('#form-step-1 input[name="consignee_email"]').val(),

                    consignee: {
                        use_company :  $('#form-step-1 input[name="consignee.use_company"]').is(':checked') ? 1 : 0,
                        lname :  $('#form-step-1 input[name="consignee.lname"]').val(),
                        fname :  $('#form-step-1 input[name="consignee.fname"]').val(),
                        mname :  $('#form-step-1 input[name="consignee.mname"]').val(),
                        company :  $('#form-step-1 input[name="consignee.company"]').val(),
                        email :  $('#form-step-1 input[name="consignee.email"]').val(),
                        // contact_no :  $('#form-step-1 input[name="consignee.contact_no"]').val(),
                        consignee_mobile_no : consignee_sub_mobile_nos,
                        consignee_telephone_no : consignee_sub_telephone_nos,
                        business_category_id :  $('#form-step-1 select[name="consignee.business_category_id"]').val(),
                        street :  $('#form-step-1 input[name="consignee.street"]').val(),
                        barangay :  $('#form-step-1 select[name="consignee.barangay"]').find('option:selected').text(),
                        sectorate_no : $('#form-step-1 select[name="consignee.barangay"]').find('option:selected').data('sectorate_no'),
                        city :  $('#form-step-1 select[name="consignee.city"]').val(),
                    },
                };

                var form_step_2 = {
                    payment_type: $('#form-step-2 select[name="payment_type"]').val(),
                    destinationbranch_id: $('#form-step-2 select[name="destinationbranch_id"]').val(),
                    shipment_type: $('#form-step-2 select[name="shipment_type"]').val(),
                    pu_checkbox :  $('#form-step-2 input[name="pu_checkbox"]').is(':checked') ? 1 : 0,
                    pu_sector: $('#form-step-2 select[name="pu_sector"]').val(),
                    pu_date: $('#form-step-2 input[name="pu_date"]').val(),
                    pu_street: $('#form-step-2 input[name="pu_street"]').val(),
                    del_checkbox : $('#form-step-2 input[name="del_checkbox"]').is(':checked') ? 1 : 0,
                    del_sector: $('#form-step-2 select[name="del_sector"]').val(),
                    del_street: $('#form-step-2 input[name="del_street"]').val(),
                    declared_value: $('#form-step-2 input[name="declared_value"]').val(),
                    discount_coupon: $('#form-step-2 input[name="discount_coupon"]').val(),
                };

                var item_codes=[];
                var unit_codes = [];
                var quantities = [];

                $('#datatable > tbody > tr').each(function(){
                    var td = $(this).find('td');
                    item_codes.push(td.eq(0).find('.description').val());
                    unit_codes.push(td.eq(1).find('.unit').val());
                    quantities.push(td.eq(2).find('.quantity').val());
                });

                var form_step_3 = {
                    item_description : item_codes,
                    unit : unit_codes,
                    quantity : quantities
                };

                var form_step_4 = {
                    agree: $('#form-step-4 input[name="agree"]').is(':checked') ? 1 : 0
                };
               
                $.ajax({
				url: "{{route('waybills.update',$id)}}",
				type: "PUT",
				dataType: "JSON",
				data : { _token: "{{csrf_token()}}", step1: form_step_1, step2: form_step_2,step3: form_step_3,step4: form_step_4},
				cache: false,
				success: function(result){
					
					swal(result.message, {
						icon: result.type,
						title: result.title
					})
                    .then(function(){
						
                        $('#form-step-1').trigger('reset');
                        $('#form-step-2').trigger('reset');
                        $('#form-step-3').trigger('reset');
                        $('#form-step-4').trigger('reset');
                        $('#form-step-5').trigger('reset');
                        window.location.href="{{url('/waybills')}}";
						
					});
				},
				error: function(xhr,status){
					
                    
                    if(xhr.status==500){
                        var responseJSON = xhr.responseJSON;
                        swal(responseJSON.message, {
                            icon: 'error',
                            title: 'Ooops!'
                        });
                    }else if(xhr.status==408){
                        swal('Please check your internet', {
                            icon: 'error',
                            title: 'Connection time-out'
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
            }
        })
    });



</script>
@include('sector.sector_script')
@endsection
