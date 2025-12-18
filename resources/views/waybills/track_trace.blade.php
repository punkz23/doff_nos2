
<div class="modal fade pasabox_track_trace" role="dialog"   aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title pasabox_track_trace_h4" >TRACK AND TRACE</h4>
			</div>
			<div class="modal-body">
                <div id="tbl_track_trace_loading" class="spinner"><center><i class="ace-icon fa fa-spinner fa-spin blue bigger-150"></i> Please wait ..</center></div>

				<table width="100%">

                    <tbody id="tbl_track_trace">

                    </tbody>



				</table>

				<div class="modal-footer">
				</div>


			</div>
		</div>
	</div>
</div>
<script>
function formatAMPM(date) { // This is to display 12 hour format like you asked
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}
function track_trace_func(ref_no){
    $(".pasabox_track_trace_h4").html('TRACK AND TRACE | '+ref_no);
    $("#tbl_track_trace").html('');
    $("#tbl_track_trace_loading").show();
    $.ajax({
        url: "{{url('/get-booking-track-trace')}}/"+ref_no,
        method: 'get',
        success: function(result){
            var result = JSON.parse(result);
            tt_list='';
            $.each(result,function(){
                    //tdate=new Date(this.trackandtrace_datetime);
                    tdate=new Date(this.track_trace_date);

                    tdate=tdate.getMonth()+ '/' +tdate.getDate()+ '/' +tdate.getFullYear()+ ' ' +formatAMPM(tdate);

                    // $("#tbl_track_trace").append('<tr>'+
                    // '<td>'+tdate+'</td>'+
                    // //'<td>'+this.trackandtrace_status+'</td>'+
                    // '<td>'+this.remarks+'</td>'+
                    // '</tr>');
                header_txt=this.ol_track_trace_details;



                txt_append='';
                if(header_txt !=null){
                    if(
                        header_txt['obr_details_id'] ==3
                        || header_txt['obr_details_id'] ==4
                        || header_txt['obr_details_id'] ==5
                        || header_txt['obr_details_id'] ==6
                        || header_txt['obr_details_id'] ==7
                        || header_txt['obr_details_id'] ==8

                    ){
                        txt_append='<br><a  onclick="pasabox_details_file(\''+ref_no+'\')"  data-toggle="modal" data-target=".pasabox_details_file_modal"   title="Uploaded Pictures"><i><u>Click here to view uploaded pictures</i></u></a>';
                    }
                    header_txt=header_txt['ol_track_trace_header'];
                    header_txt=header_txt['obr_header'];
                }else{
                    header_txt='';
                }

                if(tt_list==''){
                    tt_list +='<tr>'+
                    '<td style="color:black;" width="15%">'+tdate+'</td>'+
                    '<td style="vertical-align: top;"><div style="position:absolute;height: 10px;width: 10px;border-radius: 50%;background: blue;"></div></td>'+
                    '<td style="border-left: 1px solid #ABB2B9;padding-left: 20px;"><font color="blue"><b>'+header_txt+'</b></font><br><font color="black"><small>'+(this.remarks).toLowerCase()+txt_append+'</small></font><br><br></td>'+
                    '</tr>';
                }else{
                    tt_list +='<tr>'+
                    '<td style="color:#ABB2B9;" width="15%">'+tdate+'</td>'+
                    '<td style="vertical-align: top;"><div style="position:absolute;height: 10px;width: 10px;border-radius: 50%;background: #ABB2B9;"></div></td>'+
                    '<td style="border-left: 1px solid #ABB2B9;padding-left: 20px;"><font color="#ABB2B9"><b>'+header_txt+'</b></font><br><font color="#ABB2B9"><small>'+(this.remarks).toLowerCase()+txt_append+'</small></font><br><br></td>'+
                    '</tr>';
                }

            });
            $("#tbl_track_trace").append(tt_list);
            $("#tbl_track_trace_loading").hide();

    }});
}
</script>
