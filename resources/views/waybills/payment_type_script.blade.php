<script type="text/javascript">

    $(document).ready(function(){

        $('.mode_payment_is').click(function(){
            this.checked=true;
            $(".mode_payment_os").attr("checked", false);
            $(".div_mode_payment_email").hide();
            $(".div_mode_payment_msg").hide();

        });
        $('.mode_payment_os').click(function(){
            this.checked=true;
            $(".mode_payment_is").attr("checked", false);
            $(".div_mode_payment_email").show();
            $(".div_mode_payment_msg").show();
        });
        $(".mode_payment").change(function(){
            $(".div_mode_payment_io").hide();
            $('.mode_payment_is').click();
            if(this.value==2){
                $(".div_mode_payment_io").show();
            }
        });
        $(".pca_use_adv_payment").click(function(){
            if($(".payment_type").val()==null || $(".payment_type").val()=='' || $(".payment_type").val()=='none'){
                $(".payment_type").val('CI');
            }
            $(".payment_type").trigger('change');
        });
        $(".payment_type").change(function(){
            $(".div_mode_payment").hide();
            adv_payment=0;
            if($(".pca_use_adv_payment").length > 0){
                if($('input[name="pca_use_adv_payment"]').is(':checked')){
                    adv_payment=1;
                }
            }
            if(this.value=='CI' && adv_payment==0 ){
                $('.mode_payment').trigger('change');
                $(".div_mode_payment").show();
            }
            $('.mode_payment').trigger('change');
        });

        // ON KEYUP DISCOUNT COUPON
        $('input[name="discount_coupon"]').keyup(function(){

            check_discount_coupon();
        });
        // $('input[name="discount_coupon"]').change(function(){
        //     check_discount_coupon();
        // });
        function check_discount_coupon(){
            $(".div-discount-coupon-alert").hide();
            $(".div-discount-coupon-info").hide();

            $('input[name="discount_coupon_pickup"]').val(0);
            $('input[name="discount_coupon_delivery"]').val(0);

            if($('input[name="discount_coupon"]').val() !=''){

                $.ajax({
                url: "{{url('/check-discount-coupon')}}",
                type: "POST",
                dataType: "JSON",
                data : {
                    _token: "{{csrf_token()}}",
                    discount_coupon: btoa($('input[name="discount_coupon"]').val()),
                    s_dc_fname: btoa($('input[name="s_dc_fname"]').val()),
                    s_dc_mname: btoa($('input[name="s_dc_mname"]').val()),
                    s_dc_lname: btoa($('input[name="s_dc_lname"]').val()),
                    c_dc_fname: btoa($('input[name="c_dc_fname"]').val()),
                    c_dc_mname: btoa($('input[name="c_dc_mname"]').val()),
                    c_dc_lname: btoa($('input[name="c_dc_lname"]').val())
                },
                cache: false,
                success: function(result){

                // jQuery.ajax({
                //     url: "{{url('/check-discount-coupon')}}/"
                //     +btoa($('input[name="discount_coupon"]').val())
                //     +"/"+btoa($('input[name="s_dc_fname"]').val())
                //     +"/"+btoa($('input[name="s_dc_mname"]').val())
                //     +"/"+btoa($('input[name="s_dc_lname"]').val())
                //     +"/"+btoa($('input[name="c_dc_fname"]').val())
                //     +"/"+btoa($('input[name="c_dc_mname"]').val())
                //     +"/"+btoa($('input[name="c_dc_lname"]').val())
                //     ,
                //     method: 'get',
                //     success: function(result){

                        //result=jQuery.parseJSON(result);

                        $('input[name="discount_coupon_action"]').val(result['action']);
                        if(Number(result['action'])==0){
                            $(".div-discount-coupon-alert").show();
                            $(".div-discount-coupon-info").hide();
                            $("#discount-coupon-alert-msg").html('<i class="ace-icon fa fa-info-circle"></i> '+result['msg']);
                        }else{
                            $(".div-discount-coupon-alert").hide();
                            $(".div-discount-coupon-info").show();

                            $.each(result['data'],function(){

                                $('input[name="discount_coupon_pickup"]').val(this.pickup_discount);
                                $('input[name="discount_coupon_delivery"]').val(this.delivery_discount);

                                msg='';
                                action=1;

                                if(
                                    Number(this.online_discount)==1
                                    &&
                                    (
                                        $('#online_booking_type_1').length == 0
                                        ||
                                        !document.getElementById('online_booking_type_1').checked
                                    )
                                ){

                                    //msg='Discount coupon is for PASABOX transaction.';
                                    msg='Invalid Coupon.';
                                    action=0;
                                    $('input[name="discount_coupon_action"]').val(0);
                                }

                                if(
                                    Number(this.pasabox_discount)==1
                                    &&
                                    (
                                        $('#online_booking_type_2').length == 0
                                        ||
                                        !document.getElementById('online_booking_type_2').checked
                                    )
                                ){

                                    //msg='Discount coupon is for PASABOX transaction.';
                                    msg='Invalid Coupon.';
                                    action=0;
                                    $('input[name="discount_coupon_action"]').val(0);
                                }
                                if(action==1){

                                    $("#discount-coupon-info-msg").html('<i class="ace-icon fa fa-check"></i> Valid Coupon.');
                                    // percent_txt='';
                                    // php_txt='';
                                    // if(Number(this.fixed_amount)==1){
                                    //     php_txt='Php ';
                                    // }else{
                                    //     percent_txt='%';
                                    // }
                                    // $("#discount-coupon-info-msg").html('<i class="ace-icon fa fa-info-circle"></i> This are the following condition to avail this discount:');
                                    // count_cond=1;
                                    // $.each(result['details'],function(){
                                    //     $("#discount-coupon-info-msg").append('<br>'+count_cond+'. ');
                                    //     if(Number(this.above_freight)==1){
                                    //         $("#discount-coupon-info-msg").append('Freight should be above '+Number(this.minimum_freight).toFixed(2)+' to avail '+php_txt+(Number(this.discount_amount).toFixed(2))+percent_txt+' discount.');
                                    //     }else{
                                    //         $("#discount-coupon-info-msg").append('Freight should be above '+Number(this.minimum_freight).toFixed(2)+' and not more than '+Number(this.maximum_freight).toFixed(2)+' to avail '+php_txt+(Number(this.discount_amount).toFixed(2))+percent_txt+' discount.');
                                    //     }
                                    //     count_cond++;
                                    // });

                                    // $("#discount-coupon-info-msg").append('<hr><i class="ace-icon fa fa-info-circle"></i> Enjoy this discount when you transact in this following branches: ');
                                    // branch_txt='';
                                    // $.each(result['branch'],function(){
                                    //     if(branch_txt !=''){
                                    //         branch_txt +=', ';
                                    //     }
                                    //     branch_txt +=this.branchoffice_description;
                                    // });

                                    // $("#discount-coupon-info-msg").append('<br>'+branch_txt);

                                    if(Number(this.pickup_discount)==1 && !document.getElementById('pu_checkbox').checked ){
                                        $("#pu_checkbox").prop('checked',true);
                                        get_sector_province('pickup');
                                    }
                                    if(Number(this.delivery_discount)==1 && !document.getElementById('del_checkbox').checked ){
                                        $("#del_checkbox").prop('checked',true);
                                        get_sector_delivery_province('delivery');
                                    }

                                }
                                else{
                                    $(".div-discount-coupon-alert").show();
                                    $(".div-discount-coupon-info").hide();
                                     $("#discount-coupon-alert-msg").html('<i class="ace-icon fa fa-info-circle"></i> '+msg);
                                }
                            });

                        }
                }});
            }else{
                $(".div-discount-coupon-alert").hide();
                $(".div-discount-coupon-info").hide();
                $('input[name="discount_coupon_action"]').val(1);
            }

        }
        //END ON KEYUP DISCOUNT COUPON

    });

</script>
