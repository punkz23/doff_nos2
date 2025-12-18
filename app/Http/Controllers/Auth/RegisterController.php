<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\OnlineSite\Contact;
use App\OnlineSite\ShipperConsignee;
use App\OnlineSite\ContactVerification;
use Carbon\Carbon;
use App\Http\Controllers\ReferenceTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Response;
use DB;
use Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, ReferenceTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'contact_status' => ['required', 'in:0,1'],
            'lname' => ['required', 'string', 'max:191'],
            'fname' => ['required', 'string', 'max:191'],
            //'contact_no' => ['numeric'],
            //'contact_no' => ['required', 'numeric'],
            //'email' => ['required', 'string', 'email', 'max:255'],
            //'email' => ['string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //$contact=Contact::firstOrNew(['email'=>$data['email']]);
        $user=User::where('personal_corporate',0);
        if($data['email'] !=''){
            $user= $user->where('email',$data['email']);
        }
        if($data['contact_no'] !=''){
            $user= $user->where('mobileNo',$data['contact_no']);
        }
        $user=$user->first();

        DB::connection('dailyove_online_site')->beginTransaction();
        try{
           // if(!$contact->exists){
           $id='NONE';
           if($user){
             $id=$user->contact_id;
           }
           $contact=Contact::firstOrNew(['contact_id'=>$id]);

           if(!$user){
                $contact->fill([
                'contact_id'=>"OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8),
                'fileas'=>$data['lname'].', '.$data['fname'].' '.$data['mname'],
                'lname'=>$data['lname'],
                'fname'=>$data['fname'],
                'mname'=>$data['mname']!='' ? $data['mname'] : '',
                'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
                'gender'=>'',
                'religion'=>'',
                'nationality'=>'',
                'civil_status'=>'',
                'contact_no'=>$data['contact_no']!='' ? $data['contact_no'] : '',
                'email' => $data['email']!='' ? $data['email'] : '',
                'company'=>$data['company']!='' ? $data['company'] : '',
                'business_category_id'=>0,
                'branchoffice_id'=>0,
                'discount'=>0,
                'bir2306'=>0,
                'bir2307'=>0,
                'vat'=>1,
                'employee'=>0,
                'customer'=>1,
                'use_company'=>0,
                'email_verification'=>0,
                'profile_photo_path'=>'',
                'department'=>null,
                'position'=>null,
                'doff_account'=>null,
                'contact_status'=>$data['contact_status'],
                'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now()))
                ])->save();

                if(isset($data['checkbox_av'])){

                    Contact::where('contact_id', $contact->contact_id)
                    ->update(['verified_account' => 2]);


                    $av_id_pic = Image::make($data['av_id_pic']);
                    Response::make($av_id_pic->encode('jpeg'));

                    $av_id_w_pic = Image::make($data['av_id_w_pic']);
                    Response::make($av_id_w_pic->encode('jpeg'));

                    $form_data = array(
                        'contact_id'  => $contact->contact_id,
                        'contacts_verification_pic_id' => $av_id_pic,
                        'contacts_verification_pic_with_id' => $av_id_w_pic,
                        'valid_id_no' => $data['av_id_type'],
                        'id_no' => $data['av_id_no']
                    );

                    ContactVerification::create($form_data);




                }

            }
            $shipper_consignee=ShipperConsignee::firstOrNew(['contact_id'=>$contact->contact_id,'shipper_consignee_id'=>$contact->contact_id]);
            if(!$shipper_consignee->exists){
                $shipper_consignee->fill(['latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),'rider'=>0])->save();


            }



            DB::connection('dailyove_online_site')->commit();


        }catch(Exception $e){

            DB::rollback('dailyove_online_site');

        }

        //$user = User::firstOrNew(['email'=>$data['email']]);
        ////if(!$user->exists){
        if(!$user){

            return User::create([
                'contact_id'=>$contact->contact_id,
                'name' => $data['lname'].', '.$data['fname'].' '.$data['mname'],
                'email' => $data['email']!='' ? $data['email'] : '',
                'mobileNo'=>$data['contact_no']!='' ? $data['contact_no'] : '',
                'password' => Hash::make($data['password']),
            ])->assignRole('Client');
        }else{

            $user_update=User::where('personal_corporate',0);
            if($data['email'] !=''){
                $user_update= $user_update->where('email',$data['email']);
            }
            if($data['contact_no'] !=''){
                $user_update= $user_update->where('mobileNo',$data['contact_no']);
            }

            $user_update= $user_update->update([
                'contact_id'=>$contact->contact_id,
                'user_status'=>'Active',
                'password' => Hash::make($data['password']),
            ]);

            return  $user;
        }

    }

    public function showRegistrationForm()
    {
        $agent = new Agent();
        $view = ($agent->isMobile() || $agent->isTablet()) && \Config::get('app.mobile')==1 ? 'mobile.auth.register' : 'auth.register';
        return view($view);
    }

}
