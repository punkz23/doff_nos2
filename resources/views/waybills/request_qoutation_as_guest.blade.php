<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Request Quotation - {{ config('app.name', 'Laravel') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset(env('APP_IMG'))}}" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="{{asset('/regna/fonts/google_font.css')}}" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('/regna')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="{{asset('/regna')}}/vendor/aos/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('/theme/css/select2.min.css')}}" />

  <!-- Template Main CSS File -->
  <link href="{{asset('/regna')}}/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v10.0" nonce="t0A2wSJd"></script>
  <link href="{{asset('/css/fb.css')}}" rel="stylesheet">
  <!-- =======================================================
  * Template Name: Regna - v2.1.0
  * Template URL: https://bootstrapmade.com/regna-bootstrap-onepage-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
* {
    margin:0;
    padding:0;
    font:12pt Arial;
}

.field {
  display:flex;
  position:realtive;
  margin:5em auto;
  width:500px;
  flex-direction:row;
  -moz-box-shadow:    0px 0px 2px 0px rgba(0,0,0,0.2);
  -webkit-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.2);
  box-shadow:         0px 0px 2px 0px rgba(0,0,0,0.2);
}

.field>input[type=text],
.field>button {
  display:block;
  font:1.2em 'Open sans';
}

.field>input[type=text] {
  flex:1;
  padding:0.6em;
  border:0.2em solid #819090;
  border-left: none;
  border-top: none;
}

.field>button {
  padding:0.6em 0.8em;
  background-color: #217192;
  color: #fff;
  border:none;
}

</style>
<body>
@include('fb')
  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
      @include('logo')
        <!-- Uncomment below if you prefer to use a text logo -->
        <!--<h1><a href="#hero">Regna</a></h1>-->
      </div>

      @include('navbar')
    </div>
  </header><!-- End Header -->

  <main id="main" style=" position: relative;min-height: 70vh;">

    <!-- ======= Breadcrumbs Section ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-info"><i class="fa fa-envelope"></i> REQUEST QUOTATION</h2>
		  
             <!--h2>Request Quotation</h2-->
          <ol>
            <li><a href="/" >Home</a></li>
            <li>Request Quotation</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs Section -->

    <section class="inner-page pt-4">
      <div class="container well">
        <form id="form">
            <div class="form-group row">
                <div class="col-md-4">
                    <label class="control-label ">Firstname:</label>
                    <input type="text" name="fname" class="form-control" /> 
                </div>
                <div class="col-md-4">
                    <label for="Firstname">Middlename:</label>
                    <input type="text" name="mname" class="form-control" /> 
                </div>
                <div class="col-md-4">
                    <label for="Firstname">Lastname:</label>
                    <input type="text" name="lname" class="form-control" /> 
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Firstname">Contact Number <font color="red">*</font>:</label>
                        <input required type="number" name="contact_no" class="form-control"  maxlength="11" minlength="11" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/> 
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address <font color="red">*</font>:</label>
                        <input required type="email" name="email" class="form-control" /> 
                    </div>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-md-5 col-lg-5">
                    <div class="form-group">
                        <label for="email">Origin branch <font color="red">*</font>:</label>
                        <select name="origin_branch" class="form-control" required>
                            @foreach($branches as $branch)
                                <option value="{{$branch->branchoffice_no}}">{{$branch->branchoffice_description}}</option>
                            @endforeach
                        </select> 
                    </div>
                </div>

                <div class="col-md-5 col-lg-5">
                    <div class="form-group">
                        <label for="email">Destination branch <font color="red">*</font>:</label>
                        <select name="destination_branch" class="form-control" required >
                            @foreach($branches as $branch)
                                <option value="{{$branch->branchoffice_no}}">{{$branch->branchoffice_description}}</option>
                            @endforeach
                        </select> 
                    </div>
                </div>
                <div class="col-md-2 col-lg-2">
                    <div class="form-group">
                        <label for="declared_value">Declared Value <font color="red">*</font>:</label>
                        <input type="number" min="0" step="any" name="declared_value" class="form-control" required  /> 
                    </div>
                </div>
            </div>


            <div class="form-group">
                <input type="checkbox" name="delivery" class="chk-address">
                Delivery
            </div>
            <div class="row">
                <div class="col-md-5 col-lg-6">
                    <div class="form-group">
                        <select name="city_delivery" class="form-control select-cities"  data-type="delivery" disabled>
                            <option value="none" selected disabled>--Please select province, city and postal code--</option>
                            @foreach($provinces as $province)
                                <optgroup label="{{$province->province_name}}">
                                    @foreach($province->city as $city)
                                        <option value="{{$city->cities_id}}" data-postal_code="{{$city->postal_code}}">{{$city->cities_name}},{{$province->province_name}}, {{$city->postal_code}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        {{-- <input type="text" name="barangay_delivery" class="form-control" placeholder="Barangay" disabled> --}}
                        <select name="barangay_delivery" class="form-control" disabled>
                            <option value="none" selected disabled>--Please select barangay--</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" name="street_delivery" class="form-control" placeholder="Street/Bldg/Others" disabled>
                    </div>
                </div>
                
            </div>

            
            <div class="form-group">
                <input type="checkbox" name="pickup" class="chk-address">
                Pickup
            </div>
            <div class="row">
                <div class="col-md-5 col-lg-6">
                    <div class="form-group">
                        <select name="city_pickup" class="form-control select-cities" data-type="pickup" disabled>
                            <option value="none" selected disabled>--Please select province, city and postal code--</option>
                            @foreach($provinces as $province)
                                <optgroup label="{{$province->province_name}}">
                                    @foreach($province->city as $city)
                                        <option value="{{$city->cities_id}}" data-postal_code="{{$city->postal_code}}">{{$city->cities_name}},{{$province->province_name}}, {{$city->postal_code}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    
                        <select name="barangay_pickup" class="form-control" disabled>
                            <option value="none" selected disabled>--Please select barangay--</option>
                        </select>
                    
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <input type="text" name="street_pickup" class="form-control" placeholder="Street/Bldg/Others" disabled>
                    </div>
                </div>
                
                
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-lg-12">
                    <!--button type="button" class="btn btn-primary pull-right  add-item"-->
                    <button data-toggle="modal" data-target=".add-item-modal" type="button" class="btn btn-primary pull-right add-item-modal-btn">
                        <i class="ace-icon fa fa-plus"></i> Add Item
                    </button>
                </div>
                <div class="modal fade add-item-modal"  role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                      <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa fa-plus-circle"></i> ADD ITEM</h4>
                            <button type="button" class="close add-item-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          
						   
						    <div class="item form-group">
                                <div class="input-group col-md-12"> 
                                    <input  class="form-control col-lg-12 col-lg-12" id="search-item-name"  placeholder="Search Item..." type="text">
                                    <span class="input-group-btn">
                                        <button type="button"  class="btn btn-primary btn-md search-item"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="item form-group">
                                <div class="col-md-12"> 
                                <select name="search_item_code" class="form-control select2">

                                </select>   
                                </div>   
                            </div>
						
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary add-item"><i class="fa fa-checked"></i> ADD</button>
                        </div>
						
                      </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-lg-12 ">
                    <table id="datatable" width="100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Description</th>
                                <th>Unit</th>
                                <th width="15%">Qty</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <div class=" col-md-12 col-lg-12 ">
                    <button type="submit" class="btn btn-success pull-right submit"><i class="fa fa-send"></i> Send Request</button>
                </div>
            </div>
        </form>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Regna</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
        All the links in the footer should remain intact.
        You can delete the links only if you purchased the pro version.
        Licensing information: https://bootstrapmade.com/license/
        Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Regna
      -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer><!-- End Footer -->
  @include('fb')
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  
  <!-- Vendor JS Files -->
  <script src="{{asset('/regna')}}/vendor/jquery/jquery.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/php-email-form/validate.js"></script>
  <script src="{{asset('/regna')}}/vendor/counterup/counterup.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/superfish/superfish.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/hoverIntent/hoverIntent.js"></script>
  <script src="{{asset('/regna')}}/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/venobox/venobox.min.js"></script>
  <script src="{{asset('/regna')}}/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('/regna')}}/js/main.js"></script>
  <script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>
<script src="{{asset('/js/messenger-plugin.js')}}"></script>
  <script>
      $(document).ready(function(){
        $('.select2').css('width','100%').select2({allowClear:true});
        
        $(".add-item-modal-btn").click(function(){
            $select = $('select[name="search_item_code"]');
            $select.html('<option value="">--Please select item--</option>');
            $select.trigger('change');
            $("#search-item-name").val('');
        });
        $(".search-item").click(function(){
            if($("#search-item-name").val() !=''){
                $.ajax({
                url: "{{url('/search-stocks')}}/"+$("#search-item-name").val(),
                type: "GET",
                success: function(data){
                    
                    $select = $('select[name="search_item_code"]');
                    $select.html('<option value="">--Please select item--</option>');
                    $.each(data,function(){
                        $select.append('<option value="'+this.stock_no+'" >'+this.stock_description+'</option>');
                    });
                    $select.trigger('change');
                }
            });
            }else{  
                alert('Please input item to search.');
            }
            
        });
        $('.chk-address').change(function(){
            var type = $(this).attr('name');
            if($(this).is(':checked')==true){
                $('input[name="street_'+type+'"]').removeAttr('disabled');
                $('select[name="barangay_'+type+'"]').removeAttr('disabled');
                $('select[name="city_'+type+'"]').removeAttr('disabled');
                // $('input[name="street_'+type+'"]').attr('required',true);
                // $('input[name="barangay_'+type+'"]').attr('required',true);
                // $('select[name="city_'+type+'"]').attr('required',true);
            }else{
                
                $('input[name="street_'+type+'"]').attr('disabled',true);
                $('input[name="street_'+type+'"]').closest('.form-group').removeClass('has-error');
                $('input[name="street_'+type+'"]').closest('.form-group').next().remove();
                
                $('select[name="barangay_'+type+'"]').attr('disabled',true);
                $('select[name="barangay_'+type+'"]').closest('.form-group').removeClass('has-error');
                $('select[name="barangay_'+type+'"]').closest('.form-group').next().remove();

                
                $('select[name="city_'+type+'"]').attr('disabled',true);
                $('select[name="city_'+type+'"]').closest('.form-group').removeClass('has-error');
                $('select[name="city_'+type+'"]').closest('.form-group').next().remove();
                
                // $('input[name="street_'+type+'"]').removeAttr('required');
                // $('input[name="barangay_'+type+'"]').removeAttr('required');
                // $('select[name="city_'+type+'"]').removeAttr('required');
            }
        });

        $('.select-cities').on('change',function(){
            
            // $id = $(this).find('option:selected').data('postal_code');
            $id=$(this).val();
            
            var type = $(this).data('type');
            
            
            $.ajax({
                url: "{{url('/get-sector')}}/"+$id,
                type: "GET",
                success: function(data){
                    $select = $('select[name="barangay_'+type+'"]');
                    $select.html('<option value="none" selected disabled>--Please select barangay--</option>');
                    $.each(data.data,function(){
                        $select.append('<option value="'+this.sectorate_no+'" data-sectorate_no="'+this.sectorate_no+'">'+this.barangay+'</option>');
                    });
                    
                }
            });
            // console.log(request);
        });

        function format ( d, counter, show ) {
            
            // `d` is the original data object for the row
            return '<table id="table-'+counter+'" class="table sub-table" cellpadding="5" cellspacing="0" border="0">'+
                '<tr>'+
                    '<td><div><input type="number" name="sub_quantity['+counter+'][]" class="form-control sub_quantity" placeholder="Qty" min="1" required></div></td>'+
                    '<td>'+
                        '<div><select class="form-control unit_weight" name="unit_weight['+counter+'][]"><option selected value="kg">KILOGRAM</option></select></div>'+
                        '<div><input type="number" name="weight['+counter+'][]" class="form-control weight" placeholder="Weight" min="1" required></div>'+
                    '</td>'+
                    //'<td><div><input type="number" name="weight['+counter+'][]" class="form-control weight" placeholder="Weight" min="1" required></div></td>'+
                    '<td>'+
                        '<div><select name="unit_dimension['+counter+'][]" class="form-control unit_dimension"><option selected value="centi">CENTIMETER</option>'+"{!! $ddUnitConversion !!}"+'</select></div>'+
                        '<div><input type="number" name="height['+counter+'][]" class="form-control height" placeholder="Height" min="1" required></div>'+
                        '<div><input type="number" name="width['+counter+'][]" class="form-control width" placeholder="Width" min="1" required></div>'+
                        '<div><input type="number" name="length['+counter+'][]" class="form-control length" placeholder="Lenght" min="1" required></div>'+
                    '</td>'+
                    //'<td><div><input type="number" name="height['+counter+'][]" class="form-control height" placeholder="Height" min="1" required></div></td>'+
                    //'<td><div><input type="number" name="width['+counter+'][]" class="form-control width" placeholder="Width" min="1" required></div></td>'+
                    //'<td><div><input type="number" name="length['+counter+'][]" class="form-control length" placeholder="Lenght" min="1" required></div></td>'+
                    '<td align="right"></td>'+
                '</tr>'+
            '</table>';

           
            
        }

        var table = $('#datatable').DataTable({
            paging: false,
            bFilter: false,
            bInfo : false
        });

        table.on('click', '.remove-item', function(e) {
            $tr = $(this).closest('tr');
            table.row($tr).remove().draw();
            e.preventDefault();
        });

        table.on('click', '.remove-subitem', function(e) {
            var tr = $(this).closest('tr');
            tr.remove();
        })

        table.on('keyup','.sub_quantity',function(e){
            var $mytable = $(this).closest('table');
            var $trChildRow = $mytable.closest('tr');
            var $trDataRow = $trChildRow.prev();
            var total_quantity = 0;
            $mytable.find('tbody > tr').each(function(){
                var sub_quantity = $(this).find('td').eq(0).find('.sub_quantity').val() == "" ? 0 : $(this).find('td').eq(0).find('.sub_quantity').val();
                total_quantity += parseInt(sub_quantity);
            })
            $trDataRow.find('.quantity').val(total_quantity);
        })

        $('.add-item').on('click',function(){
            var rows_count = table.rows('.data-row').count();
            if(rows_count<5)
            var validated = 1;

            if(rows_count>0){
                var lastRow = $('#datatable > tbody > tr.data-row:last > td');
                
                var field1 = lastRow.eq(0).find('.description').valid() ? 1 : 0;
                var field2 = lastRow.eq(0).find('.item-name ').valid() ? 1 : 0;
                var field3 = lastRow.eq(1).find('.unit ').valid() ? 1 : 0;

                if(field1==1 && field2==1 && field3==1){
                    validated=1;
                    lastRow.eq(0).find('.form-group').removeClass('has-error');
                    lastRow.eq(0).find('.form-group').removeClass('has-error')
                    lastRow.eq(1).find('.form-group').removeClass('has-error')
                    var TRParent = $('#datatable > tbody > tr.data-row:last');
                    var sublastRow = $('#table-'+TRParent[0].id+' > tbody > tr:last > td');
                    var subfield1 = sublastRow.eq(0).find('.sub_quantity').valid() ? 1 : 0;
                    var subfield2 = sublastRow.eq(1).find('.unit_weight ').valid() ? 1 : 0;
                    var subfield3 = sublastRow.eq(1).find('.weight ').valid() ? 1 : 0;
                    //var subfield3 = sublastRow.eq(2).find('.weight ').valid() ? 1 : 0;
                    var subfield4 = sublastRow.eq(2).find('.unit_dimension ').valid() ? 1 : 0;
                    var subfield5 = sublastRow.eq(2).find('.height ').valid() ? 1 : 0;
                    var subfield6 = sublastRow.eq(2).find('.width ').valid() ? 1 : 0;
                    var subfield7 = sublastRow.eq(2).find('.length ').valid() ? 1 : 0;
                    // var subfield4 = sublastRow.eq(3).find('.unit_dimension ').valid() ? 1 : 0;
                    // var subfield5 = sublastRow.eq(4).find('.height ').valid() ? 1 : 0;
                    // var subfield6 = sublastRow.eq(5).find('.width ').valid() ? 1 : 0;
                    // var subfield7 = sublastRow.eq(6).find('.length ').valid() ? 1 : 0;
                    if(subfield1==1 && subfield2==1 && subfield3==1 && subfield4==1 && subfield5==1 && subfield6==1 && subfield7==1){
                        validated=1;
                    }else{
                        validated=0;
                    }
                }else{
                    validated=0;
                }
            }
           
            if( $('select[name="search_item_code"]').val() =='' || $('select[name="search_item_code"]').val()==null ){
                alert('Please select item.');
                validated=0;
            }
            if(validated==1){

                item_name=$( 'select[name="search_item_code"] option:selected' ).text();
                item_code=$('select[name="search_item_code"]').val();

                var delete_btn = rows_count>0 ? '<a href="#" class="btn btn-sm btn-danger remove-item"><i class="ace-icon fa fa-trash"></i></a>' : '';
                table.row.add([
                    '<div class="col-12">'+
                        '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'+
                            '<div class="form-group">'+
                                //'<select name="item_code[]" class="form-control description select2"></select>'+
                                '<input type="hidden" value="'+item_code+'" name="item_code[]" class="form-control description">'+
                                '<input readonly type="text" value="'+item_name+'" name="item_name[]" class="form-control item-name">'+
                            '</div>'+
                        '</div>'+
                        //'<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">'+
                        //    '<div class="form-group">'+
                        //        '<input type="text" name="item_name[]" class="form-control item-name">'+
                        //   '</div>'+
                        //'</div>'+
                    '</div>',
                    '<div><select name="unit_code[]" class="form-control unit select2">'+"{!! $ddUnits !!}"+'</select></div>',
                    '<div><input type="number" style="border-color:transparent;" class="form-control quantity" name="quantity[]" disabled></div>',
                    '<div><button type="button" class="btn btn-sm btn-primary add-dimension"><i class="ace-icon fa fa-plus"></i></button>'+delete_btn+'</div>'
                ]).draw(false);
                $('.select2').css('width','100%').select2({allowClear:true});

                var tr = $('#datatable > tbody > tr:last');
                
                tr.addClass('data-row');
                var id = new Date().getUTCMilliseconds();
                tr.attr('id',id);
                var row = table.row(tr);
                row.child( format(row.data(),id,0) ).show();
                tr.next().addClass('child-row');
                $(".add-item-modal-close").click();
            }  
            
        });

        

        table.on('click','.add-dimension',function(e){
            var tr = $(this).closest('tr');
            var row = table.row( tr );
            var new_row = '<tr>'+
                '<td><div><input type="number" name="sub_quantity['+tr[0].id+'][]" class="form-control sub_quantity" placeholder="Qty" min="1" required></div></td>'+
                '<td>'+
                    '<div><select class="form-control unit_weight" name="unit_weight['+tr[0].id+'][]"><option selected value="kg">KILOGRAM</option></select></div>'+
                    '<div><input type="number" name="weight['+tr[0].id+'][]" class="form-control weight" placeholder="Weight" min="1" required></div>'+
                '</td>'+
                //'<td><div><input type="number" name="weight['+tr[0].id+'][]" class="form-control weight" placeholder="Weight" min="1" required></div></td>'+
                '<td>'+
                    '<div><select name="unit_dimension['+tr[0].id+'][]" class="form-control unit_dimension"><option selected value="centi">CENTIMETER</option>'+"{!! $ddUnitConversion !!}"+'</select></div>'+
                    '<div><input type="number" name="height['+tr[0].id+'][]" class="form-control height" placeholder="Height" min="1" required></div>'+
                    '<div><input type="number" name="width['+tr[0].id+'][]" class="form-control width" placeholder="Width" min="1" required></div>'+
                    '<div><input type="number" name="length['+tr[0].id+'][]" class="form-control length" placeholder="Lenght" min="1" required></div>'+
                '</td>'+
                //'<td><div><input type="number" name="height['+tr[0].id+'][]" class="form-control height" placeholder="Height" min="1" required></div></td>'+
                //'<td><div><input type="number" name="width['+tr[0].id+'][]" class="form-control width" placeholder="Width" min="1" required></div></td>'+
                //'<td><div><input type="number" name="length['+tr[0].id+'][]" class="form-control length" placeholder="Lenght" min="1" required></div></td>'+
                '<td align="right"><button type="button" class="btn btn-sm btn-danger remove-subitem"><i class="ace-icon fa fa-trash"></i></button></td>'+
            '</tr>';
            var lastRow = $('#table-'+tr[0].id+' > tbody > tr:last > td');
            var field1 = lastRow.eq(0).find('.sub_quantity').valid() ? 1 : 0;
            var field2 = lastRow.eq(1).find('.unit_weight').valid() ? 1 : 0;
            var field3 = lastRow.eq(1).find('.weight').valid() ? 1 : 0;
            // var field3 = lastRow.eq(2).find('.weight').valid() ? 1 : 0;
            var field4 = lastRow.eq(2).find('.unit_dimension').valid() ? 1 : 0;
            var field5 = lastRow.eq(2).find('.height').valid() ? 1 : 0;
            var field6 = lastRow.eq(2).find('.width').valid() ? 1 : 0;
            var field7 = lastRow.eq(2).find('.length').valid() ? 1 : 0;
            //var field4 = lastRow.eq(3).find('.unit_dimension').valid() ? 1 : 0;
            // var field5 = lastRow.eq(4).find('.height').valid() ? 1 : 0;
            // var field6 = lastRow.eq(5).find('.width').valid() ? 1 : 0;
            // var field7 = lastRow.eq(6).find('.length').valid() ? 1 : 0;
            if(field1==1 && field2==1 && field3==1 && field4==1 && field5==1 && field6==1 && field7==1){
                $('#table-'+tr[0].id+' > tbody').append(new_row);
            }
        });
        

        //$('.add-item').click();

        var validation = $('#form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",
            rules: {
                contact_no: {
                    required: true
                },
                email: {
                    required: true
                },
                origin_branch: {
                    required: true
                },
                destination_branch: {
                    required: true
                },
                declared_value: {
                    required: true,
                    number : true,
                },
                street_delivery : {
                    required: function(element){
                        if($('input[name="delivery"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                barangay_delivery : {
                    required: function(element){
                        if($('input[name="delivery"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                city_delivery : {
                    required: function(element){
                        if($('input[name="delivery"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                street_pickup : {
                    required: function(element){
                        if($('input[name="pickup"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                barangay_pickup : {
                    required: function(element){
                        if($('input[name="pickup"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                city_pickup : {
                    required: function(element){
                        if($('input[name="pickup"]').is(':checked')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                "item_code[]" : {
                    required: true
                },
                "item_name[]" : {
                    required : true
                },
                "unit_code[]": {
                    required: true
                }
            },
                
            messages: {
                contact_no: "Contact # is required",
                email: "Email is required",
                origin_branch: "Origin branch is required",
                destination_branch: "Destination branch is required",
                declared_value : {
                    required: "Declared value is required",
                    number : "Declared value must be number"
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
                
                $('#form .submit').attr('disabled',true);
                $('#form .submit').html('Please wait..');
                var form_data = new FormData(form);
                form_data.append('_token',"{{csrf_token()}}");
                form_data.append('sectorate_no_delivery',$('#form select[name="barangay_delivery"]').find('option:selected').data('sectorate_no'));
                form_data.append('sectorate_no_pickup',$('#form select[name="barangay_pickup"]').find('option:selected').data('sectorate_no'));
                form_data.append('barangay_delivery',$('#form select[name="barangay_delivery"]').find('option:selected').text());
                form_data.append('barangay_pickup',$('#form select[name="barangay_pickup"]').find('option:selected').text());
                
                $.ajax({
                    url: "{{route('requests.quotation')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                        swal(result.message, {
                            icon: result.type,
                            title: result.title
                        }).then(function(){
                            $('#form .submit').removeAttr('disabled');
                            $('#form .submit').html('Send Request');
                            if(result.type=='success'){
                                $('#form').trigger('reset');
                                $('#datatable > tbody').html('');
                                //$('#datatable > tbody').find("tr.data-row:gt(0)").remove();
                                //$('#datatable > tbody').find("tr.child-row:gt(0)").remove();
                                //$('#datatable > tbody > tr:last > td:first-child > table > tbody').find('tr:gt(0)').remove();
                            }
                        });
                    },
                    error : function(xhr,status){
                        if(xhr.status==422){
                            var responseJSON = xhr.responseJSON;
                            var errors = responseJSON.errors;
                            var htmlErrors = '';
                            jQuery.each(errors, function(i, val) {
                                // $("#" + i).append(document.createTextNode(" - " + val));
                                htmlErrors += '<p style="margin-top:-10px"> <font color="red">*</font>'+val[0]+'</p>';
                            });
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = htmlErrors;
                            swal({
                                title: 'Ooops!',
                                content: wrapper,
                                icon: 'error'
                            }).then(function(){
                                window.scrollTo(0, 0);
                                $('#form .submit').removeAttr('disabled');
                                $('#form .submit').html('Send Request');
                            });
                        }
                    }
                });
                return false;
            }

        });
      })
  </script>
</body>

</html>