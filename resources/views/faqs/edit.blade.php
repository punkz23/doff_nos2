@extends('layouts.theme')

@section('css')
<link rel="stylesheet" href="{{asset('/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>
        <li>
            <a href="#">Maintenance</a>
        </li>
        <li>
            <a href="{{route('others.faq')}}">FAQ</a>
        </li>
        <li class="active">Create</li>
    </ul>
</div>
@endsection

@section('content')
<!-- <textarea class="wysiwyg-editor" id="editor1"></textarea> -->
<h4 class="header blue">NEW FAQ</h4>
<form id="form">
@csrf
<input type="hidden" id="id" name="id" value="{{$question->id}}">
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Category</label>

    <div class="col-sm-9">
        <select name="category_id" class="form-control">
            @foreach($categories as $row)
                <option  {{$question->category_id==$row->id ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="space-10"></div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Platform</label>

    <div class="col-sm-9">
        <select name="platform_id" class="form-control">
            @foreach($platforms as $row)
                <option {{$question->platform_id==$row->id ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="space-10"></div>
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Dialect</label>

    <div class="col-sm-9">
        <select name="dialect_id" class="form-control">
            @foreach($dialects as $row)
                <option {{$question->dialect_id==$row->id ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Question</label>

    <div class="col-sm-9">
        <input type="text" name="question" class="form-control" value="{{$question->question}}" required>
    </div>
</div>

<div class="col-sm-12">
    

    <div class="widget-box widget-color-green">

            <div class="widget-header widget-header-small">  </div>

            <div class="widget-body">
                <div class="widget-main no-padding">

                    <!-- <div class="wysiwyg-editor" id="editor1"></div> -->
                    <textarea class="textarea" name="body" placeholder="Place some text here"
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                              {!! $question->guide->content !!}
                          </textarea>
                </div>

                <div class="widget-toolbox padding-4 clearfix">
                    <div class="btn-group pull-left">
                        <button class="btn btn-sm btn-default btn-white btn-round">
                            <i class="ace-icon fa fa-times bigger-125"></i>
                            Cancel
                        </button>
                    </div>

                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-sm btn-danger btn-white btn-round">
                            <i class="ace-icon fa fa-floppy-o bigger-125"></i>
                            Save
                        </button>

                        <!-- <button class="btn btn-sm btn-success btn-white btn-round">
                            <i class="ace-icon fa fa-globe bigger-125"></i>

                            Publish
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-125"></i>
                        </button> -->
                    </div>
                </div>
            </div>
    </div>

</div>
</form>
@endsection

@section('plugins')
<script src="{{asset('/theme/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-additional-methods.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('/theme/js/markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.hotkeys.index.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-wysiwyg.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>
<script src="{{asset('/theme/js/jquery.gritter.min.js')}}"></script>
<script src="{{asset('/plugins/summernote/summernote-bs4.min.js')}}"></script>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($){
        $('.textarea').summernote({
            height:500
        })

        $('textarea[data-provide="markdown"]').each(function(){
            var $this = $(this);

            if ($this.data('markdown')) {
              $this.data('markdown').showEditor();
            }
            else $this.markdown()
            
            $this.parent().find('.btn').addClass('btn-white');
        })
        
        
        
        function showErrorAlert (reason, detail) {
            var msg='';
            if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
            else {
                //console.log("error uploading file", reason, detail);
            }
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
             '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
        }

        $('#editor1').ace_wysiwyg({
            toolbar:
            [
                'font',
                null,
                'fontSize',
                null,
                {name:'bold', className:'btn-info'},
                {name:'italic', className:'btn-info'},
                {name:'strikethrough', className:'btn-info'},
                {name:'underline', className:'btn-info'},
                null,
                {name:'insertunorderedlist', className:'btn-success'},
                {name:'insertorderedlist', className:'btn-success'},
                {name:'outdent', className:'btn-purple'},
                {name:'indent', className:'btn-purple'},
                null,
                {name:'justifyleft', className:'btn-primary'},
                {name:'justifycenter', className:'btn-primary'},
                {name:'justifyright', className:'btn-primary'},
                {name:'justifyfull', className:'btn-inverse'},
                null,
                {name:'createLink', className:'btn-pink'},
                {name:'unlink', className:'btn-pink'},
                null,
                {name:'insertImage', className:'btn-success'},
                null,
                'foreColor',
                null,
                {name:'undo', className:'btn-grey'},
                {name:'redo', className:'btn-grey'}
            ],
            'wysiwyg': {
                fileUploadError: showErrorAlert
            }
        }).prev().addClass('wysiwyg-style2');

        $('#editor2').css({'height':'200px'}).ace_wysiwyg({
            toolbar_place: function(toolbar) {
                return $(this).closest('.widget-box')
                       .find('.widget-header').prepend(toolbar)
                       .find('.wysiwyg-toolbar').addClass('inline');
            },
            toolbar:
            [
                'bold',
                {name:'italic' , title:'Change Title!', icon: 'ace-icon fa fa-leaf'},
                'strikethrough',
                null,
                'insertunorderedlist',
                'insertorderedlist',
                null,
                'justifyleft',
                'justifycenter',
                'justifyright'
            ],
            speech_button: false
        });
        
        


        $('[data-toggle="buttons"] .btn').on('click', function(e){
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            var toolbar = $('#editor1').prev().get(0);
            if(which >= 1 && which <= 4) {
                toolbar.className = toolbar.className.replace(/wysiwyg\-style(1|2)/g , '');
                if(which == 1) $(toolbar).addClass('wysiwyg-style1');
                else if(which == 2) $(toolbar).addClass('wysiwyg-style2');
                if(which == 4) {
                    $(toolbar).find('.btn-group > .btn').addClass('btn-white btn-round');
                } else $(toolbar).find('.btn-group > .btn-white').removeClass('btn-white btn-round');
            }
        });

        if ( typeof jQuery.ui !== 'undefined' && ace.vars['webkit'] ) {
        
            var lastResizableImg = null;
            function destroyResizable() {
                if(lastResizableImg == null) return;
                lastResizableImg.resizable( "destroy" );
                lastResizableImg.removeData('resizable');
                lastResizableImg = null;
            }

            var enableImageResize = function() {
                $('.wysiwyg-editor')
                .on('mousedown', function(e) {
                    var target = $(e.target);
                    if( e.target instanceof HTMLImageElement ) {
                        if( !target.data('resizable') ) {
                            target.resizable({
                                aspectRatio: e.target.width / e.target.height,
                            });
                            target.data('resizable', true);
                            
                            if( lastResizableImg != null ) {
                                //disable previous resizable image
                                lastResizableImg.resizable( "destroy" );
                                lastResizableImg.removeData('resizable');
                            }
                            lastResizableImg = target;
                        }
                    }
                })
                .on('click', function(e) {
                    if( lastResizableImg != null && !(e.target instanceof HTMLImageElement) ) {
                        destroyResizable();
                    }
                })
                .on('keydown', function() {
                    destroyResizable();
                });
            }

            enableImageResize();

            /**
            //or we can load the jQuery UI dynamically only if needed
            if (typeof jQuery.ui !== 'undefined') enableImageResize();
            else {//load jQuery UI if not loaded
                //in Ace demo ./components will be replaced by correct components path
                $.getScript("assets/js/jquery-ui.custom.min.js", function(data, textStatus, jqxhr) {
                    enableImageResize()
                });
            }
            */
        }

        $('#form').validate({
                    errorElement: 'div',
                    errorClass: 'help-block',
                    focusInvalid: false,
                    ignore: "",
                    rules: {
                        category_id: {
                            required: true
                        },
                        platform_id: {
                            required: true
                        },
                        dialect_id: {
                            required: true
                        },
                        question: {
                            required: true
                        }
                    },
            
                    messages: {
                        category_id: {
                            required: "Category is required"
                            
                        },
                        platform_id: {
                            required: "Platform is required",
                            
                        },
                        dialect: {
                            required: "Dialect is required",
                            
                        },
                        platform_id: {
                            required: "Question is required",
                            
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
                        var form_data = new FormData(form);
                        // form_data.append('body',$('#editor').val());
                        $.ajax({
                            url: "{{route('guides.store')}}",
                            type: "POST",
                            data: form_data,
                            dataType: 'JSON',
                            processData: false,
                            contentType:false,
                            cache:false,
                            success: function(result){

                                if(result.type=="success"){
                                    $('#form').trigger('reset');
                                    $('#editor1').html('');
                                }

                                $.gritter.add({
                                    
                                    title: result['title'],
                                    
                                    text: result['message'],
                                    class_name: 'gritter-'+result['type']
                                });
            
                    
                                


                            }
                        })

                        return false;
                    },
                    invalidHandler: function (form) {
                    }
                });
    })  
</script>
@endsection