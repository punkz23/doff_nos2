@extends('layouts.gentelella')

@section('bread-crumbs')

@endsection

@section('css')

<link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />

@endsection

@section('content')




<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>ADD SHIPPER/CONSIGNEE</h2>

          <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <form class="form-horizontal visible" id="form-contact">
                @csrf
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-3 no-padding-right">

                        </div>
                        <div class="col-xs-12 col-sm-9">
                            <div class="checkbox">
                                <label>
                                    <input name="use_company" type="checkbox" class="ace" />
                                    <span class="lbl"> Use Company</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 name">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Lastname: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" name="lname" class="form-control" required>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2 name"></div>

                <div class="col-xs-12 name">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Firstname: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" name="fname" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-2 name"></div>

                <div class="col-xs-12 name">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Middlename: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" name="mname" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-2 name"></div>

                <div class="col-xs-12 company" style="display:none;">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Company: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="text" name="company" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>



                <div class="space-2 company" style="display:none;"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Email:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Contact #: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="number" name="contact_no" class="form-control" required>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>



                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Business Type:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <select name="business_category_id" class="select2">
                                    <option value="none" selected disabled>--Select business type--</option>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                                        <!-- <div class="space-2"></div>

                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Address Label:</label>

                                                <div class="col-xs-12 col-sm-9">
                                                    <div class="clearfix">
                                                        <input type="text" name="address_label" class="form-control" placeholder="HOME/WORK">
                                                    </div>
                                                </div>

                                            </div>
                                        </div> -->





                <div class="space-2"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">City/Municipality: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <select name="city" class="select2 cities" id="city">
                                    <option value="none" disabled selected>--Please select city--</option>
                                    @foreach($provinces as $province)
                                        <optgroup label="{{$province->name}}">
                                            @foreach($province->city as $city)
                                            <option data-province="{{$province->province_name}}" data-postal_code="{{$city->postal_code}}" value="{{$city->cities_id}}">{{$city->cities_name}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Barangay: <font color="red">*</font></label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                {{-- <input type="text" name="barangay" class="form-control"> --}}
                                <select name="barangay" class="form-control">
                                    <option value="none" selected disabled="disabled">--Please select barangay--</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Street/Bldg/Room:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input onkeypress="return max_street(event)"  maxlength="100" type="text" name="street" class="form-control" placeholder="">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Province:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="hidden" name="province">
                                <label id="province"></label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="space-2"></div>



                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Postal Code:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <input type="hidden" name="postal_code">
                                <label id="postal_code"></label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="clearfix pull-right">
                        <button  type="submit" class="btn btn-success submit">Save</button>
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
<script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>
<script src="/theme/js/sweetalert.min.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($) {
        $('select.cities').append(localStorage.getItem('dropdown_provinces'));
        $('select[name="business_category_id"]').append(localStorage.getItem('dropdown_businesstypes'));

        $('select.cities').change(function(){
            var form_id = $(this).closest('form').attr('id');
            // var province_for = 'province';
            // var postal_for = 'postal_code';
            // $('#'+form_id+' input[name="'+province_for+'"]').val($(this).find('option:selected').data('province'));
            // $('#'+form_id+' label#'+province_for).html($(this).find('option:selected').data('province'));
            // $('#'+form_id+' input[name="'+postal_for+'"]').val($(this).find('option:selected').data('postal_code'));
            // $('#'+form_id+' label#'+postal_for).html($(this).find('option:selected').data('postal_code'));
            $select=$(this).closest('form').find('select[name="barangay"]');

            // $id = $(this).find('option:selected').data('postal_code');
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
                $('.company').removeAttr('style');
                $('input[name="company"]').attr('required',true);

            }else{
                $('.name').removeAttr('style');
                $('input[name="lname"]').attr('required',true);
                $('input[name="fname"]').attr('required',true);
                $('input[name="mname"]').attr('required',true);
                $('.company').attr('style','display:none');
                $('input[name="company"]').removeAttr('required');
            }
        });

        $('.select2').css('width','100%').select2({allowClear:true});

        $('select[name="city"]').change(function(e){
            var form_id = $(this).closest('form').attr('id');
            var province_for = 'province';
            var postal_for = 'postal_code';
            $('#'+form_id+' input[name="'+province_for+'"]').val($(this).find('option:selected').data('province'));
            $('#'+form_id+' label#'+province_for).html($(this).find('option:selected').data('province'));
            $('#'+form_id+' input[name="'+postal_for+'"]').val($(this).find('option:selected').data('postal_code'));
            $('#'+form_id+' label#'+postal_for).html($(this).find('option:selected').data('postal_code'));
            e.preventDefault();
        })

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
















                //documentation : http://docs.jquery.com/Plugins/Validation/validate


                jQuery.validator.addMethod("phone", function (value, element) {
                    return this.optional(element) || /^\(\d{3}\) \d{3}\-\d{4}( x\d{1,6})?$/.test(value);
                }, "Enter a valid phone number.");

                var form_contact = $('#form-contact').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {

                        email: {
                            email:true
                        },

                        contact_no: {
                            required: true
                        },
                        province: {
                            required: true
                        },
                        city: {
                            required: true
                        },
                        barangay: {
                            required: true,
                        },

                        postal_code: {
                            required: true,
                        }
                    },

                    messages: {

                        email: {

                            email: "Please provide a valid email."
                        },

                        contact_no: "Contact is required",
                        province: "Province is required",
                        city: "City is required",
                        barangay: "Barangay is required",

                        postal_code: "Postal Code is required",

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
                        form_data.append('province',$('input[name="province"]').val());
                        form_data.append('city',$('select[name="city"]').find('option:selected').text());
                        form_data.append('sectorate_no',$('select[name="barangay"]').find('option:selected').data('sectorate_no'))
                        form_data.append('barangay',$('select[name="barangay"]').find('option:selected').text())
                        // $('#form-contact .submit').attr('disabled',true);
                        // $('#form-contact .submit').html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
                        $.ajax({
                            url: "{{route('contacts.store')}}",
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

                                swal(result.message, {
                                  icon: result.type,
                                  title: result.title
                                }).then(function(){
                                    var index = $('select[name="shipper_id"] > option').length;
                                    // $('#shipper_id_chosen').find('.chosen-results').append('<li class="active-result" data-option-array-index="'+(index-1)+'" style="">'+result.data['fileas']+'</li>');
                                    $('select.contacts').append('<option value="'+result.data['contact_id']+'">'+result.data['fileas']+'</option>');
                                    $('#form-contact').trigger('reset');
                                });
                            },
                            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
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
