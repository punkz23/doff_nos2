

<div class="widget-box">
    <div class="widget-header">
        <h4 class="widget-title lighter smaller">
            <i class="ace-icon fa fa-comment blue"></i>
            {{$name}}
        </h4>
    </div>

    <div class="widget-body">
        <div class="widget-main no-padding">
            <div class="dialogs message-wrapper" style="height:550px;overflow-y: auto;">

                @foreach($messages as $message)
                    <div class="row">
                        <div class="itemdiv dialogdiv {{$message->from == Auth::id() ? 'sent' : 'received'}}">
                            <div class="{{$message->from == Auth::id() ? 'user-sender pull-right' : 'user'}}">
                                <img alt="Alexa's Avatar" class="{{$message->from == Auth::id() ? 'avatar-sender' : ''}}" src="{{asset('/')}}storage/clients/{{$message->from == $message->sender->id ? $message->sender->avatar : $message->receiver->avatar}}" />
                            </div>

                            <div class="body {{$message->from == Auth::id() ? 'pull-left' : ''}}">
                                <div class="time {{$message->from == Auth::id() ? 'pull-left' : ''}}">
                                    <i class="ace-icon fa fa-clock-o"></i>
                                    <span class="{{$message->from == Auth::id() ? 'white' : 'green'}}">{{date('m/d/Y h:i:s A')}}</span>
                                </div>

                                <!-- <div class="name">
                                    <a href="#">{{$message->from == $message->sender->id ? $message->sender->name : $message->receiver->name}}</a>
                                </div> -->
                                <div class="text" style="text-align:justify;">{{$message->message}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- <div class="itemdiv dialogdiv sent">
                    
                    <div class="user-sender pull-right">
                        &nbsp;    
                        <img alt="John's Avatar" class="avatar-sender" src="{{asset('/theme/images/avatars/avatar.png')}}" />
                        
                    </div>
                    <div class="body pull-left">
                        <div class="time pull-left">
                            <i class="white ace-icon fa fa-clock-o"></i>
                            <span class="white">38 sec</span>
                        </div>

                        <div class="name">
                            <a href="#">John</a>
                        </div>
                        <div class="text">Raw denim you probably haven&#39;t heard of them jean shorts Austin.</div>
                    </div>
                </div> -->

                
            </div>

            <form>
                <div class="form-actions">
                    <div class="input-group">
                        <input placeholder="Type your message here ..." type="text" class="form-control input-text" name="message" />
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-info no-radius send-message" type="button">
                                <i class="ace-icon fa fa-share"></i>
                                Send
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div><!-- /.widget-main -->
    </div><!-- /.widget-body -->
</div><!-- /.widget-box -->
