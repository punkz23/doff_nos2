
<div class="modal fade waybill_details_modal" role="dialog"   aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title waybill_details_modal_h4" >DETAILS </h4>
			</div>
			<div class="modal-body">
                <div class="spinner waybill_details_modal_loading"><center><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</center><br></div>
                <table width="100%"  class="table table-striped " id="tbl_waybill_details">
                    
                </table>

                <table class="table table-striped ">
                    <tr style="background-color:#AED6F1;">
                        <td colspan="3">SHIPMENT DETAILS</td>
                    </tr>
                    <tr >
                        <td>Item Description</td>
                        <td>Item Unit</td>
                        <td>Cargo Type</td>
                    </tr>
                    <tbody id="tbl_waybill_shipment_details">
											
                    </tbody>
                </table>

                <table class="table table-striped table-bordered">
                    <tr style="background-color:#AED6F1;" >
                        <td colspan="12">BILL BREAKDOWN</td>
                    </tr>
                    <tr>
                        <td >Freight</td>
                        <td >Discount</td>
                        <td >Subtotal</td>
                        <td >Pick Up</td>
                        <td >Delivery</td>
                        <td >Other/s</td>
                        <td >Declared Amount</td>
                        <td >Insurance</td>
                        <td >BIR 2307</td>
                        <td >BIR 2306</td>
                        <td >VAT</td>
                        <td >Total Amount</td>
                    </tr>
                    <tbody id="tbl_waybill_bill">
                    
                    </tbody>
                    
                </table>
				
				
				<div class="modal-footer">
				</div>
				
				
			</div>
		</div>
	</div>
</div>
<script>
function waybill_details_func(tcode){
    //$(".waybill_details_modal_h4").html('WAYBILL DETAILS | #'+wno);
   $("#tbl_waybill_details").html('');
   $("#tbl_waybill_shipment_details").html('');
   $("#tbl_waybill_bill").html('');
   
   
    $(".waybill_details_modal_loading").show();
    $.ajax({
        url: "{{url('/get-waybill-details')}}/"+tcode, 
        method: 'get',
        success: function(result){  
            var result = JSON.parse(result);  
           // console.log(result);   
            $.each(result,function(){
                $("#tbl_waybill_details").append('<tr style="background-color:#AED6F1;" >'+
                '<td>WAYBILL DETAILS</td>'+
                '</tr>');  
              $("#tbl_waybill_details").append('<tr>'+
                '<td>'+this.waybill_no+'<div id="wd_append_1"></div></td></tr>');

                if((this.transactioncode).substr(0,2) == 'CI'){
                    $("#wd_append_1").append('PREPAID');
                }
                else if((this.transactioncode).substr(0,2) == 'CD'){
                    $("#wd_append_1").append('COLLECT');
                }
                else if((this.transactioncode).substr(0,2) == 'CH'){
                    $("#wd_append_1").append('CHARGE');
                }

                $("#wd_append_1").append('<br>Tracking: '+this.tracking_no);
              
                source=this.source;
                destination=this.destination;

                $("#wd_append_1").append('<br>'+source['branchoffice_description']+' - '+destination['branchoffice_description']);

                $("#tbl_waybill_details").append('<tr>'+
                '<td>Shipper<div id="wd_append_2"></div></td></tr>');
                shipper=this.shipper;
                $("#wd_append_2").append(shipper['fileas']);  
                $("#wd_append_2").append('<br>'+this.shipper_contactno); 

                shipper_address=this.shipper_address;
                shipper_address_txt='';
                if(shipper_address['street'] !='' && shipper_address['street'] !=null ){
                    shipper_address_txt +=shipper_address['street'];
                }
                if(shipper_address['barangay'] !='' && shipper_address['barangay'] !=null ){
                    shipper_address_txt +=' '+shipper_address['barangay'];
                }
                if(shipper_address['city'] !='' && shipper_address['city'] !=null ){
                    shipper_address_txt +=' '+shipper_address['city'];
                }
                if(shipper_address['province'] !='' && shipper_address['province'] !=null ){
                    shipper_address_txt +=' '+shipper_address['province'];
                }
                if(shipper_address['postal_code'] !='' && shipper_address['postal_code'] !=null ){
                    shipper_address_txt +=' '+shipper_address['postal_code'];
                }
                $("#wd_append_2").append('<br>'+shipper_address_txt); 
                
                $("#tbl_waybill_details").append('<tr>'+
                '<td>Consignee<div id="wd_append_3"></div></td></tr>');
                shipper=this.shipper;
                $("#wd_append_3").append(shipper['fileas']);  
                $("#wd_append_3").append('<br>'+this.shipper_contactno); 

                consignee_address=this.consignee_address;
                consignee_address_txt='';
                if(consignee_address['street'] !='' && consignee_address['street'] !=null ){
                    consignee_address_txt +=consignee_address['street'];
                }
                if(consignee_address['barangay'] !='' && consignee_address['barangay'] !=null ){
                    consignee_address_txt +=' '+consignee_address['barangay'];
                }
                if(consignee_address['city'] !='' && consignee_address['city'] !=null ){
                    consignee_address_txt +=' '+consignee_address['city'];
                }
                if(consignee_address['province'] !='' && consignee_address['province'] !=null ){
                    consignee_address_txt +=' '+consignee_address['province'];
                }
                if(consignee_address['postal_code'] !='' && consignee_address['postal_code'] !=null ){
                    consignee_address_txt +=' '+consignee_address['postal_code'];
                }
                $("#wd_append_3").append('<br>'+consignee_address_txt);

                if( Number(this.delivery) ==1 ){

                    if(this.delivery_sector_street  =='' || this.delivery_sector_street ==null){
						this.delivery_sector_street='';
					}
					$("#tbl_waybill_details").append('<tr><td>DELIVERY: <u>'+this.delivery_sector_street+' '+this.delivery_sector_barangay+' '+this.delivery_sector_city+' '+this.delivery_sector_province+' '+this.delivery_sector_postalcode)+'</u></td></tr>';
                }

                waybill_shipment=this.waybill_shipment;
                
                total_qty=0;
                $.each(waybill_shipment,function(){

                    cargo_type=this.cargo_type;
                    total_qty=total_qty+Number(this.quantity);
                    $("#tbl_waybill_shipment_details").append('<tr>'+
                        '<td>'+this.item_description+'</td>'+
                        '<td>'+this.unit_description+'</td>'+
                        '<td>'+cargo_type['cargotype_description']+'</td>'+
                        '</tr>'+
                        '<tr >'+
				            '<td colspan="3">'+
				            '<table class="table table-striped table-bordered"><tbody id="tbl_'+this.waybill_shipment_id+'"></tbody></table>'+
				            '</td>'+
				        '</tr>');

                        $("#tbl_"+this.waybill_shipment_id).append('<tr>'+
                        '<td></td>'+
                        '<td>Quantity</td>'+
                        '<td>Weight</td>'+
                        '<td>Length</td>'+
                        '<td>Width</td>'+
                        '<td>Height</td>'+
                       // '<td>Price</td>'+
                        //'<td>Total Amount</td>'+
                        '</tr>'+
                        '<tr style="background-color:#E5E7E9;" id="tbl_'+this.waybill_shipment_id+'_2" >'+
					    '<td></td>'+
                        '<td id="td_qty_'+this.waybill_shipment_id+'"></td>'+
                        '<td id="tdweight_'+this.waybill_shipment_id+'"></td>'+
                        '<td id="tdlength_'+this.waybill_shipment_id+'"></td>'+
                        '<td id="tdwidth_'+this.waybill_shipment_id+'"></td>'+
                        '<td id="tdheight_'+this.waybill_shipment_id+'"></td>'+
                        //'<td align="right">-</td>'+
                        //'<td id="tdamount_'+waybill_shipment_id+'" align="right"></td>'+
                        '</tr>');
                        waybill_shipment_details=this.waybill_shipment_details;

                        tqty=0; 
                        tweight=0;
                        tlength=0;
                        twidth=0;
                        theight=0;
                        tamount=0;
                        $.each(waybill_shipment_details,function(){   
                            if(Number(this.quantity) > 0){
                                tqty=tqty+Number(this.quantity);
                            }else{
                                this.quantity='-';
                            }
                            if(Number(this.weight) > 0){
                                if(this.each_all=='EACH'){
                                    tweight=tweight+(Number(this.weight)*Number(this.quantity));
                                }else{
                                    tweight=tweight+Number(this.weight);
                                }
                            }else{
                                this.weight='-';
                            }
                            if(Number(this.lenght) > 0){
                                tlength=tlength+(Number(this.lenght)*Number(this.quantity));
                            }else{
                                this.lenght='-';
                            }
                            if(Number(this.width) > 0){
                                twidth=twidth+(Number(this.width)*Number(this.quantity));
                            }else{
                                this.width='-';
                            }
                            if(Number(this.height) > 0){
                                theight=theight+(Number(this.height)*Number(this.quantity));
                            }else{
                                this.height='-';
                            }
                            if(Number(this.total_amount) > 0){
                                tamount=tamount+Number(this.total_amount);
                            }else{
                                this.tamount='-';
                            }
                            if(tqty > 0){
                                $("#td_qty_"+this.waybill_shipment_id).html(tqty);
                            }else{
                                $("#td_qty_"+this.waybill_shipment_id).html('-');
                            }
                            if(tweight > 0){
                                $("#tdweight_"+this.waybill_shipment_id).html(tweight);
                            }else{
                                $("#tdweight_"+this.waybill_shipment_id).html('-');
                            }
                            if(tlength > 0){
                                $("#tdlength_"+this.waybill_shipment_id).html(tlength);
                            }else{
                                $("#tdlength_"+this.waybill_shipment_id).html('-');
                            }
                            if(twidth > 0){
                                $("#tdwidth_"+this.waybill_shipment_id).html(twidth);
                            }else{
                                $("#tdwidth_"+this.waybill_shipment_id).html('-');
                            }
                            if(theight > 0){
                                $("#tdheight_"+this.waybill_shipment_id).html(theight);
                            }else{
                                $("#tdheight_"+this.waybill_shipment_id).html('-');
                            }
                            // if(tamount > 0){
                            //     $("#tdamount_"+this.waybill_shipment_id).html(Number(tamount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","));
                            // }else{
                            //     $("#tdamount_"+this.waybill_shipment_id).html('-');
                            // }
                            if(this.each_all=='EACH'){
                                each_all_text='INDIVIDUAL';
                            }else{
                                each_all_text='GROUP';
                            }   

                            $("#tbl_"+this.waybill_shipment_id+'_2').before('<tr>'+
                            '<td >'+each_all_text+'</td>'+
                            '<td >'+this.quantity+'</td>'+
                            '<td >'+this.weight+'</td>'+
                            '<td >'+this.lenght+'</td>'+
                            '<td >'+this.width+'</td>'+
                            '<td >'+this.height+'</td>'+
                            //'<td align="right">'+Number(this.price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+'</td>'+
                            //'<td align="right">'+Number(this.total_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+'</td>'+
                            '</tr>');
                        });    
                });  

                $("#tbl_waybill_shipment_details").append('	<td  id="tcargo_qty" style="display:none;"></td>'+
				'<td colspan="3">Total Cargo: '+total_qty+'</td>'+
				'</tr>');

                subtotal='-';
				total='-';
				
				if( (Number(this.vat_amount)+Number(this.amount_due)) > 0 ){
					total=Number(Number(this.vat_amount)+Number(this.amount_due)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
				}
				if( (Number(this.freight_amount)-Number(this.discount_amount)) > 0 ){
					subtotal=Number(Number(this.freight_amount)-Number(this.discount_amount)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
				}
				if(Number(this.freight_amount) > 0){
					
					this.freight_amount=Number(this.freight_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.freight_amount='-';
				}
				
				if(Number(this.discount_amount) > 0){
					
					this.discount_amount=Number(this.discount_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.discount_amount='-';
				}
				
				if(Number(this.pickup_charge) > 0){
					
					this.pickup_charge=Number(this.pickup_charge).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.pickup_charge='-';
				}
				
				if(Number(this.delivery_charge) > 0){
					
					this.delivery_charge=Number(this.delivery_charge).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.delivery_charge='-';
				}
				
				if(Number(this.othercharges_amount) > 0){
					
					this.othercharges_amount=Number(this.othercharges_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.othercharges_amount='-';
				}
				if(Number(this.declared_value) > 0){
					
					this.declared_value=Number(this.declared_value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.declared_value='-';
				}
				if(Number(this.insurance_amount) > 0){
					
					this.insurance_amount=Number(this.insurance_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.insurance_amount='-';
				}
				
				if(Number(this.withholdingttax_amount) > 0){
					
					this.withholdingttax_amount=Number(this.withholdingttax_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.withholdingttax_amount='-';
				}
				
				if(Number(this.finaltax_amount) > 0){
					
					this.finaltax_amount=Number(this.finaltax_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.finaltax_amount='-';
				}
				if(Number(this.vat_amount) > 0){
					
					this.vat_amount=Number(this.vat_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
					
				}else{
					this.vat_amount='-';
				}
				
				$("#tbl_waybill_bill").append('<tr>'+
				'<td align="right">'+this.freight_amount+'</td>'+
				'<td align="right">'+this.discount_amount+'</td>'+
				'<td align="right">'+subtotal+'</td>'+
				'<td align="right">'+this.pickup_charge+'</td>'+
				'<td align="right">'+this.delivery_charge+'</td>'+
				'<td align="right">'+this.othercharges_amount+'</td>'+
				'<td align="right">'+this.declared_value+'</td>'+
				'<td align="right">'+this.insurance_amount+'</td>'+
				'<td align="right">'+this.withholdingttax_amount+'</td>'+
				'<td align="right">'+this.finaltax_amount+'</td>'+
				'<td align="right">'+this.vat_amount+'</td>'+
				'<td align="right">'+total+'</td>'+
				'</tr>');
                

            });
            $(".waybill_details_modal_loading").hide();
            
    }}); 
}

</script>