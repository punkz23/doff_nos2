@extends('layouts.theme1')



@section('content')
<div class="page-header">
    <div class="row">
        <h1>
        <a href="javascript:history.back()"><i class="ace-icon fa fa-backward"></i></a>
            &nbsp;&nbsp;
            Frequently Asked Question (Madalas na katanungan)
        </h1>
    </div>
    
    
</div><!-- /.page-header -->

<div class="row">

<div class="tabbable tabs-right">
	<ul class="nav nav-tabs" id="myDialect">
        @foreach($data as $k=>$dialect)
            <li class="{{$k==0 ? 'active' : ''}}">
                <a data-toggle="tab" href="#{{$dialect->name}}">
                    <i class="pink ace-icon fa fa-tachometer bigger-110"></i>
                    {{$dialect->name}}
                </a>
            </li>
        @endforeach										
	</ul>

	<div class="tab-content">
        @foreach($data as $k=>$dialect)
            <div id="{{$dialect->name}}" class="tab-pane in {{$k==0 ? 'active' : ''}}">
                    {!! $dialect->intro !!}
                    <div class="col-xs-12">
                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-18 tab-size-bigger" id="Tab{{$dialect->name}}">
                                @foreach($dialect->category as $c=>$category)
                                    <li class="{{$c==0 ? 'active' : ''}}">
                                        <a data-toggle="tab" href="#faq-{{$category->id}}">
                                            <i class="{{$category->color}} ace-icon fa {{$category->icon}} bigger-120"></i>
                                            {{$category->name}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="tab-content no-border padding-24">

                            @foreach($dialect->category as $c=>$category)
                                    <div id="faq-{{$category->id}}" class="tab-pane fade {{$c==0 ? 'in active' : ''}}">
                                        <h4 class="{{$category->color}}">
                                            <i class="ace-icon fa {{$category->icon}} bigger-110"></i>
                                            {{$category->name}} {{$k==0 ? 'Questions' : 'Katanungan'}}
                                        </h4>

                                        <div class="space-8"></div>

                                        <div id="faq-list-{{$c}}" class="panel-group accordion-style1 accordion-style2">
                                            @foreach($category->question as $q=>$question)
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <a href="#faq-{{$k}}-{{$c}}-{{$q}}" data-parent="#faq-list-{{$c}}" data-toggle="collapse" class="accordion-toggle collapsed">
                                                            <i class="ace-icon fa fa-chevron-left pull-right" data-icon-hide="ace-icon fa fa-chevron-down" data-icon-show="ace-icon fa fa-chevron-left"></i>

                                                            <i class="ace-icon fa fa-angle-double-right bigger-130"></i>
                                                            &nbsp; {{$question->question}}?
                                                        </a>
                                                    </div>

                                                    <div class="panel-collapse collapse" id="faq-{{$k}}-{{$c}}-{{$q}}">
                                                        <div class="panel-body"> 
                                                            {!! $question->guide['content'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                
            </div>
        @endforeach		
	</div>
</div>
    
@endsection

@section('plugins')


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


