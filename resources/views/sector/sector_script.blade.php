  
    <script>
    //START DELIVERY & PICK-UP 

    //var eventDates = new Array();
    //var scheduleDates = new Array();
    function pu_datepicker(){
        

        if($("#pu_date").val() != ''){
            pu_date=($(".pu-date-display").val()).split('/');
            getAllDays(pu_date[2], pu_date[0]);
        }
        
        var date = new Date();
        var currentMonth = date.getMonth();
        var currentDate = date.getDate();
        var currentYear = date.getFullYear();
        
        
        $('.pu-date-display').datepicker({
            changeMonth : true,
            changeYear : true,
            minDate: new Date(currentYear, currentMonth, currentDate),
            onChangeMonthYear: getAllDays,
            beforeShowDay : highlightDay
        });
        
    }
    pu_datepicker();
    function getAllDays(year, month, count_data=0)
    {   

        var eventDates = new Array();
        var scheduleDates = new Array();
        var booking_count = new Array();
        var booking_quota = new Array();
        
        $(".pu_sched_date").val('');
        $(".pu_sched_action").val('');
        //$(".count_pu_ddate").val(0);
        $('.pu-date-display').val('');
        $('.pu-date-display').trigger('change');
        
        if($(".pu_sector").val() != ''){
            
            $.ajax({
                url:  "{{url('/get-sector-schedule')}}/"+$(".pu_sector").val()+"/"+year+'-'+month+'/pickup',
                type: "GET",
                success: function(result){

                    d=result.split(",");
                    $.each(d, function(index, value) {
                    
                        if(index > 0){
                            
                            e=value.split("|");
                            //if(	Number(e[1]) > 0 ){
                                eventDates.push(e[0]);
                                scheduleDates.push(e[1]);
                                booking_count.push(e[2]);
                                booking_quota.push(e[3]);
                            //}

                            today=new Date().toISOString().slice(0, 10);
                            today=new Date(today);
                            cdate=new Date(e[0]);
                          
                            if( Number(e[1]) > 0 && count_data==0  && cdate > today && Number(e[2]) < Number(e[3]) ){ 
                                // alert( cdate +' - '+  today)
                                ddate=(e[0]).split('-');
                                $('.pu-date-display').val(ddate[1]+'/'+ddate[2]+'/'+ddate[0]);
                                $('.pu-date-display').trigger('change');
                                count_data++;

                            }
                            
                        }
                        
                    });
                    $(".pu_sched_date").val(btoa(eventDates));
                    $(".pu_sched_action").val(btoa(scheduleDates));
                    $(".pu_booking_count").val(btoa(booking_count));
                    $(".pu_booking_quota").val(btoa(booking_quota));

                    $('.pu-date-display').datepicker('refresh');
                    $('select.pu_sector').removeAttr('disabled');
                }
            }); 
        }
    }
    
    function highlightDay (date){ 

        eventDates=atob($(".pu_sched_date").val());
        scheduleDates=atob($(".pu_sched_action").val());
        booking_count=atob($(".pu_booking_count").val());
        booking_quota=atob($(".pu_booking_quota").val());

        eventDates=(eventDates).split(',');
        scheduleDates=(scheduleDates).split(',');
        booking_count=(booking_count).split(',');
        booking_quota=(booking_quota).split(',');

        if( eventDates.length > 0){

            l=eventDates.length;
            today=new Date();
            
            if ( date.toString().slice(0, 15) == today.toString().slice(0, 15)) {
                        
                // x=0;
            
                // while( x < l ){
                    
                //     data=new Date(eventDates[x]);
                //     cdate=new Date(eventDates[x]);
                    
                    
                //     if ( data.toString().slice(0, 15) == date.toString().slice(0, 15) ) {
                        
                //         if(Number(scheduleDates[x]) > 0){
                            
                //             if( Number($(".count_pu_ddate").val()) ==0 ){
                        
                //                 ddate=(eventDates[x]).split('-');
                                
                //                 $('.pu-date-display').val(ddate[1]+'/'+ddate[2]+'/'+ddate[0]);
                //                 $(".count_pu_ddate").val(Number($(".count_pu_ddate").val())+1);
                                
                //                 return [true, "ui-highlight2"];
                //             }

                            
                //         }

                //     }

                //     x++;	

                
                // }

               return [false, "ui-highlight2"];
                    
            }else{
                
                x=0;
            
                while( x < l ){
                    
                    data=new Date(eventDates[x]);
                    today=new Date();
                    cdate=new Date(eventDates[x]);
                    
                    
                    if ( data.toString().slice(0, 15) == date.toString().slice(0, 15) ) {
                            
                        if(Number(scheduleDates[x]) > 0 && Number(booking_count[x]) < Number(booking_quota[x])  ){
                            
                            // if( Number($(".count_pu_ddate").val())==0 && cdate >= today ){

                            //     ddate=(eventDates[x]).split('-');
                               
                            //     $('.pu-date-display').val(ddate[1]+'/'+ddate[2]+'/'+ddate[0]);
                            //     $(".count_pu_ddate").val(Number($(".count_pu_ddate").val())+1);
                                
                            // }
                            
                            return [true, "ui-highlight"];	
                            
                        }
                        else if(Number(scheduleDates[x]) > 0 && Number(booking_count[x]) >= Number(booking_quota[x])  ){               
                            return [false, "ui-highlight3"];
                        }
                    }

                    x++;	
                
                
                }
                
            }	
     
            return [false];
            
            
        }else{
            
            return [false];

        }
    }


    function get_sector_province(action){
        proceed=0;
        $checkbox_pu = $('#pu_checkbox');
        if($checkbox_pu.is(':checked')==false){
            // $checkbox_pu.prop('checked',true);
            if(Number($("#discount_coupon_"+action).val())==1){
                $("#pu_checkbox").prop('checked',true);
            }else{
                            
                $('select.pu_sector').attr('disabled',true);
                $("select.pu_province").attr('disabled',true);
                $("select.pu_city").attr('disabled',true);
                $('select.pu_sector').html('<option value="">--Select barangay--</option>');
                $("select.pu_province").html('<option value="">--Select Province--</option>');
                $("select.pu_city").html('<option value="">--Select city--</option>');
            }
        }else{
            // $checkbox_pu.prop('checked',false);
            // console.log('to be false')
            
            if(Number($("#discount_coupon_"+action).val())==1){

                $('select[name="pu_province"]').removeAttr('disabled');
                        
                if(action=='pickup'){
                    if(document.getElementById('pu_checkbox').checked){
                        proceed++;
                    }
                }
                else if(action=='delivery'){
                    if(document.getElementById('del_checkbox').checked){
                        proceed++;
                    }
                }
                if( proceed > 0 ){
                    //alert("get-sector-province/"+action);
                    jQuery.ajax({
                    url: "{{url('/get-sector-province')}}/"+action, 
                    method: 'get',
                    success: function(result){

                        if(action=='pickup'){
                        $('div.div-pu-province').html(result);
                            //$(".pu_province").select2({});
                            $(".pu_province").trigger('change');
                            $("select.pu_province").removeAttr('disabled');
                            $("select.pu_city").removeAttr('disabled');
                            $("select.pu_sector").removeAttr('disabled');
                            // console.log('test');
                            // var addressJSON = $('#form-step-1').find('select[name="shipper_id"]').find('option:selected').data('address').filter(function(obj){
                            //     return obj.useraddress_no == $('#form-step-1').find('select[name="shipper_address_id"]').find('option:selected').val() ? true : false;
                            // })[0];
                        }

                        else if(action=='delivery'){
                            $('div.div-del-province').html(result);
                            //$(".del_province").select2({});
                            $(".del_province").trigger('change');
                            console.log('test1');
                        }
                        get_auto_sector_province(action);
                        
                    }});
                }else{
                    if(action=='pickup'){
                        $(".pu_province").html('<option value="">--Select Province--</option>');
                        $(".pu_province").trigger('change');
                        
                    }
                    else if(action=='delivery'){
                        $(".del_province").html('<option value="">--Select Province--</option>');
                        $(".del_province").trigger('change');
                    }
                }
            }else{
                $('#pu_checkbox').prop('checked',false);
                Swal.fire({
                    title: 'Do you want to proceed?',
                    html: "<div style='text-align:left;'><h4 style='font-weight:bold;''>English</h4><p style='text-align:justify;font-size:15px;'>Check only if you want us to pickup your shipment at shipper's address using our vehicle. We will collect additional fee for availing our pick-up service</p><h4 style='font-weight:bold;'>Tagalog</h4><p style='text-align:justify;font-size:15px;'>Lagyan lamang ng tsek kung nais mong kami ang kumuha ng pangarga sa lugar ng shipper gamit ang aming behikulo. Ang pag-avail ng serbisyong ito ay may karagdagang bayad</p></div>",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed it!',
                    customClass: 'swal-wide'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#pu_checkbox').prop('checked',true);
                        $('select[name="pu_province"]').removeAttr('disabled');
                        
                        if(action=='pickup'){
                            if(document.getElementById('pu_checkbox').checked){
                                proceed++;
                            }
                        }
                        else if(action=='delivery'){
                            if(document.getElementById('del_checkbox').checked){
                                proceed++;
                            }
                        }
                        if( proceed > 0 ){
                            //alert("get-sector-province/"+action);
                            jQuery.ajax({
                            url: "{{url('/get-sector-province')}}/"+action, 
                            method: 'get',
                            success: function(result){

                                if(action=='pickup'){
                                $('div.div-pu-province').html(result);
                                    //$(".pu_province").select2({});
                                    $(".pu_province").trigger('change');
                                    $("select.pu_province").removeAttr('disabled');
                                    $("select.pu_city").removeAttr('disabled');
                                    $("select.pu_sector").removeAttr('disabled');
                                    // console.log('test');
                                    // var addressJSON = $('#form-step-1').find('select[name="shipper_id"]').find('option:selected').data('address').filter(function(obj){
                                    //     return obj.useraddress_no == $('#form-step-1').find('select[name="shipper_address_id"]').find('option:selected').val() ? true : false;
                                    // })[0];
                                }

                                else if(action=='delivery'){
                                    $('div.div-del-province').html(result);
                                    //$(".del_province").select2({});
                                    $(".del_province").trigger('change');
                                    console.log('test1');
                                }
                                get_auto_sector_province(action);
                                
                            }});
                        }else{
                            if(action=='pickup'){
                                $(".pu_province").html('<option value="">--Select Province--</option>');
                                $(".pu_province").trigger('change');
                                
                            }
                            else if(action=='delivery'){
                                $(".del_province").html('<option value="">--Select Province--</option>');
                                $(".del_province").trigger('change');
                            }
                        }
                    }else{
                        $('#pu_checkbox').prop('checked',false);
                    }

                })

                swal({
                    title: "Do you want to proceed?",
                    text: "Check if you want us to pick up your shipment at Shippers Address, we will collect additional fees for Pick Up",
                    icon: "info",
                    buttons: true,
                    dangerMode: false,
                })
                .then((willConfirm) => {
                    
                    if (willConfirm) {
                        $('#pu_checkbox').prop('checked',true);
                        $('select[name="pu_province"]').removeAttr('disabled');
                        
                        if(action=='pickup'){
                            if(document.getElementById('pu_checkbox').checked){
                                proceed++;
                            }
                        }
                        else if(action=='delivery'){
                            if(document.getElementById('del_checkbox').checked){
                                proceed++;
                            }
                        }
                        if( proceed > 0 ){
                            //alert("get-sector-province/"+action);
                            jQuery.ajax({
                            url: "{{url('/get-sector-province')}}/"+action, 
                            method: 'get',
                            success: function(result){

                                if(action=='pickup'){
                                $('div.div-pu-province').html(result);
                                    //$(".pu_province").select2({});
                                    $(".pu_province").trigger('change');
                                    $("select.pu_province").removeAttr('disabled');
                                    $("select.pu_city").removeAttr('disabled');
                                    $("select.pu_sector").removeAttr('disabled');
                                    // console.log('test');
                                    // var addressJSON = $('#form-step-1').find('select[name="shipper_id"]').find('option:selected').data('address').filter(function(obj){
                                    //     return obj.useraddress_no == $('#form-step-1').find('select[name="shipper_address_id"]').find('option:selected').val() ? true : false;
                                    // })[0];
                                }

                                else if(action=='delivery'){
                                    $('div.div-del-province').html(result);
                                    //$(".del_province").select2({});
                                    $(".del_province").trigger('change');
                                    console.log('test1');
                                }
                                get_auto_sector_province(action);
                                
                            }});
                        }else{
                            if(action=='pickup'){
                                $(".pu_province").html('<option value="">--Select Province--</option>');
                                $(".pu_province").trigger('change');
                                
                            }
                            else if(action=='delivery'){
                                $(".del_province").html('<option value="">--Select Province--</option>');
                                $(".del_province").trigger('change');
                            }
                        }
                    }else{
                        $('#pu_checkbox').prop('checked',false);
                    }
                });
            }
        }
       
    }

    function get_sector_delivery_province(action){
        proceed=0;
        if(action=='pickup'){

            
            if(document.getElementById('pu_checkbox').checked){
                proceed++;
            }else{
                if(Number($("#discount_coupon_pickup").val())==1){
                    document.getElementById('pu_checkbox').checked=true;
                }
            }
        }
        else if(action=='delivery'){

            
            if(document.getElementById('del_checkbox').checked){
                proceed++;
            }else{
                if(Number($("#discount_coupon_delivery").val())==1){
                    document.getElementById('del_checkbox').checked=true;
                }
            }
        }
        
        if( proceed > 0 ){
            //alert("get-sector-province/"+action);
            jQuery.ajax({
            url: "{{url('/get-sector-province')}}/"+action, 
            method: 'get',
            success: function(result){

                if(action=='pickup'){
                    $('div.div-pu-province').html(result);
                    //$(".pu_province").select2({});
                    $(".pu_province").trigger('change');
                    $("select.pu_province").removeAttr('disabled');
                    $("select.pu_city").removeAttr('disabled');
                    $("select.pu_sector").removeAttr('disabled');
                    // console.log('test');
                    // var addressJSON = $('#form-step-1').find('select[name="shipper_id"]').find('option:selected').data('address').filter(function(obj){
                    //     return obj.useraddress_no == $('#form-step-1').find('select[name="shipper_address_id"]').find('option:selected').val() ? true : false;
                    // })[0];
                }

                else if(action=='delivery'){
                    $('div.div-del-province').html(result);
                    //$(".del_province").select2({});
                    $(".del_province").trigger('change');
                    $("select.del_province").removeAttr('disabled');
                    $("select.del_city").removeAttr('disabled');
                    $("select.del_sector").removeAttr('disabled');
                }
                get_auto_sector_province(action);
                
            }});
        }else{
            if(Number($("#discount_coupon_delivery").val())==1){}
            else{
                if(action=='pickup'){
                    $(".pu_province").html('<option value="">--Select Province--</option>');
                    $(".pu_province").trigger('change');
                    
                }
                else if(action=='delivery'){
                    $(".del_province").html('<option value="">--Select Province--</option>');
                    $(".del_province").trigger('change');
                }
            }
        }
    }

    

    function get_auto_sector_province(action){
        if(action=='pickup'){
            if(document.getElementById('shipper_address_id')){
                if(document.getElementById('shipper_address_id').value=='new'){
                    if( $(".pu_province").val() =='' ){
                        
                        jQuery.ajax({
                        url: "/get-sector-details/"+$("#shipper_brgy").val(), 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".pu_province option[value='"+this.province_id+"']").length > 0 ){
                                  $(".pu_province").val(this.province_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }else{

                    if( $(".pu_province").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#shipper_address_id").val()+'/province', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".pu_province option[value='"+this.province_id+"']").length > 0 ){
                                  $(".pu_province").val(this.province_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".pu_province").val() =='' ){
                        
                    jQuery.ajax({
                    url: "/get-sector-details/"+$("#shipper_brgy").val(), 
                    method: 'get',
                    success: function(result){
                        var result = JSON.parse(result);
                        $.each(result,function(){
                            
                            if( $(".pu_province option[value='"+this.province_id+"']").length > 0 ){
                                $(".pu_province").val(this.province_id).trigger('change');
                            }
                        });
    
                    }});
                }
            }
        }
        else if(action=='delivery'){
            if(document.getElementById('consignee_address_id')){
                if(document.getElementById('consignee_address_id').value=='new'){
                    if( $(".del_province").val() =='' ){
                        
                        jQuery.ajax({
                        url: "/get-sector-details/"+$("#consignee_brgy").val(), 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".del_province option[value='"+this.province_id+"']").length > 0 ){
                                  $(".del_province").val(this.province_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }else{

                    if( $(".del_province").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#consignee_address_id").val()+'/province', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".del_province option[value='"+this.province_id+"']").length > 0 ){
                                  $(".del_province").val(this.province_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".del_province").val() =='' ){
                        
                    jQuery.ajax({
                    url: "/get-sector-details/"+$("#consignee_brgy").val(), 
                    method: 'get',
                    success: function(result){
                        var result = JSON.parse(result);
                        $.each(result,function(){
                            
                            if( $(".del_province option[value='"+this.province_id+"']").length > 0 ){
                                $(".del_province").val(this.province_id).trigger('change');
                            }
                        });
    
                    }});
                }
            }
        }
    }

    function get_city(action){
        proceed=0;
        province='';
        if(action=='pickup'){
            if( $(".pu_province").val() != '' ){
                proceed++;
                province=$(".pu_province").val();
            }
        }
        else if(action=='delivery'){
            if( $(".del_province").val() != ''){
                proceed++;
                province=$(".del_province").val();
            }
        }
        
        if( proceed > 0 ){
            //alert("/get-sector-city/"+action+'/'+province);
            jQuery.ajax({
            url: "{{url('/get-sector-city')}}/"+action+'/'+province, 
            method: 'get',
            success: function(result){

                if(action=='pickup'){
                    $('div.div-pu-city').html(result);
                    //$(".pu_city").select2({});
                    $(".pu_city").trigger('change');
                    $("select.pu_city").removeAttr('disabled');
                    $("select.pu_sector").removeAttr('disabled');
                }

                else if(action=='delivery'){
                    $('div.div-del-city').html(result);
                    //$(".del_city").select2({});
                    $(".del_city").trigger('change');
                    $("select.del_city").removeAttr('disabled');
                    $("select.del_sector").removeAttr('disabled');
                }
                get_auto_sector_city(action);
                
            }});
        }else{
            if(action=='pickup'){
                $(".pu_city").html('<option value="">--Select City--</option>');
                $(".pu_city").trigger('change');
                
            }
            else if(action=='delivery'){
                $(".del_city").html('<option value="">--Select City--</option>');
                $(".del_city").trigger('change');
            }
        }
    }

    function get_auto_sector_city(action){
        if(action=='pickup'){
            if(document.getElementById('shipper_address_id')){
                if(document.getElementById('shipper_address_id').value=='new'){
                    if( $(".pu_city").val() =='' ){
                        
                        jQuery.ajax({
                        url: "/get-sector-details/"+$("#shipper_brgy").val(), 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".pu_city option[value='"+this.cities_id+"']").length > 0 ){
                                  $(".pu_city").val(this.cities_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }else{

                    if( $(".pu_city").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#shipper_address_id").val()+'/city', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                               
                               if( $(".pu_city option[value='"+this.cities_id+"']").length > 0 ){
                                  $(".pu_city").val(this.cities_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".pu_city").val() =='' ){
                        
                    jQuery.ajax({
                    url: "{{asset('/get-sector-details')}}/"+$("#shipper_brgy").val(), 
                    method: 'get',
                    success: function(result){
                        var result = JSON.parse(result);
                        $.each(result,function(){
                            
                            if( $(".pu_city option[value='"+this.cities_id+"']").length > 0 ){
                                $(".pu_city").val(this.cities_id).trigger('change');
                            }
                        });
    
                    }});
                }
            }
        }
        else if(action=='delivery'){
            if(document.getElementById('consignee_address_id')){
                if(document.getElementById('consignee_address_id').value=='new'){
                    if( $(".del_city").val() =='' ){
                        
                        jQuery.ajax({
                        url: "/get-sector-details/"+$("#consignee_brgy").val(), 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".del_city option[value='"+this.cities_id+"']").length > 0 ){
                                  $(".del_city").val(this.cities_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }else{

                    if( $(".del_city").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#consignee_address_id").val()+'/city', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".del_city option[value='"+this.cities_id+"']").length > 0 ){
                                  $(".del_city").val(this.cities_id).trigger('change');
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".del_city").val() =='' ){
                        
                    jQuery.ajax({
                    url: "/get-sector-details/"+$("#consignee_brgy").val(), 
                    method: 'get',
                    success: function(result){
                        var result = JSON.parse(result);
                        $.each(result,function(){
                            
                            if( $(".del_city option[value='"+this.cities_id+"']").length > 0 ){
                                $(".del_city").val(this.cities_id).trigger('change');
                            }
                        });
    
                    }});
                }
            }
        }
    }

    function get_barangay(action){
        proceed=0;
        province='';
        city='';

        if(action=='pickup'){
            if( $(".pu_province").val() != '' && $(".pu_city").val() != '' ){
                proceed++;
                province=$(".pu_province").val();
                city=$(".pu_city").val();
            }
        }
        else if(action=='delivery'){
            if( $(".del_province").val() != '' && $(".del_city").val() != '' ){
                proceed++;
                province=$(".del_province").val();
                city=$(".del_city").val();
            }
        }
        if( proceed > 0 ){
            //alert("/get-sector-brgy/"+action+'/'+province+'/'+city);
            jQuery.ajax({
            url: "{{asset('/get-sector-brgy')}}/"+action+'/'+province+'/'+city, 
            method: 'get',
            success: function(result){

                if(action=='pickup'){
                    $('div.div-pu-brgy').html(result);
                    //$(".pu_sector").select2({});
                    $(".pu_sector").trigger('change');
                    
                }

                else if(action=='delivery'){
                    $('div.div-del-brgy').html(result);
                   // $(".del_sector").select2({});
                    $(".del_sector").trigger('change');
                    
                    $("select.del_sector").removeAttr('disabled');
                }
                get_auto_sector_barangay(action);
                if(action=='pickup'){
                    $(".pu_sector").removeAttr('disabled');
                }
                
            }});
        }else{
            if(action=='pickup'){
                $(".pu_sector").html('<option value="">--Select Barangay--</option>');
                $(".pu_sector").trigger('change');
                $(".pu_sector").removeAttr('disabled');
                
            }
            else if(action=='delivery'){
                $(".del_sector").html('<option value="">--Select Barangay--</option>');
                $(".del_sector").trigger('change');
            }
            
        }   
    }

    function get_auto_sector_barangay(action){
        if(action=='pickup'){
            if(document.getElementById('shipper_address_id')){
                if(document.getElementById('shipper_address_id').value=='new'){
                    if( $(".pu_sector").val() =='' ){
                        
                        if( $(".pu_sector option[value='"+$("#shipper_brgy").val()+"']").length > 0 ){
                            $(".pu_sector").val($("#shipper_brgy").val()).trigger('change');
                            
                            $(".pu_street").val($("#shipper_street").val());
                        }

                    }
                }else{

                    if( $(".pu_sector").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#shipper_address_id").val()+'/brgy', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".pu_sector option[value='"+this.sectorate_no+"']").length > 0 ){
                                  $(".pu_sector").val(this.sectorate_no).trigger('change');
                                  
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".pu_sector").val() =='' ){
                        
                    if( $(".pu_sector option[value='"+$("#shipper_brgy").val()+"']").length > 0 ){
                        $(".pu_sector").val($("#shipper_brgy").val()).trigger('change');
                        $(".pu_street").val($("#shipper_street").val());
                    }
                }
            }
        }
        else if(action=='delivery'){
            if(document.getElementById('consignee_address_id')){
                if(document.getElementById('consignee_address_id').value=='new'){
                    if( $(".del_sector").val() =='' ){
                        
                        if( $(".del_sector option[value='"+$("#consignee_brgy").val()+"']").length > 0 ){
                            $(".del_sector").val($("#consignee_brgy").val()).trigger('change');
                            $(".del_street").val($("#consignee_street").val());
                        }
                    }
                }else{

                    if( $(".del_sector").val() =='' ){
                        
                        jQuery.ajax({
                        url: "{{url('/get-address-details')}}/"+$("#consignee_address_id").val()+'/brgy', 
                        method: 'get',
                        success: function(result){
                            var result = JSON.parse(result);
                            $.each(result,function(){
                                
                               if( $(".del_sector option[value='"+this.sectorate_no+"']").length > 0 ){
                                  $(".del_sector").val(this.sectorate_no).trigger('change');
                                }
                            });
       
                        }});
                    }
                }
            }else{
                if( $(".del_sector").val() =='' ){
                        
                    if( $(".del_sector option[value='"+$("#consignee_brgy").val()+"']").length > 0 ){
                        $(".del_sector").val($("#consignee_brgy").val()).trigger('change');
                        $(".del_street").val($("#consignee_street").val());
                    }
                }
            }
        }
    }

    function pu_sector_func(){
        //pu_date=($(".pu-date-display").val()).split('/');
        //getAllDays(pu_date[2], pu_date[0]);
        getAllDays('<?php echo date('Y'); ?>','<?php echo date('m'); ?>');
    }

    function del_est_sched(count_data=1,dtext=''){

        $("#p_del_est_sched").html('');
       
        if( $(".pu-date-display").val() !='' && document.getElementById('del_checkbox').checked && $(".del_sector").val() != ''  ){

            

            pu_date=($(".pu-date-display").val()).split('/');
            //alert("/get-sector-schedule/"+$(".del_sector").val()+"/"+pu_date[2]+'-'+pu_date[0]+'/delivery');
            jQuery.ajax({
            url:  "{{url('/get-sector-schedule')}}/"+$(".del_sector").val()+"/"+pu_date[2]+'-'+pu_date[0]+'/delivery',
            method: 'get',
            success: function(result){
                d=result.split(",");

                pu_date=new Date($(".pu-date-display").val());
                pu_date.setDate((pu_date.getDate() + 1));
                pu_date=new Date(pu_date).toLocaleDateString();           
                pu_date=pu_date.split("/");
                pu_date=pu_date[2]+'-'+(pu_date[0].padStart(2,'0'))+'-'+(pu_date[1].padStart(2,'0'));
              
                $.each(d, function(index, value) {
                
                    if(index > 0){
                        e=value.split("|");
                       
                        p_date=new Date(pu_date);
                        cdate=new Date(e[0]);
                        
                        //alert(cdate +' | '+p_date );
                        if( Number(e[1]) > 0 && count_data <= 2  && cdate > p_date){ 
                            //alert(cdate +' | '+p_date );
                            if(dtext !=''){
                             dtext +=' to ';
                            }
                            dtext += e[0];
                            count_data++;
                        }                       
                    }
                    
                });
                $("#p_del_est_sched").html('<font color="red">Note: Estimated delivery will be on '+dtext+'</font>');

            }});

        }
    }

    //END DELIVERY & PICK-UP 

</script>