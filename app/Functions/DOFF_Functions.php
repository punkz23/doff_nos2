<?php

namespace App\Functions;

use DB;
use Auth;

class DOFF_Functions {

    public function GetBatchCode(){
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
        ->where("tblbranchoffice.branchoffice_no", Auth::user()->contact->branchoffice_id)
        ->groupBy('tblbranchoffice.branchoffice_no')
        ->get();

        if($query[0]->batch_code){
            if(($query[0]->transactiondate != '' && $query[0]->batch_end != '') || ($query[0]->transactiondate == '' && $query[0]->batch_end == '' && date("Y-m-d", strtotime($query[0]->batch_start)) != date("Y-m-d"))){
                $query = DB::table('doff_configuration.tblbranchoffice')
                ->select('branch_prefix')
                ->where('branchoffice_no', Auth::user()->contact->branchoffice_id)
                ->get();

                $batchcode = "{$query[0]->branch_prefix}".$this->ref2(date("ymdhisa"),6).$this->ref(date("ymdhisa"),1);

                DB::table('waybill.tblbranch_batchcode_history')
                ->insert([
                    "batch_start" => DB::Raw('CURRENT_TIMESTAMP'),
                    "batch_code" => $batchcode,
                    "branchoffice_no" => Auth::user()->contact->branchoffice_id
                ]);

                return $batchcode;

            } else {
                return $query[0]->batch_code;
            }
        }
    }

    public function GetUserCode($batch_code, $user_id, $terminal_no){
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

    function ref($input,$len =4){
		$val = "";
		$valChars = array_merge(range('A', 'Z'));
		for($i=0;$i < $len; $i++){
			$val .= $valChars[array_rand($valChars)];
		}
		return $val;
	}

	function ref2($input,$len =4){
		$val = "";
		$valChars = array_merge( range(0,9));
		for($i=0;$i < $len; $i++){
			$val .= $valChars[array_rand($valChars)];
		}
		return $val;
	}

    function createREFNumber($type, $batchcode){
		return $type.substr($batchcode,0,3).date("y").$this->ref(date("ymdhisa")).$this->ref2(date("ymdhisa"));
	}

    function createUnique_REFNumber($type, $table, $field, $branch_batchcode){
		while (true){
            if($branch_batchcode){
                $batch = $branch_batchcode;
            } else {
                $batch = "SVR";
            }
			switch($type){
				case "RQ" :
				case "REQ" :
				case "PO" :
					$reference = $type.substr($batch,0,3).date("y").$this->ref3(date("ymdhisa"));
					break;
				default :
					$reference = $type.substr($batch,0,3).date("y").$this->ref(date("ymdhisa")).$this->ref2(date("ymdhisa"));
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

		// return $type.substr($_SESSION['branch_batchcode'],0,3).date("y").$this->ref(date("ymdhisa")).$this->ref2(date("ymdhisa"));
	}

    public function generate_ref($len=4){
        $val = "";
        $valChars = array_merge(range('A', 'Z'), range(0, 9));
        for($i=0;$i < $len; $i++){
            $val .= $valChars[array_rand($valChars)];
        }
        return $val;
    }

    function ordinal_suffix_of($num) {
		$j = $num % 10;
		$k = $num % 100;

		if ($j == 1 && $k != 11) {
			return "{$num}st";
		}

		if ($j == 2 && $k != 12) {
			return "{$num}nd";
		}

		if ($j == 3 && $k != 13) {
			return "{$num}rd";
		}
		return "{$num}th";
	}
}
