<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\GameBet;
use App\Model\subAccountBalance;

class subAccount extends Model
{
    protected $table = "subaccount";
    
    protected $fillable = ['subAccount_ID','subAccount_User', 'subAccount_Type', 'subAccount_Password', 'subAccount_Balance', 'subAccount_RegisterDay', 'subAccount_LastLogin', 'subAccount_Status'];

	public $timestamps = false;
	
// 	protected $primaryKey = 'subAccount_ID';
  
	public static function checkAccount($user){

	    $result = subAccount::where('subAccount_ID', $user)->first();
        return $result;
	}
	public static function getTotalSubaccount($userID){
		// $totalSub = [] ;
		$totalSub = subAccount::where('subAccount_User', $userID)->get();
		return $totalSub;
	}
    
    public static function insertSucAccount($arrayData){

	    $result = subAccount::insert($arrayData);
        return $result;
    }
    
    public static function getSucAccount($user){
	    
	    $data = subAccount::where('subAccount_User', $user)->where('subAccount_Status', '<>', -1)->select('subAccount_ID','subAccount_RegisterDay','subAccount_LastLogin','subAccount_SAaccount','subAccount_AGaccount','subAccount_SPaccount','subAccount_BOaccount')->get();

	    return $data;
    }
    
    public static function changeStatusSucAccount($user, $id, $status = 0){
	    $data = subAccount::where('subAccount_User', $user)->where('subAccount_ID', $id)->update(['subAccount_Status'=>$status]);

	    return $data;
    }
    
    public static function updateBalance($type_balance, $amount, $sub_id){
		$sub = subAccount::where('subAccount_ID', $sub_id)->first();
		$user_id = $sub->subAccount_User;
		// $update_user = DB::table('users')->where('user_ID', $user_id)->decrement('user_Balance', $amount);
		$data = subAccount::where('subAccount_ID', $sub_id)->increment($type_balance, $amount);
		return $data;
	}
	public static function addMoney($arr_money){
		$arrayInsert[] = array(
			'Money_User' => $checkUser->User_ID,
			'Money_USDT' => $amountInterest,
			'Money_USDTFee' => 0,
			'Money_Time' => time(),
			'Money_Comment' => $Comment,
			'Money_MoneyAction' => $action,
			'Money_MoneyStatus' => 1,
			'Money_Address' => null,
			'Money_Currency' => $currency,
			'Money_CurrentAmount' => $amountInterest,
			'Money_Rate' => 1,
			'Money_Confirm' => 0,
			'Money_Confirm_Time' => null,
			'Money_FromAPI' => 0,
		);
		if(count($arrayInsert)){
			Money::insert($arrayInsert);
		}
	}
    public static function depositBalance($amount, $sub){
		$sub = subAccount::where('subAccount_ID', $sub)->first();
		
		$balance = subAccountBalance::getBalance($sub);
	    $newAmount = $balance + $amount;
		$newDeposit = $sub->subAccount_Deposit + $amount;
		
		$data = subAccount::where('subAccount_ID', $sub->subAccount_ID)->update(['subAccount_Deposit'=>$newDeposit]);
		// $matchThese = ['sub'=>$sub->subAccount_ID,'balance'=>$newAmount, 'time'];
		// $data = DB::table('subAccountBalanceTemp')->updateOrCreate($matchThese,['shopOwner'=>'New One']);

	    return $data;
	}
	public static function getBalanceSub($sub, $type_balance){
		$balance = subAccount::where('subAccount_ID', $sub)->value($type_balance);
		return $balance;
	}
    
    public static function withdrawBalance($amount, $sub){
	    $sub = subAccount::where('subAccount_ID', $sub)->first();
	    $newAmount = $sub->subAccount_Balance - $amount;
		$newDeposit = $sub->subAccount_Withdraw + $amount;
		
	    $data = subAccount::where('subAccount_ID', $sub->subAccount_ID)->update(['subAccount_Balance'=> $newAmount, 'subAccount_Withdraw'=>$newDeposit]);

	    return $data;
    }
}
