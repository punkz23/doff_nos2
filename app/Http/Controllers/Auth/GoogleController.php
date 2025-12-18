<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferenceTrait;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\OnlineSite\Contact;
use App\OnlineSite\ShipperConsignee;
use Carbon\Carbon;
use Socialite;
use Exception;
use Auth;
class GoogleController extends Controller
{

	use ReferenceTrait;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   

    public function handleGoogleCallback()
    {

        try {
            $user = Socialite::driver('google')->user();
            
            $create['name'] = $user->getName();
            $create['email'] = $user->getEmail();
            $create['google_id'] = $user->getId();
            $create['password']= Hash::make($user->getId().date('m/d/Y H:i:s',strtotime(Carbon::now())));
            $create['user_status']='Active';

            $finduser = User::where('google_id', $user->id)->first();
            if($finduser){
                Auth::login($finduser);
                return redirect()->route('home');
            }else{
            	// dd($user);
            	$contact = Contact::firstOrNew(['email'=>$user->getEmail()]);

	            if(!$contact->exists){
	            	$contact->fill([
	            					'contact_id'=>"OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8),
	                              	'fileas'=>$user->getName(),
	                              	'lname'=>$user->user['family_name'],
	                              	'fname'=>$user->user['given_name'],
	                                'mname'=>'',
	                                'email'=>$user->getEmail(),
	                                'birthday'=>date('Y-m-d',strtotime(Carbon::now())),
	                                'gender'=>'',
	                                'religion'=>'',
	                                'nationality'=>'',
	                                'civil_status'=>'',
	                                'contact_no'=> '',
	                                'company'=> '',
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
	                                'profile_photo_path'=>$user->user['picture'],
	                                'department'=>null,
	                                'position'=>null,
	                                'doff_account'=>null,
	                                'contact_status'=>0, 
	                                'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
	            				  ])->save();

	        		ShipperConsignee::create(['contact_id'=>$contact->contact_id,'shipper_consignee_id'=>$contact->contact_id,'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),'rider'=>0]);
	            }
	            $create['contact_id']=$contact->contact_id;

                $newUser = User::create($create)->assignRole('Client');
                Auth::login($newUser);

                return redirect()->route('home');
            }
        } catch (Exception $e) {
            return redirect('auth/google');
        }

    }
}
