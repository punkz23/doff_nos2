<style>
    p, li {
        font-size: 12px;
    }
    
    
</style>
<table width="100%">
    <tr> 
        <td width="465px">
        <img src="{{public_path('/images/doff logo.png')}}" width="300px" height="45px" style="margin-top:5px" alt="logo">
        </td>
        <td>
            <table width="100%">
                <tr>
                    <td>Reference # : </td>
                    <td><b>{{$data->reference_no}}<b></td>
                </tr>
                <tr>
                    <td>Date Created</td>
                    <td><b>{{date('m/d/Y',strtotime($data->transactiondate))}}</b></td>
                </tr>
            </table>
        </td>
        
    </tr>
</table>
<table width="100%" style="font-size:14px;">
    <tr>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="2"><h3>Shipper Info</h3></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><b style="font-size:12px;">{{strtoupper($data->shipper->fileas)}}</b></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><b style="font-size:12px;">{{$data->shipper_address->full_address}}</b></td>
                </tr>
                <tr>
                    <td>Contact #:</td>
                    <td><b style="font-size:12px;">{{strtoupper($data->shipper->contact_no)}}</b></td>
                </tr>
            </table>
        </td>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="2"><h3>Consignee Info</h3></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><b style="font-size:12px;">{{strtoupper($data->consignee->fileas)}}</b></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><b style="font-size:12px;">{{$data->consignee_address->full_address}}</b></td>
                </tr>
                <tr>
                    <td>Contact #:</td>
                    <td><b style="font-size:12px;">{{strtoupper($data->consignee->contact_no)}}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<h3>Shipments</h3>
<table border="0.5" width="100%" style="font-size:14px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Item Description</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Weight</th>
            <th>Length</th>
            <th>Width</th>
            <th>Height</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data->waybill_shipment as $key=>$row)
            <tr>
                <td>{{($key+1)}}</td>
                <td>
                    {{$row->item_description}}
                </td>
                <td>
                    {{$row->unit_description}}
                </td>
                <td>
                    {{$row->quantity}}
                </td>
                <td>
                    {{$row->weight}}
                </td>
                <td>
                    {{$row->length}}
                </td>
                <td>
                    {{$row->width}}
                </td>
                <td>
                    {{$row->height}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<table width="100%" style="font-color:14px;">
    <tr>
        <td colspan="2">Extra Information</td>
    </tr>
    <tr>
        <td width="20%">Destination</td>
        <td style="font-weight:bold;font-size:12px;">{{strtoupper($data->branch->branchoffice_description)}} BRANCH</td>
    </tr>
    <tr>
        <td width="20%">Declared Value</td>
        <td style="font-weight:bold;font-size:12px;"> {{$data->shipment_type}}</td>
    </tr>
    <tr>
        <td width="20%">Declared Amount</td>
        <td style="font-weight:bold;font-size:12px;"> {{number_format($data->declared_value,2,'.',',')}}</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"> Thank you for your continued patronage!</td>
    </tr>
</table>
<h4>Terms and Conditions</h4>
The shipper in making an Online booking with Daily Overland Freight Forwarder is deemed to have accepted the following terms and conditions:
<br><br>1. The shipper agrees that the cargo is not a prohibited cargo as defined below:
<br>&emsp;a. Any kind of explosives
<br>&emsp;b. Flammable solids or organic peroxides
<br>&emsp;c. Lottery ticket
<br>&emsp;d. Cash and cash equivalent
<br>&emsp;e. Live animals
<br>&emsp;f. Cadaver
<br>&emsp; g. Any shipment whose carriage is prohibited by Philippine law, stature, and/or regulations.
<br>2. Breakable and Perishable Cargoes are not normally accepted for transport. Should you wish to continue shipping a breakable and perishable cargo, you agree to limit the liability of Daily Overland Freight Forwarder up to P1,000.00 only or the actual cost of damage or spoilage whichever is lower.
<br>3. Daily Overland Freight Forwarder's liability for loss or damage to your shipment is limited to your declared value or the actual cost of damages whichever is less. As the shipper, it shall be your responsibility to declare the TRUE WORTH AND VALUE of your shipment.
<br>4. The shipper agrees that should he/she makes a false representation as to the true worth and value of the shipment, then the liability of the company in case of loss, destruction, and/or deterioration is limited to the declared value appearing on the waybill.
<br>5. The shipper agrees the person transacting with the company is duly authorized by the shipper/owner and further agrees that in the event of the questions as to ownership of the cargo the latter is deemed to have assumed full responsibility and further ratifies all acts of the person/ agent authorized by the shipper whether express or implied.
<br>6. The shipper further agrees and stipulates that the company shall conduct verification of the weight and dimension of the cargo and the company has the absolute right to change the information in case of misdeclaration and the shipper shall be bound by it.
<br>7. The company agrees that prior to the shipper or authorized representative to consummate the online booking, the shipper can edit his/her reservation entries and editing shall be disabled once the transaction fulfilled. The shipper likewise agrees that the failure to fulfil the online booking within 7 days from booking, the company has the absolute right to assume that the transaction is cancelled and will be deleted in the list of booking.
<br>8. Should you decide to ship your cargoes to our company please print a copy of this booking and present to our branch representative upon shipping. If unable to print, provide only the screeshot showing the booking reference number shown at the upper part of this document.