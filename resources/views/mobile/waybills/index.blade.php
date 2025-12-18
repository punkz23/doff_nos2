@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
    <div class="navbar-bg"></div>
    <div class="navbar-inner">
      <div class="left sliding">
        <a href="{{route('home')}}" class="link back external">
          <i class="icon icon-back"></i>
          <span class="if-not-md">Back</span>
        </a>
      </div>
      <div class="title sliding">Bookings</div>
      <div class="right">
        <a href="{{route('waybills.create')}}" class="link icon-only external">
            <i class="icon f7-icons">cart_badge_plus</i>
        </a>
      </div>
      
    </div>
  </div>

  <div class="toolbar toolbar-bottom tabbar">
    <div class="toolbar-inner">
      <a href="#tab-1" class="tab-link tab-link-active">Pending</a>
      <a href="#tab-2" class="tab-link">Transacted</a>
    </div>
  </div>

  <div class="tabs">
    <div id="tab-1" class="page-content tab tab-active">
      <div class="block">
      
        <form class="searchbar pending">
          <div class="searchbar-inner">
            <div class="searchbar-input-wrap">
              <input type="search" placeholder="Search" name="search-pending">
              <i class="searchbar-icon"></i>
              <span class="input-clear-button"></span>
            </div>
          </div>
        </form>
        <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Swipe right/left to see action button(s)</div>
        <div  class="list media-list pending-list">
            <ul class="pending">
                
            </ul>
                
        </div>

      </div>
    </div>
    <div id="tab-2" class="page-content tab">
      <div class="block">
        <form class="searchbar transacted">
          <div class="searchbar-inner">
            <div class="searchbar-input-wrap">
              <input type="search" placeholder="Search" name="search-transacted">
              <i class="searchbar-icon"></i>
              <span class="input-clear-button"></span>
            </div>
          </div>
        </form>
        <div style="margin-left:15px;margin-top:10px;font-size:18px;"><b>Note</b> : Swipe left to see action button</div>
        <div  class="list media-list transacted-list">
            <ul class="transacted">
                
            </ul>
                
        </div>
      </div>
    </div>
    
  </div>
  
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
      app.request({
      url : "{{url('/get-waybills')}}",
      method : "GET",
      beforeSend: function(xhr){
        app.progressbar.show('multi');
      },
      success: function(data,status,xhr){
          var obj = JSON.parse(data);
          var pending = obj['pending'];
          var transacted = obj['transacted'];
          
          var pending_content = '';
          var transacted_content=''
          for(var i = 0;i<pending.length;i++){
            var payment_type = pending[i]['payment_type']=="CI" ? "Prepaid" : "Collect";
            pending_content = pending_content + '<li class="swipeout" id="'+pending[i]['reference_no']+'">'+
                      '<div class="swipeout-content">'+
                      '<a href="#" class="item-link item-content">'+
                      '<div class="item-media">'+
                      '<i class="icon f7-icons">qrcode</i>'+
                      '</div>'+
                      '<div class="item-inner">'+
                      '<div class="item-title-row">'+
                      '<div class="item-title">'+pending[i]['reference_no']+'</div>'+
                      '<div class="item-after"></div>'+
                      '</div>'+
                      '<div class="item-subtitle">'+pending[i]['shipper']['fileas']+'-'+pending[i]['consignee']['fileas']+'</div>'+
                      '<div class="item-text">'+payment_type+' | '+pending[i]['transactiondate']+'<br>Destination : <b>'+pending[i]['branch']['branchoffice_description']+'</b></div>'+
                      '</div>'+
                      '</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-left">'+
                      '<a href="'+"{{url('/waybills')}}"+'/'+pending[i]['reference_no']+'" class="color-blue external">View</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-right">'+
                      '<a href="'+"{{url('/waybills')}}"+'/'+pending[i]['reference_no']+'/edit" class="color-orange external">Update</a>'+
                      '<a href="#" data-confirm="Are you sure you want to delete this item?" class="swipeout-delete swipeout-overswipe" id="'+pending[i]['reference_no']+'" >Delete</a>'+
                      '</div>'+
                      '</li>';
          }
          for(var i = 0;i<transacted.length;i++){
            var payment_type = transacted[i]['payment_type']=="CI" ? "Prepaid" : "Collect";
            transacted_content = transacted_content + '<li class="swipeout" id="'+transacted[i]['reference_no']+'">'+
                      '<div class="swipeout-content">'+
                      '<a href="#" class="item-link item-content">'+
                      '<div class="item-media">'+
                      '<i class="icon f7-icons">qrcode</i>'+
                      '</div>'+
                      '<div class="item-inner">'+
                      '<div class="item-title-row">'+
                      '<div class="item-title">'+transacted[i]['reference_no']+'</div>'+
                      '<div class="item-after"></div>'+
                      '</div>'+
                      '<div class="item-subtitle">'+transacted[i]['shipper']['fileas']+'-'+pending[i]['consignee']['fileas']+'</div>'+
                      '<div class="item-text">'+payment_type+' | '+transacted[i]['transactiondate']+'<br>Destination : <b>'+transacted[i]['branch']['branchoffice_description']+'</b></div>'+
                      '</div>'+
                      '</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-left">'+
                      '<a href="'+"{{url('/waybills')}}"+'/'+transacted[i]['reference_no']+'" class="color-blue swipeout-overswipe external">View</a>'+
                      '</div>'+
                      '<div class="swipeout-actions-right">'+
                      '<a href="#" class="color-green" id="'+transacted[i]['reference_no']+'" >Track and Trace</a>'+
                      '</div>'+
                      '</li>';
          }
          $('.pending-list > ul').empty()
          $('.pending-list > ul').append(pending_content);
          $('.transacted-list > ul').empty()
          $('.transacted-list > ul').append(transacted_content);
          },
          complete: function(xhr,status){
            if(status==200){
              app.progressbar.hide();
            }
          }
        })

        $('input[name="search-pending"]').keyup(function(){
            var searchText = $(this).val();
            $('ul.pending > li.swipeout').each(function(){
                var currentLiText = $(this).text(),
                    showCurrentLi = currentLiText.indexOf(searchText) !== -1;
                $(this).toggle(showCurrentLi);
            });     
        });

        $('input[name="search-transacted"]').keyup(function(){
            var searchText = $(this).val();
            $('ul.transacted > li.swipeout').each(function(){
                var currentLiText = $(this).text(),
                    showCurrentLi = currentLiText.indexOf(searchText) !== -1;
                $(this).toggle(showCurrentLi);
            });     
        });

        app.on('swipeoutDeleted',function(e)
        {
            app.request({
                url : "{{url('/waybills')}}/"+e.id,
                method : "DELETE",
                data : { _token : "{{csrf_token()}}", reference_no : e.id},
                beforeSend: function(xhr){
                    app.dialog.progress('Cancelling transaction..');
                },
                success: function(data,status,xhr){
                  if(status==200){
                    app.dialog.close();
                    var toastBottom = app.toast.create({
                      icon: '<i class="icons f7-icons">cart_badge_minus</i>',
                      text: 'Booking has been deleted',
                      position: 'center',
                      closeTimeout: 3000,
                    });
                    toastBottom.open();
                  }
                },
                error: function(xhr,status){
                  app.dialog.close();
                  var toastBottom = app.toast.create({
                      icon: '<i class="icons f7-icons">exclamationmark_triangle</i>',
                      text: 'Server connection error',
                      position: 'center',
                      closeTimeout: 3000,
                  });
                  toastBottom.open();
                },
                complete: function(xhr,status){
                  if(status==200){
                    app.dialog.close();
                  }
                }
                  
            })
        });

    });
</script>
@endsection