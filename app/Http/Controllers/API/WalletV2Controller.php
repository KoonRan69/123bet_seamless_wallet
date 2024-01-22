<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Address;
use Coinbase\Wallet\Resource\Account;
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money as CB_Money;
use Coinbase\Wallet\Enum\Param;
use DB;

use Sop\CryptoTypes\Asymmetric\EC\ECPublicKey;
use Sop\CryptoTypes\Asymmetric\EC\ECPrivateKey;
use Sop\CryptoEncoding\PEM;
use kornrunner\Keccak;

use Validator;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\LogUser;
use App\Model\User;
use App\Model\userBalance;
use App\Jobs\SendTelegramJobs;
use App\Model\Money;
use App\Model\logMoney;
use PayusAPI\Http\Client as PayusClient;
use PayusAPI\Resources\Payus;
use Illuminate\Support\Str;
use GuzzleHttp\Client as G_Client;

use App\Model\Wallet;
class WalletV2Controller extends Controller{
  public $feeWithdraw = 0.003;
  public $feeSwap = 0.03;
  public $config;
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['listPool','getHistoryDepositLucky','postCheckEmail','postWithdrawNew']]);
    $this->config = config('urlSBOBET.sbobet');
  }
  public function inputCodeBonus(Request $req){
    $user = Auth::user();
    $validator = Validator::make($req->all(), [
      'code' => 'required|max:10',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    //check tài khoản tạo sbobet hay chưa
    if(!$user->User_Name_Sbobet){
      return $this->response(200, [], 'You have not created an account sbobet', [], false);
    }
    $checkBonus = DB::table('code_bonus')->where('code',$req->code)->first();
    if(!$checkBonus){
      return $this->response(200, [], 'The gift code is wrong', [], false);
    }
    if($checkBonus->quantity <= 0){ 
      return $this->response(200, [], 'Gift code sold out', [], false);
    }
    if($checkBonus->expiration_date < date('Y-m-d H:i:s',time())){
      return $this->response(200, [], 'The code has been expired for use', [], false);
    }
    //check phải là tài khoản mới tạo chưa có phát sinh nạp (chưa phát sinh volume)
    $checkFirstDeposit = Money::where('Money_MoneyAction',91)->where('Money_User',$user->User_ID)->where('Money_MoneyStatus',1)->first();
    if($checkFirstDeposit){
      return $this->response(200, [], 'Gift code only for new accounts', [], false);
    }
    //check tài khoản đã sử dụng code tân thủ rồi
    $checkUseCode = Money::where('Money_MoneyAction',95)->where('Money_User',$user->User_ID)->where('Money_MoneyStatus',1)->first();
    if($checkUseCode){
      return $this->response(200, [], 'Used a rookie gift code', [], false);
    }
    $amountUSDT = $checkBonus->price_bonus*1;
    $txCode = Str::random(29);
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$amountUSDT,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' =>'Receive from Gift code: '.$req->code.' ' . ($amountUSDT + 0) . ' EUSD',
      'Money_MoneyAction' => 95,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 20,
      'Money_CurrentAmount' => $amountUSDT,
      'Money_Rate' => 1,
      'Money_TXID' => $txCode,
    );
    $id = Money::insertGetId($arrayInsert);

    $url = $this->config['url'].'/web-root/restricted/player/deposit.aspx';
    $body = [
      "Username" => $user->User_Name_Sbobet,
      "Amount"=> $amountUSDT,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
      "TxnId" => "$txCode",
    ];
    $topup_str = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    $check= json_decode($result);
    if ($err) {
      $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
      $cmtLogTitle = 'Deposit failed sbobet';
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $req->ip());
      return $this->response(200, [], $cmtLogTitle, [], false);
    } else {
      if($check->error->id != 0){
        $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
        $cmtLogTitle = 'Deposit failed sbobet';
        LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $req->ip());
        return $this->response(200, $check->status , $cmtLogTitle, [], false);
      }
      $cmtLogTitle = 'Deposit sbobet success';
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $req->ip());

      DB::table('code_bonus')->where('code',$req->code)->Update(['quantity'=>$checkBonus->quantity-1]);

      return $this->response(200, ['balance' => $check->balance, 'amount' => $req->Amount], $cmtLogTitle, [], true);
    }
  }
  public function testPromotion(Request $req){
    $user = Auth::user();
    dd(logMoney::getBonusDailyRecharge($user->User_ID,105,3));
  }
  public function getHistoryDepositLucky(Request $req){
    $coin = 18;
    $list = Money::join('users','User_ID','Money_User')->whereNotNull('User_Email_LuckyHero')->where('User_Email_LuckyHero_Active',1)
      ->where('Money_MoneyAction', 2)
      ->where('Money_Currency', $coin)
      ->where('Money_MoneyStatus', 1)
      ->where('Money_Confirm', 1)
      ->selectRaw('Money_ID,Money_User,Money_USDT,Money_USDTFee,Money_Time,Money_TXID,Money_Address,multiplay_pool')
      ->orderByDesc('Money_Time')->limit(100)->get();
    return $this->response(200,$list);
  }
  //rút về balance Pool <=> bên lucky viết cron quét lịch sử để nạp dựa trên mã hash Money_TXID được tạo
  public function listPool(Request $req){
    $data = [
      ['addressPool' => '0x0845e78675bb8da7bab88b6c45fae25ba7ffb350','pool'=> 'SolarSystem', 'poolName'  => 'Pool Solar System'],
      ['addressPool' => '0x54a86a4ba9d8dcdcc55f8ffc683f4ca9dd337d1a','pool'=> 'Infinity',  'poolName' => 'Pool Infinity'],
    ];
    return $this->response(200, $data); 
  }
  public function getBalanceLucky(Request $req){
    $user = Auth::user();
    $coin = 18;
    $balance = User::getBalanceLucky($user->User_ID,$coin);
    return $this->response(200, $balance); 
  }
  public function postWithdrawToLuckyHero(Request $req){
    $user = Auth::user();
    $validator = Validator::make($req->all(), [
      'amount' => 'required|numeric|min:10|nullable',
      'pool' => 'required',
      //'auth' => 'required',
      'otp' => 'required',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $coin = 18;
    $user = User::where('User_ID', $user->User_ID)->first();
    $check_custom = $user->User_Level;
    if ($check_custom == 4 || $check_custom == 5) {
      //return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }

    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    $tokenOTP = $user->otp_lucky;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    //check đã connect 123betnow chưa
    if(!$user->User_Email_LuckyHero){
      return $this->response(200, [], 'You have not made connection from Lucky Hero', [], false);
    }
    if($user->User_Email_LuckyHero_Active != 1){
      return $this->response(200, [], 'Please check your '.$user->User_Email_LuckyHero.' mailbox to confirm connection from Lucky Hero', [], false);
    } 
    //Bảo mật
    $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
    if (!$checkProfile || $checkProfile->Profile_Status != 1) {
      //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
    }
    if($user->User_WalletAddress == null){
      return $this->response(200, [], trans('notification.Please_update_your_wallet_address!'), [], false);
    }
	
    /*$key2fa = 'X21B9TT2AI';
    $google2fa = app('pragmarx.google2fa');
    $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
    if (!$AuthUser) {
      return $this->response(200, [], trans('notification.User_is_not_authenticated!'), [], false);
    }
    $responseSecret = json_decode(xxtea_decrypt(base64_decode($AuthUser->google2fa_Secret), $key2fa), true);
    if($responseSecret['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    $valid = $google2fa->verifyKey($responseSecret['secret'], "$req->auth");

    if (!$valid) {
      return $this->response(200, [], trans('notification.Wrong_code'), [], false);
    }*/


    //sỐ TIỀN MUỐN RÚT
    $amount = $req->amount;
    $coinArr = DB::table('currency')->whereIn('Currency_ID', [3, 4, 6, 7, 8, 11,18])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    if (!isset($coinArr[$coin])) {
      return $this->response(200, [], trans('notification.Coin_invalid'), [], false);
    }
    $symbol = $coinArr[$coin];
    $rate = 1;
    if($req->pool != 'SolarSystem' && $req->pool != 'Infinity'){
      return $this->response(200, [],'Pool invalid', [], false);
    }
    $getBalance = User::getBalanceLucky($user->User_ID,$coin);
    $getBalance = $getBalance[$req->pool];
    $balance = $getBalance['balance'];
    $address = $getBalance['addressPool'];
    $amountFee = $balance * ($this->feeWithdraw / 100);
    if (($amount) > $balance) {
      return $this->response(200, ['balance' => $balance], trans('notification.Your_balance_is_not_enough'), [], false);
    }
    //kiểm tra có lệnh rút nào đang chờ chưa
    $withdraw = Money::where('Money_MoneyAction', 2)->where('Money_Currency', $coin)->where('Money_MoneyStatus', 1)->where('Money_Confirm', 0)->where('Money_User', $user->User_ID)->first();
    if ($withdraw) {
      return $this->response(200, ['balance' => $balance], trans('notification.Please_wait_for_the_withdrawal_to_be_approved'), [], false);
    }
    $confirm = 1;
    $comment = 'Withdraw to Lucky Hero ' . ($amount * 1) . ' ' . $symbol . ' to Pool ' . $getBalance['poolName'];
    $commentTelegram = 'WITHDRAW TO LUCKY HERO';

    // lưu lịch sử
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$amount + $amountFee,
      'Money_USDTFee' => -$amountFee,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => 2,
      'Money_MoneyStatus' => 1,
      'Money_Currency' => $coin,
      'Money_CurrentAmount' => ($amount - $amountFee),
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => $coin,
      'Money_Rate' => $rate,
      'Money_Confirm' => $confirm,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => md5(time()),
      'Money_Address' => $user->User_Email_LuckyHero,
      'multiplay_pool' =>  $address,
    );
    $id = Money::insertGetId($arrayInsert);
    // gọi jobs
    if($user->User_Email != null){
      $message = "$user->User_Email $commentTelegram " . $amount . " $symbol\n"
        . "<b>User ID: </b> "
        . "$user->User_ID\n"
        . "<b>Email: </b> "
        . "$user->User_Email\n"
        . "<b>Amount USD: </b> "
        . ($amount * $rate) . " USD\n"
        . "<b>Amount Coin: </b> "
        . (($amount - $amountFee)) . ' ' . $symbol . "\n"
        . "<b>Email: </b> "
        . $address . "\n"
        . "<b>Email: </b> "
        . $user->User_Email_LuckyHero . "\n"
        . "<b>Pool: </b> "
        . $address . "\n"
        . "<b>Rate: </b> "
        . "$ 1 \n"
        . "<b>Submit withdraw Time: </b>\n"
        . date('d-m-Y H:i:s', time());

      dispatch(new SendTelegramJobs($message, -448649753));
    }
    //dispatch(new WalletJobs($id, $user->User_ID))->delay(1);


    $withdraw = config('utils.action.withdraw');
    LogUser::addLogUser($user->User_ID, 'withdraw_luckyhero', $comment, $req->ip());
    $getBalance = User::getBalanceLucky($user->User_ID,$coin);
    $getBalance = $getBalance[$req->pool];
    $balance = $getBalance['balance'];
    
    $user->otp_lucky='';
    $user->save();
    
    return $this->response(200, ['balance' => array('main' => (float)$balance), 'wallet' => $address], 'You withdraw ' . ($amount * 1) . ' ' . $symbol . ' to Pool ' . $getBalance['poolName'], [], true);

  }
  //check adress deposit luckyhero
  public function postCheckEmail(Request $req){
    $validator = Validator::make($req->all(), [
      'email' => 'required|email|min:1|max:100',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $checkEmail = User::where('User_Email', trim($req->email))->first();
    if(!$checkEmail){
      return $this->response(200, [], 'Address does not exist!', [], false);
    }
    if($req->connect == 1){
      if($checkEmail->User_Email_LuckyHero_Active == 1){
        return $this->response(200, [], 'This email is connected!', [], false);
      }else{
        $checkEmail->User_Email_LuckyHero_Active = 1;
      }
    }
    $checkEmail->User_Email_LuckyHero = $req->email;
    $checkEmail->save();
    return $this->response(200, [], 'True!', [], true);
  }
  //check adress deposit luckyhero

  public function postWithdrawNew(Request $Hireq){
    $user = User::where('User_ID', Auth::user()->User_ID)->first();
    $check_custom = $user->User_Level;
    if($check_custom != 1){
      //return $this->response(200, ['require_auth' => false], trans('notification.The_system_is_maintained'), [], false);
    }

    $validator = Validator::make($req->all(), [
      'address' => 'required|string|min:1|nullable',
      'amount' => 'required|numeric|min:10|nullable',
      //'coin' => 'required|numeric|in:3,5,6,8',
    ],[
      'address.required' => trans('notification.address_required') ,
      'amount.required' => trans('notification.amount_required') ,
      'amount.min' => trans('notification.amount_min_10') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    //$user = User::where('User_ID', $user->User_ID)->first();
    if($check_custom == 4 || $check_custom == 5){
      return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }
    if($user->User_Lock_Withdraw) return $this->response(200, [], 'Can\'t use this function!', [], false);
    //Bảo mật
    $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
    if(!$checkProfile || $checkProfile->Profile_Status != 1){
      //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
    }
    include(app_path() . '/functions/xxtea.php');
    /*$key2fa = 'X21B9TT2AI';
    $google2fa = app('pragmarx.google2fa');
    $AuthUser = GoogleAuth::select('google2fa_Secret')->where('google2fa_User', $user->User_ID)->first();
    if (!$AuthUser) {
      return $this->response(200, [], trans('notification.User_is_not_authenticated!'), [], false);
    }
    $responseSecret = json_decode(xxtea_decrypt(base64_decode($AuthUser->google2fa_Secret), $key2fa), true);
    if($responseSecret['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    $valid = $google2fa->verifyKey($responseSecret['secret'], "$req->auth");

    if (!$valid) {
      return $this->response(200, [], trans('notification.Wrong_code'), [], false);
    }*/


    //sỐ TIỀN MUỐN RÚT
    $amountUSD = $req->amount;
    $coin = $req->coin ?? $req->coin_to;
    $currency = $coin;
    $coinArr = DB::table('currency')->whereIn('Currency_ID', [3,4,6,7,8,11])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    if(!isset($coinArr[$coin])){
      return $this->response(200, [], 'Coin invalid!', [], false);
    }
    $symbol = $coinArr[$coin];
    if($coin != 5){
      $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
      //$rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    }else{
      $rate = 1;
    }
    //Rút từ ví nào
    //$symbol_to = $req->coin;
    //Balance
    //dd($rate,$coin,$symbol);
    //dd($user->User_ID, $coin);
    $coinBalance = 3;
    if($coin == 8){
      $coinBalance = 8;
    }
    $balance = User::getBalance($user->User_ID,$coinBalance);
    $amount = $amountUSD / $rate;
    $amountFee = $amountUSD * $this->feeWithdraw;
    if($coin == 5){
      //$amountFee += Money::feeGas();
    }else{
      //$amountFee += 0.5;
      //$amountFee = round($amountFee, 6);
    }
    //dd($amountFee,$amountUSD,$amount,$balance,$symbol,$rate);
    if($coin == 8){
      if($amount*$rate < 20){
        return $this->response(200, [], trans('notification.Min_withdraw_EBP_is_20_EUSD'), [], false);
      }
    }
    if(($amountUSD) > $balance){
      return $this->response(200, ['balance'=>$balance], trans('notification.Your_balance_is_not_enough'), [], false);
    }
    //dd($balance,$rate,$amountFee);
    //kiểm tra có lệnh rút nào chưa
    $withdraw = Money::where('Money_MoneyAction', 2)->where('Money_MoneyStatus', 0)->where('Money_User', $user->User_ID)->first();
    if($withdraw){
      return $this->response(200, ['balance'=>$balance], trans('notification.Please_wait_for_the_withdrawal_to_be_approved'), [], false);
    }
    $address = $req->address;
    $confirm = 0;
    $comment = 'Withdraw ' . ($amountUSD*1) . ' EUSD (' .$amount.' '. $symbol.') To Address '.$address;
    $commentTelegram = 'WITHDRAW';
    /*
        if($req->ecosystem){
          $ecosystem = app('App\Http\Controllers\API\ReportController')->ecosystem;
          $feeWithdrawEcosystem = config('coin.'.$symbol.'.WithdrawFeeEcoSystem');
          //dd($ecosystem, $req->ecosystem);
          if(!isset($ecosystem[$req->ecosystem])){
            return $this->response(200, ['balance'=>$balance], trans('notification.Ecosystem_is_wrong'), [], false);
          }
          $amountFee = $amount * ($feeWithdrawEcosystem[$req->ecosystem]/100);
          if($req->ecosystem != 'Out'){
            if($req->ecosystem == 'BO'){
              $userID = $user->User_ID;
              $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
			  $client = new \GuzzleHttp\Client();
              $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform',[
                'query' => [
                  'address' => $address,
                  'amount' => ($amount - $amountFee),
                  'coin' => $coin,
                  'user' => $userID,
                  'key' => $key,
                ]
              ]);
              $dataResponse = json_decode($response->getBody());
              LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
              if($dataResponse->status == true){
                $confirm = 1;
                $comment .= ' (To Exchange)';
                $commentTelegram .= ' (To Exchange)';
              }else{
                return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
              }
            }elseif($req->ecosystem == 'System'){
              $userID = $user->User_ID;
              $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
              $client = new \GuzzleHttp\Client();
              $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform',[
                'query' => [
                  'address' => $address,
                  'amount' => ($amount - $amountFee),
                  'coin' => $coin,
                  'user' => $userID,
                  'key' => $key,
                ]
              ]);
              $dataResponse = json_decode($response->getBody());
              LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
              if($dataResponse->status == true){
                $confirm = 1;
                $comment .= ' (To Eggsbook)';
                $commentTelegram .= ' (To Eggsbook)';
              }else{
                return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
              }
            }else{
              return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
            }
          }
        }else{
          return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
          //if($user->User_Level == 1){
          $userID = $user->User_ID;
          $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
		  $client = new \GuzzleHttp\Client();
          $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform',[
            'query' => [
              'address' => $address,
              'amount' => ($amount - $amountFee),
              'coin' => $coin,
              'user' => $userID,
              'key' => $key,
            ]
          ]);
          $dataResponse = json_decode($response->getBody());
          if($dataResponse->status == true){
            $confirm = 1;
            $comment .= ' (To Exchange)';
            $commentTelegram .= ' (To Exchange)';
          }
          LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
          //}
          if($confirm == 0){
            $userID = $user->User_ID;
            $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
  		    $client = new \GuzzleHttp\Client();
            $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform',[
              'query' => [
                'address' => $address,
                'amount' => ($amount - $amountFee),
                'coin' => $coin,
                'user' => $userID,
                'key' => $key,
              ]
            ]);
            $dataResponse = json_decode($response->getBody());
            if($dataResponse->status == true){
              $confirm = 1;
              $comment .= ' (To Eggsbook)';
              $commentTelegram .= ' (To Eggsbook)';
            }
            LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
          }
        }
        */
    // lưu lịch sử
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$amountUSD+$amountFee,
      'Money_USDTFee' => -$amountFee,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => 2,
      'Money_MoneyStatus' => 1,
      'Money_Address' => $address,
      'Money_Currency' => $coinBalance,
      'Money_CurrentAmount' => ($amountUSD - $amountFee)/$rate,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => $coin,
      'Money_Rate' => $rate,
      'Money_Confirm' => $confirm,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      //'Money_TXID' => md5(time()),
    );
    //dd($arrayInsert);
    $id = Money::insertGetId($arrayInsert);
    // gọi jobs
    //dispatch(new WalletJobs($id, $user->User_ID))->delay(1);
    $message = "$user->User_Email $commentTelegram ".$amount." $symbol\n"
      . "<b>User ID: </b> "
      . "$user->User_ID\n"
      . "<b>Email: </b> "
      . "$user->User_Email\n"
      . "<b>Amount USD: </b> "
      . ($amountUSD - $amountFee)." USD\n"
      . "<b>Amount Coin: </b> "
      . (($amountUSD - $amountFee)/$rate).' '.$symbol."\n"
      . "<b>Address: </b> "
      . $address."\n"
      . "<b>Rate: </b> "
      . $rate."\n"
      . "<b>Submit withdraw Time: </b>\n"
      . date('d-m-Y H:i:s',time());

    dispatch(new SendTelegramJobs($message, -448649753));

    $withdraw = config('utils.action.withdraw');
    LogUser::addLogUser($user->User_ID, $withdraw['action_type'], $withdraw['message'].' '.(float)$amount.' to wallet: '.$address, $req->ip());

    return $this->response(200, ['balance'=>array('main'=>(float)User::getBalance($user->User_ID, 3)), 'wallet'=>$address], trans('notification.you_withdraw',['amount'=>number_format($amount*1, 2),'symbol'=>$symbol ,'address'=>$address]), [], true);

  }

}
