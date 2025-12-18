<!DOCTYPE html>
<html lang="en">
  <head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{asset('/images/ICON.png')}}" rel="icon">

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="{{asset('theme/css/bootstrap.min.css')}}" />
		<link rel="stylesheet" href="{{asset('theme/font-awesome/4.5.0/css/font-awesome.min.css')}}" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="{{asset('theme/css/fonts.googleapis.com.css')}}" />

		<!-- ace styles -->
		<link rel="stylesheet" href="{{asset('theme/css/ace.min.css')}}" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="{{asset('theme/css/ace-part2.min.css')}}" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="{{asset('theme/css/ace-skins.min.css')}}" />
		<link rel="stylesheet" href="{{asset('theme/css/ace-rtl.min.css')}}" />
		<link rel="stylesheet" href="{{asset('/theme/css/jquery.gritter.min.css')}}" />
  </head>
  <body class="skin-1">
  <div class="col-sm-12">  
    <div class="page-header">
      
        <div class="left">
          <h4>
              <img src="about:blank" class="company-logo" width="30px" height="30px" alt="">
              <span class="black"><font color="black">Daily Overland Freight Forwarder 
              <br>
              <small>&emsp;&emsp;Operated by: INLAND SOUTH CARGO EXPRESS INC</small>
              </font></span>
              
          </h4>
        </div>
      
    </div><!-- /.page-header -->
  </div>

  <div class="main-container ace-save-state" id="main-container">
    
    <div class="main-container">
      <div class="main-content">
          <div class="row">
            
            <div class="col-sm-10 col-sm-offset-1">

                <div class="page-content">
                  <div id="login-box" class="login-box visible widget-box no-border">
                    
                      <div class="widget-body">
                          <center>
                          <h1>
                          <font color="green" ><i class="fa fa-check-circle-o" style="font-size: 10rem;"></i></font>
                          <br>THANK YOU FOR YOUR RESPONSE.
                          </h1>
                         
                          <br><br>
                          Do you have questions? We have answers. Like & Follow our Facebook Page & Website.
                          <br><br>
                          Customer Service<br>
                          <a target="_blank" href="https://www.facebook.com/dailyoverland">www.facebook.com/dailyoverland</a>
                          <br>
                          Website<br>
                          <a target="_blank" href="https://www.dailyoverland.com">www.dailyoverland.com</a>
                          <br>
                          Track & Trace<br>
                          <a target="_blank" href="https://www.dailyoverland.com/track">www.dailyoverland.com/track</a>
                          <br>
                          FAQ<br>
                          <a target="_blank" href="https://www.dailyoverland.com/faq">www.dailyoverland.com/faq</a>
                          </center>
                          <!--h4 class="header blue lighter bigger">
                            <i class="ace-icon fa fa-pencil"></i>
                            Claimed Shipment Form
                          </h4>
                          
                          <div class="space-6"></div>

                            
                            <form id="form-claimed" class="form-horizontal" >
                              @csrf
                              <fieldset>
                                
          
                                <div class="item form-group">
                                  <div class="col-sm-4">
                                    
                                    <div class="input-group input-group-sm col-sm-12">
                                      <label class="input-group-addon" for="dataScaleX">Date claimed</label>
                                      <input type="date" class="form-control" id="dataScaleX" name="email" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" >
                                    </div>
                                  </div>
                                  <div class="col-sm-8">
                                    <div class="input-group input-group-sm col-sm-12">
                                      <label class="input-group-addon" for="dataScaleX">Claimed by</label>
                                      <input type="text" class="form-control" id="dataScaleX" name="email"  >
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                                  <i class="fa fa-money"></i> PAYMENT DETAILS
                                </div>

                                
                                <div class="item form-group">
                                  <div class="col-sm-12">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <label style="width:60px;" class="input-group-addon" for="dataScaleX">Cash</label>
                                      <input type="number" class="form-control"  id="dataScaleX" name="email" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" >
                                    </div>
                                  </div>
                                </div>
                                <div class="item form-group">
                                  <div class="col-sm-2">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <label style="width:60px;" class="input-group-addon" for="dataScaleX">Check</label>
                                      <input type="number"  class="form-control"  id="" name="email"  >
                                     
                                    </div>
                                    
                                  </div>
                                  <div class="col-sm-3">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <input type="text"  placeholder="CHECK NUMBER" class="form-control"  id="" name="email"  >
                                    </div>
                  
                                  </div>
                                  <div class="col-sm-2">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <input type="date" class="form-control"  id="" name="email"  >
                                    </div>
                                  </div>
                                  
                                  <div class="col-sm-3">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <select name="checkbank" id="checkbank" class="select2_group form-control" style="width: 100%;"  >
                                        <option selected value="">Select Bank</option>
                                      </select>
                                      </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <select name="checkbank" id="checkbank" class="select2_group form-control" style="width: 100%;"  >
                                        <option selected value="">Check Type</option>
                                      </select>
                                      </div>
                                  </div>
                                </div>
                                <div class="item form-group">
                                  <div class="col-sm-2">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <label style="width:60px;" class="input-group-addon" for="dataScaleX">Online</label>
                                      <input type="number" class="form-control"  id="dataScaleX" name="email" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" >
                                    </div>
                                  </div>
                                  <div class="col-sm-3">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      
                                      <input type="text" placeholder="Verification/Reference No." class="form-control" id="dataScaleX" name="email"  >
                                    </div>
                                  </div>
                                  <div class="col-sm-2">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <input type="date" class="form-control"  id="" name="email"  >
                                    </div>
                                  </div>
                                  
                                  <div class="col-sm-5">
                                    <div class="input-group input-group-sm" style="width:100%;">
                                      <select name="checkbank" id="checkbank" class="select2_group form-control" style="width: 100%;"  >
                                        <option selected value="">Select Bank</option>
                                      </select>
                                      </div>
                                  </div>
                                </div>
                                <div class="clearfix">
                                  <br><br>
                                  <button type="submit" class=" pull-right btn btn-sm btn-primary submit">
                                      <i class="ace-icon fa fa-pencil"></i>
                                      <span class="bigger-110">Submit</span>
                                  </button>
                                </div>
                              </fieldset>
                            </form--> 
                       
                        
                      </div>
                    
                </div>
              </div>
            </div>
          
        
        </div>  
    </div>

    
  </div>

  <div class="footer">
    <div class="footer-inner">
      <div class="footer-content">
        <span class="action-buttons">
          <a href="https://www.dailyoverland.com" target="_blank" title="WEBSITE">
            <i class="ace-icon fa fa-globe light-blue bigger-150"></i>
          </a>

          <a href="https://www.facebook.com/dailyoverland" target="_blank" title="FACEBOOK">
            <i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
          </a>

          <a href="https://www.dailyoverland.com/faq" target="_blank" title="FAQ">
            <i class="ace-icon fa fa-info-circle orange bigger-150"></i>
          </a>
          <a href="https://www.dailyoverland.com/track" target="_blank" title="TRACK AND TRACE">
            <i class="ace-icon fa fa-truck green bigger-150"></i>
          </a>
        </span>
      </div>
    </div>
  </div>
   
  </body>
</html>
<script src="{{asset('theme/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('theme/js/bootstrap.min.js')}}"></script>
<script src="{{asset('theme/js/ace-elements.min.js')}}"></script>
<script src="{{asset('theme/js/ace.min.js')}}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="{{asset('/js/aes.js')}}"></script>
<script src="{{asset('/js/script.js')}}"></script>