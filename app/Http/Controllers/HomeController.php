<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FAQ\Category;
use App\FAQ\Dialect;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\OnlineSite\Waybill;
use App\Waybill\Waybill as DOFFLiveWaybill;
use App\Waybill\PODTransmittal;
use App\Waybill\Branch as DOFFLiveBranch;
use App\Waybill\Sector;
use App\Waybill\RouteTemplate;
use App\Branch;
use App\BranchFilter;
use App\Waybill\IncidentCategory;
use App\Waybill\Incident as WaybillIncident;
use App\Waybill\IncidentWaybill as WaybillIncidentWaybill;
use App\Waybill\CustomerEmail;
use App\Http\Controllers\ReferenceTrait;
use App\OnlineSite\UserAddress;
use App\OnlineSite\Contact;
use App\Waybill\ValidID;
use App\DOFFConfiguration\Branch as ConfigBranch;
use App\OnlineSite\QrcodeProfile;
use App\OnlineSite\QrcodeProfileDetails;
use App\Waybill\OnlinePayment;
use App\Waybill\OnlinePaymentConfirmation;
use App\Waybill\WaybillBanks;
use App\Waybill\TrackAndTrace as WaybillTrackAndTrace;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Image;
use App\OnlineSite\ContactVerification;
use App\OnlineSite\ShipperConsignee;
use View;
use PDF;
use App\Term;
use Auth;
use MobileDetect;
use Jenssegers\Agent\Agent;

use App\OnlineSite\User as OldUser;
use App\User;
use Illuminate\Support\Facades\Hash;

use App\Waybill\RebatePointFactor;

//use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use Crypt;
use Storage;
use Mail;
use App\Mail\WaybillMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    use ReferenceTrait;

    public function __construct()
    {
        ini_set('max_execution_time', 300);
        $this->middleware('auth')->except([
            'welcome',
            'privacy_policy',
            'terms_and_condition',
            'faq',
            'track_and_trace',
            'getCities',
            'previous',
            'branch_details',
            'track',
            'claim',
            'auth_check',
            'landing_page',
            'terms_and_condition',
            'branch_serviceable_map',
            'sector_list',
            'get_sector_schedule',
            'get_sector_province',
            'get_sector_city',
            'get_sector_brgy',
            'get_sector_details',
            'get_address_details',
            'validate_email',
            'get_id_type',
            'get_qr_code_profile',
            'profile_qr_code',
            'get_customer_details',
            'send_otp',
            'validate_otp',
            'login_validate_email',
            'validate_user_pwd',
            'enable_disable_web_otp'
        ]);

        View::share([
        'branches'=>Branch::get(),
        'branch_filters'=>BranchFilter::get(),
        'incident_categories'=>IncidentCategory::where('main_category_id',5)->get()
        ]);
    }

    public function auth_check(){
        return Auth::check() ? 1 : 0;
    }

    public function landing_page(){
        return view('welcome_part2');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $now=date('Y-m-d',strtotime(Carbon::now()));
        $waybill=Waybill::where('prepared_by',auth::user()->contact_id)
        ->where(function($query) use($now){
            $query->whereRaw("DATEDIFF('$now',transactiondate)<=7")->whereIn('booking_status',[0,2]);
        });
        $waybills = $waybill->with(['shipper_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); },'consignee_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->orderBy('transactiondate','DESC')->limit(5)->get();
        //$parent_path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()."/app/clients/";
        $parent_path =Storage::disk('local')->path('/app/clients/');
        // $storage_path = file_exists($parent_path.Auth::user()->id.'.png') ? $parent_path.Auth::user()->id.'.png' : asset('/images/default-avatar.png');
        if(file_exists($parent_path.Auth::user()->id.'.png')){
            $storage_path = $parent_path.Auth::user()->id.'.png';
            $img = file_get_contents($storage_path);
            $data = base64_encode($img);
            $image_base64 = 'data:image/png;base64,'.$data;
        }else{
            $image_base64 =asset('/images/default-avatar.png');
        }


        $view = 'home';

        return view($view,compact('waybills','waybill','image_base64'));
    }

    public function welcome(){
        return view('welcome_part2');
        // return redirect('/login');
    }

    public function track(){
        return view('track_and_trace');
    }

    public function branch_details($id){
        $data = Branch::where('id',$id)->first();
        return view('branch_details',compact('data'));
    }

    public function previous(){
        return view('welcome_backup');
    }

    public function privacy_policy(){
        $title='Privacy Policy';
        $term = Term::where('type','data-privacy')->first();
        return view('others.privacy_policy',compact('title','term'));
    }

    public function faq($dialect){

        $platform_id =  1;
        $data = Dialect::where('name',$dialect)->with([
                'category'=>function($query) use($platform_id){
                    $query->with(['question'=>function($query)use($platform_id){
                        $query->whereHas('guide',function($query) use($platform_id) {
                            $query->where('platform_id',$platform_id);
                        })
                        ->with(['guide'=>function($query) use($platform_id) {
                            $query->where('platform_id',$platform_id);
                        }]);
                    }]);
                }])
                ->first();
        $view = 'others.faq';
        return view($view,compact('data','dialect'));
    }

    public function terms_and_condition(){
        $titles = ['Online Booking','Registration'];
        $terms = Term::whereIn('type',['online-booking','registration'])->get();
        // PDF::SetTitle('Terms and Condition');
        // PDF::setPrintHeader(true);
        // PDF::setPrintFooter(true);

        // PDF::AddPage();
        // PDF::SetFont('Helvetica','N',10);
        // $view=\View::make('terms/terms_and_condition',compact('titles','terms'));
        // PDF::writeHTML($view->render(), true, false, true, false, '');
        // PDF::Output('Terms and Condition');

        $data = ['titles'=>$titles,'terms'=>$terms];

        $pdf = PDF::loadView('terms.terms_and_condition', $data);
        return $pdf->stream();

    }

    public function account(){
        $business_types=BusinessType::with('business_type_category')->get();
        $provinces = Province::get();
        $agent = new Agent();
        $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.auth.profile' : 'auth.profile';
        return view($view,compact('business_types','provinces'));
    }

    public function getCities($id){
        echo json_encode(City::where('province_id',$id)->get()->toArray());
    }

    public function track_trace(){
       return view('track_trace',compact('data'));
    }
    public function track_trace_data(Request $request){

        $id = Auth::user()->contact->doff_account_data->contact_id;

        $data = DOFFLiveWaybill::whereRaw("LEFT(transactioncode,2)!='AR'")
        ->where(function($query) use($id){
            $query->where('shipper_id',$id)->orWhere('consignee_id',$id)->orWhere('chargeto_id',$id);
        })
        ->where(function($query) use($request){

            if($request -> has('tt_month')){

                $query->where('transactiondate','LIKE',$request->tt_month.'%');

            }else{

                $query->where('waybill_no','LIKE',strtoupper($request->tt_no).'%')
                ->orWhere('tracking_no','LIKE',strtoupper($request->tt_no).'%')
                ->orWhere('transactioncode','LIKE',strtoupper($request->tt_no).'%')
                ->orWhereHas('waybill_reference',function($query) use($request){
                    $query->where('reference_no','LIKE',strtoupper($request->tt_no).'%');
                });
            }

        })

        ->with(['track_trace'=>function($query){ $query->orderBy('track_trace_date','DESC'); },
        'consignee',
        'waybill_reference'=>function($query){ $query->with('specialrate_reference_attachment'); }])
        ->get()->toArray();

        echo json_encode($data);

        //print_r($request->all());

    }
    public function pod_data( $pod_month ){

       // echo $pod_month;

       $id = Auth::user()->contact->doff_account_data->contact_id;

        $data = PODTransmittal::where('pod_date','LIKE',$pod_month.'%')->
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
        ->get()
        ->toArray();
        //dd($data);
        echo json_encode($data);
    }

    public function claim($transaction_code,$action){
        //print_r($transaction_code);
        $tcode=$this->encryptor('decrypt',$transaction_code);
        $tcode_action=$this->encryptor('decrypt',$action);

        $tracking_no = DOFFLiveWaybill::where('transactioncode',$tcode)
        ->first()
        ->tracking_no;

        $count_i = CustomerEmail::where('transactioncode',$tcode)
        ->where('email_type',2)
        ->whereNotNull('incident_no')
        ->count();

        if($count_i == 0){

            if($tcode_action==1){
                $subject='CARGO CLAIMED AND PAID';
                $desc='WAYBILL# '.$tcode.' CARGO CLAIMED AND PAID. REPORTED BY CUSTOMER.';
            }else{
                $subject='CARGO CLAIMED AND UNPAID';
                $desc='WAYBILL# '.$tcode.' CARGO CLAIMED AND UNPAID. REPORTED BY CUSTOMER.';
            }
            $i_no='WIAR'.date('y',strtotime(Carbon::now())).$this->random_alph_num(3,3);
            WaybillIncident::create([
                'incident_no'=> $i_no,
                'incident_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'incident_subject'=>$subject,
                'incident_description'=>$desc,
                'posted_datetime'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'incident_category_id'=>13,
                'branchoffice_no'=>'000'
            ]);
            WaybillIncidentWaybill::create([
                'incident_no'=>$i_no,
                'tracking_no'=>$tracking_no
            ]);

            CustomerEmail::where('transactioncode', $tcode)
            ->where('email_type', 2)
            ->update(['incident_no' => $i_no,'response'=>$tcode_action]);

        }
        //echo $tracking_no;
        return view('claimed',compact('tcode','tcode_action'));

    }

    public function encryptor($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'doffitgroup';
        $secret_iv = 'doffitgroup2016';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public function branch_serviceable_map($id){

        $serviceable_branch_map=DOFFLiveBranch::where('online_branch_id',$id)
        ->select('serviceable_map')
        ->get();

        //$map=Storage::disk('local')->get('app/clients/12.png');
        //$map=Storage::disk('maps')->get('Branch/CHECK.png');
        //dd( $map);

       return view('serviceable.branch')->with(compact('serviceable_branch_map'));
    }

    public function sector_list($id){

        $sector_list=Sector::whereHas('branch', function ($query) use ($id)
        {
            $query->where('online_branch_id', $id);
        })
        ->where(function($query) {
            $query->orWhere('serviceable_panel', 1)
                  ->orWhere('serviceable_truck', 1);
        })
        ->with(array('route_template_details'=>function($query){
            $query->with(array('route_template'=>function($query){
                $query->with(array('sector_schedule'=>function($query){
                   $query->orderByRaw(
                     "CASE WHEN day_of_the_week='SUNDAY'  THEN 0
                      WHEN day_of_the_week='MONDAY'  THEN 1
                      WHEN day_of_the_week='TUESDAY'  THEN 2
                      WHEN day_of_the_week='WEDNESDAY'  THEN 3
                      WHEN day_of_the_week='THURSDAY'  THEN 4
                      WHEN day_of_the_week='FRIDAY'  THEN 5
                      WHEN day_of_the_week='SATURDAY'  THEN 6
                      ELSE ''END ASC"
                   )
                   ->distinct('day_of_the_week')
                   ;
                }));
            }))
            ;
        }))
        ->orderBy('province')
        ->orderBy('city')
        ->orderBy('barangay')
        ->get();

        return view('serviceable.sector')->with(compact('sector_list'));
    }
    public function get_sector_schedule($id,$date,$action){

        if($action=='delivery'){

            $fday=date('Y-m-01',strtotime($date));
            if( $fday < date('Y-m-d') ){
                $fday=date('Y-m-d');
            }
            $lday=date('Y-m-d', strtotime("+1 month", strtotime($fday)));

        }else{
            $fday=date('Y-m-01',strtotime($date));
		    $lday=date('Y-m-t',strtotime($date));
        }

        $sector_list=Sector::where('sectorate_no', $id)
        ->with(array('route_template_details'=>function($query){
            $query->with(array('route_template'=>function($query){
                $query->with(array('sector_schedule'=>function($query){
                   $query->distinct('day_of_the_week');
                }));
            }))
            ;
        }))
        ->get();

        $sun=0;
		$mon=0;
		$tue=0;
		$wed=0;
		$thur=0;
		$fri=0;
		$sat=0;

        $pre_plan_delivery=array();
		$pre_plan_delivery_day=array();
		$pre_plan_delivery_sday=array();
		$pre_plan_delivery_rday=array();
        $count_quota=0;
        foreach($sector_list as $sector){
            if($action !='delivery'){

                $pp_cond="OR ( (CASE
                WHEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE AND tblmanifest.date_received IS NULL
                THEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))
                ELSE  CURRENT_DATE END  ) ) >= '$fday'
                AND
                ( (CASE
                WHEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE AND tblmanifest.date_received IS NULL
                THEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))
                ELSE  CURRENT_DATE END  ) ) <= '$lday'  ";

                $pp_cond2="OR (( (CASE
                WHEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE AND tblmanifest.date_received IS NULL
                THEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))
                ELSE  CURRENT_DATE END  ) ) >= '$fday'
                AND
                ( (CASE
                WHEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE AND tblmanifest.date_received IS NULL
                THEN DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))
                ELSE  CURRENT_DATE END  ) ) <= '$lday'  AND tblwaybills.delivery_tp='truck') ";

                $delivery = DB::select("SELECT tblsector_schedule.route_template_no,
                (CASE
                    WHEN tblwaybills.delivery_tp='truck' AND DATE(tblmanifest.date_received) > CURRENT_DATE THEN
                        UPPER(DAYNAME(DATE(tblmanifest.date_received)))
                    WHEN tblwaybills.delivery_tp='truck' AND DATE(tblmanifest.date_received) <= CURRENT_DATE THEN
                        UPPER(DAYNAME(CURRENT_DATE))
                    WHEN tblwaybills.delivery_tp='truck' AND tblmanifest.date_received IS NULL AND DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE THEN
                        UPPER(DAYNAME(DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))))
                    WHEN tblwaybills.delivery_tp='truck' AND tblmanifest.date_received IS NULL AND DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) <= CURRENT_DATE THEN
                        UPPER(DAYNAME(CURRENT_DATE))
                    ELSE
                        tblsector_schedule.day_of_the_week
                    END
                ) as day_of_the_week,

                (CASE

                    WHEN tblwaybills.delivery_tp='truck' AND tblmanifest.date_received IS NULL AND DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY)) > CURRENT_DATE THEN
                        DATE(DATE_ADD(tblwaybills.transactiondate, INTERVAL 1 DAY))
                    ELSE
                        ''
                    END
                ) as specific_sched,tblmanifest_reschedule.schedule_date as r_sched,

                tblwaybills.transactioncode

                FROM waybill.tblwaybills
                LEFT OUTER JOIN waybill.tblmanifest_reschedule ON tblmanifest_reschedule.waybill_no=tblwaybills.transactioncode
                AND tblmanifest_reschedule.schedule_status=0
                AND tblmanifest_reschedule.schedule_date <= '".$lday."'



                LEFT OUTER JOIN waybill.tblmanifest_reschedule freschedule ON freschedule.waybill_no=tblwaybills.transactioncode
                AND freschedule.schedule_status=0
                AND freschedule.schedule_date > '".$lday."'

                LEFT OUTER JOIN waybill.tblwaybill_released ON tblwaybill_released.waybill_no=tblwaybills.transactioncode
                LEFT OUTER JOIN waybill.tblsectorate2 ON tblsectorate2.sectorate_no=tblwaybills.delivery_sector_id

                LEFT OUTER JOIN waybill.tblroute_template_details ON tblroute_template_details.sectorate_no=tblsectorate2.sectorate_no

                LEFT OUTER JOIN waybill.tblsector_schedule ON tblsector_schedule.route_template_no=tblroute_template_details.route_template_no

                LEFT OUTER JOIN waybill.tblroute_template ON tblsector_schedule.route_template_no=tblroute_template.route_template_no
                LEFT OUTER JOIN joim.tbltruck ON tbltruck.TruckID=tblsector_schedule.TruckID

                LEFT OUTER JOIN waybill.tblsector_schedule  ss_manifest_reschedule ON ss_manifest_reschedule.route_template_no=tblroute_template_details.route_template_no
                AND tblsector_schedule.day_of_the_week=UPPER(DAYNAME(tblmanifest_reschedule.schedule_date))


                LEFT OUTER JOIN waybill.tblmanifest_details tblmanifest_details_dpc ON LEFT(tblmanifest_details_dpc.manifest_no,3) ='DPC'
                AND tblmanifest_details_dpc.waybill_no=tblwaybills.transactioncode
                LEFT OUTER JOIN waybill.tblmanifest tblmanifest_dpc ON tblmanifest_dpc.manifest_no=tblmanifest_details_dpc.manifest_no
                AND (tblmanifest_dpc.manifest_status='UPDATED' OR tblmanifest_dpc.manifest_status='RECEIVE' OR tblmanifest_dpc.manifest_status='TRUCK IN' OR tblmanifest_dpc.manifest_status='VERIFY' OR tblmanifest_dpc.manifest_status='TRUCK OUT')

                LEFT OUTER JOIN waybill.tblmanifest tblmanifest_dpc2 ON tblmanifest_dpc2.manifest_no=tblmanifest_details_dpc.manifest_no
                AND ( tblmanifest_dpc2.manifest_status='RECEIVE' OR tblmanifest_dpc2.manifest_status='TRUCK IN' OR tblmanifest_dpc2.manifest_status='VERIFY' OR tblmanifest_dpc2.manifest_status='TRUCK OUT')


                LEFT OUTER JOIN  waybill.tblmanifest_details ON tblmanifest_details.waybill_no=tblwaybills.transactioncode  AND LEFT(tblmanifest_details.manifest_no,3) !='DPC'
                LEFT OUTER JOIN waybill.tblmanifest ON tblmanifest.manifest_no=tblmanifest_details.manifest_no
                AND ( tblmanifest.manifest_status = 'DONE' OR tblmanifest.manifest_status ='OLD' )
                AND DATE(tblmanifest.date_received) <= '".$lday."'

                WHERE tblwaybills.delivery=1
                AND ( tblmanifest.manifest_no IS NOT NULL  OR LEFT(tblwaybills.reference_no,1)='C' ".$pp_cond2." )
                AND ( tblmanifest_dpc.manifest_no IS NULL OR ( tblwaybills.cargo_status='not delivered' AND tblmanifest_dpc2.manifest_no IS NULL ) )
                AND tblwaybills.destinationbranch_id='".($sector->branchoffice_no)."'
                AND NOT tblwaybills.cargo_status='delivered'
                AND NOT tblwaybills.cargo_status='released'
                AND ( ( NOT tblwaybills.waybill_status='cancel' AND NOT tblwaybills.waybill_status='reclassified' ) OR tblwaybills.waybill_status IS NULL )
                AND tblwaybill_released.waybill_no IS NULL
                AND tblwaybills.sectorate2=1
                AND ( tblsector_schedule.route_template_no IS NOT NULL OR ss_manifest_reschedule.route_template_no IS NOT NULL OR ( ( tblmanifest.date_received IS NOT NULL ".$pp_cond." ) AND tblwaybills.delivery_tp='truck' ) )
                AND freschedule.waybill_no IS NULL
                GROUP BY tblwaybills.transactioncode,tblwaybills.delivery_tp,tblmanifest.date_received,tblwaybills.transactiondate,tblmanifest_reschedule.schedule_date,tblsector_schedule.route_template_no,tblsector_schedule.day_of_the_week
                ORDER BY tblwaybills.transactioncode");

                foreach($delivery as $del){

                    $pre_plan_delivery[]=$del->transactioncode;
                    $pre_plan_delivery_day[]=$del->day_of_the_week;
                    $pre_plan_delivery_sday[]=$del->specific_sched;
                    $pre_plan_delivery_rday[]=$del->r_sched;


                }
                $tcode='';
                $day_date='';
                foreach($pre_plan_delivery as $i => $tcode_data ){


                    if( $tcode !='' && $tcode != $tcode_data ){

                        if(isset($pre_plan_del[$day_date])){
                            $pre_plan_del[$day_date]++;
                        }else{
                            $pre_plan_del[$day_date]=1;
                        }


                    }
                    if( $tcode != $tcode_data ){
                        $day_date='';
                    }
                    $day=$pre_plan_delivery_day[$i];
                    $sday=$pre_plan_delivery_sday[$i];
                    $rday=$pre_plan_delivery_rday[$i];

                    $date_from=date('Y-m-d');
                    $date_to=date('Y-m-d',strtotime(date('Y-m-d').'+6 days'));



                    while( $date_from <= $date_to && $date_from >= $fday ){

                        if( $sday !='' && $sday==$date_from ){
                            $day_date=$date_from;
                        }
                        elseif( $rday !='' && $rday==$date_from ){
                            $day_date=$date_from;
                        }
                        elseif( $rday =='' && $sday =='' && strtoupper(date('l',strtotime($date_from))) == $day  ){



                            if( $date_from < $day_date || $day_date==''){
                                $day_date=$date_from;


                            }

                        }

                        $date_from=date('Y-m-d',strtotime($date_from.'+1 days'));
                    }



                    $tcode=$tcode_data;


                }

                if( $tcode !='' ){

                    if(isset($pre_plan_del[$day_date])){
                        $pre_plan_del[$day_date]++;
                    }else{
                        $pre_plan_del[$day_date]=1;
                    }

                }

                $pickup =DB::select("SELECT COUNT(tblquotation.quotation_no) as count_booking,tblquotation.booking_date

                FROM quotation.tblquotation

                LEFT OUTER JOIN waybill.tblmanifest_details tblmanifest_details_dpc ON LEFT(tblmanifest_details_dpc.manifest_no,3) ='DPC'
                AND tblmanifest_details_dpc.pickup_booking_no=tblquotation.quotation_no
                LEFT OUTER JOIN waybill.tblmanifest tblmanifest_dpc ON tblmanifest_dpc.manifest_no=tblmanifest_details_dpc.manifest_no
                AND ( tblmanifest_dpc.manifest_status='UPDATED' OR tblmanifest_dpc.manifest_status='RECEIVE' OR tblmanifest_dpc.manifest_status='TRUCK IN' OR tblmanifest_dpc.manifest_status='VERIFY' OR tblmanifest_dpc.manifest_status='TRUCK OUT')

                WHERE tblquotation.booking_date >= '$fday'
                AND tblquotation.booking_date <='$lday'
                AND tblquotation.origin_branch='".($sector->branchoffice_no)."'
                AND tblquotation.pickup=1
                AND LEFT(tblquotation.quotation_no,2)='PB'
                AND tblquotation.quotation_status=1
                AND tblmanifest_dpc.manifest_no IS NULL
                GROUP BY tblquotation.booking_date");
                foreach($pickup as $pu){

                    $pre_plan_pu[$pu->booking_date]=$pu->count_booking;

                }

                $b_quota=ConfigBranch::where('branchoffice_no', $sector->branchoffice_no)
                ->first();
                //print_r($b_quota->booking_quota);
                $count_quota=$b_quota->booking_quota;
            }


            foreach($sector->route_template_details as $sector_rdetails){
                foreach($sector_rdetails->route_template->sector_schedule as $route_template){
                    if($route_template->day_of_the_week=='SUNDAY'){
                        $sun++;
                    }
                    elseif($route_template->day_of_the_week=='MONDAY'){
                        $mon++;
                    }
                    elseif($route_template->day_of_the_week=='TUESDAY'){
                        $tue++;
                    }
                    elseif($route_template->day_of_the_week=='WEDNESDAY'){
                        $wed++;
                    }
                    elseif($route_template->day_of_the_week=='THURSDAY'){
                        $thur++;
                    }
                    elseif($route_template->day_of_the_week=='FRIDAY'){
                        $fri++;
                    }
                    elseif($route_template->day_of_the_week=='SATURDAY'){
                        $sat++;
                    }

                }

            }


        }




		$result='';
		while($fday <= $lday){

            $count_booking=0;

			if( isset($pre_plan_pu[$fday]) ){

				$count_booking +=$pre_plan_pu[$fday];

			}
			if( isset($pre_plan_del[$fday]) ){

				$count_booking +=$pre_plan_del[$fday];

			}


            $count=0;
			if( strtoupper(date('l',strtotime($fday))) =='SUNDAY' && $sun > 0){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='MONDAY' && $mon > 0 ){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='TUESDAY' && $tue > 0  ){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='WEDNESDAY' && $wed > 0 ){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='THURSDAY' && $thur > 0 ){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='FRIDAY' && $fri > 0  ){
				$count++;
			}
			elseif( strtoupper(date('l',strtotime($fday))) =='SATURDAY' && $sat > 0 ){
				$count++;
			}
			$result .= ",".$fday.'|'.$count.'|'.$count_booking.'|'.$count_quota ;

            $date=date_create($fday);
			date_add($date,date_interval_create_from_date_string("1 days"));
			$fday= date_format($date,"Y-m-d");
        }
        echo json_encode($result);

    }
    public function get_sector_province($action){

        // $sector_list=Sector::where(function($query) {
        //     $query->orWhere('serviceable_panel', 1)
        //           ->orWhere('serviceable_truck', 1);
        // })
        // ->orderBy('province', 'asc')
        // ->get()->unique('province');


        $sector_list = Province::get();

        if($action=='pickup'){
            $text='pu_province';
        }
        elseif($action=='delivery'){
            $text='del_province';
        }

        return view('sector.province.'.$text)->with(compact('sector_list'));
    }

    public function get_sector_city($action,$province){

        // $sector_list=Sector::where(function($query) {
        //     $query->orWhere('serviceable_panel', 1)
        //           ->orWhere('serviceable_truck', 1);
        // })
        // //->where('province',$province)
        // ->whereRaw('UPPER(province) = ?', [strtoupper($province)])
        // ->orderBy('city', 'asc')
        // ->get()->unique('city');
        $sector_list=City::where('province_id',$province)->get();
        if($action=='pickup'){
            $text='pu_city';
        }
        elseif($action=='delivery'){
            $text='del_city';
        }

        return view('sector.city.'.$text)->with(compact('sector_list'));
    }
    public function get_sector_brgy($action,$province,$city){

        $sector_list=Sector::where(function($query) {
            $query->orWhere('serviceable_panel', 1)
                  ->orWhere('serviceable_truck', 1);
        })
        //->whereRaw('UPPER(province) = ?', [strtoupper($province)])
        //->whereRaw('UPPER(city) = ?', [strtoupper($city)])
        ->where('city_id',$city)
        ->orderBy('barangay', 'asc')
        ->get();

        if($action=='pickup'){
            $text='pu_brgy';
        }
        elseif($action=='delivery'){
            $text='del_brgy';
        }
        return view('sector.barangay.'.$text)->with(compact('sector_list'));
    }

    public function get_sector_details($id){

        //$id='20COXE0461';
        $sector_list=Sector::where('sectorate_no',$id)
        ->get();
        $city_id='';
        foreach($sector_list as $sector){
            $city_id=$sector->city_id;
        }

        echo json_encode(City::where('cities_id',$city_id)->get()->toArray());

    }

    public function get_address_details($id,$action){

        $address=UserAddress::where('useraddress_no',$id)
        ->get();

        $sector_no='';
        foreach($address as $address_data){
            $sector_no=$address_data->sectorate_no;
        }
        //$sector_no='20COXE0461';


        if($action=='brgy'){

            echo json_encode(Sector::where('sectorate_no',$sector_no)->get()->toArray());

        }else{
            $sector_list=Sector::where('sectorate_no',$sector_no)
            ->get();

            $city_id='';
            foreach($sector_list as $sector){
                $city_id=$sector->city_id;
            }
            echo json_encode(City::where('cities_id',$city_id)->get()->toArray());
        }


    }
    public function send_otp(Request $request){
         try{

            DB::beginTransaction();
            $otp = rand(10000,99999);

            DB::table('dailyove_online_site.tblreg_otp')->where('client',base64_decode($request->email))->delete();
            DB::table('sms.employee_account')->whereRaw("reg_otp_id NOT IN (SELECT reg_otp_id FROM dailyove_online_site.tblreg_otp ) ")->delete();

            $date_now=Carbon::now();
            $date_exp=Carbon::now()->addMinutes(5);
            $text='Registration';
            if( base64_decode($request->action)=='login' ){
                $text='Log-in';
            }
            elseif( base64_decode($request->action)=='change' ){
                $text='Changes';
            }
            $msg='Dear Customer, your OTP for DOFF '.$text.' is '.$otp.'. This code will expire after 5 minutes.';

            DB::table('dailyove_online_site.tblreg_otp')
            ->insert([
                'client' => base64_decode($request->email),
                'otp'=>$otp,
                'added_date'=> $date_now,
                'exp_date'=> $date_exp,
                'msg'=>$msg,
                'otp_type' =>base64_decode($request->type)
            ]);

            if(base64_decode($request->type)=='mobile'){

                DB::table('sms.employee_account')
                ->insert([
                    'recipient' => base64_decode($request->email),
                    'message'=>$msg,
                    'reg_otp_id' => DB::getPdo()->lastInsertId()
                ]);

            }else{

                // $data=Mail::send('otp.email_body',['otp'=>$otp,'action'=>$text], function($message) use($request){
                //     $message->to(base64_decode($request->email),'')->subject('DOFF OTP');
                //     $message->from(env('MAIL_USERNAME','booking@dailyoverland.com'),env('MAIL_NAME','DOFF'));
                // });

                // if(!$data){
                //     $msg='Email not Sent';
                // }

            }
			$msg='OTP has been Sent';
            DB::commit();
            return response()->json(['title'=>'Success','message'=>$msg,'type'=>'success','date_exp'=>$date_exp],200);

        } catch (\Exception $e) {

            echo $e;
            DB::rollback();
            return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

        }

    }
    public function validate_otp(Request $request){

        $result = DB::table('dailyove_online_site.tblreg_otp')
        ->where('client',base64_decode( $request->email))
        ->where('otp',base64_decode($request->otp))
        ->orderBy('added_date','DESC')
        ->first();

        if( $result && $result->exp_date >= date('Y-m-d h:i:s') ){
            echo json_encode(1);
        }
        elseif( $result && $result->exp_date < date('Y-m-d h:i:s') ){
            echo json_encode(2);
        }
        else{
            echo json_encode(0);
        }
    }
    public function enable_disable_web_otp(){

        $result=DB::table('doff_configuration.tblsetting')
        ->where('setting_name','WEBSITE OTP')
        ->get();

        echo json_encode($result);
    }
    public function validate_user_pwd(Request $request){

        if(base64_decode($request->type)=='mobile'){
            $contact=User::firstOrNew([
                'mobileNo'=>base64_decode($request->email),
                'personal_corporate'=>base64_decode($request->pca)
            ]);
        }else{
            $contact=User::firstOrNew([
                'email'=>base64_decode($request->email),
                'personal_corporate'=>base64_decode($request->pca)
            ]);
        }

        if($contact->exists){
            if(Hash::check(base64_decode($request->pwd),$contact->password)){
                echo json_encode(1);
            }else{
                echo json_encode(2);
            }
        }else{
            echo json_encode(0);
        }
    }
    public function login_validate_email(Request $request){
        if(base64_decode($request->type)=='mobile'){
            $contact=User::firstOrNew([
                'mobileNo'=>base64_decode($request->email),
                'personal_corporate'=>base64_decode($request->pca)
            ]);
        }else{
            $contact=User::firstOrNew([
                'email'=>base64_decode($request->email),
                'personal_corporate'=>base64_decode($request->pca)
            ]);
        }
        if($contact->exists){
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    public function validate_email($email){

        $personal_corporate=0;
        if (session()->has('pca_no')){
            $personal_corporate=1;
        }

        if (str_contains($email, 'cname-')) {
            $contact=User::whereRaw("personal_corporate=".$personal_corporate." AND UPPER(name)='".strtoupper(base64_decode(str_replace("cname-","",$email)))."' ")->firstOrNew();
        }
        elseif (str_contains($email, 'cno-')) {
            $contact=User::where('personal_corporate',$personal_corporate)->firstOrNew(['mobileNo'=>base64_decode(str_replace("cno-","",$email))]);
        }
        else{
            //$contact=Contact::firstOrNew(['email'=>$email]);

            $contact=User::where('personal_corporate',$personal_corporate)->firstOrNew(['email'=>$email]);
        }

        if($contact->exists){
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }

    }
    public function get_id_type(){

        echo json_encode(ValidID::where('valid_id_status',1)->get()->toArray());

    }

    public function save_apply_verification(Request $request){

		Contact::where('contact_id', Auth::user()->contact->contact_id)
		->update(['verified_account' => 2]);


		$av_id_pic = Image::make($request->av_id_pic);
		Response::make($av_id_pic->encode('jpeg'));

		$av_id_w_pic = Image::make($request->av_id_w_pic);
		Response::make($av_id_w_pic->encode('jpeg'));

		$form_data = array(
			'contact_id'  => Auth::user()->contact->contact_id,
			'contacts_verification_pic_id' => $av_id_pic,
			'contacts_verification_pic_with_id' => $av_id_w_pic,
			'valid_id_no' => $request->av_id_type,
            'id_no' => $request->av_id_no
			);
		$save_data=ContactVerification::create($form_data);

		if($save_data ){

			$msg='Successfully sent!';

		}else{

          $msg='Ooops! There something wrong!';
		}
        return redirect()->back()->with('success', $msg);


	}

    public function get_qr_code_profile($id){

        $data=QrcodeProfile::where('qrcode_profile_id',$id)
                ->with(['contact'=>function($query){
                    $query->with(['user_address'=>function($query){
                        $query->with('sector');
                    },
                    'shipper_consignee',
                    'contact_number'
                    ]);
                },
                'qr_code_details'=>function($query){
                    $query->with(['qr_code_profile_address'=>function($query){
                        $query->with('sector');
                    }]);
                }
                ])
                ->get()
                ->toArray();

        echo json_encode($data);


    }
    public function get_customer_details($id){
        echo json_encode(
        Contact::where('contact_id',$id)
                ->get()
                ->toArray()
        );
    }

    public function profile_qr_code(){
        return view('profile_qr_code');
    }

    public function create_qr_profile(Request $request){

        $qrcode_profile_id = $this->random_alph_num(1,2).'-'.$this->random_alph_num(3,2).'-'.$this->random_alph_num(5,1);

        $form_data = array(
			'contact_id'  => Auth::user()->contact_id,
			'qrcode_profile_id' => $qrcode_profile_id
			);
		QrcodeProfile::create($form_data);

        foreach($request->qr_code_address_no as $qr_code_address_no){

            QrcodeProfileDetails::create([
                'qrcode_profile_id'=>$qrcode_profile_id,
                'useraddress_no'=>$qr_code_address_no
            ]);

        }

        return response()->json(['title'=>'Success','message'=>'Qr Code successfully created','type'=>'success'],200);
    }
    public function get_qr_code_profile_list(){

        echo json_encode(
            QrcodeProfile::where('contact_id',Auth::user()->contact_id)
                    ->where('qrcode_profile_status',1)
                    ->with([
                        'qr_code_details'=>function($query){
                        $query->with('qr_code_profile_address');
                    }])
                    ->get()
                    ->toArray()
        );
    }
    public function deactivate_qr_code_profile($id){

        QrcodeProfile::where('qrcode_profile_id',$id)->update([
            'qrcode_profile_status'=>2,
            'date_deactivated'=>Carbon::now()
        ]);

        ShipperConsignee::where('qr_code',$id)->update([
            'shipper_consignee_status'=>2,
            'date_deactivated'=>Carbon::now(),
            'deactivated_view'=>0
        ]);

        return response()->json(['title'=>'Success','message'=>'Qr Code successfully deactivated','type'=>'success'],200);


    }

    public function save_sc_qrcode($id,$qrcode){
        if(
            ShipperConsignee::where("contact_id", Auth::user()->contact_id)
            ->where("shipper_consignee_id", $id)
            ->where("shipper_consignee_status",1)
            ->doesntExist()
        )
        {
            ShipperConsignee::create([
                'contact_id'=>Auth::user()->contact_id,
                'shipper_consignee_id'=>$id,
                'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                'rider'=>0,
                'qr_code'=> $qrcode
            ]);

            echo json_encode('Successfully Added.');
        }else{
            echo json_encode('Sorry but Shipper/Consignee already exist.');
        }


    }
    public function get_rebate_points($shipper,$consignee,$sqrcode,$cqrcode,$sqrcode_id,$cqrcode_id ){
        $rebate_c=0;
        $rebate_s=0;
        $rebate_u=0;
        $rebate_point=0;
        $rpoint=0;

        $rebate_factor= RebatePointFactor::where('rebate_point_status',1)
        ->where('effectivity_date','<=',date('Y-m-d',strtotime(Carbon::now())))
        ->where('rebate_type',1)
        ->orderBy('effectivity_date', 'DESC')
        ->orderBy('added_date', 'DESC')
        ->limit(1)
        ->get();

        foreach($rebate_factor as $rfactor){
           $rebate_point=$rfactor->rebate_point;
        }


        if($rebate_point > 0){

            if($shipper !='new'){
                $sc_qr_code='';
                if(
                    ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $shipper)
                    ->where("shipper_consignee_status",1)
                    ->doesntExist()
                )
                {
                    if( $sqrcode !='NONE'  && $sqrcode_id == $shipper
                    ){
                        $sc_qr_code=$sqrcode;
                    }
                }else{

                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $shipper)
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                }

                if($sc_qr_code !=''){
                    $rebate_s++;
                }

            }

            if($consignee !='new'){
                $sc_qr_code='';
                if(
                    ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $consignee)
                    ->where("shipper_consignee_status",1)
                    ->doesntExist()
                )
                {
                    if( $cqrcode !='NONE'  && $cqrcode_id == $consignee
                    ){
                        $sc_qr_code=$cqrcode;
                    }
                }else{

                    $sc=ShipperConsignee::where("contact_id", Auth::user()->contact_id)
                    ->where("shipper_consignee_id", $consignee)
                    ->where("shipper_consignee_status",1)
                    ->first();
                    $sc_qr_code=$sc->qr_code;

                }

                if($sc_qr_code !=''){
                    $rebate_c++;
                }
            }

            if(Auth::user()->contact->verified_account==1){
                $rebate_u++;
            }
        }
        if( $rebate_u > 0){
            $rpoint +=$rebate_point;
        }
        if( $rebate_c > 0 || $rebate_s > 0 ){
            $rpoint +=$rebate_point;
        }
        echo json_encode($rpoint);
    }

    public function get_pasabox_branch_receiver(){

        echo json_encode(
            ConfigBranch::where('pasabox_on_off',1)
                    ->with(['pasabox_authorized_employee','pasabox_alternative_authorized_employee'])
                    ->get()
                    ->toArray()
        );
    }
    public function get_online_payment_exist($ref_no,$onl_ref){

        if($onl_ref=='NONE'){
            echo json_encode( OnlinePayment::where('verification_code',$ref_no)->count() );
        }else{

            echo json_encode(
                OnlinePayment::where('verification_code',$ref_no)
                ->whereDoesntHave('online_payment_tcode',function($query) use($onl_ref){
                    $query->where('online_booking_ref',$onl_ref);
                })
                ->count()
            );

        }


    }
    public function get_gcash_info(){
        echo json_encode( WaybillBanks::where('gcash',1)->get()->toArray() );
    }
    public function get_gcash_cf_info($ref_no){
        echo json_encode(
            OnlinePayment::
            wherehas('online_payment_tcode',function($query) use($ref_no){
                $query->where('online_booking_ref',$ref_no);
            })
            ->orderBy('gcash_added_datetime','asc')
            ->limit(1)
            ->get()
            ->toArray()
        );
    }

    public function get_gcash_rp_qr($ref_no){

        $waybill=Waybill::where('reference_no',$ref_no)->first();

        echo json_encode(
            ConfigBranch::where('branchoffice_no',$waybill->pasabox_branch_receiver)
                    ->with('pasabox_authorized_employee')
                    ->get()
                    ->toArray()
        );

    }
    public function save_gcash_reposting_payment(Request $request){
        if(
            OnlinePayment::where("verification_code",$request->gcash_reference_no)
            ->doesntExist()
        ){
            DB::beginTransaction();
            try{

                $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                while(OnlinePayment::where('onlinepayment_id',$onlinepayment_id)->exists()){
                    $onlinepayment_id = "ONLBK".date('y',strtotime(Carbon::now()))."-".$this->random_num(9);
                }


                $online_payment['onlinepayment_id']= $onlinepayment_id;
                $online_payment['onlinepayment_date'] = $request->gcash_pdate;
                $online_payment['for_confirmation_pdate'] = $request->gcash_pdate;
                $online_payment['onlinepayment_amount']=$request->gcash_amount;
                $online_payment['for_confirmation_amount']=$request->gcash_amount;
                $online_payment['verification_code']=$request->gcash_reference_no;
                $online_payment['gcash_added_datetime'] = Carbon::now();
                $online_payment['for_confirmation']=1;
                $online_payment['confirmation_status']=0;
                $online_payment['pasabox_cf']=1;
                if($request->gcash_cemail !=''){
                    $online_payment['confirmation_email']=$request->gcash_cemail;
                }
                $online_payment['confirmation_branch']=$request->pasabox_branch_receiver;
                $online_payment['confirmation_branch_account_name']=$request->gcash_branch_aname;
                $online_payment['confirmation_branch_account_no']=$request->gcash_branch_ano;
                $online_payment['bank_no']=$request->gcash_id;
                OnlinePayment::create($online_payment);

                $online_payment_confirmation['online_booking_ref']=$request->rp_online_booking_ref;
                $online_payment_confirmation['onlinepayment_id']=$onlinepayment_id;
                OnlinePaymentConfirmation::create($online_payment_confirmation);

                Waybill::where('reference_no',$request->rp_online_booking_ref)
                    ->update([
                        'pasabox_cf_amt'=>$request->gcash_pfee
                    ]);
                WaybillTrackAndTrace::create([
                        'trackandtrace_status'=>'REPOSTING OF CONVINIENCE FEE PAYMENT  | PAYMENT REF#: '.$request->gcash_reference_no.' PREPARED BY '.Auth::user()->name,
                        'online_booking'=>$request->rp_online_booking_ref
                    ]);
                DB::commit();
                return response()->json(['title'=>'Success','message'=>'Payment successfully submitted','type'=>'success','key'=>$request->rp_online_booking_ref],200);
            } catch (\Exception $e) {

                //echo $e;
                DB::rollback();
                return response()->json(['title'=>'Ooops!','message'=>'Something went wrong','type'=>'error'],422);

            }

        }else{
            return response()->json(['title'=>'Ooops!','message'=>'Reference No. Already Exist','type'=>'error'],200);
        }

    }



}
