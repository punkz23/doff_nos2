<script type="text/javascript">

$(document).ready(function(){

    // Populate dropdown
    $('.cities').change(function(){
        var form_id = $(this).closest('form').attr('id');
        $select=$(this).closest('div.new-contact').find('.barangay');
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
    // Populate dropdown

    // ON CHANGE SHIPPER/CONSIGNEE
    $('.contacts').on('change',function(e){

        var elementName = e.target.name=="shipper_id" ? "shipper" : "consignee";
        middle_name_func(e.target.name);

        $("#"+elementName+"_email").attr("readonly", false);
        $(".mobile_no_"+elementName).attr("readonly", false);
        $(".add-mobile-"+elementName).attr("disabled", false);
        $(".telephone_no_"+elementName).attr("readonly", false);
        $(".add-telephone-"+elementName).attr("disabled", false);

        $("."+elementName+"_add_mn").remove();
        $("."+elementName+"_add_tn").remove();

        if(e.target.value!=="new"){

            if(e.target.value!="none"){
                var addresses = JSON.parse(e.target.selectedOptions[0].dataset.address);
                var c_qr=e.target.selectedOptions[0].dataset.qr;
                var innerHTML = '<option value="none" selected disabled>--PLEASE SELECT ADDRESS--</option>';

                if(c_qr =='' || c_qr == null){
                    innerHTML += '<option value="new">--ADD NEW--</option>';
                }else{

                    $("#"+elementName+"_email").attr("readonly", true);
                    $(".mobile_no_"+elementName).attr("readonly", true);
                    $(".add-mobile-"+elementName).attr("disabled", true);
                    $(".telephone_no_"+elementName).attr("readonly", true);
                    $(".add-telephone-"+elementName).attr("disabled", true);

                }

                for(var i=0; i<addresses.length;i++){
                    innerHTML +=  '<option @if(Route::currentRouteName() !='waybills.edit') '+(addresses[i]['address_def'] ==1 ? 'selected' : '' )+' @endif value="'+addresses[i]['useraddress_no']+'">'+addresses[i]['full_address']+'</option>';
                }
            }
            var contact_numbers = $(this).find('option:selected').data('contact_numbers');

            if(contact_numbers!==undefined){

                var mobile_numbers = contact_numbers.filter(function(obj){ return obj.type==1; });
                var telephone_numbers = contact_numbers.filter(function(obj){ return obj.type==2; });

                if(mobile_numbers.length>0){
                    $('input[name="'+elementName+'_mobile_no[]"]').eq(0).val(mobile_numbers[0]['contact_no']);
                }else{
                    $('input[name="'+elementName+'_mobile_no[]"]').eq(0).val(e.target.selectedOptions[0].dataset.contact_no);
                }
                if(mobile_numbers.length>1){
                    $table = $('input[name="'+elementName+'_mobile_no[]"]').closest('table');
                    $html = '<tr class="'+elementName+'_add_mn">'+
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
            $('input[name="'+elementName+'_mobile_no[]"]').closest('.form-group').show();
            $('input[name="'+elementName+'_telephone_no[]"]').closest('.form-group').show();
            $('.'+elementName+'-form').hide();
            $('.'+elementName+'-info').hide();
            $('.'+elementName+'-address').hide();

        }else{
            $('select[name="'+elementName+'_address_id"]').closest('.form-group').hide();
            $('input[name="'+elementName+'_contact_no"]').closest('.form-group').hide();
            $('input[name="'+elementName+'_mobile_no[]"]').closest('.form-group').hide();
            $('input[name="'+elementName+'_telephone_no[]"]').closest('.form-group').hide();
            $('input[name="'+elementName+'_email"]').closest('.form-group').hide();
            $('.'+elementName+'-form').show();
            $('.'+elementName+'-info').show();
            $('.'+elementName+'-address').show();
        }
        var elementAddress = $('select[name="'+elementName+'_address_id"]').next().find('.select2-selection__rendered');
        elementAddress.removeAttr('title');
        elementAddress.empty();


    });

    function middle_name_func(val){

        if(val=='shipper_id'){
            $(".div_shipper_mname_update").hide();
            $("#shipper_mname_update").val(0);
            $(".shipper_mname_update_text").val('');
        }
        else if(val=='consignee_id'){
            $(".div_consignee_mname_update").hide();
            $("#consignee_mname_update").val(0);
            $(".consignee_mname_update_text").val('');
        }
        if( $("#"+val).val() != 'new' && $("#"+val).val() !='' ){

            $.ajax({
                url: "{{url('/get-customer-details')}}/"+$("#"+val).val(),
                type: "GET",
                success: function(result){
                    var result = JSON.parse(result);
                    if(result.length > 0){
                        $.each(result,function(){
                            if( Number(this.use_company)==0 && (this.mname == '' || this.mname == null ) ){


                                if(val=='shipper_id'){
                                    $(".div_shipper_mname_update").show();
                                    $("#shipper_mname_update").val(1);
                                }
                                else if(val=='consignee_id'){
                                    $(".div_consignee_mname_update").show();
                                    $("#consignee_mname_update").val(1);
                                }

                            }
                        });
                    }
                }
            });

        }

    }
    // ON CHANGE SHIPPER/CONSIGNEE

    // ON CHANGE SHIPPER/CONSIGNEE ADDRESS
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
    // ON CHANGE SHIPPER/CONSIGNEE ADDRESS

    // ON CHANGE CHECKBOX USE COMPANY
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
    // END ON CHANGE CHECKBOX USE COMPANY

    // ON CHANGE SHIPMENT TYPE
    $('select[name="shipment_type"]').change(function(){

        var val = $(this).val()==="OTHERS" ? (  $('input[name="declared_value"]').val() < 2000 ? 2000 : $('input[name="declared_value"]').val() ) : (($(this).val()==="BREAKABLE" || $(this).val()==="PERISHABLE") ? 1000 : 500);
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
    // ON CHANGE SHIPMENT TYPE



    @if(Route::currentRouteName() !='waybills.edit')
        $("#shipper_id").trigger('change');
        $("#consignee_id").trigger('change');
    @else
        $(".shipment_type").trigger('change');
    @endif

    // JQUERY VALIDATIONS

    jQuery.validator.addMethod("notEqualToGroup", function (value, element, options) {

        var elems = $(element).parents('form').find(options[0]);
        var valueToCompare = value;
        var matchesFound = 0;

        jQuery.each(elems, function () {
            thisVal = $(this).val();
            if($('#form-step-1').find('select[name="shipper_id"]').val()!==$('#form-step-1').find('select[name="consignee_id"]').val()){
                if (thisVal == valueToCompare) {
                    matchesFound++;
                }
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
            // shipping_cid: {
            //     required: function(){
            //         if( $('input[name="online_booking_type_2"]').is(':checked')==true ){
            //             return true;
            //         }
            //     }
            // },
            // shipping_cemail :{
            //     required: function(){
            //         if( $('input[name="online_booking_type_2"]').is(':checked')==true ){
            //             return true;
            //         }
            //     },
            //     email : true,
            //     notEqualToGroup : ['.email-address']
            // },
            // shipping_cmobile_no : {
            //     required: function(){
            //         if( $('input[name="online_booking_type_2"]').is(':checked')==true ){
            //             return $('#form-step-1 input[name="shipping_clandline_no"]').val()==="";
            //         }
            //     },
            //     maxlength: 11,
            //     minlength: 11
            // },
            // shipping_clandline_no : {
            //     required: function(){
            //         if( $('input[name="online_booking_type_2"]').is(':checked')==true ){
            //             return $('#form-step-1 input[name="shipping_cmobile_no"]').val()==="";
            //         }
            //     },
            //     maxlength: 10,
            //     minlength: 7
            // },
            shipping_cname : {
                required: function(){
                    if($('select[name="shipping_cid"]').val()==="new" && $('input[name="online_booking_type_2"]').is(':checked')==true ){
                        return true;
                    }
                },
                maxlength: 50
            },
            // shipping_address_id : {
            //     required: function(){
            //         if( $('input[name="online_booking_type_2"]').is(':checked')==true ){
            //             return true;
            //         }
            //     },
            //     maxlength: 50
            // },
            shipping_address_city : {
                required: function(){
                    if( $('select[name="shipping_address_id"]').val()==="new" && $('input[name="online_booking_type_2"]').is(':checked')==true ){
                        return true;
                    }
                },
                maxlength: 50
            },
            shipping_address_brgy : {
                required: function(){
                    if( $('select[name="shipping_address_id"]').val()==="new" && $('input[name="online_booking_type_2"]').is(':checked')==true ){
                        return true;
                    }
                },
                maxlength: 50
            },
            shipper_id: {
                required: function(){
                    if($('select[name="shipper_id"]').val()!=="new"){
                        return true;
                    }
                }
            },
            shipper_mname_update_text: {
                required: function(){
                    if($('#form-step-1 input[name="shipper_mname_update"]').val()==="1"){
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
                maxlength: 11

            },
            "shipper_telephone_no[]" : {
                required: function(){
                    if($('select[name="shipper_id"]').val()!=="new"){
                        return $('#form-step-1 input[name="shipper_mobile_no[]"]').val()==="";
                    }
                },
                maxlength: 10,
                minlength: 7

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
            "shipper.mname" : {
                required: function(){
                    if($('select[name="shipper_id"]').val()==="new" && $('input[name="shipper.use_company"]').is(':checked')==false){
                        return true;
                    }
                },
                maxlength: 50
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
                minlength: 7
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
            consignee_mname_update_text: {
                required: function(){
                    if($('#form-step-1 input[name="consignee_mname_update"]').val()==="1"){
                        return true;
                    }
                }
            },
            consignee_email : {
                email : true,
                notEqualToGroup : ['.email-address']
            },
            // consignee_contact_no : {required : true, maxlength: 55},
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
                maxlength: 10,
                minlength: 7

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
            "consignee.mname" : {
                required: function(){
                    if($('select[name="consignee_id"]').val()==="new" && $('input[name="consignee.use_company"]').is(':checked')==false){
                        return true;
                    }
                },
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
            'consignee.email' : {
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
                maxlength: 10,
                minlength: 7
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
        messages: {
            // "shipping_cmobile_no" : {
            //     required: "Mobile number is required once telephone is not filled out"
            // },
            // "shipping_clandline_no" : {
            //     required: "Mobile number is required once telephone is not filled out"
            // },
            "shipper_mobile_no[]" : {
                required: "Mobile number is required once telephone is not filled out"
            },
            "shipper_telephone_no[]" : {
                required: "Telephone number is required once mobile is not filled out"
            },
            "consignee_mobile_no[]" : {
                required: "Mobile number is required once telephone is not filled out"
            },
            "consignee_telephone_no[]" : {
                required: "Telephone number is required once mobile is not filled out"
            },
            "shipper.shipper_mobile_no[]" : {
                required: "Mobile number is required once telephone is not filled out"
            },
            "shipper.shipper_telephone_no[]" : {
                required: "Telephone number is required once mobile is not filled out"
            },
            "consignee.consignee_mobile_no[]" : {
                required: "Mobile number is required once telephone is not filled out"
            },
            "consignee.consignee_telephone_no[]" : {
                required: "Telephone number is required once mobile is not filled out"
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

    jQuery.validator.addMethod("exactEqual", function(value, element, param) {
    return this.optional(element) || value == param;
    }, $.validator.format("Value must be equal to {0}"));

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
            if($table.find('tbody').find('tr').length<2){
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
            if($table.find('tbody').find('tr').length<2){
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
            if($table.find('tbody').find('tr').length<2){
                $html = '<tr>'+
                            '<td>'+
                                '<div class="input-group mobile-number" style="margin-bottom:0;">'+
                                    '<input type="number" class="form-control telephone_no" name="'+$for+'_telephone_no[]" placeholder="########" maxlength="10" minlength="7" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">'+
                                    '<span class="input-group-btn">'+
                                        '<button type="button" class="btn btn-danger delete-telephone"><i class="fa fa-trash"></i></button>'+
                                    '</span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                $table.append($html);
                $input.rules('remove');
            }
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
            if($table.find('tbody').find('tr').length<2){
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
        }
    });

    $('.table-telephone').on('click','.delete-telephone',function(){
        $(this).closest('tr').remove();
    });

    var validation_2 = $('#form-step-2').validate({
        rules: {
            pasabox_branch_receiver: {
                required: function(){
                    if( $('#form-step-1 input[name="online_booking_type_2"]').is(':checked') ){
                        return true;
                    }
                }
            },
            payment_type : {
                required: true
            },
            mode_payment: {
                required: function(){
                if($('#form-step-2 select[name="payment_type"]').val()=='CI'){
                    return true;
                }
                }
            },
            mode_payment_email: {
                email : true,
                notEqualToGroup : ['.email-address'],
                required: function(){
                    if
                    (   $('#form-step-2 select[name="payment_type"]').val()=='CI'
                        &&
                        $('#form-step-2 input[name="mode_payment_os"]').is(':checked')
                    ){
                    return true;
                    }
                }
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

    var validation_5 = $('#form-step-5').validate({

        gcash_reference_no: {
            required: function(){
                if( $('#form-step-1 input[name="online_booking_type_2"]').is(':checked') ){
                    return true;
                }
            }
        },
        gcash_pdate: {
            required: function(){
                if( $('#form-step-1 input[name="online_booking_type_2"]').is(':checked') ){
                    return true;
                }
            }
        },
        gcash_cemail: {

            required: function(){
                if( $('#form-step-1 input[name="online_booking_type_2"]').is(':checked') ){
                    return true;
                }
            },
            email : true,
            notEqualToGroup : ['.email-address']
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

    // END JQUERY VALIDATIONS




});

function rebate_point_func(){
    shipper_qr_code=$("#shipper_qr_code").val();
    if(shipper_qr_code==''){
        shipper_qr_code='NONE';
    }

    consignee_qr_code=$("#consignee_qr_code").val();
    if(consignee_qr_code==''){
        consignee_qr_code='NONE';
    }

    shipper_qr_code_cid=$("#shipper_qr_code_cid").val();
    if(shipper_qr_code_cid==''){
        shipper_qr_code_cid='NONE';
    }

    consignee_qr_code_cid=$("#consignee_qr_code_cid").val();
    if(consignee_qr_code_cid==''){
        consignee_qr_code_cid='NONE';
    }
    $(".div_rebate").hide();
    $("#div_rebate_text").html('');
    jQuery.ajax({
        url: "{{url('/get-rebate-points')}}/"+$("#shipper_id").val()+'/'+$("#consignee_id").val()+
        '/'+consignee_qr_code+'/'+consignee_qr_code+
        '/'+shipper_qr_code_cid+'/'+consignee_qr_code_cid,
        method: 'get',
        success: function(result){

        if(Number(result) > 0){
            $(".div_rebate").show();
            $(".div_rebate_text").html("<font size='3'><i class='fa fa-lightbulb-o'></i> Points earned: <b>"+result+
            " or P"+Number(result).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+"</b></font>."+
            " Please transact with our branches within 7 days."+
            " Earned points will be automatically deducted from your freight charges upon creation of waybill.");
        }

    }});
}

//PASABOX
$("#shipping_cid").change(function(e){

    $("#p_shipping_cname").hide();
    $(".shipping_cname").val('');
    $(".shipping_cemail").val('');
    $(".shipping_cmobile_no").val('');
    $(".shipping_clandline_no").val('');
    var innerHTML = '<option value="none" selected>--PLEASE SELECT ADDRESS--</option>';
    if(this.value=='none'){
        $('select[name="shipping_address_id"]').html(innerHTML);
        $(".shipping_address_id").val('none').trigger('change');

    }
    else if(this.value=='new'){
        $("#p_shipping_cname").show();
        innerHTML += '<option value="new">--ADD NEW--</option>';
        $('select[name="shipping_address_id"]').html(innerHTML);
        $(".shipping_address_id").val('new').trigger('change');
    }else{

        var addresses = JSON.parse(e.target.selectedOptions[0].dataset.address);
        var c_qr=e.target.selectedOptions[0].dataset.qr;
        $(".shipping_cemail").val(e.target.selectedOptions[0].dataset.email);


        var contact_numbers = $(this).find('option:selected').data('contact_numbers');

            if(contact_numbers!==undefined){

                var mobile_numbers = contact_numbers.filter(function(obj){ return obj.type==1; });
                var telephone_numbers = contact_numbers.filter(function(obj){ return obj.type==2; });

                if(mobile_numbers.length>0){
                    $('input[name="shipping_cmobile_no"]').eq(0).val(mobile_numbers[0]['contact_no']);
                }

                if(telephone_numbers.length>0){
                    $('input[name="shipping_clandline_no"]').eq(0).val(telephone_numbers[0]['contact_no']);
                }

            }
        if(c_qr =='' || c_qr == null){
            innerHTML += '<option value="new">--ADD NEW--</option>';
        }else{

            $(".shipping_cemail").attr("readonly", true);
            $(".shipping_cmobile_no").attr("readonly", true);
            $(".shipping_clandline_no").attr("readonly", true);


        }


        for(var i=0; i<addresses.length;i++){
            innerHTML +=  '<option @if(Route::currentRouteName() !='waybills.edit') '+(addresses[i]['address_def'] ==1 ? 'selected' : '' )+' @endif value="'+addresses[i]['useraddress_no']+'">'+addresses[i]['full_address']+'</option>';
        }

        $('select[name="shipping_address_id"]').html(innerHTML);

    }

    $(".shipping_address_id").trigger('change');
});


 $(".shipping_address_id").change(function(){

    $(".shipping-address").hide();
    $(".shipping_address_city").val('');
    $(".shipping_address_street").val('');
    if($(".shipping_address_id").val()=='new'){
        $(".shipping-address").show();
    }

 });



$("#online_booking_type_2").click(function(){

    document.getElementById('online_booking_type_2').checked=true;
    document.getElementById('online_booking_type_1').checked=false;
    $(".div_shipping_company").show();
    $("#div_terms_ol").hide();
    $("#div_terms_pasabox").show();
    $("#li_step4").show();
    $(".pickup_div").hide();

    $(".step_no5").html('5');
    $(".step_descr5").html('Step 5<br /><small>Terms and Condition</small>');

    $("#shipping_cid").val('none').trigger('change');
    get_branch_receiver();
    get_gcash_info();
    change_ol_reference('pasabox');
    $('input[name="discount_coupon"]').trigger('keyup');

});
$("#online_booking_type_1").click(function(){

    document.getElementById('online_booking_type_1').checked=true;
    document.getElementById('online_booking_type_2').checked=false;

    $(".div_shipping_company").hide();
    $("#div_terms_ol").show();
    $("#div_terms_pasabox").hide();
    $("#li_step4").hide();
    $(".pickup_div").show();
    $(".step_no5").html('4');
    $(".step_descr5").html('Step 4<br /><small>Terms and Condition</small>');
    change_ol_reference('regular');
    $('input[name="discount_coupon"]').trigger('keyup');

});

function change_ol_reference(action){
    $.ajax({
        url: "{{url('/change-ol-reference')}}/"+action,
        type: "GET",
        success: function(data){
            $('#form-step-4 input[name="reference_no"]').val(data);
        }
    });
}

$('.cities_shipping').change(function(){
    $id=this.value;
    $.ajax({
        url: "{{url('/get-sector')}}/"+$id,
        type: "GET",
        success: function(data){
            $(".shipping_address_brgy").html('<option value="none" selected disabled>--Please select barangay--</option>');
            $.each(data.data,function(){
                $(".shipping_address_brgy").append('<option value="'+this.sectorate_no+'" data-sectorate_no="'+this.sectorate_no+'">'+this.barangay+'</option>');
            });


        }
    });
});
function get_gcash_info(){
    $("#gcash_id").val('');
    $.ajax({
        url: "{{url('/get-gcash-info')}}",
        method: 'get',
        success: function(result){
            var result = JSON.parse(result);
            $.each(result,function(){

               $("#gcash_id").val(this.bank_no);
            });


    }});
}

function get_branch_receiver(){

    $(".pasabox_branch_receiver").html('');
    $.ajax({
        url: "{{url('/get-pasabox-branch-receiver')}}",
        method: 'get',
        success: function(result){

            var result = JSON.parse(result);

            $.each(result,function(){
                ap=this.pasabox_authorized_employee;
                ap_fileas='';
                if( ap != null && ap !='' ){
                    ap_fileas=ap['fileas'];
                }

                ap2=this.pasabox_alternative_authorized_employee;
                ap2_fileas='';
                if( ap2 != null && ap2 !='' ){
                    ap2_fileas=ap2['fileas'];
                }

                $(".pasabox_branch_receiver").append('<option data-gcash_qr="'+this.gcash_qr_code+'" data-gcash_no="'+this.gcash_account_no+'" data-gcash_name="'+this.gcash_account_name+'" data-address="'+this.branch_address+'" data-ap_no="'+this.pasabox_incharge_employee_cno+'" data-ap="'+ap_fileas+'"  data-aap_no="'+this.pasabox_incharge_alternative_employee_cno+'" data-aap="'+ap2_fileas+'" data-pfee="'+this.pasabox_convinience_fee+'" value="'+this.branchoffice_no+'" >'+this.branchoffice_description+'</option>');
                if(this.branchoffice_no=='008'){
                    $(".pasabox_branch_receiver").val('008').trigger('change');
                }
            });
            @if( Route::currentRouteName()=='waybills.edit' && $data->pasabox ==1  )
                $(".pasabox_branch_receiver").val('{{ $data->pasabox_branch_receiver }}').trigger('change');
            @endif

    }});

}

$(".pasabox_branch_receiver").change(function(e){

    emp_name=e.target.selectedOptions[0].dataset.ap;
    emp_cno=e.target.selectedOptions[0].dataset.ap_no;

    emp_name2=e.target.selectedOptions[0].dataset.aap;
    emp_cno2=e.target.selectedOptions[0].dataset.aap_no;

    b_address=e.target.selectedOptions[0].dataset.address;
    gcash_name=e.target.selectedOptions[0].dataset.gcash_name;
    gcash_no=e.target.selectedOptions[0].dataset.gcash_no;
    gcash_qr=e.target.selectedOptions[0].dataset.gcash_qr;
    pfee=e.target.selectedOptions[0].dataset.pfee;

    if(emp_cno==null){
        emp_cno='';
    }
    if(emp_cno2==null){
        emp_cno2='';
    }
    if(emp_name !=''){
        $(".pasabox_branch_receiver_emp").html('<br><i class="fa fa-user"></i> '+emp_name+
        '<br><i class="fa fa-phone"></i> '+emp_cno);
    }
    if(emp_name2 !='' && emp_name2 !=emp_name ){
        $(".pasabox_branch_receiver_emp").append('<br><i class="fa fa-user"></i> '+emp_name2+
        '<br><i class="fa fa-phone"></i> '+emp_cno2);
    }
    $(".pasabox_branch_receiver_emp").append('<br><i class="fa fa-map-marker"></i> '+b_address);

    $("#h4_gcash_name").html('<b>'+gcash_name+'</b>');
    $('#gcash_qr').attr('src',gcash_qr);
    $("#gcash_amount").val(Number(pfee).toFixed(2));
    $("#gcash_branch_aname").val(gcash_name);
    $("#gcash_branch_ano").val(gcash_no);

});

$("#gcash_reference_no").change(function(){
    @if( Route::currentRouteName()=='waybills.edit'  )
        onl_ref='{{ $data->reference_no }}';
    @else
        onl_ref='NONE';
    @endif
    $.ajax({
        url: "{{url('/get-online-payment-exist')}}/"+$("#gcash_reference_no").val()+'/'+onl_ref,
        method: 'get',
        success: function(result){

            if(Number(result) > 0){

                alert('Sorry but reference no. already exist.');
                $("#gcash_reference_no").val('');

                $selected_step = $wizard_steps.find('li').find('a.selected');
                if(
                    document.getElementById('online_booking_type_2').checked
                    && parseInt($selected_step.find('.step_no')[0].innerHTML)==5
                    &&  $("#gcash_reference_no").val() ==''
                ){
                    $('.buttonPrevious').trigger('click');
                }

            }


    }});

});
$(document).ready(function() {
    $(".selectdata_group").select2({
        templateResult: function (data, container) {

            if( typeof $(data.element).data('icon') ==='undefined'){
                icon_txt='';
            }else{
                icon_txt=$(data.element).data('icon');
            }
            return $('<span><i class="fa ' +icon_txt+ '"></i> ' +data.text + '</span>');
        }

    });
});


function get_gcash_cf_info(){
    $("#gcash_reference_no").attr("readonly", false);
    $("#gcash_pdate").attr("readonly", false);
    $("#gcash_cemail").attr("readonly", false);
    $("#gcash_reference_no").val('');
    $("#gcash_pdate").val('');
    $("#gcash_cemail").val('');
    $("#cf_onl_id").val('');
    @if( Route::currentRouteName()=='waybills.edit'  )
    $.ajax({
        url: "{{url('/get-gcash-cf-info')}}/{{ $data->reference_no }}",
        method: 'get',
        success: function(result){
            var result = JSON.parse(result);
            $.each(result,function(){
                $("#gcash_reference_no").val(this.verification_code);
                $("#gcash_pdate").val(this.onlinepayment_date);
                $("#gcash_cemail").val(this.confirmation_email);
                $("#cf_onl_id").val(this.onlinepayment_id);

                if(Number(this.confirmation_status) !=0  ){
                    $("#gcash_reference_no").attr("readonly", true);
                    $("#gcash_pdate").attr("readonly", true);
                    $("#gcash_cemail").attr("readonly", true);
                }

            });


    }});
    @endif
}

@if( Route::currentRouteName()=='waybills.edit'  )
    @if( $data->pasabox ==1 )
        get_branch_receiver();
        get_gcash_info();
        get_gcash_cf_info();
    @else
        $("#online_booking_type_1").trigger('click');
    @endif
@else
$("#online_booking_type_1").trigger('click');
@endif
$(".cities").html($(".address_city").html());
$(".cities_shipping").html($(".address_city").html());
$(".biz_category").html($(".business_category").html());

function max_street(evt) {

    if (evt.target.value.length >= 100) {
        return false;
    }
    return true;
}
</script>
