

@php
    if (!function_exists('number_format_func')){
        function number_format_func($val){
            if($val==0){
                $val='-';
            }else{
                $val=number_format($val,2);
            }
            return $val;
        }
    }
@endphp

<table cellspacing="2" cellpadding="2" border="0" width="100%">
    <tr>
        <td align="center">
            <h3>PREMIUM ACCOUNT</h3>
            {{ $details->full_name }}<br>
            {{ $date_txt }}
        </td>
    </tr>
</table>

<table width="100%"  cellpadding="2" border="1" >

    <tr>
        <th width="7%" >Date</th>
        <th width="20%" >Particular</th>
        <th width="43%" >Reference</th>
        <th width="10%"  >Debit</th>
        <th width="10%" >Credit</th>
        <th width="10%" >Running Balance</th>
    </tr>
    @php
        $pub_dr_id='';
        $dcount=count($result);
        $debit=number_format_func($result[$dcount-1]->withdraw);
        $rbalance=$result[$dcount-1]->withdraw;

        echo '<tr>
        <td colspan="5">Previous Balance</td>
        <td>'.$debit.'</td>
        </tr>';
        foreach($result as $data_main){

            $particular='ADVANCE PAYMENT';
            $debit=number_format_func($data_main->withdraw);
            $credit=number_format_func($data_main->deposit);
            $rbalance=$rbalance+$data_main->withdraw-$data_main->deposit;
            if( $data_main->deposit > 0){
                $particular='PAYMENT';
            }
            if( $data_main->pdate !='PREVIOUS BALANCE' && $data_main->particulars != null && $data_main->particulars !=''){
                $particular=$data_main->particulars;
            }
            if($data_main->pdate !='PREVIOUS BALANCE'){

                $reference='';

                if(  $data_main->reversal_payment_details != null && $data_main->reversal_payment_details !=''){
                    $data_details = explode("^",$data_main->reversal_payment_details);

                    foreach($data_details as $i => $reversal_payment_details ){
                        $data =explode("~",$reversal_payment_details);
                        if($reference !=''){
                            $reference .='<br><br>';
                        }
                        $reference .='Waybill/Ref: '.$data[0];
                        if( substr($data[1],0,2) =='CI'){
                            $reference .='<br>PREPAID';
                        }
                        else if(substr($data[1],0,2)=='CD'){
                            $reference .='<br>COLLECT';
                        }
                        else{
                            $reference .='<br>CHARGE';
                        }
                    }
                }

                if($data_main->payment_details != null && $data_main->payment_details !=''){
                    $data_details = explode("^",$data_main->payment_details);
                    foreach($data_details as $i => $payment_details ){

                        $data =explode("~",$payment_details);

                        if( $data_main->deposit > 0){
                            if($reference !=''){
                                $reference .='<br><br>';
                            }
                            if($data[15] !=''  ){
                                if($pub_dr_id != $data[15]){
                                    $reference .='Delivery Receipt: '.$data[16];
                                }
                                $pub_dr_id=$data[15];
                            }else{
                                if($data[12] ==1 ){
                                    $reference .='Ref: <a>'.$data[2].'</a>';
                                    $reference .='<br>PASABOX CONV. FEE';
                                }else{
                                    $reference .='Waybill/Ref:'.$data[2];
                                    if( substr($data[1],0,2) =='CI'){
                                        $reference .='<br>PREPAID';
                                    }
                                    else if( substr($data[1],0,2) =='CD'){
                                        $reference .='<br>COLLECT';
                                    }
                                    else{
                                        $reference .='<br>CHARGE';
                                    }
                                    if( $data[13] !='' && $data[14] !='' ){
                                        $reference .='<br>'.$data[13].' TO '.$data[14];
                                    }
                                }
                            }

                        }else{

                            if($reference !=''){
                                $reference .='<br><br>';
                            }
                            if($data[3] > 0){
                                $reference .='Mode of Payment: ONLINE';
                                $reference .='<br>Bank: '.$data[6];
                                $reference .='<br>Reference: '.$data[4];
                                $reference .='<br>Date: '.$data[5];
                            }
                            if($data[7] > 0){
                                $reference .='Mode of Payment: CHECK';
                                $reference .='<br>Bank: '.$data[9];
                                $reference .='<br>Check No.: '.$data[8];
                                $reference .='<br>Date: '.$data[10];
                            }
                            if($data[11] > 0){
                                $reference .='Mode of Payment: CASH';
                            }
                        }

                    }
                }
				if( $data_main->particulars != null && $data_main->particulars !=''){
					$reference='Adjustment';
				}

                echo '<tr>
                <td>'.$data_main->pdate.'</td>
                <td>'.$particular.'</td>
                <td>'.$reference.'</td>
                <td>'.$debit.'</td>
                <td>'.$credit.'</td>
                <td>'.number_format_func($rbalance).'</td>
                </tr>';
            }
        }
    @endphp

</table>
