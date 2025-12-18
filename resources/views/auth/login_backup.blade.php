@extends('layouts.unloggedin')

@section('content')
<div class="position-relative">
    <div id="login-box" class="login-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                <h4 class="header blue lighter bigger">
                    <i class="ace-icon fa fa-coffee green"></i>
                    Please Enter Your Information

                    @if(isset($message))
                        {{$message}}
                    @endif
                </h4>

                <div class="space-6"></div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <fieldset>
                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <!-- <input type="text" class="form-control" placeholder="Username" /> -->
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                                <i class="ace-icon fa fa-user"></i>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </label>

                        <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <!-- <input type="password" class="form-control" placeholder="Password" /> -->
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                                <i class="ace-icon fa fa-lock"></i>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </label>

                        <div class="space"></div>

                        <div class="clearfix">
                            <label class="inline">
                                <!-- <input type="checkbox" class="ace" /> -->
                                <input class="ace" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="lbl"> Remember Me</span>
                            </label>

                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary submit">
                                <i class="ace-icon fa fa-key"></i>
                                <span class="bigger-110">Login</span>
                            </button>
                        </div>

                        <div class="space-4"></div>
                    </fieldset>
                </form>

                <div class="social-or-login center">
                    <span class="bigger-110">Or Login Using</span>
                </div>

                <div class="space-6"></div>

                    <div class="social-login center">
                        <a href="{{ url('auth/facebook') }}" class="btn btn-primary social-button" title="Facebook">
                            <i class="ace-icon fa fa-facebook"></i>
                        </a>

                        <a href="{{ url('auth/google') }}" class="btn btn-danger social-button" title="Google">
                            <i class="ace-icon fa fa-google-plus"></i>
                        </a>
                    </div>

                    <div class="center spinner" style="display: none;">
                        <h3 class="smaller lighter grey">
                            <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>
                            <h4>Please wait..</h4>
                        </h3>
                    </div>

                </div><!-- /.widget-main -->

                <div class="toolbar clearfix">
                    <div>
                        <a href="{{ url('/') }}" class="forgot-password-link">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            Back to landing page
                        </a>
                    </div>

                    <div>
                        <a href="{{route('register')}}"class="user-signup-link">
                            I want to register
                            <i class="ace-icon fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
        </div><!-- /.widget-body -->
    </div><!-- /.login-box -->

                                
</div><!-- /.position-relative -->
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.social-button').click(function(){
            $('.spinner').removeAttr('style');
            $('.social-login').attr('style','display:none');
            $('.submit').attr('disabled',true);
        });

        $('.submit').click(function(){
            if($('input[name="email"]').val()!="" && $('input[name="password"]').val()!=""){
                $(this).html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');    
            }
        })
    });
</script>
@endsection







