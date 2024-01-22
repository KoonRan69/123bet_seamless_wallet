<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\System\CoinbaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

use Image;
use PragmaRX\Google2FA\Google2FA;

use DB;
use Mail;
use GuzzleHttp\Client;
use App\Model\Wallet;
use App\Model\GoogleAuth;
use App\Model\User;
use App\Model\userBalance;
use App\Model\Money;
use App\Model\subAccount;
use App\Model\GameBet;

class GameController extends Controller{

  public function postWithdrawBonusBirthday(Request $req){
    //    return $this->response(200, [], 'Please try again later!', [], false);
    $validator = Validator::make($req->all(), [
      'password' => 'required|min:6',
      //'amount' => 'required|numeric|min:1|max:999999',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters'),
      //'amount.required' => trans('notification.amount_required'),
      //'amount.min' => trans('notification.Amount_must_be_greater_than_0'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user_auth = Auth::user();
    $user = User::find($user_auth->User_ID);
    //$main_balance = User::getBalance($user_auth->User_ID, 5);
    // kiểm tra Subaccount có bị block ko

    if(!$user){
      return $this->response(200, [], trans('notification.user_does_not_exist'), [], false);
    }
    if($user->User_Block == 1){
      return $this->response(200, [], trans('notification.User_has_been_locked'), [], false);
    }
    if($user->User_Level == 4 || $user->User_Level == 5){
      return $this->response(200, [], trans('notification.permission_denied'), [], false);
    }


    if (!Hash::check($req->password, $user->User_Password)) {
      return $this->response(200, [], trans('notification.incorrec_password'), [], false);
    }

    if($user->User_Level != 1){
      //if(date('w') != "1"){
      //      return $this->response(200, [], "Error! This function is maintained", [], false);
      //}
    }

    $dayNow = time();
    $dayStart = strtotime('2024-01-11 03:00:00');
    $dayEnd = strtotime('2025-12-20 00:00:00');

    //dd($user->User_Level,date('Y-m-d H:i:s',$dayStart),date('Y-m-d H:i:s',$dayEnd));
    if($dayNow < $dayStart || $dayNow >= $dayEnd){
      return $this->response(200, [], trans('notification.Error_The_promotion_is_closed'), [], false);
    }

    $balanBonus = User::getBalance($user->User_ID, 10);

    $balanBonus = round($balanBonus, 2);
    if(!$balanBonus || $balanBonus == 0){
      return $this->response(200, [],trans('notification.withdraw_failed_bonus_wallet_is_not_enough'), [], false);
    }

    $beforeDay = strtotime(date("Y-m-d 00:00:00"));
    $afterDay = $beforeDay + 86400;

    //chỉ được rút 1 lần duy nhất (1 lần 100%) 
    $checkWithdrawBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $beforeDay)
      ->where('Money_Time', '<', $afterDay)
      ->where('Money_MoneyAction', 77)
      ->where('Money_MoneyStatus', 1)
      ->orderByDesc('Money_ID')
      ->first();
    if($checkWithdrawBonus){
      return $this->response(200, [], "Only 100% withdrawal is allowed", [], false);
    } 

    //Lấy ra lệnh nạp đầu tiên trong ngày (lệnh này được km 50%)
    $getDepositBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $beforeDay)
      ->where('Money_Time', '<', $afterDay)
      ->where('Money_MoneyAction', 1)
      ->where('Money_MoneyStatus', 1)
      ->first();
    if(!$getDepositBonus){
      return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
    }
    $totalDeposit = $getDepositBonus->Money_USDT*1; //Tính ra số tiền nạp lúc bonus
    if($totalDeposit > 300){
      $totalDeposit = 300;
    }

    $amountTotal = 0;
    $arrayActionPromotion = [10];

    //Lấy ra danh sách lệnh nạp bonus
    $getBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $beforeDay)
      ->where('Money_Time', '<', $afterDay)
      ->whereIn('Money_MoneyAction', $arrayActionPromotion)
      ->where('Money_MoneyStatus', 1)
      ->first();

    if(!$getBonus){
      return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
    }  


    $totalBonus = $getBonus->Money_USDT*1;

    //lấy ra số volume trade trong ngày
    $totalTradeBonus = GameBet::getShowTotalBet($user->User_ID, date('Y-m-d',$beforeDay), date('Y-m-d',$afterDay))['totalBet'];

    $amountCheckVolume = (float)($totalDeposit + $totalBonus);
    $depositX18 = (float)$amountCheckVolume;
    $depositX18 = $depositX18*18;
    if($depositX18 > $totalTradeBonus){
      return $this->response(200, [], "Volume must be 12 times the total deposit amount and bonus", [], false);
    }

    $withdrawBonus = $totalBonus*1;
    if(round($balanBonus) != round($withdrawBonus)){
      return $this->response(200, [], "Please contact support", [], false);
    }

    if($withdrawBonus <= 0){
      return $this->response(200, [], trans('notification.your_volume_trade_is_not_enough_to_withdraw'), [], false);
    }


    //payment
    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$withdrawBonus,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' USD (From Balance Bonus)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $withdrawBonus,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $withdrawBonus,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$withdrawBonus.' USD (To Main Balance)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $withdrawBonus,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    if($withdrawBonus > 0){
      if(count($insertArray)){
        Money::insert($insertArray);
        return $this->response(200, [], trans('notification.withdrawal_successful'), [], true);
      }
      return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
    }
    return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
  }

  public function postTestWithdrawBonusBirthday(Request $req){

    //    return $this->response(200, [], 'Please try again later!', [], false);
    $validator = Validator::make($req->all(), [
      'password' => 'required|min:6',
      //'amount' => 'required|numeric|min:1|max:999999',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters'),
      //'amount.required' => trans('notification.amount_required'),
      //'amount.min' => trans('notification.Amount_must_be_greater_than_0'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user_auth = Auth::user();
    $user = User::find($user_auth->User_ID);
    //$main_balance = User::getBalance($user_auth->User_ID, 5);
    // kiểm tra Subaccount có bị block ko

    if(!$user){
      return $this->response(200, [], trans('notification.user_does_not_exist'), [], false);
    }
    if($user->User_Block == 1){
      return $this->response(200, [], trans('notification.User_has_been_locked'), [], false);
    }
    if($user->User_Level == 4 || $user->User_Level == 5){
      return $this->response(200, [], trans('notification.permission_denied'), [], false);
    }
    if($user->User_Level != 1){
      //if(date('w') != "1"){
      //      return $this->response(200, [], "Error! This function is maintained", [], false);
      //}
    }
    if($user->User_ID == 541255){
      //return $this->response(200, [], "Error! Promotion expires!", [], false);
    }

    $balanBonus = User::getBalance($user->User_ID, 10);
    $balanBonus = round($balanBonus, 2);
    if(!$balanBonus || $balanBonus == 0){
      return $this->response(200, [],trans('notification.withdraw_failed_bonus_wallet_is_not_enough'), [], false);
    }
    $dayNow = time();
    $dayStart = strtotime('2023-03-15 00:00:00');
    $dayEnd = strtotime('2023-04-16 00:00:00');
    if($user->User_Level == 1){
      $dayStart = strtotime('2023-04-16 00:00:00');
      $dayEnd = strtotime('2023-05-28 00:00:00');
    }
    //dd($user->User_Level,date('Y-m-d H:i:s',$dayStart),date('Y-m-d H:i:s',$dayEnd));
    if($dayNow < $dayStart || $dayNow >= $dayEnd){
      return $this->response(200, [], trans('notification.Error_The_promotion_is_closed'), [], false);
    }


    if($user->User_Level != 1){
      //return $this->response(200, [], "Error! This function is maintained", [], false);
    }
    //chỉ được rút 1 lần duy nhất (cả 3 sự kiện bonus nếu user thực hiện rút rồi thì thôi/chỉ được 1 lần 100%) 
    $checkWithdrawBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->where('Money_MoneyAction', 77)
      ->where('Money_MoneyStatus', 1)
      ->orderByDesc('Money_ID')
      ->first();
    if($checkWithdrawBonus){
      return $this->response(200, [], "Only 100% withdrawal is allowed", [], false);
    } 

    $amountTotal = 0;
    $arrayPromotion = [10,13];//10,11,12
    //$amountCheckVolume = 0;
    foreach($arrayPromotion as $key=>$action){
      $amountCheckVolume = 0;
      $checkBonus = Money::where('Money_User', $user->User_ID)
        ->where('Money_Time', '>=', $dayStart)
        ->where('Money_Time', '<', $dayEnd)
        ->where('Money_MoneyAction', $action)
        ->where('Money_MoneyStatus', 1)
        ->orderByDesc('Money_ID')
        ->selectRaw('SUM(Money_USDT) as Money_USDT')->first();
      if($checkBonus->Money_USDT <= 0){
        continue;
      }   
      //lấy ra số volume trade hiện tại
      $totalTradeBonus = GameBet::getShowTotalBet($user->User_ID, $dayStart, $dayEnd)['totalBet'];
      //$totalTradeBonus = GameBet::getShowTotalBet($user->User_ID)['totalBet'];


      if($action == 10 || $action == 13){
        $amountBonus = $checkBonus->Money_USDT*1;
        if($action == 10){
          $amountCheckVolume = $amountCheckVolume + $checkBonus->Money_USDT*1/(15/100); //Tính ra số tiền nạp lúc bonus
          $apartFrom = $checkBonus->Money_USDT*1/(15/100);
        }
        if($action == 13){
          $amountCheckVolume = $amountCheckVolume + $checkBonus->Money_USDT*1/(10/100); //Tính ra số tiền nạp lúc bonus
          $apartFrom = $checkBonus->Money_USDT*1/(10/100);
        }
      }
      if($action == 12){
        $money = Money::where('Money_User', $user->User_ID)
          ->where('Money_Time', '>=', $dayStart)
          ->where('Money_Time', '<', $dayEnd)
          ->where('Money_MoneyAction', 12)
          ->where('Money_MoneyStatus', 1)
          ->orderByDesc('Money_ID')
          ->selectRaw('COALESCE(SUM(`Money_USDT`+`Money_USDTFee`), 0) as total')->first();
        $amountBonus = $money->total*1;
        $amountCheckVolume = $amountCheckVolume + $money->total*1/(15/100); //Tính ra số tiền nạp lúc bonus
        $apartFrom = $money->total*1/(15/100);
      }

      $checkdate = Money::where('Money_User', $user->User_ID)
        ->where('Money_Time', '>=', $dayStart)
        ->where('Money_Time', '<', $dayEnd)
        ->where('Money_MoneyAction', $action)
        ->where('Money_MoneyStatus', 1)
        ->orderByDesc('Money_ID')
        ->first();

      $dayBonus = $checkdate->Money_Time;
      $dayExpired = strtotime('+7 days', $dayBonus);
      $fromDate = date('Y-m-d 00:00:00', $dayBonus);
      $toDate = date('Y-m-d H:i:s');
      $totalDeposit = (float)$amountCheckVolume;
      $totalDeposit = $totalDeposit/0.1;



      if($action == 13){
        //dd($amount,$checkBonus,$totalTradeBonus,$totalDeposit);
      }
      if($totalTradeBonus < $totalDeposit){
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], trans('notification.your_volume_trade_is_not_enough_to_withdraw'), [], false);
      }

      if($dayNow > $dayExpired){
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], "Error! Promotion expires!", [], false);
      }

      $amount = $amountBonus;
      $maxWithdraw = $totalTradeBonus * 1 / 10;

      if (!Hash::check($req->password, $user->User_Password)) {
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], trans('notification.incorrec_password'), [], false);
      }
      if($amount < 0){
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
      }
      if($amount < 50){
        //continue;
        //return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
      }
      if($amount > $amountBonus){
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], trans('notification.withdraw_failed_bonus_wallet_is_not_enough'), [], false);
      }
      if($amount > $maxWithdraw){
        //return $this->response(200, [], trans('notification.Your_volume_trade_is_not_enough_to_withdraw'), [], false);
      }
      if($amount != $amountBonus){
        //$amountCheckVolume = $amountCheckVolume - $apartFrom;
        continue;
        return $this->response(200, [], 'Only withdraw bonus 1 times, 100% total bonus! Please withdraw '.($amountBonus).' EUSD', [], false);
      }
      //$amountTotal = $amountTotal + $amount; //số tiền bonus có thể rút về ví
      $amountTotal = $amount;

    }
    //payment
    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$amountTotal,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$amountTotal.' EUSD (From Balance Bonus)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $amountTotal,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $amountTotal,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw bonus deposit with '.$amountTotal.' EUSD (To Main Balance)',
        'Money_MoneyAction' => 77,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $amountTotal,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    if($amountTotal > 0){
      if(count($insertArray)){
        Money::insert($insertArray);
        return $this->response(200, [], trans('notification.withdrawal_successful'), [], true);
      }
      return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
    }
    return $this->response(200, [], "Not eligible for bonus withdrawal.", [], false);
  }
  public function postWithdrawBonus(Request $req){
    $user_auth = Auth::user();
    $user = User::find($user_auth->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], trans('notification.error_This_function_is_maintained'), [], false);
    }
    $validator = Validator::make($req->all(), [
      'password' => 'required|min:6|max:12',
      //'amount' => 'required|numeric|min:1|max:999999',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters'),
      //'amount.required' => trans('notification.amount_required'),
      //'amount.min' => trans('notification.Amount_must_be_greater_than_0'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }



    if(!$user){
      return $this->response(200, [], trans('notification.User_does_not_exist'), [], false);
    }
    if($user->User_Block == 1){
      return $this->response(200, [], trans('notification.User_has_been_locked'), [], false);
    }
    if($user->User_Level == 4 || $user->User_Level == 5){
      return $this->response(200, [], trans('notification.permission_denied'), [], false);
    }
    if($user->User_Level != 1){
      //if(date('w') != "1"){
      //return $this->response(200, [], trans('notification.error_This_function_is_maintained'), [], false);
      //}
    }
    $fromBonus = date('Y-m-d 00:00:00', strtotime('2022-07-20 00:00:00'));
    $endBonus = date('Y-m-d 00:00:00', strtotime('2022-09-06 23:59:59'));
    $toDate = date('Y-m-d H:i:s');
    if($toDate < $fromBonus || $toDate >= $endBonus){
      return $this->response(200, [], trans('notification.Error_The_promotion_is_closed'), [], false);
    }
    $totalTradeBonusArr = GameBet::getTotalTradeBonus($user->User_ID);

    $totalTradeBonus = $totalTradeBonusArr->totalBet ?? 0;

    $balanBonus = User::getBalance($user->User_ID, 10);
    //dd($balanBonus);

    $getAmountBonus = Money::where('Money_User', $user->User_ID)->where('Money_Time','>=', strtotime($fromBonus))->where('Money_MoneyAction', 10)->where('Money_Currency', 10)->where('Money_MoneyStatus', 1)->first();
    if(!$getAmountBonus){
      return $this->response(200, [],trans('notification.Amount_must_be_greater_than_0'), [], false);
    }
    $amount = $amountBonus = $getAmountBonus->Money_USDT;

    if($getAmountBonus->Money_CurrencyFrom*1 == 7){
      $percent = 20/100;
      $amountDeposit = $amountBonus/$percent;
    }
    if($getAmountBonus->Money_CurrencyFrom*1 == 5 || $getAmountBonus->Money_CurrencyFrom*1 == 6 || $getAmountBonus->Money_CurrencyFrom*1 == 11 || $getAmountBonus->Money_CurrencyFrom*1 == 13 || $getAmountBonus->Money_CurrencyFrom*1 == 14 || $getAmountBonus->Money_CurrencyFrom*1 == 15 || $getAmountBonus->Money_CurrencyFrom*1 == 16){
      $percent = 10/100;
      $amountDeposit = $amountBonus/$percent;
    }

    $arrWithdrawCurren = Money::where('Money_User', $user->User_ID)->where('Money_Time','>=', strtotime($fromBonus))->where('Money_MoneyAction', 77)->where('Money_MoneyStatus', 1)->where('Money_Currency', 10)->get();
    if(count($arrWithdrawCurren) >= 1){
      return $this->response(200, [], trans('notification.Amount_must_be_greater_than_0'), [], false);
    }
    if($totalTradeBonus < $amountDeposit*10){
      return $this->response(200, [], trans('notification.trader_can_withdraw_when_the_volume'), [], false);
    }

    if (Hash::check($req->password, $user->User_Password)) {
      if($amount < 0){
        return $this->response(200, [],trans('notification.Amount_must_be_greater_than_0'), [], false);
      }
      if($amount > $balanBonus){
        return $this->response(200, [], trans('notification.Your_volume_trade_is_not_enough_to_withdraw'), [], false);
      }
      $balanBonus = $balanBonus;
      if($amount > $balanBonus){
        return $this->response(200, [], trans('notification.Withdraw_failed_Bonus_wallet_is_not_enough'), [], false);
      }

      $insertArray = array(
        array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => -$amount,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Withdraw bonus deposit with '.$amount.' EUSD (From Balance Bonus)',
          'Money_MoneyAction' => 77,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => 10,
          'Money_CurrentAmount' => $amount,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1
        ),
        array(
          'Money_User' => $user->User_ID,
          'Money_USDT' => $amount,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Withdraw bonus deposit with '.$amount.' EUSD (To Main Balance)',
          'Money_MoneyAction' => 77,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => 3,
          'Money_CurrentAmount' => $amount,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 1
        ),
      );
      if(count($insertArray)){
        Money::insert($insertArray);
      }

      return $this->response(200, [], trans('notification.Withdrawal_successful'), [], true);

    }else{
      return $this->response(200, [],  trans('notification.Incorrect_password'), [], false);

    }
  }

  public function getListGame(Request $req){

    $gameList = [
      'all' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Agin SportBook',
          'game_name' => 'Agin SportBook',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Agin Fish Shooter',
          'game_name' => 'Agin Fish Shooter',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Agin Slot',
          'game_name' => 'Agin Slot',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        /*[
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Fish Shooter',
          'game_name' => 'Fish Shooter',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?fish',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Slot',
          'game_name' => 'Slot',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?slot',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Sportbook',
          'game_name' => 'Sportbook',
          'game_show' => 1,
          'game_play' => 'https://sportbook.123betnow.net/',
        ],*/
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Baccarat',
          'game_name' => 'Baccarat',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragontiger-wm.png',
          'game_display_name' => 'DRAGON TIGER',
          'game_name' => 'DRAGON TIGER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo-wm.png',
          'game_display_name' => 'SIC BO',
          'game_name' => 'SIC BO',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette-wm.png',
          'game_display_name' => 'ROULETTE',
          'game_name' => 'ROULETTE',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/multibet-wm.png',
          'game_display_name' => 'MULTI-BET',
          'game_name' => 'MULTI-BET',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        /*[
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/slotgame-wm.png',
          'game_display_name' => 'SLOTS GAME',
          'game_name' => 'SLOTS GAME',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish-wm.png',
          'game_display_name' => 'FISH SHOOTER',
          'game_name' => 'FISH SHOOTER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook-wm.png',
          'game_display_name' => 'SPORT BOOK',
          'game_name' => 'SPORT BOOK',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],*/
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sedie.png',
          'game_display_name' => 'Se Die',
          'game_name' => 'Se Die',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/niuniu.png',
          'game_display_name' => 'Niu Niu',
          'game_name' => 'Niu Niu',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/bahar.png',
          'game_display_name' => 'Andar Bahar',
          'game_name' => 'Andar Bahar',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/crab.png',
          'game_display_name' => 'Fish - Prawn - Crab',
          'game_name' => 'Fish - Prawn - Crab',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/golden.png',
          'game_display_name' => 'Golden Flower',
          'game_name' => 'Golden Flower',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fantan.png',
          'game_display_name' => 'Fantan',
          'game_name' => 'Fantan',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
      ],
      'wm555' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Baccarat',
          'game_name' => 'Baccarat',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragontiger-wm.png',
          'game_display_name' => 'DRAGON TIGER',
          'game_name' => 'DRAGON TIGER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo-wm.png',
          'game_display_name' => 'SIC BO',
          'game_name' => 'SIC BO',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette-wm.png',
          'game_display_name' => 'ROULETTE',
          'game_name' => 'ROULETTE',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/multibet-wm.png',
          'game_display_name' => 'MULTI-BET',
          'game_name' => 'MULTI-BET',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        /*[
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/slotgame-wm.png',
          'game_display_name' => 'SLOTS GAME',
          'game_name' => 'SLOTS GAME',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish-wm.png',
          'game_display_name' => 'FISH SHOOTER',
          'game_name' => 'FISH SHOOTER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook-wm.png',
          'game_display_name' => 'SPORT BOOK',
          'game_name' => 'SPORT BOOK',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],*/
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sedie.png',
          'game_display_name' => 'Se Die',
          'game_name' => 'Se Die',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/niuniu.png',
          'game_display_name' => 'Niu Niu',
          'game_name' => 'Niu Niu',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/bahar.png',
          'game_display_name' => 'Andar Bahar',
          'game_name' => 'Andar Bahar',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/crab.png',
          'game_display_name' => 'Fish - Prawn - Crab',
          'game_name' => 'Fish - Prawn - Crab',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/golden.png',
          'game_display_name' => 'Golden Flower',
          'game_name' => 'Golden Flower',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fantan.png',
          'game_display_name' => 'Fantan',
          'game_name' => 'Fantan',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
      ],
      /*'sa_game' => [
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat.png',
                    'game_display_name' => 'Baccarat',
                    'game_name' => 'Baccarat',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragon.png',
                    'game_display_name' => 'Dragon Tiger',
                    'game_name' => 'Dragon Tiger',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo.png',
                    'game_display_name' => 'Sic Bo',
                    'game_name' => 'Sic Bo',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette.png',
                    'game_display_name' => 'Roulette',
                    'game_name' => 'Roulette',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/bet.png',
                    'game_display_name' => 'Muilti-Bet',
                    'game_name' => 'Muilti-Bet',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
            ],
      'sky_game' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Fish Shooter',
          'game_name' => 'Fish Shooter',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?fish',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Slot',
          'game_name' => 'Slot',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?slot',
        ],
      ],*/
      'agin' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Agin SportBook',
          'game_name' => 'Agin SportBook',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Agin Fish Shooter',
          'game_name' => 'Agin Fish Shooter',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Agin Slot',
          'game_name' => 'Agin Slot',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
      ],
    ];
    $category = [
      ['name'=>'All', 'key'=>'all'],
      //['name'=>'Wm555', 'key'=>'wm555'],
      ['name'=>'Agin', 'key'=>'agin'],

      /*['name'=>'SA Game', 'key'=>'sa_game'],
      ['name'=>'Sky Game', 'key'=>'sky_game'],
      ['name'=>'Sportbook', 'key'=>'sportbook'],*/
    ];//dd(123);
    $user_auth = Auth::user();
    if($user_auth){

      $user = User::find($user_auth->User_ID);
      //dd($user->User_Level);
      if($user->User_Level == 1){

        $gameList['evolution'] = [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Casino Evolution',
          'game_name' => 'Casino',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>0,
        ];
        $category = [
          ['name'=>'All', 'key'=>'all'],
          ['name'=>'Wm555', 'key'=>'wm555'],
          ['name'=>'Agin', 'key'=>'agin'],
          ['name'=>'Evolution', 'key'=>'Evo'],

        ];
      }
    }
    return $this->response(200, [ 'category'=> $category,'game'=>$gameList], '', [], true);
  }

  public function getAgencyIB(){
    //$withdrawBonus = false;
    $user = Auth::user();

    $PackageAgency = GameBet::getPackageAgency();
    //    $totalBuyAgency = GameBet::totalBuyAgency($user->User_ID);
    //    $agencyUser = GameBet::getPackageUser($totalBuyAgency, $PackageAgency);
    $PackageBO = GameBet::getPackage();
    $lastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    $fromDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $fromBonus = date('Y-m-d 00:00:00', strtotime('2023-07-01 00:00:00'));
    $dayStart = strtotime('2023-07-01 00:00:00');
    $dayEnd = strtotime('2023-08-01 00:00:00');

    $agencyUser = GameBet::getPackageUserAvailable($user->User_ID, $fromDate, date('Y-m-d H:i:s'), 0);
    //    dd($agencyUser);
    $balanBonus = User::getBalance($user->User_ID, 10);

    //$balanBonus = $checkBonus->Money_USDT;
    //$dayNow = time();
    //$dayStart = strtotime('2021-08-10 00:00:00');
    //$dayEnd = strtotime('2021-10-01 00:00:00');

    $checkBonus = Money::where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->where('Money_MoneyAction', 10)
      ->where('Money_MoneyStatus', 1)
      ->orderByDesc('Money_ID')
      ->having('Money_USDT', '>=', 10)
      ->first();
    $balanBonusNew = $checkBonus->Money_USDT ?? 0;
    $totalDeposit = $balanBonusNew*10;


    //$totalTrade = DB::table('statistical_123betnow')->where('statistical_User', $user->User_ID)->sum('statistical_TotalBet');
    //$fromDate = date('Y-m-d 00:00:00', strtotime('monday last week'));
    // thứ 2 tuần này
    $toDate = date('Y-m-d H:i:s');
    //get package current
    //Game BO
    //$getIDPackage = GameBet::NumberPackage($user->User_ID, $fromDate, $toDate, 0);
    //$infoPackage = $PackageBO[$getIDPackage];
    $infoPackage = $agencyUser;

    $pendingThisWeek = GameBet::getProfitPending($user, $fromDate, $toDate);
    //      	$checkReceiveLastWeek = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', 58)->where('Money_Time', '>=', strtotime($fromDate))->first();
    //      	if($checkReceiveLastWeek){
    //           	$pendingLastWeek = 0;
    //        }else{
    //            $pendingLastWeek = GameBet::getProfitPending($user, $lastWeek, $fromDate);
    //        }
    $pendingLastWeek = Money::where('Money_User', $user->User_ID)->where('Money_Time', '>=', strtotime($fromDate))->where('Money_MoneyAction', 64)->sum('Money_USDT');
    $pendingLastWeek = number_format($pendingLastWeek,2);
    $listPackage = [];
    foreach($PackageAgency as $k=>$p){
      if($agencyUser['id'] >= $k){
        $p['can_buy'] = 0;
      }else{
        $p['can_buy'] = 1;
      }
      $listPackage[] = $p;
    }
    $arrIB['Game'] = [ 'AllPackage'=>$listPackage, 'BuyShow'=>1, 'PackageName'=>$infoPackage['name'], 'PackageImage'=>$infoPackage['image'] ];
    //static game
    $totalTrade = GameBet::getShowTotalBet($user->User_ID, $fromDate, $toDate)['totalBet']; //Volume trong tuần của người chơi

    $beforeDay = strtotime(date("Y-m-d 00:00:00"));
    $afterDay = $beforeDay + 86400;
    $amountTotalBonus = GameBet::getShowTotalBet($user->User_ID, date('Y-m-d',$beforeDay), date('Y-m-d',$afterDay))['totalBet'];

    $beforeLastDay = strtotime(date("Y-m-d 00:00:00",strtotime("last day")));
    $afterLastDay = $beforeLastDay + 86400;
    $amountTotalBonusLastDay = GameBet::getShowTotalBet($user->User_ID, date('Y-m-d',$beforeLastDay), date('Y-m-d',$afterLastDay))['totalBet'];

    //$withdrawCurren = Money::where('Money_User', $user->User_ID)->where('Money_MoneyAction', 77)->where('Money_MoneyStatus', 1)->where('Money_Currency', 10)->sum('Money_USDT');
    $withdrawBonus = true;
    if($user->User_Level == 1){
      //$withdrawBonus = true;
    }else{
      //$withdrawBonus = false;
    }
    $withdrawCurren =  Money::where('Money_User', $user->User_ID)
      ->where('Money_MoneyAction', 77)
      ->where('Money_MoneyStatus', 1)
      ->where('Money_Currency', 10)
      ->where('Money_Time', '>=', $dayStart)
      ->where('Money_Time', '<', $dayEnd)
      ->sum('Money_USDT');

    $maxWithdraw = ($amountTotalBonus * 0.15 / 15);
    if($maxWithdraw > $balanBonus){
      $maxWithdraw = $balanBonus;
    }

    $F1Active = GameBet::getF1Active($user->User_ID, $fromDate, $toDate);
    $VolumeTrade = GameBet::getShowTotalBetSystem($user->User_ID, $fromDate, $toDate)['totalBet']; //Tổng volume trong tuần này của hệ thống
    $staticTrade = GameBet::getTradeInfo($user, $fromDate, $toDate);

    $arrIB['Game']['Static'] = [
      'TotalF1Active'=>$F1Active->count()." Member", //Tổng số lượng F1 

      'TotalTrade' => number_format(GameBet::getShowTotalBet($user->User_ID)['totalBet'], 2)." EUSD" , //Tổng Volume của người chơi
      'TotalTradeThisWeek' => number_format($totalTrade, 2)." EUSD" , //Volume trong tuần của người chơi

      'VolumeTrade'=>number_format(GameBet::getShowTotalBetSystem($user->User_ID)['totalBet'])." EUSD",  //Tổng volume của hệ thống
      'VolumeTradeThisWeek'=>number_format($VolumeTrade)." EUSD", //Volume trong tuần này của hệ thống 

      'PendingLastWeek'=>$pendingLastWeek." EUSD", 
      'PendingThisWeek'=>$pendingThisWeek." EUSD", 

      'Branch_Trade'=>$staticTrade['branch_trade'], 

      'balanBonus'=>number_format($balanBonus,4)." EUSD",

      'totalTradeBonusLastDay'=>number_format($amountTotalBonusLastDay, 2)." EUSD", //Tổng volume ngày hôm trước của người chơi
      'totalTradeBonus'=>number_format($amountTotalBonus, 2)." EUSD", //Tổng volume ngày hôm nay của người chơi

      'totalSystemTradeBonusLastDay'=>number_format(GameBet::getShowTotalBetSystem($user->User_ID, $beforeLastDay, $afterLastDay)['totalBet'], 2)." EUSD", //Tổng volume hệ thống ngày hôm trước của người chơi
      'totalSystemTradeBonus'=>number_format(GameBet::getShowTotalBetSystem($user->User_ID, date('Y-m-d 00:00:00'), date('Y-m-d H:i:s'))['totalBet'], 2)." EUSD", //Tổng volume hệ thống ngày hôm nay của người chơi

      'balanceBonusLastDay'=>number_format(GameBet::StaticBalanceBonus($user->User_ID,$beforeLastDay,$afterLastDay),2)." EUSD", //Bonus ngày hôm qua
      'balanceBonusToDay'=>number_format(GameBet::StaticBalanceBonus($user->User_ID,$beforeDay,$afterDay),2)." EUSD", //Bonus ngày hôm nay

      'balanceBonusLastWeek'=>number_format(GameBet::StaticBalanceBonus($user->User_ID,$lastWeek,$fromDate),2)." EUSD", //Bonus tuần trước
      'balanceBonusThisWeek'=>number_format(GameBet::StaticBalanceBonus($user->User_ID,$fromDate,$toDate),2)." EUSD", //Bonus tuần này

      "withdrawBonus"=>$withdrawBonus, 
      'maxWithdtawBonus'=>number_format($maxWithdraw,2)." EUSD", 
      'totalDeposit'=>number_format($totalDeposit,2)." EUSD",
      "timenow"=>date("y-m-d H:i:s")
    ];
    return $this->response(200, $arrIB, '', [], true);
  }
  public function postBuyAgency(Request $req){
    //return $this->response(200, [], 'Coming Soon!', [], false);
    $user = Auth::user();

    $validator = Validator::make($req->all(), [
      'id' => 'required|numeric|in:1,2,3,4,5,6,7',
    ],[
      'id.required' => trans('notification.id_required') ,
      'id.in' => trans('notification.ID_must_be_1_2_3_4_5_6_7') ,
    ]);
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    /*
		$captcha = app('App\Http\Controllers\API\WalletController')->checkCaptcha($req->token);
		if(!$captcha){
			return $this->response(200, [], 'Captcha isn\'t exist!', [], false);
		}
        $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $req->CodeSpam)->first();
        if ($checkSpam == null) {
            //khoong toonf taij
            return $this->response(200, [], 'Misconduct!', [], false);
        }else{
            DB::table('string_token')->where('User', $user->User_ID)->delete();
        }*/
    $id = $req->id;
    $getPackageAgency = GameBet::getPackageAgency();
    if(!isset($getPackageAgency[$id])){
      return $this->response(200, [], trans('notification.package_error'), [], false);
    }
    $infoPackage = $getPackageAgency[$id];
    $totalBuyAgency = GameBet::totalBuyAgency($user->User_ID);
    //price to buy Agency
    $amount = $infoPackage['price'] - $totalBuyAgency;
    if($amount <= 0){
      return $this->response(200, [], trans('notification.you_bought_this_package'), [], false);
    }
    $arrCurrency = DB::table('currency')->whereIn('Currency_ID', [3,9])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    $currency = 3;
    $balance = User::getBalance($user->User_ID, $currency);
    if($amount > $balance){
      return $this->response(200, ['balance'=>$balance], trans('notification.Your_balance_is_not_enough'), [], false);
    }
    // trừ tiền người nạp
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -(float)($amount*1),
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Buy Package Agency '.$infoPackage['name'].' $'.$amount,
      'Money_MoneyAction' => 68,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => (float)($amount*1),
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    $insert = Money::insert($arrayInsert);
    if($insert){
      Money::checkCommissionAgency($user, $infoPackage, $amount);
    }
    $balanceEUSD = User::getBalance($user->User_ID, 3);
    $balanceEBP = User::getBalance($user->User_ID, 8);
    $json['Balance']['EUSD'] = $balanceEUSD;
    $json['Balance']['EBP'] = $balanceEBP;
    return $this->response(200, $json, trans('notification.Buy_Agency_Success'), [], true);
  }

  public function postBuyAgencyOld(Request $req){
    return $this->response(200, [], trans('notification.coming_soon'), [], false);
    $user = Auth::user();
    /*
		$captcha = app('App\Http\Controllers\API\WalletController')->checkCaptcha($req->token);
		if(!$captcha){
			return $this->response(200, [], 'Captcha isn\'t exist!', [], false);
		}
        $checkSpam = DB::table('string_token')->where('User', $user->User_ID)->where('Token', $req->CodeSpam)->first();
        if ($checkSpam == null) {
            //khoong toonf taij
            return $this->response(200, [], 'Misconduct!', [], false);
        }else{
            DB::table('string_token')->where('User', $user->User_ID)->delete();
        }*/
    $checkBuyAgency = GameBet::checkBuyAgency($user->User_ID);
    if($checkBuyAgency){
      return $this->response(200, [], 'You Bought Package Agency!', [], false);
    }
    //price to buy Agency
    $amount = 100;
    $arrCurrency = DB::table('currency')->whereIn('Currency_ID', [3,9])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    $currency = 3;
    $balance = User::getBalance($user->User_ID, $currency);
    if($amount > $balance){
      return $this->response(200, ['balance'=>$balance], 'Your balance is not enough', [], false);
    }
    $json['status'] = 'OK';
    if($json['status'] == 'OK'){
      // trừ tiền người nạp
      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -(float)($amount*1),
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Buy Package Agency 123BetNow $'.$amount,
        'Money_MoneyAction' => 63,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => (float)($amount*1),
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1,
      );
      $insert = Money::insert($arrayInsert);
      if($insert){
        //Money::checkCommissionBOAgency($user, $amount, $currency);
      }
      $balanceEUSD = User::getBalance($user->User_ID, 3);
      $json['Balance']['EUSD'] = $balanceEUSD;
      return $this->response(200, $json, 'Buy Agency Success!', [], true);
    }else{
      return $this->response(200, $json, 'Failed Please Try Again!', [], false);
    }
  }

  public function withdrawGame(Request $req){

    $validator = Validator::make($req->all(), [
      'password' => 'required|min:6|max:12',
      'amount' => 'required|numeric|min:1|max:999999',
      'typeWithdraw' => 'required',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters'),
      'amount.required' => trans('notification.amount_required'),
      'amount.min' => trans('notification.Amount_must_be_greater_than_0'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $user_auth = Auth::user();
    $user = User::find($user_auth->User_ID);
    //$main_balance = User::getBalance($user_auth->User_ID, 5);
    // kiểm tra Subaccount có bị block ko


    if(!$user){
      return $this->response(200, [], trans('notification.User_does_not_exist'), [], false);
    }

    if($user->User_Status != 1){
      return $this->response(200, [], "", trans('notification.User_has_been_locked'), false);
    }
    $amount = abs($req->amount);
    if (Hash::check($req->password, $user->User_Password)) {
      $system = null;
      if($req->typeWithdraw == 1){
        $system = 'Casino Game';
        $arr_withdrawSA = array(
          'user_ID'=> $user_auth->User_ID,
          'username' => $user_auth->User_ID,
          'amount' => $amount,
        );
        if($user->User_Casino == 0){
          return $this->response(200, [], "", trans('notification.Casino_withdraw_failed'), false);
        }


        $balanceCasiino = $CasinoBalance = (array)app('App\Http\Controllers\API\SAGameController')->checkBalance($user->User_ID);
        $balanceCasiino = $balanceCasiino[0]*1;
        if($amount > $balanceCasiino){
          return $this->response(200, [], "", trans('notification.Casino_game_withdraw_failed'), false);
        }



        $sagame = app('App\Http\Controllers\API\SAGameController')->withdrawSA($arr_withdrawSA);

        if($sagame === false){
          return $this->response(200, [], "", trans('notification.Casino_withdraw_failed'), false);
        }
        $orderid = $sagame;
        $CasinoBalance = (array)app('App\Http\Controllers\API\SAGameController')->checkBalance($user->User_ID);
        $newBalance = $CasinoBalance[0]*1;

      }elseif($req->typeWithdraw == 2){
        $system = 'Sportbook';
        $arr_withdrawBCSport = array(
          'user_ID'=> $user_auth->User_ID,
          'username' => $user_auth->User_ID,
          'amount' => $amount,
        );
        if($user->User_SportBook == 0){
          return $this->response(200, [], "", trans('notification.Sportbook_withdraw_failed'), false);
        }

        $bcsport = app('App\Http\Controllers\API\BCSportController')->withdrawSP($arr_withdrawBCSport);

        if($bcsport === false){
          return $this->response(200, [], "", trans('notification.Sportbook_withdraw_failed'), false);
        }

        $orderid = $bcsport;
        $SportBookBalance = app('App\Http\Controllers\API\BCSportController')->checkBalance($user->User_ID);
        $newBalance = $SportBookBalance*1;

      }elseif($req->typeWithdraw == 3){
        $system = 'Binary Option';
        $arr_withdrawBo = array(
          'user_ID'=> $user_auth->User_ID,
          'username' => $user_auth->User_ID,
          'amount' => $amount,
        );
        if($user->User_BinanryOption == 0){
          return $this->response(200, [], "", trans('notification.Bo_withdraw_failed'), false);
        }

        $bo = app('App\Http\Controllers\API\BoController')->withdrawBO($arr_withdrawBo);

        if($bo === false){
          return $this->response(200, [], "", trans('notification.Bo_withdraw_failed'), false);
        }

        $orderid = $bo;
        $BoBalance = app('App\Http\Controllers\API\BoController')->checkBalance($user->User_ID);
        $newBalance = $BoBalance*1;

      }elseif($req->typeWithdraw == 4){
        $system = 'Sky Game';
        $arr_withdrawSky = array(
          'username' => $user_auth->User_ID,
          'amount' => $amount,
        );
        if($user->User_SkyGame == 0){
          return $this->response(200, [], "", trans('notification.Sky_Game_withdraw_failed'), false);
        }

        $sky = app('App\Http\Controllers\API\SkyGameController')->withdrawSkyGame($arr_withdrawSky);

        if($sky === false){
          return $this->response(200, [], "", trans('notification.Sky_Game_withdraw_failed'), false);
        }

        $orderid = $sky;
        $skyBalance = app('App\Http\Controllers\API\SkyGameController')->checkBalance($user->User_ID);
        $newBalance = $skyBalance*1;

      }


      $arrayInsert[] = array(
        'Money_User' => $user_auth->User_ID,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => "Withdraw from system ".$system." with $".$amount,
        'Money_MoneyAction' => 4,
        'Money_Game' => $req->typeWithdraw,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      if(count($arrayInsert)){
        Money::insert($arrayInsert);
      }
      return $this->response(200, ['system'=>$system, 'balance'=>array('newBalance'=>$newBalance, 'main'=>User::getBalance($user_auth->User_ID, 5)), 'orderID'=>$orderid], "Withdraw from system ".$system." with : $".$amount, "", true);
    }else{
      return $this->response(200, [], "", trans('notification.Incorrect_password'), false);

    }
  }

  public function depositGame(Request $req){

    $validator = Validator::make($req->all(), [
      'password' => 'required|min:6|max:12',
      'amount' => 'required|numeric|min:1|max:999999',
      'typeDeposit' => 'required',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min:6' => trans('notification.password_minimum_6_characters'),
      'password.max:12' => trans('notification.password_up_to_12_characters '),
      'amount.required' => trans('notification.amount_required '),
      'amount.min:1' => trans('notification.Amount_must_be_greater_than_0 '),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $user_auth = Auth::user();

    $user = User::find($user_auth->User_ID);
    $main_balance = User::getBalance($user_auth->User_ID, 3);

    if(!$user){
      return $this->response(200, [], trans('notification.User_does_not_exist'), [], false);
    }

    if($user->User_Status != 1){
      return $this->response(200, [], "", trans('notification.User_has_been_locked'), false);
    }


    if($req->amount > $main_balance){
      return $this->response(200, [], "", trans('notification.Account_balance_is_not_enough'), false);

    }

    $amount = abs($req->amount);


    $type_deposit = [
      1 => "User_Casino",
      2 => "User_SportBook",
      3 => "User_BinanryOption"
    ];
    if (Hash::check($req->password, $user->User_Password)) {
      $system = null;
      $orderid;
      if($req->typeDeposit == 1){
        $system = 'Casino Game';
        if($user->User_Casino == 0){
          $sagame_register = app('App\Http\Controllers\API\SAGameController')->register($user->User_ID);
          if($sagame_register){
            User::where('User_ID', $user_auth->User_ID)->update(['User_Casino'=>1]);
          }

        }
        $arr_depositsa = array(
          'user_ID'=> Auth::user()->User_ID,
          'username' => $user->User_ID,
          'amount' => $amount,

        );

        // $result = $this->postDebit($arr_depositsa);
        $sagame = app('App\Http\Controllers\API\SAGameController')->depositSA($arr_depositsa);

        if($sagame === false){
          return $this->response(200, [], "", "Casino deposit failed", false);
        }
        $orderid = $sagame;
        $CasinoBalance = (array)app('App\Http\Controllers\API\SAGameController')->checkBalance($user->User_ID);
        $newBalance = $CasinoBalance[0]*1;
      }elseif($req->typeDeposit == 2){
        $system = 'Sportbook';

        if($user->User_SportBook == 0){

          $bcsport_register = app('App\Http\Controllers\API\BCSportController')->postRegister($user->User_ID);
          if($bcsport_register){
            User::where('User_ID', $user_auth->User_ID)->update(['User_SportBook'=>1]);
          }

        }
        $arr_depositBCsport = array(
          'user_ID'=> Auth::user()->User_ID,
          'username' => $user->User_ID,
          'amount' => $amount,

        );

        // $result = $this->postDebit($arr_depositsa);
        $bcsport = app('App\Http\Controllers\API\BCSportController')->depositSP($arr_depositBCsport);

        if($bcsport === false){
          return $this->response(200, [], "", "Sportbook deposit failed", false);
        }
        $orderid = $bcsport;
        $SportBookBalance = app('App\Http\Controllers\API\BCSportController')->checkBalance($user->User_ID);
        $newBalance = $SportBookBalance*1;
      }elseif($req->typeDeposit == 3){
        $system = 'Binary Option';


        $arr_depositBo = array(
          'user_ID'=> Auth::user()->User_ID,
          'username' => $user->User_ID,
          'amount' => $amount,

        );

        if($user->User_BinanryOption  == 0){

          $BO_register = app('App\Http\Controllers\API\BoController')->postRegister($user->User_ID);


        }

        // $result = $this->postDebit($arr_depositsa);
        $bo = app('App\Http\Controllers\API\BoController')->depositBO($arr_depositBo);

        if($bo === false){
          return $this->response(200, [], "", "bet sport deposit failed", false);
        }
        $orderid = $bo;
        $boBalance = app('App\Http\Controllers\API\BoController')->checkBalance($user->User_ID);
        $newBalance = $boBalance*1;
      }elseif($req->typeDeposit == 4){
        $system = 'Sky Game';

        $arr_depositSky = array(
          'username' => $user->User_ID,
          'amount' => $amount,

        );

        if($user->User_SkyGame  == 0){
          $arr_register = array(
            'username'=>$user->User_ID
          );
          $sky_register = app('App\Http\Controllers\API\SkyGameController')->registerGame($arr_register);
          if($sky_register){
            User::where('User_ID', $user->User_ID)->update(['User_SkyGame'=>1]);
          }
        }

        // $result = $this->postDebit($arr_depositsa);
        $sky = app('App\Http\Controllers\API\SkyGameController')->depositGame($arr_depositSky);

        if($sky === false){
          return $this->response(200, [], "", "Sky game deposit failed", false);
        }
        $orderid = $sky;
        $skyBalance = app('App\Http\Controllers\API\SkyGameController')->checkBalance($user->User_ID);

        $newBalance = $skyBalance*1;
      }


      $arrayInsert[] = array(
        'Money_User' => $user_auth->User_ID,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => "Deposit to system ".$system." with $".$amount,
        'Money_MoneyAction' => 3,
        'Money_Game' => $req->typeWithdraw,
        'Money_MoneyStatus' => 1,
        'Money_TXID' => $orderid,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $amount,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );

      if(count($arrayInsert)){
        Money::insert($arrayInsert);
      }

      return $this->response(200, ['system'=>$system, 'balance'=>array('newBalance'=>$newBalance, 'main'=>User::getBalance($user_auth->User_ID, 5)), 'orderID'=>$orderid], "Deposit to system ".$system." with : $".$amount, "", true);
    }else{
      return $this->response(200, [], "", trans('notification.Incorrect_password'), false);

    }
  }





  public function getListGameTest(Request $req){
    //$user_auth = Auth::user();
    //$user = User::find($user_auth->User_ID);
    //    if($user->User_Level == 1){
    $gameList = [
      'all' => [
        [
          'game_image_url'=>'https://media.123betnow.net/list/game/image_63623f10e4f50.png',
          'game_display_name' => 'Sbobet',
          'game_name' => 'Sbobet',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/emato/product/62c518a14a359.png',
          'game_display_name' => 'AE sexy',
          'game_name' => 'AE sexy',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Casino Evolution',
          'game_name' => 'Casino Evolution',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Agin SportBook',
          'game_name' => 'Agin SportBook',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Agin Fish Shooter',
          'game_name' => 'Agin Fish Shooter',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Agin Slot',
          'game_name' => 'Agin Slot',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        /*[
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Fish Shooter',
          'game_name' => 'Fish Shooter',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?fish',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Slot',
          'game_name' => 'Slot',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?slot',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Sportbook',
          'game_name' => 'Sportbook',
          'game_show' => 1,
          'game_play' => 'https://sportbook.123betnow.net/',
        ],*/
        /*[
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Baccarat',
          'game_name' => 'Baccarat',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragontiger-wm.png',
          'game_display_name' => 'DRAGON TIGER',
          'game_name' => 'DRAGON TIGER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo-wm.png',
          'game_display_name' => 'SIC BO',
          'game_name' => 'SIC BO',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette-wm.png',
          'game_display_name' => 'ROULETTE',
          'game_name' => 'ROULETTE',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/multibet-wm.png',
          'game_display_name' => 'MULTI-BET',
          'game_name' => 'MULTI-BET',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/slotgame-wm.png',
          'game_display_name' => 'SLOTS GAME',
          'game_name' => 'SLOTS GAME',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish-wm.png',
          'game_display_name' => 'FISH SHOOTER',
          'game_name' => 'FISH SHOOTER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook-wm.png',
          'game_display_name' => 'SPORT BOOK',
          'game_name' => 'SPORT BOOK',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sedie.png',
          'game_display_name' => 'Se Die',
          'game_name' => 'Se Die',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/niuniu.png',
          'game_display_name' => 'Niu Niu',
          'game_name' => 'Niu Niu',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/bahar.png',
          'game_display_name' => 'Andar Bahar',
          'game_name' => 'Andar Bahar',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/crab.png',
          'game_display_name' => 'Fish - Prawn - Crab',
          'game_name' => 'Fish - Prawn - Crab',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/golden.png',
          'game_display_name' => 'Golden Flower',
          'game_name' => 'Golden Flower',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fantan.png',
          'game_display_name' => 'Fantan',
          'game_name' => 'Fantan',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],*/
      ],
      /*'wm555' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Baccarat',
          'game_name' => 'Baccarat',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragontiger-wm.png',
          'game_display_name' => 'DRAGON TIGER',
          'game_name' => 'DRAGON TIGER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo-wm.png',
          'game_display_name' => 'SIC BO',
          'game_name' => 'SIC BO',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette-wm.png',
          'game_display_name' => 'ROULETTE',
          'game_name' => 'ROULETTE',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/multibet-wm.png',
          'game_display_name' => 'MULTI-BET',
          'game_name' => 'MULTI-BET',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/slotgame-wm.png',
          'game_display_name' => 'SLOTS GAME',
          'game_name' => 'SLOTS GAME',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish-wm.png',
          'game_display_name' => 'FISH SHOOTER',
          'game_name' => 'FISH SHOOTER',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook-wm.png',
          'game_display_name' => 'SPORT BOOK',
          'game_name' => 'SPORT BOOK',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sedie.png',
          'game_display_name' => 'Se Die',
          'game_name' => 'Se Die',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/niuniu.png',
          'game_display_name' => 'Niu Niu',
          'game_name' => 'Niu Niu',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/bahar.png',
          'game_display_name' => 'Andar Bahar',
          'game_name' => 'Andar Bahar',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/crab.png',
          'game_display_name' => 'Fish - Prawn - Crab',
          'game_name' => 'Fish - Prawn - Crab',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/golden.png',
          'game_display_name' => 'Golden Flower',
          'game_name' => 'Golden Flower',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fantan.png',
          'game_display_name' => 'Fantan',
          'game_name' => 'Fantan',
          'game_show' => 1,
          'game_play' => 'https://wm111.net/',
          'check_agin'=>0,
        ],
      ],*/
      /*'sa_game' => [
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat.png',
                    'game_display_name' => 'Baccarat',
                    'game_name' => 'Baccarat',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/dragon.png',
                    'game_display_name' => 'Dragon Tiger',
                    'game_name' => 'Dragon Tiger',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/sicbo.png',
                    'game_display_name' => 'Sic Bo',
                    'game_name' => 'Sic Bo',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/roulette.png',
                    'game_display_name' => 'Roulette',
                    'game_name' => 'Roulette',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
              	[
                    'game_image_url'=>'https://media.eggsbook.com/ecosystem/bet.png',
                    'game_display_name' => 'Muilti-Bet',
                    'game_name' => 'Muilti-Bet',
                    'game_show' => 1,
                    'game_play' => 'https://casino.123betnow.net/',
                ],
            ],
      'sky_game' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Fish Shooter',
          'game_name' => 'Fish Shooter',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?fish',
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Slot',
          'game_name' => 'Slot',
          'game_show' => 1,
          'game_play' => 'https://fish.123betnow.net/?slot',
        ],
      ],*/
      'agin' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/sportbook.png',
          'game_display_name' => 'Agin SportBook',
          'game_name' => 'Agin SportBook',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/fish.png',
          'game_display_name' => 'Agin Fish Shooter',
          'game_name' => 'Agin Fish Shooter',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/gameslot.png',
          'game_display_name' => 'Agin Slot',
          'game_name' => 'Agin Slot',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
      ],
      'Evolution' => [
        [
          'game_image_url'=>'https://media.eggsbook.com/ecosystem/baccarat-wm.png',
          'game_display_name' => 'Casino Evolution',
          'game_name' => 'Casino Evolution',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
      ],

      'Aesexy' => [
        [
          'game_image_url'=>'https://images-storage-bucket.s3.ap-southeast-1.amazonaws.com/emato/product/62c518a14a359.png',
          'game_display_name' => 'AE sexy',
          'game_name' => 'AE sexy',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
      ],

      'Sbobet' => [
        [
          'game_image_url'=>'https://media.123betnow.net/list/game/image_63623f10e4f50.png',
          'game_display_name' => 'Sbobet',
          'game_name' => 'Sbobet',
          'game_show' => 1,
          'game_play' => '',
          'check_agin'=>1,
        ],
      ],

    ];
    $category = [
      ['name'=>'All', 'key'=>'all'],
      //['name'=>'Wm555', 'key'=>'wm555'],
      ['name'=>'Agin', 'key'=>'agin'],
      ['name'=>'Evolution', 'key'=>'Evo'],
      ['name'=>'Aesexy', 'key'=>'Aesexy'],
      ['name'=>'Sbobet', 'key'=>'Sbobet'],
      /*['name'=>'SA Game', 'key'=>'sa_game'],
      ['name'=>'Sky Game', 'key'=>'sky_game'],
      ['name'=>'Sportbook', 'key'=>'sportbook'],*/
    ];



    return $this->response(200, [ 'category'=> $category,'game'=>$gameList], '', [], true);
  }
  //api cho giao diện mới
  public function getListGameNew(Request $req){
    $category = [1=>'Recommended',2=>'Trending',3=>'Hot'];
    $gameList = array();
    foreach($category as $key=>$value){
      $gameList[$value] = DB::table('list_game')->where('type',$key)->where('show',1)->get();
    }
    $gameList['All'] = DB::table('list_game')->where('show',1)->get();

    return $this->response(200, [ 'category'=> $category,'game'=>$gameList], '', [], true);
  }
  public function getStaticticalHome(Request $req){
    $mondayLastWeek = date('Y-m-d 00:00:00',strtotime('monday last week'));//, strtotime('saturday last week') 
    $sundayLastWeek = date('Y-m-d 23:59:59', strtotime('sunday last week'));

    $topListAE = DB::table('bet_history_ae_sexy')->join('users','User_ID','userId')
      ->whereNotNull('gameInfo')->where('time123bet','<=',$sundayLastWeek)->where('realWinAmount','>','realBetAmount')
      ->selectRaw("SUM(realWinAmount) as totalWin,User_Email,User_WalletAddress,User_ID")

      ->groupBy('userId')->orderbyDesc('totalWin');// 
    //$topAESexyAll = clone $topList; $topAESexyWeek = clone $topList;
    $topAESexyAll = $topListAE->first();
    $topAESexyWeek = $topListAE->where('time123bet','>=',$mondayLastWeek)->first();

    $topListEvolu = DB::table('bet_history_evo')->join('users','User_ID','user_id')
      ->whereNotNull('gameInfo')->where('time123bet','<=',$sundayLastWeek)
      ->selectRaw("SUM(realWinAmount-realBetAmount) as totalWin,User_Email,User_WalletAddress,User_ID")
      ->groupBy('userId')->orderbyDesc('totalWin');// 

    $arrayTop = [

    ];
    dd($topAESexyAll,$topAESexyWeek);
  }
}
