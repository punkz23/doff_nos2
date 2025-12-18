<table>
    <tr>
        <th>Publication</th>
        <td>{{ $details->full_name }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>Issue Date (Format: MM/DD/YYYY)</td>
        <td>{{ $issue_date }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="color:white;">{{ base64_encode($details->pca_account_no) }}</td>
        <td></td>
        <td></td>
        <td colspan="4" align="center" >Address</td>
        <td colspan="2" align="center">Quantity</td>
    </tr>
    <tr>
        <td>Agent</td>
        <td>Contact Person</td>
        <td>Contact Number</td>
        <td>Street</td>
        <td>Barangay</td>
        <td>City</td>
        <td>Province</td>
        <td>Main</td>
        <td>Tabloid</td>
    </tr>
    @foreach($data as $row)
        <tr>
            <td>{{ $row->publication_agent_name }}</td>
            <td>{{ $row->contact_person }}</td>
            <td>{{ $row->contact_no }}</td>
            <td>{{ $row->street }}</td>
            <td>{{ $row->barangay }}</td>
            <td>{{ $row->cities_name }}</td>
            <td>{{ $row->province_name }}</td>
            <td>{{ $row->main_qty }}</td>
            <td>{{ $row->tabloid_qty }}</td>
        </tr>
    @endforeach

</table>
