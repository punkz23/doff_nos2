<?php

use Illuminate\Database\Seeder;
use App\OnlineSite\Waybill;
use App\Waybill\Waybill as RefWaybill;
use Carbon\Carbon;
class UpdateBookingStatusOnlineWebsiteWaybill extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $from='2019-06-01';$to = '2020-09-29';
        $waybill_db_waybill_ref_nos = RefWaybill::whereBetween('transactiondate',[$from,$to])->where(function($query){ $query->whereRaw("reference_no != '' && LEFT(reference_no,2) ='OL'"); })->pluck('reference_no');
        $website_waybill = Waybill::whereIn('reference_no',$waybill_db_waybill_ref_nos)->update(['booking_status'=>1]);
    }
}
