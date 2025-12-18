
<div class="modal fade pasabox_details_file_modal" role="dialog"   aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title pasabox_details_file_modal_h4" >PASABOX UPLOADED PICTURE</h4>
			</div>
			<div class="modal-body">
			
				<table class="table table-striped table-bordered">
					<tr style="background-color:#AED6F1;">
					<td >ITEM</td>
					<td>UNIT</td>
                    <td>DECLARED QTY</td>
                    <td>RECEIVED QTY</td>
                    </tr>
                    <tr id="tbl_pasabox_file_loading">
                        <td colspan="2" align="center"><div class="spinner"><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</div></td>
                    </tr>
                    <tbody id="tbl_pasabox_file">

                    </tbody>
   
				</table>
				
				<div class="modal-footer">
				</div>
				
				
			</div>
		</div>
	</div>
</div>
<script>

function pasabox_details_file(ref_no){
    $(".pasabox_details_file_modal_h4").html('PASABOX UPLOADED PICTURE | '+ref_no);
    $("#tbl_pasabox_file").html('');
    $("#tbl_pasabox_file_loading").show();
    $.ajax({
        url: "{{url('/get-pasabox-uploaded-file')}}/"+ref_no, 
        method: 'get',
        success: function(result){  
            var result = JSON.parse(result);  
            //console.log(result);   
            $.each(result,function(){
                total_received=0;
                received='';
                data_received=this.pasabox_received_details;
                $.each(data_received,function(){
                    data_received_files=this.pasabox_received_files;
                    image_data='';
                    
                    $.each(data_received_files,function(){
                       if(image_data !=''){
                        image_data +='<br>';
                       }
                       image_data +='<img src="'+this.upload_file+'" width="300" width="300"  />';
                    });  
                    
                    if(Number(this.received_status) !=2 ){
                        total_received +=Number(this.qty_received);
                    }
                    status_txt='';
                    if(Number(this.received_status) ==1 ){
                        status_txt='GOOD';
                    }
                    else if(Number(this.received_status) ==2 ){
                        status_txt='MISSING';
                    }
                    else if(Number(this.received_status) ==3 ){
                        status_txt='PILFERED';
                    }
                    else if(Number(this.received_status) ==4 ){
                        status_txt='DAMAGED';
                    }

                    received +='<tr>'+
                       
                        '<td><li class="fa fa-chevron-right" style="color:black;"></li> '+this.qty_received+' '+status_txt+'</td>'+
                        '<td align="center">'+image_data+'</td>'+
                        '</tr>';
                });   
                $("#tbl_pasabox_file").append('<tr>'+
                    '<td>'+this.item_description+'</td>'+
                    '<td>'+this.unit_description+'</td>'+
                    '<td>'+this.quantity+'</td>'+
                    '<td>'+total_received+'</td>'+
                    '</tr>'+
                    '<tr><td colspan="4">'+
                        '<table class="table table-striped">'+
                            received+ 
                        '</table>'+
                    '</td></tr>');
            });
            $("#tbl_pasabox_file_loading").hide();
            
    }}); 
}
</script>