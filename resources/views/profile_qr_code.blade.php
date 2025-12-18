<center>

<input type="hidden" id="qr-data" value="{{ Auth::user()->contact_id }}" />

@if (  Auth::user()->contact->use_company==0 && Auth::user()->contact->mname=='' )
<h5><font color="red">*Note: Please update your middle name to generate QR.</font></h5>
<a href="{{url('/account')}}">
    
    Click here to update profile
  </a>    
@endif

<div {{  Auth::user()->contact->use_company==0 && Auth::user()->contact->mname=='' ? 'hidden' : '' }}>
    <div id="qr_code_profile" ></div>
   <p><b> <i class="fa fa-lightbulb-o"></i> NOTE: <i>This permanent QR Code is especially assigned to your account profile containing your DOFF ID number, name and addresses. If you wish to share your personal information with others pls select the details you want to share and generate QR Code below.</i></b></p>
    <a id="download_qr_profile" download="doff_qrcode.png" type="button"  class="btn btn-success"><i class="fa fa-download"></i> DOWNLOAD</a>

    


</div>
</center>

<div class="col-xs-12" {{  Auth::user()->contact->use_company==0 && Auth::user()->contact->mname=='' ? 'hidden' : '' }} >
    
    <div class="col-xs-12">
        <h4><i class="green ace-icon fa fa-plus-circle bigger-120"></i> Add QR Code</h4>
        <div class="alert alert-danger alert-dismissible " role="alert" style="text-align:left;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            * Select the address you want to share and click create.
        </div>
        <form method="post" id="add_qr_profile_form">
            <table  width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%"></th>
                        <th>Address</th>     
                    </tr>
                </thead>

                <tbody>
                    @foreach ( Auth::user()->user_address as $user_address )
                        <tr>
                            <td align="center">
                                <input value="{{ $user_address->useraddress_no }}" name="qr_code_address_no[]"   type="checkbox" >
                            </td>
                          
                            <td>
                                {{ $user_address->street !='' ? strtoupper($user_address->street).' ' :'' }}
                                {{ $user_address->barangay !='' ? strtoupper($user_address->barangay).' ' :'' }}
                                {{ $user_address->city !='' ? strtoupper($user_address->city).' ' :'' }}
                                {{ $user_address->province !='' ? strtoupper($user_address->province).' ' :'' }}
                                {{ $user_address->postal_code }}
                            </td>
                            
                        </tr>
                    @endforeach
                    
                </tbody>
                <thead>
                    <tr>
                        <th colspan="3">
                            <button type="submit" name="add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Create</button>
                        </th>

                    </tr>
                </thead>
            </table>

        </form>
    </div>
    
    <div class="col-xs-12">
        <h4><i class="green ace-icon fa fa-list bigger-120"></i> List </h4>
        <div class="alert alert-danger alert-dismissible " role="alert" style="text-align:left;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            * Download the QR Code and send to your shipper/consignee. Your contact details will be saved to their address book.
            <br>* Deactivate your QR Code if you wish to disconnect their access to your contact information.
        </div>
        <table id="qr-profile-table" width="100%" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th>Address</th>                                                  
                    <th width="10%"></th>
                </tr>
            </thead>

            <tbody>
                
            </tbody>
        </table>
    </div>

    

    

</div>

<canvas id="myCanvas" width="200" height="100"
style="border:1px solid #d3d3d3;">
Your browser does not support the canvas element.
</canvas>


<script src="{{asset('/qr_code_title/easy.qrcode.min.js')}}"></script>

<script>

$("#download_qr_profile").on('click', function () {
    var link = document.getElementById("download_qr_profile");
    var image = document.getElementById("qr_code_profile").getElementsByTagName("canvas");
    var qr = image[0].toDataURL();
    link.href = qr;
    
});

function generateQR_profile() {
    var qrdata = document.getElementById("qr-data");
    var qrcode = new QRCode(document.getElementById("qr_code_profile"),{
        text: qrdata.value,
        width: 220,
        height: 220,

        title: 'DAILY OVERLAND',
        titleFont: "normal normal bold 18px Arial",
        titleColor: "#fff",
        titleBackgroundColor: "#3074B4",
        titleHeight: 70,
        titleTop: 25,

        //subTitle: qrdata.value+'{{ ucwords(strtolower(Auth::user()->name)) }}', 
        subTitle: qrdata.value, 
        subTitleFont: "bold 16px Arial", 
        subTitleColor: "black", 
        subTitleTop: 50, 

    });

   

    
   
    
    var data = qrdata.value;
    qrcode.makeCode(data);

    var image = document.getElementById("qr_code_profile").getElementsByTagName("canvas");
    var canvas = image[0];
    var ctx = canvas.getContext('2d'); 

    var text = "{{ ucwords(strtolower(Auth::user()->name)) }}";
    ctx.font = "bold 12px Arial";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText(text, canvas.width/2, 65);


}
generateQR_profile();

$('#add_qr_profile_form').on('submit',function(e){
    e.preventDefault();

   // let qr_code_address_no = document.getElementsByName('qr_code_address_no[]').value;
    var qr_code_address_no = [];

    $('#add_qr_profile_form input[name="qr_code_address_no[]"]').each(function(){
       
        if(this.checked){
            qr_code_address_no.push(this.value);
        }
    });
    
    if( qr_code_address_no.length > 0){
        $.ajax({
            url: "/create-qr-profile",
            type:"POST",
            data:{
            "_token": "{{ csrf_token() }}",
            qr_code_address_no:qr_code_address_no,
    
            },
            success:function(result){
                swal(result.message, {
                    icon: result.type,
                    title: result.title
                })
                .then(function(){
                   reset_add_qr_profile_form();
                   list_qr_profile();
                });
            },
            error: function(result) {
                // $('#qr_code_address_no[]').text(response.responseJSON.errors.name);
            
            }
        });
    }else{
        alert('Please select at least one address.');
    }
});

function reset_add_qr_profile_form(){
    $('#add_qr_profile_form input[name="qr_code_address_no[]"]').each(function(){
        this.checked=false;
   });
}

list_qr_profile();
function list_qr_profile(){
    $('#qr-profile-table').DataTable().clear().destroy();
    $.ajax({
        url: "{{url('/get-qr-code-profile-list')}}", 
        method: 'get',
        success: function(result){
            
            var result = JSON.parse(result);
           
            $.each(result,function(){

                id=this.qrcode_profile_id;
                qr_code_details=this.qr_code_details;
                
                
                
                address_text='';
                $.each(qr_code_details,function(){
                    address=this.qr_code_profile_address;
                    if(address_text !=''){
                        address_text +='<br>';
                    }
                    address_text +='* ';
                    if(address['street'] !='' && address['street'] !=null ){
                        address_text +=address['street'].toUpperCase()+' ';
                    }
                    if(address['barangay'] !='' && address['barangay'] !=null ){
                        address_text +=address['barangay'].toUpperCase()+' ';
                    }
                    if(address['city'] !='' && address['city'] !=null ){
                        address_text +=address['city'].toUpperCase()+' ';
                    }
                    if(address['province'] !='' && address['province'] !=null ){
                        address_text +=address['province'].toUpperCase()+' ';
                    }
                    if(address['postal_code'] !='' && address['postal_code'] !=null ){
                        address_text +=address['postal_code'].toUpperCase();
                    }
                   
                }); 
                $('#qr-profile-table tbody').append('<tr>'+
                    '<td width="5%"><div  id="qr_code_profile_'+id+'" ></div></td>'+
                    '<td>'+address_text+'</td>'+
                    '<td width="5%">'+
                        '<a onclick="setUpDownload(\''+id+'\')" id="download_qr_profile_'+id+'" download="doff_qrcode.png"><button style="width:100%;"  type="button"  class="btn btn-success btn-sm"><i class="fa fa-download"></i> DOWNLOAD</button></a>'+
                        '<button style="width:100%;" type="button" onclick="deactivate_profile_qr(\''+id+'\')" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> DEACTIVATE</button>'+
                    '</td>'+
                    '</tr>');

                generateQR(id);
            });
            $("#qr-profile-table").dataTable();

    }});
}

function generateQR(id) {

    var qrcode = new QRCode(document.getElementById("qr_code_profile_"+id),{
        
        text: id,
        width: 220,
        height: 220,
        // title: id,
        // titleFont: "normal normal bold 18px Arial",
        // titleColor: "#fff",
        // titleBackgroundColor: "#3074B4",
        // titleHeight: 40,
        // titleTop: 25,
        // colorDark: "#000000",

        title: 'DAILY OVERLAND',
        titleFont: "normal normal bold 18px Arial",
        titleColor: "#fff",
        titleBackgroundColor: "#3074B4",
        titleHeight: 70,
        titleTop: 25,

        //subTitle: id+'{{ ucwords(strtolower(Auth::user()->name)) }}', 
        subTitle: id, 
        subTitleFont: "bold 16px Arial", 
        subTitleColor: "black", 
        subTitleTop: 50,
    
    });
    var data = id;
    qrcode.makeCode(data);

    var image = document.getElementById("qr_code_profile_"+id).getElementsByTagName("canvas");
    var canvas = image[0];
    var ctx = canvas.getContext('2d'); 

    var text = "{{ ucwords(strtolower(Auth::user()->name)) }}";
    ctx.font = "bold 12px Arial";
    ctx.fillStyle = "black";
    ctx.textAlign = "center";
    ctx.fillText(text, canvas.width/2, 65);

}

function setUpDownload(id) {

    var link = document.getElementById("download_qr_profile_"+id);
    var image = document.getElementById("qr_code_profile_"+id).getElementsByTagName("canvas");
    var qr = image[0].toDataURL(); 
    link.href = qr;

}
function deactivate_profile_qr(id){

    if(confirm('Are you sure you want to deactivate this?')){
        
        $.ajax({
            url: "{{url('/deactivate-qr-code-profile')}}/"+id, 
            method: 'get',
            success: function(result){
                swal(result.message, {
                    icon: result.type,
                    title: result.title
                })
                .then(function(){
                    list_qr_profile();
                });
        }});
    }
}
</script>


