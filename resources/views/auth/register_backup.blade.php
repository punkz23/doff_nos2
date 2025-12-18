@extends('layouts.unloggedin')

@section('content')
<div class="position-relative">




                                <div id="signup-box" class="signup-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                New User Registration
                                            </h4>

                                            <div class="space-6"></div>
                                            <p> Enter your details to begin: </p>

                                            <form method="POST" action="{{ route('register') }}">
                                                @csrf
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block">
                                                            <select name="contact_status" class="form-control" required>
                                                                <option selected disabled value="none">--SELECT ACCOUNT TYPE--</option>
                                                                <option {{old('contact_status')==0 ? 'selected' : ''}} value="0">NEW ACCOUNT</option>
                                                                <option  {{old('contact_status')==1 ? 'selected' : ''}} value="1">CHARGE ACCOUNT</option>
                                                            </select>
                                                        </span>
                                                    </label>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" name="lname" placeholder="Lastname" value="{{old('lname')}}" required/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                            @error('lname')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" name="fname" placeholder="Firstname" value="{{old('fname')}}" required/>
                                                            <i class="ace-icon fa fa-user"></i>
                                                            @error('fname')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" name="mname" placeholder="Middlename"  value="{{old('mname')}}" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>
                                                    <div class="charge-account" style="display:none;">
                                                        <label class="block clearfix">
                                                            <span class="block input-icon input-icon-right">
                                                                <input type="text" class="form-control" name="company" placeholder="Company"  value="{{old('company')}}" />
                                                                <i class="ace-icon fa fa-building"></i>
                                                            </span>
                                                        </label>


                                                    </div>

                                                     <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" name="contact_no" placeholder="Contact #"  value="{{old('contact_no')}}" required/>
                                                            <i class="ace-icon fa fa-mobile-phone"></i>
                                                            @error('contact_no')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" required/>
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
                                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password" required/>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password" autocomplete="new-password"/>
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl">
                                                            I accept the
                                                            <a href="{{url('/terms-and-condition')}}">User Agreement</a>
                                                        </span>
                                                    </label>

                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="reset" class="width-30 pull-left btn btn-sm">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Reset</span>
                                                        </button>

                                                        <button type="submit" class="width-65 pull-right btn btn-sm btn-success submit">
                                                            <span class="bigger-110">Register</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>

                                        <div class="toolbar center">
                                            <a href="{{route('login')}}" class="back-to-login-link">
                                                <i class="ace-icon fa fa-arrow-left"></i>
                                                Back to login
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.signup-box -->
                            </div><!-- /.position-relative -->
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){


        $('.submit').click(function(){
            if($('select[name="account_type"]').val()!="" && $('input[name="lname"]').val()!="" && $('input[name="fname"]').val()!="" && $('input[name="contact_no"]').val()!="" && $('input[name="email"]').val()!="" && $('input[name="password"]').val()!="" && $('input[name="password_confirmation"]').val()!="" && ($('input[name="password"]').val()===$('input[name="password_confirmation"]').val())){
                $(this).html('<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><font size="1">Please wait..</font></span>');
            }
        })
    });
</script>
@endsection
