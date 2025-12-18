<div class="modal fade" id="modal-track-and-trace" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <form id="form-tracking" class="form-horizontal">
      <div class="modal-dialog">
          <div class="modal-content">

              <div class="modal-header">

                  <h3 class="getlaid" id="trackModalLabel"><i class="ace-icon fa fa-search orange bigger-125"></i> <a href="{{url('/')}}/?track">Track your shipment</a></h3>

              </div>

              <div class="modal-body">

                    @csrf
                    <div class="form-group">
                        <input type="radio" name="search_type" value="tracking"> Tracking No &nbsp;&nbsp;&nbsp;
                        <input type="radio" name="search_type" value="waybill"> Waybill No
                    </div>
                    <div class="row">
                        <div class="col-12 tracking-div" hidden>
                            <input type="text" name="tracking_no" class="form-control" placeholder="Tracking Number">
                        </div>
                        <div class="col-7 waybill-div" hidden>
                            <input type="text" name="name" class="form-control" placeholder="Shipper/Consignee (Name/Company)">
                        </div>
                        <div class="col-5 waybill-div" hidden>
                            <input type="text" name="waybill_no" class="form-control" placeholder="Waybill Number">
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <table class="datatable table table-striped">
                                <thead>
                                    <tr>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="loading" hidden>
                                        <td>
                                        <center><h1><i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i><span class="bigger-110"><br><font size="2">Please wait..</font></span></h1></center>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn close-track-and-trace btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Track</button>
              </div>
          </div>
      </div>
      </form>
  </div>