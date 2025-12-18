@extends('layouts.gentelella')

@section('bread-crumbs')
<!--h3>List of Branches</h3-->

@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="dashboard_graph">
        
            <div class="row x_title">
                <div class="col-md-12">
                <h4><i class="ace-icon fa fa-list"></i> LIST OF BRANCHES</h4>
                </div>
                
            </div>   
        
            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>
            
            @foreach($branches as $row)
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{$row->google_maps_api}}" allowfullscreen></iframe>
                    <h5>{{$row->name}} <small>{{$row->branch_contact->count()>0 ? '(' : ''}} @foreach($row->branch_contact as $k=>$c) {{$k>0 ? '/' : ''}}{{$c->contact_no}} @endforeach {{$row->branch_contact->count()>0 ? ')' : ''}}</small></h5>
                </div>

            @endforeach
        </div>
    </div>
</div>

@endsection

