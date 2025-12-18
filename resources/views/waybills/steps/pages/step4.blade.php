<form class="form-horizontal" id="form-step-4">
    <div class="row">

        <div class="col-sm-6 col-xs-12">

            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Transaction Type:</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select name="payment_type" class="form-control select2">
                            <option value="">--Select payment type--</option>
                            <option value="CI">Prepaid</option>
                            <option value="CD">Collect</option>
                        </select>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-sm-6 col-xs-12">
            
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Destination:</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select name="destinationbranch_id" class="form-control select2">
                            <option value="">--Select destination--</option>
                            @foreach($branches as $row)
                                <option value="{{$row->branchoffice_no}}">{{$row->branchoffice_description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>             
            </div>

        </div>
    </div>



    <div class="space-2"></div>

    <div class="row">
        
        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Shipment Type:</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <select name="shipment_type" class="form-control select2">
                            <option value="">--Select shipment type--</option>
                            <option>BREAKABLE</option>
                            <option>PERISHABLE</option>
                            <option>LETTER</option>
                            <option>OTHERS</option>
                        </select>
                    </div>
                </div>
                            
            </div>
        </div>


        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-xs-12 col-sm-3 no-padding-right" for="email">Declared Amount:</label>

                <div class="col-xs-12 col-sm-9">
                    <div class="clearfix">
                        <input type="number" class="form-control" name="declared_amount" value="0" disabled>
                        <input type="hidden" name="declared_value">
                    </div>
                </div>

            </div>
        </div>
    
    </div>

                
    <!-- <div class="col-12">
        
        <label for="form-field-mask-1">
            <h3>Discount Coupon</h3>                
        </label>

        <div class="input-group">
            <input class="form-control" type="text" name="discount_coupon" placeholder="Discount Coupon (Optional)" id="discount_coupon" />
                <span class="input-group-btn">
                    <button class="btn btn-sm btn-success verify" type="button">
                        <i class="ace-icon fa fa-search bigger-110"></i>
                        Go!
                    </button>
                </span>
            </div>
        </div> -->
                
              
        <div class="hr hr-24"></div>

        <h1>Shipments</h1>

        <div class="space-2"></div>

        <div class="clearfix">
            <div class="pull-right tableTools-container"></div>
        </div>

        <div class="row">
            <table id="dynamic-table" width="100%" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                <tr>
                        <td>
                            <center><label class="pos-rel"><input type="checkbox" class="ace" value="'+counter+'"/><span class="lbl"></span></label></center>
                        </td>
                        <td>
                            <select class="select2 form-control description" style="width:100%;" required>
                            <option value="">--Select description--</option>
                            {!! $ddStocks !!}
                            </select>
                        </td>
                        <td>
                            <select class="select2 form-control unit" style="width:100%;" >
                                <option value="">--Select unit--</option>
                                {!! $ddUnits !!}
                            </select>
                        </td>
                        <td><input type="number" class="form-control quantity"  style="width:100%;" value="{{$row->quantity}}" required></td>
                        <td>
                            
                        </td>
                        </tr>
                </tbody>
			
        </table>
    </div>				
</form>