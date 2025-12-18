<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\OnlineSite\UserAddress;
use App\OnlineSite\Contact;
use Carbon\Carbon;
trait ReferenceTrait{

	public function random_num($length){
		$val = "";
		$valChars = array_merge(range(0,9));
		for($i=0;$i < $length; $i++){
			$val .= $valChars[array_rand($valChars)];
		}
		return $val;
	}

	public function random_alph_num($lenght1,$length2){
		$characters = '0123456789';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $lenght1; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	        
	    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    //$randomString = '';
	    for ($i = 0; $i < $length2; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function random_alpha_uclc($lenght){
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $lenth; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function Encrypt($string) {
	    $crypted = sha1($string);                         
	    return $crypted;
	}

	function encrypt_decrypt($action, $string) {
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'argus';
		$secret_iv = 'zilong';
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if( $action == 'decrypt' ) {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		
		return $output;
	}

	public function generate_useraddress_no(){
		$useraddress_no = "OLA-".$this->random_alph_num(2,3);
		while(UserAddress::where('useraddress_no',$useraddress_no)->exists()){
            $useraddress_no = "OLA-".$this->random_alph_num(2,3);
        }
		return $useraddress_no;
	}

	public function generate_contact_id(){
		$contact_id = "OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8);
        while(Contact::where('contact_id',$contact_id)->exists()){
            $contact_id = "OL-".date('y',strtotime(Carbon::now()))."-".$this->random_num(8);
        }
        return $contact_id;
	}

	

}
?>