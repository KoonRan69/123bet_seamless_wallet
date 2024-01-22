<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\System\CoinbaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

use Image;
use PragmaRX\Google2FA\Google2FA;

use Mail;
use GuzzleHttp\Client;
use App\Model\Wallet;
use App\Model\GoogleAuth;
use App\Model\User;
use App\Model\userBalance;
use App\Model\Money;

use App\Jobs\WalletJobs;
use App\Model\Investment;
use App\Model\LogUser;
use App\Model\Profile;
use App\Jobs\SendTelegramJobs;
use App\Jobs\SendMailJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Walletcontroller extends Controller
{

  public $feeWithdraw = 0.003;
  public $feeWithdrawEcoSystem = 0.02;
  public $feeTransfer = 0;
  public $feeSwap = 0;
  public $addressDepositEBP;
  protected $keyPlatform = ['032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC' => 'Eggsbook', 'BOoQJE0OTepPFhpEX8NlWyOGVbbeYone6i8ADWlDRZcTW1Pkhu' => 'Exchange'];
  protected $ipAllow = ['18.140.148.150'];

  public function __construct()
  {
    //$this->middleware('auth:api');
    $this->feeWithdraw = config('coin.EUSD.WithdrawFee');
    $this->feeWithdrawEcoSystem = config('coin.EUSD.WithdrawFeeEcoSystem');
    $this->feeTransfer = config('coin.EUSD.TransferFee');
    $this->addressDepositEBP = config('coin.EBP.addressDeposit');
  }
  public function sendMailOTP(Request $req){
    $validator = Validator::make($req->all(), [
      'otp_type' => 'required',
    ]);
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($req->otp_type != 'otp_w' && $req->otp_type != 'transfer' && $req->otp_type != 'lucky' && $req->otp_type != 'evo' && $req->otp_type != 'sbobet' && $req->otp_type != "order_deposit") return $this->response(200, [], 'Error!', [], false);
    include(app_path() . '/functions/xxtea.php');
    $user = Auth::user();

    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 0){
      return $this->response(200, [], 'You have not activated email yet!', [], false);
    }

    $key = 'CD17TT2AI';
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    //checkotp đủ 60s chưa
    if($req->otp_type == 'otp_w') $tokenOTP = $user->otp_w;
    if($req->otp_type == 'transfer') $tokenOTP = $user->otp_transfer;
    if($req->otp_type == 'lucky') $tokenOTP = $user->otp_lucky;
    if($req->otp_type == 'evo') $tokenOTP = $user->otp_evo;
    if($req->otp_type == 'sbobet' || $req->otp_type == 'sbobet_infinity' || $req->otp_type == 'sbobet_solar') $tokenOTP = $user->otp_sbobet;
    if($req->otp_type == 'order_deposit') $tokenOTP = $user->otp_order_deposit;

    if($tokenOTP){
      $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
      if(strtotime('+5 minutes', $responseToken['time']) > time()) return $this->response(200, [], 'New code will be sent in 5 minutes', [], false);
    }

    $otp = substr(str_shuffle($permitted_chars), 0, 10);
    $time = time();
    $dataToken = array('otp'=>$otp,'user_id' => $user->User_ID , 'time' => $time); //Check mã chỉ có hiệu lực trong 1 phút
    //Mã hóa
    $token = base64_encode(xxtea_encrypt(json_encode($dataToken), $key));
    //$content = $response->getBody()->getContents();
    //$data = json_decode($content);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($token), $key), true);

    if($req->otp_type == 'otp_w') $user->otp_w = $token;
    if($req->otp_type == 'transfer') $user->otp_transfer = $token;
    if($req->otp_type == 'lucky') $user->otp_lucky = $token;
    if($req->otp_type == 'evo') $user->otp_evo = $token;
    if($req->otp_type == 'sbobet' || $req->otp_type == 'sbobet_infinity' || $req->otp_type == 'sbobet_solar') $user->otp_sbobet = $token;
    if($req->otp_type == 'order_deposit') $user->otp_order_deposit = $token;
    $user->save();

    $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email,'OTP'=>$otp, 'token' => $token);
    //Job
    dispatch(new SendMailJobs('OTP', $data, 'Send OTP!', $user->User_ID));
    return $this->response(200, ['time'=>date('Y-m-d H:i:s',$time)], 'OTP sent successfully, please check your mailbox!', [], true);
  }

  public function postWithdrawMemberAdd(Request $req)
  {
    $user = Auth::user();
    if($user->User_Level != 1){
      //return $this->response(200, [], 'function has been maintained', [], false);
    }

    $validator = Validator::make($req->all(), [
      'userid' => 'required|',
      'amount' => 'required|numeric|min:10',
      'coin' => 'required|numeric|in:3,8',
      'otp' => 'required|',
    ],[
      'userid.required' => "User ID required!" ,
      'amount.required' => trans('notification.amount_required') ,
      'coin.required' => trans('notification.coin_required') ,
      'email.email' => trans('notification.User_email_is_not_in_the_correct_email_format') ,
      'amount.numeric' => trans('notification.The_amount_must_be_a_number') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 0){
      return $this->response(200, [], 'You have not activated email yet!', [], false);
    }

    if($user->User_ID == $req->userid){
      return $this->response(200, [], trans('notification.not_sent_to_self'), [], false);
    }
    $check_custom = $user->User_Level;
    if ($check_custom == 4 || $check_custom == 5) {
      return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }
    if ($user->User_Lock_Deposit_MemberAdd == 1) return $this->response(200, [], trans('notification.Cant_use_this_function!'), [], false);


    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    $tokenOTP = $user->otp_transfer;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    $fromUser = User::where('User_ID', $req->userid)->first();
    if (!$fromUser) {
      return $this->response(200, [], trans('notification.User_not_exist'), [], false);
    }
    if ($fromUser->User_Level != $user->User_Level) {
      return $this->response(200, [], "Cant withdraw to another level", [], false);
    }

    if($fromUser->User_Parent_AddMember != $user->User_ID){
      return $this->response(200, [], "You are not the person who added member $fromUser->User_ID", [], false);
    }

    $coin = $req->coin;
    $arrCurrency = DB::table('currency')->whereIn('Currency_ID', [3, 8])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    $symbol = $arrCurrency[$coin];


    $balance = User::getBalance($fromUser->User_ID, $coin);
    $amount = abs($req->amount);
    if ($amount > $balance) {
      return $this->response(200, [], "Member balance $fromUser->User_ID is not enough", [], false);
    }

    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Receive (Withdraw) ' . ($amount * 1) . ' ' . $symbol . ' from User: ' . $fromUser->User_ID,
        'Money_MoneyAction' => 98,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $fromUser->User_ID,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw ' . ($amount * 1) . ' ' . $symbol . ' to User: ' . $user->User_ID,
        'Money_MoneyAction' => 98,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    $user->otp_transfer = '';
    $user->save();
    $insert = Money::insert($insertArray);
    if ($insert) {
      return $this->response(200, ['balance' => [$symbol => User::getBalance($user->User_ID, $coin)]], "Success withdraw from User: $fromUser->User_ID" , [] , true);

    }
  }

  public function postDepositMemberAdd(Request $req)
  {
    $user = Auth::user();
    if($user->User_Level != 1){
      //return $this->response(200, [], 'function has been maintained', [], false);
    }

    $validator = Validator::make($req->all(), [
      'userid' => 'required|',
      'amount' => 'required|numeric|min:10',
      'coin' => 'required|numeric|in:3,8',
      'otp' => 'required|',
    ],[
      'userid.required' => "User ID required!" ,
      'amount.required' => trans('notification.amount_required') ,
      'coin.required' => trans('notification.coin_required') ,
      'email.email' => trans('notification.User_email_is_not_in_the_correct_email_format') ,
      'amount.numeric' => trans('notification.The_amount_must_be_a_number') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 0){
      return $this->response(200, [], 'You have not activated email yet!', [], false);
    }

    if($user->User_ID == $req->userid){
      return $this->response(200, [], trans('notification.not_sent_to_self'), [], false);
    }
    $check_custom = $user->User_Level;
    if ($check_custom == 4 || $check_custom == 5) {
      return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }
    if ($user->User_Lock_Deposit_MemberAdd == 1) return $this->response(200, [], trans('notification.Cant_use_this_function!'), [], false);


    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    $tokenOTP = $user->otp_transfer;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    $toUser = User::where('User_ID', $req->userid)->first();
    if (!$toUser) {
      return $this->response(200, [], trans('notification.User_not_exist'), [], false);
    }
    if ($toUser->User_Level != $user->User_Level) {
      return $this->response(200, [], "Cant deposit to another level", [], false);
    }

    if($toUser->User_Parent_AddMember != $user->User_ID){
      return $this->response(200, [], "You are not the person who added member $toUser->User_ID", [], false);
    }

    $coin = $req->coin;
    $arrCurrency = DB::table('currency')->whereIn('Currency_ID', [3, 8])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    $symbol = $arrCurrency[$coin];
    $balance = User::getBalance($user->User_ID, $coin);
    $amount = abs($req->amount);
    if ($amount > $balance) {
      return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    }

    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Deposit ' . ($amount * 1) . ' ' . $symbol . ' to User: ' . $toUser->User_ID,
        'Money_MoneyAction' => 97,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $toUser->User_ID,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Receive (Deposit) ' . ($amount * 1) . ' ' . $symbol . ' from User: ' . $user->User_ID,
        'Money_MoneyAction' => 97,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    $user->otp_transfer = '';
    $user->save();
    $insert = Money::insert($insertArray);
    if ($insert) {
      return $this->response(200, ['balance' => [$symbol => User::getBalance($user->User_ID, $coin)]], "Success deposit to User: $toUser->User_ID" , [] , true);

    }
  }

  public function getDepositPlatform(Request $req)
  {

    //if($project->ip != '0.0.0.0'){
    //if( array_search($req->ip(), $this->ipAllow) === false ){
    //return $this->response(200, ['ip'=>$req->ip()], 'IP incorrect!', [], false);
    //return response()->json(['status' => false, , 'message'=>'IP incorrect!']);
    //}
    //}

    $validator = Validator::make($req->all(), [
      'address' => 'required',
      'amount' => 'required|numeric|min:0',
      'coin' => 'required|exists:currency,Currency_ID',
      'user' => 'required|numeric|digits:6'
    ],[
      'address.required' => trans('notification.address_requaired'),
      'amount.required' => trans('notification.amount_required'),
      'amount.min:0' => trans('notification.Amount_must_be_greater_than_0'),
      'coin.required' => trans('notification.coin_required!'),
      'user.required' => trans('notification.Password_required'),
      'coin.exists' => trans('notification.Coin_not_exits'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if (!isset($this->keyPlatform[$req->key])) {
      return $this->response(200, [], 'Error!', [], false);
    }
    $system = $this->keyPlatform[$req->key];
    $address = $req->address;
    $amount = $req->amount;
    $coin = $req->coin;

    $coinBalance = 3;
    if ($coin == 8) {
      $coinBalance = $coin;
    }

    $userEggsbook = $req->user;
    $user = User::join('address', 'Address_User', 'User_ID')->where('Address_Address', $address)->where('Address_Currency', $coin)->first();
    if (!$user) {
      return $this->response(200, [], trans('notification.Address_is_not_found'), [], false);
    }
    $symbol = DB::table('currency')->where('Currency_ID', $coin)->value('Currency_Symbol');

    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    $money = new Money();
    $money->Money_User = $user->User_ID;
    $money->Money_USDT = $amount;
    $money->Money_Time = time();
    $money->Money_Comment = 'Deposit From ' . $system . ' User ID: ' . $userEggsbook . ' ' . ($amount + 0) . ' ' . $symbol;
    $money->Money_Currency = $coinBalance;
    $money->Money_CurrencyFrom = $coin;
    $money->Money_MoneyAction = 1;
    $money->Money_Address = 'Deposit From ' . $system . ' User ID: ' . $userEggsbook . ' (' . date('Y-m-d H:i:s') . ')';
    $money->Money_CurrentAmount = $amount;
    $money->Money_Rate = $rate;
    $money->Money_MoneyStatus = 1;
    $money->save();
    //bonus Deposit
    Money::commissionDeposit($user);
    $message = "$user->User_Email Deposit $amount $symbol From $system\n"
      . "<b>User ID: </b> "
      . "$user->User_ID\n"
      . "<b>Email: </b> "
      . "$user->User_Email\n"
      . "<b>Amount: </b> "
      . $amount . " $symbol\n"
      . "<b>Amount USD: </b> "
      . ($amount*$rate) . " USDT\n"
      . "<b>Rate: </b> "
      . "$$rate \n"
      . "<b>Submit Deposit Time: </b>\n"
      . date('d-m-Y H:i:s', time());

    dispatch(new SendTelegramJobs($message, -485635858));
    return $this->response(200, [], trans('notification.Success'), [], true);
  }

  public function postSwap(Request $req)
  {
    $user = User::where('User_ID', Auth::user()->User_ID)->first();
    if($user->User_Level != 1){
      return $this->response(200, [], 'function has been maintained', [], false);
    }

    $check_custom = $user->User_Level;
    if ($check_custom == 4) {
      //return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Your account can\'t use this function!"]);
    }

    $validator = Validator::make($req->all(), [
      'coin_from' => 'required|in:3,8',
      'amount' => 'required|numeric|min:0',
      'coin_to' => 'required|in:3,8',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if ($user->User_Lock_Swap) return $this->response(200, [], trans('notification.Cant_use_this_function!'), [], false);
    $arr_from_wallet = [
      3 => 'EUSD',
      8 => 'EBP',
    ];
    $coin_from = $req->coin_from;
    $coin_to = $req->coin_to;
    if (($coin_from == $coin_to)) {
      return $this->response(200, [], trans('notification.Currency_Error!'), [], false);
    }
    $symbolFrom = $arr_from_wallet[$coin_from];
    $symbolTo = $arr_from_wallet[$coin_to];
    $amount_from = $req->amount;
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
    //Balance
    $balance = User::getBalance($user->User_ID, $coin_from);
    $fee = $this->feeSwap;
    if ($amount_from > $balance) {
      return $this->response(200, [], trans('notification.Your_balance_is_not_enough!'), [], false);
    }

    $amount_to = $amount_from * $rate[$symbolFrom] / $rate[$symbolTo];
    $amountFee = $amount_to * $fee;
    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$amount_from,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Swap Coin From ' . $symbolFrom . ' To ' . $symbolTo,
        'Money_MoneyAction' => 15,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin_from,
        'Money_CurrentAmount' => $amount_from,
        'Money_CurrencyFrom' => $coin_from,
        'Money_CurrencyTo' => $coin_to,
        'Money_Rate' => $rate[$symbolFrom],
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $amount_to,
        'Money_USDTFee' => -($amountFee),
        'Money_Time' => time(),
        'Money_Comment' => 'Swap Coin From ' . $symbolFrom,
        'Money_MoneyAction' => 15,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin_to,
        'Money_CurrentAmount' => $amount_to - $amountFee,
        'Money_CurrencyFrom' => $coin_from,
        'Money_CurrencyTo' => $coin_to,
        'Money_Rate' => $rate[$symbolTo],
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    $insert = Money::insert($insertArray);
    //update Balance
    $balance = [];
    $balance[$symbolFrom] = User::getBalance($user->User_ID, $coin_from);
    $balance[$symbolTo] = User::getBalance($user->User_ID, $coin_to);
    return $this->response(200, ['balance' => $balance], "Swap From $symbolFrom To $symbolTo Success!", [], true);
  }
  public function postTransfer(Request $req)
  {
    $user = Auth::user();
    if($user->User_Level != 1){
      //return $this->response(200, [], 'function has been maintained', [], false);
    }

    $validator = Validator::make($req->all(), [
      'email' => 'required|email|nullable',
      'amount' => 'required|numeric|min:1|nullable',
      'coin' => 'required|numeric|in:3,8',
      'otp' => 'required|',
    ],[
      'email.required' => trans('notification.email_required') ,
      'amount.required' => trans('notification.amount_required') ,
      'coin.required' => trans('notification.coin_required') ,
      'email.email' => trans('notification.User_email_is_not_in_the_correct_email_format') ,
      'amount.numeric' => trans('notification.The_amount_must_be_a_number') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 0){
      return $this->response(200, [], 'You have not activated email yet!', [], false);
    }

    if($user->User_Email == $req->email){
      return $this->response(200, [], trans('notification.not_sent_to_self'), [], false);
    }
    $check_custom = $user->User_Level;
    if ($check_custom == 4 || $check_custom == 5) {
      return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }
    if ($user->User_Lock_Transfer) return $this->response(200, [], trans('notification.Cant_use_this_function!'), [], false);


    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    $tokenOTP = $user->otp_transfer;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    $toUser = User::where('User_Email', $req->email)->first();
    if (!$toUser) {
      return $this->response(200, [], trans('notification.User_not_exist'), [], false);
    }
    if ($toUser->User_Level != $user->User_Level) {
      return $this->response(200, [], trans('notification.Cant_transfer_to_another_level!'), [], false);
    }
    //Bảo mật
    $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
    if (!$checkProfile || $checkProfile->Profile_Status != 1) {
      //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
    }
    $coin = $req->coin;
    $arrCurrency = DB::table('currency')->whereIn('Currency_ID', [3, 8])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    $symbol = $arrCurrency[$coin];
    $balance = User::getBalance($user->User_ID, $coin);
    $amount = abs($req->amount);
    if ($amount > $balance) {
      return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
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
    //$password = $req->password;
    //if (Hash::check($req->password, $user->User_Password)) {
    $insertArray = array(
      array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Transfer ' . ($amount * 1) . ' ' . $symbol . ' to User: ' . $toUser->User_ID,
        'Money_MoneyAction' => 7,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
      array(
        'Money_User' => $toUser->User_ID,
        'Money_USDT' => $amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Receive ' . ($amount * 1) . ' ' . $symbol . ' from User: ' . $user->User_ID,
        'Money_MoneyAction' => 7,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $amount,
        'Money_CurrencyFrom' => $coin,
        'Money_CurrencyTo' => $coin,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      ),
    );
    $user->otp_transfer = '';
    $user->save();
    $insert = Money::insert($insertArray);
    if ($insert) {
      return $this->response(200, ['balance' => [$symbol => User::getBalance($user->User_ID, $coin)]], trans('notification.Success_tranfer',['amount' => $amount * 1 ,'symbol' => $symbol ,'toUser' => $toUser->User_ID ]), [] , true);

    }
    //}
    //else{
    //return $this->response(200, 'Wrong password!', '', [], false);
    //}

  }

  public function getDeposit(Request $req)
  {
    $addressArray = array(1 => 'BTC', 2 => 'ETH', 3 => 'EUSD', 4 => 'DP-NFT', 5 => 'USDT', 6 => 'USDT', 7 => 'HBG', 8 => 'EBP', 11 => 'USDT', 12 => 'SOL', 13 => 'C98', 14 => 'ADA', 15 => 'TRX', 16 => 'BNB' );

    $user = Auth::user();

    // kiểm tra user này có ví chưa
    $coin = $req->coin;

    if (!isset($addressArray[$coin])) {
      return $this->response(200, [], 'Coin not exits', [], false);
    }
    //        return $this->response(200, [], 'This function is under maintenance!', [], false);
    if ($coin == 8) {
      //mới
      if ($user->User_Level == 1) {
        $address = Wallet::checkWallet($user->User_ID, $coin);
        if ($address) {
          $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $address->Address_Address . '&choe=UTF-8';

          $returnData = array('symbol' => $addressArray[$coin], 'address' => $address->Address_Address, 'Qr' => $Qr);
          return $this->response(200, $returnData, '', [], true);
        }
        $createAddressEBP = app('App\Http\Controllers\API\TestDepositEBPController')->createAddressEBPNew($coin, $user->User_ID);
        if ($createAddressEBP) {
          // lưu vào db
          $arrayInsert = array(
            'Address_Currency' => $coin,
            'Address_Address' => $createAddressEBP['address'],
            'Address_User' => $user->User_ID,
            'Address_PrivateKey' => null,
            'Address_HexAddress' => null,
            'Address_CreateAt' => date('Y-m-d H:i:s'),
            'Address_UpdateAt' => date('Y-m-d H:i:s'),
            'Address_IsUse' => 1,
            'Address_Comment' => null
          );
          Wallet::insert($arrayInsert);
          if ($coin == 1) {
            $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . $createAddressEBP['address'] . '&choe=UTF-8';
          } else {
            $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddressEBP['address'] . '&choe=UTF-8';
          }
          $returnData = array('symbol' => $addressArray[$coin], 'address' => $createAddressEBP['address'], 'Qr' => $Qr);
          return $this->response(200, $returnData, '', [], true);
        }
      }


      //cũ
      $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $this->addressDepositEBP . '&choe=UTF-8';
      $returnData = array('symbol' => $addressArray[$coin], 'address' => $this->addressDepositEBP, 'Qr' => $Qr);
      return $this->response(200, $returnData, '', [], true);
    } else {
      $address = Wallet::checkWallet($user->User_ID, $coin);

      if ($address) {
        if ($coin == 1) {
          $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . $address->Address_Address . '&choe=UTF-8';
        } else {
          $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $address->Address_Address . '&choe=UTF-8';
        }
        $returnData = array('symbol' => $addressArray[$coin], 'address' => $address->Address_Address, 'Qr' => $Qr);
        return $this->response(200, $returnData, '', [], true);
      }
    }
    $createAddress = app('App\Http\Controllers\System\CoinbaseController')->createAddress($coin, $user->User_ID);
    if ($createAddress) {
      // lưu vào db
      $arrayInsert = array(
        'Address_Currency' => $coin,
        'Address_Address' => $createAddress['address'],
        'Address_User' => $user->User_ID,
        'Address_PrivateKey' => null,
        'Address_HexAddress' => null,
        'Address_CreateAt' => date('Y-m-d H:i:s'),
        'Address_UpdateAt' => date('Y-m-d H:i:s'),
        'Address_IsUse' => 1,
        'Address_Comment' => null
      );
      Wallet::insert($arrayInsert);
      if ($coin == 1) {
        $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=bitcoin:' . $createAddress['address'] . '&choe=UTF-8';
      } else {
        $Qr = 'https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=' . $createAddress['address'] . '&choe=UTF-8';
      }
      $returnData = array('symbol' => $addressArray[$coin], 'address' => $createAddress['address'], 'Qr' => $Qr);
      return $this->response(200, $returnData, '', [], true);
    }

    $createAddress = app('App\Http\Controllers\System\CoinbaseController')->createAddress($coin, $user->User_ID);
    if ($createAddress) {
      // lưu vào db
      $arrayInsert = array(
        'Address_Currency' => $coin,
        'Address_Address' => $createAddress['address'],
        'Address_User' => $user->User_ID,
        'Address_PrivateKey' => null,
        'Address_HexAddress' => null,
        'Address_CreateAt' => date('Y-m-d H:i:s'),
        'Address_UpdateAt' => date('Y-m-d H:i:s'),
        'Address_IsUse' => 1,
        'Address_Comment' => null
      );
      Wallet::insert($arrayInsert);
      return $this->response(200, $createAddress, '', [], false);
    }
  }


  public function postWithdraw(Request $req)
  {
    $user = Auth::user();
    if($user->User_Level != 1){
      //return $this->response(200, [], 'function has been maintained', [], false);
    }
    $validator = Validator::make($req->all(), [
      'address' => 'required|string|min:1|nullable',
      'amount' => 'required|numeric|min:20|nullable',
      'otp' => 'required|',
      //'coin' => 'required|numeric|in:3,5,6,8',
    ],[
      'address.required' => trans('notification.address_requaired') ,
      'amount.required' => trans('notification.amount_required') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 0){
      return $this->response(200, [], 'You have not activated email yet!', [], false);
    }

    $check_custom = $user->User_Level;
    if ($check_custom == 4 || $check_custom == 5) {
      return $this->response(200, [], trans('notification.Your_account_cant_use_this_function!'), [], false);
    }


    /*$balanBonus = User::getBalance($user->User_ID, 10);
    if($balanBonus > 0){
      $insertArrayBonus[] = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$balanBonus,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Reset balance bonus '.($balanBonus*1).' EUSD (balance bonus: '.$balanBonus.')',
        'Money_MoneyAction' => 78,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 10,
        'Money_CurrentAmount' => $balanBonus,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1
      );
      Money::insert($insertArrayBonus);
    }*/


    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    $tokenOTP = $user->otp_w;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    if($user->User_Lock_Withdraw == 1) return $this->response(200, [], 'Can\'t use this function!', [], false);
    //Bảo mật
    $checkProfile = Profile::where('Profile_User', $user->User_ID)->first();
    if (!$checkProfile || $checkProfile->Profile_Status != 1) {
      //return $this->response(200, [], 'Your Profile KYC Is Unverify', [], false);
    }
    if($user->User_WalletAddress == null){
      //return $this->response(200, [], trans('notification.Please_update_your_wallet_address!'), [], false);
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
    $coin = $req->coin ?? $req->coin_to;
    $coinArr = DB::table('currency')->whereIn('Currency_ID', [3, 4, 7, 8, 11])->pluck('Currency_Symbol', 'Currency_ID')->toArray();
    if (!isset($coinArr[$coin])) {
      return $this->response(200, [], trans('notification.Coin_invalid'), [], false);
    }
    $symbol = $coinArr[$coin];
    if ($coin != 5) {
      $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy($symbol);
    } else {
      $rate = 1;
    }
    //Rút từ ví nào
    $symbol_to = $req->coin;
    //Balance
    $balance = User::getBalance($user->User_ID, 3);
    $amountFee = $amount * ($this->feeWithdraw / 100);
    if ($coin == 5) {
      $amountFee += Money::feeGas();
    } else {
      $amountFee += 0.5;
      //$amountFee = round($amountFee, 6);
    }
    if (($amount) > $balance) {
      return $this->response(200, ['balance' => $balance], trans('notification.Your_balance_is_not_enough'), [], false);
    }
    //kiểm tra có lệnh rút nào chưa
    $withdraw = Money::where('Money_MoneyAction', 2)->where('Money_MoneyStatus', 0)->where('Money_User', $user->User_ID)->first();
    if ($withdraw) {
      return $this->response(200, ['balance' => $balance], trans('notification.Please_wait_for_the_withdrawal_to_be_approved'), [], false);
    }

    //kiểm tra 1 ngày chỉ có 3 lệnh rút
    $three_withdraw = Money::where('Money_MoneyAction', 2)->whereIn('Money_MoneyStatus', [0,1])->where('Money_User', $user->User_ID)
      ->where('Money_Time', '>=', strtotime(date('Y-m-d 00:00:00')))
      ->where('Money_Time', '<', strtotime(date('Y-m-d 23:59:59')))->get();
    if (count($three_withdraw)>=3) {
      return $this->response(200, [], 'You can only withdraw up to 3 times/day', [], false);
    }

    $address = $req->address;
    $confirm = 0;
    $comment = 'Withdraw ' . ($amount * 1) . ' ' . $symbol . ' Address ' . $address;
    $commentTelegram = 'WITHDRAW';
    if ($req->ecosystem) {
      $ecosystem = app('App\Http\Controllers\API\ReportController')->ecosystem;
      $feeWithdrawEcosystem = config('coin.' . $symbol . '.WithdrawFeeEcoSystem');
      //dd($ecosystem, $req->ecosystem);
      if (!isset($ecosystem[$req->ecosystem])) {
        return $this->response(200, ['balance' => $balance], trans('notification.Ecosystem_is_wrong'), [], false);
      }
      $amountFee = $amount * ($feeWithdrawEcosystem[$req->ecosystem] / 100);
      if ($req->ecosystem != 'Out') {
        if ($req->ecosystem == 'BO') {
          $userID = $user->User_ID;
          $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
          $client = new Client();
          $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform', [
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
          if ($dataResponse->status == true) {
            $confirm = 1;
            $comment .= ' (To Exchange)';
            $commentTelegram .= ' (To Exchange)';
          } else {
            return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
          }
        } elseif ($req->ecosystem == 'System') {
          $userID = $user->User_ID;
          $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
          $client = new Client();
          $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform', [
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
          if ($dataResponse->status == true) {
            $confirm = 1;
            $comment .= ' (To Eggsbook)';
            $commentTelegram .= ' (To Eggsbook)';
          } else {
            return $this->response(200, [], trans('notification.Address_is_not_found_in_Ecosystem'), [], false);
          }
        } else {
          return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
        }
      }
    } else {
      return $this->response(200, [], trans('notification.Ecosystem_is_wrong'), [], false);
      //if($user->User_Level == 1){
      $userID = $user->User_ID;
      $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
      $client = new Client();
      $response = $client->get('abcxyz.eggsbook.com/api/v1/check-deposit-platform', [
        'query' => [
          'address' => $address,
          'amount' => ($amount - $amountFee),
          'coin' => $coin,
          'user' => $userID,
          'key' => $key,
        ]
      ]);
      $dataResponse = json_decode($response->getBody());
      if ($dataResponse->status == true) {
        $confirm = 1;
        $comment .= ' (To Exchange)';
        $commentTelegram .= ' (To Exchange)';
      }
      LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
      //}
      if ($confirm == 0) {
        $userID = $user->User_ID;
        $key = '032417RrrwNsMxnAX127ADonnrBmlxDH5LSXnfkZvzlwFPN9yC';
        $client = new Client();
        $response = $client->get('api.eggsbook.com/api/v1/check-deposit-platform', [
          'query' => [
            'address' => $address,
            'amount' => ($amount - $amountFee),
            'coin' => $coin,
            'user' => $userID,
            'key' => $key,
          ]
        ]);
        $dataResponse = json_decode($response->getBody());
        if ($dataResponse->status == true) {
          $confirm = 1;
          $comment .= ' (To Eggsbook)';
          $commentTelegram .= ' (To Eggsbook)';
        }
        LogUser::addLogUser($user->User_ID, 'Withdraw To Platform', $dataResponse->message, $req->ip(), 10);
      }
    }
    // lưu lịch sử
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$amount + $amountFee,
      'Money_USDTFee' => -$amountFee,
      'Money_Time' => time(),
      'Money_Comment' => $comment,
      'Money_MoneyAction' => 2,
      'Money_MoneyStatus' => 1,
      'Money_Address' => $address,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => ($amount - $amountFee),
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => $coin,
      'Money_Rate' => $rate,
      'Money_Confirm' => $confirm,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      //'Money_TXID' => md5(time()),
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
        . "<b>Address: </b> "
        . $address . "\n"
        . "<b>Rate: </b> "
        . "$ 1 \n"
        . "<b>Submit withdraw Time: </b>\n"
        . date('d-m-Y H:i:s', time());

      dispatch(new SendTelegramJobs($message, -448649753));
    }
    //dispatch(new WalletJobs($id, $user->User_ID))->delay(1);


    $withdraw = config('utils.action.withdraw');
    LogUser::addLogUser($user->User_ID, $withdraw['action_type'], $withdraw['message'] . ' ' . (float)$amount . ' to wallet: ' . $address, $req->ip());

    $user->otp_w = '';
    $user->save();

    return $this->response(200, ['balance' => array('main' => (float)User::getBalance($user->User_ID, 3)), 'wallet' => $address], 'You withdraw ' . ($amount * 1) . ' ' . $symbol . ' to ' . $address, [], true);


  }

}
