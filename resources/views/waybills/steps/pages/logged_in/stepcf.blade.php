<form id="form-step-5" class="form-horizontal">
    <div class="form-group">
        <div class="col-md-5">
            
            <center><img id="gcash_qr"  width="300" width="300"  /></center>        
            <br> 
            <table width="100%">
                <tr>
                    
                    <td align="center">
                        <h4 id="h4_gcash_name"></h4>
                        
                    </td>
                </tr>
                <tr>
                    
                    <td align="center">
                        Please Scan QR Code using Gcash App.
                        
                    </td>
                </tr>
                <tr>
                    
                    <td align="center">
                        <div class="form-group pickup_div" {{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ? 'hidden' :  '' ) : '' }} >
                            <div class="col-md-3"></div>
                            <div class="alert alert-danger alert-dismissible fade in col-sm-6" role="alert">
                            * Requested pick up date is subject to the availability of our facilities. Our Customer Service Representative will contact you shortly on your request. Confirmation on pick up schedule will also be sent to your email.
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        
                    </td>
                </tr>
            </table>
 
        </div>
        
        <div class="col-md-7">
            <b><font size="3">Amount to be Paid: Php <input   value="{{Route::currentRouteName()=='waybills.edit' ? ($data->pasabox ==1 ?  round($data->pasabox_cf_amt,2) : '' ) : '' }}" readonly name="gcash_amount" id="gcash_amount" type="text" style="border-color:transparent;font-weight: bold;width:25%;"  > </font></b>  
            <i>*(does not include freight charges)</i> 
            <br>
            <br>
             *Pay now or Pay Later is part of Gcash advertisement, however our Pasabox transactions requires immediate payment of convenience fee.                                
            <br>
            <br>
            <input  type="hidden" id="gcash_branch_aname"   name="gcash_branch_aname">
			<input  type="hidden" id="gcash_branch_ano"   name="gcash_branch_ano">
            <input  type="hidden" id="gcash_id"   name="gcash_id">
            <input   type="hidden" id="cf_onl_id"   name="cf_onl_id">
            <div {{ Auth::user()->personal_corporate==1 ? '' : 'hidden'  }} class="form-group" > 
                <label class="control-label col-md-4 col-sm-4 col-xs-4" for="name">
                </label>
                <label class="col-md-8 col-sm-8 col-xs-8" for="name">
                <input {{ $pca_adv_bal <=0 ? 'disabled' :'' }} type="checkbox" {{Route::currentRouteName()=='waybills.edit' ? ($data->pca_pasabox_cf_advance_payment==1 && $pca_adv_bal > 0 ? 'checked' : '') : ''}}  name="pca_use_adv_payment_cf" class="pca_use_adv_payment_cf" /> Use Advance Payment
                </label>
            </div>
            <div class="form-group div_cf_require_ref" {{Route::currentRouteName()=='waybills.edit' ? ($data->pca_pasabox_cf_advance_payment==1 && $pca_adv_bal > 0 ? 'hidden' : '') : ''}} >
                <div class="col-md-4">
                    <b>Reference No.<font color="red">*</font></b>
                </div>
                <div class="col-md-8">
                    <input  id="gcash_reference_no" type="text"  name="gcash_reference_no" class="form-control col-md-12 col-xs-12" >
                </div>
            </div> 
            <div class="form-group div_cf_require_ref" {{Route::currentRouteName()=='waybills.edit' ? ($data->pca_pasabox_cf_advance_payment==1 && $pca_adv_bal > 0 ? 'hidden' : '') : ''}}  >
                <div class="col-md-4">
                    <b>Payment Date <font color="red">*</font></b>
                </div>
                <div class="col-md-8">
                    <input  type="date" id="gcash_pdate"  name="gcash_pdate"  type="date"  value="{{  date('Y-m-d') }}"  max="{{  date('Y-m-d') }}" class="form-control col-md-12 col-xs-12" >
                </div>
            </div> 
            <div class="form-group" >
                <div class="col-md-4">
                    <b>Email <font color="red">*</font></b>
                </div>
                <div class="col-md-8">
                    <input  value="{{ Auth::user()->contact->email }}" type="email" id="gcash_cemail" name="gcash_cemail"   class="form-control col-md-12 col-xs-12 email-address" >
                </div>
                <div class="col-md-12">
                *A confirmation will be sent to your email once payment has been confirmed by our staff.
                </div>
            </div>    
            <div class="form-group" >
												
                <div class="col-md-12">
                    <table width="100%">          
                        <tr>
                            <td colspan="2"><br><br>
                                <h5><i class="fa fa-lightbulb-o"></i> Note: </h5>
                                <br><b>Security Reminders:</b>
                                <br>1.	Ensure that your browser shows our verified URL <a href="https://dailyoverland.com" target="_blank">https://dailyoverland.com</a> or <a href="https://track.dailyoverland.com" target="_blank">https://track.dailyoverland.com</a></u>.
                                <br><br>2.	Don’t click links from suspicious email, check the sender first. Our official email address is <u>booking@dailyoverland.com</u>.
                                <br><br>3.	Be alert of PHISHING. Fraudsters use a fake website and email address to get your personal information and create fraudulent transactions. 
                                <br><br>4.	Match the account name shown above the QR Code with your Gcash screen before transferring payment.
                                <br><br>5.	Never share messages you received from Daily Overland containing the unique URL.
                                <br><br>6.	Our company will not be responsible for any payment made from suspicious sites.

                                <br><br><b>Refund Policy:</b>
                                <br><br>1.	Erroneous transactions shall follow a no refund policy if the erroneous transaction is proven to be due to customer error after investigation (e.g. wrong account details, wrong biller). 
                                <br><br>2.	Overpayment made to our account should be reported to us and Gcash Customer Support the same day payment was made. However, only FULL AMOUNT reversal is honored by Gcash. Thus if you wish to proceed with your transaction pending reversal, you will be required to re-transact and pay the correct amount.
                                <br><br>3.  Convenience Fees are non-refundable.


                            </td>
                            
                        </tr>
                    </table>
                    
                </div>
            </div>

        </div>

    </div>
    

</form>