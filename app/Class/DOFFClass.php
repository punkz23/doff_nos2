<?php
    namespace App\Class;

    use App\Models\User\SuperAdmin;
    use App\Models\User\TemplateRight;
    use App\Models\User\Menu;
    use App\Models\User;
    use Session;
    use DB;
    use Auth;
    use HTMLPurifier;
    use HTMLPurifier_Config;
    use Symfony\Component\HttpFoundation\StreamedResponse;

    class DOFFClass
    {
        function encrypt($string) {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = session::getId();
            $secret_iv = 'PUKuku64I-rLS&Ves9_8';
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);

            return $output;
        }

        function decrypt($string) {
            $output = false;
            $encrypt_method = "AES-256-CBC";
            $secret_key = session::getId();
            $secret_iv = 'PUKuku64I-rLS&Ves9_8';
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

            return $output;
        }

        function createREFNumber($type, $batchcode = ''){
            if($batchcode==''){
                $batchcode = session('batchcode');
            }
            return $type.substr($batchcode,0,3).date("y").$this->random_letters(4).$this->random_numbers(4);
        }

        function createUnique_REFNumber($type, $table, $field, $branch_batchcode = ""){
            while (true){
                if($branch_batchcode != ""){
                    $batch = $branch_batchcode;
                } else {
                    $batch = "WEB";
                }
                switch($type){
                    case "RQ" :
                    case "REQ" :
                    case "PO" :
                        $reference = $type.substr($batch,0,3).date("y").$this->random_numbers(6).$this->random_numbers(2);
                        break;
                    default :
                        $reference = $type.substr($batch,0,3).date("y").$this->random_numbers(6).$this->random_numbers(2);
                        break;
                }
                $result = DB::table($table)
                            ->select($field)
                            ->where($field, $reference)
                            ->get();

                if(!$result || $result->count() <= 0){
                    break;
                }
            }

            return $reference;
        }

        public function getBatchCode(){
            $query = DB::table("doff_configuration.tblbranchoffice")
                            ->selectRaw('tblbranchoffice.branchoffice_no,
                            tblbranchoffice.branchoffice_description,
                            tblbranchoffice.branch_prefix,
                            bh_details.batch_start,bh_details.batch_end,bh_details.batch_code,
                            tbldeposit.transactiondate')
                            ->leftJoin(
                                DB::raw("(SELECT MAX(batch_start) as batch_start, branchoffice_no
                                        FROM waybill.tblbranch_batchcode_history
                                        WHERE DATE(batch_start) <= CURRENT_DATE GROUP BY branchoffice_no) `bh_max`"), 'bh_max.branchoffice_no', 'tblbranchoffice.branchoffice_no')
                            ->leftJoin('waybill.tblbranch_batchcode_history as bh_details', function($join){
                                $join->on(DB::Raw('bh_details.batch_start'), DB::Raw('bh_max.batch_start'))
                                ->on('bh_details.branchoffice_no','tblbranchoffice.branchoffice_no');
                            })
                            ->leftJoin('waybill.tbldeposit', function($join){
                                $join->on(DB::Raw('tbldeposit.transactiondate'), DB::Raw('DATE(bh_details.batch_start)'))
                                ->on(DB::Raw('LEFT(tbldeposit.batchcode,3)'), 'tblbranchoffice.branch_prefix');
                            })
                            ->where("tblbranchoffice.branchoffice_no", Auth::user()->contacts->branchoffice_id)
                            ->groupBy('tblbranchoffice.branchoffice_no')
                            ->get();

            if($query[0]->batch_code){
                if(($query[0]->transactiondate != '' && $query[0]->batch_end != '') || ($query[0]->transactiondate == '' && $query[0]->batch_end == '' && date("Y-m-d", strtotime($query[0]->batch_start)) != date("Y-m-d"))){
                    $query = DB::table('doff_configuration.tblbranchoffice')
                    ->select('branch_prefix')
                    ->where('branchoffice_no', Auth::user()->contacts->branchoffice_id)
                    ->get();

                    $batchcode = "{$query[0]->branch_prefix}".$this->random_numbers(6).$this->random_letters(1);

                    DB::table('waybill.tblbranch_batchcode_history')
                    ->insert([
                        "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                        "batch_code" => $batchcode,
                        "branchoffice_no" => Auth::user()->contacts->branchoffice_id
                    ]);

                    return $batchcode;

                } else {
                    return $query[0]->batch_code;
                }
            }
        }

        public function getUserCode($batch_code, $user_id, $terminal_no){
            $query = DB::table('waybill.tbluser_login')
            ->selectRaw("*")->where('user_id', $user_id)
            ->get();
            if($query){
                if(date("Y-m-d", strtotime($query[0]->login_start)) == date("Y-m-d")){
                    $user_batch_code = $query[0]->user_batch_code;
                    $query = DB::table('waybill.tblcashierremittance')
                    ->select('CashierBatchCode')
                    ->where('CashierBatchCode', $query[0]->user_batch_code)
                    ->get();
                    if($query->count() != 0){
                        $user_batch_code = date("y");
                        $characters = "0123456789QWERTYUIOPASDFGHJKLZXCVBNM";
                        $characters_length = strlen($characters);
                        for($i=0; $i<8; $i++){
                            $user_batch_code.= $characters[rand(0, $characters_length-1)];
                        }
                        $result = DB::transaction(function () use($user_id, $user_batch_code, $batch_code, $terminal_no) {
                            DB::table('waybill.tbluser_login')
                            ->where('user_id', $user_id)
                            ->update([
                                "user_batch_code" => $user_batch_code,
                                "login_start" => DB::Raw('CURRENT_TIMESTAMP')
                            ]);
                            DB::table('waybill.tblcashier_batchcode_history')
                            ->insert([
                                "user_id" => $user_id,
                                "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                                "user_batch_code" => $user_batch_code,
                                "branch_batch_code" => $batch_code,
                                "terminal_no" => $terminal_no
                            ]);
                        });
                    }
                    return $user_batch_code;
                } else {
                    $count_transaction_current = DB::table('waybill.tblorcrdetails')
                    ->selectRaw('COUNT(reference_no) as count_transaction')
                    ->where('cashier_batch_code', $query[0]->user_batch_code)
                    ->whereRaw("DATE(transaction_date) = CURRENT_DATE")
                    ->get();
                    $count_transaction_prev = DB::table('waybill.tblorcrdetails')
                    ->selectRaw('COUNT(reference_no) as count_transaction')
                    ->where('cashier_batch_code', $query[0]->user_batch_code)
                    ->whereRaw("DATE(transaction_date) < CURRENT_DATE")
                    ->get();
                    if($count_transaction_current[0]->count_transaction <= 0 && $count_transaction_prev[0]->count_transaction > 0){
                        $user_batch_code = date("y");
                        $characters = "0123456789QWERTYUIOPASDFGHJKLZXCVBNM";
                        $characters_length = strlen($characters);
                        for($i=0; $i<8; $i++){
                            $user_batch_code.= $characters[rand(0, $characters_length-1)];
                        }
                        $result = DB::transaction(function () use($user_id, $user_batch_code, $batch_code, $terminal_no) {
                            DB::table('waybill.tbluser_login')
                            ->where('user_id', $user_id)
                            ->update([
                                "user_batch_code" => $user_batch_code,
                                "login_start" => DB::Raw('CURRENT_TIMESTAMP'),
                                "terminal_no" => $terminal_no
                            ]);
                            DB::table('waybill.tblcashier_batchcode_history')
                            ->insert([
                                "user_id" => $user_id,
                                "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                                "user_batch_code" => $user_batch_code,
                                "branch_batch_code" => $batch_code,
                                "terminal_no" => $terminal_no
                            ]);
                        });
                        return $user_batch_code;
                    } else if ($count_transaction_current[0]->count_transaction > 0 && $count_transaction_prev[0]->count_transaction <= 0 || ($count_transaction_current[0]->count_transaction <= 0 && $count_transaction_prev[0]->count_transaction <= 0)){
                        $user_batch_code = $query[0]->user_batch_code;
                        $result = DB::transaction(function () use($user_id, $user_batch_code, $batch_code, $terminal_no) {
                            DB::table('waybill.tbluser_login')
                            ->where('user_id', $user_id)
                            ->update([
                                "login_start" => DB::Raw('CURRENT_TIMESTAMP'),
                                "terminal_no" => $terminal_no
                            ]);
                            DB::table('waybill.tblcashier_batchcode_history')
                            ->insert([
                                "user_id" => $user_id,
                                "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                                "user_batch_code" => $user_batch_code,
                                "branch_batch_code" => $batch_code,
                                "terminal_no" => $terminal_no
                            ]);
                        });
                        return $user_batch_code;
                    } else {
                        $user_batch_code = $query[0]->user_batch_code;
                        DB::table('waybill.tblcashier_batchcode_history')
                        ->insert([
                            "user_id" => $user_id,
                            "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                            "user_batch_code" => $user_batch_code,
                            "branch_batch_code" => $batch_code,
                            "terminal_no" => $terminal_no
                        ]);
                        return $user_batch_code;
                    }
                }
            } else {
                $user_batch_code = date("y");
                $characters = "0123456789QWERTYUIOPASDFGHJKLZXCVBNM";
                $characters_length = strlen($characters);
                for($i=0; $i<8; $i++){
                    $user_batch_code.= $characters[rand(0, $characters_length-1)];
                }
                $result = DB::transaction(function () use($user_id, $user_batch_code, $batch_code, $terminal_no) {
                    DB::table('waybill.tbluser_login')
                    ->insert([
                        "user_id" => $user_id,
                        "login_start" => DB::Raw('CURRENT_TIMESTAMP'),
                        "user_batch_code" => $user_batch_code,
                        "terminal_no" => $terminal_no
                    ]);
                    DB::table('waybill.tblcashier_batchcode_history')
                    ->insert([
                        "user_id" => $user_id,
                        "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                        "user_batch_code" => $user_batch_code,
                        "branch_batch_code" => $batch_code,
                        "terminal_no" => $terminal_no
                    ]);
                });
                return $user_batch_code;
            }
        }

        function random_letters($len =5){
            $val = "";
            $valChars = array_merge(range('A', 'Z'));
            for($i=0;$i < $len; $i++){
                $val .= $valChars[array_rand($valChars)];
            }
            return $val;
        }

        function random_numbers($len =5){
            $val = "";
            $valChars = array_merge( range(0,9));
            for($i=0;$i < $len; $i++){
                $val .= $valChars[array_rand($valChars)];
            }
            return $val;
        }

        function random_string($len =5){
            $val = "";
            $valChars = array_merge(range('A', 'Z'), range(0,9));
            for($i=0;$i < $len; $i++){
                $val .= $valChars[array_rand($valChars)];
            }

            return $val;
        }

        function sanitizeString($string) {
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            return $purifier->purify($string);
        }

        public function user_right($menu_name,$user_id='',$main=false){

            $user_id = $user_id != '' ? $user_id : Auth::user()->contact_id;

            session(['module' => $menu_name]);

            $rights['right_view'] =0;
            $rights['right_add'] =0;
            $rights['right_edit'] =0;
            $rights['right_delete'] =0;
            $rights['super_user'] =0;

            if(SuperAdmin::where('contact_id',$user_id)->count()>0){
                $rights['right_view'] =1;
                $rights['right_add'] =1;
                $rights['right_edit'] =1;
                $rights['right_delete'] =1;
                $rights['super_user'] =1;
            }else{
                $user = User::where('contact_id',$user_id)->first();
                $menu = Menu::where('menu_description',$menu_name)->first();
                if($menu){
                    $template = TemplateRight::select('right_view', 'right_add', 'right_edit', 'right_delete')
                                                ->join('tblmenu','tblmenu.menu_id','tbltemplate_right.menu_id')
                                                ->where('tbltemplate_right.menu_id',$menu->menu_id)
                                                ->where('template_id',$user->usertype_no)
                                                ->first();

                    if($template){
                        $rights['right_view'] = $template->right_view;
                        $rights['right_add'] = $template->right_add;
                        $rights['right_edit'] = $template->right_edit;
                        $rights['right_delete'] = $template->right_delete;
                        $rights['super_user'] =0;
                    }
                }
            }

            if(session('account')!=Auth::user()->user_password){
                $rights['right_view'] =0;
                $rights['right_add'] =0;
                $rights['right_edit'] =0;
                $rights['right_delete'] =0;
                $rights['super_user'] =0;
            }


            return $rights;
        }


        public function streamVideo($path){
            $filePath = public_path($path);

            if (!file_exists($filePath)) {
                return response()->json(['message' => 'File not found'], 404);
            }

            $fileSize = filesize($filePath);
            $file = fopen($filePath, 'rb');
            $response = new StreamedResponse(function () use ($file) {
                fpassthru($file);
            });

            $response->headers->set('Content-Type', mime_content_type($filePath));
            $response->headers->set('Content-Length', $fileSize);
            $response->headers->set('Accept-Ranges', 'bytes');

            // byte range reqs
            if (isset($_SERVER['HTTP_RANGE'])) {
                [$param, $range] = explode('=', $_SERVER['HTTP_RANGE']);
                if ($param === 'bytes') {
                    [$start, $end] = explode('-', $range);
                    $start = intval($start);
                    $end = $end === '' ? $fileSize - 1 : intval($end);

                    fseek($file, $start);
                    $length = $end - $start + 1;

                    $response->setCallback(function () use ($file, $length) {
                        echo fread($file, $length);
                        fclose($file);
                    });

                    $response->setStatusCode(206);
                    $response->headers->set('Content-Range', "bytes $start-$end/$fileSize");
                    $response->headers->set('Content-Length', $length);
                }
            }

            return $response;
        }

        // public function streamVideo($url){
        //     if (!filter_var($url, FILTER_VALIDATE_URL)) {
        //         return response()->json(['message' => 'Invalid URL'], 400);
        //     }

        //     $headers = get_headers($url, 1);
        //     if (!$headers || strpos($headers[0], '200') === false) {
        //         return response()->json(['message' => 'File not found'], 404);
        //     }

        //     $fileSize = isset($headers['Content-Length']) ? (int)$headers['Content-Length'] : null;
        //     $contentType = $headers['Content-Type'] ?? 'application/octet-stream';

        //     $file = fopen($url, 'rb');
        //     if (!$file) {
        //         return response()->json(['message' => 'Unable to open file'], 500);
        //     }

        //     $response = new StreamedResponse(function () use ($file) {
        //         fpassthru($file);
        //         fclose($file);
        //     });

        //     $response->headers->set('Content-Type', $contentType);
        //     if ($fileSize) {
        //         $response->headers->set('Content-Length', $fileSize);
        //     }
        //     return $response;
        // }
    }
?>
