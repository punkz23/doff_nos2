@extends('layouts.theme')

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
<div class="col-sm-12">
    <h4 class="header blue">NEW FAQ</h4>

    <div class="widget-box widget-color-green">

            <div class="widget-header widget-header-small">  </div>

            <div class="widget-body">
                <div class="widget-main no-padding">
                    <div class="wysiwyg-editor" id="editor1"></div>
                </div>

                <div class="widget-toolbox padding-4 clearfix">
                    <div class="btn-group pull-left">
                        <button class="btn btn-sm btn-default btn-white btn-round">
                            <i class="ace-icon fa fa-times bigger-125"></i>
                            Cancel
                        </button>
                    </div>

                    <div class="btn-group pull-right">
                        <button class="btn btn-sm btn-danger btn-white btn-round">
                            <i class="ace-icon fa fa-floppy-o bigger-125"></i>
                            Save
                        </button>

                        <button class="btn btn-sm btn-success btn-white btn-round">
                            <i class="ace-icon fa fa-globe bigger-125"></i>

                            Publish
                            <i class="ace-icon fa fa-arrow-right icon-on-right bigger-125"></i>
                        </button>
                    </div>
                </div>
            </div>
    </div>

</div>
@endsection

@section('plugins')
<script src="{{asset('/theme/js/jquery-ui.custom.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.ui.touch-punch.min.js')}}"></script>
<script src="{{asset('/theme/js/markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-markdown.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.hotkeys.index.min.js')}}"></script>
<script src="{{asset('/theme/js/bootstrap-wysiwyg.min.js')}}"></script>
<script src="{{asset('/theme/js/bootbox.js')}}"></script>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(function($){
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
    })  
</script>
@endsection