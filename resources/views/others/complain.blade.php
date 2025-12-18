@extends('layouts.gentelella')

@section('css')

@endsection

@section('bread-crumbs')

@endsection

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-pencil"></i> COMPLAIN</h4>
                </div>
                
            </div>
            <form class="form-horizontal" id="form-complain">
                @csrf
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                        Category <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="incident_category_id" class="form-control">
                            <option selected value="none" disabled>--Please select category--</option>
                            @foreach($incident_categories as $category)
                                <option value="{{$category->incident_category_id}}">{{$category->incident_category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                        Tracking No(s): <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tags_1" type="text" name="tracking_no" class="tags form-control" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                        Your Complain: <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea name="incident_subject" rows="10" class="form-control">
                        
                        </textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                        Attachment: <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="file" name="attachments[]" class="form-control" multiple>
                    </div>
                </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                    </label>
                    <div class="col-xs-6">
                        <div class="clearfix pull-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>     
                </div>            
            </form>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('plugins')


<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>

<script src="{{asset('/theme/js/bootstrap-tag.min.js')}}"></script>
<!-- jQuery Tags Input -->
<script src="{{asset('/gentelella')}}/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#tags_1').tagsInput({
          width: 'auto'
        });
        

        $('#form-complain').validate({
            rules: {
                incident_category_id: {
                    required: true
                },
                tracking_no: {
                    required: function(element) {
                        return $("#form-complain select[name='incident_category_id']").val()==4 || $("#form-complain select[name='incident_category_id']").val()==11;
                    }
                },
                incident_subject: {
                    required: true,
                }
            },
            submitHandler: function (form) {
                var form_data = new FormData(form);
                form_data.append('_token',"{{csrf_token()}}");
                
                $('#form-complain .submit').attr('disabled',true);
                
                
                $.ajax({
                    url: "{{route('incident.store_complain')}}",
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
                            if(result.type=='error'){
                            var $validator = $('#form-complain').validate();
                            var errors;
                            Object.entries(result.errors).forEach(([key,value])=>{
                                errors = { [key] : value[0] };
                            });
                            $validator.showErrors(errors);
                            }else{
                            $('#form-complain .submit').removeAttr('disabled');
                            $('#form-complain .tagsinput').find('span.tag').remove();
                            $('#form-complain').trigger('reset');

                            }
                        });
                        
                    },
                    error: function(xhr,status){
                        
                        if(xhr.status==422){
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
                
                return false;
            },
            invalidHandler: function (form) {
            }
        });
    })
</script>
@endsection