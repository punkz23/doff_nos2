<nav id="nav-menu-container">
    <ul class="nav-menu">
        <li class="menu-active"><a href="{{url('/')}}">Home</a></li>
        <li><a href="https://track.dailyoverland.com">Track your shipment</a></li>
        {{-- <li><a href="{{url('/')}}#news_section">News Updates</a></li> --}}
        <li><a href="{{url('/')}}#services">Services</a></li>
        <li class="menu-has-children"><a>FAQ</a>
        <ul class="online-booking">
            <li><a href="{{route('faqs.outside','English')}}">English</a></li>
            <li><a href="{{route('faqs.outside','Tagalog')}}">Tagalog</a></li>
        </ul>
        </li>
        <li class="menu-has-children" style="display:none;"><a>Serviceable Area</a>
            <ul class="online-booking">
                @foreach($branches as $key=>$row)
                <li><a href="#" class="li_serviceable" data-id="{{$row->id}}" data-name="{{strtoupper($row->name)}}" >{{strtoupper($row->name)}}</a></li>
                @endforeach
            </ul>
        </li>
        <li class="menu-has-children"><a href="#">About Us</a>
        <ul>
            <li><a href="{{url('/')}}#about">Our Company</a></li>
            <li><a href="https://career.dailyoverland.com">Career</a></li>
            <li><a href="{{url('/')}}#portfolio">Branches</a></li>
            <li><a href="{{route('privacy-policy')}}">Privacy Policy</a></li>
        </ul>
        </li>
        <li>
        <a class="menu-has-children" href="#">Contact Us</a>
        <ul>
            <li><a href="{{url('/')}}#complain-feedback" class="contact-us-navi" data-value="complain">Complain</a></li>
            <li><a href="{{url('/')}}#complain-feedback" class="contact-us-navi" data-value="feedback">Feedback</a></li>
            <li><a href="{{route('request.qoutation')}}" target="_blank">Request Quotation</a></li>
        </ul>
        </li>
        <li class="menu-has-children"><a>Customer Login</a>
        <ul class="online-booking">
            <li><a onclick="customer_login_func('regular')" >Regular</a></li>
            <li><a onclick="customer_login_func('pca')">Premium Account</a></li>
            <li><a href="{{url('register')}}" >Register</a></li>
            <!--li><a href="{{route('home')}}">With Account</a></li>
            <li><a href="{{url('/create-booking')}}">Guest</a></li-->
        </ul>
        </li>
    </ul>
    </nav><!-- #nav-menu-container -->
