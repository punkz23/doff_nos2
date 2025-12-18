<table width="100%">
    <tr>
        
        <td width="465px">
            <img src="{{asset('/images/doff logo.png')}}" width="300px" height="45px" style="margin-top:5px" alt="logo">
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

<table width="100%">
    <tr>
        <td width="50%">
            <table width="100%">
                <tr>
                    <td colspan="2"><h3>Shipper Info</h3></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><b>{{strtoupper($data->shipper->fileas)}}</b></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><b>{{$data->shipper_address->full_address}}</b></td>
                </tr>
                <tr>
                    <td>Contact #:</td>
                    <td><b>{{strtoupper($data->shipper->contact_no)}}</b></td>
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
                    <td><b>{{strtoupper($data->consignee->fileas)}}</b></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><b>{{$data->consignee_address->full_address}}</b></td>
                </tr>
                <tr>
                    <td>Contact #:</td>
                    <td><b>{{strtoupper($data->consignee->contact_no)}}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<h3>Shipments</h3>
<table border="0.5" width="100%">
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
<table width="100%">
    <tr>
        <td colspan="2">Extra Information</td>
    </tr>
    <tr>
        <td width="20%">Destination</td>
        <td style="font-weight:bold;">{{strtoupper($data->branch->branchoffice_description)}} BRANCH</td>
    </tr>
    <tr>
        <td width="20%">Declared Value</td>
        <td style="font-weight:bold;"> {{$data->shipment_type}}</td>
    </tr>
    <tr>
        <td width="20%">Declared Amount</td>
        <td style="font-weight:bold;"> {{number_format($data->declared_value,2,'.',',')}}</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"> Thank you for your continued patronage!</td>
    </tr>
</table>

<h4>Terms and Conditions</h4>
@if($term!=null)
    {!! $term->content !!}
@endif