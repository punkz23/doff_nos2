<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="space-6"></div>

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="widget-box transparent">
                    <div class="widget-header widget-header-large">
                        <h3 class="widget-title grey lighter">
                            <!-- <i class="ace-icon fa fa-leaf green"></i> -->
                            <img src="{{asset('/images/ICON.png')}}" width="35px" height="35px" alt="logo">
                            DOFF Online
                        </h3>

                        <div class="widget-toolbar no-border invoice-info">
                            <span class="invoice-info-label">Reference No:</span>
                            <font size="3" style="font-weight:bold;color:#2C6AA0;">{{$data->reference_no}}</font>

                            <br />
                            <span class="invoice-info-label">Date:</span>
                            <span class="blue">{{date('m/d/Y',strtotime($data->transactiondate))}}</span>
                        </div>

                        <div class="widget-toolbar hidden-480">
                            @if(Route::currentRouteName()=='waybills.show')
                            <a href="{{url('/waybills/create')}}" title="Create new booking">
                                <i class="green ace-icon fa fa-cart-plus"></i>
                            </a>
                            <a href="{{url('/waybills/printable-reference/'.$encrypt_reference_no)}}" title="Print">
                                <i class="ace-icon fa fa-print"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main padding-24">
                            @if($data->chargeto_id!=null)
                            <div class="row">
                                <h4>CHARGE TO : <b>{{$data->charge_to_data->fileas}}</b></h4>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-info arrowed-in arrowed-right">
                                            <b>Shipper Info</b>
                                        </div>
                                    </div>

                                    <div>
                                        <ul class="list-unstyled spaced">
                                            <li>
                                                <i class="ace-icon fa fa-user blue"></i>{{strtoupper($data->shipper->fileas)}}
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-map-pin blue"></i>{{$data->shipper_address!=null ? $data->shipper_address->full_address : ''}}
                                            </li>



                                            <li>
                                                <i class="ace-icon fa fa-mobile blue"></i>
                                                Contact No:
                                                <b class="red">{{$data->shipper->contact_no}}</b>
                                            </li>


                                        </ul>
                                    </div>
                                </div><!-- /.col -->

                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-11 label label-lg label-success arrowed-in arrowed-right">
                                            <b>Consignee Info</b>
                                        </div>
                                    </div>

                                    <div>
                                        <ul class="list-unstyled spaced">
                                            <li>
                                                <i class="ace-icon fa fa-user green"></i>{{strtoupper($data->consignee->fileas)}}
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-map-pin green"></i>{{$data->consignee_address!=null ? $data->consignee_address->full_address : ''}}
                                            </li>



                                            <li>
                                                <i class="ace-icon fa fa-mobile green"></i>
                                                Contact No:
                                                <b class="red">{{$data->consignee->contact_no}}</b>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- /.col -->


                            </div><!-- /.row -->

                            <div class="space"></div>

                            <div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th class="hidden-480">Item Description</th>
                                            <th class="hidden-480">Unit</th>
                                            <th class="hidden-480">Qty</th>
                                            <th class="hidden-480">Weight</th>
                                            <th class="hidden-480">Length</th>
                                            <th class="hidden-480">Width</th>
                                            <th class="hidden-480">Height</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($data->waybill_shipment as $key=>$row)
                                        <tr>
                                            <td class="center">{{($key+1)}}</td>

                                            <td class="hidden-480">
                                                {{$row->item_description}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->unit_description}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->quantity}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->weight}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->length}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->width}}
                                            </td>
                                            <td class="hidden-480">
                                                {{$row->height}}
                                            </td>
                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>

                            <div class="hr hr8 hr-double hr-dotted"></div>

                            <div class="row">
                                <div class="col-sm-5 pull-right">
                                    <h4 class="pull-right">
                                        <!-- Total amount :
                                        <span class="red">$395</span> -->
                                    </h4>
                                </div>

                                <div class="col-sm-7 pull-left">
                                    Other Details
                                    <ul class="list-unstyled spaced">
                                            <li>
                                                <i class="ace-icon fa fa-map-pin"></i>
                                                Destination :{{strtoupper($data->branch->branchoffice_description)}} BRANCH
                                            </li>

                                            <li>
                                                <i class="ace-icon fa fa-legal"></i>
                                                Declared value : {{$data->shipment_type}}
                                            </li>



                                            <li>
                                                <i class="ace-icon fa fa-money"></i>
                                                Declared Amount:
                                                <b class="red">{{number_format($data->declared_value,2,'.',',')}}</b>
                                            </li>


                                            
                                        </ul>
                                </div>
                            </div>

                            <div class="space-6"></div>
                            <div class="well">
                                    Thank you for your continued patronage!
                            </div>

                            <div class="well">
                                <h4>Terms and Conditions</h4>
                                @if($term!=null)
                                    {!! $term->content !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
