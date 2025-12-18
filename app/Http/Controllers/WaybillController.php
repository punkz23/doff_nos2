<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\OnlineSite\Waybill;
use App\OnlineSite\WaybillShipment;
use App\OnlineSite\WaybillShipmentMultiple;
use App\OnlineSite\Contact;
use App\OnlineSite\ContactNumber;
use App\OnlineSite\WaybillContact;

use App\OnlineSite\Stock;
use App\OnlineSite\Unit;
use App\Waybill\Stock as WStock;
use App\Waybill\Unit as WUnit;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\OnlineSite\UserAddress;

use App\Waybill\Sector;
use App\Waybill\RebatePointFactor;

use App\Waybill\DiscountCoupon;
use App\Waybill\Waybill as RefWaybill;
use App\Waybill\Dimension;
use App\Waybill\TrackAndTrace as WaybillTrackAndTrace;
use App\Waybill\UnclaimedCallLogs;

use App\Quotation\RequestQuotation;
use App\Quotation\RequestQuotationDetail;
use App\Quotation\RequestQuotationDetailDimension;
use App\Quotation\UnitConversion;

use App\Newsletter\NewsletterSubscriber;
use App\RecordLogs\TrackAndTrace;
use App\RecordLogs\RecordLogs;
use App\Http\Controllers\ReferenceTrait;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\OnlineSite\ShipperConsignee;
use App\DOFFConfiguration\Branch;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingAsGuestRequest;

use App\Http\Resources\WaybillResource;

use App\Waybill\OnlinePayment;
use App\Waybill\OnlinePaymentConfirmation;
use App\Waybill\ORCRDetails;
use App\Waybill\AdvancePayment;
use App\Waybill\PasaboxReceive;

use App\User;
use App\Term;
use App\ContactPerson;

use App\Http\Requests\QuotationRequest;

use View;
use Validator;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use App\Rules\CheckDiscountCoupon;

use Auth;
use Mail;
use Storage;
use App\Mail\WaybillMail;

use App\Traits\ErrorLog;

use Jenssegers\Agent\Agent;
use Barryvdh\DomPDF\Facade\Pdf as BPDF;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelPublicationTemplate as ExportExcelPublicationTemplate;

//ini_set('max_execution_time', 300);

class WaybillController extends Controller
{
    use ReferenceTrait,ErrorLog;

    private $title;
    private $message;
    private $type;
    private $return_data;
    private $errors=null;
    private $shipments;
    private $shipments_multiple;
    private $result;
    private $created = 0;
    private $reference_no=null;

    public function __construct(){

        date_default_timezone_set('Asia/Manila');

        $this->middleware(['auth'])->except(['pca_save_application','get_pc_cities','get_pc_brgy','pca_application','pc_activate_account','pca_access','set_doff_pwd','doff_set_pwd','track_and_trace','create_as_guest','create_as_guest_post','print','request_qoutation_as_guest','request_quotation_as_guest_post','generatePDF','other_link','verify','sendPDF','verify_discount_coupon','check_discount_coupon']);

        View::share([
                'business_types'=>BusinessType::with('business_type_category')->get(),
                'provinces'=>Province::get(),
                'cities'=>City::with(['province'])->orderBy('cities_name','ASC')->get(),
                'branches'=>Branch::where('waybilling_destination',1)->orderBy('branchoffice_description','ASC')->get()
              ]);
    }

    public function hasDOFFAccount(){
        return Auth::user()->contact->doff_account!=null ? true : false;
    }

    public function hasChargeAccount(){
        return Auth::user()->contact->doff_account_data!=null ? true : false;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get_pendings(){
        $now=date('Y-m-d',strtotime(Carbon::now()));

        $data = Waybill::where('prepared_by',auth()->user()->contact_id)
        ->whereIn('booking_status',[0,2])
        ->where('is_guest',0)
        ->where(function($query) use($now){
            $query->whereRaw("DATEDIFF('$now',transactiondate)<=7 || booking_status!=0");
        })
       ->with([
            'pasabox_cf_adv_payment',
            'pasabox_cf'=>function($query){
               $query->with('online_payment');
            },
            'pasabox_received','track_trace_recent','shipper','shipper_address','consignee','consignee_address','shipping_company','shipping_address'])
            ->orderBy('transactiondate','DESC')->get();
            return WaybillResource::collection($data);
    }

    public function get_transacted($fmonth){
       $now=date('Y-m-d',strtotime(Carbon::now()));
       $data = Waybill::where('prepared_by',auth()->user()->contact_id)
       ->whereRaw("LEFT(transactiondate,7) = '".$fmonth."'")
       //->whereIn('booking_status',[1,3])
       //->whereRaw('( booking_status=1 OR ( booking_status=3 AND DATE_ADD(transactiondate, INTERVAL 3 DAY) >= CURRENT_DATE ) )')
       ->where('booking_status',1)
       ->where('is_guest',0)
       ->with(['pasabox_received','track_trace_recent','shipper','shipper_address','consignee','consignee_address','waybill','shipping_company','shipping_address'])
       ->orderBy('transactiondate','DESC')
       ->get();
        return WaybillResource::collection($data);
    }

    public function get_recent_transaction(){
        $now=date('Y-m-d',strtotime(Carbon::now()));
        $data = Waybill::where('prepared_by',auth()->user()->contact_id)
        ->whereIn('booking_status',[0,1,2])
        ->where('is_guest',0)
        //->where(function($query) use($now){
        //   $query->whereRaw("DATEDIFF('$now',transactiondate)<=7 || booking_status!=0");
        //})
        ->whereRaw('DATE_ADD(transactiondate, INTERVAL 7 DAY) >= CURRENT_DATE')
        ->with(['pasabox_received','track_trace_recent','shipper','shipper_address','consignee','consignee_address','waybill','shipping_company','shipping_address'])->orderBy('transactiondate','DESC')->get();
        return WaybillResource::collection($data);
    }
    public function get_pasabox_uploaded_file($ref_no){
        echo json_encode(
            WaybillShipment::where('reference_no',$ref_no)
                    //->with('pasabox_received_details')
                    ->with(['pasabox_received_details'=>function($query){
                        $query->with('pasabox_received_files');
                    }])
                    ->get()
                    ->toArray()
        );

    }
    public function get_booking_track_traces($ref_no){

        // echo json_encode(
        //     WaybillTrackAndTrace::where('online_booking',$ref_no)
        //             ->orderBy('trackandtrace_datetime','DESC')
        //             ->get()
        //             ->toArray()
        // );

        echo json_encode(
            TrackAndTrace::where('online_booking',$ref_no)
                    ->with(['ol_track_trace_details'=>function($query){
                        $query->with('ol_track_trace_header');
                    }])
                    ->orderBy('track_trace_date','DESC')
                    ->get()
                    ->toArray()
        );

    }

    public function index()
    {
        $now=date('Y-m-d',strtotime(Carbon::now()));
        $waybills = Waybill::where('prepared_by',auth()->user()->contact_id)->with(['shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->orderBy('transactiondate','DESC')->get();
        $pending = Waybill::where('prepared_by',auth()->user()->contact_id)
                            ->whereIn('booking_status',[0,2])
                            ->where(function($query) use($now){
                                $query->whereRaw("DATEDIFF('$now',transactiondate)<=7 || booking_status!=0");
                            })
                           ->with(['shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->orderBy('transactiondate','DESC')->get();
        $transacted = Waybill::where('prepared_by',auth()->user()->contact_id)->where('booking_status',1)->with(['shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->orderBy('transactiondate','DESC')->get();
        //->whereIn('booking_status',[1,3])

        $agent = new Agent();
        $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.waybills.index' : 'waybills.index';


        return view($view,compact('pending','transacted','waybills'));
    }

    public function getWaybills(){
        $now=date('Y-m-d',strtotime(Carbon::now()));
        return response()->json([
            'pending'=>Waybill::where('prepared_by',auth()->user()->contact_id)
                                ->where(function($query) use($now){
                                    $query->whereRaw("DATEDIFF('$now',transactiondate)<=7 || booking_status!=0");
                                })
                              ->with(['branch','shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->select("*", DB::raw("DATE_FORMAT(transactiondate, '%m /%d/ %Y') AS transactiondate"))->orderBy('transactiondate','DESC')->get(),
            'transacted'=>Waybill::where('prepared_by',auth()->user()->contact_id)->where('booking_status',1)->with(['branch','shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->select("*", DB::raw("DATE_FORMAT(transactiondate, '%m /%d/ %Y') AS transactiondate"))->orderBy('transactiondate','DESC')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $ddStocks = ''; $ddUnits= '';$ddCities='';$ddBranches='';
        // $stocks = WStock::orderBy('stock_description','ASC')->get();
        // $units = WUnit::orderBy('unit_description','ASC')->get();
        //$branches = Branch::where('branches',1)->whereNotIn('branchoffice_no',['000','011','023'])->orderBy('branchoffice_description','ASC')->get();
        $branches = Branch::where('waybilling_destination',1)->orderBy('branchoffice_description','ASC')->get();

        // $stocks = WStock::whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get()->unique('stock_description');
        $units = WUnit::orderBy('unit_description','ASC')->get();
        // $cities=City::with(['province'])->orderBy('cities_name','ASC')->get();
        // foreach($stocks as $key=>$row){
        //     $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
        // }
        $ddUnits.="<option value='none'>--Please select--</option>";
        foreach($units as $key=>$row){
            $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
        }
        $term = Term::where('type','online-booking')->first();
        $term_pasabox = Term::where('type','pasabox')->first();
        // foreach($cities as $city){
        //     $ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
        // }
        foreach($branches as $branch){
            $ddBranches=$ddBranches.'<option value="'.$branch->branchoffice_no.'">'.$branch->branchoffice_description.'</option>';
        }
        $provinces = Province::with('city')->get();

        $contacts_external=array();
        if(
            Auth::user()->personal_corporate==1
            && $request->session()->has('pca_no')
            && $request->session()->get('pca_atype')=='external'
        ){

            $contacts_external=Contact::where('contact_id',$request->session()->get('pca_no'))
            ->orderBy('fileas','ASC')
            ->select("*")
            ->with(['user_address'=>function($query){
                $query->whereNotNull('sectorate_no')->with('sector');
            },
            'contact_number'
            ])
            ->get();

        }

        $contacts=Contact::whereHas('shipper_consignee',function($query){
                            $query->where('contact_id',auth()->user()->contact_id)
                            ->where('shipper_consignee_status',1)
                            ->where('pasabox',0);
                        })
                        ->orderBy('fileas','ASC')
                        ->select("*")
                        ->with(['user_address'=>function($query){
                            $query->whereNotNull('sectorate_no')->with('sector');
                        },
                        'shipper_consignee'=>function($query){
                            $query->with(['qr_profile'=>function($query){
                                $query->with(['qr_code_details'=>function($query){
                                    $query->with(['qr_code_profile_address'=>function($query){
                                        $query->whereNotNull('sectorate_no')->with('sector');
                                    }]);
                                }]);
                            }]);
                        },
                        'contact_number'
                        ])
                        ->get();


        $contacts_pasabox=Contact::whereHas('shipper_consignee',function($query){
                                $query->where('contact_id',auth()->user()->contact_id)
                                ->where('shipper_consignee_status',1)
                                ->where('pasabox',1);
                            })
                           ->orderBy('fileas','ASC')
                           ->select("*")
                           ->with(['user_address'=>function($query){
                               $query->with('sector');
                           },
                           'shipper_consignee'=>function($query){
                               $query->with(['qr_profile'=>function($query){
                                   $query->with(['qr_code_details'=>function($query){
                                        $query->with(['qr_code_profile_address'=>function($query){
                                            $query->whereNotNull('sectorate_no')->with('sector');
                                        }]);
                                   }]);
                               }]);
                           },
                           'contact_number'
                           ])
                           ->get();
        $is_charge = $this->hasChargeAccount();
        $rebate_factor= RebatePointFactor::where('rebate_point_status',1)
        ->where('effectivity_date','<=',date('Y-m-d',strtotime(Carbon::now())))
        ->where('rebate_type',1)
        ->orderBy('effectivity_date', 'DESC')
        ->orderBy('added_date', 'DESC')
        ->limit(1)
        ->get();
        $pca_adv_bal=0;

        if( Auth::user()->personal_corporate==1 && $request->session()->has('pca_no') ){
            $pending_termination =DB::table('waybill.tblpca_account_termination')
            ->where('pca_account_termination_status',0)
            ->where('pca_account_no',$request->session()->get('pca_no'))
            ->count();
            if($pending_termination == 0){
                $adv_details=AdvancePayment::where('pca_account_no',$request->session()->get('pca_no'))
                ->where('advance_payment_status',1)
                ->selectRaw("IFNULL(SUM(withdraw),0)-IFNULL(SUM(deposit),0) as bal")
                ->first();
                $pca_adv_bal=$adv_details->bal;
            }
        }

        return view(
            'waybills.create',
            compact(
                'ddStocks',
                'ddUnits',
                'contacts',
                'contacts_pasabox',
                'term',
                'term_pasabox',
                'ddCities',
                'ddBranches',
                'provinces',
                'is_charge',
                'units',
                'rebate_factor',
                'pca_adv_bal',
                'contacts_external'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function city_data($id){
        return City::where('cities_id',$id)->with('province')->first()->toArray();
    }

    public function stock_description($stock_no){
        return WStock::where('stock_no',$stock_no)->first()->stock_description;
    }

    public function unit_description($unit_no){
        return WUnit::where('unit_no',$unit_no)->first()->unit_description;
    }



    public function store(Request $request){

        try{
            DB::beginTransaction();
            $proceed_adv_payment=1;
            if(
                $request->step1['pasabox']==1
                && $request->step2['pca_no'] != ''
                && $request->step5['pca_use_adv_payment_cf'] == 1
                && $request->step5['gcash_amount'] > 0
            ){
                $adv_details=AdvancePayment::where('pca_account_no',$request->step2['pca_no'])
                ->where('advance_payment_status',1)
                ->selectRaw("IFNULL(SUM(withdraw),0)-IFNULL(SUM(deposit),0) as bal")
                ->first();
                if( $adv_details->bal < $request->step5['gcash_amount'] ){
                    $proceed_adv_payment=0;
                }
            }

            if($proceed_adv_payment==0){
                DB::rollback();
                return response()->json(['title'=>'Ooops!','message'=>'Insufficient Advance Payment Balance to Pay Pasabox Fee.','type'=>'error'],200);
            }else{
                $waybill_data = [];
                $shipper_data=[];
                $shipper_address_data=[];
                $consignee_data=[];
                $consignee_address_data=[];

                if($request->step1['shipper_id']=="new"){

                    $shipper_contact_no ='';
                    $shipper_contact_count = 0;
                    $shipper_mno='';
                    if($request->has('step1.shipper.shipper_mobile_no')){
                        foreach($request->step1['shipper']['shipper_mobile_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            if($shipper_mno==''){
                                $shipper_mno=$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    if($request->has('step1.shipper.shipper_telephone_no')){
                        foreach($request->step1['shipper']['shipper_telephone_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    $shipper_data['use_company']=$request->step1['shipper']['use_company'];
                    $shipper_data['lname'] = $request->step1['shipper']['use_company']==0 ? $request->step1['shipper']['lname'] : '';
                    $shipper_data['fname'] = $request->step1['shipper']['use_company']==0 ? $request->step1['shipper']['fname'] : '';
                    $shipper_data['mname'] = $request->step1['shipper']['use_company']==0 ? ($request->step1['shipper']['mname']!=null ? $request->step1['shipper']['mname'] : '') : '';
                    $shipper_data['company'] = $request->step1['shipper']['company']!=null ? $request->step1['shipper']['company'] : '';
                    $shipper_data['email'] = $request->step1['shipper']['email']!=null ? $request->step1['shipper']['email'] : '';
                    $shipper_data['contact_no']=$shipper_mno;
                    $shipper_data['business_category_id'] = $request->step1['shipper']['business_category_id'] != null && $request->step1['shipper']['business_category_id'] != 'none' && $request->step1['shipper']['business_category_id'] != '' ? $request->step1['shipper']['business_category_id'] : 0;
                    $shipper_data['fileas']=$request->step1['shipper']['use_company']==1 ? $request->step1['shipper']['company'] : $shipper_data['lname'].', '.$shipper_data['fname'].' '.$shipper_data['mname'];
                    $shipper_data['contact_id']=$this->generate_contact_id();

                    Contact::create($shipper_data);
                    ShipperConsignee::create([
                                            'contact_id'=>Auth::user()->contact_id,
                                            'shipper_consignee_id'=>$shipper_data['contact_id'],
                                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                            'rider'=>0
                                        ]);
                    $shipper_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $shipper_address_data['address_caption']='DEFAULT';
                    $shipper_address_data['user_id']= $shipper_data['contact_id'];
                    $shipper_address_data['street']=$request->step1['shipper']['street'];
                    $shipper_address_data['province']=$request->step1['shipper']['province'];
                    $shipper_address_data['city']=$request->step1['shipper']['city_text'];
                    $shipper_address_data['postal_code']=$request->step1['shipper']['postal_code'];
                    $shipper_address_data['barangay']=$request->step1['shipper']['barangay'];
                    $shipper_address_data['sectorate_no']=$request->step1['shipper']['sectorate_no'];
                    UserAddress::create($shipper_address_data);
                }else{
                    $shipper_id = $request->step1['shipper_id'];
                    $shipper_contact_no ='';
                    $shipper_contact_count=0;
                    $shipper_mno='';
                    if($request->has('step1.shipper_mobile_no')){
                        foreach($request->step1['shipper_mobile_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            if($shipper_mno==''){
                                $shipper_mno=$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    if($request->has('step1.shipper_telephone_no')){
                        foreach($request->step1['shipper_telephone_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;

                        }
                    }
                    Contact::where('contact_id',$shipper_id)->update([
                        'email'=>$request->step1['shipper_email']!=null ? $request->step1['shipper_email'] : '',
                        'contact_no'=>$shipper_mno
                    ]);
                }

                if($request->step1['shipper_address_id']=="new"){
                    $shipper_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $shipper_address_data['address_caption']='DEFAULT';
                    $shipper_address_data['user_id']= $request->step1['shipper_id'];
                    $shipper_address_data['street']=$request->step1['shipper']['street'];
                    $shipper_address_data['province']=$request->step1['shipper']['province'];
                    $shipper_address_data['city']=$request->step1['shipper']['city_text'];
                    $shipper_address_data['postal_code']=$request->step1['shipper']['postal_code'];
                    $shipper_address_data['barangay']=$request->step1['shipper']['barangay'];
                    $shipper_address_data['sectorate_no']=$request->step1['shipper']['sectorate_no'];
                    UserAddress::create($shipper_address_data);
                }

                if($request->step1['consignee_id']=="new"){
                    $consignee_contact_count = 0;
                    $consignee_contact_no ='';
                    $consignee_mno ='';
                    if($request->has('step1.consignee.consignee_mobile_no')){
                        foreach($request->step1['consignee']['consignee_mobile_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            if($consignee_mno==''){
                                $consignee_mno=$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }
                    if($request->has('step1.consignee.consignee_telephone_no')){
                        foreach($request->step1['consignee']['consignee_telephone_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }

                    $consignee_data['use_company']=$request->step1['consignee']['use_company'];
                    $consignee_data['lname'] = $request->step1['consignee']['use_company']==0 ? $request->step1['consignee']['lname'] : '';
                    $consignee_data['fname'] = $request->step1['consignee']['use_company']==0 ? $request->step1['consignee']['fname'] : '';
                    $consignee_data['mname'] = $request->step1['consignee']['use_company']==0 ? ($request->step1['consignee']['mname']!=null ? $request->step1['consignee']['mname'] : '') : '';
                    $consignee_data['company'] = $request->step1['consignee']['company']!=null ? $request->step1['consignee']['company'] : '';
                    $consignee_data['email'] = $request->step1['consignee']['email']!=null ? $request->step1['consignee']['email'] : '';
                    $consignee_data['contact_no']=$consignee_mno;
                    $consignee_data['business_category_id'] = $request->step1['consignee']['business_category_id'] != null && $request->step1['consignee']['business_category_id'] !='none' && $request->step1['consignee']['business_category_id'] !='' ? $request->step1['consignee']['business_category_id'] : 0;
                    $consignee_data['fileas']=$request->step1['consignee']['use_company']==1 ? $request->step1['consignee']['company'] : $consignee_data['lname'].', '.$consignee_data['fname'].' '.$consignee_data['mname'];
                    $consignee_data['contact_id']=$this->generate_contact_id();
                    //echo $consignee_data['business_category_id'];
                    Contact::create($consignee_data);
                    ShipperConsignee::create([
                                            'contact_id'=>Auth::user()->contact_id,
                                            'shipper_consignee_id'=>$consignee_data['contact_id'],
                                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                            'rider'=>0
                                        ]);

                    $consignee_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $consignee_address_data['address_caption']='DEFAULT';
                    $consignee_address_data['user_id']= $consignee_data['contact_id'];
                    $consignee_address_data['street']=$request->step1['consignee']['street'];
                    $consignee_address_data['province']=$request->step1['consignee']['province'];
                    $consignee_address_data['city']=$request->step1['consignee']['city_text'];
                    $consignee_address_data['postal_code']=$request->step1['consignee']['postal_code'];
                    $consignee_address_data['barangay']=$request->step1['consignee']['barangay'];
                    $consignee_address_data['sectorate_no']=$request->step1['consignee']['sectorate_no'];
                    UserAddress::create($consignee_address_data);
                }else{
                    $consignee_id = $request->step1['consignee_id'];
                    $consignee_contact_no ='';
                    $consignee_contact_count=0;
                    $consignee_mno ='';
                    if($request->has('step1.consignee_mobile_no')){
                        foreach($request->step1['consignee_mobile_no'] as $key=>$contact_no){

                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            if($consignee_mno==''){
                                $consignee_mno=$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }
                    if($request->has('step1.consignee_telephone_no')){
                        foreach($request->step1['consignee_telephone_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;

                        }
                    }
                    Contact::where('contact_id',$consignee_id)->update([
                        'email'=>$request->step1['consignee_email']!=null ? $request->step1['consignee_email'] : '',
                        'contact_no'=>$consignee_mno
                    ]);
                }

                if($request->step1['consignee_address_id']=="new"){
                    $consignee_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $consignee_address_data['address_caption']='DEFAULT';
                    $consignee_address_data['user_id']= $request->step1['consignee_id'];
                    $consignee_address_data['street']=$request->step1['consignee']['street'];
                    $consignee_address_data['province']=$request->step1['consignee']['province'];
                    $consignee_address_data['city']=$request->step1['consignee']['city_text'];
                    $consignee_address_data['postal_code']=$request->step1['consignee']['postal_code'];
                    $consignee_address_data['barangay']=$request->step1['consignee']['barangay'];
                    $consignee_address_data['sectorate_no']=$request->step1['consignee']['sectorate_no'];
                    UserAddress::create($consignee_address_data);
                }

                $discount_coupon='';
                if($request->step2['discount_coupon_action']==1 && $request->step2['discount_coupon'] !='' ){
                    $discount_coupon=$request->step2['discount_coupon'];
                }

                $waybill_data['reference_no']=$request->step4['reference_no'];
                $waybill_data['shipper_id']=$request->step1['shipper_id']=="new" ? $shipper_data['contact_id'] : $request->step1['shipper_id'];
                $waybill_data['shipper_address_id']=$request->step1['shipper_id']=="new" || $request->step1['shipper_address_id']=="new" ? $shipper_address_data['useraddress_no'] : $request->step1['shipper_address_id'];
                $waybill_data['consignee_id']=$request->step1['consignee_id']=="new" ? $consignee_data['contact_id'] : $request->step1['consignee_id'];
                $waybill_data['consignee_address_id']=$request->step1['consignee_id']=="new" || $request->step1['consignee_address_id']=="new" ? $consignee_address_data['useraddress_no'] : $request->step1['consignee_address_id'];
                $waybill_data['prepared_by']=Auth::user()->contact_id;
                $waybill_data['transactiondate']=date('Y-m-d',strtotime(Carbon::now()));
                $waybill_data['prepared_datetime']=date('Y-m-d H:i:s',strtotime(Carbon::now()));
                $waybill_data['shipment_type']=$request->step2['shipment_type'];
                $waybill_data['destinationbranch_id']=$request->step2['destinationbranch_id'];
                $waybill_data['declared_value']=$request->step2['declared_value'];
                $waybill_data['payment_type']=$request->step2['payment_type'];
                $waybill_data['pickup']=$request->step2['pu_checkbox'];
                $waybill_data['delivery']=$request->step2['del_checkbox'];
                $waybill_data['pickup_sector_id']=$request->step2['pu_checkbox']==1 ? $request->step2['pu_sector'] : '';
                $waybill_data['delivery_sector_id']=$request->step2['del_checkbox']==1 ? $request->step2['del_sector'] : '';
                $waybill_data['pickup_date']=$request->step2['pu_checkbox']==1 ? date('Y-m-d',strtotime( $request->step2['pu_date'] )) : null;
                $waybill_data['pickup_sector_street']=$request->step2['pu_checkbox']==1 ? ($request->step2['pu_street'] != null ? $request->step2['pu_street'] :'') : '';
                $waybill_data['delivery_sector_street']=$request->step2['del_checkbox']==1 ? ($request->step2['del_street'] != null ? $request->step2['del_street'] :'') : '';
                $waybill_data['pasabox']=$request->step1['pasabox'];
                $waybill_data['discount_coupon']=$discount_coupon !='' ? $discount_coupon : null;

                if( $request->step2['payment_type']=='CI' ){
                    $waybill_data['mode_payment']=$request->step2['mode_payment'];

                    if( $request->step2['mode_payment']==2 ){
                        $waybill_data['mode_payment_io']=$request->step2['mode_payment_io'];

                        if( $request->step2['mode_payment_io']==2 ){
                            $waybill_data['mode_payment_email']=$request->step2['mode_payment_email'];
                        }
                    }
                }

                $waybill_data['pca_advance_payment']=$request->step2['pca_use_adv_payment'];
                $waybill_data['pca_account_no']=$request->step2['pca_no'] !='' ? $request->step2['pca_no'] : null;


                Waybill::create($waybill_data);

                ContactNumber::whereIn('contact_id',[$waybill_data['shipper_id'],$waybill_data['consignee_id']])->delete();
                WaybillContact::where('reference_no',$waybill_data['reference_no'])->delete();

                if($request->step1['pasabox']==1){

                    if($request->step1['shipping_cid']=="new"){

                        $shipping_company_contact_no="";

                        if($request->step1['shipping_cmobile_no'] !=''){
                            $shipping_company_contact_no .=$request->step1['shipping_cmobile_no'];
                        }

                        // if($request->step1['shipping_clandline_no'] !=''){

                        //     if($shipping_company_contact_no !=''){
                        //         $shipping_company_contact_no .='/';
                        //     }

                        //     $shipping_company_contact_no .=$request->step1['shipping_clandline_no'];
                        // }

                        $shipping_company_data['use_company']=1;
                        $shipping_company_data['company'] = $request->step1['shipping_cname'];
                        $shipping_company_data['email'] =  $request->step1['shipping_cemail'] !=null ? $request->step1['shipping_cemail'] : '';
                        $shipping_company_data['contact_no']=$shipping_company_contact_no;
                        $shipping_company_data['fileas']=$request->step1['shipping_cname'];
                        $shipping_company_data['contact_id']=$this->generate_contact_id();

                        Contact::create($shipping_company_data);



                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$shipping_company_data['contact_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'pasabox'=>1
                        ]);
                    }else{
                        $shipping_company_data['contact_id']=$request->step1['shipping_cid'];
                    }
                    if( $shipping_company_data['contact_id'] !='' && $shipping_company_data['contact_id'] !='none'){
                        ContactNumber::where('contact_id',$shipping_company_data['contact_id'])->delete();
                        if($request->step1['shipping_cmobile_no'] !=''){

                            ContactNumber::create([
                                'contact_id'=>$shipping_company_data['contact_id'],
                                'contact_no'=>$request->step1['shipping_cmobile_no'],
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$request->step1['shipping_cmobile_no'],
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>3,
                                'reference_no'=>$request->step4['reference_no']
                            ]);

                        }


                        if($request->step1['shipping_clandline_no'] !=''){

                            ContactNumber::create([
                                'contact_id'=>$shipping_company_data['contact_id'],
                                'contact_no'=>$request->step1['shipping_clandline_no'],
                                'type'=>2
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$request->step1['shipping_clandline_no'],
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>3,
                                'reference_no'=>$request->step4['reference_no']
                            ]);

                        }


                        if( $request->step1['shipping_address_id']=="new" ){

                            $shipping_company_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                            //$shipping_company_address_data['address_caption']='DEFAULT';
                            $shipping_company_address_data['user_id']= $shipping_company_data['contact_id'];
                            $shipping_company_address_data['street']=$request->step1['shipping_street'];
                            $shipping_company_address_data['province']=$request->step1['shipping_province'];
                            $shipping_company_address_data['city']=$request->step1['shipping_city_text'];
                            $shipping_company_address_data['postal_code']=$request->step1['shipping_postal_code'];
                            $shipping_company_address_data['barangay']=$request->step1['shipping_barangay'];
                            $shipping_company_address_data['sectorate_no']=$request->step1['shipping_sectorate_no'];
                            UserAddress::create($shipping_company_address_data);

                        }else{
                            $shipping_company_address_data['useraddress_no']= $request->step1['shipping_address_id'];
                        }



                    }else{
                        $shipping_company_data['contact_id']=null;
                        $shipping_company_address_data['useraddress_no']=null;

                    }

                    Waybill::where('reference_no',$request->step4['reference_no'])
                    ->update([
                        'shipping_company_id'=> $shipping_company_data['contact_id']=='' || $shipping_company_data['contact_id']=='none' ? null : $shipping_company_data['contact_id'] ,
                        'shipping_company_address_id'=> $shipping_company_address_data['useraddress_no']=='' || $shipping_company_address_data['useraddress_no']=='none' ? null : $shipping_company_address_data['useraddress_no'] ,
                        'shipping_company_cno'=>$request->step1['shipping_cmobile_no'],
                        'shipping_company_lno'=>$request->step1['shipping_clandline_no'],
                        'shipping_company_email'=>$request->step1['shipping_cemail'],
                        'pasabox_branch_receiver'=>$request->step2['pasabox_branch_receiver'],
                        'pasabox_cf_amt'=>$request->step5['gcash_amount']
                    ]);

                    if( $request->step5['pca_use_adv_payment_cf'] == 1){

                        Waybill::where('reference_no',$request->step4['reference_no'])
                        ->update([
                            'pca_pasabox_cf_advance_payment'=> 1
                        ]);

                        $orcr_reference='CRONL'.date("y").''.$this->random_alph_num(3,2);

                        $orcr_details['reference_no']=$orcr_reference;
                        $orcr_details['pasabox_cf']=1;
                        $orcr_details['pasabox_cf_ref_no']=$request->step4['reference_no'];
                        $orcr_details['online_booking_ref']=$request->step4['reference_no'];
                        $orcr_details['transaction_date']=Carbon::now();
                        $orcr_details['advancepayment']=$request->step5['gcash_amount'];
                        $orcr_details['deposit']=$request->step5['gcash_amount'];
                        ORCRDetails::create($orcr_details);

                        $adv_payment_details['reference_no']=$orcr_reference;
                        $adv_payment_details['deposit']=$request->step5['gcash_amount'];
                        $adv_payment_details['pca_account_no']=$request->step2['pca_no'];
                        $adv_payment_details['prepared_datetime']=Carbon::now();
                        AdvancePayment::create($adv_payment_details);

                        $pasabox_to_receive['online_booking_ref']=$request->step4['reference_no'];
                        PasaboxReceive::create($pasabox_to_receive);

                        DB::table('waybill.tblpca_accounts_emailing')
                        ->insert([
                            'pca_account_no' => $request->step2['pca_no'],
                            'email_type'=>4,
                            'payment_ref'=>$orcr_reference
                        ]);

                    }else{

                        $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                        while(OnlinePayment::where('onlinepayment_id',$onlinepayment_id)->exists()){
                            $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                        }
                        $online_payment['onlinepayment_id']= $onlinepayment_id;
                        $online_payment['onlinepayment_date'] = $request->step5['gcash_pdate'];
                        $online_payment['for_confirmation_pdate'] = $request->step5['gcash_pdate'];
                        $online_payment['onlinepayment_amount']=$request->step5['gcash_amount'];
                        $online_payment['for_confirmation_amount']=$request->step5['gcash_amount'];
                        $online_payment['verification_code']=$request->step5['gcash_reference_no'];
                        $online_payment['gcash_added_datetime'] = Carbon::now();
                        $online_payment['for_confirmation']=1;
                        $online_payment['confirmation_status']=0;
                        $online_payment['pasabox_cf']=1;
                        if($request->step5['gcash_cemail'] !=''){
                            $online_payment['confirmation_email']=$request->step5['gcash_cemail'];
                        }
                        $online_payment['confirmation_branch']=$request->step2['pasabox_branch_receiver'];
                        $online_payment['confirmation_branch_account_name']=$request->step5['gcash_branch_aname'];
                        $online_payment['confirmation_branch_account_no']=$request->step5['gcash_branch_ano'];
                        $online_payment['bank_no']=$request->step5['gcash_id'];
                        OnlinePayment::create($online_payment);

                        $online_payment_confirmation['online_booking_ref']=$request->step4['reference_no'];
                        $online_payment_confirmation['onlinepayment_id']=$onlinepayment_id;
                        OnlinePaymentConfirmation::create($online_payment_confirmation);
                    }
                }


                if($request->step1['shipper_id']=="new"){
                    if($request->has('step1.shipper.shipper_mobile_no')){
                        foreach($request->step1['shipper']['shipper_mobile_no'] as $contact_no){

                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                    if($request->has('step1.shipper.shipper_telephone_no')){
                        foreach($request->step1['shipper']['shipper_telephone_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                }else{
                    $shipper_contact_numbers="";
                    if($request->has('step1.shipper_mobile_no')){
                        foreach($request->step1['shipper_mobile_no'] as $key=>$contact_no){
                            $shipper_contact_numbers .= ($key>0 ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                    if($request->has('step1.shipper_telephone_no')){
                        foreach($request->step1['shipper_telephone_no'] as $contact_no){
                            $shipper_contact_numbers .= ($shipper_contact_numbers!="" ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>2
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                    if($request->step1['shipper_mname_update']==1){

                        $c_details=Contact::where('contact_id',$request->step1['shipper_id'])->get();
                        $new_fileas='';
                        $mname=strtoupper(trim($request->step1['shipper_mname_update_text']));
                        foreach($c_details as $c_data){
                            $lname=strtoupper(trim($c_data->lname));
                            $fname=strtoupper(trim($c_data->fname));
                            $new_fileas=$lname.", ".$fname." ".$mname;
                        }
                        Contact::where('contact_id',$request->step1['shipper_id'])->update([
                            'fileas'=>$new_fileas,
                            'mname'=>$mname
                        ]);

                    }

                    if(
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['shipper_id'])
                        ->where("shipper_consignee_status",1)
                        ->doesntExist()
                    )
                    {
                        $qr='';
                        if( $request->step1['shipper_qr_code'] !=''
                            &&
                            $request->step1['shipper_qr_code_cid'] == $request->step1['shipper_id']
                        ){
                            $qr=$request->step1['shipper_qr_code'];
                        }
                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$request->step1['shipper_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'qr_code'=> ($qr=='' ? NULL : $qr )
                        ]);


                    }


                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $request->step1['shipper_id'])
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                    if( $request->step1['shipper_qr_code'] !=''
                        &&
                        $request->step1['shipper_qr_code_cid'] == $request->step1['shipper_id']
                        &&
                        $sc_qr_code != $request->step1['shipper_qr_code']
                    ){
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['shipper_id'])
                        ->update([
                            'qr_code'=>$request->step1['shipper_qr_code']
                        ]);
                        $sc_qr_code=$request->step1['shipper_qr_code'];
                    }

                    if($sc_qr_code !=''){
                        Waybill::where('reference_no',$request->step4['reference_no'])
                        ->update([
                            'shipper_qr_code'=>$sc_qr_code
                        ]);
                    }
                }

                if($request->step1['consignee_id']=="new"){
                    if($request->has('step1.consignee.consignee_mobile_no')){
                        foreach($request->step1['consignee']['consignee_mobile_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }

                    if($request->has('step1.consignee.consignee_telephone_no')){
                        foreach($request->step1['consignee']['consignee_telephone_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                }else{
                    $consignee_contact_numbers = "";
                    if($request->has('step1.consignee_mobile_no')){
                        foreach($request->step1['consignee_mobile_no'] as $key=>$contact_no){
                            $consignee_contact_numbers .= ($key>0 ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                    if($request->has('step1.consignee_telephone_no')){
                        foreach($request->step1['consignee_telephone_no'] as $contact_no){
                            $consignee_contact_numbers .= ($consignee_contact_numbers!="" ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>2
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$waybill_data['reference_no']
                            ]);
                        }
                    }
                    if($request->step1['consignee_mname_update']==1){

                        $c_details=Contact::where('contact_id',$request->step1['consignee_id'])->get();
                        $new_fileas='';
                        $mname=strtoupper(trim($request->step1['consignee_mname_update_text']));
                        foreach($c_details as $c_data){
                            $lname=strtoupper(trim($c_data->lname));
                            $fname=strtoupper(trim($c_data->fname));
                            $new_fileas=$lname.", ".$fname." ".$mname;
                        }
                        Contact::where('contact_id',$request->step1['consignee_id'])->update([
                            'fileas'=>$new_fileas,
                            'mname'=>$mname
                        ]);

                    }

                    if(
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['consignee_id'])
                        ->where("shipper_consignee_status",1)
                        ->doesntExist()
                    )
                    {
                        $qr='';
                        if( $request->step1['consignee_qr_code'] !=''
                            &&
                            $request->step1['consignee_qr_code_cid'] == $request->step1['consignee_id']
                        ){
                            $qr=$request->step1['consignee_qr_code'];
                        }
                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$request->step1['consignee_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'qr_code'=> ($qr=='' ? NULL : $qr )
                        ]);


                    }


                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $request->step1['consignee_id'])
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                    if( $request->step1['consignee_qr_code'] !=''
                        &&
                        $request->step1['consignee_qr_code_cid'] == $request->step1['consignee_id']
                        &&
                        $sc_qr_code != $request->step1['consignee_qr_code']
                    ){
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['consignee_id'])
                        ->update([
                            'qr_code'=>$request->step1['consignee_qr_code']
                        ]);
                        $sc_qr_code=$request->step1['consignee_qr_code'];
                    }

                    if($sc_qr_code !=''){
                        Waybill::where('reference_no',$request->step4['reference_no'])
                        ->update([
                            'consignee_qr_code'=>$sc_qr_code
                        ]);
                    }


                }

                foreach($request->step3 as $ws){
                    $waybill_shipment_id = 'K'.$this->random_num(6);
                    $ws_data = $ws;
                    $ws_data['waybill_shipment_id']=$waybill_shipment_id;
                    $ws_data['reference_no']=$waybill_data['reference_no'];
                    $ws_data['cargo_type_id']='';
                    WaybillShipment::create($ws_data);
                    WaybillShipmentMultiple::create([
                        'waybill_shipment_id'=> $ws_data['waybill_shipment_id'],
                        'reference_no'=>$ws_data['reference_no'],
                        'itemcode'=>$ws_data['item_code'],
                        'itemdescription'=>$ws_data['item_description'],
                        'quantity'=>$ws_data['quantity'],
                        'weight'=>0,
                        'lenght'=>0,
                        'height'=>0,
                        'width'=>0
                    ]);
                }

                WaybillTrackAndTrace::create([
                    'trackandtrace_status'=>'CREATED ONLINE BOOKING WITH REF#: '.$waybill_data['reference_no'].' ADDED BY '.Auth::user()->name,
                    'online_booking'=>$waybill_data['reference_no']
                ]);

                DB::commit();
                return response()->json(['title'=>'Success','message'=>'Booking successfully','type'=>'success','key'=>Crypt::encrypt($waybill_data['reference_no'])],200);

            }
        } catch (\Throwable $th) {

            DB::rollback();

            #RSD 10-04-2025 1035 -- use standard error log trait
            // $e_id=substr(sha1(mt_rand()),17,20);
            // DB::table('recordlogs.error_logs')
            // ->insert([
            //     'error_id'=>$e_id,
            //     'error_message'=>'ERROR IN SAVING WAYBILLS.',
            //     'error_description'=>$e->getMessage()
            // ]);

            // return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);

            $this->error_log($th,$request->url());
            $message = 'An unexpected error has occurred. Please take a screenshot of this message and share it with our customer service team so we can assist you promptly. Error Code: '.session('errorId');
            return response()->json(['message'=>$message,'type'=>'error'],200);
        }
    }









    // public function store(Request $request){
    //     print_r($request->all());
    // }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        $data = Waybill::where('reference_no',$id)
                        ->with([
                            'shipping_company',
                            'shipper',
                            'consignee',
                            'branch',
                            'branch_receiver'=>function($query){
                                $query->with('pasabox_authorized_employee');
                            },
                            'waybill_shipment',
                            'charge_to_data',
                            'shipping_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'shipper_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'consignee_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            }
                            ])->first();
        if($data['pasabox']==1){
            $term_data = 'pasabox';
        }else{
            $term_data = 'online-booking';
        }
        $term = Term::where('type',$term_data)->first();


        $shipper_contact = WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',1)->pluck('waybill_contacts_no');
        $consignee_contact = WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',2)->pluck('waybill_contacts_no');
        $shipping_contact = WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',3)->pluck('waybill_contacts_no');

        $view = 'waybills.show';
        $encrypt_reference_no = Crypt::encrypt($id);
        return view($view,compact('data','term','encrypt_reference_no','shipper_contact','consignee_contact','shipping_contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function edit(Request $request,$id)
    {
        $id=base64_decode($id);
        $waybill=Waybill::with('pasabox_received')->where('reference_no',$id);
        if(
            in_array($waybill->first()->booking_status,[0,2])
            &&
            (
                in_array($waybill->first()->pasabox_received,[NULL])
                ||
                in_array($waybill->first()->pasabox_received->pasabox_to_receive_status,[0])
            )
        ){
            $data = $waybill->with([
                'waybill_contact',
                'pasabox_received',
                'shipping_company'=>function($query){
                    $query->with(['user_address'=>function($query){
                        $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                        }
                    ]);
                },
                'shipping_address'=>function($query){
                    $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'shipping_sc'=>function($query){
                    $query->with(['qr_profile'=>function($query){
                        $query->with(['qr_code_details'=>function($query){
                                $query->with(['qr_code_profile_address'=>function($query){
                                    $query->whereNotNull('sectorate_no')->with('sector');
                                }]);
                        }]);
                    }]);
                },
                'shipper'=>function($query){
                    $query->with(['user_address'=>function($query){
                        $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                        }
                    ]);
                },
                'shipper_sc'=>function($query){
                    $query->with(['qr_profile'=>function($query){
                        $query->with(['qr_code_details'=>function($query){
                                $query->with(['qr_code_profile_address'=>function($query){
                                    $query->whereNotNull('sectorate_no')->with('sector');
                                }]);
                        }]);
                    }]);
                },
                'shipper_address'=>function($query){
                    $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'consignee'=>function($query){
                    $query->with(['user_address'=>function($query){
                        $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                    }
                    ]);
                },
                'consignee_sc'=>function($query){
                    $query->with(['qr_profile'=>function($query){
                        $query->with(['qr_code_details'=>function($query){
                             $query->with(['qr_code_profile_address'=>function($query){
                                 $query->whereNotNull('sectorate_no')->with('sector');
                             }]);
                        }]);
                    }]);
                },
                'consignee_address'=>function($query){
                    $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'waybill_shipment',
                'pick_up_sector',
                'delivery_sector'

                ])->first();
            $ddStocks = ''; $ddUnits= '';$ddCities='';$ddBranches='';
            //$branches = Branch::where('branches',1)->whereNotIn('branchoffice_no',['000','011','023'])->orderBy('branchoffice_description','ASC')->get();
            $branches = Branch::where('waybilling_destination',1)->orderBy('branchoffice_description','ASC')->get();

            $stocks = WStock::whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get()->unique('stock_description');
            $units = WUnit::orderBy('unit_description','ASC')->get();
            $cities=City::with(['province'])->orderBy('cities_name','ASC')->get();
            foreach($stocks as $key=>$row){
            $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
            }
            foreach($units as $key=>$row){
            $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
            }
            $term = Term::where('type','online-booking')->first();
            $term_pasabox = Term::where('type','pasabox')->first();
            foreach($cities as $city){
            $ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
            }
            foreach($branches as $branch){
            $ddBranches=$ddBranches.'<option value="'.$branch->branchoffice_no.'">'.$branch->branchoffice_description.'</option>';
            }
            $provinces = Province::with('city')->get();

            // $contacts=Contact::whereHas('shipper_consignee',function($query){
            //                 $query->where('contact_id',auth()->user()->contact_id);
            //             })
            //         ->orderBy('fileas','ASC')
            //         ->select("*")
            //         ->with(['user_address'])
            //         ->get();

            $contacts=Contact::whereHas('shipper_consignee',function($query){
                $query->where('contact_id',auth()->user()->contact_id)
                ->where('shipper_consignee_status',1)
                ->where('pasabox',0);
            })
           ->orderBy('fileas','ASC')
           ->select("*")
           ->with(['user_address'=>function($query){
               $query->whereNotNull('sectorate_no')->with('sector');
           },
           'shipper_consignee'=>function($query){
               $query->with(['qr_profile'=>function($query){
                   $query->with(['qr_code_details'=>function($query){
                        $query->with(['qr_code_profile_address'=>function($query){
                            $query->whereNotNull('sectorate_no')->with('sector');
                        }]);
                   }]);
               }]);
           },
           'contact_number'
           ])
           ->get();


            $is_charge = $this->hasChargeAccount();

            $shipper_contacts_mobile = array_values(array_filter($data->waybill_contact->toArray(), function($contact) {
                return $contact['waybill_shipper_consignee'] == 1 && $contact['waybill_contacts_no_type'] == 1;
            }));

            $shipper_contacts_telephone = array_values(array_filter($data->waybill_contact->toArray(), function($contact) {
                return $contact['waybill_shipper_consignee'] == 1 && $contact['waybill_contacts_no_type'] == 2;
            }));

            $consignee_contacts_mobile = array_values(array_filter($data->waybill_contact->toArray(), function($contact) {
                return $contact['waybill_shipper_consignee'] == 2 && $contact['waybill_contacts_no_type'] == 1;
            }));

            $consignee_contacts_telephone = array_values(array_filter($data->waybill_contact->toArray(), function($contact) {
                return $contact['waybill_shipper_consignee'] == 1 && $contact['waybill_contacts_no_type'] == 2;
            }));


            $pu_province_sector='';
            $pu_city_sector='';
            $pu_brgy_sector='';

            if( $data->pickup==1 ){

                $sector_list=Sector::where('sectorate_no', $data->pickup_sector_id)
                ->get();

                foreach($sector_list as $sector){

                    // $pu_province_sector=Sector::where(function($query) {
                    //     $query->orWhere('serviceable_panel', 1)
                    //           ->orWhere('serviceable_truck', 1);
                    // })
                    // ->orderBy('province', 'asc')
                    // ->get()->unique('province');
                    $pu_province_sector = Province::get();

                    // $pu_city_sector=Sector::where(function($query) {
                    //     $query->orWhere('serviceable_panel', 1)
                    //           ->orWhere('serviceable_truck', 1);
                    // })
                    // ->whereRaw('UPPER(province) = ?', [strtoupper( $sector->province )])
                    // ->orderBy('city', 'asc')
                    // ->get()->unique('city');
                    $city=City::where('cities_id',$sector->city_id)->get();
                    foreach($city as $city_data){
                        $pu_city_sector=City::where('province_id',$city_data->province_id)->get();
                    }


                    $pu_brgy_sector=Sector::where(function($query) {
                        $query->orWhere('serviceable_panel', 1)
                              ->orWhere('serviceable_truck', 1);
                    })
                    ->where('city_id',$sector->city_id)
                    //->whereRaw('UPPER(province) = ?', [strtoupper($sector->province)])
                    //->whereRaw('UPPER(city) = ?', [strtoupper($sector->city)])
                    ->orderBy('barangay', 'asc')
                    ->get();

                }

            }

            $del_province_sector='';
            $del_city_sector='';
            $del_brgy_sector='';

            if( $data->delivery==1 ){

                $sector_list=Sector::where('sectorate_no', $data->delivery_sector_id)
                ->get();

                foreach($sector_list as $sector){


                    // $del_province_sector=Sector::where(function($query) {
                    //     $query->orWhere('serviceable_panel', 1)
                    //           ->orWhere('serviceable_truck', 1);
                    // })
                    // ->orderBy('province', 'asc')
                    // ->get()->unique('province');

                    $del_province_sector = Province::get();

                    // $del_city_sector=Sector::where(function($query) {
                    //     $query->orWhere('serviceable_panel', 1)
                    //           ->orWhere('serviceable_truck', 1);
                    // })
                    // ->whereRaw('UPPER(province) = ?', [strtoupper($sector->province)])
                    // ->orderBy('city', 'asc')
                    // ->get()->unique('city');

                    $city=City::where('cities_id',$sector->city_id)->get();
                    foreach($city as $city_data){
                        $del_city_sector=City::where('province_id',$city_data->province_id)->get();
                    }

                    $del_brgy_sector=Sector::where(function($query) {
                        $query->orWhere('serviceable_panel', 1)
                              ->orWhere('serviceable_truck', 1);
                    })
                    //->whereRaw('UPPER(province) = ?', [strtoupper($sector->province)])
                    //->whereRaw('UPPER(city) = ?', [strtoupper($sector->city)])
                    ->where('city_id',$sector->city_id)
                    ->orderBy('barangay', 'asc')
                    ->get();
                }

            }

            $contacts_pasabox=Contact::whereHas('shipper_consignee',function($query){
                $query->where('contact_id',auth()->user()->contact_id)
                ->where('shipper_consignee_status',1)
                ->where('pasabox',1);
            })
           ->orderBy('fileas','ASC')
           ->select("*")
           ->with(['user_address'=>function($query){
               $query->with('sector');
           },
           'shipper_consignee'=>function($query){
               $query->with(['qr_profile'=>function($query){
                   $query->with(['qr_code_details'=>function($query){
                        $query->with(['qr_code_profile_address'=>function($query){
                            $query->with('sector');
                        }]);
                   }]);
               }]);
           },
           'contact_number'
           ])
           ->get();
            $rebate_factor= RebatePointFactor::where('rebate_point_status',1)
            ->where('effectivity_date','<=',date('Y-m-d',strtotime(Carbon::now())))
            ->where('rebate_type',1)
            ->orderBy('effectivity_date', 'DESC')
            ->orderBy('added_date', 'DESC')
            ->limit(1)
            ->get();

            $pca_adv_bal=0;

            if( Auth::user()->personal_corporate==1 && $request->session()->has('pca_no') ){
                $pending_termination =DB::table('waybill.tblpca_account_termination')
                ->where('pca_account_termination_status',0)
                ->where('pca_account_no',$request->session()->get('pca_no'))
                ->count();
                if($pending_termination == 0){
                    $adv_details=AdvancePayment::where('pca_account_no',$request->session()->get('pca_no'))
                    ->where('advance_payment_status',1)
                    ->selectRaw("IFNULL(SUM(withdraw),0)-IFNULL(SUM(deposit),0) as bal")
                    ->first();
                    $pca_adv_bal=$adv_details->bal;
                }
            }

            return view(
                'waybills.edit',
                compact(
                    'id',
                    'data',
                    'ddStocks',
                    'ddUnits',
                    'contacts',
                    'contacts_pasabox',
                    'term',
                    'term_pasabox',
                    'ddCities',
                    'ddBranches',
                    'provinces',
                    'is_charge',
                    'units',
                    'shipper_contacts_mobile',
                    'shipper_contacts_telephone',
                    'consignee_contacts_mobile',
                    'consignee_contacts_telephone',
                    'pu_province_sector',
                    'pu_city_sector',
                    'pu_brgy_sector',
                    'del_province_sector',
                    'del_city_sector',
                    'del_brgy_sector',
                    'rebate_factor',
                    'pca_adv_bal'
                )
            );


        }else{
            abort(404);
        }



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id){
        DB::beginTransaction();
        try{

            $proceed_adv_payment=1;
            if(
                $request->step1['pasabox']==1
                && $request->step2['pca_no'] != ''
                && $request->step5['pca_use_adv_payment_cf'] == 1
                && $request->step5['gcash_amount'] > 0
            ){
                $adv_details=AdvancePayment::where('tbladvance_payment.pca_account_no',$request->step2['pca_no'])
                ->where('tbladvance_payment.advance_payment_status',1)
                ->whereRaw("IFNULL(tblorcrdetails.pasabox_cf_ref_no,'') != '".$id."'")
                ->leftJoin('waybill.tblorcrdetails','tbladvance_payment.reference_no','=','tblorcrdetails.reference_no')
                ->selectRaw("IFNULL(SUM(tbladvance_payment.withdraw),0)-IFNULL(SUM(tbladvance_payment.deposit),0) as bal")
                ->first();
                if( $adv_details->bal < $request->step5['gcash_amount'] ){
                    $proceed_adv_payment=0;
                }
            }
            $proceed_adv_payment=1;
            if($proceed_adv_payment==0){
                DB::rollback();
                return response()->json(['title'=>'Ooops!','message'=>'Insufficient Advance Payment Balance to Pay Pasabox Fee.','type'=>'error'],200);
            }else{
                $waybill_data = [];
                $shipper_data=[];
                $shipper_address_data=[];
                $consignee_data=[];
                $consignee_address_data=[];

                if($request->step1['shipper_id']=="new"){

                    $shipper_contact_no ='';
                    $shipper_contact_count = 0;
                    if($request->has('step1.shipper.shipper_mobile_no')){
                        foreach($request->step1['shipper']['shipper_mobile_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    if($request->has('step1.shipper.shipper_telephone_no')){
                        foreach($request->step1['shipper']['shipper_telephone_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    $shipper_data['use_company']=$request->step1['shipper']['use_company'];
                    $shipper_data['lname'] = $request->step1['shipper']['use_company']==0 ? $request->step1['shipper']['lname'] : '';
                    $shipper_data['fname'] = $request->step1['shipper']['use_company']==0 ? $request->step1['shipper']['fname'] : '';
                    $shipper_data['mname'] = $request->step1['shipper']['use_company']==0 ? $request->step1['shipper']['mname'] : '';
                    $shipper_data['company'] = $request->step1['shipper']['company']!=null ? $request->step1['shipper']['company'] : '';
                    $shipper_data['email'] = $request->step1['shipper']['email'];
                    $shipper_data['contact_no']=$shipper_contact_no;
                    $shipper_data['business_category_id'] = $request->step1['shipper']['business_category_id'] != null && $request->step1['shipper']['business_category_id'] !='none' && $request->step1['shipper']['business_category_id'] !=''  ? $request->step1['shipper']['business_category_id'] : 0;
                    $shipper_data['fileas']=$request->step1['shipper']['use_company']==1 ? $request->step1['shipper']['company'] : $shipper_data['lname'].', '.$shipper_data['fname'].' '.$shipper_data['mname'];
                    $shipper_data['contact_id']=$this->generate_contact_id();

                    Contact::create($shipper_data);
                    ShipperConsignee::create([
                                            'contact_id'=>Auth::user()->contact_id,
                                            'shipper_consignee_id'=>$shipper_data['contact_id'],
                                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                            'rider'=>0
                                        ]);
                    $shipper_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $shipper_address_data['address_caption']='DEFAULT';
                    $shipper_address_data['user_id']= $shipper_data['contact_id'];
                    $shipper_address_data['street']=$request->step1['shipper']['street'];
                    $shipper_address_data['province']=$request->step1['shipper']['province'];
                    $shipper_address_data['city']=$request->step1['shipper']['city_text'];
                    $shipper_address_data['postal_code']=$request->step1['shipper']['postal_code'];
                    $shipper_address_data['barangay']=$request->step1['shipper']['barangay'];
                    $shipper_address_data['sectorate_no']=$request->step1['shipper']['sectorate_no'];
                    UserAddress::create($shipper_address_data);
                }else{
                    $shipper_id = $request->step1['shipper_id'];
                    $shipper_contact_no ='';
                    $shipper_contact_count=0;
                    if($request->has('step1.shipper_mobile_no')){
                        foreach($request->step1['shipper_mobile_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;
                        }
                    }
                    if($request->has('step1.shipper_telephone_no')){
                        foreach($request->step1['shipper_telephone_no'] as $key=>$contact_no){
                            if($shipper_contact_count<2){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $shipper_contact_count+=1;

                        }
                    }
                    Contact::where('contact_id',$shipper_id)->update([
                        'email'=>$request->step1['shipper_email']!=null ? $request->step1['shipper_email'] : '',
                        'contact_no'=>$shipper_contact_no
                    ]);


                }

                if($request->step1['shipper_address_id']=="new"){
                    $shipper_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $shipper_address_data['address_caption']='DEFAULT';
                    $shipper_address_data['user_id']= $request->step1['shipper_id'];
                    $shipper_address_data['street']=$request->step1['shipper']['street'];
                    $shipper_address_data['province']=$request->step1['shipper']['province'];
                    $shipper_address_data['city']=$request->step1['shipper']['city_text'];
                    $shipper_address_data['postal_code']=$request->step1['shipper']['postal_code'];
                    $shipper_address_data['barangay']=$request->step1['shipper']['barangay'];
                    $shipper_address_data['sectorate_no']=$request->step1['shipper']['sectorate_no'];
                    UserAddress::create($shipper_address_data);
                }

                if($request->step1['consignee_id']=="new"){
                    $consignee_contact_count = 0;
                    $consignee_contact_no ='';
                    if($request->has('step1.consignee.consignee_mobile_no')){
                        foreach($request->step1['consignee']['consignee_mobile_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }
                    if($request->has('step1.consignee.consignee_telephone_no')){
                        foreach($request->step1['consignee']['consignee_telephone_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }
                    $consignee_data['use_company']=$request->step1['consignee']['use_company'];
                    $consignee_data['lname'] = $request->step1['consignee']['use_company']==0 ? $request->step1['consignee']['lname'] : '';
                    $consignee_data['fname'] = $request->step1['consignee']['use_company']==0 ? $request->step1['consignee']['fname'] : '';
                    $consignee_data['mname'] = $request->step1['consignee']['use_company']==0 ? $request->step1['consignee']['mname'] : '';
                    $consignee_data['company'] = $request->step1['consignee']['company']!=null ? $request->step1['consignee']['company'] : '';
                    $consignee_data['email'] = $request->step1['consignee']['email'];
                    $consignee_data['contact_no']=$consignee_contact_no;
                    $consignee_data['business_category_id'] = $request->step1['consignee']['business_category_id'] != null && $request->step1['consignee']['business_category_id'] != 'none' && $request->step1['consignee']['business_category_id'] != '' ? $request->step1['consignee']['business_category_id'] : 0;
                    $consignee_data['fileas']=$request->step1['consignee']['use_company']==1 ? $request->step1['consignee']['company'] : $consignee_data['lname'].', '.$consignee_data['fname'].' '.$consignee_data['mname'];
                    $consignee_data['contact_id']=$this->generate_contact_id();

                    Contact::create($consignee_data);
                    ShipperConsignee::create([
                                            'contact_id'=>Auth::user()->contact_id,
                                            'shipper_consignee_id'=>$consignee_data['contact_id'],
                                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                            'rider'=>0
                                        ]);

                    $consignee_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $consignee_address_data['address_caption']='DEFAULT';
                    $consignee_address_data['user_id']= $consignee_data['contact_id'];
                    $consignee_address_data['street']=$request->step1['consignee']['street'];
                    $consignee_address_data['province']=$request->step1['consignee']['province'];
                    $consignee_address_data['city']=$request->step1['consignee']['city_text'];
                    $consignee_address_data['postal_code']=$request->step1['consignee']['postal_code'];
                    $consignee_address_data['barangay']=$request->step1['consignee']['barangay'];
                    $consignee_address_data['sectorate_no']=$request->step1['consignee']['sectorate_no'];
                    UserAddress::create($consignee_address_data);
                }else{
                    $consignee_id = $request->step1['consignee_id'];
                    $consignee_contact_no ='';
                    $consignee_contact_count=0;
                    if($request->has('step1.consignee_mobile_no')){
                        foreach($request->step1['consignee_mobile_no'] as $key=>$contact_no){

                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;
                        }
                    }
                    if($request->has('step1.consignee_telephone_no')){
                        foreach($request->step1['consignee_telephone_no'] as $key=>$contact_no){
                            if($consignee_contact_count<2){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                            $consignee_contact_count+=1;

                        }
                    }
                    Contact::where('contact_id',$consignee_id)->update([
                        'email'=>$request->step1['consignee_email']!=null ? $request->step1['consignee_email'] : '',
                        'contact_no'=>$consignee_contact_no
                    ]);


                }

                if($request->step1['consignee_address_id']=="new"){
                    $consignee_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                    $consignee_address_data['address_caption']='DEFAULT';
                    $consignee_address_data['user_id']= $request->step1['consignee_id'];
                    $consignee_address_data['street']=$request->step1['consignee']['street'];
                    $consignee_address_data['province']=$request->step1['consignee']['province'];
                    $consignee_address_data['city']=$request->step1['consignee']['city_text'];
                    $consignee_address_data['postal_code']=$request->step1['consignee']['postal_code'];
                    $consignee_address_data['barangay']=$request->step1['consignee']['barangay'];
                    $consignee_address_data['sectorate_no']=$request->step1['consignee']['sectorate_no'];
                    UserAddress::create($consignee_address_data);
                }

                $waybill_current = Waybill::where('reference_no',$id)->first(['shipper_id','consignee_id','pasabox']);
                ContactNumber::whereIn('contact_id',[$waybill_current['shipper_id'],$waybill_current['consignee_id']])->delete();
                WaybillContact::where('reference_no',$id)->delete();

                if($waybill_current->pasabox != $request->step1['pasabox'] ){
                    $prev_id=$id;
                    if($request->step1['pasabox']==1){
                        $id=str_replace("OL-","OLP-",$id);
                    }
                    elseif($request->step1['pasabox']==0){
                        $id=str_replace("OLP-","OL-",$id);
                    }
                    Waybill::where('reference_no',$prev_id)->update(['reference_no'=>$id]);
                }

                $waybill_data['shipper_id']=$request->step1['shipper_id']=="new" ? $shipper_data['contact_id'] : $request->step1['shipper_id'];
                $waybill_data['shipper_address_id']=$request->step1['shipper_id']=="new" || $request->step1['shipper_address_id']=="new" ? $shipper_address_data['useraddress_no'] : $request->step1['shipper_address_id'];
                $waybill_data['consignee_id']=$request->step1['consignee_id']=="new" ? $consignee_data['contact_id'] : $request->step1['consignee_id'];
                $waybill_data['consignee_address_id']=$request->step1['consignee_id']=="new" || $request->step1['consignee_address_id']=="new" ? $consignee_address_data['useraddress_no'] : $request->step1['consignee_address_id'];
                $waybill_data['prepared_by']=Auth::user()->contact_id;
                $waybill_data['transactiondate']=date('Y-m-d',strtotime(Carbon::now()));
                $waybill_data['prepared_datetime']=date('Y-m-d H:i:s',strtotime(Carbon::now()));
                $waybill_data['shipment_type']=$request->step2['shipment_type'];
                $waybill_data['destinationbranch_id']=$request->step2['destinationbranch_id'];
                $waybill_data['declared_value']=$request->step2['declared_value'];
                $waybill_data['payment_type']=$request->step2['payment_type'];
                $waybill_data['pickup']=$request->step2['pu_checkbox'];
                $waybill_data['delivery']=$request->step2['del_checkbox'];
                $waybill_data['pickup_sector_id']=$request->step2['pu_checkbox']==1 ? $request->step2['pu_sector'] : '';
                $waybill_data['delivery_sector_id']=$request->step2['del_checkbox']==1 ? $request->step2['del_sector'] : '';
                $waybill_data['pickup_date']=$request->step2['pu_checkbox']==1 ? date('Y-m-d',strtotime( $request->step2['pu_date'] )) : null;
                $waybill_data['pickup_sector_street']=$request->step2['pu_checkbox']==1 ? ($request->step2['pu_street'] != null ? $request->step2['pu_street'] :'') : '';
                $waybill_data['delivery_sector_street']=$request->step2['del_checkbox']==1 ? ($request->step2['del_street'] != null ? $request->step2['del_street'] :'') : '';
                $waybill_data['pasabox']=$request->step1['pasabox'];

                if( $request->step2['payment_type']=='CI' ){
                    $waybill_data['mode_payment']=$request->step2['mode_payment'];

                    if( $request->step2['mode_payment']==2 ){
                        $waybill_data['mode_payment_io']=$request->step2['mode_payment_io'];

                        if( $request->step2['mode_payment_io']==2 ){
                            $waybill_data['mode_payment_email']=$request->step2['mode_payment_email'];
                        }else{
                            $waybill_data['mode_payment_email']=NULL;
                        }
                    }else{
                        $waybill_data['mode_payment_io']=NULL;
                        $waybill_data['mode_payment_email']=NULL;
                    }
                }else{
                    $waybill_data['mode_payment']=NULL;
                    $waybill_data['mode_payment_io']=NULL;
                    $waybill_data['mode_payment_email']=NULL;
                }

                $waybill_data['pca_advance_payment']=$request->step2['pca_use_adv_payment'];
                $waybill_data['pca_account_no']=$request->step2['pca_no'] !='' ? $request->step2['pca_no'] : null;

                Waybill::where('reference_no',$id)->update($waybill_data);

                if($request->step1['pasabox']==1){



                    if($request->step1['shipping_cid']=="new"){

                        $shipping_company_contact_no="";

                        if($request->step1['shipping_cmobile_no'] !=''){
                            $shipping_company_contact_no .=$request->step1['shipping_cmobile_no'];
                        }

                        if($request->step1['shipping_clandline_no'] !=''){

                            if($shipping_company_contact_no !=''){
                                $shipping_company_contact_no .='/';
                            }

                            $shipping_company_contact_no .=$request->step1['shipping_clandline_no'];
                        }

                        $shipping_company_data['use_company']=1;
                        $shipping_company_data['company'] = $request->step1['shipping_cname'];
                        $shipping_company_data['email'] = $request->step1['shipping_cemail'] !=null ? $request->step1['shipping_cemail'] : '';
                        $shipping_company_data['contact_no']=$shipping_company_contact_no;
                        $shipping_company_data['fileas']=$request->step1['shipping_cname'];
                        $shipping_company_data['contact_id']=$this->generate_contact_id();

                        Contact::create($shipping_company_data);



                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$shipping_company_data['contact_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'pasabox'=>1
                        ]);

                    }else{
                        $shipping_company_data['contact_id']=$request->step1['shipping_cid'];
                    }

                    if( $shipping_company_data['contact_id'] !='' && $shipping_company_data['contact_id'] !='none'){
                        ContactNumber::where('contact_id',$shipping_company_data['contact_id'])->delete();
                        if($request->step1['shipping_cmobile_no'] !=''){

                            ContactNumber::create([
                                'contact_id'=>$shipping_company_data['contact_id'],
                                'contact_no'=>$request->step1['shipping_cmobile_no'],
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$request->step1['shipping_cmobile_no'],
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>3,
                                'reference_no'=>$id
                            ]);


                        }

                        if($request->step1['shipping_clandline_no'] !=''){

                            ContactNumber::create([
                                'contact_id'=>$shipping_company_data['contact_id'],
                                'contact_no'=>$request->step1['shipping_clandline_no'],
                                'type'=>2
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$request->step1['shipping_clandline_no'],
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>3,
                                'reference_no'=>$id
                            ]);

                        }

                        if( $request->step1['shipping_address_id']=="new" ){

                            $shipping_company_address_data['useraddress_no']="OLA-".$this->random_alph_num(2,3);
                            //$shipping_company_address_data['address_caption']='DEFAULT';
                            $shipping_company_address_data['user_id']= $shipping_company_data['contact_id'];
                            $shipping_company_address_data['street']=$request->step1['shipping_street'];
                            $shipping_company_address_data['province']=$request->step1['shipping_province'];
                            $shipping_company_address_data['city']=$request->step1['shipping_city_text'];
                            $shipping_company_address_data['postal_code']=$request->step1['shipping_postal_code'];
                            $shipping_company_address_data['barangay']=$request->step1['shipping_barangay'];
                            $shipping_company_address_data['sectorate_no']=$request->step1['shipping_sectorate_no'];
                            UserAddress::create($shipping_company_address_data);


                        }else{
                            $shipping_company_address_data['useraddress_no']= $request->step1['shipping_address_id'];
                        }

                    }
                    else{
                        $shipping_company_data['contact_id']=null;
                        $shipping_company_address_data['useraddress_no']=null;
                    }


                    Waybill::where('reference_no',$id)
                    ->update([
                        'shipping_company_id'=> $shipping_company_data['contact_id']=='' || $shipping_company_data['contact_id']=='none' ? null : $shipping_company_data['contact_id'] ,
                        'shipping_company_address_id'=> $shipping_company_address_data['useraddress_no']=='' || $shipping_company_address_data['useraddress_no']=='none' ? null : $shipping_company_address_data['useraddress_no'] ,
                        'shipping_company_cno'=>$request->step1['shipping_cmobile_no'],
                        'shipping_company_lno'=>$request->step1['shipping_clandline_no'],
                        'shipping_company_email'=>$request->step1['shipping_cemail'],
                        'pasabox_branch_receiver'=>$request->step2['pasabox_branch_receiver'],
                        'pasabox_cf_amt'=>$request->step5['gcash_amount']
                    ]);
                    if( $request->step5['pca_use_adv_payment_cf'] == 1){


                        Waybill::where('reference_no',$request->step4['reference_no'])
                        ->update([
                            'pca_pasabox_cf_advance_payment'=> 1
                        ]);

                        $adv_details=AdvancePayment::where('tbladvance_payment.pca_account_no',$request->step2['pca_no'])
                        ->where('tbladvance_payment.advance_payment_status',1)
                        ->whereRaw("tblorcrdetails.pasabox_cf=1 AND IFNULL(tblorcrdetails.pasabox_cf_ref_no,'') = '".$id."'")
                        ->leftJoin('waybill.tblorcrdetails','tbladvance_payment.reference_no','=','tblorcrdetails.reference_no')
                        ->selectRaw("tbladvance_payment.reference_no ")
                        ->first();
                        if($adv_details){

                            AdvancePayment::where('reference_no',$adv_details->reference_no)
                            ->where('pca_account_no',$request->step2['pca_no'])
                            ->update([
                                'deposit'=> $request->step5['gcash_amount']
                            ]);

                        }else{

                            $orcr_reference='CRONL'.date("y").''.$this->random_alph_num(3,2);

                            $orcr_details['reference_no']=$orcr_reference;
                            $orcr_details['pasabox_cf']=1;
                            $orcr_details['pasabox_cf_ref_no']=$request->step4['reference_no'];
                            $orcr_details['online_booking_ref']=$request->step4['reference_no'];
                            $orcr_details['transaction_date']=Carbon::now();
                            $orcr_details['advancepayment']=$request->step5['gcash_amount'];
                            $orcr_details['deposit']=$request->step5['gcash_amount'];
                            ORCRDetails::create($orcr_details);

                            $adv_payment_details['reference_no']=$orcr_reference;
                            $adv_payment_details['deposit']=$request->step5['gcash_amount'];
                            $adv_payment_details['pca_account_no']=$request->step2['pca_no'];
                            $adv_payment_details['prepared_datetime']=Carbon::now();
                            AdvancePayment::create($adv_payment_details);

                            $pasabox_to_receive['online_booking_ref']=$request->step4['reference_no'];
                            PasaboxReceive::create($pasabox_to_receive);

                            DB::table('waybill.tblpca_accounts_emailing')
                            ->insert([
                                'pca_account_no' => $request->step2['pca_no'],
                                'email_type'=>4,
                                'payment_ref'=>$orcr_reference
                            ]);

                        }


                    }else{

                        if(
                            OnlinePayment::where("onlinepayment_id", $request->step5['cf_onl_id'] )
                            //->where("confirmation_status", 1)
                            ->whereIn('confirmation_status',[1,2])
                            ->doesntExist()
                        )
                        {

                            OnlinePayment::where('onlinepayment_id',$request->step5['cf_onl_id'])->delete();
                            $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                            while(OnlinePayment::where('onlinepayment_id',$onlinepayment_id)->exists()){
                                $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                            }

                            $online_payment['onlinepayment_id']= $onlinepayment_id;
                            $online_payment['onlinepayment_date'] = $request->step5['gcash_pdate'];
                            $online_payment['for_confirmation_pdate'] = $request->step5['gcash_pdate'];
                            $online_payment['onlinepayment_amount']=$request->step5['gcash_amount'];
                            $online_payment['for_confirmation_amount']=$request->step5['gcash_amount'];
                            $online_payment['verification_code']=$request->step5['gcash_reference_no'];
                            $online_payment['gcash_added_datetime'] = Carbon::now();
                            $online_payment['for_confirmation']=1;
                            $online_payment['confirmation_status']=0;
                            $online_payment['pasabox_cf']=1;
                            if($request->step5['gcash_cemail'] !=''){
                                $online_payment['confirmation_email']=$request->step5['gcash_cemail'];
                            }
                            $online_payment['confirmation_branch']=$request->step2['pasabox_branch_receiver'];
                            $online_payment['confirmation_branch_account_name']=$request->step5['gcash_branch_aname'];
                            $online_payment['confirmation_branch_account_no']=$request->step5['gcash_branch_ano'];
                            $online_payment['bank_no']=$request->step5['gcash_id'];
                            OnlinePayment::create($online_payment);

                            $online_payment_confirmation['online_booking_ref']=$id;
                            $online_payment_confirmation['onlinepayment_id']=$onlinepayment_id;
                            OnlinePaymentConfirmation::create($online_payment_confirmation);
                        }
                    }
                }



                if($request->step1['shipper_id']=="new"){
                    if($request->has('step1.shipper.shipper_mobile_no')){
                        foreach($request->step1['shipper']['shipper_mobile_no'] as $contact_no){

                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                    if($request->has('step1.shipper.shipper_telephone_no')){
                        foreach($request->step1['shipper']['shipper_telephone_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                }else{
                    $shipper_contact_numbers="";
                    if($request->has('step1.shipper_mobile_no')){
                        foreach($request->step1['shipper_mobile_no'] as $key=>$contact_no){
                            $shipper_contact_numbers .= ($key>0 ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                    if($request->has('step1.shipper_telephone_no')){
                        foreach($request->step1['shipper_telephone_no'] as $contact_no){
                            $shipper_contact_numbers .= ($shipper_contact_numbers!="" ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['shipper_id'],
                                'contact_no'=>$contact_no,
                                'type'=>2
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                    if($request->step1['shipper_mname_update']==1){

                        $c_details=Contact::where('contact_id',$request->step1['shipper_id'])->get();
                        $new_fileas='';
                        $mname=strtoupper(trim($request->step1['shipper_mname_update_text']));
                        foreach($c_details as $c_data){
                            $lname=strtoupper(trim($c_data->lname));
                            $fname=strtoupper(trim($c_data->fname));
                            $new_fileas=$lname.", ".$fname." ".$mname;
                        }
                        Contact::where('contact_id',$request->step1['shipper_id'])->update([
                            'fileas'=>$new_fileas,
                            'mname'=>$mname
                        ]);

                    }

                    if(
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['shipper_id'])
                        ->where("shipper_consignee_status",1)
                        ->doesntExist()
                    )
                    {
                        $qr='';
                        if( $request->step1['shipper_qr_code'] !=''
                            &&
                            $request->step1['shipper_qr_code_cid'] == $request->step1['shipper_id']
                        ){
                            $qr=$request->step1['shipper_qr_code'];
                        }
                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$request->step1['shipper_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'qr_code'=> ($qr=='' ? NULL : $qr )
                        ]);

                    }


                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $request->step1['shipper_id'])
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                    if( $request->step1['shipper_qr_code'] !=''
                        &&
                        $request->step1['shipper_qr_code_cid'] == $request->step1['shipper_id']
                        &&
                        $sc->qr_code != $request->step1['shipper_qr_code']
                    ){
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['shipper_id'])
                        ->update([
                            'qr_code'=>$request->step1['shipper_qr_code']
                        ]);
                        $sc_qr_code=$request->step1['shipper_qr_code'];
                    }

                    if($sc_qr_code !=''){
                        Waybill::where('reference_no',$id)
                        ->update([
                            'shipper_qr_code'=>$sc_qr_code
                        ]);
                    }


                }

                if($request->step1['consignee_id']=="new"){
                    if($request->has('step1.consignee.consignee_mobile_no')){
                        foreach($request->step1['consignee']['consignee_mobile_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>1,
                                'reference_no'=>$id
                            ]);
                        }
                    }

                    if($request->has('step1.consignee.consignee_telephone_no')){
                        foreach($request->step1['consignee']['consignee_telephone_no'] as $contact_no){
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);

                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                }else{
                    $consignee_contact_numbers = "";
                    if($request->has('step1.consignee_mobile_no')){
                        foreach($request->step1['consignee_mobile_no'] as $key=>$contact_no){
                            $consignee_contact_numbers .= ($key>0 ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>1
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>1,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$id
                            ]);
                        }
                    }
                    if($request->has('step1.consignee_telephone_no')){
                        foreach($request->step1['consignee_telephone_no'] as $contact_no){
                            $consignee_contact_numbers .= ($consignee_contact_numbers!="" ? "/" : "").$contact_no;
                            ContactNumber::create([
                                'contact_id'=>$waybill_data['consignee_id'],
                                'contact_no'=>$contact_no,
                                'type'=>2
                            ]);
                            WaybillContact::create([
                                'waybill_contacts_no'=>$contact_no,
                                'waybill_contacts_no_type'=>2,
                                'waybill_shipper_consignee'=>2,
                                'reference_no'=>$id
                            ]);
                        }
                    }

                    if($request->step1['consignee_mname_update']==1){

                        $c_details=Contact::where('contact_id',$request->step1['consignee_id'])->get();
                        $new_fileas='';
                        $mname=strtoupper(trim($request->step1['consignee_mname_update_text']));
                        foreach($c_details as $c_data){
                            $lname=strtoupper(trim($c_data->lname));
                            $fname=strtoupper(trim($c_data->fname));
                            $new_fileas=$lname.", ".$fname." ".$mname;
                        }
                        Contact::where('contact_id',$request->step1['consignee_id'])->update([
                            'fileas'=>$new_fileas,
                            'mname'=>$mname
                        ]);

                    }

                    if(
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['consignee_id'])
                        ->where("shipper_consignee_status",1)
                        ->doesntExist()
                    )
                    {
                        $qr='';
                        if( $request->step1['consignee_qr_code'] !=''
                            &&
                            $request->step1['consignee_qr_code_cid'] == $request->step1['consignee_id']
                        ){
                            $qr=$request->step1['consignee_qr_code'];
                        }
                        ShipperConsignee::create([
                            'contact_id'=>Auth::user()->contact_id,
                            'shipper_consignee_id'=>$request->step1['consignee_id'],
                            'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                            'rider'=>0,
                            'qr_code'=> ($qr=='' ? NULL : $qr )
                        ]);


                    }


                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $request->step1['consignee_id'])
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                    if( $request->step1['consignee_qr_code'] !=''
                        &&
                        $request->step1['consignee_qr_code_cid'] == $request->step1['consignee_id']
                        &&
                        $sc->qr_code != $request->step1['consignee_qr_code']
                    ){
                        ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                        ->where("shipper_consignee_id", $request->step1['consignee_id'])
                        ->update([
                            'qr_code'=>$request->step1['consignee_qr_code']
                        ]);
                        $sc_qr_code=$request->step1['consignee_qr_code'];
                    }

                    if($sc_qr_code !=''){
                        Waybill::where('reference_no',$id)
                        ->update([
                            'consignee_qr_code'=>$sc_qr_code
                        ]);
                    }


                }

                $current_waybill_shipment_id = [];

                foreach($request->step3 as $ws){
                    $waybill_shipment_id = $ws['waybill_shipment_id']!=null ? $ws['waybill_shipment_id'] : 'K'.$this->random_num(6);
                    $ws_data = $ws;
                    $waybill_shipment = WaybillShipment::where(['waybill_shipment_id'=>$waybill_shipment_id])->first();

                    if($waybill_shipment){
                        WaybillShipment::where('waybill_shipment_id',$waybill_shipment_id)->update([
                            'item_code'=>$ws['item_code'],
                            'item_description'=>$ws['item_description'],
                            'unit_no'=>$ws['unit_no'],
                            'unit_description'=>$ws['unit_description'],
                            'quantity'=>$ws_data['quantity'],
                        ]);
                        WaybillShipmentMultiple::where('waybill_shipment_id',$waybill_shipment_id)->update([
                            'itemcode'=>$ws_data['item_code'],
                            'itemdescription'=>$ws_data['item_description'],
                            'quantity'=>$ws_data['quantity']
                        ]);
                    }else{
                        // $waybill_shipment->save();
                        WaybillShipment::create([
                            'waybill_shipment_id'=>$waybill_shipment_id,
                            'reference_no'=>$id,
                            'item_code'=>$ws['item_code'],
                            'item_description'=>$ws['item_description'],
                            'unit_no'=>$ws['unit_no'],
                            'unit_description'=>$ws['unit_description'],
                            'quantity'=>$ws_data['quantity'],
                            'freight_amount'=>0,
                            'weight'=>0,
                            'lenght'=>0,
                            'height'=>0,
                            'width'=>0,
                            'cargo_type_id'=>''
                        ]);
                        WaybillShipmentMultiple::create([
                            'waybill_shipment_id'=> $waybill_shipment_id,
                            'reference_no'=>$id,
                            'itemcode'=>$ws_data['item_code'],
                            'itemdescription'=>$ws_data['item_description'],
                            'quantity'=>$ws_data['quantity'],
                            'weight'=>0,
                            'lenght'=>0,
                            'height'=>0,
                            'width'=>0
                        ]);
                    }
                    array_push($current_waybill_shipment_id,$waybill_shipment_id);
                }
                WaybillShipment::where('reference_no',$id)->whereNotIn('waybill_shipment_id',$current_waybill_shipment_id)->delete();
                WaybillTrackAndTrace::create([
                    'trackandtrace_status'=>'EDITTED ONLINE BOOKING WITH REF#: '.$id.' EDITED BY '.Auth::user()->name,
                    'online_booking'=>$id
                ]);
                DB::commit();
                return response()->json(['title'=>'Success','message'=>'Booking successfully','type'=>'success'],200);
            }
        } catch (\Throwable $th) {
            //echo $e;

            DB::rollback();

            #RSD 10-04-2025 1035 -- use standard error log trait
            // $e_id=substr(sha1(mt_rand()),17,20);
            // DB::table('recordlogs.error_logs')
            // ->insert([
            //     'error_id'=>$e_id,
            //     'error_message'=>'ERROR IN SAVING WAYBILLS.',
            //     'error_description'=>$e->getMessage()
            // ]);
            // return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);

            $this->error_log($th,$request->url());
            $message = 'An unexpected error has occurred. Please take a screenshot of this message and share it with our customer service team so we can assist you promptly. Error Code: '.session('errorId');
            return response()->json(['message'=>$message,'type'=>'error'],200);
        }
    }



    public function updateNow(BookingRequest $request, $id)
    {
        $booking_status=Waybill::where('reference_no',$id)->first()->booking_status;

        if($booking_status==0){
            DB::transaction(function() use ($request,$id){
                try{
                    $shipper_contact_no ='';
                    if($request->has('step1.shipper.shipper_mobile_no')){
                        foreach($request->step1['shipper']['shipper_mobile_no'] as $key=>$contact_no){
                            $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                        }
                    }
                    if($request->has('step1.shipper.shipper_telephone_no')){
                        foreach($request->step1['shipper']['shipper_telephone_no'] as $key=>$contact_no){
                            $shipper_contact_no.= ($shipper_contact_no!="" ? '/' : '').$contact_no;

                        }
                    }
                    $shipper = [
                        'use_company'=>$request->step1['shipper']['use_company'],
                        'lname'=>$request->step1['shipper']['use_company']==1 ? '' : $request->step1['shipper']['lname'],
                        'fname'=>$request->step1['shipper']['use_company']==1 ? '' :$request->step1['shipper']['fname'],
                        'mname'=>$request->step1['shipper']['mname']!=null ? $request->step1['shipper']['mname'] : '',
                        'company'=>$request->step1['shipper']['company']!=null ? $request->step1['shipper']['company'] : '',
                        'email'=>$request->step1['shipper']['email']!=null ? $request->step1['shipper']['email'] : '',
                        'business_category_id'=>$request->step1['shipper']['business_category_id'] != null && $request->step1['shipper']['business_category_id'] != 'none' && $request->step1['shipper']['business_category_id'] != '' ? $request->step1['shipper']['business_category_id']: 0,
                        'contact_no'=>$shipper_contact_no
                    ];

                    $shipper_address = [
                        'street'=>$request->step1['shipper']['street'],
                        'barangay'=>$request->step1['shipper']['barangay'],
                        'sectorate_no'=>$request->step1['shipper']['sectorate_no']
                    ];

                    $shipper_id=null; $shipper_address_id=null;

                    if($request->step1['shipper_id']=="new"){
                        $shipper_added_columns = [
                            'fileas'=>$request->step1['shipper']['use_company']==1 ? $request->step1['shipper']['company'] : $request->step1['shipper']['lname'].', '.$request->step1['shipper']['fname'].($request->step1['shipper']['mname']!=null ? ' '.$request->step1['shipper']['mname'] : ''),
                            'contact_id'=>$this->generate_contact_id()
                        ];

                        $shipper = array_merge($shipper,$shipper_added_columns);
                        $shipper_info = Contact::create($shipper);
                        $shipper_id = $shipper_info->contact_id;
                        ShipperConsignee::create(['contact_id'=>Auth::user()->contact_id,
                                        'shipper_consignee_id'=>$shipper_id,
                                        'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                        'rider'=>0
                                        ]);
                        $shipper_city_data=$this->city_data($request->step1['shipper']['city']);
                        $shipper_address_added_columns = [
                            'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                            'user_id'=>$shipper_id,
                            'city'=>$shipper_city_data['cities_name'],
                            'province'=>$shipper_city_data['province']['province_name'],
                            'postal_code'=>$shipper_city_data['postal_code'],
                        ];
                        $shipper_address = array_merge($shipper_address,$shipper_address_added_columns);
                        $shipper_address_info = UserAddress::create($shipper_address);
                        $shipper_address_id = $shipper_address_info->useraddress_no;
                    }else{
                        $shipper_id = $request->step1['shipper_id'];
                        $shipper_contact_no ='';
                        if($request->has('step1.shipper_mobile_no')){
                            foreach($request->step1['shipper_mobile_no'] as $key=>$contact_no){
                                $shipper_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                        }
                        if($request->has('step1.shipper_telephone_no')){
                            foreach($request->step1['shipper_telephone_no'] as $key=>$contact_no){
                                $shipper_contact_no.= ($shipper_contact_no!="" ? '/' : '').$contact_no;

                            }
                        }
                        Contact::where('contact_id',$shipper_id)->update([
                            'email'=>$request->step1['shipper_email']!=null ? $request->step1['shipper_email'] : '',
                            'contact_no'=>$shipper_contact_no
                        ]);
                        if($request->step1['shipper_address_id']=="new"){
                            $shipper_city_data=$this->city_data($request->step1['shipper']['city']);
                            $shipper_address_added_columns = [
                                'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                                'user_id'=>$shipper_id,
                                'city'=>$shipper_city_data['cities_name'],
                                'province'=>$shipper_city_data['province']['province_name'],
                                'postal_code'=>$shipper_city_data['postal_code'],
                            ];
                            $shipper_address = array_merge($shipper_address,$shipper_address_added_columns);
                            $shipper_address_info = UserAddress::create($shipper_address);
                            $shipper_address_id = $shipper_address_info->useraddress_no;
                        }else{
                            $shipper_address_id = $request->step1['shipper_address_id'];
                        }
                    }

                    $consignee_contact_no ='';
                    if($request->has('step1.consignee.consignee_mobile_no')){
                        foreach($request->step1['consignee']['consignee_mobile_no'] as $key=>$contact_no){
                            $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                        }
                    }
                    if($request->has('step1.consignee.consignee_telephone_no')){
                        foreach($request->step1['consignee']['consignee_telephone_no'] as $key=>$contact_no){
                            $consignee_contact_no.= ($consignee_contact_no!="" ? '/' : '').$contact_no;

                        }
                    }
                    $consignee = [
                        'use_company'=>$request->step1['consignee']['use_company'],
                        'lname'=>$request->step1['consignee']['use_company']==1 ? '' : $request->step1['consignee']['lname'],
                        'fname'=>$request->step1['consignee']['use_company']==1 ? '' : $request->step1['consignee']['fname'],
                        'mname'=>$request->step1['consignee']['mname']!=null ? $request->step1['consignee']['mname'] : '',
                        'company'=>$request->step1['consignee']['company']!=null ? $request->step1['consignee']['company'] : '',
                        'email'=>$request->step1['consignee']['email']!=null ? $request->step1['consignee']['email'] : '',
                        'business_category_id'=>$request->step1['consignee']['business_category_id'] != null && $request->step1['consignee']['business_category_id'] != 'none' && $request->step1['consignee']['business_category_id'] != '' ? $request->step1['consignee']['business_category_id']: 0,
                        'contact_no'=>$consignee_contact_no
                    ];

                    $consignee_address = [
                        'street'=>$request->step1['consignee']['street'],
                        'barangay'=>$request->step1['consignee']['barangay'],
                        'sectorate_no'=>$request->step1['consignee']['sectorate_no']
                    ];

                    $consignee_id=null; $consignee_address_id = null;

                    if($request->step1['consignee_id']=="new"){
                        $consignee_added_columns = [
                            'fileas'=>$request->step1['consignee']['use_company']==1 ? $request->step1['consignee']['company'] : $request->step1['consignee']['lname'].', '.$request->step1['consignee']['fname'].($request->step1['consignee']['mname']!=null ? ' '.$request->step1['consignee']['mname'] : ''),
                            'contact_id'=>$this->generate_contact_id()
                        ];

                        $consignee = array_merge($consignee,$consignee_added_columns);
                        $consignee_info = Contact::create($consignee);
                        $consignee_id = $consignee_info->contact_id;
                        ShipperConsignee::create(['contact_id'=>Auth::user()->contact_id,
                                        'shipper_consignee_id'=>$consignee_id,
                                        'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                        'rider'=>0
                                        ]);
                        $consignee_city_data=$this->city_data($request->step1['consignee']['city']);
                        $consignee_address_added_columns = [
                            'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                            'user_id'=>$consignee_id,
                            'city'=>$consignee_city_data['cities_name'],
                            'province'=>$consignee_city_data['province']['province_name'],
                            'postal_code'=>$consignee_city_data['postal_code'],
                        ];
                        $consignee_address = array_merge($consignee_address,$consignee_address_added_columns);
                        $consignee_address_info = UserAddress::create($consignee_address);
                        $consignee_address_id = $consignee_address_info->useraddress_no;
                    }else{
                        $consignee_id = $request->step1['consignee_id'];
                        $consignee_contact_no ='';
                        if($request->has('step1.consignee_mobile_no')){
                            foreach($request->step1['consignee_mobile_no'] as $key=>$contact_no){
                                $consignee_contact_no.= ($key>0 ? '/' : '').$contact_no;
                            }
                        }
                        if($request->has('step1.consignee_telephone_no')){
                            foreach($request->step1['consignee_telephone_no'] as $key=>$contact_no){
                                $consignee_contact_no.= ($shipper_contact_no!="" ? '/' : '').$contact_no;

                            }
                        }
                        Contact::where('contact_id',$consignee_id)->update([
                            'email'=>$request->step1['consignee_email']!=null ? $request->step1['consignee_email'] : '',
                            'contact_no'=>$consignee_contact_no
                        ]);
                        if($request->step1['consignee_address_id']=="new"){
                            $consignee_city_data=$this->city_data($request->step1['consignee']['city']);
                            $consignee_address_added_columns = [
                                'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                                'user_id'=>$consignee_id,
                                'city'=>$consignee_city_data['cities_name'],
                                'province'=>$consignee_city_data['province']['province_name'],
                                'postal_code'=>$consignee_city_data['postal_code'],
                            ];
                            $consignee_address = array_merge($consignee_address,$consignee_address_added_columns);
                            $consignee_address_info = UserAddress::create($shipper_address);
                            $consignee_address_id = $consignee_address_info->useraddress_no;
                        }else{
                            $consignee_address_id = $request->step1['consignee_address_id'];
                        }
                    }

                    $waybill_data = [
                        'shipper_id'=>$shipper_id,
                        'shipper_address_id'=>$shipper_address_id,
                        'consignee_id'=>$consignee_id,
                        'consignee_address_id'=>$consignee_address_id,
                        'shipment_type'=>$request->step2['shipment_type'],
                        'destinationbranch_id'=>$request->step2['destinationbranch_id'],
                        'declared_value'=>$request->step2['declared_value'],
                        'payment_type'=>$request->step2['payment_type'],
                        'pickup'=>$request->step2['pu_checkbox'],
                        'delivery'=>$request->step2['del_checkbox'],
                        'pickup_sector_id'=>$request->step2['pu_checkbox']==1 ? $request->step2['pu_sector'] : '',
                        'delivery_sector_id'=>$request->step2['del_checkbox']==1 ? $request->step2['del_sector'] : '',
                        'pickup_date'=>$request->step2['pu_checkbox']==1 ? date('Y-m-d',strtotime( $request->step2['pu_date'] )) : null,
                        'pickup_sector_street'=>$request->step2['pu_checkbox']==1 ? ($request->step2['pu_street'] != null ? $request->step2['pu_street'] :'') : '',
                        'delivery_sector_street'=>$request->step2['del_checkbox']==1 ? ($request->step2['del_street'] != null ? $request->step2['del_street'] :'') : '',
                        'discount_coupon'=>''
                    ];

                    Waybill::where('reference_no',$id)->update($waybill_data);
                    WaybillTrackAndTrace::create([
                        'trackandtrace_status'=>'EDITTED ONLINE BOOKING WITH REF#: '.$id.' EDITED BY '.Auth::user()->name,
                        'online_booking'=>$id
                    ]);
                    // $waybill=Waybill::create($waybill_data);
                    WaybillShipment::where('reference_no',$id)->delete();
                    WaybillShipmentMultiple::where('reference_no',$id)->delete();
                    WaybillContact::where('reference_no',$id)->delete();
                    ContactNumber::whereIn('contact_id',[$shipper_id,$consignee_id])->delete();
                    if($request->step1['shipper_id']=="new"){
                        if($request->has('step1.shipper.shipper_mobile_no')){
                            foreach($request->step1['shipper']['shipper_mobile_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$shipper_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);

                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>1,
                                    'waybill_shipper_consignee'=>1,
                                    'reference_no'=>$id,
                                ]);
                            }
                        }
                        if($request->has('step1.shipper.shipper_telephone_no')){
                            foreach($request->step1['shipper']['shipper_telephone_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$shipper_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);
                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>2,
                                    'waybill_shipper_consignee'=>1,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                    }else{
                        if($request->has('step1.shipper_mobile_no')){
                            foreach($request->step1['shipper_mobile_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$shipper_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);
                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>1,
                                    'waybill_shipper_consignee'=>1,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                        if($request->has('step1.shipper_telephone_no')){
                            foreach($request->step1['shipper_telephone_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$shipper_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>2
                                ]);
                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>2,
                                    'waybill_shipper_consignee'=>1,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                    }

                    if($request->step1['consignee_id']=="new"){
                        if($request->has('step1.consignee.consignee_mobile_no')){
                            foreach($request->step1['consignee']['consignee_mobile_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$consignee_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);

                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>1,
                                    'waybill_shipper_consignee'=>1,
                                    'reference_no'=>$id
                                ]);
                            }
                        }

                        if($request->has('step1.consignee.consignee_telephone_no')){
                            foreach($request->step1['consignee']['consignee_telephone_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$consignee_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);

                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>2,
                                    'waybill_shipper_consignee'=>2,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                    }else{
                        if($request->has('step1.consignee_mobile_no')){
                            foreach($request->step1['consignee_mobile_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$consignee_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>1
                                ]);
                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>1,
                                    'waybill_shipper_consignee'=>2,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                        if($request->has('step1.consignee_telephone_no')){
                            foreach($request->step1['consignee_telephone_no'] as $contact_no){
                                ContactNumber::create([
                                    'contact_id'=>$consignee_id,
                                    'contact_no'=>$contact_no,
                                    'type'=>2
                                ]);
                                WaybillContact::create([
                                    'waybill_contacts_no'=>$contact_no,
                                    'waybill_contacts_no_type'=>2,
                                    'waybill_shipper_consignee'=>2,
                                    'reference_no'=>$id
                                ]);
                            }
                        }
                    }

                    $unit = $request->step3['unit']; $quantity = $request->step3['quantity'];
                    foreach($request->step3['item_description'] as $key=>$item){
                        $waybill_shipment_id = 'K'.$this->random_num(6);
                        WaybillShipment::create([
                            'waybill_shipment_id'=> $waybill_shipment_id,
                            'reference_no'=>$id,
                            'item_code'=>$item,
                            'item_description'=>$this->stock_description($item),
                            'unit_no'=>$unit[$key],
                            'unit_description'=>$this->unit_description($unit[$key]),
                            'quantity'=>$quantity[$key],
                            'freight_amount'=>0,
                            'weight'=>0,
                            'lenght'=>0,
                            'height'=>0,
                            'width'=>0,
                            'cargo_type_id'=>''
                        ]);

                        WaybillShipmentMultiple::create([
                            'waybill_shipment_id'=> $waybill_shipment_id,
                            'reference_no'=>$id,
                            'itemcode'=>$item,
                            'itemdescription'=>$this->stock_description($item),
                            'quantity'=>$quantity[$key],
                            'weight'=>0,
                            'lenght'=>0,
                            'height'=>0,
                            'width'=>0
                        ]);
                        $this->title='Success!';
                        $this->message='Booking has been updated';
                        $this->type = 'success';

                    }
                }catch(Exception $e){
                    DB::rollBack();
                    $this->title='Ooops!';
                    $this->message='Your booking is unsuccessful!';
                    $this->return_data=null;
                    $this->type = 'error';
                }
            });
            return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type],200);
        }else{
            return response()->json(['title'=>'Ooops!','message'=>'Unable to update, booking is already transacted','type'=>'error'],200);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->title='';
        $this->message='';
        $this->type='';
        DB::transaction(function() use($id){
            try{
                $waybill_shipments_multiple = WaybillShipmentMultiple::where('reference_no',$id)->delete();
                $waybill_shipments = WaybillShipment::where('reference_no',$id)->delete();
                $waybill = Waybill::where('reference_no',$id)->delete();

                if(!$waybill_shipments_multiple || !$waybill_shipments || !$waybill){
                    DB::rollBack();
                    $this->message='No rows affected!';
                }else{
                     DB::commit();
                    $this->message='Booking has been deleted';
                }
                 $this->title='Success!';
                $this->type='success';

            }catch(Exception $e){
            DB::rollBack();
            $this->title='Ooops!';
            $this->message='There something wrong!<br>No rows affected';
            $this->type='error';
            }
        });

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
    }
    public function check_discount_coupon(Request $request){

        $request->discount_coupon=base64_decode($request->discount_coupon);

        $request->s_dc_fname=base64_decode($request->s_dc_fname);
        $request->s_dc_mname=base64_decode($request->s_dc_mname);
        $request->s_dc_lname=base64_decode($request->s_dc_lname);
        $request->c_dc_fname=base64_decode($request->c_dc_fname);
        $request->c_dc_mname=base64_decode($request->c_dc_mname);
        $request->c_dc_lname=base64_decode($request->c_dc_lname);

        $discount_coupon = DiscountCoupon::where('tbldiscount_coupon.discount_coupon_no',$request->discount_coupon)
        ->leftJoin("waybill.tbldiscount_coupon_released","tbldiscount_coupon.discount_coupon_no","=","tbldiscount_coupon_released.discount_coupon_no")
        ->where('tbldiscount_coupon.validity_from','<=',date('Y-m-d',strtotime(Carbon::now())))
        ->where('tbldiscount_coupon.validity_to','>=',date('Y-m-d',strtotime(Carbon::now())))
        ->whereRaw("
        tbldiscount_coupon_released.discount_coupon_released_id IS NOT NULL
        AND
        (
            (
                UPPER(tbldiscount_coupon_released.fname)='".strtoupper($request->s_dc_fname)."'
                AND UPPER(tbldiscount_coupon_released.lname)='".strtoupper($request->s_dc_lname)."'
            )
            OR
            (
                UPPER(tbldiscount_coupon_released.fname)='".strtoupper($request->c_dc_fname)."'
                AND UPPER(tbldiscount_coupon_released.lname)='".strtoupper($request->c_dc_lname)."'
            )
        )
        ")
        ;

        $result['data']=array();
        $result['branch']=array();
        $result['details']=array();
        $result['msg']='';
        $result['action']='';

        if($discount_coupon->count()>0){

            $discount_coupon_branch =DB::table('waybill.tbldiscount_coupon_branch')
            ->where('tbldiscount_coupon_branch.discount_coupon_no',$request->discount_coupon)
            ->leftjoin('doff_configuration.tblbranchoffice','tbldiscount_coupon_branch.branchoffice_no','=','tblbranchoffice.branchoffice_no')
            ->selectRaw("tblbranchoffice.branchoffice_description");

            $discount_coupon_details =DB::table('waybill.tbldiscount_coupon_details')
            ->where('tbldiscount_coupon_details.discount_coupon_no',$request->discount_coupon)
            ;

            if($discount_coupon->first()->one_time_use==1){

                $chk_coupon = Waybill::where('discount_coupon',$request->discount_coupon);
                $chk_coupon_adj = DB::table('waybill.tbladjustment')->where('discount_coupon',$request->discount_coupon);
                $chk_coupon_ol = DB::table('dailyove_online_site.tblwaybills')
                ->where('booking_status','!=',3)
                ->where('discount_coupon',$request->discount_coupon);

                if(
                    $chk_coupon->count() > 0
                    || $chk_coupon_adj->count() > 0
                    || $chk_coupon_ol-> count() > 0
                ){
                    $result['msg']='Invalid Coupon. Coupon code already used.';
                    $result['action']=0;
                }else{
                    $result['msg']='';
                    $result['action']=1;
                    $result['data']=$discount_coupon->get();
                    $result['branch']=$discount_coupon_branch->get();
                    $result['details']=$discount_coupon_details->get();
                }
            }else{
                $result['msg']='';
                $result['action']=1;
                $result['data']=$discount_coupon->get();
                $result['branch']=$discount_coupon_branch->get();
                $result['details']=$discount_coupon_details->get();
            }
        }else{
            $result['msg']='Invalid Coupon.';
            $result['action']=0;
        }
        echo json_encode($result);
    }
    public function verify_discount_coupon(Request $request){

        $discount_coupon = DiscountCoupon::where('discount_coupon_no',$request->discount_coupon)
        ->where('validity_from','<=',date('Y-m-d',strtotime(Carbon::now())))
        ->where('validity_to','>=',date('Y-m-d',strtotime(Carbon::now())));

        $title='';
        $message='';
        $type='';

        if($discount_coupon->count()>0){
            if($discount_coupon->first()->one_time_use==1){
                $chk_coupon = Waybill::where('discount_coupon',$request->discount_coupon);
                $chk_coupon_adj = DB::table('waybill.tbladjustment')->where('discount_coupon',$request->discount_coupon);
                $chk_coupon_ol = DB::table('dailyove_online_site.tblwaybills')
                ->where('booking_status','!=',3)
                ->where('discount_coupon',$request->discount_coupon);

                if(
                    $chk_coupon->count() > 0
                    || $chk_coupon_adj->count() > 0
                    || $chk_coupon_ol-> count() > 0
                ){
                    return "false";
                }else{
                    return "true";
                }
            }else{
                return "true";
            }
        }else{
            return "false";
        }
    }

    public function verify($id){
        $discount_coupon = DiscountCoupon::where('discount_coupon_no',$id)->where('validity_from','<=',date('Y-m-d',strtotime(Carbon::now())))->where('validity_to','>=',date('Y-m-d',strtotime(Carbon::now())));
        $title='';$message='';$type='';
        if($discount_coupon->count()>0){
                if($discount_coupon->first()->one_time_use==1){
                    $chk_coupon = RefWaybill::where('discount_coupon',$id);
                    if($chk_coupon->count()>0){
                        $title="Ooops!";
                        $message="Invalid discount coupon number";
                        $type='error';
                    }else{
                         $title="Success!";
                         $message="Verified";
                         $type='success';
                    }
                }else{
                     $title="Success!";
                     $message="Verified";
                     $type='success';

                }
            }else{
                $title="Ooops!";
                $message="Invalid discount coupon number";
                $type='error';
        }
        return ['title'=>$title,'message'=>$message,'type'=>$type];
    }

    public function search(Request $request){
        $request->merge(['datefrom'=>date('Y-m-d',strtotime($request->datefrom)),'dateto'=>date('Y-m-d',strtotime($request->dateto))]);
        echo json_encode(Waybill::where('prepared_by',auth()->user()->contact_id)->whereBetween('transactiondate',[$request->datefrom,$request->dateto])->with(['shipper','shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee','consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->orderBy('transactiondate','DESC')->get()->toArray());
    }

    public function print($id){
        $id = Crypt::decrypt($id);
        // $id = Auth::check() ? $id : $this->encrypt_decrypt('decrypt',$id);


        $data = Waybill::where('reference_no',$id)
                        ->with([

                            'shipper',
                            'consignee',
                            'branch',
                            'waybill_shipment',
                            'shipper_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'consignee_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            }
                            ])->first();

        if($data['pasabox']==1){
            $term_data = 'pasabox';
        }else{
            $term_data = 'online-booking';
        }
        $pdf_data =[
            'term'=>Term::where('type',$term_data)->first(),
            'data'=>Waybill::where('reference_no',$id)
                ->with([
                'shipping_company',
                'shipper',
                'consignee',
                'branch',
                'branch_receiver'=>function($query){
                    $query->with('pasabox_authorized_employee');
                },
                'waybill_shipment',
                'charge_to_data',
                'pick_up_sector',
                'delivery_sector',
                'shipping_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'shipper_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'consignee_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },

                ])->first(),

            'shipper_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',1)->pluck('waybill_contacts_no'),
            'consignee_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',2)->pluck('waybill_contacts_no'),
            'shipping_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',3)->pluck('waybill_contacts_no'),
        ];

        $waybill_data = Waybill::where('reference_no',$id)
        ->with([
        'shipper',
        'consignee',
        'branch',
        'waybill_shipment',
        'charge_to_data',
        'shipper_address'=>function($query){
            $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        },
        'consignee_address'=>function($query){
            $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        }
        ])->first();

        return view('waybills.print_layout',$pdf_data);


    }

    public function print_label($id){
        $id = Crypt::decrypt($id);
        // $id = Auth::check() ? $id : $this->encrypt_decrypt('decrypt',$id);


        $data = Waybill::where('reference_no',$id)
                        ->with([

                            'shipper',
                            'consignee',
                            'branch',
                            'waybill_shipment',
                            'shipper_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'consignee_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            }
                            ])->first();

        if($data['pasabox']==1){
            $term_data = 'pasabox';
        }else{
            $term_data = 'online-booking';
        }
        $pdf_data =[
            'term'=>Term::where('type',$term_data)->first(),
            'data'=>Waybill::where('reference_no',$id)
                ->with([
                'shipping_company',
                'shipper',
                'consignee',
                'branch',
                'branch_receiver'=>function($query){
                    $query->with('pasabox_authorized_employee');
                },
                'waybill_shipment',
                'charge_to_data',
                'pick_up_sector',
                'delivery_sector',
                'shipping_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'shipper_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },
                'consignee_address'=>function($query){
                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                },

                ])->first(),

            'shipper_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',1)->pluck('waybill_contacts_no'),
            'consignee_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',2)->pluck('waybill_contacts_no'),
            'shipping_contact' => WaybillContact::where('reference_no',$id)->where('waybill_shipper_consignee',3)->pluck('waybill_contacts_no'),
        ];

        $waybill_data = Waybill::where('reference_no',$id)
        ->with([
        'shipper',
        'consignee',
        'branch',
        'waybill_shipment',
        'charge_to_data',
        'shipper_address'=>function($query){
            $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        },
        'consignee_address'=>function($query){
            $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        }
        ])->first();

        return view('waybills.print_label',$pdf_data);


    }

    public function track_and_trace(Request $request){

        $arr_data = [];

        $arr_data['url_text']='';

        if($request->tracking_no!='' && $request->search_type=='tracking'){

            $unique_URL = UnclaimedCallLogs::where('unique_url',$request->tracking_no)->first();

            if($unique_URL != '' && $unique_URL != null ){
                $arr_data['url_text']='https://track.dailyoverland.com/'.$unique_URL->unique_url;
            }

        }

        if( $arr_data['url_text'] == ''){

            $validation=$request->search_type=='tracking' ? Validator::make($request->only('tracking_no'),['tracking_no'=>'required'],['tracking_no.required'=>'Tracking number is required']) : Validator::make($request->only(['name','waybill_no']),['name'=>'required','waybill_no'=>'required'],['name.required'=>'Name of shipper/consignee is required','waybill_no.required'=>'Waybill # is required']);


            if($validation->passes()){

                $data = null;
                if($request->tracking_no!='' && $request->search_type=='tracking'){
                    $transactioncodes = RefWaybill::where('tracking_no',$request->tracking_no)->pluck('transactioncode')->toArray();
                    $data = TrackAndTrace::whereIn('transactioncode',$transactioncodes)
                    ->where(function($query){ $query->whereIn(DB::raw('LEFT(remarks,8)'),['RECEIVED','ACCEPTED'])
                    ->orWhere(DB::raw('LEFT(remarks,7)'),'CLAIMED'); })->orderBy('track_trace_date','DESC');

                }
                else{
                    $transactioncodes = RefWaybill::where('waybill_no',$request->waybill_no)->where(function($query) use($request){
                        $query->whereHas('consignee',function($query) use($request){ $query->where('fileas',$request->name); })
                            ->orWhereHas('shipper',function($query) use($request){ $query->where('fileas',$request->name); });
                    })->pluck('transactioncode')->toArray();

                    //$data = TrackAndTrace::whereIn('transactioncode',$transactioncodes)
                    //->orderBy('track_trace_date','DESC');

                    $data = TrackAndTrace::whereIn('transactioncode',$transactioncodes)
                    ->where(function($query){ $query->whereIn(DB::raw('LEFT(remarks,8)'),['RECEIVED','ACCEPTED'])
                    ->orWhere(DB::raw('LEFT(remarks,7)'),'CLAIMED'); })->orderBy('track_trace_date','DESC');
                }

                //print_r($transactioncodes);
                if($data->count()>0){
                    $d = $data->first();
                    $arr_data['contact_id'] = $d['contact_id'];
                    $arr_data['remarks'] = strpos($d['remarks'],'RECEIVED')!==false ? 'ARRIVED AT DESTINATION BRANCH' : "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
                    $arr_data['track_trace_date'] = $d['track_trace_date'];
                    $arr_data['transactioncode'] = $d['transactioncode'];
                }else{
                    $arr_data['remarks'] = "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
                }
            }else{
                $arr_data['remarks'] = "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
            }
        }
        echo json_encode($arr_data);


    }

    public function track_by_reference(Request $request){
        $validation =  Validator::make($request->all(),[
            'reference_no'=>'required|exists:waybill.tblwaybills,reference_no'
        ]);
        $title='';
        $message = '';
        $type = '';
        if($validation->fails()){
            $title='Ooops!';
            $message = "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
            $type = 'error';
        }else{

            //$transactioncodes = RefWaybill::where('reference_no',$request->reference_no)->pluck('transactioncode')->toArray();
            //$data = TrackAndTrace::whereIn('transactioncode',$transactioncodes)->orderBy('track_trace_date','DESC')->first();
            //$data = WaybillTrackAndTrace::whereIn('transaction_code',$transactioncodes)->orderBy('trackandtrace_datetime','DESC')->first();
            //$data = TrackAndTrace::whereIn('online_booking',$request->reference_no)->orderBy('track_trace_date','DESC')->first();
            $data = TrackAndTrace::where('online_booking',$request->reference_no)->orderBy('track_trace_date','DESC')->first();
            if($data){
                $message = strpos($data['remarks'],'RECEIVED')!==false ? 'ARRIVED AT DESTINATION BRANCH' : "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
                if(isset($data['remarks'])){
                    $title='Success!';
                    $message = strpos($data['remarks'],'RECEIVED AT')!==false ? 'ARRIVED AT DESTINATION BRANCH' : "Sorry. No record found please contact Customer Service at our FB page https://www.facebook.com/dailyoverland";
                    $type = 'success';
                }else{
                    $title='Ooops!';
                    $message = "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
                    $type = 'error';
                }
            }else{
                $title='Ooops!';
                $message = "Sorry. No record found please contact Customer Service at our FB page <a href='https://www.facebook.com/dailyoverland/' target='_blank' >https://www.facebook.com/dailyoverland/</a>";
                $type = 'error';
            }

        }

        return ['title'=>$title,'message'=>$message,'type'=>$type];
    }
    public function pca_save_application(Request $request){

        try{

            DB::beginTransaction();
            $no=$request->pca_application_pcno;
            $vat=0;
            if(isset($request->add_account_vat)){
                $vat=1;
            }
            $bir_2306=0;
            if(isset($request->add_account_bir_2306)){
                $bir_2306=1;
            }
            $bir_2307=0;
            if(isset($request->add_account_bir_2307)){
                $bir_2307=1;
            }

            DB::table('waybill.tblpca_accounts')
                ->where("pca_account_no",$no)
                ->update([
                    'address_no'=>$request->add_account_brgy,
                    'address_street'=>strtoupper($request->add_account_street),
                    'contact_person'=>strtoupper($request->add_account_cperson),
                    'contact_no'=>$request->add_account_cno,
                    'vat'=>$vat,
                    'bir_2306'=>$bir_2306,
                    'bir_2307'=>$bir_2307,
                    'tin_no'=>$request->add_account_tin
                ]);

            DB::table('waybill.tblpca_account_application_req')
            ->where("pca_account_no",$no)
            ->update([
                'pca_account_application_req_status'=>2
            ]);
            DB::table('waybill.tblpca_account_application_req_files')
            ->whereRaw("
            pca_account_application_req_id IN
            (SELECT pca_account_application_req_id FROM waybill.tblpca_account_application_req WHERE pca_account_no='".$no."' )
            ")
            ->update([
                'pca_account_application_req_files_status'=>2
            ]);

            if(isset($request->add_account_req)){
                foreach($request->add_account_req as $req_id){
                    $req_name=$request['add_account_req_name_'.$req_id];

                    $existing_req=DB::table('waybill.tblpca_account_application_req')
                    ->where("pca_account_no",$no)
                    ->where("pca_requirements_id",$req_id)
                    ->first();
                    if($existing_req){
                        $pca_account_application_req_id=$existing_req->pca_account_application_req_id;
                        DB::table('waybill.tblpca_account_application_req')
                        ->where('pca_account_application_req_id',$pca_account_application_req_id)
                        ->update([
                            'pca_account_application_req_status' => 1,
                            'pca_requirements_name' => $req_name
                        ]);
                    }else{
                        DB::table('waybill.tblpca_account_application_req')
                        ->insert([
                            'pca_account_no' => $no,
                            'pca_requirements_id' => $req_id,
                            'pca_requirements_name' => $req_name
                        ]);
                        $pca_account_application_req_id=DB::getPdo()->lastInsertId();
                    }

                    if(isset($request['add_account_req_file_id_'.$req_id])){
                        foreach($request['add_account_req_file_id_'.$req_id] as $id_file){
                            if($id_file !=''){
                                DB::table('waybill.tblpca_account_application_req_files')
                                ->where('pca_account_application_req_files_id',$id_file)
                                ->update([
                                    'pca_account_application_req_files_status' => 1
                                ]);
                            }

                        }
                    }

                    if(isset($request['add_account_req_file_'.$req_id])){

                            $images = $request->file('add_account_req_file_'.$req_id);

                            if(!Storage::disk('system_files')->exists('system_files/PCA/'))
                            {
                                Storage::disk('system_files')->makeDirectory('system_files/PCA/', 0777, true);
                            }

                            if(!Storage::disk('system_files')->exists('system_files/PCA/'.$no))
                            {
                                Storage::disk('system_files')->makeDirectory('system_files/PCA/'.$no, 0777, true);
                            }

                            foreach($images  as $file){

                                if( $file != '' ){

                                    $name=$file->getClientOriginalName();
                                    $name = pathinfo($name, PATHINFO_FILENAME);
                                    $name=preg_replace('/\s+/', '', $name);

                                    $data=imagejpeg(
                                        imagecreatefromstring(
                                            file_get_contents($file->getPathName())
                                        ),
                                        storage_path('app/system_files/PCA/'.$no.'/'.$name.'.jpg')
                                    );
                                    $file_name=$name.'.jpg';
                                    if($data){
                                        DB::table('waybill.tblpca_account_application_req_files')
                                        ->insert([
                                            'upload_file' => $no.'/'.$file_name,
                                            'pca_account_application_req_id' => $pca_account_application_req_id
                                        ]);
                                    }
                                }

                            }

                    }
                }
            }

            DB::table('waybill.tblpca_account_application_req')
            ->where("pca_account_no",$no)
            ->where("pca_account_application_req_status",2)
            ->delete();

            DB::table('waybill.tblpca_account_application_req_files')
            ->whereRaw("
            pca_account_application_req_id IN
            (SELECT pca_account_application_req_id FROM waybill.tblpca_account_application_req WHERE pca_account_no='".$no."' )
            ")
            ->where("pca_account_application_req_files_status",2)
            ->delete();


            if($request->pca_application_action==0){
                $msg='APPLICATION SAVED AS DRAFT.';
            }else{

                DB::table('waybill.tblpca_accounts_emailing')
                ->insert([
                    'email_type' => 9,
                    'pca_account_no' => $no
                ]);
                DB::table('waybill.tblpca_accounts_emailing')
                ->where("unique_url",$request->pca_application_url)
                ->update([
                    'application_url_status'=>1
                ]);
                DB::table('waybill.tblpca_accounts')
                ->where("pca_account_no",$no)
                ->update([
                    'pca_account_status'=>0
                ]);

                $msg='SUBMITTED.';
            }

            DB::table('recordlogs.tblpca_accounts_logs')
            ->insert([
                'module' => 'PERSONAL/CORPORATE APPLICATION',
                'logs' => $msg,
                'pca_account_no' => $no
            ]);

            DB::commit();
            return response()->json(['title'=>'Success','message'=>$msg,'type'=>'success'],200);

        } catch (\Exception $e) {

            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }
    }
    public function doff_set_pwd(Request $request){

         try{

            DB::beginTransaction();
            $details = DB::table('dailyove_online_site.users')
            ->where("contact_id",$request->id)
            ->first();
            if( $details && $details->password !='' ){
                $msg='Password Already Updated';
            }else{

                DB::table('dailyove_online_site.users')
                    ->where('contact_id',$request->id)
                    ->update([
                       'password'=>Hash::make($request->doff_password)
                    ]);

                DB::table('recordlogs.tblrecordlog')
                ->insert([
                    'module' => 'CLIENT WEBSITE PASSWORD',
                    'recordlog' => 'Website Password Successfully Updated',
                    'reference_no' => $request->id
                ]);

                $msg='Password Successfully Updated';

            }
            DB::commit();
            return response()->json(['title'=>'Success','message'=>$msg,'type'=>'success'],200);
        } catch (\Throwable $th) {

            DB::rollback();

            $this->error_log($th,$request->url());
            $message = 'An unexpected error has occurred. Please take a screenshot of this message and share it with our customer service team so we can assist you promptly. Error Code: '.session('errorId');
            return response()->json(['message'=>$message,'type'=>'error'],200);
        }
    }
    public function pc_activate_account(Request $request){

        try{

            DB::beginTransaction();
            $count_exist = DB::table('dailyove_online_site.users')
            ->where("contact_id",$request->pca_no)
            ->count();
            if( $count_exist > 0){
                $msg='Already Activated';
            }else{

                $details = DB::table('waybill.tblpca_accounts')
                ->where("pca_account_no",$request->pca_no)
                ->selectRaw(
                "
                full_name,fname,mname,lname,company,company_name,email,vat,bir_2306,bir_2307,branch,contact_no
                "
                )
                ->first();

                DB::table('dailyove_online_site.tblcontacts')
                    ->insert([
                        'contact_id' => $request->pca_no,
                        'fileas'=>$details->full_name,
                        'fname'=>$details->fname,
                        'mname'=>$details->mname,
                        'lname'=>$details->lname,
                        'company'=>$details->company_name,
                        'use_company'=>$details->company,
                        'vat'=>$details->vat,
                        'bir2306'=>$details->bir_2306,
                        'bir2307'=>$details->bir_2307,
                        'customer'=>1,
                        'email'=>$request->email,
                        'branchoffice_id'=>$details->branch,
                        'contact_status'=>1,
                        'gender'=>'',
                        'religion'=>'',
                        'nationality'=>'',
                        'civil_status'=>'',
                        'contact_no'=>$details->contact_no !=null ? $details->contact_no : '',
                        'business_category_id'=>'0',
                        'discount'=>'0',
                        'employee'=>'0',
                        'profile_photo_path'=>''
                    ]);

                User::create([
                        'contact_id' => $request->pca_no,
                        'email'=>$request->email,
                        'name'=>$details->full_name,
                        //'personal_corporate'=>1,
                        'password'=>Hash::make($request->pca_password)
                    ])->assignRole('Client');

                DB::table('dailyove_online_site.users')
                    ->where('contact_id',$request->pca_no)
                    ->update([
                        'personal_corporate'=>1
                    ]);

                $log='SUCCESSFULLY ACTIVATED ACCOUNT.';
                DB::table('recordlogs.tblpca_accounts_logs')
                ->insert([
                    'module' => 'PERSONAL/CORPORATE ACCOUNT ACTIVATION',
                    'logs' => $log,
                    'pca_account_no' => $request->pca_no
                ]);

                $msg='Successfully Activated';

            }
            DB::commit();
            return response()->json(['title'=>'Success','message'=>$msg,'type'=>'success'],200);
        } catch (\Exception $e) {

            //echo $e;
            DB::rollback();

            $e_id=substr(sha1(mt_rand()),17,20);
            DB::table('recordlogs.error_logs')
            ->insert([
                'error_id'=>$e_id,
                'error_message'=>'CLIENT WEBSITE- ERROR ACTIVATING ACCOUNT.',
                'error_description'=>$e->getMessage()
            ]);

            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);

        }
    }
    public function get_wtax_breakdown(Request $request){

        $tcode=base64_decode($request->tcode);
        $pca_no=base64_decode($request->pca_no);

        $result=DB::table('waybill.tblwaybills_wtax_breakdown_application')
        ->whereRaw(" pca_account_no='".$pca_no."' AND waybills_wtax_breakdown_application_status !=3 ")
        ->selectRaw("
            DATE_FORMAT(application_date,'%m/%d/%Y %h:%i %p') as tdate,
            upload_file,
            waybills_wtax_breakdown_application_id,
            waybills_wtax_breakdown_application_status,
            (
                SELECT GROUP_CONCAT(
                CONCAT( '<a class=\"view_waybill_wtax_application\" data-tcode=\"',tblwaybills.transactioncode,'\" data-toggle=\"modal\" data-target=\".view_waybill\" >',tblwaybills.waybill_no,'</a>')
                SEPARATOR '<br>')
                FROM waybill.tblwaybills_wtax_breakdown_application_wb wb
                LEFT OUTER JOIN waybill.tblwaybills ON tblwaybills.transactioncode=wb.transactioncode
                WHERE wb.waybills_wtax_breakdown_application_id=tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id
            ) as wb_list,
            (
                SELECT GROUP_CONCAT(CONCAT(tbltax_code_atc.tax_code,' : Php ',ROUND(tc.amt,2)) SEPARATOR '<br>')
                FROM waybill.tblwaybills_wtax_breakdown_application_details tc
                LEFT OUTER JOIN waybill.tbltax_code_atc ON tbltax_code_atc.tax_code_id=tc.tax_code_id
                WHERE tc.waybills_wtax_breakdown_application_id=tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id
            ) as tax_code,
            IFNULL((
                SELECT SUM(tblwaybills_wtax_breakdown_application_details.amt)
                FROM waybill.tblwaybills_wtax_breakdown_application_details
                WHERE tblwaybills_wtax_breakdown_application_details.waybills_wtax_breakdown_application_id=tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id
            ),0) as tamount
        ")
        ->get()
        ;


        // $result['validated']=DB::table('waybill.tblwaybills_wtax_breakdown')
        // ->where('tblwaybills_wtax_breakdown.transactioncode',)
        // ->whereRaw("
        //     (
        //         SELECT COUNT(orcr_wtax.reference_no)
        //         FROM waybill.tblorcrdetails orcr_wtax
        //         WHERE  orcr_wtax.reference_no=tblwaybills_wtax_breakdown.reference_no
        //         AND orcr_wtax.transactioncode=tblwaybills_wtax_breakdown.transactioncode
        //         AND (orcr_wtax.reversal_ref IS NOT NULL OR orcr_wtax.reversal_ref !='')
        //     ) = 0
        // ")
        // ->leftJoin("waybill.tbltax_code_atc","tblwaybills_wtax_breakdown.tax_code_id","=","tbltax_code_atc.tax_code_id")
        // ->selectRaw("
        //     tblwaybills_wtax_breakdown.dtype,
        //     tbltax_code_atc.tax_code,
        //     tblwaybills_wtax_breakdown.amt
        // ")
        // ->get();

        //  $result_pending=DB::table('waybill.tblwaybills_wtax_breakdown_application')
        // ->where('tblwaybills_wtax_breakdown_application.transactioncode',base64_decode($request->tcode))
        // ->where('tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_status',0)
        // ->leftJoin("dailyove_online_site.users","tblwaybills_wtax_breakdown_application.user_id","=","users.contact_id")
        // ->selectRaw("
        //     DATE_FORMAT(tblwaybills_wtax_breakdown_application.application_date,'%Y/%m/%d %h:%i %p') as application_date,
        //     users.name,
        //     tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id,
        //     tblwaybills_wtax_breakdown_application.upload_file
        // ")
        // ->get();
        // $pending="";
        // if(count($result_pending)){
        //     foreach($result_pending as $data){

        //         $pending .='<tr><td>'.$data->application_date.'<br><small>'.$data->name.'</small></td>';
        //         $pending .='<td><table class="table">';

        //         $details=DB::table('waybill.tblwaybills_wtax_breakdown_application_details')
        //         ->where('tblwaybills_wtax_breakdown_application_details.waybills_wtax_breakdown_application_id',$data->waybills_wtax_breakdown_application_id)

        //         ->leftJoin("waybill.tbltax_code_atc","tblwaybills_wtax_breakdown_application_details.tax_code_id","=","tbltax_code_atc.tax_code_id")
        //         ->selectRaw("
        //             tblwaybills_wtax_breakdown_application_details.dtype,
        //             tbltax_code_atc.tax_code,
        //             tblwaybills_wtax_breakdown_application_details.amt
        //         ")
        //         ->get();
        //         $tamount=0;
        //         if(count($details)){
        //             foreach($details as $details_data){
        //                 $pending .='<tr>
        //                 <td>'.$details_data->tax_code.'</td>
        //                 <td>'.number_format($details_data->amt,2).'</td>
        //                 </tr>';
        //                 $tamount +=$details_data->amt;
        //             }
        //         }
        //         $pending .='<tr>
        //                 <td><b>TOTAL</b></td>
        //                 <td><b>'.number_format($tamount,2).'</b></td>
        //                 </tr>';
        //         $pending .='</table></td>';
        //         $pending .='<td><a data-file="'.$data->upload_file.'" class="view_pca_deposit_upload_btn" data-toggle="modal" data-target=".view_pca_deposit_upload"  ><img width="100px;" height="100px;" src="'.$data->upload_file.'" /></a></td>';
        //         $pending .='<td><button type="button" onclick="remove_wtax_application('.$data->waybills_wtax_breakdown_application_id.')" class="btn btn-xs btn-warning" ><i class="fa fa-times"></i> Cancel</button></td>';
        //         $pending .='</tr>';

        //     }
        // }
        // if($pending !=''){
        //     $pending ='<h5><b><i class="fa fa-pencil"></i> FOR VALIDATION</b></h5>
        //     <table class="table">
        //     <thead><tr><th>Date</th><th>Details</th><th>Uploaded File</th><th></th></tr></thead>
        //     <tbody>'.$pending.'</tbody>
        //     </table>';
        // }
        // $result['pending']=$pending;
        echo json_encode($result);

    }
    public function cancel_wtax_application(Request $request){
        $id=base64_decode($request->id);
        try{

            DB::beginTransaction();

            DB::table('waybill.tblwaybills_wtax_breakdown_application')
            ->where('waybills_wtax_breakdown_application_id',$id)
            ->update([
                'waybills_wtax_breakdown_application_status'=> 3,
                'validated_date' => Carbon::now()
            ]);

            DB::commit();
            return response()->json(['title'=>'Success','message'=>'Successfully cancelled.','type'=>'success'],200);


        } catch (\Exception $e) {
            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }
    }
    public function get_pca_wtax_waybill (Request $request){
        $pca_no=base64_decode($request->pca_no);

        DB::statement("SET SQL_MODE= ''");
        $result=DB::select("
        SELECT
        tblwaybills.transactioncode,
        IFNULL(tblwaybills.waybill_no,tblwaybills.transactioncode) as waybill_no,
        (
            tblwaybills.withholdingttax_amount
            +
            (
                SELECT IFNULL(SUM(adjustment_amount),0)
                FROM waybill.tbladjustment  adj_wtax
                LEFT OUTER JOIN waybill.tblorcrdetails orcr_wtax ON orcr_wtax.reference_no=adj_wtax.reference_no
                AND orcr_wtax.transactioncode=adj_wtax.transactioncode
                WHERE adj_wtax.adjustment_type='WITHOLDING TAX'
                AND adj_wtax.transactioncode=tblwaybills.transactioncode
                AND (orcr_wtax.reversal_ref IS NULL OR orcr_wtax.reversal_ref='')
            )
        ) as wtax_amt,
        (
            SELECT IFNULL(SUM(tblwaybills_wtax_breakdown_application_details.amt),0)
            FROM waybill.tblwaybills_wtax_breakdown_application
            LEFT OUTER JOIN waybill.tblwaybills_wtax_breakdown_application_details ON
            tblwaybills_wtax_breakdown_application_details.waybills_wtax_breakdown_application_id=tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id
            WHERE tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_status=0
            AND tblwaybills_wtax_breakdown_application.transactioncode=tblwaybills.transactioncode
        ) as wtax_amt_pending

        FROM waybill.tblwaybills
        LEFT OUTER JOIN waybill.tblpca_accounts ON tblpca_accounts.pca_account_no=tblwaybills.pca_account_no
        WHERE tblwaybills.pca_account_no='".$pca_no."'
        GROUP BY transactioncode
        HAVING wtax_amt+wtax_amt_pending <= 0
        ORDER BY transactiondate DESC

        ");


        echo json_encode($result);

    }
    public function pca_unpaid_transaction(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $action=base64_decode($request->action);
        $month=base64_decode($request->month);

        $result['pca']=DB::select("SELECT tblpca_accounts.bir_2307 FROM waybill.tblpca_accounts WHERE pca_account_no='".$pca_no."'");

        DB::statement("SET SQL_MODE= ''");
        $having="";
        $cond=" WHERE tblwaybills.pca_account_no='".$pca_no."' ";
        if($action=='unpaid'){
            $having=" HAVING balance_amount > 0 ";
        }

        if($action=='transacted'){
            $cond .=" AND LEFT(tblwaybills.transactiondate,7)='".$month."' ";
        }
        if( Auth::user()->contact_id != $pca_no && session('pca_atype') == 'external' ){
            $cond .=" AND ol_waybill.prepared_by='".Auth::user()->contact_id."' ";
        }
        $result['wb']=DB::select("
        SELECT
        tblwaybills.transactioncode,
        IFNULL(tblwaybills.waybill_no,tblwaybills.transactioncode) as waybill_no ,
        tblwaybills.transactiondate,
        DATE_FORMAT(tblwaybills.transactiondate,'%Y/%m/%d') as tdate,
        shipper.fileas as shipper_name,
        consignee.fileas as consignee_name,
        (Sum(tblorcrdetails.withdraw) + ifnull(addj.adjustment,0)) - (Sum(tblorcrdetails.deposit)  + ifnull(lessj.adjustment,0)) as balance_amount,
        tblpca_accounts.bir_2307

        FROM waybill.tblwaybills
        LEFT OUTER JOIN waybill.tblcontacts shipper ON shipper.contact_id=tblwaybills.shipper_id
        LEFT OUTER JOIN waybill.tblcontacts consignee ON consignee.contact_id=tblwaybills.consignee_id
        LEFT OUTER JOIN waybill.tblorcrdetails ON tblorcrdetails.transactioncode=tblwaybills.transactioncode
        LEFT OUTER JOIN dailyove_online_site.tblwaybills ol_waybill ON ol_waybill.reference_no=tblwaybills.online_booking_ref
        LEFT JOIN (SELECT transactioncode,SUM(adjustment_amount) as adjustment FROM waybill.tbladjustment WHERE add_less =1 AND reference_no IN (SELECT reference_no FROM waybill.tblorcrdetails) GROUP BY transactioncode) addj
        ON addj.transactioncode = tblwaybills.transactioncode
        LEFT JOIN (SELECT transactioncode,SUM(adjustment_amount) as adjustment FROM waybill.tbladjustment WHERE add_less =0 AND reference_no IN (SELECT reference_no FROM waybill.tblorcrdetails) GROUP BY transactioncode) lessj
        ON lessj.transactioncode = tblwaybills.transactioncode
        LEFT OUTER JOIN waybill.tblpca_accounts ON tblpca_accounts.pca_account_no=tblwaybills.pca_account_no
        ".$cond."
        GROUP BY transactioncode
        ".$having."
        ORDER BY transactiondate DESC

        ");

        // (
        //     tblwaybills.withholdingttax_amount
        //     +
        //     (
        //         SELECT IFNULL(SUM(adjustment_amount),0)
        //         FROM waybill.tbladjustment  adj_wtax
        //         LEFT OUTER JOIN waybill.tblorcrdetails orcr_wtax ON orcr_wtax.reference_no=adj_wtax.reference_no
        //         AND orcr_wtax.transactioncode=adj_wtax.transactioncode
        //         WHERE adj_wtax.adjustment_type='WITHOLDING TAX'
        //         AND adj_wtax.transactioncode=tblwaybills.transactioncode
        //         AND (orcr_wtax.reversal_ref IS NULL OR orcr_wtax.reversal_ref='')
        //     )
        // ) as wtax_amt,
        // (
        //     SELECT IFNULL(SUM(tblwaybills_wtax_breakdown_application_details.amt),0)
        //     FROM waybill.tblwaybills_wtax_breakdown_application
        //     LEFT OUTER JOIN waybill.tblwaybills_wtax_breakdown_application_details ON
        //     tblwaybills_wtax_breakdown_application_details.waybills_wtax_breakdown_application_id=tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_id
        //     WHERE tblwaybills_wtax_breakdown_application.waybills_wtax_breakdown_application_status=0
        //     AND tblwaybills_wtax_breakdown_application.transactioncode=tblwaybills.transactioncode
        // ) as wtax_amt_pending,


        echo json_encode($result);


    }
    public function save_wtax_application(Request $request){
        $reference_count=0;
        if(isset($request->pca_wtax_waybill)){
            foreach($request->pca_wtax_waybill as $pca_wtax_waybill ){
                $reference_data= DB::table('waybill.tblwaybills_wtax_breakdown_application')
                ->where('transactioncode',$pca_wtax_waybill)
                ->where('waybills_wtax_breakdown_application_status',0)
                ->count();
                $reference_count +=$reference_data;
            }
        }
        if($reference_count > 0){

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Already applied.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);
            //return response()->json(['title'=>'Ooops!','message'=>'Already applied.','type'=>'error'],200);
        }else{
            try{

                DB::beginTransaction();

                $file=$request->file('wtax_file');
                $extension = $file->extension();
                $name=$file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $name=preg_replace('/\s+/', '', $name);

                $extensions_arr = array("jpg","jpeg","png","gif","jfif");
                $count_not_image=0;
                if( in_array(strtolower($extension),$extensions_arr) ){}
                else{
                    $count_not_image++;
                }
                if($count_not_image > 0){
                    DB::rollback();
                    return response()->json([
                        'title'=>'Ooops!',
                        'message'=>'Uploaded File is Invalid. Please upload image only.',
                        'type'=>'success',
                        'icon'=>'error',
                        'msg_type'=>2
                    ],200);

                }else{

                    if(!Storage::disk('system_files')->exists('system_files/PCA'))
                    {
                        Storage::disk('system_files')->makeDirectory('system_files/PCA', 0777, true);
                    }
                    if(!Storage::disk('system_files')->exists('system_files/PCA/WTAX'))
                    {
                        Storage::disk('system_files')->makeDirectory('system_files/PCA/WTAX', 0777, true);
                    }
                    DB::table('waybill.tblwaybills_wtax_breakdown_application')
                    ->insert([
                        'user_id' => Auth::user()->contact_id,
                        'pca_account_no'=>$request->pca_wtax_cno,
                        'transactioncode' => $request->input_wtax_breakdown_tcode
                    ]);
                    $waybills_wtax_breakdown_application_id=DB::getPdo()->lastInsertId();

                    if(!Storage::disk('system_files')->exists('system_files/PCA/WTAX/'.$waybills_wtax_breakdown_application_id))
                    {
                        Storage::disk('system_files')->makeDirectory('system_files/PCA/WTAX/'.$waybills_wtax_breakdown_application_id, 0777, true);
                    }

                    $data=imagejpeg(
                        imagecreatefromstring(
                            file_get_contents($file->getPathName())
                        ),
                        Storage::disk('system_files')->path('system_files/PCA/WTAX/'.$waybills_wtax_breakdown_application_id.'/'.$name.'.jpg')
                    );
                    $file_save= 'data:image/jpg;base64,'.base64_encode(file_get_contents(Storage::disk('system_files')->path('system_files/PCA/WTAX/'.$waybills_wtax_breakdown_application_id.'/'.$name.'.jpg')));

                    DB::table('waybill.tblwaybills_wtax_breakdown_application')
                    ->where('waybills_wtax_breakdown_application_id',$waybills_wtax_breakdown_application_id)
                    ->update([
                        'upload_file'=> $file_save
                    ]);

                    if(isset($request->pca_wtax_waybill)){
                        foreach($request->pca_wtax_waybill as $pca_wtax_waybill ){
                            DB::table('waybill.tblwaybills_wtax_breakdown_application_wb')
                            ->insert([
                                'waybills_wtax_breakdown_application_id' => $waybills_wtax_breakdown_application_id,
                                'transactioncode' => $pca_wtax_waybill
                            ]);

                        }
                    }
                    if(isset($request->wtax_breakdown_tcode)){

                        foreach($request->wtax_breakdown_tcode as $wt => $wtax_breakdown_tcode ){

                            $wtax_breakdown_type=$request['wtax_breakdown_type'][$wt];
                            $wtax_breakdown_tcode_amt=$request['wtax_breakdown_tcode_amt'][$wt];

                            DB::table('waybill.tblwaybills_wtax_breakdown_application_details')
                            ->insert([
                                'waybills_wtax_breakdown_application_id' => $waybills_wtax_breakdown_application_id,
                                'dtype' => $wtax_breakdown_type,
                                'tax_code_id' => $wtax_breakdown_tcode,
                                'amt'=>$wtax_breakdown_tcode_amt
                            ]);


                        }

                    }

                    DB::commit();
                    return response()->json([
                        'title'=>'Success',
                        'message'=>'Successfully submitted.',
                        'type'=>'success',
                        'icon'=>'success',
                        'msg_type'=>1
                    ],200);
                }

            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();

                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN APPLYING WAYBILL WTAX.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }
        }
    }
    public function pca_save_deposit(Request $request){

        $reference_count= AdvancePayment::where('deposit_reference',$request->pca_deposit_ref_no)
        ->where('pca_account_no',$request->pca_deposit_no)
        ->where(function ($query){
            $query->where('advance_payment_status', 1)
            ->orWhere('advance_payment_status', 0);
        })
        ->count();
        $application_group_id='';
        if(isset($request->addtl_pca_deposit_ref_no)){

            $application_group_id = 'ADVWEB'.date("y");
            $characters = "0123456789QWERTYUIOPASDFGHJKLZXCVBNM";
            $characters_length = strlen($characters);
            for($i=0; $i<12; $i++){
                $application_group_id.= $characters[rand(0, $characters_length-1)];
            }
            foreach($request->addtl_pca_deposit_ref_no as $ref_no){

                $addtl_reference_count= AdvancePayment::where('deposit_reference',$ref_no)
                ->where('pca_account_no',$request->pca_deposit_no)
                ->where(function ($query){
                    $query->where('advance_payment_status', 1)
                    ->orWhere('advance_payment_status', 0);
                })
                ->count();
                $reference_count +=$addtl_reference_count;
            }
        }

        if($reference_count > 0){
            return response()->json(['title'=>'Ooops!','message'=>'Reference No. already exist.','type'=>'error'],200);
        }else{
            try{

                DB::beginTransaction();

                $file=$request->file('pca_deposit_file');
                $name=$file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $name=preg_replace('/\s+/', '', $name);

                if(!Storage::disk('system_files')->exists('system_files/PCA'))
                {
                    Storage::disk('system_files')->makeDirectory('system_files/PCA', 0777, true);
                }
                if(!Storage::disk('system_files')->exists('system_files/PCA/DEPOSIT'))
                {
                    Storage::disk('system_files')->makeDirectory('system_files/PCA/DEPOSIT', 0777, true);
                }



                $adv_payment_details['advance_payment_status']=0;
                $adv_payment_details['withdraw']=$request->pca_deposit_amt;
                $adv_payment_details['deposit_date']=$request->pca_deposit_date;
                $adv_payment_details['deposit_account_name']=$request->pca_deposit_account_name;
                $adv_payment_details['deposit_account_no']=$request->pca_deposit_account_no;
                $adv_payment_details['deposit_reference']=$request->pca_deposit_ref_no;
                $adv_payment_details['pca_account_no']=$request->pca_deposit_no;
                $adv_payment_details['pca_adv_onlinepayment']=$request->pca_deposit_amt;
                $adv_payment_details['prepared_datetime']=Carbon::now();
                $adv_payment_details['application_group_id']=$application_group_id !='' ? $application_group_id : null;
                $data_adv=AdvancePayment::create($adv_payment_details);

                if(!Storage::disk('system_files')->exists('system_files/PCA/DEPOSIT/'.$data_adv->id))
                {
                    Storage::disk('system_files')->makeDirectory('system_files/PCA/DEPOSIT/'.$data_adv->id, 0777, true);
                }
                $data=imagejpeg(
                    imagecreatefromstring(
                        file_get_contents($file->getPathName())
                    ),
                    Storage::disk('system_files')->path('system_files/PCA/DEPOSIT/'.$data_adv->id.'/'.$name.'.jpg')
                );
                $file_save= 'data:image/jpg;base64,'.base64_encode(file_get_contents(Storage::disk('system_files')->path('system_files/PCA/DEPOSIT/'.$data_adv->id.'/'.$name.'.jpg')));

                AdvancePayment::where('advance_payment_id',$data_adv->id)
                ->update([
                    'upload_file'=> $file_save
                ]);

                if(isset($request->addtl_pca_deposit_ref_no)){
                    foreach($request->addtl_pca_deposit_ref_no as $a_ol => $ref_no){

                        $file=$request->file('addtl_pca_deposit_file')[$a_ol];
                        $name=$file->getClientOriginalName();
                        $name = pathinfo($name, PATHINFO_FILENAME);
                        $name=preg_replace('/\s+/', '', $name);

                        $adv_payment_details_addtl['advance_payment_status']=0;
                        $adv_payment_details_addtl['withdraw']=$request['addtl_pca_deposit_amt'][$a_ol];
                        $adv_payment_details_addtl['deposit_date']=$request['addtl_pca_deposit_date'][$a_ol];
                        $adv_payment_details_addtl['deposit_account_name']=$request->pca_deposit_account_name;
                        $adv_payment_details_addtl['deposit_account_no']=$request->pca_deposit_account_no;
                        $adv_payment_details_addtl['deposit_reference']=$ref_no;
                        $adv_payment_details_addtl['pca_account_no']=$request->pca_deposit_no;
                        $adv_payment_details_addtl['pca_adv_onlinepayment']=$request['addtl_pca_deposit_amt'][$a_ol];
                        $adv_payment_details_addtl['prepared_datetime']=Carbon::now();
                        $adv_payment_details_addtl['application_group_id']=$application_group_id !='' ? $application_group_id : null;
                        $data_adv_addtl=AdvancePayment::create($adv_payment_details_addtl);

                        if(!Storage::disk('system_files')->exists('system_files/PCA/DEPOSIT/'.$data_adv_addtl->id))
                        {
                            Storage::disk('system_files')->makeDirectory('system_files/PCA/DEPOSIT/'.$data_adv_addtl->id, 0777, true);
                        }
                        $data=imagejpeg(
                            imagecreatefromstring(
                                file_get_contents($file->getPathName())
                            ),
                            Storage::disk('system_files')->path('system_files/PCA/DEPOSIT/'.$data_adv_addtl->id.'/'.$name.'.jpg')
                        );
                        $file_save= 'data:image/jpg;base64,'.base64_encode(file_get_contents(Storage::disk('system_files')->path('system_files/PCA/DEPOSIT/'.$data_adv_addtl->id.'/'.$name.'.jpg')));

                        AdvancePayment::where('advance_payment_id',$data_adv_addtl->id)
                        ->update([
                            'upload_file'=> $file_save
                        ]);


                    }
                }


                DB::commit();
                return response()->json(['title'=>'Success','message'=>'Deposit successfully submitted.','type'=>'success'],200);


            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();
                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE: ERROR IN ADDING PCA DEPOSIT.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);

                //return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

            }
        }

    }
    public function pca_cancel_deposit(Request $request){

        $id=base64_decode($request->id);
        $pca_no=base64_decode($request->pca_no);
        try{

            DB::beginTransaction();

            AdvancePayment::where('advance_payment_id',$id)
            ->orwhere('application_group_id','=',$id)
            ->update([
                'advance_payment_status'=> 3
            ]);

            DB::commit();
            return response()->json(['title'=>'Success','message'=>'Deposit successfully cancelled.','type'=>'success'],200);


        } catch (\Exception $e) {
            //echo $e;
            DB::rollback();
             $e_id=substr(sha1(mt_rand()),17,20);
            DB::table('recordlogs.error_logs')
            ->insert([
                'error_id'=>$e_id,
                'error_message'=>'CLIENT WEBSITE: ERROR IN CANCELLING PCA DEPOSIT.',
                'error_description'=>$e->getMessage()
            ]);

            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);
            //return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],200);

        }

    }
    public function get_wtax_atc(){

        $result=DB::table('waybill.tbltax_code_atc')
        ->where('tax_code_status',1)
        ->selectRaw("
        tax_code_id,
        tax_code
        ")
        ->get();

        echo json_encode($result);
    }
    public function pca_no_details(Request $request){
        $pca_no=base64_decode($request->pca_no);

        $result=DB::table('waybill.tblpca_accounts')
        ->where('tblpca_accounts.pca_account_no',$pca_no)
        ->leftJoin("doff_configuration.tblbranchoffice_pca_bank_account","tblpca_accounts.branch","=","tblbranchoffice_pca_bank_account.branchoffice_no")
        ->selectRaw("
        tblbranchoffice_pca_bank_account.account_name,
        tblbranchoffice_pca_bank_account.account_no,
        IFNULL(
            (
            SELECT
            IFNULL(IFNULL(tblpca_accounts.min_deposit,tblpca_min_deposit.min_deposit),0) as setting_value
            FROM waybill.tblpca_accounts
            LEFT OUTER JOIN waybill.tblpca_min_deposit ON tblpca_min_deposit.pca_type=tblpca_accounts.personal_corporate
            WHERE tblpca_accounts.pca_account_no ='".$pca_no."'
            )
        ,0)
        as min_deposit
        ")
        ->get();

        echo json_encode($result);
    }
    public function pca_deposit_view_proof(Request $request){

        $result = DB::table('waybill.tbladvance_payment')
        ->where('advance_payment_id',base64_decode($request->adv_id))
        ->get();
        echo json_encode($result);
    }
    public function pca_deposit(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $tab=base64_decode($request->tab);

        $result=DB::table('waybill.tbladvance_payment')
        ->whereNotNull('pca_account_no')
        ->where('pca_account_no',$pca_no)
        ->where('withdraw','>',0)
        ->whereRaw(" (SELECT COUNT(adjustment_id) FROM waybill.tbladjustment adj WHERE adj.reference_no=tbladvance_payment.reference_no)=0 ")
        ;

        if( $tab==1 ){
            $result=$result->where('advance_payment_status',0);
        }
        elseif( $tab==2 ){
            $result=$result->where('advance_payment_status',1);
        }
        $result=$result
        ->selectRaw("

            IFNULL(tbladvance_payment.application_group_id,tbladvance_payment.advance_payment_id) as advance_payment_id,
            GROUP_CONCAT( IFNULL(tbladvance_payment.advance_payment_id,'') SEPARATOR '~' ) as adv_id,
            GROUP_CONCAT( IFNULL(tbladvance_payment.withdraw,'') SEPARATOR '~' ) as withdraw,
            GROUP_CONCAT( IFNULL(tbladvance_payment.deposit_reference,'') SEPARATOR '~' ) as deposit_reference,
            GROUP_CONCAT( IFNULL(DATE_FORMAT(tbladvance_payment.deposit_date,'%Y/%m/%d'),'') SEPARATOR '~' ) as deposit_date,
            DATE_FORMAT(tbladvance_payment.actual_datetime,'%Y/%m/%d %h:%i %p') as actual_datetime,
            tbladvance_payment.pca_account_no,
            tbladvance_payment.advance_payment_status,
            tbladvance_payment.deposit_account_name,
            tbladvance_payment.deposit_account_no,
            tbladvance_payment.prepared_by


        ")
        ->groupByRaw("IFNULL(tbladvance_payment.application_group_id,tbladvance_payment.advance_payment_id)")
        ->get();

        echo json_encode($result);
    }
    public function pca_application(Request $request){

        try{

            $url_code=$request->url_code;

            $result = DB::table('waybill.tblpca_accounts_emailing')
            ->where("tblpca_accounts_emailing.unique_url",$url_code)
            ->where("tblpca_accounts_emailing.email_type",8)
            ->leftJoin("waybill.tblpca_accounts","tblpca_accounts_emailing.pca_account_no","=","tblpca_accounts.pca_account_no")
            ->leftJoin("waybill.tblsectorate2","tblpca_accounts.address_no","=","tblsectorate2.sectorate_no")
            ->leftJoin("waybill.tblcitiesminicipalities","tblsectorate2.city_id","=","tblcitiesminicipalities.cities_id")
            ->selectRaw("
            tblpca_accounts.personal_corporate,
            tblpca_accounts_emailing.pca_account_no,
            tblpca_accounts_emailing.email,
            tblpca_accounts.full_name,
            tblpca_accounts_emailing.application_url_status,
            tblpca_accounts_emailing.unique_url,
            tblcitiesminicipalities.province_id,
            tblsectorate2.city_id,
            tblsectorate2.sectorate_no,
            tblpca_accounts.address_street,
            tblpca_accounts.contact_person,
            tblpca_accounts.contact_no,
            tblpca_accounts.tin_no,
            tblpca_accounts.vat,
            tblpca_accounts.bir_2306,
            tblpca_accounts.bir_2307
            "
            )
            ->first();

            $result_req=array();
            $result_prov=array();

            if($result){
                $pca_no=$result->pca_account_no;

                $result_req = DB::table('waybill.tblpca_requirements')
                ->leftJoin('waybill.tblpca_requirements_details as application',function($join){
                    $join->on('tblpca_requirements.pca_requirements_id', '=', 'application.pca_requirements_id')
                    ->where('application.application_termination',1);
                })
                ->leftJoin('waybill.tblpca_account_application_req',function($join) use($pca_no){
                    $join->on('tblpca_account_application_req.pca_requirements_id', '=', 'application.pca_requirements_id')
                    ->where('tblpca_account_application_req.pca_account_no',$pca_no);
                })
                ;
                $result_req = $result_req->whereNotNull('application.tblpca_requirements_details_id');
                if($result->personal_corporate=='personal'){
                    $result_req = $result_req->where('application.personal',1);
                }
                elseif($result->personal_corporate=='corporate'){
                    $result_req = $result_req->where('application.corporate',1);
                }
                elseif($result->personal_corporate=='partnership'){
                    $result_req = $result_req->where('application.partnership',1);
                }
                elseif($result->personal_corporate=='publication'){
                    $result_req = $result_req->where('application.publication',1);
                }
                $result_req = $result_req->selectRaw("
                    tblpca_requirements.pca_requirements_id,
                    tblpca_requirements.pca_requirements_name,
                    tblpca_requirements.upload_file,
                    IFNULL(application.personal,0) as a_personal,
                    IFNULL(application.corporate,0) as a_corporate,
                    tblpca_account_application_req.pca_account_application_req_id,
                    (
                        SELECT
                        GROUP_CONCAT(
                            CONCAT(pca_account_application_req_files_id,'~',upload_file)
                            SEPARATOR '^'
                        )
                        FROM  waybill.tblpca_account_application_req_files
                        WHERE tblpca_account_application_req_files.pca_account_application_req_id=tblpca_account_application_req.pca_account_application_req_id
                        AND tblpca_account_application_req.pca_account_application_req_id IS NOT NULL
                    ) as req_files

                ")
                ->get();

                $result_prov = DB::table('waybill.tblprovinces')
                ->selectRaw("
                    province_id,
                    province_name

                ")
                ->get();
            }

            return view('pca.application',compact('result','result_req','result_prov'));

        } catch (QueryException $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (\Throwable $th) {
            $this->error_log($th,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (Exception $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        }
    }
    public function get_pc_cities(Request $request){

        $province=base64_decode($request->province);

        $result = DB::table('waybill.tblcitiesminicipalities')
        ->where('province_id',$province)
        ->selectRaw("
            cities_id,
            cities_name

        ")
        ->get();

        echo json_encode($result);
    }
    public function get_pc_brgy(Request $request){

        $city=base64_decode($request->city);

        $result = DB::table('waybill.tblsectorate2')
        ->where('city_id',$city)
        ->selectRaw("
            sectorate_no,
            barangay

        ")
        ->get();

        echo json_encode($result);
    }
    public function login_check_pwd(){

        $result=0;
        $details = DB::table('dailyove_online_site.users')
        ->where("contact_id",\Auth::user()->contact_id)
        ->first();
        if( $details && $details->password =='' ){
            $result=1;
        }
        echo json_encode($result);
    }
    public function set_doff_pwd(Request $request){
        if(base64_decode($request->url_code)=='doff_user_login'){

            $result= DB::table('dailyove_online_site.users')
            ->where('users.contact_id',\Auth::user()->contact_id)
            ->selectRaw("users.name,users.password,users.contact_id,users.personal_corporate")
            ->first();
            $url_code=$request->url_code;
        }else{
            $result= DB::table('recordlogs.tblrecordlog')
            ->leftjoin('dailyove_online_site.users','users.contact_id','=','tblrecordlog.reference_no')
            ->where('tblrecordlog.unique_url',$request->url_code)
            ->where('tblrecordlog.module','CLIENT WEBSITE PASSWORD')
            ->selectRaw("users.name,users.password,users.contact_id,users.personal_corporate")
            ->first();
            $url_code= base64_encode($request->url_code);
        }


        return view('doff.pwd',compact('result','url_code'));
    }
    public function pca_access(Request $request){
        try{

            $url_code=$request->url_code;

            $result = DB::table('waybill.tblpca_accounts_emailing')
            ->where("unique_url",$url_code)
            ->where("email_type",1)
            ->leftJoin("waybill.tblpca_accounts","tblpca_accounts_emailing.pca_account_no","=","tblpca_accounts.pca_account_no")
            ->leftJoin("dailyove_online_site.users","tblpca_accounts_emailing.pca_account_no","=","users.contact_id")
            ->selectRaw("
            users.contact_id,
            tblpca_accounts_emailing.pca_account_no,
            tblpca_accounts_emailing.email,
            tblpca_accounts_emailing.account_code,
            tblpca_accounts.full_name,
            tblpca_accounts.personal_corporate
            ")
            ->first();

            return view('pca.access',compact('result'));

        } catch (QueryException $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (\Throwable $th) {
            $this->error_log($th,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (Exception $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        }
    }
    public function pca_transactions(Request $request){

        return view('pca.transactions');

    }
    public function pca_accounts(Request $request){

        return view('pca.accounts');

    }
    public function pca_account_selection_list(){

        $result['option']="";
        $result['count']=0;
        $pca_account = DB::table('waybill.tblpca_accounts')
        ->where('pca_account_no',\Auth::user()->contact_id)
        ->where('pca_account_status',1)
        ->first();
        if($pca_account){
            $selected='';
            if(session('pca_no')==$pca_account->pca_account_no){
                $selected='selected';
            }
            $result['option'] .='<option '.$selected.' value="'.$pca_account->pca_account_no.'" >'.$pca_account->full_name.'</option>';
            $result['count']++;
        }
        $pca_account = DB::table('dailyove_online_site.tblpca_internal_external_accounts')
        ->leftJoin('waybill.tblpca_accounts','tblpca_internal_external_accounts.pca_account_no','=','tblpca_accounts.pca_account_no')
        ->where('tblpca_internal_external_accounts.contact_id',Auth::user()->contact_id)
        ->where('tblpca_internal_external_accounts.account_status',1)
        ->selectRaw("tblpca_accounts.pca_account_no,tblpca_accounts.full_name")
        ->get();
        if(count($pca_account) > 0){
            foreach($pca_account as $acnt){
                $selected='';
                if(session('pca_no')==$acnt->pca_account_no){
                    $selected='selected';
                }
                $result['option'] .='<option '.$selected.' value="'.$acnt->pca_account_no.'" >'.$acnt->full_name.'</option>';
                $result['count']++;

            }
        }
        echo json_encode($result);
    }
    public function pca_account_selected_update(Request $request){
        try{

            $pca_no=base64_decode($request->pca_no);

            DB::beginTransaction();
            $pca_account = DB::table('waybill.tblpca_accounts')
            ->where('pca_account_no',$pca_no)
            ->count();
            if( $pca_account > 0 && $pca_no==Auth::user()->contact_id ){
                $request->session()->put('pca_no', $pca_no);
                $request->session()->put('pca_atype','main_acount');
            }else{

                $pca_account = DB::table('dailyove_online_site.tblpca_internal_external_accounts')
                ->where('contact_id',Auth::user()->contact_id)
                ->where('pca_account_no',$pca_no)
                ->where('account_status',1)
                ->first();

                $request->session()->put('pca_no', $pca_no);
                if($pca_account->internal_external ==1){
                    $request->session()->put('pca_atype','internal');

                    $pca_account_access = DB::table('dailyove_online_site.tblpca_internal_accounts_access')
                    ->where('pca_internal_external_account_id',$pca_account->pca_internal_external_account_id)
                    ->get();
                    $access_data=array();
                    if(count($pca_account_access)){

                        foreach($pca_account_access as $access){
                            array_push($access_data,$access->access_name);
                        }

                    }
                    $request->session()->put('pca_internal_access',$access_data);

                }else{
                    $request->session()->put('pca_atype','external');
                }
            }

            DB::commit();
            return response()->json(['title'=>'Success','message'=>'Successfully changed.','type'=>'success'],200);


        } catch (\Exception $e) {
            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }
    }
    public function pca_save_account(Request $request){

        try{

            DB::beginTransaction();

            $result = DB::table('dailyove_online_site.users')
            ->leftJoin('dailyove_online_site.tblpca_internal_external_accounts',function($join) use($request) {
                $join->on('tblpca_internal_external_accounts.contact_id', '=', 'users.contact_id')
                ->where('tblpca_internal_external_accounts.pca_account_no',$request->pca_ano);
            })
            ->leftJoin("dailyove_online_site.tblcontacts","users.contact_id","=","tblcontacts.contact_id")
            ->where('users.email',$request->account_email)
            ->where('users.personal_corporate',1)
            ->selectRaw("
                tblpca_internal_external_accounts.internal_external,
                tblpca_internal_external_accounts.pca_internal_external_account_id,
                tblcontacts.contact_id,tblcontacts.fileas
            ")
            ->first();
            $random_pwd='';
            $pca_internal_external_account_id='';
            if($result){
                if( $result->pca_internal_external_account_id !='' ){

                    $pca_internal_external_account_id=$result->pca_internal_external_account_id;

                    DB::table('dailyove_online_site.tblpca_internal_accounts_access')
                    ->where('pca_internal_external_account_id',$pca_internal_external_account_id)
                    ->delete();

                    DB::table('dailyove_online_site.tblpca_internal_external_accounts')
                    ->where('pca_internal_external_account_id',$pca_internal_external_account_id)
                    ->update([
                        'added_by' => Auth::user()->contact_id,
                        'internal_external' => $request->pca_internal_external,
                        'pca_account_no' => $request->pca_ano,
                        'account_status'=> 1,
                        'added_date' => Carbon::now()
                    ]);

                }else{

                    DB::table('dailyove_online_site.tblpca_internal_external_accounts')
                    ->insert([
                        'contact_id' => $result->contact_id,
                        'added_by' => Auth::user()->contact_id,
                        'internal_external' => $request->pca_internal_external,
                        'pca_account_no' => $request->pca_ano
                    ]);
                    $pca_internal_external_account_id=DB::getPdo()->lastInsertId();

                }
            }else{

                $contact_id=$this->generate_contact_id();
                $full_name=$request->account_lname.", ".$request->account_fname;
                if($request->account_mname !=''){
                    $full_name .=" ".$request->account_mname;
                }else{
                    $request->account_mname=' ';
                }
                DB::table('dailyove_online_site.tblcontacts')
                    ->insert([
                        'contact_id' => $contact_id,
                        'fileas'=>$full_name,
                        'fname'=>$request->account_fname,
                        'mname'=>$request->account_mname,
                        'lname'=>$request->account_lname,
                        'company'=>'',
                        'use_company'=>0,
                        'vat'=>1,
                        'bir2306'=>0,
                        'bir2307'=>0,
                        'customer'=>1,
                        'email'=>$request->account_email,
                        'branchoffice_id'=>'',
                        'contact_status'=>1,
                        'gender'=>'',
                        'religion'=>'',
                        'nationality'=>'',
                        'civil_status'=>'',
                        'contact_no'=>'',
                        'business_category_id'=>'0',
                        'discount'=>'0',
                        'employee'=>'0',
                        'profile_photo_path'=>''
                    ]);
                $random_pwd=$this->random_alph_num(5,5);
                User::create([
                        'contact_id' => $contact_id,
                        'email'=>$request->account_email,
                        'name'=>$full_name,
                        'password'=>Hash::make($random_pwd)
                    ])->assignRole('Client');

                DB::table('dailyove_online_site.users')
                    ->where('contact_id',$contact_id)
                    ->update([
                        'personal_corporate'=>1
                    ]);

                    DB::table('dailyove_online_site.tblpca_internal_external_accounts')
                    ->insert([
                        'contact_id' => $contact_id,
                        'added_by' => Auth::user()->contact_id,
                        'internal_external' => $request->pca_internal_external,
                        'pca_account_no' => $request->pca_ano
                    ]);
                    $pca_internal_external_account_id=DB::getPdo()->lastInsertId();

            }

            if( $pca_internal_external_account_id !='' && $request->pca_internal_external==1 && isset($request->account_rights) ){
                foreach($request->account_rights as $access ){

                    DB::table('dailyove_online_site.tblpca_internal_accounts_access')
                    ->insert([
                        'pca_internal_external_account_id' => $pca_internal_external_account_id,
                        'access_name' => $access
                    ]);

                }

            }
            DB::table('dailyove_online_site.tblpca_internal_external_email')
            ->insert([
                'pca_internal_external_account_id' => $pca_internal_external_account_id,
                'default_pwd' => $random_pwd !='' ? base64_encode($random_pwd) : null
            ]);



            DB::commit();
            return response()->json(['title'=>'Success','message'=>'Account successfully saved.','type'=>'success'],200);


        } catch (\Exception $e) {

            // echo $e;
            DB::rollback();
            $e_id=substr(sha1(mt_rand()),17,20);
            DB::table('recordlogs.error_logs')
            ->insert([
                'error_id'=>$e_id,
                'error_message'=>'ERROR IN ADDING PCA INTERNAL/EXTERNAL ACCOUNT.',
                'error_description'=>$e->getMessage()
            ]);

            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.','type'=>'error'],200);
            //return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }

    }
    public function pca_update_account_access(Request $request){

        try{

            DB::beginTransaction();

            DB::table('dailyove_online_site.tblpca_internal_accounts_access')
            ->where('pca_internal_external_account_id',$request->pca_internal_id)
            ->delete();


            foreach($request->account_rights as $access ){

                DB::table('dailyove_online_site.tblpca_internal_accounts_access')
                ->insert([
                    'pca_internal_external_account_id' => $request->pca_internal_id,
                    'access_name' => $access
                ]);

            }




            DB::commit();
            return response()->json(['title'=>'Success','message'=>'Account successfully saved.','type'=>'success'],200);


        } catch (\Exception $e) {
            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }
    }
    public function pca_deactivate_account(Request $request){

        try{

            $pca_no=base64_decode($request->pca_no);
            $id=base64_decode($request->id);
            $status=base64_decode($request->status);

            DB::beginTransaction();


            DB::table('dailyove_online_site.tblpca_internal_external_accounts')
            ->where('pca_internal_external_account_id',$id)
            ->update([
                'account_status'=> $status
            ]);

            if( $status==1){
                $msg='Successfully Activated.';
            }else{
                $msg='Successfully Deactivated.';
            }

            DB::commit();
            return response()->json(['title'=>'Success','message'=>$msg,'type'=>'success'],200);
        } catch (\Exception $e) {

            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }
    }
    public function pca_account_access(Request $request){
        $id=base64_decode($request->id);

        $result = DB::table('dailyove_online_site.tblpca_internal_accounts_access')
        ->where('tblpca_internal_accounts_access.pca_internal_external_account_id',$id)

        ->selectRaw("
            tblpca_internal_accounts_access.access_name
        ")
        ->get();

        echo json_encode($result);

    }
    public function pca_account_list(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $ie=base64_decode($request->ie);
        $status=base64_decode($request->status);

        $result = DB::table('dailyove_online_site.tblpca_internal_external_accounts')
        ->where('tblpca_internal_external_accounts.internal_external',$ie)
        ->where('tblpca_internal_external_accounts.pca_account_no', $pca_no)
        ->where('tblpca_internal_external_accounts.account_status', $status)
        ->leftJoin('dailyove_online_site.tblcontacts','tblpca_internal_external_accounts.contact_id','=','tblcontacts.contact_id')
        ->leftJoin('dailyove_online_site.users','tblpca_internal_external_accounts.contact_id','=','users.contact_id')
        ->leftJoin('dailyove_online_site.tblcontacts as aby','tblpca_internal_external_accounts.added_by','=','aby.contact_id')
        ->selectRaw("
            tblcontacts.fileas,
            tblpca_internal_external_accounts.internal_external,
            tblpca_internal_external_accounts.pca_internal_external_account_id,
            tblpca_internal_external_accounts.account_status,
            users.email,
            aby.fileas as added_by,
            DATE_FORMAT(tblpca_internal_external_accounts.added_date,'%Y/%m/%d %h:%i %p') as added_date

        ")
        ->get();

        echo json_encode($result);
    }
    public function pca_account_check_ie(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $email=base64_decode($request->email);

        $result = DB::table('dailyove_online_site.users')
        ->leftJoin('dailyove_online_site.tblpca_internal_external_accounts',function($join) use($pca_no) {
            $join->on('tblpca_internal_external_accounts.contact_id', '=', 'users.contact_id')
            ->where('tblpca_internal_external_accounts.pca_account_no',$pca_no)
            ->where('tblpca_internal_external_accounts.account_status',1);
        })
        ->leftJoin("dailyove_online_site.tblcontacts","users.contact_id","=","tblcontacts.contact_id")
        ->where('users.email',$email)
        ->where('users.personal_corporate',1)
        ->selectRaw("

            tblpca_internal_external_accounts.internal_external,
            tblpca_internal_external_accounts.pca_internal_external_account_id,
            tblcontacts.contact_id,tblcontacts.fileas

        ")
        ->get();

        echo json_encode($result);

    }

    public function get_pca_ledger(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $date=base64_decode($request->date);
        $from=base64_decode($request->from);
        $to=base64_decode($request->to);
        $type=base64_decode($request->type);


        $result = DB::table('waybill.tbladvance_payment')
        ->where('tbladvance_payment.pca_account_no',$pca_no)
        ->whereNotNull('tbladvance_payment.pca_account_no')
        ->where('tbladvance_payment.advance_payment_status',1)
        ;
        if($date=='today'){
            $result=$result->whereRaw("DATE(tbladvance_payment.prepared_datetime)=CURRENT_DATE");
            $prev_date=date('Y-m-d',strtotime(date('Y-m-d').' -1 day'));
            $date_txt=date('F d, Y',strtotime(date('Y-m-d')));
        }
        elseif($date=='yesterday'){
            $result=$result->whereRaw("DATE(tbladvance_payment.prepared_datetime)=DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
            $prev_date=date('Y-m-d',strtotime(date('Y-m-d').' -2 day'));
            $date_txt=date('F d, Y',strtotime(date('Y-m-d').' -1 day'));
        }
        elseif($date=='last7'){
            $result=$result->whereRaw("
            DATE(tbladvance_payment.prepared_datetime) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            AND DATE(tbladvance_payment.prepared_datetime) <=CURRENT_DATE
            ");
            $prev_date=date('Y-m-d',strtotime(date('Y-m-d').' -8 day'));
            $date_txt=date('F d, Y',strtotime(date('Y-m-d').' -7 day')).' TO '.date('F d, Y',strtotime(date('Y-m-d')));
        }
        elseif($date=='last30'){
            $result=$result->whereRaw("
            DATE(tbladvance_payment.prepared_datetime) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            AND DATE(tbladvance_payment.prepared_datetime) <=CURRENT_DATE
            ");
            $prev_date=date('Y-m-d',strtotime(date('Y-m-d').' -31 day'));
            $date_txt=date('F d, Y',strtotime(date('Y-m-d').' -30 day')).' TO '.date('F d, Y',strtotime(date('Y-m-d')));
        }
        elseif($date=='custom'){

            $result=$result->whereRaw("
            DATE(tbladvance_payment.prepared_datetime) >= '".$from."'
            AND DATE(tbladvance_payment.prepared_datetime) <= '".$to."'
            ");
            $prev_date=date('Y-m-d',strtotime($from.' -1 day'));
            $date_txt=date('F d, Y',strtotime($from)).' TO '.date('F d, Y',strtotime($to));
        }
        $result_prev = DB::table('waybill.tbladvance_payment')
        ->where('tbladvance_payment.pca_account_no',$pca_no)
        ->where('tbladvance_payment.advance_payment_status',1)
        ->whereNotNull('tbladvance_payment.pca_account_no')
        ->whereRaw("DATE(tbladvance_payment.prepared_datetime) <= '".$prev_date."'")
        ->selectRaw("
        IFNULL(SUM(tbladvance_payment.withdraw),0)-IFNULL(SUM(tbladvance_payment.deposit),0) as prev_balance
        ")
        ->first()
        ;



        $result =$result
        ->selectRaw("
        tbladvance_payment.particulars,
        (
            SELECT
            GROUP_CONCAT(
                CONCAT(IFNULL(wb_adj.waybill_no,wb_adj.transactioncode),'~',adj_reversal.transactioncode)
            SEPARATOR '^' )
            FROM waybill.tbladjustment adj_reversal
            LEFT OUTER JOIN waybill.tblwaybills wb_adj ON wb_adj.transactioncode=adj_reversal.transactioncode

            WHERE adj_reversal.reference_no=tbladvance_payment.reference_no
            AND UPPER(adj_reversal.adjustment_type) ='REVERSAL OF PAYMENT'

        ) as reversal_payment_details,
        IFNULL(tbladvance_payment.withdraw,0) as withdraw,
        IFNULL(tbladvance_payment.deposit,0) as deposit,
        DATE_FORMAT(tbladvance_payment.prepared_datetime, '%Y/%m/%d') as pdate ,
        (
            SELECT

            GROUP_CONCAT(
                CONCAT(
                    DATE(tblorcrdetails.transaction_date),'~',
                    CASE WHEN tblorcrdetails.pasabox_cf=1 THEN tblorcrdetails.pasabox_cf_ref_no ELSE tblorcrdetails.transactioncode END,'~',
                    CASE WHEN tblorcrdetails.pasabox_cf=1 THEN  tblorcrdetails.pasabox_cf_ref_no ELSE IFNULL(IFNULL(tblwaybills.waybill_no,tblwaybills.transactioncode),'') END,'~',
                    IFNULL(tblorcrdetails.onlinepayment,''),'~',
                    IFNULL(tblonline_payment.verification_code,''),'~',
                    IFNULL(tblonline_payment.onlinepayment_date,''),'~',
                    IFNULL(ol_bank.bank_name,''),'~',
                    tblorcrdetails.checkpayment,'~',
                    IFNULL(tblchecks.check_no,''),'~',
                    IFNULL(check_bank.bank_name,''),'~',
                    IFNULL(tblchecks.check_date,''),'~',
                    IFNULL(tblorcrdetails.cashpayment,''),'~',
                    tblorcrdetails.pasabox_cf,'~',
                    IFNULL(source.branchoffice_description,''),'~',
                    IFNULL(destination.branchoffice_description,''),'~',
                    IFNULL(tblpublication_added_transaction.publication_added_transaction_id,''),'~',
                    IFNULL(DATE_FORMAT(tblpublication_added_transaction.issue_date,'%Y/ %m/ %d'),'')
                )
                SEPARATOR '^' )
            FROM waybill.tblorcrdetails
            LEFT OUTER JOIN waybill.tblwaybills ON tblwaybills.transactioncode=tblorcrdetails.transactioncode
            LEFT OUTER JOIN waybill.tblonline_payment ON tblonline_payment.onlinepayment_id=tblorcrdetails.onlinepayment_id
            AND tblorcrdetails.onlinepayment > 0 AND tblorcrdetails.onlinepayment_id IS NOT NULL
            LEFT OUTER JOIN waybill.tblbanks ol_bank ON ol_bank.bank_no=tblonline_payment.bank_no
            LEFT OUTER JOIN waybill.tblchecks ON tblchecks.unique_check_no=tblorcrdetails.unique_check_no
            AND tblorcrdetails.checkpayment > 0 AND tblorcrdetails.unique_check_no IS NOT NULL
            LEFT OUTER JOIN waybill.tblbanks check_bank ON check_bank.bank_no=tblchecks.bank_no
            LEFT OUTER JOIN doff_configuration.tblbranchoffice source ON source.branchoffice_no=tblwaybills.sourcebranch_id
            LEFT OUTER JOIN doff_configuration.tblbranchoffice destination ON destination.branchoffice_no=tblwaybills.destinationbranch_id
            LEFT OUTER JOIN waybill.tblpublication_details ON tblpublication_details.transactioncode=tblwaybills.transactioncode
            AND tblpublication_details.publication_added_transaction_details_id IS NOT NULL
            LEFT OUTER JOIN waybill.tblpublication_added_transaction_details ON tblpublication_added_transaction_details.publication_added_transaction_details_id=tblpublication_details.publication_added_transaction_details_id
            LEFT OUTER JOIN waybill.tblpublication_added_transaction ON tblpublication_added_transaction.publication_added_transaction_id=tblpublication_added_transaction_details.publication_added_transaction_id
            WHERE tblorcrdetails.reference_no=tbladvance_payment.reference_no  AND tblorcrdetails.deposit > 0
        ) as payment_details
        ")
        ->orderBy('tbladvance_payment.prepared_datetime','ASC')
        ->get();



        $result[] = (object) [
            'withdraw'=>$result_prev->prev_balance,
            'deposit'=>0,
            'pdate'=>'PREVIOUS BALANCE',
            'payment_details'=>''
        ];

        if($type=='print'){

            $details = DB::table('waybill.tblpca_accounts')
            ->where('tblpca_accounts.pca_account_no',$pca_no)
            ->selectRaw("full_name")
            ->first();

            $details_pdf = view('pca.ledger',compact('result','details','date_txt'));

            PDF::SetTitle('PREMIUM ACCOUNT LEDGER');
            PDF::setHeaderCallback(function($pdf){
                $pdf->SetFont('helvetica', 'B', 8);
                // Title
                $html = '<table><tr>
                <td width="25%"></td>
                <td width="16%"><br><br><img src="/images/logo.jpg" style="width:80px; height: 50px;"></td>
                <td ><br><br>Daily Overland Freight Forwarder<br>JRE Bldg., Rizal Street Daraga<br> Albay, Philippines 4501</td></tr></table>';
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'bottom', $autopadding = true);
            });

            PDF::setFooterCallback(function($pdf){
                $pdf->SetY(-15);
                $pdf->SetFont('helvetica', 'I', 8);
                $tDate = date("F j, Y, h:i a");
                $html = '<table><tr><td align="right">Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages().'</td></tr><tr><td align="right">Date: '.$tDate.'</td></tr></table>';
		        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'bottom', $autopadding = true);
            });
            PDF::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-5, PDF_MARGIN_RIGHT);
            //PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-5);
            PDF::AddPage('L', 'A4');
            PDF::SetFont('helvetica', '', 8);
            PDF::WriteHTML($details_pdf,true, false, true, false, '');
            PDF::Output('doff_premium_account_ledger.pdf');
            PDF::reset();
        }else{
             echo json_encode($result);
        }



    }
    public function create_as_guest(){

        try {
            $ddStocks = ''; $ddUnits= '';$ddCities='';

            $stocks = WStock::whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get()->unique('stock_description');

            $units = WUnit::orderBy('unit_description','ASC')->get();
            $cities=City::with('province')->orderBy('cities_name','ASC')->get();
            foreach($stocks as $key=>$row){
                $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
            }
            foreach($units as $key=>$row){
                $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
            }
            foreach($cities as $city){
                $ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
            }
            $term = Term::where('type','online-booking')->first();

            $provinces = Province::with('city')->orderBy('province_name','ASC')->get();
            return view('waybills.create_as_guest',compact('ddStocks','ddUnits','ddCities','term','provinces'));
        } catch (QueryException $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (\Throwable $th) {
            $this->error_log($th,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        } catch (Exception $e) {
            $this->error_log($e,url()->current());
            return response()->json(['message'=>session('errorId'),'type'=>'error'],422);
        }
    }

    public function create_as_guest_post2(BookingAsGuestRequest $request){
        if((bool)$request->subscribe){
            if($request->step2['email']!=null){
                $token =  Crypt::encrypt(date("m-d-Y H:i:s.u"));

                Mail::send('newsletter.email_body',['token'=>$token], function($message) use($request){
                    $message->to($request['email_subscribe'],'')->subject('Newsletter Subscription');
                    $message->from('newsletter@dailyoverland.com',env('MAIL_NAME'));
                });
                $new_subscriber = new NewsletterSubscriber;
                $new_subscriber->email_address = $request['email_subscribe'];
                $new_subscriber->token =$token;
                $new_subscriber->save();

                NewsletterSubscriber::create([
                    'token'=>$token,
                    'email_address'=>$request->step2['email'],
                    'subscription_status'=>1,
                    'confirmed_datetime'=>now()
                ]);

                $recordlogs = new RecordLogs;
                $recordlogs->recordlogs = 'CUSTOMER SUBSCRIBED TO NEWSLETTER -- FOR CONFIRMATION (LINK SENT)';
                $recordlogs->email = $request->step2['email'];
                $recordlogs->save();

            }
        }
    }

    public function create_as_guest_post(BookingAsGuestRequest $request){
        DB::beginTransaction();
        // DB::connection('dailyove_online_site')->beginTransaction();
        // DB::connection('recordlogs')->beginTransaction();
        try{
            $contact_person_id = null;
            if($request->step1['type']==0){
                $contact_person = ContactPerson::firstOrNew(['email'=>$request->step1['email']]);

                if(!$contact_person->exists){
                    $contact_person->fill([
                        'lname'=>html_entity_decode($request->step1['lname']),
                        'fname'=>html_entity_decode($request->step1['fname']),
                        'mname'=>html_entity_decode($request->step1['mname']),
                        'gender'=>'DEFAULT',
                        'email'=>html_entity_decode($request->step1['email']),
                        'contact_no'=>$request->step1['contact_no'],
                    ])->save();
                }
                $contact_person_id = $contact_person->id;
            }

            $shipper = [
                'use_company'=>$request->step2['use_company'],
                'lname'=>$request->step2['use_company']==1 ? '' : $request->step2['lname'],
                'fname'=>$request->step2['use_company']==1 ? '' :$request->step2['fname'],
                'mname'=>$request->step2['mname']!=null ? $request->step2['mname'] : '',
                'company'=>$request->step2['company']!=null ? $request->step2['company'] : '',
                'email'=>$request->step2['email']!=null ? $request->step2['email'] : '',
                'business_category_id'=>$request->step2['business_category_id'] != null && $request->step2['business_category_id'] != 'none' && $request->step2['business_category_id'] != '' ? $request->step2['business_category_id']: 0,
                'contact_no'=>$request->step2['contact_no']
            ];

            $shipper_address = [
                'street'=>$request->step2['street'],
                'barangay'=>$request->step2['barangay'],
                'sectorate_no'=>$request->step2['sectorate_no']
            ];

            $shipper_id=null; $shipper_address_id=null;

            $shipper_added_columns = [
                'fileas'=>$request->step2['use_company']==1 ? $request->step2['company'] : $request->step2['lname'].', '.$request->step2['fname'].($request->step2['mname']!=null ? ' '.$request->step2['mname'] : ''),
                'contact_id'=>$this->generate_contact_id()
            ];

            $shipper = array_merge($shipper,$shipper_added_columns);
            $shipper_info = Contact::create($shipper);
            $shipper_id = $shipper_info->contact_id;

            $shipper_city_data=$this->city_data($request->step2['city']);
            $shipper_address_added_columns = [
                'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                'user_id'=>$shipper_id,
                'city'=>$shipper_city_data['cities_name'],
                'province'=>$shipper_city_data['province']['province_name'],
                'postal_code'=>$shipper_city_data['postal_code'],
            ];
            $shipper_address = array_merge($shipper_address,$shipper_address_added_columns);
            $shipper_address_info = UserAddress::create($shipper_address);
            $shipper_address_id = $shipper_address_info->useraddress_no;



            $consignee = [
                'use_company'=>$request->step3['use_company'],
                'lname'=>$request->step3['use_company']==1 ? '' : $request->step3['lname'],
                'fname'=>$request->step3['use_company']==1 ? '' : $request->step3['fname'],
                'mname'=>$request->step3['mname']!=null ? $request->step3['mname'] : '',
                'company'=>$request->step3['company']!=null ? $request->step3['company'] : '',
                'email'=>$request->step3['email']!=null ? $request->step3['email'] : '',
                'business_category_id'=>$request->step3['business_category_id'] != null && $request->step3['business_category_id'] != 'none' && $request->step3['business_category_id'] != '' ? $request->step3['business_category_id']: 0,
                'contact_no'=>$request->step3['contact_no']
            ];

            $consignee_address = [
                'street'=>$request->step3['street'],
                'barangay'=>$request->step3['barangay'],
                'sectorate_no'=>$request->step3['sectorate_no']
            ];

            $consignee_id=null; $consignee_address_id = null;


            $consignee_added_columns = [
                'fileas'=>$request->step3['use_company']==1 ? $request->step3['company'] : $request->step3['lname'].', '.$request->step3['fname'].($request->step3['mname']!=null ? ' '.$request->step3['mname'] : ''),
                'contact_id'=>$this->generate_contact_id()
            ];

            $consignee = array_merge($consignee,$consignee_added_columns);
            $consignee_info = Contact::create($consignee);
            $consignee_id = $consignee_info->contact_id;

            $consignee_city_data=$this->city_data($request->step3['city']);
            $consignee_address_added_columns = [
                'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                'user_id'=>$consignee_id,
                'city'=>$consignee_city_data['cities_name'],
                'province'=>$consignee_city_data['province']['province_name'],
                'postal_code'=>$consignee_city_data['postal_code'],
            ];
            $consignee_address = array_merge($consignee_address,$consignee_address_added_columns);
            $consignee_address_info = UserAddress::create($consignee_address);
            $consignee_address_id = $consignee_address_info->useraddress_no;

            $mode_payment='';
            $mode_payment_io='';
            $mode_payment_email='';

            if( $request->step4['payment_type']=='CI' ){
                $mode_payment=$request->step4['mode_payment'];

                if( $request->step4['mode_payment']==2 ){
                    $mode_payment_io=$request->step4['mode_payment_io'];

                    if( $request->step4['mode_payment_io']==2 ){
                        $mode_payment_email=$request->step4['mode_payment_email'];
                    }
                }
            }


            $discount_coupon='';
            if($request->step4['discount_coupon_action']==1 && $request->step4['discount_coupon'] !='' ){
                $discount_coupon=$request->step4['discount_coupon'];
            }

            $waybill_data = [
                'reference_no'=>'OL-'.date('y').$this->random_alph_num(3,2),
                'prepared_by'=>'',
                'transactiondate'=>date('Y-m-d',strtotime(Carbon::now())),
                'prepared_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'shipper_id'=>$shipper_id,
                'shipper_address_id'=>$shipper_address_id,
                'consignee_id'=>$consignee_id,
                'consignee_address_id'=>$consignee_address_id,
                'shipment_type'=>$request->step4['shipment_type'],
                'destinationbranch_id'=>$request->step4['destinationbranch_id'],
                'declared_value'=>$request->step4['declared_value'],
                'payment_type'=>$request->step4['payment_type'],
                'is_guest'=>1,
                'contact_person_id'=>$contact_person_id,
                'pickup'=>$request->step4['pu_checkbox'],
                'delivery'=>$request->step4['del_checkbox'],
                'pickup_sector_id'=>$request->step4['pu_checkbox']==1 ? $request->step4['pu_sector'] : '',
                'delivery_sector_id'=>$request->step4['del_checkbox']==1 ? $request->step4['del_sector'] : '',
                'pickup_date'=>$request->step4['pu_checkbox']==1 ? date('Y-m-d',strtotime( $request->step4['pu_date'] )) : null,
                'pickup_sector_street'=>$request->step4['pu_checkbox']==1 ? ($request->step4['pu_street'] != null ? $request->step4['pu_street'] :'') : '',
                'delivery_sector_street'=>$request->step4['del_checkbox']==1 ? ($request->step4['del_street'] != null ? $request->step4['del_street'] :'') : '' ,
                'mode_payment' =>  $mode_payment != '' ? $mode_payment: null,
                'mode_payment_io' =>  $mode_payment_io != '' ? $mode_payment_io: null,
                'mode_payment_email' =>  $mode_payment_email != '' ? $mode_payment_email: null,
                'discount_coupon' =>  $discount_coupon != '' ? $discount_coupon: null

            ];

            $waybill=Waybill::create($waybill_data);

            WaybillTrackAndTrace::create([
                'trackandtrace_status'=>'CREATED ONLINE BOOKING WITH REF#: '.$waybill->reference_no.' ADDED BY GUEST',
                'online_booking'=>$waybill->reference_no
            ]);

            WaybillContact::create([
                'waybill_contacts_no'=>$request->step2['contact_no'],
                'waybill_contacts_no_type'=>1,
                'waybill_shipper_consignee'=>1,
                'reference_no'=>$waybill->reference_no,
            ]);

            WaybillContact::create([
                'waybill_contacts_no'=>$request->step3['contact_no'],
                'waybill_contacts_no_type'=>1,
                'waybill_shipper_consignee'=>2,
                'reference_no'=>$waybill->reference_no,
            ]);


            $unit = $request->step4['unit']; $quantity = $request->step4['quantity'];
            foreach($request->step4['item_description'] as $key=>$item){
                $waybill_shipment_id = 'K'.$this->random_num(6);
                WaybillShipment::create([
                    'waybill_shipment_id'=> $waybill_shipment_id,
                    'reference_no'=>$waybill->reference_no,
                    'item_code'=>$item,
                    'item_description'=>$this->stock_description($item),
                    'unit_no'=>$unit[$key],
                    'unit_description'=>$this->unit_description($unit[$key]),
                    'quantity'=>$quantity[$key],
                    'freight_amount'=>0,
                    'weight'=>0,
                    'lenght'=>0,
                    'height'=>0,
                    'width'=>0,
                    'cargo_type_id'=>''
                ]);

                WaybillShipmentMultiple::create([
                    'waybill_shipment_id'=> $waybill_shipment_id,
                    'reference_no'=>$waybill->reference_no,
                    'itemcode'=>$item,
                    'itemdescription'=>$this->stock_description($item),
                    'quantity'=>$quantity[$key],
                    'weight'=>0,
                    'lenght'=>0,
                    'height'=>0,
                    'width'=>0
                ]);

            }
            $this->title='Success!';
            $this->message='Booking has been created';
            $this->return_data=$waybill;
            $this->type = 'success';
            $this->reference_no = Crypt::encrypt($waybill->reference_no);

            $to_name=$request->step1['type']==0 ? $request->step1['lname'].','.$request->step1['fname'].$request->step1['mname'] : $request->step2['lname'].', '.$request->step2['fname'].' '.$request->step2['mname'];
            $to_email=$request->step1['type']==0 ? $request->step1['email'] : $request->step2['email'];
            $reference_no=$waybill->reference_no;
            DB::commit();


            // $imagePath = public_path("/images/doff logo.png");
            // $image = "data:image/png;base64,".base64_encode(file_get_contents($imagePath));
            $pdf_data =[
                //'image'=>$image,
                'term'=>Term::where('type','online-booking')->first(),
                'data'=>Waybill::where('reference_no',$waybill->reference_no)
                        ->with([
                            'shipper',
                            'consignee',
                            'branch',
                            'waybill_shipment',
                            'shipper_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'consignee_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            }
                            ])->first()
            ];

            //$pdf = BPDF::loadView('waybills.reference_pdf',$pdf_data);
            //$data = ['reference_no'=>$waybill->reference_no,'name'=>$to_name];
            // Mail::send('waybills.send_printable_link',$data, function($message) use($to_name,$to_email,$pdf,$reference_no){
            //     $message->to($to_email,$to_name)->subject(env('MAIL_SUBJECT'));
            //     $message->from(env('MAIL_USERNAME'),env('MAIL_NAME'));
            //     $message->attachData($pdf->output(), $reference_no.".pdf");
            // });

            $content = [
                'pdf_data' => $pdf_data,
                'reference_no' => $waybill->reference_no,
                'to_name'=>$to_name
            ];
            Mail::to($to_email)->send(new WaybillMail($content));


            if((bool)$request->subscribe){
                if($request->step2['email']!=null){
                    $token =  Crypt::encrypt(date("m-d-Y H:i:s.u"));

                    Mail::send('newsletter.email_body',['token'=>$token], function($message) use($request){
                        $message->to($request['email_subscribe'],'')->subject('Newsletter Subscription');
                        $message->from('newsletter@dailyoverland.com',env('MAIL_NAME'));
                    });
                    $new_subscriber = new NewsletterSubscriber;
                    $new_subscriber->email_address = $request['email_subscribe'];
                    $new_subscriber->token =$token;
                    $new_subscriber->save();

                    NewsletterSubscriber::create([
                        'token'=>$token,
                        'email_address'=>$request->step2['email'],
                        'subscription_status'=>0
                    ]);

                    $recordlogs = new RecordLogs;
                    $recordlogs->recordlogs = 'CUSTOMER SUBSCRIBED TO NEWSLETTER -- FOR CONFIRMATION (LINK SENT)';
                    $recordlogs->email = $request->step2['email'];
                    $recordlogs->save();

                }
            }
            // DB::connection('dailyove_online_site')->commit();
            // DB::connection('recordlogs')->commit();

        }catch(Exception $e){
            DB::rollBack();
            // DB::rollback('dailyove_online_site');
            // DB::rollback('recordlogs');
            $this->title='Ooops!';
            $this->message='Your booking is unsuccessful!';
            $this->return_data=null;
            $this->type = 'error';
        }


        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type,'key'=>$this->reference_no],200);

        // $validations = Validator::make($request->all(),[

        //     'contact_person.lname'=>$request->contact_person['type']==0 ? 'required' : '',
        //     'contact_person.fname'=>$request->contact_person['type']==0 ? 'required' : '',
        //     'contact_person.email'=>$request->contact_person['type']==0 ? 'required' : '',
        //     'contact_person.contact_no'=>$request->contact_person['type']==0 ? 'required' : '',

        //     'shipper.lname'=>$request->shipper['use_company']==true ? '' : 'required',
        //     'shipper.fname'=>$request->shipper['use_company']==true ? '' :'required',
        //     'shipper.email'=>'required_if:contact_person.type,1',
        //     'shipper.barangay'=>'required',

        //     'consignee.lname'=>$request->consignee['use_company']==true ? '' :'required',
        //     'consignee.fname'=>$request->consignee['use_company']==true ? '' :'required',
        //     'consignee.barangay'=>'required',

        //     'waybill.payment_type'=>'required',
        //     'waybill.shipment_type'=>'required',
        //     'waybill.destinationbranch_id'=>'required',
        //     'waybill.declared_value'=>'required',


        //     'shipments'=>'required'

        // ]);

        // $title='';
        // $message='';
        // $type='';
        // $data=null;
        // $json_ref_no = null;
        // $reference_no='';
        // $errors = null;
        // $http_status = 422;

        // if($validations->passes()){
        //     $contact_person_id = null;

        //     if($request->contact_person['type']==0){
        //         $contact_person = ContactPerson::firstOrNew(['email'=>$request->contact_person['email']]);

        //         if(!$contact_person->exists){
        //             $contact_person->fill([
        //                 'lname'=>html_entity_decode($request->contact_person['lname']),
        //                 'fname'=>html_entity_decode($request->contact_person['fname']),
        //                 'mname'=>html_entity_decode($request->contact_person['mname']),
        //                 'gender'=>'DEFAULT',
        //                 'email'=>html_entity_decode($request->contact_person['email']),
        //                 'contact_no'=>$request->contact_person['contact_no'],
        //             ])->save();
        //         }
        //         $contact_person_id = $contact_person->id;
        //     }



        //     $shipper_address_id=null;
        //     $consignee_address_id=null;





        //     $shipper_data = [
        //         'email'=>html_entity_decode($request->shipper['email']),
        //         'fileas'=>$request->shipper['use_company']==1 ? html_entity_decode($request->shipper['company']) : html_entity_decode($request->shipper['lname'].', '.$request->shipper['fname'].' '.$request->shipper['mname']),
        //         'fname'=>$request->shipper['fname']!=null ? html_entity_decode($request->shipper['fname']) : '',
        //         'lname'=>$request->shipper['lname']!=null ? html_entity_decode($request->shipper['lname']) : '',
        //         'contact_no'=>$request->shipper['contact_no'],
        //         'contact_id'=>"OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8),
        //         'business_category_id'=>$request->shipper['business_category_id']!=null ? $request->shipper['business_category_id']!=null : 0,
        //         'gender'=>'DEFAULT',
        //         'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
        //         'mname'=>$request->shipper['mname']!='' ? html_entity_decode($request->shipper['mname']) : '',
        //         'company'=>$request->shipper['company']!='' ? html_entity_decode($request->shipper['company']) : '',
        //         'position'=> '',
        //         'religion'=>'',
        //         'nationality'=>'',
        //         'civil_status'=>'',
        //         'branchoffice_id'=>0,
        //         'discount'=>0,
        //         'bir2306'=>0,
        //         'bir2307'=>0,
        //         'vat'=>0,
        //         'employee'=>0,
        //         'customer'=>0,
        //         'use_company'=>0,
        //         'email_verification'=>0,
        //         'profile_photo_path'=>'',
        //         'department'=>null,
        //         'doff_account'=>null,
        //         'contact_status'=>0,
        //         'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))
        //     ];

        //     $consignee_data = [
        //         'email'=>html_entity_decode($request->consignee['email']),
        //         'fileas'=>$request->consignee['use_company']==1 ? html_entity_decode($request->consignee['company']) : html_entity_decode($request->consignee['lname'].', '.$request->consignee['fname'].' '.$request->consignee['mname']),
        //         'fname'=>$request->consignee['fname']!=null ? html_entity_decode($request->consignee['fname']) : '',
        //         'lname'=>$request->consignee['lname']!=null ? html_entity_decode($request->consignee['lname']) : '',
        //         'contact_no'=>$request->consignee['contact_no'],
        //         'business_category_id'=>$request->consignee['business_category_id']!=null ? $request->consignee['business_category_id'] :0,
        //         'contact_id'=>"OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8),
        //         'gender'=>'DEFAULT',
        //         'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
        //         'mname'=>$request->consignee['mname']!='' ? html_entity_decode($request->consignee['mname']) : '',
        //         'company'=>$request->consignee['company']!='' ? html_entity_decode($request->consignee['company']) : '',
        //         'position'=> '',
        //         'religion'=>'',
        //         'nationality'=>'',
        //         'civil_status'=>'',
        //         'branchoffice_id'=>0,
        //         'discount'=>0,
        //         'bir2306'=>0,
        //         'bir2307'=>0,
        //         'vat'=>0,
        //         'employee'=>0,
        //         'customer'=>0,
        //         'use_company'=>0,
        //         'email_verification'=>0,
        //         'profile_photo_path'=>'',
        //         'department'=>null,
        //         'doff_account'=>null,
        //         'contact_status'=>0,
        //         'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))
        //     ];

        //     $shipper = Contact::create($shipper_data);

        //     $shipper_address=UserAddress::create([
        //         'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
        //         'street'=>html_entity_decode($request->shipper['street']),
        //         'barangay'=>html_entity_decode($request->shipper['barangay']),
        //         'city'=>$request->shipper['city'],
        //         'province'=>$request->shipper['province'],
        //         'postal_code'=>$request->shipper['postal_code'],
        //         'user_id'=>$shipper->contact_id,
        //         'address_def'=>'0',
        //         'address_caption'=>'NULL',
        //         'added_by'=>null]);
        //     $shipper_address_id=$shipper_address->useraddress_no;

        //     $consignee=Contact::create($consignee_data);

        //     $consignee_address=UserAddress::create(['useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
        //                                 'street'=>$request->consignee['street'],
        //                                 'barangay'=>$request->consignee['barangay'],
        //                                 'city'=>$request->consignee['city'],
        //                                 'province'=>$request->consignee['province'],
        //                                 'postal_code'=>$request->consignee['postal_code'],
        //                                 'user_id'=>$consignee->contact_id,
        //                                 'address_def'=>'0',
        //                                 'address_caption'=>'NULL',
        //                                 'added_by'=>null]);
        //     $consignee_address_id=$consignee_address->useraddress_no;

        //     $waybill=Waybill::create([
        //         'shipper_id'=>$shipper->contact_id,
        //         'shipper_address_id'=>$shipper_address_id,
        //         'consignee_id'=>$consignee->contact_id,
        //         'consignee_address_id'=>$consignee_address_id,
        //         'payment_type'=>$request->waybill['payment_type'],
        //         'shipment_type'=>$request->waybill['shipment_type'],
        //         'destinationbranch_id'=>$request->waybill['destinationbranch_id'],
        //         'declared_value'=>$request->waybill['declared_value'],
        //         'transactiondate'=>date('Y-m-d',strtotime(Carbon::now())),
        //         'prepared_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
        //         'reference_no'=>date('y').$this->random_alph_num(3,2),
        //         'prepared_by'=>$shipper->contact_id,
        //         'chargeto_id'=>'',
        //         'pickup'=>0,
        //         'pickup_truckpanel'=>'',
        //         'delivery'=>0,
        //         'delivery_truckpanel'=>'',
        //         'shipper_instruction'=>'',
        //         'pickup_sector_id'=>'',
        //         'delivery_sector_id'=>'',
        //         'delivery_sector_street'=>'',
        //         'delivery_sector_barangay'=>'',
        //         'delivery_sector_city'=>'',
        //         'delivery_sector_province'=>'',
        //         'delivery_sector_postalcode'=>'',
        //         'pickup_sector_street'=>'',
        //         'pickup_sector_barangay'=>'',
        //         'pickup_sector_city'=>'',
        //         'pickup_sector_province'=>'',
        //         'pickup_sector_postalcode'=>'',
        //         'booking_status'=>0,
        //         'customer_id'=>null,
        //         'is_guest'=>1,
        //         'contact_person_id'=>$contact_person_id
        //     ]);

        //     $data=$this->encrypt_decrypt('encrypt',$waybill->reference_no);

        //     $shipments_request = $request->shipments;

        //     $shipments = [];
        //     $shipments_multiple = [];
        //     foreach($shipments_request as $row){

        //         $stock = WStock::firstOrNew(['stock_no'=>$row['description']]);
        //         $unit = WUnit::firstOrNew(['unit_no'=>$row['unit']]);

        //         if($stock->exists && $unit->exists){
        //             $row_preset = array_key_exists("preset",$row) ? Dimension::firstOrNew(['dimension_id',$row['preset']]) : null;
        //             $waybill_shipment_id = 'K'.$this->random_num(6);
        //             array_push($shipments,[
        //                 'waybill_shipment_id'=> $waybill_shipment_id,
        //                 'reference_no'=>$waybill->reference_no,
        //                 'item_code'=>$stock->stock_no,
        //                 'item_description'=>$stock->stock_description,
        //                 'unit_no'=>$unit->unit_no,
        //                 'unit_description'=>$unit->unit_description,
        //                 'quantity'=>$row['quantity'],
        //                 'freight_amount'=>0,
        //                 'weight'=>$row_preset!=null ? $row_preset->weight : 0,
        //                 'lenght'=>$row_preset!=null ? $row_preset->lenght : 0,
        //                 'height'=>$row_preset!=null ? $row_preset->height : 0,
        //                 'width'=>$row_preset!=null ? $row_preset->width : 0,
        //                 'cargo_type_id'=>''
        //             ]);

        //             array_push($shipments_multiple,[
        //                 'waybill_shipment_id'=> $waybill_shipment_id,
        //                 'reference_no'=>$waybill->reference_no,
        //                 'itemcode'=>$stock->stock_no,
        //                 'itemdescription'=>$stock->stock_description,
        //                 'quantity'=>$row['quantity'],
        //                 'weight'=>$row_preset!=null ? $row_preset->weight : 0,
        //                 'lenght'=>$row_preset!=null ? $row_preset->lenght : 0,
        //                 'height'=>$row_preset!=null ? $row_preset->height : 0,
        //                 'width'=>$row_preset!=null ? $row_preset->width : 0
        //             ]);
        //         }
        //     }

        //     $waybill_shipments = WaybillShipment::insert($shipments);
        //     $waybill_shipments_multiple = WaybillShipmentMultiple::insert($shipments_multiple);

        //     $to_name=$request->contact_person['type']==0 ? $request->contact_person['lname'].','.$request->contact_person['fname'].$request->contact_person['mname'] : $request->shipper['lname'].', '.$request->shipper['fname'].' '.$request->shipper['mname'];
        //     $to_email=$request->contact_person['type']==0 ? $request->contact_person['email'] : $request->shipper['email'];
        //     $reference_no=$waybill->reference_no;

        //     $pdf_data =[
        //         'term'=>Term::where('type','online-booking')->first(),
        //         'data'=>Waybill::where('reference_no',$waybill->reference_no)
        //                 ->with([
        //                     'shipper',
        //                     'consignee',
        //                     'branch',
        //                     'waybill_shipment',
        //                     'shipper_address'=>function($query){
        //                             $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        //                     },
        //                     'consignee_address'=>function($query){
        //                             $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        //                     }
        //                     ])->first()
        //     ];

        //     $pdf = PDF::loadView('waybills.reference_pdf',$pdf_data);
        //     $data = ['reference_no'=>$reference_no,'name'=>$to_name];
        //     Mail::send('waybills.send_printable_link',$data, function($message) use($to_name,$to_email,$pdf,$reference_no){
        //         $message->to($to_email,$to_name)->subject(env('MAIL_SUBJECT','DOFF Online Booking'));
        //         $message->from(env('MAIL_USERNAME','booking@dailyoverland.com'),env('MAIL_NAME','DOFF'));
        //         $message->attachData($pdf->output(), $reference_no.".pdf");
        //     });

        //     $title='Success!';
        //     $message='Booking has been created and already sent to your email';
        //     $type='success';
        //     $http_status=200;
        //     return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'reference_no'=>Crypt::encrypt($reference_no),'errors'=>$errors],200);
        // }else{
        //     $title='Ooops!';
        //     $message='Please fill up the form completely';
        //     $type='error';
        //     $errors = $validations->errors();
        //     return response()->json(['title'=>$title,'message'=>$message,'type'=>$type,'errors'=>$errors],422);


        // }



    }


    public function sendPDF($reference_no){
        try {
            $reference_no = Crypt::decryptString($reference_no);
            $waybill_data = Waybill::where('reference_no',$reference_no)
                            ->with([
                                'shipper',
                                'consignee',
                                'branch',
                                'waybill_shipment',
                                'shipper_address'=>function($query){
                                        $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                                },
                                'consignee_address'=>function($query){
                                        $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                                }
                                ])->first();

            $contact_data = $waybill_data->contact_person_id!=null ? ContactPerson::where('contact_id',$waybill_data->contact_person_id)->first() : Contact::where('contact_id',$waybill_data->shipper_id)->first();

            $to_name=$waybill_data->contact_person_id!=null ? $contact_data->lname.', '.$contact_data->fname.' '.$contact_data->mname : $contact_data->fileas;
            $to_email=$contact_data->email;

            $pdf_data =[
                'term'=>Term::where('type','online-booking')->first(),
                'data'=>$waybill_data
            ];

            // $pdf = PDF::loadView('waybills.reference_pdf',$pdf_data);
            // $data = ['reference_no'=>$reference_no,'name'=>$to_name];
            // Mail::send('waybills.send_printable_link',$data, function($message) use($to_name,$to_email,$pdf,$reference_no){
            //     $message->to($to_email,$to_name)->subject(env('MAIL_SUBJECT','DOFF Online Booking'));
            //     $message->from(env('MAIL_USERNAME','booking@dailyoverland.com'),env('MAIL_NAME','DOFF'));
            //     $message->attachData($pdf->output(), $reference_no.".pdf");
            // });
            $content = [
                'pdf_data' => $pdf_data,
                'reference_no' => $reference_no,
                'to_name'=>$to_name
            ];
            Mail::to($to_email)->send(new WaybillMail($content));

        } catch (DecryptException $e) {
            //
        }

    }

    public function request_qoutation_as_guest(){
        $ddStocks = '';
        $ddUnits= '';
        //$stocks = WStock::whereRaw("stock_description != '' && LEFT(stock_no,2) !='KS' && LEFT(stock_no,2) !='OL'")->orderBy('stock_description','ASC')->get()->unique('stock_description');
        $units = WUnit::orderBy('unit_description','ASC')->get();
        $provinces = Province::with('city')->orderBy('province_name','ASC')->get();
        // foreach($stocks as $key=>$row){
        //     $ddStocks.="<option value=".$row->stock_no.">".$row->stock_description."</option>";
        // }
        foreach($units as $key=>$row){
            $ddUnits.="<option value=".$row->unit_no.">".$row->unit_description."</option>";
        }
        $ddUnitConversion='';
        $unit_conversion = UnitConversion::get();

        foreach($unit_conversion as $key=>$row){
            $ddUnitConversion.="<option value=".$row->unit_convertion_id.">".$row->unit_name."</option>";

        }
        // $term = Term::where('type','online-booking')->first();
        return view('waybills.request_qoutation_as_guest',compact('ddStocks','ddUnits','provinces','unit_conversion','ddUnitConversion'));

    }

    public function request_quotation_as_guest_post(QuotationRequest $request){

        $request_quotation_id = $this->random_alph_num(3,2).'-'.$this->random_alph_num(5,1);
        $request_quotation = [
            'request_quotation_id'=>$request_quotation_id,
            'request_by_fileas'=>$request->lname.($request->lname!=null && $request->fname!=null && $request->mname!=null ? ', ' : '').$request->fname.' '.$request->mname,
            'request_by_lname'=>$request->lname,
            'request_by_fname'=>$request->fname,
            'request_by_mname'=>$request->mname,
            'request_by_cno'=>$request->contact_no,
            'request_by_email'=>$request->email,
            'request_by_street'=>'',
            'request_by_barangay'=>'',
            'request_by_province'=>'',
            'request_by_postalcode'=>'',
            'origin_branch'=>$request->origin_branch,
            'destination_branch'=>$request->destination_branch,
            'declared_value'=>$request->declared_value,
            'delivery'=>$request->has('delivery') ? 1 : 0,
            'delivery_truckpanel'=>'',
            'delivery_sectorate'=>$request->has('sectorate_no_delivery') ? $request->sectorate_no_delivery : '',
            'pickup'=>$request->has('pickup') ? 1 : 0,
            'pickup_truckpanel'=>'',
            'pickup_sectorate'=>$request->has('sectorate_no_pickup') ? $request->sectorate_no_pickup : '',
            'request_status'=>0,
            'pickup_street'=>$request->has('pickup') ? $request->street_pickup : '',
            'pickup_brgy'=>$request->has('pickup') ? $request->barangay_pickup : '',
            'pickup_city'=>$request->has('pickup') ? $request->city_pickup : null,
            'delivery_street'=>$request->has('delivery') ? $request->street_delivery : '',
            'delivery_brgy'=>$request->has('delivery') ? $request->barangay_delivery : '',
            'delivery_city'=>$request->has('delivery') ? $request->city_delivery : null
        ];

        $item_codes = $request->item_code;
        $item_names = $request->item_name;
        $unit_codes = $request->unit_code;
        $sub_quantities = $request->sub_quantity;
        $unit_weights = $request->unit_weight;
        $weights = $request->weight;
        $heights = $request->height;
        $widths = $request->width;
        $lengths = $request->length;
        $unit_dimensions = $request->unit_dimension;

        $item_count = count($item_codes);
        $request_quotation_details = [];
        $request_quotation_details_dimensions = [];

        for($i=0; $i<$item_count; $i++){
            $request_quotation_details_id = date('y').'-'.$this->random_alph_num(2,7);
            $total_qty = 0;
            foreach($sub_quantities[$i] as $sub_quantity){
                $total_qty = $total_qty + $sub_quantity;
            }
            array_push($request_quotation_details,[
                'request_quotation_details_id'=>$request_quotation_details_id,
                'request_quotation_id'=>$request_quotation_id,
                'item_id'=>$item_codes[$i],
                'item_name'=>$item_names[$i],
                'item_unit'=>$unit_codes[$i],
                'total_qty'=>$total_qty
            ]);
            $sub_items = count($sub_quantities[$i]);
            for($j=0;$j<$sub_items;$j++){
                array_push($request_quotation_details_dimensions,[
                    'request_quotation_details_id'=>$request_quotation_details_id,
                    'quantity'=>$sub_quantities[$i][$j],
                    'weight'=>$weights[$i][$j],
                    'height'=>$heights[$i][$j],
                    'width'=>$widths[$i][$j],
                    'length'=>$lengths[$i][$j],
                    'weight_utype'=>$unit_weights[$i][$j]=='kg' ? null : $unit_weights[$i][$j],
                    'dimension_utype'=>$unit_dimensions[$i][$j]=='centi' ? null : $unit_dimensions[$i][$j]
                ]);
            }
        }

        $this->message='';
        $this->type='';
        $this->title='';
        $this->return_data=null;
        DB::connection('quotation')->beginTransaction();
        try{
        //DB::transaction(function() use($request_quotation,$request_quotation_details,$request_quotation_details_dimensions){
           // try{
                $query1 = RequestQuotation::create($request_quotation);
                $query2 = RequestQuotationDetail::insert($request_quotation_details);
                $query3 = RequestQuotationDetailDimension::insert($request_quotation_details_dimensions);

                //$name = Auth::check() ? Auth::user()->name : 'GUEST';
                // WaybillTrackAndTrace::create([
                //     'trackandtrace_status'=>'CREATED QUOTATION WITH REF#: '.$request_quotation['request_quotation_id'].' ADDED BY '.$name,
                //     'reference_no'=>$request_quotation['request_quotation_id']
                // ]);
                // if(!$query1 || !$query2 || !$query3){
                //     DB::rollBack();
                //     $this->message='Request unsuccessful!';
                //     $this->type='error';
                //     $this->title='Ooops!';
                // }else{
                //     DB::commit();
                    $this->message='Request quotation has been sent!';
                    $this->type='success';
                    $this->title='Success!';
                    $this->return_data=$query1;
                // }
                DB::connection('quotation')->commit();

            }catch(Exception $e){
                DB::rollback('quotation');
                //DB::rollBack();
                $this->title='Ooops!';
                $this->message='Request unsuccessful!';
                $this->type='error';
            }
        //});

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type,'data'=>$this->return_data]);

    }

    public function generatePDF(){

        $waybill=Waybill::where('reference_no','20250KE')
                        ->with([
                            'shipper',
                            'consignee',
                            'branch',
                            'waybill_shipment',
                            'shipper_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            },
                            'consignee_address'=>function($query){
                                    $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                            }
                            ])->first();
        $data = ['name'=>'FRANKLY DEXISNE','term'=>Term::where('type','online-booking')->first(),'data'=>$waybill];

        // $pdf = PDF::loadView('waybills.test', $data);
        // return $pdf->stream();


    }

    public function other_link(){
        $data = ['name'=>'FRANKLY DEXISNE','term'=>Term::where('type','online-booking')->first()];
        $pdf = PDF::loadView('waybills.testing', $data);
        Mail::send('waybills.testing',$data, function($message) use($pdf){
            $message->to('franklydexisne@dailyoverland.com','FRANKLY DEXISNE JR')->subject('TEST WITH ATTACHMENT');
            $message->from(env('MAIL_USERNAME', 'franklydexisne@dailyoverland.com'),'DOFF IT');
            $message->attachData($pdf->output(), "invoice.pdf");
        });
    }

    public function get_waybill_details($tcode){
        echo json_encode(
            RefWaybill::where('transactioncode',$tcode)
                    ->with([
                        'source',
                        'destination',
                        'shipper',
                        'consignee',
                        'shipper_address',
                        'consignee_address',
                        'waybill_shipment',
                    ])
                    ->get()
                    ->toArray()
        );

    }
    public function change_ol_reference($action){

        while (true){

            if($action=='pasabox'){
                $reference_number = 'OLP-'.date('y').random_alph_num(3,2);
            }else{
                $reference_number = 'OL-'.date('y').random_alph_num(3,2);
            }

            $reference_count= Waybill::where('reference_no',$reference_number)->count();
            if($reference_count<=0){
                break;
            }
        }
        return $reference_number;
    }
    public function pca_waybill_shipment_dimensions_details(Request $request){
        $id=base64_decode($request->id);
        $result = DB::select("
        SELECT

		wsm.quantity,wsm.weight,wsm.height,wsm.width,wsm.lenght,
		wsm.each_all,wsm.price,wsm.total_amount,wsm.itemcode,
		wsm.item_id,wsm.chargeable_weight,tblwaybills.specialrate_template_id,
		wsm.weight_sd,wsm.dimension_sd

		FROM waybill.tblwaybill_shipments_multipleitem  wsm
		LEFT OUTER JOIN waybill.tblwaybills ON tblwaybills.transactioncode=wsm.transactioncode
		WHERE wsm.waybill_shipment_id='".$id."'
		ORDER BY wsm.item_id DESC
        ");
        echo json_encode($result);

    }
    public function pca_waybill_shipment_details(Request $request){

        $tcode=base64_decode($request->tcode);
        $result = DB::select("
        SELECT tblwaybill_shipments.item_description, tblwaybill_shipments.unit_description, tblwaybill_shipments.unit_no,tblcargotype.cargotype_description,tblwaybill_shipments.waybill_shipment_id,
		tblwaybill_shipments.quantity,tblwaybill_shipments.cargo_type_id,tblwaybill_shipments.item_code,
		 (CASE WHEN tblwaybills.specialrate_template_id IS NOT NULL THEN tblstocks.special_amount ELSE tblstocks.stock_amount END  ) as stock_amount
		FROM waybill.tblwaybill_shipments
		LEFT OUTER JOIN waybill.tblcargotype ON tblcargotype.cargo_type_id=tblwaybill_shipments.cargo_type_id
		LEFT OUTER JOIN waybill.tblstocks ON tblstocks.stock_no=tblwaybill_shipments.item_code
		LEFT OUTER JOIN waybill.tblwaybills ON tblwaybills.transactioncode=tblwaybill_shipments.transactioncode
		where tblwaybill_shipments.transactioncode='".$tcode."'
		ORDER BY tblwaybill_shipments.item_id ASC
        ");

        echo json_encode($result);
    }
    public function pca_get_waybill_details(Request $request){

        $tcode=base64_decode($request->tcode);

        $result = DB::select("
        SELECT tblwaybills.waybill_no,LEFT( tblwaybills.transactioncode,2) as tcode, IFNULL(tblwaybills.tracking_no,'') as tracking_no,

		CAST(CONVERT(IFNULL(pby.fileas,'') USING utf8) AS binary) as pby_fileas,

		CAST(CONVERT(IFNULL(sby.fileas,'') USING utf8) AS binary) as sby_fileas,
		CAST(CONVERT(sby.contact_no USING utf8) AS binary) as sby_contact_no,
		CAST(CONVERT(sby.email USING utf8) AS binary) as sby_email,
		sby.vat  as sby_vat,

		CAST(CONVERT(IFNULL(cby.fileas,'') USING utf8) AS binary) as cby_fileas,
		CAST(CONVERT(cby.contact_no USING utf8) AS binary) as cby_contact_no,
		CAST(CONVERT(cby.email USING utf8) AS binary) as cby_email,
		cby.vat  as cby_vat,

		CAST(CONVERT(IFNULL(chby.fileas,'') USING utf8) AS binary) as chby_fileas,
		CAST(CONVERT(chby.contact_no USING utf8) AS binary) as chby_contact_no,
		CAST(CONVERT(chby.email USING utf8) AS binary) as chby_email,
		chby.vat  as chby_vat,

		DATE_FORMAT(tblwaybills.prepared_datetime, '%b. %d, %Y %h:%i %p') as prepared_datetime,
		DATE_FORMAT(tblwaybills.transactiondate,'%Y/%m/%d' ) as transactiondate,tblwaybills.waybill_status,tblwaybills.transactioncode,
		sbranch.branchoffice_description as sbranch_desc,dbranch.branchoffice_description as dbranch_desc,
		tblwaybills.discount_coupon,tblwaybills.reference_no,
		IFNULL(tblwaybills.shipper_contactno,'') as shipper_contactno,tblwaybills.shipper_street,tblwaybills.shipper_barangay,
		tblwaybills.shipper_city,tblwaybills.shipper_province,tblwaybills.shipper_postalcode,
		IFNULL(tblwaybills.consignee_contactno,'') as consignee_contactno,IFNULL(tblwaybills.consignee_street,'') as consignee_street,IFNULL(tblwaybills.consignee_barangay,'') as consignee_barangay,
		IFNULL(tblwaybills.consignee_city,'') as consignee_city,IFNULL(tblwaybills.consignee_province,'') as consignee_province,IFNULL(tblwaybills.consignee_postalcode,'') as consignee_postalcode,
		tblwaybills.freight_amount,tblwaybills.discount_amount,tblwaybills.pickup_charge,tblwaybills.delivery_charge,
		tblwaybills.othercharges_amount,tblwaybills.declared_value,tblwaybills.insurance_amount,
		tblwaybills.withholdingttax_amount,tblwaybills.finaltax_amount,tblwaybills.vat_amount,tblwaybills.amount_due,
		tblwaybills.sourcebranch_id,tblwaybills.destinationbranch_id,tblwaybills.discount_rate,tblwaybills.prepared_by,
		tblwaybills.shipment_type,tblwaybills.cancellation_reason,tblwaybills.control_staff,tblwaybills.specialrate_template_id,
		tblwaybills.pickup_sector_id,tblwaybills.delivery_sector_id,
		tblwaybills.delivery,tblwaybills.pickup,

		CAST(CONVERT(IFNULL(tblwaybills.delivery_sector_street,'') USING utf8) AS binary) as delivery_sector_street,
		CAST(CONVERT(IFNULL(tblwaybills.delivery_sector_barangay,'') USING utf8) AS binary) as delivery_sector_barangay,
		CAST(CONVERT(IFNULL(tblwaybills.delivery_sector_city,'') USING utf8) AS binary) as delivery_sector_city,
		CAST(CONVERT(IFNULL(tblwaybills.delivery_sector_province,'') USING utf8) AS binary) as delivery_sector_province,
		CAST(CONVERT(IFNULL(tblwaybills.delivery_sector_postalcode,'') USING utf8) AS binary) as delivery_sector_postalcode,

		CAST(CONVERT(IFNULL(tblwaybills.pickup_sector_street,'') USING utf8) AS binary) as pickup_sector_street,
		CAST(CONVERT(IFNULL(tblwaybills.pickup_sector_barangay,'') USING utf8) AS binary) as pickup_sector_barangay,
		CAST(CONVERT(IFNULL(tblwaybills.pickup_sector_city,'') USING utf8) AS binary) as pickup_sector_city,
		CAST(CONVERT(IFNULL(tblwaybills.pickup_sector_province,'') USING utf8) AS binary) as pickup_sector_province,
		CAST(CONVERT(IFNULL(tblwaybills.pickup_sector_postalcode,'') USING utf8) AS binary) as pickup_sector_postalcode,
		tblwaybills.shipper_id,tblwaybills.consignee_id,
		(
			SELECT IFNULL(SUM(disc_breakdown_amt),0)
			FROM waybill.tblwaybills_disc_breakdown wdb
			WHERE wdb.disc_breakdown_type =2
			AND wdb.transactioncode=tblwaybills.transactioncode

		) as bd_rebate,
		(
			SELECT IFNULL(SUM(disc_breakdown_amt),0)
			FROM waybill.tblwaybills_disc_breakdown wdb
			WHERE wdb.disc_breakdown_type =1
			AND wdb.transactioncode=tblwaybills.transactioncode

		) as bd_disc,
		IFNULL(tblwaybills.vat_discount,0) as vat_discount

		FROM  waybill.tblwaybills
		LEFT OUTER JOIN payroll.tblcontacts as pby ON  pby.contact_id=tblwaybills.prepared_by
		LEFT OUTER JOIN waybill.tblcontacts as sby ON  sby.contact_id=tblwaybills.shipper_id
		LEFT OUTER JOIN waybill.tblcontacts as cby ON  cby.contact_id=tblwaybills.consignee_id
		LEFT OUTER JOIN waybill.tblcontacts as chby ON  chby.contact_id=tblwaybills.chargeto_id
		LEFT OUTER JOIN doff_configuration.tblbranchoffice as sbranch ON  sbranch.branchoffice_no=tblwaybills.sourcebranch_id
		LEFT OUTER JOIN doff_configuration.tblbranchoffice as dbranch ON  dbranch.branchoffice_no=tblwaybills.destinationbranch_id
		WHERE tblwaybills.transactioncode='".$tcode."'
        ")
        ;

        echo json_encode($result);

    }
    public function get_pca_deactivation_pending_count(Request $request){
        $pca_no=base64_decode($request->pca_no);
        $action=base64_decode($request->action);
        if($action==3){
            $result = DB::table('waybill.tblpca_account_renewal')
            ->where('pca_account_renewal_status',0);
        }else{
            $result = DB::table('waybill.tblpca_account_termination')
            ->where('pca_account_termination_status',0);
        }
        $result = $result

        ->where('pca_account_no',$pca_no)->count();

        echo json_encode($result);
    }
    public function pca_account_check_apply_renewal(Request $request){

        $pca_no=base64_decode($request->pca_no);

        $result = DB::table('waybill.tblpca_accounts_emailing')
        ->where('tblpca_accounts_emailing.email_type',6)
        ->where('tblpca_accounts_emailing.pca_account_no',$pca_no)
        ->whereRaw(" tblpca_accounts_emailing.exp_date_for_renewal >= CURRENT_DATE ")
        ->orderBy('tblpca_accounts_emailing.exp_date_for_renewal','DESC')
        ->limit(1)
        ->leftJoin('waybill.tblpca_account_renewal',function($join){
            $join->on('tblpca_account_renewal.pca_accounts_emailing_id', '=', 'tblpca_accounts_emailing.pca_accounts_emailing_id')
            ->where(function($query){
                $query->where('tblpca_account_renewal.pca_account_renewal_status',1)
                ->orWhere('tblpca_account_renewal.pca_account_renewal_status',0);
            })
            ;
        })
        ->selectRaw("
        tblpca_accounts_emailing.pca_accounts_emailing_id,
        tblpca_account_renewal.pca_account_renewal_id,
        tblpca_account_renewal.pca_account_renewal_status
        ")
        ->get();


        echo json_encode($result);
    }
    public function pca_account_exp_date(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $result = DB::table('waybill.tblpca_accounts_approval_date')
        ->where('pca_account_no',$pca_no)
        ->crossJoin('waybill.tblpca_expiration')
        ->orderBy('approval_date','DESC')
        ->limit(1)
        ->selectRaw("
        DATE_FORMAT((
            CASE
                WHEN tblpca_expiration.expiration_year_month='year'
                    THEN DATE_ADD(tblpca_accounts_approval_date.approval_date, INTERVAL tblpca_expiration.expiration_count YEAR)
                WHEN tblpca_expiration.expiration_year_month='month'
                    THEN DATE_ADD(tblpca_accounts_approval_date.approval_date, INTERVAL tblpca_expiration.expiration_count MONTH)
                ELSE
                    tblpca_accounts_approval_date.approval_date
            END
        ),'%M %d,%Y') as exp_date
        ")
        ->get();

        echo json_encode($result);
    }
    public function get_pca_notif(){

        $data = DB::table('waybill.tblpca_on_off_notif')
        ->leftJoin('waybill.tblpca_off_notif_details',function($join){
            $join->on('tblpca_on_off_notif.pca_on_off_notif_id', '=', 'tblpca_off_notif_details.pca_on_off_notif_id')
            ->where('tblpca_off_notif_details.pca_account_no',Auth::user()->contact_id);
        })
        ->leftJoin('waybill.tblpca_accounts',function($join){
            $join->where('tblpca_accounts.pca_account_no',Auth::user()->contact_id);
        })
        ->selectRaw("
            tblpca_on_off_notif.pca_on_off_notif_id,
            tblpca_on_off_notif.notif_name,
            tblpca_on_off_notif.notif_desc,
            tblpca_off_notif_details.pca_off_notif_details_id,
            tblpca_on_off_notif.soa_notif,
            tblpca_accounts.soa_range
        ")
        ->orderBy('tblpca_on_off_notif.notif_name')
        ->get();
        echo json_encode($data);

    }
    public function pca_account_soa_range(Request $request){
        try{

            DB::beginTransaction();
            $soa_range=base64_decode($request->soa_range);

            DB::table('waybill.tblpca_accounts')
                ->where('pca_account_no',Auth::user()->contact_id)
                ->update([
                    'soa_range' => $soa_range
                ]);

            $log='UPDATE SOA NOTIFICATION RANGE TO MONTHLY.';
            if($soa_range==2){
                $log='UPDATE SOA NOTIFICATION RANGE TO BI-MONTHLY.';
            }
            DB::table('recordlogs.tblpca_accounts_logs')
            ->insert([
                'module' => 'PERSONAL/CORPORATE ACCOUNT WEBSITE',
                'logs' => $log,
                'pca_account_no' => Auth::user()->contact_id,
            ]);

            $msg='Successfully Updated.';
            DB::commit();
            return response()->json(['message'=>$msg,'type'=>1],200);

        } catch (\Throwable $e) {

            DB::rollBack();
            return response()->json(['message'=>$e->getMessage(),'type'=>0],422);

        }
    }
    public function on_off_notif_pca_account(Request $request){
        try{

            DB::beginTransaction();

            $off_notif=base64_decode($request->off_notif);
            $notif_id=base64_decode($request->notif_id);

            if($notif_id=='ALL'){

                if( $off_notif ==1 ){
                    $log='OFF ALL NOTIFIFICATION';
                    $details = DB::table('waybill.tblpca_on_off_notif')
                    ->leftJoin('waybill.tblpca_off_notif_details',function($join){
                        $join->on('tblpca_off_notif_details.pca_on_off_notif_id', '=', 'tblpca_off_notif_details.pca_on_off_notif_id')
                        ->where('tblpca_off_notif_details.pca_account_no',Auth::user()->contact_id);
                    })
                    ->whereNull('tblpca_off_notif_details.pca_off_notif_details_id')
                    ->selectRaw("
                        tblpca_on_off_notif.pca_on_off_notif_id
                    ")
                    ->get();
                    if(count($details) > 0){
                        foreach($details as $data){
                            DB::table('waybill.tblpca_off_notif_details')
                                ->insert([
                                    'pca_account_no' => Auth::user()->contact_id,
                                    'pca_on_off_notif_id' => $data->pca_on_off_notif_id
                            ]);
                        }
                    }
                }else{
                    $log='ON ALL NOTIFIFICATION';
                    DB::table('waybill.tblpca_off_notif_details')
                    ->where('pca_account_no',Auth::user()->contact_id)
                    ->delete();
                }
            }else{

                $details = DB::table('waybill.tblpca_on_off_notif')->where('pca_on_off_notif_id',$notif_id)->first();
                if( $off_notif ==1 ){
                    $log='OFF NOTIFIFICATION FOR '.$details->notif_name;
                    DB::table('waybill.tblpca_off_notif_details')
                        ->insert([
                            'pca_account_no' => Auth::user()->contact_id,
                            'pca_on_off_notif_id' => $notif_id
                    ]);
                }else{

                    $log='ON NOTIFIFICATION FOR '.$details->notif_name;
                    DB::table('waybill.tblpca_off_notif_details')
                        ->where('pca_account_no',Auth::user()->contact_id)
                        ->where('pca_on_off_notif_id',$notif_id)
                        ->delete();

                }
            }

            DB::table('recordlogs.tblpca_accounts_logs')
                ->insert([
                    'module' => 'PERSONAL/CORPORATE ACCOUNT WEBSITE',
                    'logs' => $log,
                    'pca_account_no' => Auth::user()->contact_id,
                ]);


            $msg='Successfully Updated.';
            DB::commit();
            return response()->json(['message'=>$msg,'type'=>1],200);

        } catch (\Throwable $e) {

            DB::rollBack();
            return response()->json(['message'=>$e->getMessage(),'type'=>0],422);

        }
    }

    public function get_pca_requirements(Request $request){

        $pca_no=base64_decode($request->pca_no);
        $at=base64_decode($request->at);

        $details = DB::table('waybill.tblpca_accounts')->where('pca_account_no',$pca_no)->first();
        $pc=$details->personal_corporate;

        $result = DB::table('waybill.tblpca_requirements')
        ->leftJoin('waybill.tblpca_requirements_details as application',function($join){
            $join->on('tblpca_requirements.pca_requirements_id', '=', 'application.pca_requirements_id')
            ->where('application.application_termination',1);
        })
        ->leftJoin('waybill.tblpca_requirements_details as termination',function($join){
            $join->on('tblpca_requirements.pca_requirements_id', '=', 'termination.pca_requirements_id')
            ->where('termination.application_termination',2);
        })
        ->leftJoin('waybill.tblpca_requirements_details as renewal',function($join){
            $join->on('tblpca_requirements.pca_requirements_id', '=', 'renewal.pca_requirements_id')
            ->where('renewal.application_termination',3);
        })
        ;

        if($at==1){
            $result = $result->whereNotNull('application.tblpca_requirements_details_id');
            if($pc=='personal'){
                $result = $result->where('application.personal',1);
            }
            elseif($pc=='corporate'){
                $result = $result->where('application.corporate',1);
            }
            elseif($pc=='partnership'){
                $result = $result->where('application.partnership',1);
            }
            elseif($pc=='publication'){
                $result = $result->where('application.publication',1);
            }

        }
        elseif($at==2){
            $result = $result->whereNotNull('termination.tblpca_requirements_details_id');
            if($pc=='personal'){
                $result = $result->where('termination.personal',1);
            }
            elseif($pc=='corporate'){
                $result = $result->where('termination.corporate',1);
            }
            elseif($pc=='partnership'){
                $result = $result->where('termination.partnership',1);
            }
            elseif($pc=='publication'){
                $result = $result->where('termination.publication',1);
            }
        }
        elseif($at==3){
            $result = $result->whereNotNull('renewal.tblpca_requirements_details_id');
            if($pc=='personal'){
                $result = $result->where('renewal.personal',1);
            }
            elseif($pc=='corporate'){
                $result = $result->where('renewal.corporate',1);
            }
            elseif($pc=='partnership'){
                $result = $result->where('renewal.partnership',1);
            }
            elseif($pc=='publication'){
                $result = $result->where('renewal.pa_personalublication',1);
            }
        }

        $result = $result->selectRaw("
            tblpca_requirements.pca_requirements_id,
            tblpca_requirements.pca_requirements_name,
            tblpca_requirements.upload_file,
            IFNULL(application.personal,0) as a_personal,
            IFNULL(application.corporate,0) as a_corporate,
            IFNULL(application.partnership,0) as a_partnership,
            IFNULL(application.publication,0) as a_publication,
            IFNULL(termination.personal,0) as t_personal,
            IFNULL(termination.corporate,0) as t_corporate,
            IFNULL(termination.partnership,0) as t_partnership,
            IFNULL(termination.publication,0) as t_publication,
            IFNULL(renewal.personal,0) as r_personal,
            IFNULL(renewal.corporate,0) as r_corporate,
            IFNULL(renewal.partnership,0) as r_partnership,
            IFNULL(renewal.publication,0) as r_publication
        ")
        ->get();

        echo json_encode($result);
    }
    public function deactivate_pca_account(Request $request){

        try{

            DB::beginTransaction();


            $no=$request->deactivate_account_no;
            $action=$request->pca_deactivate_action;

            if($action==3){
                DB::table('waybill.tblpca_account_renewal')
                ->insert([
                    'pca_account_no' => $no,
                    'remarks' => $request->deactivate_account_remarks,
                    'pca_accounts_emailing_id' => $request->pca_renewal_email_id
                ]);
            }else{
                 DB::table('waybill.tblpca_account_termination')
                ->insert([
                    'pca_account_no' => $no,
                    'remarks' => $request->deactivate_account_remarks
                ]);
            }

            $pca_account_termination_id=DB::getPdo()->lastInsertId();

            if(isset($request->deactivate_account_req)){
                foreach($request->deactivate_account_req as $req_id){
                    $req_name=$request['deactivate_account_req_name_'.$req_id];
                    if($action==3){
                        DB::table('waybill.tblpca_account_renewal_req')
                        ->insert([
                            'pca_account_renewal_id' => $pca_account_termination_id,
                            'pca_requirements_id' => $req_id,
                            'pca_requirements_name' => $req_name
                        ]);
                    }else{
                        DB::table('waybill.tblpca_account_termination_req')
                        ->insert([
                            'pca_account_termination_id' => $pca_account_termination_id,
                            'pca_requirements_id' => $req_id,
                            'pca_requirements_name' => $req_name
                        ]);
                    }
                    $pca_account_termination_req_id=DB::getPdo()->lastInsertId();

                    if(isset($request['deactivate_account_req_file_'.$req_id])){

                            $folder_name='TERMINATION';
                            if($action==3){
                                $folder_name='RENEWAL';
                            }

                            $images = $request->file('deactivate_account_req_file_'.$req_id);

                            if(!Storage::disk('system_files')->exists('system_files/PCA/'))
                            {
                                Storage::disk('system_files')->makeDirectory('system_files/PCA/', 0777, true);
                            }
                            if(!Storage::disk('system_files')->exists('system_files/PCA/'.$folder_name))
                            {
                                Storage::disk('system_files')->makeDirectory('system_files/PCA/'.$folder_name.'/', 0777, true);
                            }

                            if(!Storage::disk('system_files')->exists('system_files/PCA/'.$folder_name.'/'.$pca_account_termination_id))
                            {
                                Storage::disk('system_files')->makeDirectory('system_files/PCA/'.$folder_name.'/'.$pca_account_termination_id, 0777, true);
                            }

                            foreach($images  as $file){

                                if( $file != '' ){

                                    $name=$file->getClientOriginalName();
                                    $name = pathinfo($name, PATHINFO_FILENAME);
                                    $name=preg_replace('/\s+/', '', $name);

                                    $data=imagejpeg(
                                        imagecreatefromstring(
                                            file_get_contents($file->getPathName())
                                        ),
                                        Storage::disk('system_files')->path('system_files/PCA/'.$folder_name.'/'.$pca_account_termination_id.'/'.$name.'.jpg')
                                    );
                                    $file_name=$name.'.jpg';
                                    if($data){
                                        if($action==3){
                                            DB::table('waybill.tblpca_account_renewal_req_files')
                                            ->insert([
                                                'upload_file' => $pca_account_termination_id.'/'.$file_name,
                                                'pca_account_renewal_req_id' => $pca_account_termination_req_id
                                            ]);
                                        }else{
                                            DB::table('waybill.tblpca_account_termination_req_files')
                                            ->insert([
                                                'upload_file' => $pca_account_termination_id.'/'.$file_name,
                                                'pca_account_termination_req_id' => $pca_account_termination_req_id
                                            ]);
                                        }
                                    }
                                }

                            }

                    }
                }
            }
            $log='APPLICATION FOR DEACTIVATION/TERMINATION SUCCESSFULLY SUBMITTED.';
            if($action==3){
                $log='APPLICATION FOR RENEWAL SUCCESSFULLY SUBMITTED.';
            }
            DB::table('recordlogs.tblpca_accounts_logs')
                ->insert([
                    'module' => 'PERSONAL/CORPORATE ACCOUNT WEBSITE',
                    'logs' => $log,
                    'pca_account_no' => $no
                ]);

            $msg='Successfully Submitted.';
            DB::commit();
            return response()->json(['message'=>$msg,'type'=>1],200);

        } catch (\Throwable $e) {

            DB::rollBack();
            return response()->json(['message'=>$e->getMessage(),'type'=>0],422);

        }
    }
    public function pca_brgy(Request $request){
        $result= DB::table('waybill.tblsectorate2')
        ->where('tblsectorate2.city_id',base64_decode($request->city))
        ->orderByRaw('tblsectorate2.barangay ASC')
        ->selectRaw("tblsectorate2.sectorate_no,tblsectorate2.barangay")
        ->get();
        echo json_encode($result);
    }

    public function pca_city(){
        $result=City::whereNotNull('tblprovinces.province_id')
        ->leftjoin('waybill.tblprovinces','tblcitiesminicipalities.province_id','=','tblprovinces.province_id')
        ->orderByRaw('tblprovinces.province_name ASC,tblcitiesminicipalities.cities_name ASC')
        ->selectRaw("tblprovinces.province_name,tblcitiesminicipalities.cities_id,tblcitiesminicipalities.cities_name")
        ->get();
        echo json_encode($result);
    }
    public function pca_agent_list(Request $request){

        $result= DB::table('waybill.tblpublication_agent')
        ->where('tblpublication_agent.pca_account_no',base64_decode($request->pca_no))
        ->leftJoin('waybill.tblsectorate2','tblpublication_agent.brgy','=','tblsectorate2.sectorate_no')
        ->leftJoin('waybill.tblcitiesminicipalities','tblsectorate2.city_id','=','tblcitiesminicipalities.cities_id')
        ->leftJoin('waybill.tblprovinces','tblcitiesminicipalities.province_id','=','tblprovinces.province_id')
        ->selectRaw("
        tblpublication_agent.publication_agent_id,
        tblpublication_agent.publication_agent_name,
        UPPER(tblpublication_agent.street) as street,
        tblpublication_agent.contact_person,
        tblpublication_agent.contact_no,
        UPPER(tblsectorate2.barangay) as barangay,
        UPPER(tblcitiesminicipalities.cities_name) as cities_name,
        UPPER(tblprovinces.province_name) as province_name,
        tblsectorate2.city_id,
        tblsectorate2.sectorate_no
        ");
        if( base64_decode($request->id) !='ALL' && base64_decode($request->id) !='SORT' ){
            $result=$result->where('tblpublication_agent.publication_agent_id',base64_decode($request->id));
        }
        // if(base64_decode($request->id) =='SORT'){
            $result=$result->orderByRaw('-tblpublication_agent.agent_sequence DESC');
        // }else{
            //$result=$result->orderByRaw('tblpublication_agent.publication_agent_name ASC');
        //}
        $result=$result->get();
        echo json_encode($result);
    }
    public function pub_transaction_save_agent_address(Request $request){

        $id=base64_decode($request->id);
        $sector=base64_decode($request->sector);
        $street=base64_decode($request->street);

        $count_exist= DB::table('waybill.tblpublication_agent')
        ->whereRaw("UPPER(street)='".strtoupper($street)."' AND brgy='".$sector."' ")
        ->count();
        if($count_exist > 0){

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Already set as current address.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>1
            ],200);

        }else{
            try{

                DB::beginTransaction();

                $msg='Updated';
                DB::table('waybill.tblpublication_agent')
                ->where('publication_agent_id',$id)
                ->update([
                    'brgy'=>$sector,
                    'street'=>$street
                ]);


                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully '.$msg.'.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1
                ],200);


            } catch (\Exception $e) {

                DB::rollback();
                $msg='UPDATING CURRENT ADDRESS OF ';
                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN '.$msg.' AGENT.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }

        }


    }
    public function pub_transaction_save_agent(Request $request){

        $name=base64_decode($request->name);
        $cperson=base64_decode($request->cperson);
        $cperson_no=base64_decode($request->cperson_no);
        $street=base64_decode($request->street);
        $brgy=base64_decode($request->brgy);
        $city=base64_decode($request->city);
        $province=base64_decode($request->province);
        $pca_no=base64_decode($request->pca_no);

        $count_exist= DB::table('waybill.tblpublication_agent')->whereRaw("UPPER(publication_agent_name)='".strtoupper($name)."' ")->count();
        if($count_exist > 0){

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Agent already added.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>1
            ],200);

        }else{
            try{

                DB::beginTransaction();

                $sector='';

                $sector_details= DB::table('waybill.tblsectorate2')
                ->whereRaw("
                UPPER(tblsectorate2.barangay)='".strtoupper($brgy)."'
                AND UPPER(tblcitiesminicipalities.cities_name)='".strtoupper($city)."'
                AND UPPER(tblprovinces.province_name)='".strtoupper($province)."'
                ")
                ->leftjoin('waybill.tblcitiesminicipalities','tblsectorate2.city_id','=','tblcitiesminicipalities.cities_id')
                ->leftjoin('waybill.tblprovinces','tblcitiesminicipalities.province_id','=','tblprovinces.province_id')
                ->selectRaw("tblsectorate2.sectorate_no")
                ->first();
                if($sector_details){
                    $sector=$sector_details->sectorate_no;
                }


                $msg='added';
                DB::table('waybill.tblpublication_agent')
                ->insert([
                    'publication_agent_name'=>strtoupper($name),
                    'brgy'=>$sector=='' ? null : $sector,
                    'street'=>$street,
                    'contact_person'=>$cperson,
                    'contact_no'=>$cperson_no,
                    'pca_account_no'=>$pca_no

                ]);
                $publication_agent_id=DB::getPdo()->lastInsertId();


                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully '.$msg.'.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1,
                    'id'=>$publication_agent_id
                ],200);


            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();
                $msg='ADDING';
                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN '.$msg.' AGENT.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }
        }
    }
    public function save_pca_agent(Request $request){

        $count_exist= DB::table('waybill.tblpublication_agent')
                ->whereRaw("UPPER(publication_agent_name)='".strtoupper($request->agent_name)."' ");

        if($request->agent_id !=''){
            $count_exist=$count_exist->where('publication_agent_id','!=',$request->agent_id);
        }
        $count_exist=$count_exist->count();

        if($count_exist > 0){

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Agent already added.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);

        }else{

            try{

                DB::beginTransaction();

                if($request->agent_id !=''){
                    $msg='updated';
                    DB::table('waybill.tblpublication_agent')
                    ->where('publication_agent_id',$request->agent_id)
                    ->update([
                        'publication_agent_name'=>strtoupper($request->agent_name),
                        'brgy'=>$request->agent_brgy,
                        'street'=>$request->agent_street,
                        'contact_person'=>$request->agent_cperson,
                        'contact_no'=>$request->agent_cperson_no,
                        'pca_account_no'=>$request->agent_pca_no

                    ]);
                }else{
                    $msg='added';
                    DB::table('waybill.tblpublication_agent')
                    ->insert([
                        'publication_agent_name'=>strtoupper($request->agent_name),
                        'brgy'=>$request->agent_brgy,
                        'street'=>$request->agent_street,
                        'contact_person'=>$request->agent_cperson,
                        'contact_no'=>$request->agent_cperson_no,
                        'pca_account_no'=>$request->agent_pca_no

                    ]);
                }


                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully '.$msg.'.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1
                ],200);


            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();
                $msg='ADDING';
                if($request->agent_id !=''){
                    $msg='UPDATING';
                }

                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN '.$msg.' AGENT.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }
        }
    }
    public function add_publication_transaction(Request $request){

        $dr_exist= DB::table('waybill.tblpublication_added_transaction')
        ->where('pca_account_no',$request->pub_transaction_pno)
        ->where('issue_date',$request->pub_transaction_add_date)
        ->count();

        if($dr_exist > 0){
            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Delivery Receipt for '.date('F d,Y',strtotime($request->pub_transaction_add_date)).' already added.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);
        }else{

            try{

                DB::beginTransaction();

                DB::table('waybill.tblpublication_added_transaction')
                ->insert([
                    'pca_account_no'=>$request->pub_transaction_pno,
                    'issue_date'=>$request->pub_transaction_add_date

                ]);
                $publication_added_transaction_id=DB::getPdo()->lastInsertId();

                DB::table('recordlogs.tblpublication_added_transaction_logs')
                ->insert([
                    'module' => 'CLIENT WEBSITE',
                    'logs' => 'SUCESSFULLY ADDED DELIVERY RECEIPT.',
                    'publication_added_transaction_id' => $publication_added_transaction_id
                ]);
                $client_sequence=1;
                if(isset($request->pub_add_transaction_agent_id)){
                    foreach($request->pub_add_transaction_agent_id as $i => $agent_id){

                        $agent_name=$request['pub_add_transaction_agent_name'][$i];
                        $cperson=$request['pub_add_transaction_cperson'][$i];
                        $cperson_no=$request['pub_add_transaction_cperson_no'][$i];
                        $agent_address_street=$request['pub_add_transaction_agent_address_street'][$i];
                        $agent_address_brgy=$request['pub_add_transaction_agent_address_brgy'][$i];
                        $agent_address_city=$request['pub_add_transaction_agent_address_city'][$i];
                        $agent_address_province=$request['pub_add_transaction_agent_address_province'][$i];
                        $main=$request['pub_add_transaction_main_qty'][$i];
                        $tabloid=$request['pub_add_transaction_tabloid_qty'][$i];

                        if($main==''){ $main=0; }
                        if($tabloid==''){ $tabloid=0; }
                        if (strpos($agent_id, 'NONE') !== false) {
                            $agent_id='';
                        }
                        DB::table('waybill.tblpublication_added_transaction_details')
                        ->insert([
                            'publication_added_transaction_id'=>$publication_added_transaction_id,
                            'publication_agent_id'=>$agent_id=='' ?  null : $agent_id,
                            'agent_name'=>$agent_name,
                            'agent_contact_person'=>$cperson,
                            'agent_contact_no'=>$cperson_no,
                            'agent_address_street'=>$agent_address_street,
                            'agent_address_barangay'=>$agent_address_brgy,
                            'agent_address_city'=>$agent_address_city,
                            'agent_address_province'=>$agent_address_province,
                            'main_qty'=>$main,
                            'tabloid_qty'=>$tabloid,
                            'client_sequence' => $client_sequence
                        ]);
                        $client_sequence++;
                    }
                }

                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully Added.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1
                ],200);


            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();

                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN ADDING PUBLICATION TRANSACTION.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }
        }
    }

    public function update_publication_transaction(Request $request){

        $details= DB::table('waybill.tblpublication_added_transaction')
        ->where('publication_added_transaction_id',$request->dr_edit_id)
        ->select('pca_account_no','issue_date','confirmed_date','received_date','publication_added_transaction_status')
        ->first();

        $dr_exist= DB::table('waybill.tblpublication_added_transaction')
        ->where('pca_account_no',$details->pca_account_no)
        ->where('issue_date',$request->dr_edit_issue_date)
        ->where('publication_added_transaction_id','!=',$request->dr_edit_id)
        ->count();

        if($dr_exist > 0){
            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Delivery Receipt for '.date('F d,Y',strtotime($request->dr_edit_issue_date)).' already added.',
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);
        }
        elseif(
            $details->confirmed_date !='' ||
            $details->received_date !='' ||
            $details->publication_added_transaction_status ==1

        ){
            if(
                $details->confirmed_date !=''
                && $details->received_date==''
                && $details->publication_added_transaction_status==0
            ){
                $msg='Cannot be Edited. Delivery in Progress.';
            }
            elseif(
                $details->confirmed_date !=''
                && $details->received_date !=''
                && $details->publication_added_transaction_status==1
            ){
                $msg='Cannot be Edited. Already Delivered.';
            }
            return response()->json([
                'title'=>'Ooops!',
                'message'=>$msg,
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);

        }else{

            try{

                DB::beginTransaction();
                DB::table('waybill.tblpublication_added_transaction')
                ->where('publication_added_transaction_id',$request->dr_edit_id)
                ->update([
                    'issue_date'=>$request->dr_edit_issue_date
                ]);

                DB::table('waybill.tblpublication_added_transaction_details')
                ->where('publication_added_transaction_id',$request->dr_edit_id)
                ->delete();

                $publication_added_transaction_id=$request->dr_edit_id;

                DB::table('recordlogs.tblpublication_added_transaction_logs')
                ->insert([
                    'module' => 'CLIENT WEBSITE',
                    'logs' => 'SUCESSFULLY UPDATED DELIVERY RECEIPT.',
                    'publication_added_transaction_id' => $publication_added_transaction_id
                ]);
                $client_sequence=1;
                if(isset($request->pub_add_transaction_agent_id)){
                    foreach($request->pub_add_transaction_agent_id as $i => $agent_id){

                        $agent_name=$request['pub_add_transaction_agent_name'][$i];
                        $cperson=$request['pub_add_transaction_cperson'][$i];
                        $cperson_no=$request['pub_add_transaction_cperson_no'][$i];
                        $agent_address_street=$request['pub_add_transaction_agent_address_street'][$i];
                        $agent_address_brgy=$request['pub_add_transaction_agent_address_brgy'][$i];
                        $agent_address_city=$request['pub_add_transaction_agent_address_city'][$i];
                        $agent_address_province=$request['pub_add_transaction_agent_address_province'][$i];
                        $main=$request['pub_add_transaction_main_qty'][$i];
                        $tabloid=$request['pub_add_transaction_tabloid_qty'][$i];

                        if($main==''){ $main=0; }
                        if($tabloid==''){ $tabloid=0; }
                        if (strpos($agent_id, 'NONE') !== false) {
                            $agent_id='';
                        }
                        DB::table('waybill.tblpublication_added_transaction_details')
                        ->insert([
                            'publication_added_transaction_id'=>$publication_added_transaction_id,
                            'publication_agent_id'=>$agent_id=='' ?  null : $agent_id,
                            'agent_name'=>$agent_name,
                            'agent_contact_person'=>$cperson,
                            'agent_contact_no'=>$cperson_no,
                            'agent_address_street'=>$agent_address_street,
                            'agent_address_barangay'=>$agent_address_brgy,
                            'agent_address_city'=>$agent_address_city,
                            'agent_address_province'=>$agent_address_province,
                            'main_qty'=>$main,
                            'tabloid_qty'=>$tabloid,
                            'client_sequence' => $client_sequence
                        ]);
                        $client_sequence++;
                    }
                }

                $logs='Update Publication Delivery Receipt';
                DB::table('recordlogs.tblrecordlog')
                ->insert([
                    'module' => 'PUBLICATION DELIVERY RECEIPT',
                    'recordlog' => $logs,
                    'reference_no' => $publication_added_transaction_id
                ]);

                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully Updated.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1
                ],200);


            } catch (\Exception $e) {
                //echo $e;
                DB::rollback();

                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN UPDATING PUBLICATION TRANSACTION.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }
        }
    }
    public function pub_csv_templatye_download(Request $request){
        $filename='DOFFPublicationDRTemplate';
        if(str_contains(base64_decode($request->pca_no),'dr-')){
            $id=str_replace("dr-", "",base64_decode($request->pca_no));
            $details=DB::table('waybill.tblpublication_added_transaction')
            ->where('tblpublication_added_transaction.publication_added_transaction_id',$id)
            ->select('tblpublication_added_transaction.issue_date')
            ->first();
            $filename='DOFFPublicationDR'.str_replace("-", "",$details->issue_date);
        }
        return Excel::download(new ExportExcelPublicationTemplate($request->pca_no), $filename.'.xlsx');
    }
    public function publication_import_delivery(Request $request){

        $request->validate([
            'input_file' => 'required|mimes:xlsx,xls'
        ]);
        $data = Excel::toArray([], $request->file('input_file'))[0];
        if(count($data) > 0){
            if(base64_decode($data[2][0])==$request->pub_no){
                $row_list=array();
                $unix_date = ($data[1][1] - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $UNIX_DATE = ($excel_date - 25569) * 86400;
                $row_list['issue_date']= gmdate("Y-m-d", $unix_date);

                $row_list['delivery']=array();
                $none_id=0;

                foreach ($data as $i => $datum) {
                    $sector_save='NONE';
                    if(
                        $i >= 4 && $datum[0] !=''
                    ){
                        $exist_data= DB::table('waybill.tblpublication_agent')
                        ->whereRaw("UPPER(publication_agent_name)='".strtoupper($datum[0])."' ")
                        ->select('publication_agent_id')
                        ->first();
                        if($exist_data){
                            $id=$exist_data->publication_agent_id;

                            $sector_details= DB::table('waybill.tblsectorate2')
                            ->whereRaw("
                            UPPER(tblsectorate2.barangay)='".strtoupper($datum[4])."'
                            AND UPPER(tblcitiesminicipalities.cities_name)='".strtoupper($datum[5])."'
                            AND UPPER(tblprovinces.province_name)='".strtoupper($datum[6])."'
                            ")
                            ->leftjoin('waybill.tblcitiesminicipalities','tblsectorate2.city_id','=','tblcitiesminicipalities.cities_id')
                            ->leftjoin('waybill.tblprovinces','tblcitiesminicipalities.province_id','=','tblprovinces.province_id')
                            ->selectRaw("tblsectorate2.sectorate_no")
                            ->first();
                            if($sector_details){
                                $exist_address_data= DB::table('waybill.tblpublication_agent')
                                ->whereRaw(" street='".strtoupper($datum[3])."' AND brgy='".$sector_details->sectorate_no."' AND publication_agent_id='".$id."' ")
                                ->select('publication_agent_id')
                                ->first();
                                if(!$exist_address_data){
                                    $sector_save=$sector_details->sectorate_no;
                                }

                            }

                        }else{
                            $none_id++;
                            $id='NONE'.$id;
                        }
                        array_push($datum,$id,$sector_save);
                        $row_list['delivery'][]=$datum;

                    }
                }

                 return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully uploaded.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1,
                    'row_list' =>$row_list
                ],200);

            }else{
                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Uploaded file is Incorrect. Please use Downloaded CSV Template.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);
            }
        }else{

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Uploaded file is Empty.',
                'type'=>'error',
                'icon'=>'error',
                'msg_type'=>0
            ],200);

        }
    }
    public function get_pub_dr_transaction(Request $request){

        $result['main']=DB::table('waybill.tblpublication_added_transaction')
        ->where('publication_added_transaction_id',base64_decode($request->id))
        ->selectRaw("issue_date")
        ->get();

        $result['details']=DB::table('waybill.tblpublication_added_transaction_details as details')
        ->where('details.publication_added_transaction_id',base64_decode($request->id))
        ->leftJoin('waybill.tblpublication_agent',function($join){
            $join->on('details.publication_agent_id', '=', 'tblpublication_agent.publication_agent_id')
            ->whereNotNull('details.publication_agent_id');
        })
        ->orderByRaw('-IFNULL(details.client_sequence,tblpublication_agent.agent_sequence) DESC')
        ->selectRaw("
        details.publication_added_transaction_details_id,
        details.publication_agent_id,
        details.agent_name,
        details.agent_contact_person,
        details.agent_contact_no,
        details.agent_address_street,
        details.agent_address_barangay,
        details.agent_address_city,
        details.agent_address_province,
        details.main_qty,
        details.tabloid_qty,
        details.confirmed_main_qty,
        details.confirmed_tabloid_qty,
        details.received_main_qty,
        details.received_tabloid_qty,
        details.returned_main_qty,
        details.returned_tabloid_qty,
        details.main_cprice,
        details.tabloid_cprice
        ")
        ->get();

        echo json_encode($result);
    }
    public function pub_upload_view_proof(Request $request){

        $result = DB::table('waybill.tblpublication_added_transaction_details_proof')
        ->where('publication_added_transaction_details_id',base64_decode($request->id))
        ->get();
        echo json_encode($result);
    }
    public function get_pub_dr_list(Request $request){

        $result=DB::table('waybill.tblpublication_added_transaction')
        ->whereRaw("
        tblpublication_added_transaction.pca_account_no='".base64_decode($request->pca_no)."'
        AND LEFT(tblpublication_added_transaction.issue_date,7)='".base64_decode($request->month)."'
        ")
        ->orderByRaw('tblpublication_added_transaction.issue_date DESC,tblpublication_added_transaction.added_date DESC')
        ->selectRaw("
        tblpublication_added_transaction.publication_added_transaction_id,
        DATE_FORMAT(tblpublication_added_transaction.issue_date,'%Y/%m/%d') as issue_date,
        DATE_FORMAT(tblpublication_added_transaction.added_date,'%Y/%m/%d %h:%i %p') as added_date,
        tblpublication_added_transaction.confirmed_date,
        tblpublication_added_transaction.received_date,
        tblpublication_added_transaction.publication_added_transaction_status,
        tblpublication_added_transaction.added_by
        ")
        ->get();
        echo json_encode($result);
    }
    public function remove_pub_dr(Request $request){

        $id=base64_decode($request->id);

        $details= DB::table('waybill.tblpublication_added_transaction')
        ->where('publication_added_transaction_id',$id)
        ->select('issue_date','confirmed_date','received_date','publication_added_transaction_status')
        ->first();

        if(
            $details->confirmed_date !='' ||
            $details->received_date !='' ||
            $details->publication_added_transaction_status ==1

        ){
            if(
                $details->confirmed_date !=''
                && $details->received_date==''
                && $details->publication_added_transaction_status==0
            ){
                $msg='Cannot be Removed. Delivery in Progress.';
            }
            elseif(
                $details->confirmed_date !=''
                && $details->received_date !=''
                && $details->publication_added_transaction_status==1
            ){
                $msg='Cannot be Removed. Already Delivered.';
            }
            return response()->json([
                'title'=>'Ooops!',
                'message'=>$msg,
                'type'=>'success',
                'icon'=>'error',
                'msg_type'=>2
            ],200);

        }else{

            try{

                DB::beginTransaction();


                DB::table('waybill.tblpublication_added_transaction')
                ->where('publication_added_transaction_id',$id)
                ->delete();

                $logs='Remove Publication Delivery Receipt | Issue Date: '.$details->issue_date;
                DB::table('recordlogs.tblrecordlog')
                ->insert([
                    'module' => 'PUBLICATION DELIVERY RECEIPT',
                    'recordlog' => $logs,
                    'reference_no' => $id
                ]);

                DB::commit();
                return response()->json([
                    'title'=>'Success',
                    'message'=>'Successfully Removed.',
                    'type'=>'success',
                    'icon'=>'success',
                    'msg_type'=>1
                ],200);


            } catch (\Exception $e) {

                DB::rollback();
                $msg='UPDATING CURRENT ADDRESS OF ';
                $e_id=substr(sha1(mt_rand()),17,20);
                DB::table('recordlogs.error_logs')
                ->insert([
                    'error_id'=>$e_id,
                    'error_message'=>'CLIENT WEBSITE- ERROR IN '.$msg.' AGENT.',
                    'error_description'=>$e->getMessage()
                ]);

                return response()->json([
                    'title'=>'Ooops!',
                    'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                    'type'=>'error',
                    'icon'=>'error',
                    'msg_type'=>0
                ],200);

            }

        }


    }
    public function save_agent_sorting(Request $request){

        try{

            DB::beginTransaction();

            DB::table('waybill.tblpublication_agent')
            ->where('pca_account_no',$request->agent_sorting_no)
            ->update([
                'agent_sequence'=> null
            ]);
            $count_agent=1;
            foreach($request->sort_agent as $sort_agent){
                DB::table('waybill.tblpublication_agent')
                ->where('publication_agent_id',$sort_agent)
                ->update([
                    'agent_sequence'=> $count_agent
                ]);
                $count_agent++;
            }

            DB::commit();
            return response()->json([
                'title'=>'Success',
                'message'=>'Successfully saved.',
                'type'=>'success',
                'icon'=>'success',
                'msg_type'=>1
            ],200);


        } catch (\Exception $e) {
            //echo $e;
            DB::rollback();

            $e_id=substr(sha1(mt_rand()),17,20);
            DB::table('recordlogs.error_logs')
            ->insert([
                'error_id'=>$e_id,
                'error_message'=>'CLIENT WEBSITE- ERROR IN SORTING AGENT.',
                'error_description'=>$e->getMessage()
            ]);

            return response()->json([
                'title'=>'Ooops!',
                'message'=>'Something went wrong. Error Code: '.$e_id.'. Please screenshot this and send to our customer service. Thank you.',
                'type'=>'error',
                'icon'=>'error',
                'msg_type'=>0
            ],200);

        }

    }


}

