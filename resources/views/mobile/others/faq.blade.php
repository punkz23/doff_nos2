@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
    <div class="navbar-bg"></div>
    <div class="navbar-inner sliding">
      <div class="left">
          <a href="{{url('/')}}" class="link external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
      <div class="title">Frequently Asked Question</div>
    </div>
  </div>
  <div class="toolbar tabbar toolbar-bottom">
    <div class="toolbar-inner">
        @foreach($data as $k=>$dialect)
            <a href="#{{$dialect->name}}" class="tab-link {{$k==0 ? 'tab-link-active' : ''}}">{{$dialect->name}}</a>
        @endforeach
    </div>
  </div>
  <div class="tabs">
    @foreach($data as $k=>$dialect)
        <div id="{{$dialect->name}}" class="page-content tab {{$k==0 ? 'tab-active' : ''}}">
        <div class="block">
            {!! $dialect->intro !!}
        </div>
        <div class="list links-list">
            <ul>
            @foreach($dialect->category as $c=>$category)
                @foreach($category->question as $q=>$question)
                    <li><a href="{{route('guides.show',['id'=>$question->guide['id']])}}" class="external">{{$question->question}}?</a></li>
                @endforeach
            @endforeach
            </ul>
        </div>
        </div>
    @endforeach
  </div>
</div>
@endsection

