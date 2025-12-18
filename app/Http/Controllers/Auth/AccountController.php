<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\FAQ\Category;
use App\OnlineSite\BusinessType;
use App\OnlineSite\Province;
use App\OnlineSite\City;
use App\OnlineSite\Contact;
use App\OnlineSite\UserAddress;
use App\User;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\ReferenceTrait;
use Jenssegers\Agent\Agent;
use Validator;
use Auth;
use App\Http\Resources\AddressResource;
use App\OnlineSite\ShipperConsignee;
use Storage;
class AccountController extends Controller
{

	use ReferenceTrait;

	private $title;
	private $message;
	private $type;
	private $return_data;

    public function __construct(){
		$this->middleware('auth')->except(['getCities']);
	}

	public function index(){

		$business_types=BusinessType::with('business_type_category')->get();
		$provinces = Province::get();
		$cities=City::orderBy('cities_name','ASC')->get();
		$ddCities = '';
		foreach($cities as $city){
			$ddCities=$ddCities.'<option value="'.$city->cities_id.'">'.$city->cities_name.'</option>';
		}
        $pca_details= DB::table('waybill.tblpca_accounts')
        ->where('tblpca_accounts.pca_account_no',Auth::user()->contact_id)
        ->leftJoin("waybill.tblpublication_rate_template","tblpca_accounts.publication_rate_template_id","=","tblpublication_rate_template.publication_rate_template_id")
        ->selectRaw("
        IFNULL(tblpca_accounts.publication_main_rate,tblpublication_rate_template.main_rate) as publication_main_rate,
        IFNULL(tblpca_accounts.publication_tabloid_rate,tblpublication_rate_template.tabloid_rate) as publication_tabloid_rate,
        IFNULL(tblpca_accounts.min_deposit,(SELECT min_deposit FROM waybill.tblpca_min_deposit WHERE pca_type=tblpca_accounts.personal_corporate )) as min_deposit
        ")
        ->first();

        return view('auth.profile',compact('business_types','provinces','ddCities','pca_details'));
	}

	public function account_address(){
		echo json_encode(Auth::user()->user_address->toArray());
	}

	public function update_profile(Request $request){



		$validation=Validator::make($request->all(),
                [
                    'fname'=>$request->use_company=='on' ? '' : 'required',
					'lname'=>$request->use_company=='on' ? '' : 'required',
					'company'=>$request->use_company=='on' ? 'required' : '',
                    //'email'=>'required',
                    //'contact_no'=>'required|numeric',

                ],
                [
                    'fname.required'=>'Firstname is required',
					'lname.required'=>'Lastname is required',
					'company'=>'Company is required',
                    //'email.required'=>'Email is required',

                ]
		);


        $this->title='';
        $this->message='';
        $this->type='';



        if($validation->passes()){

        	DB::transaction(function() use($request){

		    	try{

		    		$user = User::where('id',Auth::user()->id)
			        		  ->update([
			        		  	'name'=>$request->use_company=='on' ? $request->company : $request->lname.', '.$request->fname.' '.$request->mname
							  ]);
					$lname = $request->use_company!=='on' ? $request->lname : '';
					$fname = $request->use_company!=='on' ? $request->fname : '';
					$mname = $request->use_company!=='on' ? $request->mname : '';
		        	$contact = Contact::where('contact_id',Auth::user()->contact_id)
			        		   ->update([
			        		   		'fileas'=>$request->use_company=='on' ? $request->company : $request->lname.', '.$request->fname.' '.$request->mname,
			        		   		'lname'=>$lname,
			        		   		'fname'=>$fname,
			        		   		'mname'=>$mname,
			        		   		//'contact_no'=>$request->contact_no,
			        		   		//'email'=>$request->email,
			        		   		'company'=>$request->company!='' ? $request->company : '',
									'business_category_id'=>$request->business_category_id!=null ? $request->business_category_id : 0,
									'use_company'=> $request->use_company=='on' ? 1 : 0
			        		   ]);


		        	if(!$user || !$contact){
		        		DB::rollBack();
		        		$this->message='No changes';
		        	}else{
		        		DB::commit();
		        		$this->message='Profile has been updated';
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
        }

        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
	}
	public function update_em(Request $request){

		$validation=Validator::make($request->all(),
                [
                    'change_email_mobile_input' => ['required', 'string'],
					'change_email_mobile_type' => ['required', 'string']
                ]
        );
		$this->title='';
        $this->message='';
        $this->type='';

        if($validation->passes()){

			DB::transaction(function() use($request){
				try{
					if(
						$request->change_email_mobile_type =='mobile'
					){

						$txt='Mobile No.';
						$change_em = User::where('id',Auth::user()->id)
						->update(['mobileNo'=>$request->change_email_mobile_input]);
						$contact = Contact::where('contact_id',Auth::user()->contact_id)
			        		   ->update([
			        		   		'contact_no'=>$request->change_email_mobile_input
			        		   ]);

					}else{

						$txt='Email';
						$change_em = User::where('id',Auth::user()->id)
						->update(['email'=>$request->change_email_mobile_input]);
						$contact = Contact::where('contact_id',Auth::user()->contact_id)
			        		   ->update([
			        		   		'email'=>$request->change_email_mobile_input
			        		   ]);
                        if(
                            Auth::user()->personal_corporate==1
                            && session('pca_no') == Auth::user()->contact_id
                        ){
                            DB::table('waybill.tblpca_accounts')
                            ->where("pca_account_no",Auth::user()->contact_id)
                            ->update([
                                'email'=>$request->change_email_mobile_input
                            ]);

                            $pca_logs='CLIENT UPDATED EMAIL TO '.$request->change_email_mobile_input;
                            DB::table('recordlogs.tblpca_accounts_logs')
                            ->insert([
                                'module' => 'CLIENT WEBSITE',
                                'logs' => $pca_logs,
                                'pca_account_no' =>  Auth::user()->contact_id
                            ]);
                        }

					}

					if(!$change_em || !$contact){
						DB::rollBack();
						$this->message='No changes';
					}else{
						DB::commit();
						$this->message=$txt.' has been updated';
					}
					$this->title='Success!';
					$this->icon='success';
					$this->type=1;

				}catch(Exception $e){
					DB::rollBack();
					$this->title='Ooops!';
					$this->message='There something wrong!<br>No rows affected';
					$this->icon='error';
					$this->type=0;
				}
			});


        }else{
        	$this->title='Ooops!';
            $this->message='Please fill required fields';
            $this->type=0;
			$this->icon='error';
			print_r($validation->errors());
        }

		return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
	}
	public function update_password(Request $request){
		$validation=Validator::make($request->all(),
                [
                    //'current_password'=>'required',
                    'new_password' => ['required', 'string', 'min:8','confirmed'],
                ]
        );

		$this->title='';
        $this->message='';
        $this->type='';

        if($validation->passes()){
        	//if(Hash::check($request->current_password,Auth::user()->password)){
        		DB::transaction(function() use($request){

			    	try{

			    		$change_password = User::where('id',Auth::user()->id)->update(['password'=>Hash::make($request->new_password)]);


			        	if(!$change_password){
			        		DB::rollBack();
			        		$this->message='No changes';
                            $this->icon='error';

			        	}else{
			        		DB::commit();
			        		$this->message='Password has been updated';
                            $this->icon='success';
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

        	// }else{
        	// 	$this->title='Ooops!';
	        //     $this->message='Invalid Current password';
	        //     $this->type='error';
            //     $this->icon='error';
        	// }
        }else{
        	$this->title='Ooops!';
            $this->message='Please fill required fields';
            $this->type='error';
            $this->icon='error';
			print_r($validation->errors());
        }

         return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);

	}

	public function save_address(Request $request){

		$validation=Validator::make($request->all(),
                [
                	'province'=>'required',
                	'city'=>'required',
                    'barangay'=>'required',
                    'address_caption'=>'required',
                ],
                [
                    'province.required'=>'Province is required',
                    'city.required'=>'City is required',
                    'barangay.required'=>'Barangay is required',
                    'address_caption.required'=>'Address caption is required',
                ]
        );

        if($validation->passes()){
        	DB::transaction(function() use($request){

			    try{

			    	$user_address = $request->useraddress_no=='' ? UserAddress::create([
			    		'useraddress_no'=>"OLA-".$this->random_alph_num(2,3),
			    		'address_caption'=>$request->address_caption,
			    		'province'=>$request->province,
			    		'city'=>$request->city,
			    		'barangay'=>$request->barangay,
			    		'street'=>$request->street,
			    		'postal_code'=>$request->postal_code,
			    		'user_id'=>Auth::user()->contact_id
			    	]) : UserAddress::where('user_id',Auth::user()->contact_id)->where('useraddress_no',$request->useraddress_no)->update([
			    			'address_caption'=>$request->address_caption,
				    		'province'=>$request->province,
				    		'city'=>$request->city,
				    		'barangay'=>$request->barangay,
				    		'street'=>$request->street,
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
        }
        return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type,'data'=>$this->return_data]);
	}

	public function getCities($id){
		echo json_encode(City::where('province_id',$id)->get()->toArray());
	}

	public function get_useraddress($id){
		echo json_encode(UserAddress::where('useraddress_no',$id)->first()->toArray());
	}

	public function setDefault(Request $request,$contact_id){
		$validation=Validator::make($request->all(),
                [
                	'useraddress_no'=>'required'
                ],
                [
                    'useraddress_no.required'=>'UserAddress is required',
                ]
        );
        if($validation->passes()){
        	DB::transaction(function() use($request,$contact_id){

			    try{
					$all_address=UserAddress::where('user_id',$contact_id)->update(['address_def'=>0]);

			    	$address = UserAddress::where('useraddress_no',$request->useraddress_no)->update(['address_def'=>1]);
			        if(!$address && !$all_address){
			        	DB::rollBack();
			        	$this->message='No rows affected';
			        }else{
			        	DB::commit();
			        	$this->message='Address has been set to default';
			        }
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
        	$this->title='Ooops!';
			$this->message='There something wrong!';
			$this->type='error';
        }

		return response()->json(['title'=>$this->title,'message'=>$this->message,'type'=>$this->type]);
	}

	public function get_Alluseraddress($id){

		$sc=ShipperConsignee::where('shipper_consignee_id',$id)
		->where('contact_id',Auth::user()->contact_id)
		->where('shipper_consignee_status',1)
		->first();
		$qr_code=$sc->qr_code;

		if( $sc->qr_code !='' &&  $sc->qr_code !=null ){

			$all= UserAddress::where('user_id',$id)
			->whereHas('qr_details',function($query) use ($qr_code) {
                $query->where('qrcode_profile_id',$qr_code);
            })
			->whereNotNull('sectorate_no')
			->with('sector')->get();

		}else{
			$all= UserAddress::where('user_id',$id)->whereNotNull('sectorate_no')->with('sector')->get();
		}

		return AddressResource::collection($all);
	}
}
