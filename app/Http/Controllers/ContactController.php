<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\OnlineSite\UserAddress;
use Auth;
use Validator;
use Carbon\Carbon;
use App\OnlineSite\Contact;
use App\OnlineSite\ShipperConsignee;
use App\User;
use App\Http\Controllers\ReferenceTrait;
use Illuminate\Support\Facades\DB;
use View;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Jenssegers\Agent\Agent;

class ContactController extends Controller
{

    use ReferenceTrait;

    private $title;
    private $message;
    private $type;
    private $return_data;

    public function __construct(){
        $this->middleware(['auth'])->except(['getCities']);
        View::share([
                'business_types'=>BusinessType::with('business_type_category')->get(),
                'provinces'=>Province::with('city')->get(),
                'cities'=>City::orderBy('cities_name')->get()
              ]);
    }

    public function test(){
      echo $this->encrypt_decrypt('decrypt','hJCpeHmhoYWJrQ==');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts=Contact::where('contact_id','<>',Auth::user()->contact_id)
                        ->whereHas('shipper_consignee',function($query){
                            $query->where('contact_id',auth()->user()->contact_id)
                            ->where('pasabox',0);
                        })
                       ->whereHas('user_address')
                       ->orderBy('lname','ASC')
                       ->select("*")
                       ->with(['user_address'=>function($query){
                            $query->whereNotNull('sectorate_no')->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
                       }])
                       ->get();
                       
        $cities=City::orderBy('cities_name','ASC')->get();
        $ddCities = '';
        foreach($cities as $city){
            $ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
        }
        
        $view = 'contacts.index';
        return view($view,compact('contacts','ddCities'));
        
    }

    public function getContacts($status){

        $cdate=date('Y-m-d',strtotime(date('Y-m-d').' -1 month'));
        
        if($status==2){

            $data=Contact::
            where('contact_id','<>',Auth::user()->contact_id)
                ->whereHas('shipper_consignee',function($query) use($status,$cdate) {
                $query->where('contact_id',auth()->user()->contact_id)         
                ->where('shipper_consignee_status',$status)
                ->whereDate('date_deactivated','>=',$cdate)
                ->where('pasabox',0);
            })
            ->whereHas('user_address')
            ->orderBy('lname','ASC')
            ->select("*")
            ->with(['user_address'=>function($query){ $query->whereNotNull('sectorate_no')->with('sector'); },'shipper_consignee'])
            ->get();

        }else{
            $data=Contact::
            where('contact_id','<>',Auth::user()->contact_id)
                ->whereHas('shipper_consignee',function($query) use($status) {
                $query->where('contact_id',auth()->user()->contact_id)         
                ->where('shipper_consignee_status',$status)
                ->where('pasabox',0);
            })
            ->whereHas('user_address')
            ->orderBy('lname','ASC')
            ->select("*")
            ->with(['user_address'=>function($query){ $query->whereNotNull('sectorate_no')->with('sector'); },'shipper_consignee'])
            ->get();

        }
        

        return ContactResource::collection($data);
    //     echo json_encode(Contact::where('contact_id','<>',Auth::user()->contact_id)->whereHas('shipper_consignee',function($query){
    //         $query->where('contact_id',auth()->user()->contact_id);
    //     })
    //    ->whereHas('user_address')
    //    ->orderBy('lname','ASC')
    //    ->select("*")
    //    ->with(['user_address'=>function($query){
    //         $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
    //    }])
    //    ->get()->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    public function store(Request $request)
    {
        
        $fileas = $request->use_company == 'on' ? html_entity_decode($request->company) : html_entity_decode($request->lname.', '.$request->fname.' '.$request->mname);
        $validation = $request->use_company == 'on' ? Validator::make($request->all(),[
            'company'=>'required',
            'barangay'=>'required',
            'city'=>'required',
            'province'=>'required',
            'postal_code'=>'required'
        ]) : Validator::make($request->all(),[
            'lname'=>'required',
            'fname'=>'required',
            'barangay'=>'required',
            'city'=>'required',
            'province'=>'required',
            'postal_code'=>'required'
        ]);
        

        $this->title='';
        $this->message='';
        $this->type='';
        $this->return_data = null;


        if($validation->passes()){

            $contact_id=$this->generate_contact_id();
                
            $request->merge([
                    'email'=>$request->email!=null ? html_entity_decode($request->email) : '',
                    'fileas'=>$fileas,
                    'contact_id'=>$contact_id,
                    'gender'=>'DEFAULT',
                    'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
                    'lname'=>$request->use_company != 'on' ? html_entity_decode( $request->lname) : '',
                    'fname'=>$request->use_company != 'on' ? html_entity_decode($request->fname) : '',
                    'mname'=>$request->use_company != 'on' ? html_entity_decode($request->mname) : '',
                    'company'=>$request->company!='' ? html_entity_decode($request->company) : '',
                    'position'=>$request->position!='' ? html_entity_decode($request->position) : '',
                    'business_category_id'=>$request->business_category_id!=null ? $request->business_category_id : 0,
                    'religion'=>'',
                    'nationality'=>'',
                    'civil_status'=>'',
                    'branchoffice_id'=>0,
                    'discount'=>0,
                    'bir2306'=>0,
                    'bir2307'=>0,
                    'vat'=>1,
                    'employee'=>0,
                    'customer'=>0,
                    'use_company'=>$request->use_company == 'on' ? 1 : 0,
                    'email_verification'=>0,
                    'profile_photo_path'=>'',
                    'department'=>null,
                    'doff_account'=>null,
                    'contact_status'=>0,
                    'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                    'shipper_consignee_id'=>$contact_id,
                    'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                    'rider'=>0
                    ]);

            $this->return_data=Contact::create($request->except('user_name','user_password','user_status','user_type_no','shipper_consignee_id','latest_transaction','rider'));
             
            $useraddress_no = $this->generate_useraddress_no();

            UserAddress::create(['useraddress_no'=>$useraddress_no,
                                'street'=>html_entity_decode($request->street),
                                'barangay'=>html_entity_decode($request->barangay),
                                'city'=>html_entity_decode($request->city),
                                'province'=>html_entity_decode($request->province),
                                'postal_code'=>html_entity_decode($request->postal_code),
                                'sectorate_no'=>html_entity_decode($request->sectorate_no),
                                'user_id'=>$contact_id,
                                'address_def'=>'0',
                                'address_caption'=>'NONE',
                                'added_by'=>null]);

            // $contact = Contact::firstOrNew(['email'=>$request->email]);

            // if(!$contact->exists){

            //     $contact_id="OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8);
                
            //     $request->merge([
            //             'email'=>$request->email!=null ? html_entity_decode($request->email) : '',
            //             'fileas'=>$fileas,
            //             'contact_id'=>$contact_id,
            //             'gender'=>'DEFAULT',
            //             'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
            //             'lname'=>$request->use_company != 'on' ? html_entity_decode( $request->lname) : '',
            //             'fname'=>$request->use_company != 'on' ? html_entity_decode($request->fname) : '',
            //             'mname'=>$request->use_company != 'on' ? html_entity_decode($request->mname) : '',
            //             'company'=>$request->company!='' ? html_entity_decode($request->company) : '',
            //             'position'=>$request->position!='' ? html_entity_decode($request->position) : '',
            //             'business_category_id'=>$request->business_category_id!=null ? $request->business_category_id : 0,
            //             'religion'=>'',
            //             'nationality'=>'',
            //             'civil_status'=>'',
            //             'branchoffice_id'=>0,
            //             'discount'=>0,
            //             'bir2306'=>0,
            //             'bir2307'=>0,
            //             'vat'=>1,
            //             'employee'=>0,
            //             'customer'=>0,
            //             'use_company'=>$request->use_company == 'on' ? 1 : 0,
            //             'email_verification'=>0,
            //             'profile_photo_path'=>'',
            //             'department'=>null,
            //             'doff_account'=>null,
            //             'contact_status'=>0,
            //             'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
            //             'shipper_consignee_id'=>$contact_id,
            //             'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
            //             'rider'=>0
            //             ]);

            //     $contact->fill($request->except('user_name','user_password','user_status','user_type_no','shipper_consignee_id','latest_transaction','rider'))->save();
            //     $this->return_data = $contact;
            //     $useraddress_no ="OLA-".$this->random_alph_num(2,3);

            //     UserAddress::create(['useraddress_no'=>$useraddress_no,
            //                         'street'=>html_entity_decode($request->street),
            //                         'barangay'=>html_entity_decode($request->barangay),
            //                         'city'=>html_entity_decode($request->city),
            //                         'province'=>html_entity_decode($request->province),
            //                         'postal_code'=>html_entity_decode($request->postal_code),
            //                         'user_id'=>$contact->contact_id,
            //                         'address_def'=>'0',
            //                         'address_caption'=>'NONE',
            //                         'added_by'=>null]);
            // }
            // else{

            //     $useraddress_no ="OLA-".$this->random_alph_num(2,3);
            //     UserAddress::create(['useraddress_no'=>$useraddress_no,
            //                         'street'=>html_entity_decode($request->street),
            //                         'barangay'=>html_entity_decode($request->barangay),
            //                         'city'=>html_entity_decode($request->city),
            //                         'province'=>html_entity_decode($request->province),
            //                         'postal_code'=>html_entity_decode($request->postal_code),
            //                         'user_id'=>$contact->contact_id,
            //                         'address_def'=>'0',
            //                         'address_caption'=>'NONE',
            //                         'added_by'=>null]);
            // }

            // if(ShipperConsignee::where('contact_id',$contact->contact_id)->where('shipper_consignee_id',$contact->contact_id)->count()==0){
            //     ShipperConsignee::create(['contact_id'=>$contact->contact_id,
            //                         'shipper_consignee_id'=>$contact->contact_id,
            //                         'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
            //                         'rider'=>0
            //                         ]);
            // }
            
            if(ShipperConsignee::where('contact_id',Auth::user()->contact_id)->where('shipper_consignee_id',$contact_id)->count()==0){
                ShipperConsignee::create(['contact_id'=>Auth::user()->contact_id,
                                        'shipper_consignee_id'=>$contact_id,
                                        'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                        'rider'=>0
                                        ]);
            }

            $this->title='Success';
            $this->message='Shipper/Consignee has been added';
            $this->type='success';
            
        }else{
            $this->title='Ooops!';
            $this->message='Please fill required fields';
            $this->type='error';
            
        }

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type,'data'=>$this->return_data]);

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo json_encode(Contact::where('contact_id',$id)->with(['user_address'=>function($query){
          $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' || street != null THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"));
        }])->first()->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = Contact::firstOrNew(['contact_id'=>$id]);

        if($contact->exists){
            $cities=City::orderBy('cities_name','ASC')->get();
            $ddCities = '';
            foreach($cities as $city){
                $ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
            }
            $data=Contact::where('contact_id',$id)->with(['shipper_consignee','user_address'=>function($query){ $query->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address")); }])->first();
            $agent = new Agent();
            $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.contacts.edit' : 'contacts.edit';
            return view($view,compact('data','id','ddCities'));
        }else{
            return redirect()->route('contacts.index');
        }
    }

    public function contact_address($id){
        echo json_encode(UserAddress::where('user_id',$id)->select("*", DB::raw("CONCAT(CASE WHEN street !='' THEN CONCAT(street,',') ELSE '' END,barangay,', ',city,', ',province,CASE WHEN postal_code !='' THEN CONCAT(',',postal_code) ELSE '' END) as full_address"))->get()->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $fileas = $request->use_company == 'on' ? html_entity_decode($request->company) : html_entity_decode($request->lname.', '.$request->fname.' '.$request->mname);
        
        $validation = $request->use_company == 'on' ? Validator::make($request->all(),[
            'company'=>'required',
            'contact_no'=>'required'
        ]) : Validator::make($request->all(),[
            'lname'=>'required',
            'fname'=>'required',
            'contact_no'=>'required'
        ]);


        $this->title='';
        $this->message='';
        $this->type='';

        
        
        if($validation->passes()){

            DB::transaction(function() use($request){

                try{
                    $fileas = html_entity_decode($request->use_company) != 'on' ? html_entity_decode($request->lname.','.$request->fname.' '.$request->mname) : html_entity_decode($request->company);
                    $company = $request->use_company != 'on' ? '' : html_entity_decode($request->company);
                    $user = User::where('contact_id',$request->contact_id)
                              ->update([
                                'name'=>$fileas
                              ]);
                    
                    $contact = Contact::where('contact_id',$request->contact_id)
                               ->whereHas('shipper_consignee',function($query){
                                   $query->where('contact_id',Auth::user()->contact_id);
                               })
                               ->update([
                                    'fileas'=>$fileas,
                                    'email'=>$request->email!='' ? html_entity_decode($request->email) : '',
                                    'lname'=>$request->use_company != 'on' ? html_entity_decode($request->lname) : '',
                                    'fname'=>$request->use_company != 'on' ? html_entity_decode($request->fname) : '',
                                    'mname'=>$request->use_company != 'on' ? html_entity_decode($request->mname) : '',
                                    'contact_no'=>$request->contact_no,
                                    'gender'=>$request->gender!=null ? $request->gender : 'DEFAULT',
                                    'company'=>$company,
                                    'business_category_id'=>$request->business_category_id!=null ? $request->business_category_id : 0,
                                    'use_company'=>$request->use_company != 'on' ? 0 : 1
                               ]);
                    
                    $this->message='Contact has been updated';
                    $this->title='Success!';
                    $this->type='success';

                }catch(Exception $e){
                    DB::rollBack();
                    $this->title='Ooops!';
                    $this->message='There something wrong!';
                    $this->type='error';
                }
            });



        }else{
            $title='Ooops!';
            $message='Please fill required fields';
            $type='error';
            
        }

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
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
                $contact = ShipperConsignee::where('shipper_consignee_id',$id)->delete();
               

                if(!$contact){
                    DB::rollBack();
                    $this->message='No rows affected!';
                }else{
                     DB::commit();
                    $this->message='Contact has been removed';
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

    public function getCities($id){
        echo json_encode(City::where('province_id',$id)->get()->toArray());
    }

    public function save_address(Request $request){
        $validation=Validator::make($request->all(),
                [
                    'province'=>'required',
                    'city'=>'required',
                    'barangay'=>'required'
                    
                ],
                [
                    'province.required'=>'Province is required',
                    'city.required'=>'City is required',
                    'barangay.required'=>'Barangay is required'
                    
                ]
        );

        if($validation->passes()){
            DB::transaction(function() use($request){
                try{
                    $user_address = $request->useraddress_no=='' ? UserAddress::create([
                        'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
                        'address_caption'=>$request->address_caption!='' ? $request->address_caption : 'NONE',
                        'province'=>$request->province,
                        'city'=>$request->city,
                        'barangay'=>$request->barangay,
                        'street'=>$request->street,
                        'postal_code'=>$request->postal_code,
                        'sectorate_no'=>$request->sectorate_no,
                        'user_id'=>$request->contact_id
                    ]) : UserAddress::where('user_id',$request->contact_id)->where('useraddress_no',$request->useraddress_no)->update([
                            'address_caption'=>$request->address_caption!='' ? $request->address_caption : 'NONE',
                            'province'=>$request->province,
                            'city'=>$request->city,
                            'barangay'=>$request->barangay,
                            'street'=>$request->street,
                            'sectorate_no'=>$request->sectorate_no,
                            'postal_code'=>$request->postal_code
                        ]);

                    if(!$user_address){
                        DB::rollBack();
                        $this->message='No changes';
                    }else{
                        DB::commit();
                        $type=$request->useraddress_no=='' ? 'save' : 'updated';
                        $this->message='Address has been '.$type;
                        $this->return_data=$user_address;
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
        }else{
            $this->title='Ooops!';
            $this->message='Please fill required fields';
            $this->type='error';
            print_r($validation->errors());
            
        }
        
        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type,'data'=>$this->return_data]);
    }
    public function save_default($id)
    {
        ShipperConsignee::where('contact_id',Auth::user()->contact_id)
        ->update(['default_customer'=>0]);

        ShipperConsignee::where('contact_id',Auth::user()->contact_id)
        ->where('shipper_consignee_id',$id)
        ->update(['default_customer'=>1]);
        return response()->json(['message'=>'Saved']);
    }

    public function get_contacts_deactivated(){
        $cdate=date('Y-m-d',strtotime(date('Y-m-d').' -1 month'));
        echo json_encode(
            ShipperConsignee::where('contact_id',Auth::user()->contact_id)
                    ->where('shipper_consignee_status',2)
                    ->where('deactivated_view',0)
                    ->whereDate('date_deactivated','>=',$cdate)
                    ->count()
        );
    }

    public function update_contacts_deactivated(){

        ShipperConsignee::where('contact_id',Auth::user()->contact_id)
            ->where('shipper_consignee_status',2)
            ->where('deactivated_view',0)
            ->update([
            'deactivated_view'=>1
        ]);

        echo json_encode(1);
    }
}
