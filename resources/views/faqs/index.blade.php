@extends('layouts.gentelella')

@section('css')
<style>
.page-content {
	max-width: 100%; 
	background: #fff;
}
a {
	color: #21D4FD; 
	transition: all 0.3s;
}
a:hover {
	color: #B721FF;
}

.tabbed {
	overflow-x: hidden; /* so we could easily hide the radio inputs */
	margin: 32px 0;
	padding-bottom: 16px;
	border-bottom: 1px solid #ccc;
}

.tabbed [type="radio"] {
	/* hiding the inputs */
	display: none;
}

.tabs {
	display: flex;
	align-items: stretch;
	list-style: none;
	padding: 0;
	border-bottom: 1px solid #ccc;
}
.tab > label {
	display: block;
	margin-bottom: -1px;
	padding: 12px 15px;
	border: 1px solid #ccc;
	background: #eee;
	color: #666;
	font-size: 12px; 
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 1px;
	cursor: pointer;	
	transition: all 0.3s;
}
.tab:hover label {
	border-top-color: #333;
	color: #333;
}

.tab-content {
	display: none;
	color: #777;
}

/* As we cannot replace the numbers with variables or calls to element properties, the number of this selector parts is our tab count limit */
.tabbed [type="radio"]:nth-of-type(1):checked ~ .tabs .tab:nth-of-type(1) label,
.tabbed [type="radio"]:nth-of-type(2):checked ~ .tabs .tab:nth-of-type(2) label,
.tabbed [type="radio"]:nth-of-type(3):checked ~ .tabs .tab:nth-of-type(3) label,
.tabbed [type="radio"]:nth-of-type(4):checked ~ .tabs .tab:nth-of-type(4) label,
.tabbed [type="radio"]:nth-of-type(5):checked ~ .tabs .tab:nth-of-type(5) label {
	border-bottom-color: #fff;
	border-top-color: #B721FF;
	background: #fff;
	color: #222;
}

.tabbed [type="radio"]:nth-of-type(1):checked ~ .tab-content:nth-of-type(1),
.tabbed [type="radio"]:nth-of-type(2):checked ~ .tab-content:nth-of-type(2),
.tabbed [type="radio"]:nth-of-type(3):checked ~ .tab-content:nth-of-type(3),
.tabbed [type="radio"]:nth-of-type(4):checked ~ .tab-content:nth-of-type(4) {
	display: block;
}

.bah-accordion {
	width: 100%;
	margin: 10px auto 30px auto;
  text-align: left;
}

.bah-accordion__element__header {
	height: 30px;
  padding: 5px 20px;
  position: relative;
  z-index: 20;
  display: block;
  height: 30px;
  cursor: pointer;
  height: 30px;
}

.bah-accordion__element__header:hover {
  background: #fff;
}

.bah-accordion__element__check:checked + .bah-accordion__element__header,
.bah-accordion__element__check:checked + .bah-accordion__element__header:hover {
  height: 30px;
}

.bah-accordion__element__header:hover:after,
.bah-accordion__element__check:checked + .bah-accordion__element__header:hover:after {
  content: '';
  position: absolute;
  width: 24px;
  height: 24px;
  right: 13px;
  top: 7px;
}

.bah-accordion__element__check {
  display: none;
}

.bah-accordion__element__content {
  overflow: hidden;
  height: 0;
  position: relative;
  z-index: 10;
  -webkit-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -moz-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -o-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  -ms-transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
  transition: height 0.3s ease-in-out,box-shadow 0.6s linear;
}

.bah-accordion__element__check:checked ~ .bah-accordion__element__content {
  -webkit-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -moz-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -o-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  -ms-transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
  transition: height 0.5s ease-in-out,box-shadow 0.1s linear;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--small {
  height: auto;
  margin: 0 20px;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--medium {
  height: auto;
}

.bah-accordion__element__check:checked ~.bah-accordion__element__content--large {
  height: auto;
}
</style>
@endsection

@section('bread-crumbs')
<!--h3>
    FAQ
    <small>
        <i class="ace-icon fa fa-angle-double-right"></i>
        frequently asked questions
    </small>
</h3-->

@endsection

@section('content')




<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        @if(strtoupper($dialect)=='ENGLISH')
        <p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">Say goodbye to your shipping hassles because this May 20, 2020, Daily Overland Freight Forwarder will be back on the run!.</p><p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">Questions? Want to know more about our services? Let our team who has real time knowledge on product shipping assist you. Send us a message. Chat us on our Facebook page&nbsp;<a href="https://www.facebook.com/dailyoverland/" style="color: rgb(69, 174, 214); transition: color 400ms ease 0s, background-color 400ms ease 0s;">https://www.facebook.com/dailyoverland/</a>
        @else
        <p class="text-center wow fadeInDown animated" style="margin-bottom: 0px; animation-duration: 1s; animation-fill-mode: both; animation-name: fadeInDown; color: rgb(100, 104, 109); font-family: Roboto, sans-serif; font-size: 14px; visibility: visible;">May mga problema sa pagpapadala? Huwag mabahala sapagkat simula ngayong Hunyo 2020, ang Daily Overland Freight Forwarder ay muling aarangkada na Mayroon ka bang mga katanungan? Haka-haka sa aming mga serbisyo? Hayaan niyo ang aming team na may kaukulang kaalaman sa pagpapadala ng inyong mga kargamento ang tumulong sa inyo. Mangyaring malaman kung papaano? Padalhan niyo kami ng mensahe gamit ang aming Facebook page&nbsp;<a href="https://www.facebook.com/dailyoverland/" style="background-color: rgb(255, 255, 255); color: rgb(69, 174, 214); transition: color 400ms ease 0s, background-color 400ms ease 0s;">https://www.facebook.com/dailyoverland/</a><br></p>
        @endif
        <div class="tabbed">
            <input type="radio" id="tab1" name="css-tabs" checked>
            <input type="radio" id="tab2" name="css-tabs">
            
            
            <ul class="tabs">
                <li class="tab"><label for="tab1">{{strtoupper($dialect) == 'ENGLISH' ? 'General' : 'Pangkalahatan'}}</label></li>
                <li class="tab"><label for="tab2">Online Booking</label></li>
                
            </ul>
            
            <div class="tab-content">
                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-1" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-1-1" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Helpful tips for hassle-free shipping' : 'Mga makakatulong na tip para sa madaliang pagpapadala'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide1')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-2" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-1-2" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Where are your branches located and thier schedules' : 'Saan matatagpuan ang inyong mga branches at ano ang mga schedules nito?'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                        @include('faqs.contents.'.strtolower($dialect).'.general.guide2')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-3" name="test-accordion" type="radio" class="bah-accordion__element__check">
                        <label for="question-1-3" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'What are your services' : 'Ano-ano ang inyong mga serbisyo'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                        @include('faqs.contents.'.strtolower($dialect).'.general.guide3')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-4" name="test-accordion" type="radio" class="bah-accordion__element__check">
                        <label for="question-1-4" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'Shipping Requirement and Packaging guidelines' : 'Ano nga ba ang dapat kong gawin bago ko ipadala ang aking mga kargamento sa inyong mga branches?'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                        @include('faqs.contents.'.strtolower($dialect).'.general.guide4')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-5" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-1-5" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'I have a representative who will receive my shipment, what should I do' : 'Meron akong representante na tatanggap ng aking mga pinadalhang kargamento, ano ang dapat kong gawin'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide5')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-6" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-1-6" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How much will it cost you?' : 'Magkano nga ba ang magpadala sa inyo?'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide6')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-1-7" name="test-accordion" type="radio" class="bah-accordion__element__check">
                        <label for="question-1-7" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How do I track my shipments' : 'Paano ko nga ba itrack ang aking pinadalang mga kargamento'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.general.guide7')
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-content">
                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-2-1" name="test-accordion" type="radio" class="bah-accordion__element__check">
                        <label for="question-2-1" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to create online booking' : 'Paano gumawa ng online booking'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide1')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-2-2" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-2-2" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to add shipper/consignee' : 'Paano magdagdag ng shipper/consignee'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide2')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-2-3" name="test-accordion" type="radio" class="bah-accordion__element__check">
                        <label for="question-2-3" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to update account information' : 'Paano iupdate ang impormasyon ng account'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                            @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide3')
                        </div>
                    </div>
                </div>

                <div class="bah-accordion">
                    <div class="bah-accordion__element">
                        <input id="question-2-4" name="test-accordion" type="radio"  class="bah-accordion__element__check">
                        <label for="question-2-4" class="bah-accordion__element__header">{{strtoupper($dialect) == 'ENGLISH' ? 'How to register account' : 'Paano gumawa ng account'}}</label>
                        <div class="bah-accordion__element__content bah-accordion__element__content--small">
                        @include('faqs.contents.'.strtolower($dialect).'.onlinebooking.guide4')
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</div><!-- /.row -->
@endsection

@section('plugins')


<!-- page specific plugin scripts -->
<script src="{{asset('/theme/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/theme/js/jquery.dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.print.min.js')}}"></script>
<script src="{{asset('/theme/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('/theme/js/dataTables.select.min.js')}}"></script>
@endsection

@section('scripts')
@role('Admin')
<script type="text/javascript">
    $(document).ready(function(){
        $('.remove').click(function(e){

            swal({
              title: "Are you sure?",
              text: "Delete this FAQ!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{url('/guides')}}/"+e.target.id,
                    type: "DELETE",
                    data : { _token : "{{csrf_token()}}"},
                    success: function(result){
                        swal(result.message, {
                          icon: result.type,
                          title: result.title
                        }).then(function(){
                            if(result.type=="success"){
                                window.location.reload(true);
                            }
                        });
                    }
                })

              }
            });
        })
    })
</script>
@endrole
@endsection
