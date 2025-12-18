@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('faqs.outside')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">{{$data->question->question}}</div>
        
      </div>
  </div>
  <div class="page-content">
    <div class="block">
        {!! $data->content !!}
    </div>
    

  </div>
</div>
@endsection
