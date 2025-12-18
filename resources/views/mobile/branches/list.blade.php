@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{route('home')}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">BRANCHES</div>
        
      </div>
  </div>
  <div class="page-content">
    
    @foreach($branches as $row)
        <div class="card demo-facebook-card">
            <div class="card-header">
            <div class="block-title-medium">{{$row->name}} Office</div>
            <p>Contact # : 
            <b>
                @foreach($row->branch_contact as $key=>$contact)
                {{$key==0 ? '' : ','}} {{$contact->contact_no}}
                @endforeach
            </b><br>
                Address : <b>{{$row->address}}</b><br>
                Schedule(s) : 
                <b>
                    @foreach($row->branch_schedule as $s=>$schedule)
                        {{$s==0 ? '' : '/'}} {{$schedule->days_from==$schedule->days_to ? $schedule->days_from.' & Holidays' : $schedule->days_from.'-'.$schedule->days_to}} {{date('h:i A',strtotime($schedule->time_from))}}-{{date('h:i A',strtotime($schedule->time_to))}}
                    @endforeach
                </b>

            </p>
            </div>
            <div class="card-content card-content-padding">
                <iframe width="100%" height="200" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{{$row->google_maps_api}}" allowfullscreen></iframe>
            </div>
        </div>
    @endforeach

  </div>
</div>
@endsection