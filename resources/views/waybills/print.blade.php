
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div  id="print_page">
            
            <div class="nav navbar-right panel_toolbox">
                @if(Route::currentRouteName()=='waybills.show')
                    <a target="_blank" class="btn btn-primary" href="{{url('/waybills/printable-reference/'.$encrypt_reference_no)}}"><i class="fa fa-print"></i> Print Details</a>
                    @if( $data->pasabox ==1 )
                    <a target="_blank" class="btn btn-primary" href="{{url('/waybills/printable-label/'.$encrypt_reference_no)}}"><i class="fa fa-print"></i> Print Label</a>
                    @endif
                @else
                    <a href="#"  onclick="printpage_func()" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                @endif
            </div>
            <div class="clearfix"></div>
            </div>
            <div class="x_content">

            <section class="content invoice">
                <!-- title row -->
                <div class="row">
                <div class="col-xs-12 invoice-header">
                    <h3>
                    <img src="{{asset('/images/ICON.png')}}" width="35px" height="35px" alt="logo">
                    DOFF Online
                    <small class="pull-right">Date: {{date('m/d/Y',strtotime($data->transactiondate))}}</small>
                    </h3>
                </div>
                <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                    <table width="100%">
                        <tr>
                            <td width="30%">
                                Shipper
                                <address>
                                    <strong>{{strtoupper($data->shipper->fileas)}}</strong>
                                    <br>{{$data->shipper_address!=null ? $data->shipper_address->full_address : ''}}
                                    <br>Contact No: 
                                    @foreach($shipper_contact as $key=>$waybill_contact)
                                        {{$waybill_contact}}
                                    @endforeach
                                    <br>Email: {{$data->shipper->email}}
                                </address>
                            </td>
                            <td width="30%">
                                Consignee
                                <address>
                                    <strong>{{strtoupper($data->consignee->fileas)}}</strong>
                                    <br>{{$data->consignee_address!=null ? $data->consignee_address->full_address : ''}}
                                    <br>Contact No: @foreach($consignee_contact as $key=>$waybill_contact)
                                        {{$waybill_contact}}
                                    @endforeach
                                    <br>Email: {{$data->consignee->email}}
                                </address>
                            </td>
                            <td width="30%">
                                <b>Reference Number: </b> <label style="font-size:20px;">{{$data->reference_no}}</label>
                                @if( $data->pasabox ==1 )
                                <br>
                                <b>Branch Receiver:</b> {{strtoupper($data->branch_receiver->branchoffice_description)}} BRANCH
                                @endif
                                <br>
                                <b>Destination:</b> {{strtoupper($data->branch->branchoffice_description)}} BRANCH
                                <br>
                                <b>Shipment Type:</b> {{$data->shipment_type}}
                                <br>
                                <b>Declared Value:</b> {{number_format($data->declared_value,2,'.',',')}}
                                <br>
                                <b>Transaction Type:</b>
                                @if( $data->payment_type =='CI' )
                                    {{ 'PREPAID'}}
                                    @if( $data->mode_payment != null && $data->pca_advance_payment==0 )
                                        <br><b>Mode of payment:</b>
                                        
                                        {{ $data->mode_payment ==1 ? 'CASH' : ( $data->mode_payment ==2 ? 'GCASH' : 'OTHERS' ) }}

                                        {{ $data->mode_payment ==2 && $data->mode_payment_io ==1 ? '/IN-STORE' : ( $data->mode_payment ==2 && $data->mode_payment_io ==2 ? '/OUT-STORE' : '' ) }}
                                         
                                        @if( $data->mode_payment ==2 && $data->mode_payment_io ==2 )
                                        <br><b>Email:</b>
                                       
                                            {{ $data->mode_payment_email }}
                                        
                                        @endif
                                       
                                    @endif
                                   
                                @elseif( $data->payment_type =='CD' )  
                                    {{ 'COLLECT' }}  
                                @elseif( $data->payment_type =='CD' )  
                                    {{ 'CHARGE' }}  
                                @endif
                                @if( $data->pca_advance_payment==1 )
                                    <br><b>Mode of payment:</b> Use Adv. Payment
                                @endif
                                @if( $data->pca_pasabox_cf_advance_payment==1 )
                                    <br><b>Pasabox Conv. Fee:</b> Use Adv. Payment
                                @endif
                                @if($data->discount_coupon !='')
                                    <br><b>Discount Coupon: </b> {{ $data->discount_coupon }}
                                @endif
                                
                            </td>
                           <td width="10%" align="center">
                            <div id="qr_code" >
                            </div>
                            {{  $data->pasabox ==1 ? 'PASABOX' : 'REGULAR' }}
                           </td>
                        </tr>
                        @if( $data->pasabox ==1 && $data->shipping_company !='' )
                        <tr>
                            <td colspan="3">
                               
                                Shipping Company
                                <address>
                                    <strong>{{strtoupper($data->shipping_company->fileas)}}</strong>
                                    @if ($data->shipping_address !=null  && $data->shipping_address !='')
                                        <br>{{ $data->shipping_address->full_address }}
                                    @endif
                                    @if ( $shipping_contact->count() > 0)
                                        <br>Contact No: 
                                        @foreach($shipping_contact as $key=>$waybill_contact)
                                            {{$waybill_contact}}
                                        @endforeach
                                    @endif
                                    @if ($data->shipping_company->email != null && $data->shipping_company->email !='')
                                        <br>Email: {{$data->shipping_company->email}}
                                    @endif
                                </address>
                            </td>
                        </tr>
                        @endif
                    </table>
                    @if( $data->pickup ==1 )
                    <table width="100%">
                        <tr>
                            <td ><b>Pick-up address:&nbsp;</b> 
                                <u>{{ strtoupper($data->pickup_sector_street).' '.strtoupper($data->pick_up_sector->barangay).' '.strtoupper($data->pick_up_sector->city).' '.strtoupper($data->pick_up_sector->province).' '.$data->pick_up_sector->postalcode   }} </u> 
                                &emsp;&emsp;
                                <b>Selected pick-up date:&nbsp;</b>
                                <u>{{  date('Y/m/d',strtotime($data->pickup_date)) }}</u>
                            </td>
                            
                        </tr>
                    </table>
                    <br>
                    @endif
                    @if( $data->delivery ==1 )
                    <table width="100%">
                        <tr>
                            <td ><b>Delivery address:&nbsp;</b> 
                                <u>{{ strtoupper($data->delivery_sector_street).' '.strtoupper($data->delivery_sector->barangay).' '.strtoupper($data->delivery_sector->city).' '.strtoupper($data->delivery_sector->province).' '.$data->delivery_sector->postalcode   }} </u> 
                                
                            </td>
                            
                        </tr>
                    </table>
                    <br>
                    @endif
                </div>
                <!-- /.row -->

                <!-- Table row -->
                <div class="row">
                <div class="col-xs-12 table">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Qty</th>
                        <th>Item Description</th>
                        <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->waybill_shipment as $key=>$row)
                        <tr>
                            <td class="hidden-480">
                                {{$row->quantity}}
                            </td>
                            <td class="hidden-480">
                                {{$row->item_description}}
                            </td>
                            <td class="hidden-480">
                                {{$row->unit_description}}
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                <!-- /.col -->
                </div>
                <!-- /.row -->
                
                <div class="row">
                    <!-- accepted payments column -->
                    <div class="col-xs-12">
                        <p class="lead">Thank you for your continued patronage!</p>
                        @if( $data->pasabox ==1 )
                        <h4>Terms and Conditions of Pasabox Booking</h4>   
                        @else
                        <h4>Terms and Conditions</h4>
                        @endif
                        @if($term!=null)
                            {!! $term->content !!}
                        @endif
                        </p>
                    </div>
                    @if( $data->pasabox !=1 )
                    <div class="col-xs-12">  
                        <h4>Notes:</h4> 
                        * Before you enter the barangay where our Manila Branch is located, the barangay officials will facilitate two (2) queuing lines for our transactions, one of which is for those who’ve booked online. For accommodation, kindly present the online booking to our Manila Branch employee giving out the queuing numbers.

                        <br>* There will be separate lane to those who’ve booked online. However, it must be taken into account that our online booking service is not used for appointments, reservations or whatever of the sort as our transactions will be on a “first-come, first-serve” basis. Online booking is only valid for seven (7) days. We advised our customers who have opted to avail Lalamove or any other transport service providers to have their designated couriers comply with the queuing process from start to finish. Remind them not to leave your shipments behind if transaction is still on queue.
                     </div>
                    @endif
                   
                    <h4 style="color:red;"><br>Notice to Client</h4>
                    <p style="color:red;">*Please be reminded to ensure proper and secure packaging of all items prior to shipping, to prevent possible damage or delay in transit. Based on our experience, most damages occur due to improper packaging. Thank you for your cooperation.</p>
                    
                </div>
                <!-- /.row -->

                
            </section>
            </div>
        </div>
    </div>
</div>

<script>
 
    function printpage_func() {    
        window.print();

    }
    @if(Route::currentRouteName() !='waybills.show')
    printpage_func();
    @endif
    print_page=document.getElementById("print_page").innerHTML;
    window.onbeforeprint = function(){
        
        document.getElementById("print_page").innerHTML='';
       
    }
    window.onafterprint = function(){
        
        document.getElementById("print_page").innerHTML=print_page;
    }
    function print_btn(){
        @if(Route::currentRouteName() =='waybills.show')
        window.open("{{url('/waybills/printable-reference/'.$encrypt_reference_no)}}"); 
        window.open("{{url('/waybills/printable-label/'.$encrypt_reference_no)}}");   
        @endif
    }
 
</script>