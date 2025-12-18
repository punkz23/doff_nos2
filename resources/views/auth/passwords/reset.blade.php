@extends('layouts.unloggedin')

@section('content')
<div class="position-relative">
    <div id="login-box" class="login-box visible widget-box no-border">
        <div class="widget-body">
            <div class="widget-main">
                
            <h4 class="header red lighter bigger">
				<i class="ace-icon fa fa-key"></i>
				Reset Password
			</h4>

			<div class="space-6"></div>
			

			<form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
				<fieldset>
					<label class="block clearfix">
						<span class="block input-icon input-icon-right">
							<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Email-Address" />
                            <i class="ace-icon fa fa-envelope"></i>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</span>
                    </label>
                    
                    <label class="block clearfix">
						<span class="block input-icon input-icon-right">
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password" />
                            <i class="ace-icon fa fa-envelope"></i>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
						</span>
                    </label>
                    
                    <label class="block clearfix">
						<span class="block input-icon input-icon-right">
							<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"  placeholder="Confirm Password" />
                            <i class="ace-icon fa fa-envelope"></i>
                           
						</span>
					</label>

					<div class="clearfix">
						<button type="submit" class="width-35 pull-right btn btn-sm btn-danger">
							<i class="ace-icon fa fa-lightbulb-o"></i>
							<span class="bigger-110">Reset Password</span>
						</button>
					</div>
				</fieldset>
			</form>

                
                

                </div><!-- /.widget-main -->

                <div class="toolbar clearfix">
                    <div>
                        <a href="#" class="forgot-password-link">
                            <i class="ace-icon fa fa-arrow-left"></i>
                            I forgot my password
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