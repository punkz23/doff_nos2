<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\OnlineSite\Contact;
use App\OnlineSite\ShipperConsignee;
use App\OnlineSite\User as OldUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferenceTrait;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Socialite;
use Exception;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class FacebookController extends Controller
{
	use ReferenceTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
    	// dd(Socialite::driver('facebook')->fields([
        //         'first_name', 'last_name', 'email', 'gender', 'birthday','middle_name','name','picture'
        //     ]));
        return Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday','middle_name','name','picture'
        ])->scopes([
            'email', 'user_birthday'
        ])->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday','middle_name','name','picture'
        	])->user();

            $create['name'] = $user->getName();
            $create['email'] = $user->getEmail();
            $create['facebook_id'] = $user->getId();
            $create['password']= Hash::make($user->getId().date('m/d/Y H:i:s',strtotime(Carbon::now())));
            $create['user_status']='Active';

            // $check_user = OldUser::where(['fb_id'=>$user->getId()]);
            // if($check_user->exists){
            //     $create['contact_id']=$check_user->contact_id;
            // }else{
                $contact = Contact::firstOrNew(['email'=>$user->getEmail()]);
                if(!$contact->exists){
                    $contact->fill([
                                    'contact_id'=>"OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8),
                                    'fileas'=>$user->getName(),
                                    'lname'=>$user->user['last_name'],
                                    'fname'=>$user->user['first_name'],
                                    'mname'=>$user->user['middle_name'],
                                    'email'=>$user->getEmail(),
                                    'birthday'=>date('Y-m-d',strtotime($user->user['birthday'])),
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
                                    'profile_photo_path'=>'',
                                    'department'=>null,
                                    'position'=>null,
                                    'doff_account'=>null,
                                    'contact_status'=>0,
                                    'member_since'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),
                                ])->save();

                    ShipperConsignee::create(['contact_id'=>$contact->contact_id,'shipper_consignee_id'=>$contact->contact_id,'latest_transaction'=>date('Y-m-d H:i:s',strtotime(Carbon::now())),'rider'=>0]);
                }
                $create['contact_id']=$contact->contact_id;
                // $img = Image::make($user->avatar_original);
                // $img->save($contact->contact_id.'.png');
                // $create['avatar']=$contact->contact_id;
            // }





            $userModel = new User;
            $createdUser = $userModel->addNew($create);

            Auth::loginUsingId($createdUser->id);

            return redirect()->route('home');


        } catch (Exception $e) {
            return redirect(url('/auth/facebook'));

        }
    }

    

    public function fb_data_deletion(Request $request){
        // header('Content-Type: application/json');
        // $signed_request =  $request->query->get("signed_request");
        // $data = parse_signed_request($signed_request);
        // $user_id = $data['user_id'];
        // $fb_deletion = FbDataDeletion::create([
        //     'confirmation_code'=>date('Ym').'-'.str_pad(Auth::user()->id,8,"0",STR_PAD_LEFT),
        //     'user_id'=>Auth::user()->id,
        //     'fb_id'=>Auth::user()->fb_id
        // ]);
        // // Start data deletion
        // $confirmation_code = $fb_deletion->confirmation_code; // unique code for the deletion request
        // $status_url = url('/').'/fb-data-deletion/'.$confirmation_code; // URL to track the deletion
        // $data = array(
        // 'url' => $status_url,
        // 'confirmation_code' => $confirmation_code
        // );
        // echo json_encode($data);
        
        $signed_request =  $request->query->get("signed_request");
        $data = $this->parse_signed_request($signed_request);
        $user_id = $data['user_id'];

        // here will delete the user base on the user_id from facebook
        User::where([
            ['facebook_id' => $user_id]
        ])->forceDelete();

        // here will check if the user is deleted
        $isDeleted = User::withTrashed()->where([
            ['facebook_id' => $user_id]
        ])->find();

        if ($isDeleted ===null) {
            return response()->json([
                'url' => '<url>', // <------ i dont know what to put on this or what should it do
                'confirmation_code' => '<code>', // <------ i dont know what is the logic of this code
            ]);
        }

        return response()->json([
            'message' => 'operation not successful'
        ], 500);
    }

    public function parse_signed_request($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
      
        $secret = "4b5353bf922134f971f60fcbd73f28a2"; // Use your app secret here
      
        // decode the data
        $sig = base64_url_decode($encoded_sig);
        $data = json_decode(base64_url_decode($payload), true);
      
        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
          error_log('Bad Signed JSON signature!');
          return null;
        }
      
        return $data;
      }
      
      public function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
      }

}
