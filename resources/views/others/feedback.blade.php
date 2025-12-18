@extends('layouts.gentelella')

@section('css')
<link rel="stylesheet" href="{{asset('/theme/css/chosen.min.css')}}" />
@endsection

@section('bread-crumbs')
<!--h3>Contact Us (Feedback)</h3-->
@endsection

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard_graph">
            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-pencil"></i> FEEDBACK</h4>
                </div>
                
            </div>
            <form class="form-horizontal visible" id="form-feedback">
                @csrf

                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email">Your feedback to us:</label>

                        <div class="col-xs-12 col-sm-9">
                            <div class="clearfix">
                                <textarea name="incident_subject" rows="15" class="form-control">

                                </textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-2 no-padding-right" for="email"></label>

                    <div class="col-xs-9">
                        <div class="clearfix pull-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                </div>     
            </form>
        </div> 
    </div> 
</div> 

@endsection

@section('plugins')

<script src="{{asset('/theme/js/wizard.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('/theme/js/select2.min.js')}}"></script>

<script src="{{asset('/theme/js/chosen.jquery.min.js')}}"></script>

<script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#form-feedback').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            ignore: "",

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
                
                $('#form-feedback .submit').attr('disabled',true);
                
                
                $.ajax({
                    url: "{{route('incident.store_feedback')}}",
                    type: "POST",
                    data: form_data,
                    dataType: 'JSON',
                    processData: false,
                    contentType:false,
                    cache:false,
                    success: function(result){
                        // console.log("{{url('/start-conversation/')}}/"+result.data['session_key']);
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                          $('#form-feedback').trigger('reset');
                          $('#form-feedback .submit').removeAttr('disabled');
                        });
                        
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