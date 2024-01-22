<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\User;
use App\Model\Investment;
use App\Model\Eggs;
use App\Model\MoneyAction;
use App\Model\GameBet;
use App\Model\Profile;
use Illuminate\Support\Facades\Auth;
class Money extends Model
{
  protected $table = 'money';
  public $timestamps = false;

  protected $fillable = ['Money_ID', 'Money_Game', 'Money_User', 'Money_BetAction', 'Money_USDT', 'Money_USDT_Return', 'Money_USDTFee', 'Money_Time', 'Money_Comment', 'Money_MoneyAction', 'Money_MoneyStatus', 'Money_BinaryWeak', 'Money_Package', 'Money_TXID', 'Money_Address', 'Money_Currency', 'Money_Rate', 'Money_Confirm', 'Money_Active', 'Money_FromAPI'];

  protected $primaryKey = 'Money_ID';
  public static function getSymbol()
  {
    $arr_coin = [
      0 => 'EUSD',
      1 => 'BTC',
      2 => 'ETH',
      3 => 'EUSD',
      4 => 'DP-NFT',
      5 => 'USDT (ERC20)',
      6 => 'USDT (TRC20)',
      7 => 'HBG',
      8 => 'EBP',
      9 => 'GOLD',
      10 => 'USDT Bonus',
      11 => 'USDT (BEP20)',
      12 => 'Solana (BEP20)',
      13 => 'Coin98 (BEP20)',
      14 => 'Cardano (BEP20)',
      15 => 'Tron',
      16 => 'BNB',
      17 => 'USDT (Voucher)',
      18 => 'USDT (Lucky Hero)',
      20 => 'Promotion Gift Code',
      21 => 'VNĐ',
    ];
    return $arr_coin;
  }
  public static function checkSpamAction($userID){
    $checkSpam = Money::where('Money_User', $userID)->whereIn('Money_MoneyAction', [2,3,4,57,31,32,63,68,73,75,76])->where('Money_MoneyStatus', 1)->groupBy('Money_Time', 'Money_Comment')->havingRaw('Count(*) > 1')->first();
    if($checkSpam){
      $user = User::find($userID);
      if($user->User_Block == 1){
        return true;
      }
      $user->User_Block = 1;
      $user->save();
      Log::insertLog($userID, "Block Spam Money", 0, 'User Block Spam Money');
      $message = "<b> BLOCK SPAM MONEY </b>\n"
        . "PROJECT: <b>123BetNow</b>\n"
        . "ID: <b>$user->User_ID</b>\n"
        . "NAME: <b>$user->User_Name</b>\n"
        . "EMAIL: <b>$user->User_Email</b>\n"
        . "<b>Submit Withdraw Time: </b>\n"
        . date('d-m-Y H:i:s',time());
      dispatch(new SendTelegramJobs($message, -398297366));
      return true;
    }
  }

  public static function commissionDeposit($user){
    return false;
    $checkDeposit = Money::where('Money_MoneyAction', 1)->where('Money_MoneyStatus', 1)->where('Money_User', $user->User_ID)->sum('Money_USDT');
    if($checkDeposit < 5){
      return false;
    }
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $CommissionArr = array(1=>7, 2=>5, 3=>2);
    $arrayInsert = [];
    $action = 9;
    $currency = 8;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('EBP');
    for($i = 1; $i<=3; $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      $checkUser = User::find($arrParent[$i]);
      if(!$checkUser){
        continue;
      }
      $checkProfile = Profile::where('Profile_User', $arrParent[$i])->where('Profile_Status', 1)->first();
      if(!$checkProfile){
        continue;
      }
      $checkPaidDup = Money::where('Money_User', $arrParent[$i])
        ->where('Money_MoneyAction', $action)
        ->where('Money_Comment', 'LIKE', "%$user->User_ID%")
        ->first();
      if($checkPaidDup){
        continue;
      }
      $getF1 = User::getQuantityF1($checkUser, 10);
      $checkUserF1 = User::checkUserInBranchAvailable($user, $getF1);
      if(!$checkUserF1){
        continue;
      }
      $Commission = $CommissionArr[$i];
      $Comment = 'Comission Deposit from F'.$i.' ID:'.$user->User_ID;
      $arrayInsert[] = array(
        'Money_User' => $checkUser->User_ID,
        'Money_USDT' => $Commission,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => $Comment,
        'Money_MoneyAction' => $action,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => $Commission,
        'Money_Rate' => $rate,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      echo $checkUser->User_ID." : ".$Comment." ".$Commission." EBP <br>";

    }
    if(count($arrayInsert)){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function bonusKYC($user){
    return false;
    $action = 8;
    $currency = 8;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('EBP');
    $checkBonus = Money::where('Money_MoneyAction', $action)->where('Money_User', $user->User_ID)->where('Money_MoneyStatus', 1)->first();
    if($checkBonus){
      return false;
    }
    $amount = 15;
    $Comment = 'Bonus KYC';
    $arrayInsert[] = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => $amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $Comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $currency,
      'Money_CurrentAmount' => $amount,
      'Money_Rate' => $rate,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    Money::insert($arrayInsert);
    return true;
  }

  public static function checkCommissionAgency($user, $package, $amount){
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $percentArr = array(1=>0.5, 2=>0.25, 3=>0.12);
    $arrayInsert = [];
    $action = 64;
    $currency = 3;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('EUSD');
    $PackageAgency = GameBet::getPackageAgency();
    for($i = 1; $i<=3; $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      $checkUser = User::find($arrParent[$i]);
      if(!$checkUser){
        continue;
      }
      $checkPaidDup = Money::where('Money_User', $arrParent[$i])
        ->where('Money_MoneyAction', $action)
        ->where('Money_Comment', 'LIKE', "%$user->User_ID%")
        ->where('Money_Comment', 'LIKE', "%".$package['name']."%")
        ->first();
      if($checkPaidDup){
        continue;
      }
      $totalBuyAgency = GameBet::totalBuyAgency($checkUser->User_ID);
      if($totalBuyAgency < 10){
        continue;
      }
      $agencyUser = GameBet::getPackageUser($totalBuyAgency, $PackageAgency);
      if($agencyUser <= 0){
        continue;
      }
      if($agencyUser['f'] < $i){
        continue;
      }
      $percent = $percentArr[$i];
      $Commission = $amount*$percent / $rate;
      $Comment = 'Commission Buy Agency '.$package['name'].' ('.($percent*100).'%) From F'.$i.' ID:'.$user->User_ID;
      $arrayInsert[] = array(
        'Money_User' => $checkUser->User_ID,
        'Money_USDT' => $Commission,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => $Comment,
        'Money_MoneyAction' => $action,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => $Commission,
        'Money_Rate' => $rate,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      //echo $checkUser->User_ID." : ".$Comment." ".$Commission." EUSD <br>";

    }
    if(count($arrayInsert)){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function checkCommissionAgencyBackup($user, $package, $amount){
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $CommissionArr = array(1=>2, 2=>1.5, 3=>1);
    $arrayInsert = [];
    $action = 64;
    $currency = 8;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('EBP');
    $PackageAgency = GameBet::getPackageAgency();
    for($i = 1; $i<=3; $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      $checkUser = User::find($arrParent[$i]);
      if(!$checkUser){
        continue;
      }
      $checkPaidDup = Money::where('Money_User', $arrParent[$i])
        ->where('Money_MoneyAction', $action)
        ->where('Money_Comment', 'LIKE', "%$user->User_ID%")
        ->where('Money_Comment', 'LIKE', "%".$package['name']."%")
        ->first();
      if($checkPaidDup){
        continue;
      }
      $totalBuyAgency = GameBet::totalBuyAgency($checkUser->User_ID);
      if($totalBuyAgency < 10){
        continue;
      }
      $agencyUser = GameBet::getPackageUser($totalBuyAgency, $PackageAgency);
      if($agencyUser <= 0){
        continue;
      }
      $getF1 = User::getQuantityF1($checkUser, $agencyUser['branch']);
      $checkUserActive = User::checkUserInBranchAvailable($user, $getF1);
      if(!$checkUserActive){
        continue;
      }
      $Commission = $CommissionArr[$i] / $rate;
      $Comment = 'Comission Buy Agency '.$package['name'].' from F'.$i.' ID:'.$user->User_ID;
      $arrayInsert[] = array(
        'Money_User' => $checkUser->User_ID,
        'Money_USDT' => $Commission,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => $Comment,
        'Money_MoneyAction' => $action,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => $Commission,
        'Money_Rate' => $rate,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      echo $checkUser->User_ID." : ".$Comment." ".$Commission." EBP <br>";

    }
    if(count($arrayInsert)){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function checkCommissionAgency123BetNow($user, $amount, $currency, $req){
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $CommissionArr = array(1=>0.5, 2=>0.25, 3=>0.12);
    $arrayInsert = [];
    $action = 64;
    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday last week'));
    //$mondayLastWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    // 	    $mondayLastWeek = date('Y-m-d 00:00:00', strtotime('2019-11-01'));
    // thứ 2 tuần này
    $mondayThisWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
    //$mondayThisWeek = date('Y-m-d H:i:s');
    $packageArray = GameBet::getPackage();
    // dd($arrParent, $actionNameArray[$action]);
    for($i = 1; $i<=3; $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      $checkUser = User::find($arrParent[$i]);
      if(!$checkUser){
        continue;
      }
      $checkPaidDup = Money::where('Money_User', $arrParent[$i])
        ->where('Money_MoneyAction', $action)
        ->where('Money_Comment', 'LIKE', "%$user->User_ID%")
        ->first();
      if($checkPaidDup){
        continue;
      }
      $checkBuyAgency = GameBet::checkBuyAgency($arrParent[$i]);
      if(!$checkBuyAgency){
        continue;
      }
      //lấy gói thoả điều kiện
      $getPackageParent = GameBet::NumberPackage($arrParent[$i], $mondayLastWeek, $mondayThisWeek, 0);
      if($getPackageParent <= 0){
        continue;
      }
      //lấy dữ liệu của gói ra 
      $dataInterest = $packageArray[$getPackageParent];
      if($dataInterest['f'] < $i){
        continue;
      }

      $Commission = 0;
      $Commission = (float)(($amount)*$CommissionArr[$i]);
      $Comment = 'Comission Buy Agency 123BetNow from F'.$i.' ID:'.$user->User_ID;
      $arrayInsert[] = array(
        'Money_User' => $checkUser->User_ID,
        'Money_USDT' => $Commission,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => $Comment,
        'Money_MoneyAction' => $action,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $currency,
        'Money_CurrentAmount' => $Commission,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 0,
      );
      echo $checkUser->User_ID." : ".$Comment." ".$Commission." EUSD <br>";

    }
    if(count($arrayInsert) && $req->pay == 1){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function getCheckConfirm($id){
    $money = Money::where('Money_ID', $id)/*->whereIn('Money_MoneyAction', [2])*/->first();
    return $money;
  }

  public static function feeGas(){
    $rateETH = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('ETH');

    $getLastedGas = DB::table('gas')->orderByDesc('id')->first();
    if(!$getLastedGas || (time()- $getLastedGas->time >= $getLastedGas->duration)){
      $json = json_decode(file_get_contents('https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=GMGAYV28HNBZSAHUQQD3PQDXMFGZU7BMBP'));
      $pricegas = 150;
      if($json->message == 'OK'){
        $pricegas = $json->result->FastGasPrice;
      }

      $timeChange = 1800;
      $data = [
        'amount' => $pricegas,
        'time' => time(),
        'duration' => $timeChange,
      ];
      DB::table('gas')->insert($data);
    }else{
      $pricegas = $getLastedGas->amount;
    }
    $pricegas = $pricegas/1000000000;
    $feeGas = $pricegas*42000*$rateETH;
    $feeGas = $feeGas*1.15;
    return $feeGas;
  }

  public static function checkCommission($user, $action, $currency, $amount){
    $moneyAction = MoneyAction::where('MoneyAction_ID', $action)->first();
    $arrParent = explode(',', $user->User_Tree);

    $arrParent = array_reverse($arrParent);
    $CommissionArr = array(1=>0.05, 2=>0.03, 3=>0.02);
    $arrayInsert = [];
    // dd($arrParent, $actionNameArray[$action]);
    for($i = 1; $i<=3; $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      if(isset($arrParent[$i])){
        $checkUser = User::find($arrParent[$i]);
        if(!$checkUser){
          continue;
        }
        $checkEggsActive = Eggs::where('Owner', $checkUser->User_ID)->where('ActiveTime', '>', 0)->first();
        if(!$checkEggsActive){
          continue;
        }
        if($i >= 2){
          $getChild = User::where('User_Parent', $checkUser->User_ID)->pluck('User_ID')->toArray();
          $countChildEggsActive = Eggs::whereIn('Owner', $getChild)->where('ActiveTime', '>', $checkEggsActive->ActiveTime)->select('ID', 'Owner')->groupBy('Owner')->get()->count();
          if($i == 2){
            if($countChildEggsActive < 2){
              continue;
            }
          }elseif($i == 3){
            if($countChildEggsActive < 5){
              continue;
            }
          }else{
            continue;
          }
        }
        $Commission = 0;
        $Commission = (float)(($amount)*$CommissionArr[$i]);
        $Comment = 'Commission '.$moneyAction->MoneyAction_Name.' from F'.$i.' ID:'.$user->User_ID;
        $arrayInsert[] = array(
          'Money_User' => $checkUser->User_ID,
          'Money_USDT' => $Commission,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => $Comment,
          'Money_MoneyAction' => $action,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => $currency,
          'Money_CurrentAmount' => $Commission,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 0,
        );
      }

    }
    // dd($arrayInsert);
    if(count($arrayInsert)){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function checkAgencyCommission($user, $actionPaid, $currency, $amount){
    $moneyAction = MoneyAction::where('MoneyAction_ID', $actionPaid)->first();
    $arrParent = explode(',', $user->User_Tree);
    $arrParent = array_reverse($arrParent);
    $CommissionArr = array(1=>0.005, 2=>0.01, 3=>0.015, 3=>0.02, 3=>0.03);
    $arrayInsert = [];
    $percentCurrent = 0;
    $percentSameLevel = 0.1;
    $action = 10;
    $actionSameRank = 11;
    for($i = 1; $i<count($arrParent); $i++){
      if(!isset($arrParent[$i])){
        continue;
      }
      $checkUser = User::find($arrParent[$i]);
      if(!$checkUser){
        continue;
      }
      if(!isset($CommissionArr[$checkUser->User_Agency_Level])){
        continue;
      }
      //số % nhận được = số % package của parent - số % của user con
      $percentInterest = $CommissionArr[$checkUser->User_Agency_Level] - $percentCurrent;
      //update percent parent
      $percentCurrent = $CommissionArr[$checkUser->User_Agency_Level];
      if($percentInterest < 0){
        continue;
      }elseif($percentInterest == 0){
        // hoa hồng đồng cấp
        if(!isset($amountInterest)){
          $amountInterest = $amount*$percentInterest;
          $amountInterest = $amountInterest*$percentSameLevel;
        }else{
          $amountInterest = $amountInterest*$percentSameLevel;
        }
        $Comment = 'Same Rank  '.$moneyAction->MoneyAction_Name.' ID:'.$arrParent[$i-1];
        $arrayInsert[] = array(
          'Money_User' => $checkUser->User_ID,
          'Money_USDT' => $amountInterest,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => $Comment,
          'Money_MoneyAction' => $actionSameRank,
          'Money_MoneyStatus' => 1,
          'Money_Address' => null,
          'Money_Currency' => $currency,
          'Money_CurrentAmount' => $amountInterest,
          'Money_Rate' => 1,
          'Money_Confirm' => 0,
          'Money_Confirm_Time' => null,
          'Money_FromAPI' => 0,
        );
        continue;
      }
      $amountInterest = (float)(($amount)*$percentCurrent);
      $amountSameLevel = $amountInterest;
      $Comment = 'Rank Commission '.$actionNameArray[$actionPaid].' from F'.$i.' ID:'.$user->User_ID;
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
    }
    if(count($arrayInsert)){
      Money::insert($arrayInsert);
    }
    return true;
  }

  public static function checkMaxout($user_ID, $amount = 0){

    $checkMaxout = User::where('User_ID', $user_ID)->value('User_UnMaxout');
    if($checkMaxout == 1){
      return 999999999;
    }
    $total_invest = Investment::where('investment_User', $user_ID)->where('investment_Status', 1)->sum(DB::raw('investment_Amount*investment_Rate'));
    $percent_maxout = 3;
    //SUM COMMISSION And Interest
    $total_com = Money::where('Money_User', $user_ID)
      ->whereIN('Money_MoneyAction', [4,5,6,9,13,14,16,17])
      ->where('Money_MoneyStatus', 1)
      ->sum('Money_USDT');
    // dd($total_invest,$total_com, $amount);
    if($total_com+$amount >= $total_invest*$percent_maxout){
      // invest income
      $getBalance = User::getBalance($user_ID, 10);
      if($getBalance >= $total_invest){
        $updateblance = User::updateBalance($user_ID, 10, -($total_invest));
        $moneyArray = array(
          'Money_User' => $user_ID,
          'Money_USDT' => -$total_invest,
          'Money_USDTFee' => 0,
          'Money_Time' => time(),
          'Money_Comment' => 'Join package $'.number_format($total_invest,2).' 300% Income',
          'Money_MoneyAction' => 15,
          'Money_MoneyStatus' => 1,
          'Money_Rate' => 1,
          'Money_CurrentAmount' => $total_invest,
          'Money_Currency' => 10
        );
        //Invest
        $invest = array(
          'investment_User' => $user_ID,
          'investment_Amount' => $total_invest,
          'investment_Rate' => 1,
          'investment_Currency' => 5,
          'investment_Time' => time(),
          'investment_Status' => 1
        );
        // thêm dữ liệu
        DB::table('investment')->insert($invest);
        DB::table('money')->insert($moneyArray);
      }
      // return $total_com+$amount - $total_invest;
    }
    return 999999999;
    // $total_invest = $total_invest * $percent_maxout;
    // return $total_invest - $total_com;
  }

  public static function limitWithdrawIncome($user_ID, $amount = 0){

    $checkMaxout = User::where('User_ID', $user_ID)->value('User_UnMaxout');
    if($checkMaxout == 1){
      return true;
    }
    $total_invest = Investment::where('investment_User', $user_ID)->where('investment_Status', 1)->sum(DB::raw('investment_Amount*investment_Rate'));
    //SUM COMMISSION And Interest
    // $total_com = Money::where('Money_User', $user_ID)
    // 					->whereIN('Money_MoneyAction', [4,5,6,9,13,14,16,17])
    // 					->where('Money_MoneyStatus', 1)
    // 					->sum('Money_USDT');
    $totalWithdrawIncome = Money::where('Money_User', $user_ID)
      ->whereIN('Money_MoneyAction', [21])
      ->where('Money_MoneyStatus', 1)
      ->where('Money_Currency', 5)
      ->sum('Money_USDT');
    if($totalWithdrawIncome+$amount <= $total_invest*2){
      return true;
    }
    return false;
    // $total_invest = $total_invest * $percent_maxout;
    // return $total_invest - $total_com;
  }

  public static function checkWithdraw($userID){
    $checkInvest = Investment::where('investment_User', $userID)->where('investment_Status', 1)->sum('investment_Amount');
    if(!$checkInvest || $checkInvest < 300){
      return ['status'=>false, 'message'=>'Your investment isn\'t enough $300'];
    }
    $checkChildInvest = Investment::join('users', 'investment_User', 'User_ID')
      ->where('User_Parent', $userID)
      ->where('investment_Status', 1)
      ->select('User_ID')
      ->groupBy('User_ID')->get()->count();
    if($checkChildInvest < 2){
      // return ['status'=>false, 'message'=>'You need to invite 2 investors to withdraw'];
    }
    return ['status'=>true];
  }

  //Check spam request
  public static function RandomToken()
  {
    $code = str_random(32) . '' . rand(10000000, 99999999);
    $CheckCode = DB::table('string_token')->where('Token', $code)->first();
    if (!$CheckCode) {
      //Xóa token của thằng đó đã tạo mà chưa dùng

      $minutest_30p = date('Y-m-d H:i:s',strtotime('-30 minutes', time()));

      $delete = DB::table('string_token')->where('CreateDate', '<=', $minutest_30p)->delete();


      //bắt đàu tạo token mới
      $createCode = DB::table('string_token')->insert([
        'Token' => $code,
        'User' => Auth::user()->User_ID
      ]);
      return $code;
    } else {
      return self::RandomToken();
    }
  }

  // check spam cho app
  public static function RandomTokenAPI($user)
  {
    $code = str_random(32) . '' . rand(10000000, 99999999);
    $CheckCode = DB::table('string_token')->where('Token', $code)->first();
    if (!$CheckCode) {
      //Xóa token của thằng đó đã tạo mà chưa dùng
      $minutest_30p = date('Y-m-d H:i:s',strtotime('-30 minutes', time()));

      $delete = DB::table('string_token')->where('CreateDate', '<=', $minutest_30p)
        ->orWhere('User', $user)
        ->delete();

      //bắt đàu tạo token mới
      $createCode = DB::table('string_token')->insert([
        'Token' => $code,
        'User' => $user
      ]);
      return $code;
    } else {
      return self::RandomTokenAPI($user);
    }
  }


  static function StatisticTotal($where)
  {
    $result = Money::join('users', 'Money_User', 'User_ID')->selectRaw('
			 User_ID as userid,
			SUM(IF(`Money_Currency` = 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceDAFCO,
			SUM(IF(`Money_Currency` != 8 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceUSD,
			SUM(IF(`Money_Currency` = 10 ' . $where . ', (ROUND((`Money_USDT` - `Money_USDTFee`),8)), 0)) as BalanceMATRIX,

			SUM(IF(`Money_Currency` = 1 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as DepositBTC, 
			SUM(IF(`Money_Currency` = 2 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as DepositETH,
			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as DepositUSD,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as DepositDAFCO,
			SUM(IF(`Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as DepositTotal,

			SUM(IF(`Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as WithdrawTotal,
			SUM(IF(`Money_MoneyAction` = 2 ' . $where . ', ROUND((`Money_USDTFee`),8), 0)) as WithdrawFee,

			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,8), 0)) as GiveUSD,
			SUM(IF(`Money_Currency` = 5 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,8), 0)) as TransferUSD,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Give%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,8), 0)) as GiveDAFCO,
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 7 AND `Money_Comment` LIKE "Transfer%" ' . $where . ', ROUND(`Money_USDT` - `Money_USDTFee`,8), 0)) as TransferDAFCO,

			SUM(IF(`Money_MoneyAction` = 3 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as Investment,
			SUM(IF(`Money_MoneyAction` = 8 ' . $where . ', ROUND((`Money_USDTFee`),8), 0)) as CancelInvestFee,

			SUM(IF(`Money_MoneyAction` = 4 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as Interest,
			SUM(IF(`Money_MoneyAction` = 5 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as Direct,
			SUM(IF(`Money_MoneyAction` = 6 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as Affiliate,

			SUM(IF(`Money_MoneyAction` = 10 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as BonusDeposit,
			SUM(IF(`Money_MoneyAction` = 11 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as RefundBet,
			SUM(IF(`Money_MoneyAction` = 12 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MasterCom,
			SUM(IF(`Money_MoneyAction` = 13 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MasterComSameLevel,

			SUM(IF(`Money_MoneyAction` = 21 AND `Money_Currency` = 10 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixDeposit,
			SUM(IF(`Money_MoneyAction` = 22 AND `Money_Currency` = 10 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixWithdraw,
			SUM(IF(`Money_MoneyAction` = 18 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixJoin,
			SUM(IF(`Money_MoneyAction` = 25 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixBonusDAFCO,
			SUM(IF(`Money_MoneyAction` = 24 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixDirect,
			SUM(IF(`Money_MoneyAction` = 19 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixCom,
			SUM(IF(`Money_MoneyAction` = 20 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixIncome,
			SUM(IF(`Money_MoneyAction` = 23 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as MatrixReActive,


			SUM(IF(`Money_MoneyAction` = 8 ' . $where . ', ROUND((`Money_USDT` - `Money_USDTFee`),8), 0)) as RefundInvestment')
      ->where('Money_MoneyStatus', 1)
      ->where('User_Level', 0)
      ->where('User_Status', 1);


    //			( SELECT SUM(`amount`) FROM `sonix_log` join `users` on `User_ID` = `user` WHERE User_Level = 0 AND type = "debit") as TotalBet,
    //			( SELECT SUM(`amount`) FROM `sonix_log` join `users` on `User_ID` = `user` WHERE User_Level = 0 AND type = "credit") as BetWin,
    return $result;
  }

  public static function getStatistic($where)
  {
    $result = Money::join('users', 'Money_User', 'User_ID')
      ->selectRaw('Money_User, 
			SUM(IF(`Money_Currency` = 8 AND `Money_MoneyAction` = 1 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as deposit_ebp,
			SUM(IF(`Money_Currency` = 9 AND `Money_MoneyAction` = 33 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as buy_gold,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 33 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as buy_gold_eusd,
			SUM(IF(`Money_Currency` = 9 AND (`Money_MoneyAction` = 35 OR `Money_MoneyAction` = 39) ' . $where . ', ROUND(`Money_USDT`,8), 0)) as gold_reward,
			SUM(IF(`Money_Currency` = 9 AND `Money_MoneyAction` = 27 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as buy_egg_gold,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 27 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as buy_egg_eusd,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 27 ' . $where . ', ROUND(`Money_USDT`,8), 0))/-200 as count_egg_buy,
			SUM(IF(`Money_Currency` = 9 AND (`Money_MoneyAction` = 28 OR `Money_MoneyAction` = 29) ' . $where . ', ROUND(`Money_USDT`,8), 0)) as buy_items_gold,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 30 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as active_egg_eusd,
			SUM(IF(`Money_Currency` = 3 AND (`Money_MoneyAction` = 5 OR `Money_MoneyAction` = 6 OR `Money_MoneyAction` = 8 OR `Money_MoneyAction` = 9) ' . $where . ', ROUND(`Money_USDT`,8), 0)) as direct_commission,
			SUM(IF(`Money_Currency` = 3 AND (`Money_MoneyAction` = 10 OR `Money_MoneyAction` = 11) ' . $where . ', ROUND(`Money_USDT`,8), 0)) as achievement_commission,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 7 AND `Money_USDT` < 0 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as transfer_to,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 7 AND `Money_USDT` > 0 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as received_from,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND(`Money_USDT`,8), 0)) as withdraw,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 2 ' . $where . ', ROUND(`Money_USDTFee`,8), 0)) as fee_withdraw,
			SUM(IF(`Money_Currency` = 3 AND `Money_MoneyAction` = 7 AND `Money_USDT` < 0 ' . $where . ', ROUND(`Money_USDTFee`,8), 0)) as fee_transfer
			')
      ->where('Money_MoneyStatus', 1)
      // ->where('User_Level', 0)
      ->groupBy('Money_User');
    return $result;
  }


  public static function commissionDepositNew($user, $amount, $currency, $rate){
    //return false;
    if($user->User_Level != 1){
      //return false;
    }
    //dd($amount,$rate,$currency);
    //check ngày đăng ký user
    $dayStart = strtotime('2022-01-25 03:30:00');
    $dayEnd = strtotime('2022-02-26 00:00:00');
    $dayRegister = strtotime($user->User_RegisteredDatetime);
    //dd($dayRegister,$dayEnd);
    if($dayStart > $dayRegister || $dayRegister > $dayEnd){
      return false;
    }
    $checkDeposit = Money::where('Money_MoneyAction', 1)->where('Money_MoneyStatus', 1)->where('Money_User', $user->User_ID)->sum('Money_USDT');
    if($checkDeposit < 5){
      //return false;
    }

    $parentID = $user->User_Parent;
    $infoParrent = User::find($parentID);
    if(!$infoParrent){
      return false;
    }

    $percenComission = 0.005;
    $action = 9;
    $rateEUSD = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('EUSD');
    $currencyEUSD = 3;

    $amountCommission = $percenComission*$amount;
    $amountCommissionEUSD = $amountCommission*$rate/$rateEUSD;

    $Comment = 'Comission Deposit from F1 ID:'.$user->User_ID;
    $arrayInsert = array(
      'Money_User' => $infoParrent->User_ID,
      'Money_USDT' => $amountCommissionEUSD,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $Comment,
      'Money_MoneyAction' => $action,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_CurrencyFrom' => $currency,
      'Money_Currency' => $currencyEUSD,
      'Money_CurrentAmount' => $amountCommissionEUSD,
      'Money_Rate' => $rateEUSD,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 0,
    );
    //dd($arrayInsert,$amount,$rateEUSD,$rate);
    if($arrayInsert){
      Money::insert($arrayInsert);
    }
    return true;
    //bỏ
    /*
        $arrParent = explode(',', $user->User_Tree);
        $arrParent = array_reverse($arrParent);
		$CommissionArr = array(1=>7, 2=>5, 3=>2);
		$arrayInsert = [];

        for($i = 1; $i<=3; $i++){
			if(!isset($arrParent[$i])){
				continue;
			}
			$checkUser = User::find($arrParent[$i]);
			if(!$checkUser){
				continue;
			}
            $checkProfile = Profile::where('Profile_User', $arrParent[$i])->where('Profile_Status', 1)->first();
          	if(!$checkProfile){
              	continue;
            }
			$checkPaidDup = Money::where('Money_User', $arrParent[$i])
              					->where('Money_MoneyAction', $action)
                                ->where('Money_Comment', 'LIKE', "%$user->User_ID%")
                                ->first();
			if($checkPaidDup){
				continue;
			}
            $getF1 = User::getQuantityF1($checkUser, 10);
            $checkUserF1 = User::checkUserInBranchAvailable($user, $getF1);
			if(!$checkUserF1){
              	continue;
            }
			$Commission = $CommissionArr[$i];
			$Comment = 'Comission Deposit from F'.$i.' ID:'.$user->User_ID;
			$arrayInsert[] = array(
				'Money_User' => $checkUser->User_ID,
				'Money_USDT' => $Commission,
				'Money_USDTFee' => 0,
				'Money_Time' => time(),
				'Money_Comment' => $Comment,
				'Money_MoneyAction' => $action,
				'Money_MoneyStatus' => 1,
				'Money_Address' => null,
				'Money_Currency' => $currency,
				'Money_CurrentAmount' => $Commission,
				'Money_Rate' => $rate,
				'Money_Confirm' => 0,
				'Money_Confirm_Time' => null,
				'Money_FromAPI' => 0,
			);
          	echo $checkUser->User_ID." : ".$Comment." ".$Commission." EBP <br>";

        }
        if(count($arrayInsert)){
            Money::insert($arrayInsert);
        }
        return true;
        */
  }
}
