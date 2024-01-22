<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Money;
use App\Model\User;
class logMoney extends Model
{
  protected $table = "logmoney";

  protected $fillable = ['logMoney_ID','logMoney_User', 'logMoney_SubAccount', 'logMoney_Balance', 'logMoney_OldBalance', 'logMoney_Action', 'logMoney_Log', 'logMoney_Datetime', 'logMoney_Status'];

  public $timestamps = false;

  protected $primaryKey = 'logMoney_ID';

  public function subAccount(){
    return $this->belongsTo('App\Model\subAccount', 'logMoney_SubAccount');
  }

  public static function insertLog($array){

    $result = logMoney::insert($array, $limit = 3);
    return $result;
  }

  public static function getLog($user, $start = 0, $limit = 25){
    $result = logMoney::join('action', 'logMoney_Action', 'action_ID')->Where('logMoney_User', $user)->offset($start)->limit($limit)->orderBy('logMoney_ID', 'DESC')->get();
    return $result;
  }
  public static function getBonusDeposit($userID, $amount){

    $action = 10;
    if($amount < 500){
      return false;
    }
    $current_date = time();
    $dayStart = strtotime('2021-06-10 00:00:00');
    $dayEnd = strtotime('2021-06-21 00:00:00');
    if($current_date < $dayStart || $current_date > $dayEnd){
      return false;
    }
    $checkBonus = Money::where('Money_User', $userID)->where('Money_Time', '>=', $dayStart)->where('Money_Time', '<', $dayEnd)->where('Money_MoneyAction', $action)->where('Money_MoneyStatus', 1)->first();
    //dd($checkBonus,$dayEnd,$dayStart,$amount,$userID);
    if($checkBonus){
      return false;
    }
    $percent = 0;
    if($amount >= 500 && $amount <= 5000){
      $percent = 0.15;
    }elseif($amount > 5000){
      $percent = 0.3;
    }
    $currency = 10;
    $amountBonus = $amount * $percent;
    $comment = 'Bonus Deposit '.$amountBonus.' EUSD (From '.$amount.' USDT)';
    $arrayInsert = array(
      'Money_User' => $userID,
      'Money_USDT' => $amountBonus,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => $amountBonus,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
    return true;
    /*
    return false;
    $action = 10;
    if($amount < 500){
       return false;
    }
    $checkBonus = Money::where('Money_User', $userID)->where('Money_MoneyAction', $action)->where('Money_MoneyStatus', 1)->first();
    if($checkBonus){
      	return false;
    }
    $percent = 0.2;
    $currency = 10;
    $amountBonus = $amount * $percent;
    $comment = 'Bonus Deposit '.$amountBonus.' EUSD (From '.$amount.' USDT)';
    $arrayInsert = array(
      'Money_User' => $userID,
      'Money_USDT' => $amountBonus,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => $amountBonus,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
    */
  }


  public static function getBonusDepositV3($userID, $amount, $currency){
    $user = User::find($userID);

    $action = 10;
    $minWithdraw = 50;
    if($amount < $minWithdraw){
      return false;
    }
    $currencyInfo = DB::table('currency')->where('Currency_ID', $currency)->first();
    if(!$currencyInfo){
      return false;
    }
    $dayNow = time();
    $dayStart = strtotime('2024-01-11 03:00:00');
    $dayEnd = strtotime('2025-12-20 00:00:00');

    if($dayNow < $dayStart || $dayNow > $dayEnd){
      return false;
    }

    $beforeDay = strtotime(date("Y-m-d 00:00:00"));
    $afterDay = $beforeDay + 86400;

    //Trong thời gian sự kiện, mỗi ngày sẽ được khuyến mãi 50% nạp lần đầu
    $checkBonus = Money::where('Money_User', $userID)
      ->where('Money_Time', '>=', $beforeDay)
      ->where('Money_Time', '<', $afterDay)
      ->where('Money_MoneyAction', $action)
      ->where('Money_MoneyStatus', 1)->first();
    if($checkBonus){
      return false;
    }

    $percent = 50/100;
    $currencyBonus = 10;
    $amountBonus = $amount * $percent;

    if($amountBonus > 150){
      $amountBonus = 150;
    }

    $comment = 'Bonus Deposit '.$amountBonus.' EUSD (From '.$amount.' EUSD ('.$percent*100 .'%))';
    $arrayInsert = array(
      'Money_User' => $userID,
      'Money_USDT' => $amountBonus,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currencyBonus,
      'Money_CurrencyFrom' => $currency,
      'Money_CurrentAmount' => $amountBonus,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
  }
  //chương trình nạp bonus
  public static function getBonusDepositBirthday($userID, $amount, $currency){
    logMoney::getBonusDepositV3($userID, $amount, $currency);
    return true;
  }
  //Khuyến mãi vé cược đầu tiên với banh
  public static function getBonusFirstBetSportBet($userID, $amount, $currency){
    $user = User::find($userID);
    if($user->User_Level != 1){
      //return false;
    }
    $action = 11;
    $minLose = 100;
    if($amount < $minLose){
      return false;
    }
    $currencyInfo = DB::table('currency')->where('Currency_ID', $currency)->first();
    if(!$currencyInfo){
      return false;
    }
    //dd($currency,$amount);
    $dayNow = time();
    $dayStart = strtotime('2022-12-03 00:00:00');
    $dayEnd = strtotime('2023-01-01 00:00:00');
    if($dayNow < $dayStart || $dayNow > $dayEnd){
      return false;
    }

    $checkFirst = DB::table('bet_history_sbobet')->where('user_id', $userID)
      ->where('created_at', '>=', date('Y-m-d H:i:s',$dayStart))
      ->where('created_at', '<', date('Y-m-d H:i:s',$dayEnd))
      ->where('status', '!=', 'running')
      ->orderBy('id','ASC')
      ->get();
    if(count($checkFirst) > 1){
      return false;
    }
    $checkBonus = Money::where('Money_User', $userID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->where('Money_MoneyAction', $action)
      ->where('Money_MoneyStatus', 1)
      ->first();
    if($checkBonus){
      return false;
    }
    $currencyBonus = 10;
    $amountBonus = $amount ;
    $comment = 'Refund your first bet with SportBook '.$amountBonus.' EUSD';
    $arrayInsert = array(
      'Money_User' => $userID,
      'Money_USDT' => $amountBonus,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currencyBonus,
      'Money_CurrentAmount' => $amountBonus,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
  }
  public static function getBonusDailyRecharge($userID, $amount, $currency){
    $user = User::find($userID);
    if($user->User_Level != 1){
      //return false;
    }
    $action = 12;
    $minWithdraw = 100;
    if($amount < $minWithdraw){
      return false;
    }
    $currencyInfo = DB::table('currency')->where('Currency_ID', $currency)->first();
    if(!$currencyInfo){
      return false;
    }
    //dd($currency,$amount);
    $dayNow = time();
    $dayStart = strtotime('2022-12-03 00:00:00');
    $dayEnd = strtotime('2023-01-01 00:00:00');
    if($dayNow < $dayStart || $dayNow > $dayEnd){
      return false;
    }

    $startToDay = date('Y-m-d 00:00:00',strtotime('today'));
    $endToDay = date('Y-m-d 23:59:59',strtotime('today'));

    //check trong ngày đã có nhận bonus lần nạp đầu rồi thì không nhận bonus lần nạp lại trong ngày
    $checkBonusFirst = Money::where('Money_User', $userID)
      ->where('Money_Time', '>=', strtotime($startToDay))
      ->where('Money_Time', '<', strtotime($endToDay))
      ->where('Money_MoneyAction', 10)
      ->where('Money_MoneyStatus', 1);
    $checkBonusFirst = $checkBonusFirst->first();
    if($checkBonusFirst){
      return false;
    }

    $checkBonusDailyRecharge = Money::where('Money_User', $userID)
      ->where('Money_Time', '>=', strtotime($startToDay))
      ->where('Money_Time', '<', strtotime($endToDay))
      ->where('Money_MoneyAction', $action)
      ->where('Money_MoneyStatus', 1);
    $checkBonusDailyRecharge = $checkBonusDailyRecharge->first();
    if($checkBonusDailyRecharge){
      return false;
    }

    $percent = 10/100;

    $currencyBonus = 10;
    $amountBonus = $amount * $percent;
    $comment = 'Bonus 10% deposit on daily '.$amountBonus.' EUSD (From '.$amount.' EUSD)';
    $arrayInsert = array(
      'Money_User' => $userID,
      'Money_USDT' => $amountBonus,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currencyBonus,
      'Money_CurrencyFrom' => $currency,
      'Money_CurrentAmount' => $amountBonus,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    $insert = Money::insert($arrayInsert);
  }
}
