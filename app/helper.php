<?php
    use App\OnlineSite\Waybill;
    use App\Newsletter\NewsletterSubscriber;
    use Illuminate\Support\Facades\Auth;

    if(!function_exists('generateReferenceNumber')){
        function generateReferenceNumber(){
            while (true){ 
                $reference_number = 'OL-'.date('y').random_alph_num(3,2);
                $reference_count= Waybill::where('reference_no',$reference_number)->count();
                if($reference_count<=0){
                    break;
                }
            }
            return $reference_number;
        }
    }

    function referenceNumberExists($reference_no){
        return Waybill::where('reference_no',$reference_no)->exists();
    }

    function random_alph_num($lenght1,$length2){
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

    if(!function_exists('encryptEnv')){
        function encryptEnv($token){
            // $cipher_method = 'aes-128-ctr';
            $cipher_method=\Config::get('app.cipher');
            $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
            $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
            $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
            unset($token, $cipher_method, $enc_key, $enc_iv);
            return $crypted_token;
        }
    }

    if(!function_exists('decryptEnv')){
        function decryptEnv($crypted_token){
            // $crypted_token=encrypt_decrypt($crypted_token,'decrypt');
            list($crypted_token, $enc_iv) = explode("::", $crypted_token);
            // $cipher_method = 'aes-128-ctr';
            $cipher_method=\Config::get('app.cipher');
            $enc_key = openssl_digest(php_uname(), 'SHA256', TRUE);
            $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
            unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
            return $token;
        }
    }

    function newsletterSubscription(){
        return Auth::check()== true ? NewsletterSubscriber::where(['customer_id'=>Auth::user()->contact_id,'subscription_status'=>1])->first() : null;
    }
?>