<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME')}} | Activation</title>
    <link href="{{asset(env('APP_IMG'))}}" rel="icon">
    <!-- Bootstrap -->
    <link href="{{asset('/gentelella')}}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('/gentelella')}}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{asset('/gentelella')}}/css/custom.css" rel="stylesheet">
  </head>

  <body style="background:#F7F7F7;">
    @include('fb')
    <div class="">


        <div class="col-middle" {{ $result== true ? 'hidden' : '' }} >
            <div class="text-center text-center">
                <h1><i class="fa fa-cogs"></i> Page not found.</h1>
            </div>
        </div>
        <div class="col-middle" {{ $result== true && $result->application_url_status==2 ? '' : 'hidden' }} >
            <div class="text-center text-center">
                <h1><i class="fa fa-cogs"></i> Page Expired.</h1>
            </div>
        </div>
        <div class="col-middle" {{ $result== true && $result->application_url_status==1 ? '' : 'hidden' }} >
            <div class="text-center text-center">
                <h1><i class="fa fa-check"></i> Application Submitted.</h1>
            </div>
        </div>
        @php
        $req_count=0;
        if( $result &&  $result->application_url_status==0 ){
        @endphp

        <div id="login" class="form"  >


          <section style="width: 500px;" class="login_content">
            <form autocomplete="off" method="post" onkeyPress="return !(event.keyCode==13)" id="pca_application_form" enctype="multipart/form-data" >
                @csrf
                <input type="hidden" value="{{ $result->pca_account_no }}" name="pca_application_pcno" />
                <input type="hidden" value="{{ $result->unique_url }}" name="pca_application_url" />
                <input type="hidden" value="0" name="pca_application_action" />
                <input type="hidden" value="{{ $result->personal_corporate }}" name="pca_application_atype" />
              <h1>Application Form</h1>
              <p>Premium Account</p>
              <i class="fa fa-lightbulb-o" ></i> CyberSafe: Ensure that your browser shows our verified URL. <a href="https://dailyoverland.com" ><u>https://dailyoverland.com</u></a> .
                <hr>
                <div class="alert alert-info" style="text-align:left;">
                    <strong><i class="fa fa-user"></i> PERSONAL INFORMATION</strong>
                </div>
                <div class="form-group row" >
                    <label style="text-align:left;" class="col-md-12 col-sm-12 col-xs-12 " for="fname"><h5><span class="label label-default">{{ strtoupper($result->personal_corporate) }} </span></h5></label>
                    <label style="text-align:left;" class="col-md-12 col-sm-12 col-xs-12 " for="fname">Account Name: {{ $result->full_name }}</label>
                </div>
                <div class="form-group row" >
                    <input type="hidden" value="{{ $result->province_id }}" name="add_account_default_province" />
                    <input type="hidden" value="{{ $result->city_id }}" name="add_account_default_city" />
                    <input type="hidden" value="{{ $result->sectorate_no }}" name="add_account_default_brgy" />

                    <label style="text-align:left;" class="col-md-12 col-sm-12 col-xs-12 " for="fname">Address: <small style="color:red;"><i>*</i></small></label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="add_account_province" class="default-select2 form-control" style="width: 100%;" class="form-control col-md-7 col-xs-12"  >
                        <option value=''>-Select Province-</option>
                        @php
                        foreach($result_prov as $prov_data){
                            echo '<option value="'.$prov_data->province_id.'">'.$prov_data->province_name.'</option>';
                        }
                        @endphp

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" id="div_cities">
                        <select name="add_account_city" class="default-select2 form-control" style="width: 100%;"  class="form-control col-md-7 col-xs-12" >
                        <option value=''>-Select City-</option>

                        </select>
                    </div>
                </div>
                <div class="form-group row " >
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="add_account_brgy" class="default-select2 form-control" style="width: 100%;"  class="form-control col-md-7 col-xs-12">
                        <option value=''>-Select Barangay-</option>

                        </select>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="{{ $result->address_street }}" name="add_account_street" type="text" class="form-control" placeholder="Street" />
                    </div>

                </div>
                <div class="form-group row" >
                    <label style="text-align:left;" class="col-md-12 col-sm-12 col-xs-12" for="fname">Contact Person: <small style="color:red;" {{ $result->personal_corporate == 'corporate' ? '' : 'hidden' }} ><i>*</i></small></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <input value="{{ $result->contact_person }}" name="add_account_cperson"  type="text" class="form-control" placeholder="Name" />
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <input value="{{ $result->contact_no }}" name="add_account_cno"  type="text" class="form-control" placeholder="Contact Number" />
                    </div>

                </div>
                <div style="text-align:left;" class="form-group row" >

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input {{ $result->vat==1 ? 'checked' : '' }} name="add_account_vat" type="checkbox"/> VAT&emsp;

                        <div hidden ><input {{ $result->bir_2306==1 ? 'checked' : '' }}  name="add_account_bir_2306" type="checkbox"/> BIR 2306&emsp; </div>

                        <input {{ $result->bir_2307==1 ? 'checked' : '' }}  name="add_account_bir_2307" type="checkbox"/> BIR 2307
                    </div>

                </div>
                <div class="form-group row" >
                    <label style="text-align:left;" class="col-md-12 col-sm-12 col-xs-12" for="fname">TIN No.: <small style="color:red;"><i>*</i></small></label>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input value="{{ $result->tin_no }}" name="add_account_tin" type="text" class="form-control" placeholder="TIN No." />
                    </div>
                </div>
                <div style="text-align:left;" class="alert alert-info">
                    <strong><i class="fa fa-upload"></i> REQUIREMENTS </strong><i>(image only)</i>
                </div>
                <div class="form-group row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        @php
                            $req_list="";

                            if(isset($result_req)){
                                foreach($result_req as $req_data){

                                    $file_data='';
                                    if(isset($req_data->req_files) && $req_data->req_files !='' ){
                                        $req_files=explode("^",$req_data->req_files);
                                        foreach($req_files as $rdata){
                                            $req=explode("~",$rdata);

                                            $filename = '<a href="'.Storage::disk('local')->url('PCA/'.$req[1]).'" target="_blank">'.str_replace($result->pca_account_no."/","",$req[1]).'</a>';

                                            $file_data .='<tr id="tr_add_account_req_file_remove_'.$req_count.'" >
                                            <td width="5%"><button type="button" data-req_count="'.$req_count.'" class="btn btn-danger btn-xs btn_add_account_req_file_remove" ><i class="fa fa-trash"></i> </button></td>
                                            <td>
                                            <input value="'.$req[0].'" name="add_account_req_file_id_'.$req_data->pca_requirements_id.'[]" type="hidden" />
                                            <div hidden><input  value="'.Storage::disk('local')->url('PCA/'.$req[1]).'" class="img-responsive avatar-view" onchange="readURL(this,'.$req_count.','.$req_data->pca_requirements_id.');"  accept="image/*" name="add_account_req_file_'.$req_data->pca_requirements_id.'[]" type="file" class="form-control" /></div>
                                            '.$filename.'
                                            </td>
                                            <td><img src="'.Storage::disk('local')->url('PCA/'.$req[1]).'" class="img-responsive avatar-view"  width="150" height="150" id="photo_'.$req_data->pca_requirements_id.'_'.$req_count.'" /></td>
                                            </tr>';

                                        }
                                        $req_count++;
                                    }

                                    $upload_file_required=0;
                                    $upload_file_required_txt='';
                                    if($req_data->upload_file==1){
                                        $upload_file_required=1;
                                        $upload_file_required_txt='<small><font color="red"><i> (*required uploading file)</i></font></small></i>';
                                    }
                                    $req_list .='
                                    <tr>
                                    <td width="5%" align="center" >
                                        <input onclick="return false;" checked value="'.$req_data->pca_requirements_id.'" type="checkbox" name="add_account_req[]" />
                                        <input value="'.$req_data->pca_requirements_name.'" type="hidden" name="add_account_req_name_'.$req_data->pca_requirements_id.'" />
                                        <input value="'.$upload_file_required.'" type="hidden" name="add_account_req_file'.$req_data->pca_requirements_id.'" />
                                    </td>
                                    <td>'.$req_data->pca_requirements_name.$upload_file_required_txt.'
                                    <button  type="button" data-id="'.$req_data->pca_requirements_id.'" class="btn btn-info btn-xs pull-right btn_add_account_req_file" ><i class="fa fa-plus"> </i> UPLOAD FILE</button>
                                    <table width="100%"><tbody id="tbl_add_account_req_file_'.$req_data->pca_requirements_id.'" >
                                    '.$file_data.'
                                    </tbody></table>
                                    </td>
                                    </tr>';


                                }
                            }
                        @endphp
                        <table class="table table-striped">
                            <tbody id="tbl_add_account_req">@php echo $req_list; @endphp </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group row" >
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button type="submit" id="pca_application_save_as_draft" class="btn btn-primary "><i class="fa fa-file"></i> Save as Draft</button>
                        <button type="submit" id="pca_application_submit" class="btn btn-success "><i class="fa fa-check"></i> Submit Application</button>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="separator">


                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><img src="{{asset(env('APP_IMG'))}}" width="25px" height="25px" alt=""> {{env('APP_NAME')}}</h1>

                  <p>©2015 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
        @php }  @endphp
    </div>
    <script src="{{asset('/js/messenger-plugin.js')}}"></script>
    <script src="{{asset('/js/jquery.min.js')}}"></script>
    <script src="{{asset('/js')}}/sweetalert2.js"></script>

  </body>
</html>

<script>
req_count={{$req_count}};
$('#tbl_add_account_req').on('click', '.btn_add_account_req_file', function(){

    id=$(this).data('id');
    $("#tbl_add_account_req_file_"+id).append('<tr id="tr_add_account_req_file_remove_'+req_count+'" >'+
        '<td width="5%"><button type="button" data-req_count="'+req_count+'" class="btn btn-danger btn-xs btn_add_account_req_file_remove" ><i class="fa fa-trash"></i> </button></td>'+
        '<td>'+
        '<input  name="add_account_req_file_id_'+id+'[]" type="hidden" />'+
        '<input required onchange="readURL(this,'+req_count+','+id+');"  accept="image/*" name="add_account_req_file_'+id+'[]" type="file" class="form-control" /></td>'+
        '<td><img class="img-responsive avatar-view"  width="150" height="150" id="photo_'+id+'_'+req_count+'" /></td>'+
        '</tr>');
    req_count++;
});
$('#tbl_add_account_req').on('click', '.btn_add_account_req_file_remove', function(){
    r_count=$(this).data('req_count');
    $("#tr_add_account_req_file_remove_"+r_count).remove();
});

function readURL(input,rcount,id) {
    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {
            $('#photo_'+id+'_'+rcount)
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$('select[name="add_account_province"]').change(function(){
    get_pc_cities();
});
function get_pc_cities(){
    $('select[name="add_account_city"]').html('<option value="">-Select City-</option>');
    if( $('select[name="add_account_province"]').val() !='' ){

        $.ajax({
            url: "/get-pc-cities/"+btoa($('select[name="add_account_province"]').val()),
            type: "GET",
            dataType: "json",
            success: function(result){

                $('select[name="add_account_city"]').html('<option value="">-Select City-</option>');
                $.each(result,function(){
                    $('select[name="add_account_city"]').append('<option value="'+this.cities_id+'">'+this.cities_name+'</option>');
                });
                $('select[name="add_account_city"]').val($('input[name="add_account_default_city"]').val()).trigger('change');
                $('input[name="add_account_default_city"]').val('');

            }, error: function(){
                swal({
                    icon: "error"
                });
            }
        });

    }else{
        $('select[name="add_account_city"]').val('').trigger('change');
    }
}
$('select[name="add_account_city"]').change(function(){
    get_pc_brgy();
});
function get_pc_brgy(){
    $('select[name="add_account_brgy"]').html('<option value="">-Select Barangay-</option>');
    if( $('select[name="add_account_city"]').val() !='' ){
        $.ajax({
            url: "/get-pc-brgy/"+btoa($('select[name="add_account_city"]').val()),
            type: "GET",
            dataType: "json",
            success: function(result){

                $('select[name="add_account_brgy"]').html('<option value="">-Select Barangay-</option>');
                $.each(result,function(){
                    $('select[name="add_account_brgy"]').append('<option value="'+this.sectorate_no+'">'+this.barangay+'</option>');
                });
                $('select[name="add_account_brgy"]').val($('input[name="add_account_default_brgy"]').val()).trigger('change');
                $('input[name="add_account_default_brgy"]').val('');

            }, error: function(){
                swal({
                    icon: "error"
                });
            }
        });
    }else{
        $('select[name="add_account_city"]').val('').trigger('change');
    }
}
@php
if($result && $result->application_url_status==0  && $result->province_id !=''){
@endphp
$('select[name="add_account_province"]').val('{{ $result->province_id }}').trigger('change');
@php
}
@endphp

$("#pca_application_form").submit(function(){

    event.preventDefault();
    cannot_proceed_req=0;
    cannot_proceed_info=0;

    if(Number($('input[name="pca_application_action"]').val()==1)){

        if(
            $('select[name="add_account_province"]').val() ==''
            ||
            $('select[name="add_account_city"]').val() ==''
            ||
            $('select[name="add_account_brgy"]').val() ==''
            ||
            (
                $('input[name="pca_application_atype"]').val()=='corporate'
                &&
                ($('input[name="add_account_cperson"]').val()=='' || $('input[name="add_account_cno"]').val()=='')
            )
            ||
            $('input[name="add_account_tin"]').val()==''

        ){
            cannot_proceed_info++;
        }
        add_account_req=document.getElementsByName('add_account_req[]');
        for(i=0;i< add_account_req.length;i++){

            add_account_req_file=$('input[name="add_account_req_file'+add_account_req[i].value+'"]').val();
            req_file=document.getElementsByName('add_account_req_file_'+add_account_req[i].value+'[]');
            if(Number(add_account_req_file)==1 && req_file.length==0 ){
                cannot_proceed_req++;
            }
        }
    }
    if(cannot_proceed_info > 0){
        alert('Please input required (*) fields.');
    }
    else if(cannot_proceed_req > 0){
        alert('Please upload at least 1 file on requirements with required uploading file label.');
    }else{
        if(Number($('input[name="pca_application_action"]').val()==1)){
            c=confirm('Are you sure you want to SUBMIT this?');
        }else{
            c= true;
        }
        if(c){
            $.ajax({
                url: "/pca-save-application",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                processData:false,
                success: function(result){

                    Swal.fire({
                        icon: result.type,
                        title: result.title,
                        text: result.message
                    }).then((res) => {
                        location.reload();

                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong'
                    });

                }
            });
        }
    }

});
$("#pca_application_save_as_draft").click(function(){
    $('input[name="pca_application_action"]').val(0);
});
$("#pca_application_submit").click(function(){
    $('input[name="pca_application_action"]').val(1);
});
</script>
