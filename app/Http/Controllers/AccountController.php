<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FAQ\Category;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\RequestLinkAccount;
use App\Waybill\Waybill;
use App\Waybill\ORCRDetails;
use App\Waybill\Adjustment;
use App\Waybill\PODTransmittal;
use App\Http\Resources\SoaResource;
use App\Http\Resources\TrackAndTraceResource;
use App\RecordLogs\TrackAndTrace;
use App\Http\Resources\PODResource;
use App\User;
use Auth;
use DB;
use Storage;
class AccountController extends Controller
{

	public function __construct(){
		$this->middleware('auth');
	}

	public function request_link_account(){
        $requestLinkAccount = RequestLinkAccount::firstOrNew(['contact_id'=>Auth::user()->contact_id]);
        if(!$requestLinkAccount->exists){
            $requestLinkAccount->save();
            return response()->json(['title'=>'Success!','message'=>'Request link has been sent','type'=>'success'],200);
        }else{
            return response()->json(['title'=>'Ooops!','message'=>'You already sent request','type'=>'error'],200);
        }
    }

    public function upload_photo(Request $request){
        $base64_str = substr($request->image,strpos($request->image,",")+1);
        $image = base64_decode($base64_str);
        Storage::disk('local')->put('app/clients/'.Auth::user()->id.'.png',$image);
        User::where('id',Auth::user()->id)->update(['avatar'=>Auth::user()->id.'.png']);
    }

    public function doff_transaction(){
        // return view('waybills.soa_list');
        return view('doff_transaction');  
    }

    public function doff_transaction_data(Request $request){
        
        
        $data = Waybill::where('chargeto_id',Auth::user()->contact->doff_account_data->contact_id)
                       //->where('soa_customer_post',1)
                       ->where('waybill_status','<>','cancel')
                       ->whereRaw("( (LEFT(transactioncode,2)='AR' AND soa_from IS NOT NULL) OR LEFT(transactioncode,2)='CH')")
                       ->whereDoesntHave('soa_detail')
                       ->with([
                        // 'soa_detail'=>function($query){
                        //     $query->whereNull('waybill_no');
                        // },
                        'orcr_detail',
                        'adjustment_less'=>function($query) { 
                                $query->with([
                                    'orcr_detail'=>function($query) { 
                                        $query->where('validated',1); 
                                    } 
                                ]); 
                        },
                       'adjustment_add'=>function($query) { 
                           $query->with([
                               'orcr_detail'=>function($query) { 
                                    $query->where('validated',1); 
                                } 
                            ]); 
                        }
                        ])
                        // ->select([DB::raw()]) 
                       //->orderBy('tblwaybills.prepared_datetime','ASC')
                       ->orderBy('tblwaybills.transactiondate','DESC')
                       ->groupBy('tblwaybills.transactioncode')
                       ->get();
        
        return SoaResource::collection($data);

        // $data = Waybill::where('soa_customer_post',1)
        //                 ->where('chargeto_id',Auth::user()->contact->doff_account_data->contact_id)
        //                 ->where('waybill_status','<>','cancel')
        //                 ->with([
        //                 'soa_detail'=>function($query){ $query->whereNull('waybill_no'); },
        //                 'charge_account',
        //                 'orcr_detail',
        //                 'adjustment_less'=>function($query) { $query->with(['orcr_detail'=>function($query) { $query->where('validated',1); } ]); },
        //                 'adjustment_add'=>function($query) { $query->with(['orcr_detail'=>function($query) { $query->where('validated',0); } ]); }
        //                 ])
        //                 ->leftJoin('tblorcrdetails','tblwaybills.transactioncode','tblorcrdetails.transactioncode')
        //                 ->leftJoin('tbladjustment','tblwaybills.transactioncode','tbladjustment.transactioncode')
        //                 ->orderBy('tblwaybills.prepared_datetime','ASC')
        //                 ->groupBy('tblwaybills.transactioncode')
        //                 ->get([
        //                     'soa_duedate',
        //                     'soa_from',
        //                     'soa_to',
        //                     'waybill_status',
        //                     'chargeto_id',
        //                     'freight_amount',
        //                     'shipper_id',
        //                     'consignee_id',
        //                     'tblwaybills.transactioncode',
        //                     'tblwaybills.transactiondate',
        //                     'amount_due',
        //                     'vat_amount',
        //                     'sourcebranch_id',
        //                     'destinationbranch_id',
        //                     'pickup_charge',
        //                     'delivery_charge',
        //                     'insurance_amount',
        //                     'withholdingttax_amount',
        //                     'finaltax_amount',
        //                     'othercharges_amount',
        //                     DB::raw("RIGHT(tblwaybills.transactioncode,2) as r_tcode"),
        //                     DB::raw("(SUM(tblorcrdetails.withdraw)+IFNULL((SELECT SUM(adjustment_amount) AS adjustment_less FROM tbladjustment WHERE add_less=1 AND tbladjustment.transactioncode=tblwaybills.transactioncode),0))-(SUM(tblorcrdetails.deposit)+IFNULL((SELECT SUM(adjustment_amount) AS adjustment_less FROM tbladjustment WHERE add_less=0 AND tbladjustment.transactioncode=tblwaybills.transactioncode),0)) AS balance_amount")
        //                 ]);
        
        
    }

    public function track_and_trace_data(Request $request){
        
        //$cond=$request->tt_status==1 ? 'ACCEPTED' : ( $request->tt_status==2 ? 'RECEIVED' : ($request->tt_status==3 ? 'CLAIMED' : '' ) );

        $id = Auth::user()->contact->doff_account_data->contact_id;

        $data = Waybill::whereRaw("LEFT(tblwaybills.transactioncode,2)!='AR'")
        
        ->where(function($query) use($id){
            $query->where('shipper_id',$id)->orWhere('consignee_id',$id)->orWhere('chargeto_id',$id);
        })
        ->where(function($query) use($request){
            
            if($request->has('tt_month')){
                
                $query->where('transactiondate','LIKE',$request->tt_month.'%');

            }else{
                $query->where('waybill_no','LIKE',strtoupper($request->tt_no).'%')
                ->orWhere('tracking_no','LIKE',strtoupper($request->tt_no).'%')
                ->orWhere('tblwaybills.transactioncode','LIKE',strtoupper($request->tt_no).'%')
                ->orWhereHas('waybill_reference',function($query) use($request){
                    $query->where('reference_no','LIKE',strtoupper($request->tt_no).'%');
                });
            }
           
        })
       
        
        // ->leftJoin('recordlogs.tbltrack_trace as tt', function ($query) {
        //         $query->on('tt.transactioncode', '=', 'tblwaybills.transactioncode')
        //         ->where('tt.remarks', 'LIKE', 'CLAIMED%');
        //         ;
        // })
        
        ->with(['track_trace'=>function($query){ 
            $query->orderBy('track_trace_date','DESC'); },
        'consignee',
        'waybill_reference'=>function($query){ $query->with('specialrate_reference_attachment'); }])

        
        ->get();
        
        return TrackAndTraceResource::collection($data);
    }

    public function pod_data(Request $request){
        
        $id = Auth::user()->contact->doff_account_data->contact_id;

        $data = PODTransmittal::where('pod_date','LIKE',$request->tt_month.'%')->
        whereNotNull('tracking_no')->
        where('customer_id',$id)
        ->with([
            'pod_transmittal_upload',
            'pod_transmittal_details'=>function($query) { 
                $query->with([
                    'waybill_reference_attachment'=>function($query){
                        $query->with([
                        'specialrate_reference_attachment',
                        'waybill'=>function($query){
                            $query->with('consignee');
                        }
                        ]);  
                    }
                ]);   
            }
        ])
        ->get();
        
        return PODResource::collection($data);
    }

}
