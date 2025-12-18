<form id="form-step-3" class="form-horizontal">
    <div class="clearfix">
        <div class="pull-right tableTools-container"></div>
    </div>
    <div class="row"><div class="pull-right">
        <button type="button" class="btn btn-success add-item"><i class="menu-icon fa fa-plus"></i> Add Item</button></div></div>
    <div class="space-10"></div>
    <div class="form-group">
        <table id="datatable" width="100%" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="40%">Description</th>
                    <th width="30%">Unit</th>
                    <th width="20%">Quantity</th>
                    <th width="10%"></th>
                </tr>
            </thead>

            <tbody>
                @if(Route::currentRouteName()=='waybills.edit')
                    @foreach($data->waybill_shipment as $row)
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <center>
                                                    <div class="portion-name">
                                                        {{$row->item_description}}<br>
                                                        <a href="#" style="font-size:10px" data-toggle="modal" data-target="#modal-item" class="select-itemdesc">--Change Description--</a>
                                                    </div>
                                                </center>
                                                <input type="hidden" name="item_code[]" class="form-control description" value="{{$row->item_code}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>

                                <div>
                                    <select name="unit_code[]" class="form-control unit select2">
                                        @foreach($units as $unit)
                                            <option value="{{$unit->unit_no}}" {{$row->unit_no==$unit->unit_no ? 'selected' : ''}}>{{$unit->unit_description}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </td>
                            <td>
                                <div>
                                    <input type="number" class="form-control quantity" name="quantity[]" value="{{$row->quantity}}">
                                </div>
                            </td>
                            {{-- <td>

                            </td> --}}
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</form>

<div class="modal fade" id="modal-item" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-file-text bigger-130"></i> Please select item description</h4>
            </div>
            <div class="modal-body">
                <form id="form-search-item">
                    <div class="col-12">
                        <div class="input-group">
                            <input type="hidden" id="parentTableIndex">
                            <input class="form-control" type="text" name="item_description" placeholder="Enter item description" autofocus="true"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-success verify" type="button">
                                    <i class="ace-icon fa fa-search bigger-110"></i>
                                    Search
                                </button>
                            </span>
                        </div>
                    </div>
                </form>
                <div style="height:400px;overflow:scroll;">
                    <table id="table-items" width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Item description</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-unit" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <i class="ace-icon fa fa-file-text bigger-130"></i> Please select unit description</h4>
            </div>
            <div class="modal-body">
                <div style="height:400px;overflow:scroll;">
                    <table id="table-units" width="100%" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Unit description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $unit)
                                <tr id="{{$unit->unit_no}}">
                                    <td>{{$unit->unit_description}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

