@extends('layouts.theme')

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>

        <li>
            <a href="{{route('waybills.index')}}">Other Pages</a>
        </li>
        <li class="active">FAQ</li>
    </ul>
</div>
@endsection

@section('content')
<div class="page-header">
    <h1>
        FAQ
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            frequently asked questions using tabs and accordions
        </small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">

                @foreach($categories as $key=>$row)
                    <li class="{{$key==0 ? 'active' : ''}}">
                        <a data-toggle="tab" href="#faq-{{$row->name}}">
                            <i class="{{$row->color}} ace-icon fa {{$row->icon}} bigger-120"></i>
                            {{$row->name}}
                        </a>
                    </li>
                @endforeach

                <li class="active">
                    <a data-toggle="tab" href="#faq-tab-1">
                        <i class="blue ace-icon fa fa-question-circle bigger-120"></i>
                        General
                    </a>
                </li>

                <li>
                    <a data-toggle="tab" href="#faq-tab-2">
                        <i class="green ace-icon fa fa-user bigger-120"></i>
                        Account
                    </a>
                </li>
                            
            </ul>

            <div class="tab-content no-border padding-24">
                <div id="faq-tab-1" class="tab-pane fade in active">
                    <h4 class="blue">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        General Questions
                    </h4>

                    <div class="space-8"></div>

                    <div id="faq-list-1" class="panel-group accordion-style1 accordion-style2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#faq-1-1" data-parent="#faq-list-1" data-toggle="collapse" class="accordion-toggle collapsed">
                                    <i class="ace-icon fa fa-chevron-left pull-right" data-icon-hide="ace-icon fa fa-chevron-down" data-icon-show="ace-icon fa fa-chevron-left"></i>

                                    <i class="ace-icon fa fa-user bigger-130"></i>
                                        &nbsp; High life accusamus terry richardson ad squid?
                                </a>
                            </div>

                            <div class="panel-collapse collapse" id="faq-1-1">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                                </div>
                            </div>
                        </div>
                            
                    </div>
                </div>

                <div id="faq-tab-2" class="tab-pane fade">
                    <h4 class="blue">
                        <i class="green ace-icon fa fa-user bigger-110"></i>
                        Account Questions
                    </h4>

                    <div class="space-8"></div>

                    <div id="faq-list-2" class="panel-group accordion-style1 accordion-style2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="#faq-2-1" data-parent="#faq-list-2" data-toggle="collapse" class="accordion-toggle collapsed">
                                    <i class="ace-icon fa fa-chevron-right smaller-80" data-icon-hide="ace-icon fa fa-chevron-down align-top" data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;
                                    Enim eiusmod high life accusamus terry richardson?
                                </a>
                            </div>

                            <div class="panel-collapse collapse" id="faq-2-1">
                                <div class="panel-body">
                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
                                </div>
                            </div>
                        </div>

                                                
                    </div>
                </div>

                                        
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('plugins')
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="{{asset('/theme/js/bootstrap.min.js')}}"></script>

<!-- page specific plugin scripts -->
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

@endsection