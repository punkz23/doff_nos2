
@if( $data->pasabox ==1 )


<div  class="col-xs-12" id="print_page" style="background-color:white;">   
<br><a href="#"  onclick="printpage_func()" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print</a>
</div>
<div class="col-xs-12 " style="background-color:white;">
    <div id="qr_code" class="pull-right">
    </div>
</div>
<div class="col-xs-12" style="background-color:white;">
    <table width="100%">
        <tr>
            <td width="25%"><font size="6px">SHIPPER:</font></td>
            <td >
                <font size="6px">
                {{strtoupper($data->shipper->fileas)}}
                <br>{{$data->shipper_address!=null ? $data->shipper_address->full_address : ''}}
                <br>
                @foreach($shipper_contact as $key=>$waybill_contact)
                        {{$waybill_contact}}
                @endforeach 
                @if ($data->shipper->email != null && $data->shipper->email !='')
                    <br>{{$data->shipper->email}}  
                @endif
                
                </font>
            </td>
        </tr>
        @if($data->shipping_company !='')
        <tr>
            <td style="border-top: 1px solid #c2adad;" width="25%"><font size="6px">SHIP THRU:</font></td>
            <td style="border-top: 1px solid #c2adad;">
                
                <font size="6px">
                {{strtoupper($data->shipping_company->fileas)}}
                @if ($data->shipping_address !=null  && $data->shipping_address !='')
                    <br>{{$data->shipping_address->full_address}}  
                @endif
                @if ( $shipping_contact->count() > 0)
                    <br>
                    @foreach($shipping_contact as $key=>$waybill_contact)
                            {{$waybill_contact}}
                    @endforeach 
                @endif
                @if ($data->shipping_company->email != null && $data->shipping_company->email !='')
                    <br>{{$data->shipping_company->email}}  
                @endif
                </font>
            </td>
        </tr>
        @endif
        <tr>
            <td style="border-top: 1px solid #c2adad;" width="25%"><font size="6px">SHIP TO:</font></td>
            <td style="border-top: 1px solid #c2adad;" >
                <font size="6px">
                DAILY OVERLAND FREIGHT FORWARDER {{ strtoupper($data->branch_receiver->branchoffice_description) }}
                <br>{{ strtoupper($data->branch_receiver->pasabox_authorized_employee->fileas) }}
                <br>{{ strtoupper($data->branch_receiver->branch_address) }}
                <br>{{ strtoupper($data->branch_receiver->pasabox_incharge_employee_cno) }}
                
                </font>
            </td>
        </tr>
        <tr>
            <td width="25%" style="border-top: 1px solid #c2adad;" ><font size="6px">CONSIGNEE:</font></td>
            <td style="border-top: 1px solid #c2adad;" >
                <font size="6px">
                {{strtoupper($data->consignee->fileas)}}
                <br>{{$data->consignee_address!=null ? $data->consignee_address->full_address : ''}}
                <br>
                @foreach($consignee_contact as $key=>$waybill_contact)
                        {{$waybill_contact}}
                @endforeach 
                @if ($data->consignee->email != null && $data->consignee->email !='')
                    <br>{{$data->consignee->email}}  
                @endif
                </font>
            </td>
        </tr>
    </table>        
    
</div>


@endif

<script>
    function printpage_func() {
        
        window.print();
        
        
    }
    printpage_func();
    print_page=document.getElementById("print_page").innerHTML;
    window.onbeforeprint = function(){
        
        document.getElementById("print_page").innerHTML='';
       
    }
    window.onafterprint = function(){
        
        document.getElementById("print_page").innerHTML=print_page;
    }
</script>