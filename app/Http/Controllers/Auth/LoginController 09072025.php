<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\OnlineSite\User as OldUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ReferenceTrait;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ReferenceTrait;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function oldEncryption($password){
		$key1 = "DOFF_979805";
		$passwdOLD = '';
		$test1 = [];
		for($i=0; $i<strlen($password); $i++) {
			$char1 = substr($password, $i, 1);
			$keychar1 = substr($key1, ($i % strlen($key1))-1, 1);
			$char1 = chr(ord($char1)+ord($keychar1));
			$test1[$char1]= ord($char1)+ord($keychar1);
			$passwdOLD.=$char1;
		}
        return base64_encode($passwdOLD);
    }

    protected function attemptLogin(Request $request)
    {
        return \Auth::attempt(
            $this->credentials($request) + ["personal_corporate" => $request->rpc_account],
            $request->filled('personal_corporate')
        );
    }

    protected function validateLogin(Request $request)
    {
        $check_social = OldUser::where('user_name',$request->email)->where(function($query){ $query->whereNotNull('fb_id')->orWhereNotNull('oauth_uid'); })->count();

        if($check_social==0){
            $oldPassword = $this->oldEncryption($request->password);
            $old_user = OldUser::where('user_name',$request->email)
                                ->where(function($query) use($request,$oldPassword){
                                    $query->where('user_password',sha1($request->password))->orWhere('user_password',$oldPassword);
                                });
                                
            if($old_user->count()>0){
                $old_data = $old_user->with('contact')->first();
                $user=User::firstOrNew(['email'=>$request->email]);
                if(!$user->exists){
                    User::create([
                        'email'=>$request->email,
                        'name'=>$old_data->contact->fileas,
                        'password'=>Hash::make($request->password),
                        'contact_id'=>$old_data->contact_id,
                        'facebook_id'=>$old_data->fb_id,
                        'google_id'=>$old_data->oauth_uid
                    ])->assignRole('Client');
                    // $user->fill(['name'=>$old_data->contact->fileas,'password'=>Hash::make($request->password),'contact_id'=>$old_data->contact_id,'facebook_id'=>$old_data->fb_id,'google_id'=>$old_data->oauth_uid])->save();
                }
            }
        }


        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    protected function authenticated(Request $request, $user)
    {


        if($user->personal_corporate==1){
           
            if (!$request->session()->has('pca_no')) {

                $pca_account = DB::table('waybill.tblpca_accounts')
                ->where('pca_account_no',\Auth::user()->contact_id)
                ->where('pca_account_status',1)
                ->count();
                
                if( $pca_account > 0){
                    $request->session()->put('pca_no', \Auth::user()->contact_id);
                    $request->session()->put('pca_atype','main_acount');
                }else{

                    $pca_account = DB::table('dailyove_online_site.tblpca_internal_external_accounts')
                    ->where('tblpca_internal_external_accounts.contact_id',\Auth::user()->contact_id)
                    ->where('tblpca_internal_external_accounts.account_status',1)
                    ->where('tblpca_accounts.pca_account_status',1)
                    ->leftJoin("waybill.tblpca_accounts","tblpca_internal_external_accounts.pca_account_no","=","tblpca_accounts.pca_account_no")
                    ->first();  
                    if($pca_account){
                        $request->session()->put('pca_no', $pca_account->pca_account_no);
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
                    }else{

                        $this->guard()->logout();
                        $request->session()->invalidate();
                        return redirect('/login')->with('pca_no_account_found','No Business Account Found.');

                    }
                }
            }
        }

        //return $request->session()->get('pca_no').' | '.$request->session()->get('pca_atype');
    }

}
