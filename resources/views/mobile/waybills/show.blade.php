@extends('layouts.mobile')

@section('content')
<div class="page">
  <div class="navbar">
      <div class="navbar-bg"></div>
      <div class="navbar-inner sliding">
        <div class="left">
          <a href="{{URL::previous()}}" class="link back external">
            <i class="icon icon-back"></i>
            <span class="if-not-md">Back</span>
          </a>
        </div>
        <div class="title">REF # : {{$data->reference_no}}</div>
        <div class="right">
          @if(Auth::check())
          <a href="{{route('waybills.edit',['id'=>$data->reference_no])}}" class="link external">
            <i class="icon f7-icons">pencil</i>
             Edit
          </a>
          @else
          <a href="{{url('/create-booking')}}" class="link external">
            <i class="icon f7-icons">cart_badge_plus</i>
            &nbsp;New Booking
          </a>
          <a href="{{url('/')}}" class="link external">
            <i class="icon f7-icons">house</i>
            &nbsp;Home
          </a>
          @endif

        </div>
      </div>
  </div>

  
  
  <div class="toolbar toolbar-bottom tabbar">
    <div class="toolbar-inner">
      <a href="#tab-1" class="tab-link tab-link-active">QRCode</a>
      <a href="#tab-2" class="tab-link">Details</a>
    </div>
  </div>

  <div class="tabs">
    <div id="tab-1" class="page-content tab tab-active">
      <div class="block">
          <br><br>
          <center>
          <div id="qrcode"></div>
          <h2>{{$data->reference_no}}</h2>
          </center>
      </div>
    </div>
    <div id="tab-2" class="page-content tab">
      <div class="block">
        <div class="block-title block-title-large">Booking Details</div>
          <div class="list no-hairlines-md">
            <ul>
              <li class="item-content item-input">
                <div class="item-inner">
                  <div class="item-title item-label">Payment Type</div>
                  <div class="item-input-wrap">
                  <b id="payment_type_value">{{$data->payment_type == 'CI' ? 'Prepaid' : 'Collect'}}</b>
                  </div>
                </div>
              </li>
            </ul>
            <div class="block-title">Shipper</div>
            <div class="card demo-facebook-card">
              <div class="card-header">
                <div class="demo-facebook-avatar"><img src="{{asset('/images/default-avatar.png')}}" width="34" height="34"/></div>
                <div class="demo-facebook-name" style="font-weight:bold" id="shipper_name_value">{{$data->shipper->fileas}}</div>
                <div class="demo-facebook-date" id="shipper_address_value">{{$data->shipper_address->full_address}}</div>
              </div>
              <div class="card-content card-content-padding">
                <p></p>
                <p class="likes">Contact # : <b id="shipper_contact_no_value">{{$data->shipper->contact_no}}</b></p>
              </div>
            </div> 
            <div class="block-title">Consignee</div>
            <div class="card demo-facebook-card">
              <div class="card-header">
                <div class="demo-facebook-avatar"><img src="{{asset('/images/default-avatar.png')}}" width="34" height="34"/></div>
                <div class="demo-facebook-name" style="font-weight:bold" id="consignee_name_value">{{$data->consignee->fileas}}</div>
                <div class="demo-facebook-date" id="consignee_address_value">{{$data->consignee_address->full_address}}</div>
              </div>
              <div class="card-content card-content-padding">
                <p></p>
                <p class="likes">Contact # : <b id="consignee_contact_no_value">{{$data->consignee->contact_no}}</b></p>
              </div>
            </div> 
          </div>
          <div class="list media-list">
            <ul>
              <li>
                <a href="#" class="item-content">
                  <div class="item-inner">
                    <div class="item-title-row">
                      <div class="item-title" style="color:black;font-size:15px;">SHIPMENT TYPE : <b id="shipment_type_value">{{$data->shipment_type}}</b></div>
                      <div class="item-after" style="color:black;font-size:15px;">Php. <b id="declared_amount_value">{{number_format($data->declared_value,2,'.',',')}}</b></div>
                    </div>
                    <div class="item-subtitle" style="color:black;font-size:15px;">
                          
                      <p>Destination : <b id="destination_value">{{$data->branch->branchoffice_description}}</b></p>
                      <p>Discount Coupon: <b id="discount_coupon_value">{{$data->discount_coupon}}</b></p>
                    </div>
                        
                      
                  </div>
                </a>
              </li>
            </ul>
          </div>

          
          <div class="card data-table data-table-collapsible data-table-init">
            <div class="card-header">
              <div class="data-table-title">Shipments</div>
            </div>
            <div class="card-content">
              <table id="table-shipments">
                <thead>
                  <tr>
                    <th class="label-cell">Item</th>
                    <th class="numeric-cell">Unit</th>
                    <th class="numeric-cell">Quantity</th>
                    <th class="label-cell">Dimension (Weight, LWH)</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data->waybill_shipment as $key=>$row)
                    <tr>
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
                            <?php
                            $multiplier = $row->weight>0 && $row->length>0 && $row->width>0 && $row->height>0 ? ',' : '';
                            $seperator = $row->weight>0 && $row->length>0 && $row->width>0 && $row->height>0 ? ',' : '';
                            ?>
                            {{$row->weight>0 ? $row->weight : ''}}
                            {{$seperator}}
                            {{$row->length>0 ? $row->length : ''}}
                            {{$multiplier}}
                            {{$row->width>0 ? $row->width : ''}}
                            {{$multiplier}}
                            {{$row->height>0 ? $row->height : ''}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        new QRCode(document.getElementById("qrcode"),"{$data->reference_no}}");
    });
</script>
@endsection
