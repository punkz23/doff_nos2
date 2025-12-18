@extends('layouts.theme')

@section('css')
<style>
.received{
    float:left;
    text-align:left;
}

.sent{
    float: right;
    text-align: right;
}
.sent .body{
    background-color: #579ec8;
    color: white;
}

.sent .name a{
    color: #ffffff;
}

.sent .user{

}

.avatar-sender{
    border-radius: 100%;border: 2px solid #5293C4;max-width: 40px;position: relative;
}

.itemdiv .name{
    font-weight:bold;
    font-size:15px;
}

.active{
    background: #eeeeee;
}
</style>
@endsection

@section('bread-crumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{route('home')}}">Home</a>
        </li>
        <li>
            <a href="#">Other Page</a>
        </li>

        <li class="active">Tech Support</li>
    </ul>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        
        <div class="widget-box" style="height:650px;">
            <div class="widget-header">
                <h4 class="widget-title lighter smaller">
                    <i class="ace-icon fa fa-users blue"></i>
                    Clients
                </h4>
            </div>

            <div class="widget-body">
                <div class="widget-main no-padding">
                    <div class="dialogs">
                        @foreach($users as $user)
                            <div class="itemdiv commentdiv userdiv" style="margin-bottom:5px;" id="{{$user->id}}">
                                <div class="user">
                                    <img alt="{{$user->name}}'s Avatar" src="{{asset('/')}}storage/clients/{{$user->avatar}}" />
                                </div>

                                <div class="body">
                                    <div class="name">
                                        <b class="blue">{{$user->name}}</b>
                                    </div>
                                    @if($user->unread)
                                        <div class="time red unread">
                                            <i class="ace-icon fa fa-comment"></i>
                                            <span class="pending">{{$user->unread}}</span>
                                        </div>
                                    @endif
                                    <div class="text">
                                        <i class="ace-icon fa fa-envelope"></i>
                                        {{$user->email}}
                                    </div>
                                </div>

                                <div class="tools">
                                    <div class="inline position-relative">
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" id="messages">
        
    </div>
</div>

@endsection

@section('plugins')
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        var receiver_id='';var my_id="{{Auth::id()}}";
        

        // Pusher.logToConsole = true;

        // var pusher = new Pusher('95fa701463116f4f00b9', {
        //     cluster: 'ap1'
        // });

        Pusher.logToConsole = true;

        var pusher = new Pusher('005680ee53ec4f3524bc', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('my-channel');
        

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
                if(my_id == data.from){
                    $('#'+data.to).click();
                }else if(my_id==data.to){
                    if(receiver_id==data.from){
                        $('#'+data.from).click();
                    }else{
                        var pending = parseInt($('#'+data.from).find('.pending').html());
                        if(pending){
                            $('#'+data.from).find('.pending').html(pending+1);
                        }else{
                            $('#'+data.from).append('<span class="pending">1</span>');
                        }
                    }
                }
        });


            
            

            $('.btn-done').click(function(){
                var id = $(this).attr('id');
                $.ajax({
                    url : "{{url('/update-to-done')}}/"+id,
                    type: "POST",
                    data: {_token: "{{csrf_token()}}", id: id},
                    success: function(result){
                        $('.btn-done').attr('style','display:none');
                    }
                });
            });

            $('.userdiv').click(function(){
                $('.userdiv').removeClass('active');
                $(this).addClass('active');
                $(this).find('.unread').remove();
                receiver_id=$(this).attr('id');
                
                $.ajax({
                    url: "{{url('/chats/create')}}/"+receiver_id,
                    type: "GET",
                    // cache: false,
                    success: function(data){
                        $('#messages').html(data);
                        scrollToBottomFunc();
                        $('.send-message').click(function(e){
                            e.preventDefault();
                            var message = $('input.input-text').val();
                            if(message!='' && receiver_id!=''){
                                $('input.input-text').val('');
                                $.ajax({
                                    url: "{{route('chats.store')}}",
                                    type: "POST",
                                    data : {_token: "{{csrf_token()}}",to: receiver_id, message: message},
                                    cache: false,
                                    success: function(data){
                                    },
                                    error: function(xhr,status,err){
                                    },
                                    complete: function(){
                                        $.ajax({
                                            url: "{{url('/chats/create')}}/"+receiver_id,
                                            type: "GET",
                                            // cache: false,
                                            success: function(data){
                                                $('#messages').html(data);
                                                scrollToBottomFunc();
                                            }
                                        })
                                        scrollToBottomFunc();
                                    }
                                })
                            }
                        })
                    }
                })
            })
            
            function scrollToBottomFunc(){
                $('.message-wrapper').animate({
                    scrollTop : $('.message-wrapper').get(0).scrollHeight
                },50);
            }
        })
    </script>
@endsection
