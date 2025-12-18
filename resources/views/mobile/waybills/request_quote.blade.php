@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('waybills.index')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">REQUEST FOR QUOTATION</div>
        <div class="right">
          <a class="link popup-open" id="review">
            <i class="icon f7-icons">doc_text_search</i>
            Send Request
          </a>
        </div>
      </div>
  </div>
  <div class="page-content">
    
  </div>
</div>
@endsection

@section('scripts')
    
@endsection