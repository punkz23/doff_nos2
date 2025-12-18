@extends('layouts.theme')

@section('css')
<style>
        ul{
            margin:0;
            padding:0;
        }
        .user-wrapper, .message-wrapper{
            border: 1px solid #dddddd;
            overflow-y: auto;
        }
        .user-wrapper {
            height:600px;
        }

        .user{
            cursor : pointer;
            padding: 5px 0;
            position:relative;
        }
        .user:hover{
            background: #eeeeee;
        }
        .user:last-child{
            margin-bottom:0;
        }
        .pending{
            position: absolute;
            left: 13px;
            top:9px;
            background: #b600ff;
            margin:0;
            border-radius:50%;
            width:18px;
            height:18px;
            line-height:18px;
            padding-left:5px;
            color: #ffffff;
            font-size:12px;
        }
        .media-left{
            margin:0 10px;
        }

        .media-left img{
            width:64px;
            border-radius:64px;
        }

        .media-body{
            padding: 6px 0;
        }

        .media-body p{
            margin:6px 0;
        }

        .message-wrapper{
            padding:10px;
            height:536px;
            background: #eeeeee;
        }

        .messages .message{
            margin-bottom: 15px;
        }

        .messages .message:last-child{
            margin-bottom:0;
        }

        .received, .sent{
            width: 45%;
            padding:3px 10px;
            border-radius: 10px;
        }

        .received{
            background : #ffffff;
        }

        .sent{
            background: #3bebff;
            float: right;
            text-align: right;
        }

        .message p{
            margin: 5px 0;
        }

        .date{
            color: #777777;
            font-size:12px;
        }

        .active{
            background: #eeeeee;
        }

        input[type=text]{
            width: 100%;
            padding: 12px 20px;
            margin: 15px 0 0 0;
            display: inline-block;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
            border: 1px solid #cccccc;
        }

        input[type=text]:focus{
            border: 1px solid #aaaaaa;
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
<!-- <textarea class="wysiwyg-editor" id="editor1"></textarea> -->
<h4 class="header blue">Tech Support</h4>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-4">
            <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search name">
            </div>
            <div class="user-wrapper">
                <ul class="users">
                    @foreach($users as $user)
                    <li class="user" id="{{$user->id}}">
                        @if($user->unread)
                        <span class="pending">{{$user->unread}}</span>
                        @endif
                        <div class="media">
                            <div class="media-left">
                                <img src="{{asset('/')}}storage/clients/{{$user->avatar}}" alt="" class="media-object">
                            </div>
                            <div class="media-body">
                                <p class="name">{{$user->name}}</p>
                                <p class="email">{{$user->email}}</p>
                                @role('Admin')
                                    @if($user->user_status=='Anonymous')
                                        <button class="btn btn-sm btn-success btn-done" id="{{$user->id}}">Done</button>
                                    @endif
                                @endrole
                            </div>
                        </div>
                        
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        

        <div class="col-md-8" id="messages">
            
        </div>
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
        

        Pusher.logToConsole = true;

        var pusher = new Pusher('95fa701463116f4f00b9', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
                alert(JSON.stringify(data));
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

            $('.user').click(function(){
                $('.user').removeClass('active');
                $(this).addClass('active');
                $(this).find('.pending').remove();
                receiver_id=$(this).attr('id');
                
                $.ajax({
                    url: "{{url('/chats/create')}}/"+receiver_id,
                    type: "GET",
                    // cache: false,
                    success: function(data){
                        $('#messages').html(data);
                        scrollToBottomFunc();
                    }
                })
            })

            $(document).on('keyup','.input-text input',function(e){
                var message = $(this).val();
                if(e.keyCode==13 && message!='' && receiver_id!=''){
                    // alert(message);
                    // console.log(receiver_id);
                    $(this).val('');
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

            function scrollToBottomFunc(){
                $('.message-wrapper').animate({
                    scrollTop : $('.message-wrapper').get(0).scrollHeight
                },50);
            }
        })
    </script>
@endsection
