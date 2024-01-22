<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Validator;
use App\Model\User;
use App\Model\Money;
use App\Model\LogUser;
use App\Model\BetHistoryAeSexy;
use Carbon\Carbon;
use DB;
use DateTime;

class AWCController extends Controller
{
  public $config;
  //public $is_maintain = 0;

  public function __construct()
  {
    //$this->middleware('auth:api');
    $this->config = config('urlAWC.ae_sexy');
  }

  public function CreateMember(Request $request){
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      return $this->response(200, [], "Coming soon", [], false);
    }
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'password_confirm' => 'required|same:password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password_confirm.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password_confirm.min' => trans('notification.password_minimum_6_characters'),
      'password_confirm.max' => trans('notification.password_up_to_12_characters '),
      'password_confirm.same' => trans('notification.Confirm_password_is_not_the_same_as_password'),
    ]);
    if(!$user){
      return $this->response(200, [], trans('notification.you_not_login_123bet'), [], false);
    }
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($user->User_AWC_Password != NULL){
      return $this->response(200, [], trans('notification.you_are_already_registered'), [], false);
    }

    $url = $this->config['url'].'/wallet/createMember';
    $betLimit = '{
    "SEXYBCRT":{
    	"LIVE":{"limitId":[260312,260317]}
        }
    }';

    $body = "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&userId=".$user->User_ID."&currency=CNY&betLimit=".urlencode($betLimit)."&language=".$this->config['language']."&userName=".$this->config['prefix'].$user->User_ID;
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $body,
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);

    $err = curl_error($curl);

    curl_close($curl);
    $check= json_decode($response);
    if ($err) {
      return $this->response(200, $err, trans('notification.register_failed'), [], false);
    }
    if($check->status != 0000){
      return $this->response(200, $check->desc , trans('notification.register_failed'), [], false);
    }
    $user->User_AWC_Password = $request->password;
    $user->save();
    return $this->response(200, [], trans('notification.register_success'), [], true);
  }

  public function login(Request $request){
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      return $this->response(200, [], "Coming soon", [], false);
    }
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
    ]);
    if(!$user){
      return $this->response(200, [], trans('notification.you_not_login_123bet'), [], false);
    }
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($user->User_AWC_Password == NULL){
      return $this->response(200, [], trans('notification.you_have_not_registered_an_account_yet'), [], false);
    }

    if($request->password !== $user->User_AWC_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    $url = $this->config['url'].'/wallet/login';
    $urlBetnow = "https://123betnow.net/";

    $body = "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&userId=".$user->User_ID."&currency=CNY&isMobileLogin=&externalURL=".urlencode($urlBetnow)."&gameForbidden=&gameType=&platform=&language=en&betLimit=&autoBetMode=";
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $body,
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $check= json_decode($response);
    //dd($check->desc);
    if ($err) {
      LogUser::addLogUser($user->User_ID, 'login ae-sexy faild', $err ??'Response data false', $request->ip());
      return $this->response(200, $err, trans('notification.login_failed'), [], false);
    } 
    if($check->status != 0000){
      LogUser::addLogUser($user->User_ID, 'login ae-sexy faild', $check->desc ?? 'Response data false', $request->ip());
      return $this->response(200, $check->desc, trans('notification.login_failed'), [], false);
    }

    LogUser::addLogUser($user->User_ID, 'login ae-sexy success', $err ?? 'Response data false', $request->ip());
    return $this->response(200, $check->url , trans('notification.login_success'), [], true);

  }

  public function deposit(Request $request){
    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric|min:50',
      //'amount' => 'required|numeric',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ],[
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.Password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'amount.required' => trans('notification.amount_required'),
      'amount.min' => trans('notification.minimum_amount_50'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      return $this->response(200, [], "Coming soon", [], false);
    }
    //if ($user->User_Level != 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    if ($user->User_AWC_Password == NULL) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    //$etransid = $this->generateRandomString();
    //dd($etransid);
    $userBalance = User::getBalance($user->User_ID);
    if ($userBalance < $request->amount) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    if($request->password !== $user->User_AWC_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }

    $url = $this->config['url'].'/wallet/deposit';
    $txCode = "deposit".time();

    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to awc ' . $request->amount . ' EUSD',
      'Money_MoneyAction' => 89,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );


    $id = Money::insertGetId($arrayInsert);

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&userId=".$user->User_ID."&transferAmount=".$request->amount."&txCode=".$txCode,
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
      $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
      LogUser::addLogUser($user->User_ID, 'Deposit failed awc', $err ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.deposit_failed'), [], false);
    } else {
      $check= json_decode($response);
      if($check->status != 0000){
        $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
        LogUser::addLogUser($user->User_ID, 'Deposit failed awc', $err ?? 'Response data false', $request->ip());
        return $this->response(200, $check->status , trans('notification.deposit_failed'), [], false);
      }

      LogUser::addLogUser($user->User_ID, 'Deposit awc success', $err ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.deposit_success'), [], true);
    }

  }

  public function withdraw(Request $request){
    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ],[
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.Password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'amount.required' => trans('notification.amount_required'),
      'amount.min' => trans('notification.minimum_amount_50'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      return $this->response(200, [], "Coming soon", [], false);
    }
    //if ($user->User_Level != 1) return $this->response(200, [], 'The system is maintained!!!', [], false);
    if ($user->User_AWC_Password == NULL) {
      return $this->response(200, [], trans('notification.please_register!'), [], false);
    }
    if($request->password != $user->User_AWC_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    //dd($this->aginBalance($user->User_ID)*1);
    //dd($this->evoBalance($user->User_ID));
    //dd($this->awcBalance($user->User_ID));
    if($request->amount > $this->awcBalance($user->User_ID)){
      //dd(123);
      return $this->response(200, [], trans('notification.Balance_awc_is_not_enough'), [], false);
    }
    $txCode = "withdraw".time();
    $url = $this->config['url'].'/wallet/withdraw';
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&userId=".$user->User_ID."&txCode=".$txCode."&withdrawType=0&transferAmount=".$request->amount,
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      LogUser::addLogUser($user->User_ID, 'Withdraw failed awc', $err, $request->ip());
      return $this->response(200, $err, trans('notification.withdraw_failed'), [], false);
    } 

    $check = json_decode($response);
    if($check->status != 0000){
      LogUser::addLogUser($user->User_ID, 'Withdraw failed awc', $check->status , $request->ip());
      return $this->response(200, $check->desc, trans('notification.withdraw_failed'), [], false);
    }
    //////////////////// CHUYEN TIEN VAO  /////////////////   

    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => $request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Withdraw from awc with ' . $request->amount . ' EUSD',
      'Money_MoneyAction' => 90,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
    );
    Money::insert($arrayInsert);
    LogUser::addLogUser($user->User_ID, 'Withdraw agin success', 'withraw', $request->ip());
    return $this->response(200, [], trans('notification.withdraw_success'), [], true);
  }
  public function awcBalance($user){
    $user_data = User::find($user);
    if ($user_data->User_AWC_Password == NULL) {
      $balance = 0;
      return $balance;
    }
    $url = $this->config['url'].'/wallet/getBalance';
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&userIds=".$user,

      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $check = json_decode($response);
    if ($err) {
      return 0;
    }
    if($check->status != 0000){
      return 0;
    }
    foreach($check->results as $item){
      $data = $item->balance;
    }
    return $data;
  }

  public function listHistoryBest(Request $request){
    $user = User::find($request->user()->User_ID);
    $betHistory = BetHistoryAeSexy::where('userId', $user->User_ID)
      ->select('id','userId', 'winAmount', 'realBetAmount', 'realWinAmount', 'txTime', 'gameName', 'betTime')->paginate(10);
    return $this->response(200,$betHistory);
  }

  public function saveHistoryBest(Request $request){
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $now = new \DateTime();
    if($request->time >= 60 || $request->time < 1){
      dd("Time is illegal");
    }
    if($request->time){
      $start  =date(DATE_ISO8601, mktime(date("H") , date("i") - $request->time, date("s"), date("m")  , date("d"), date("Y")));
    }else{
      $start  =date(DATE_ISO8601, mktime(date("H") - 1 , date("i"), date("s"), date("m")  , date("d"), date("Y")));
    }
    $before = new \DateTime($start);

    //dd($now, $before);

    $dateEnd = urlencode($now->format(DateTime::ATOM));
    $dateStart = urlencode($before->format(DateTime::ATOM));
    //dd($dateEnd, $dateStart);
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $this->config['url']."/fetch/gzip/getTransactionByTxTime",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "cert=".$this->config['cert']."&agentId=".$this->config['agentId']."&startTime=".$dateStart."&endTime=".$dateEnd."&platform=SEXYBCRT&currency=CNY",
      CURLOPT_HTTPHEADER => [
        "content-type: application/x-www-form-urlencoded"
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $results = [];
    if ($err) {
      //echo "cURL Error #:" . $err;
     dd('faild');
    }else{
      $data = json_decode($response);
      //dd($data);
      if(empty($data->transactions)){
        dd('empty');
      }
      if($data->status == 1028){
        dd('Unable to proceed. please try again later');
        //return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
      }
      foreach($data->transactions as $item){
        $check = User::where('User_ID',$item->userId)->first();
        $checkExist = BetHistoryAeSexy::where('platformTxId', $item->platformTxId)->first();
        //dd($check, $checkExist);
        if($check && !$checkExist){
          //dd($item);
          $timeAE = strtotime($item->updateTime);
          $updateTime = date('Y-m-d H:i:s' , $timeAE);
          $time123bet = date( 'Y-m-d H:i:s' ,strtotime ( '-7 hours' ,  $timeAE  ));
          //dd($updateTime , $time123bet);
          $datas = [
            'gameType' => $item->gameType,
            'winAmount' =>$item->winAmount,
            'settleStatus' => $item->settleStatus,
            'realBetAmount'=> $item->realBetAmount,
            'realWinAmount'=> $item->realWinAmount,
            'txTime'=> $item->txTime,
            'updateTime'=> $updateTime,
            'userId'=> $item->userId,
            'betType'=> $item->betType,
            'platform'=> $item->platform,
            'txStatus'=> $item->txStatus,
            'betAmount'=> $item->betAmount,
            'gameName'=> $item->gameName,
            'platformTxId'=> $item->platformTxId,
            'betTime'=> $item->betTime,
            'gameCode'=> $item->gameCode,
            'currency'=> $item->currency,
            'jackpotBetAmount'=> $item->jackpotBetAmount,
            'jackpotWinAmount'=> $item->jackpotWinAmount,
            'turnover'=> $item->turnover,
            'roundId'=> $item->roundId,
            'gameInfo'=> $item->gameInfo,
            'time123bet' => $time123bet,
          ];
          BetHistoryAeSexy::insert($datas);
          //array_push($results, $datas);
        }
      }
      if(empty($results)){
        dd('empty');
        //return $this->response(200, [], 'empty', [], false);
      }
      dd('success');
      //return $this->response(200, [], 'success', [], false);
      //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');
    }
  }

  public function postChangePass(Request $request){
    $user = User::find($request->user()->User_ID);
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12',
      'new_password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'confirm_password' => 'required|same:new_password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'new_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'confirm_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'new_password.required' => trans('notification.password_required'),
      'new_password.min' => trans('notification.password_minimum_6_characters'),
      'new_password.max' => trans('notification.password_up_to_12_characters '),
      'confirm_password.required' => trans('notification.password_required') , 
      'confirm_password.min' => trans('notification.password_minimum_6_characters'),
      'confirm_password.max' => trans('notification.password_up_to_12_characters '),
      'confirm_password.same' => trans('notification.confirm_password_must_be_the_same_as_the_old_password'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if(!$user){
      return $this->response(200, [], trans('notification.you_not_login_123bet'), [], false);
    }
    if ($user->User_AWC_Password == NULL) {
      return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
    }
    if ($user->User_AWC_Password !== $request->password) {
      return $this->response(200, [], trans('notification.old_password_is_incorrect'), [], false);
    }
    if ($request->password === $request->new_password) {
      return $this->response(200, [], trans('notification.New_password_and_old_password_cannot_be_the_same!'), [], false);
    }
    $user->User_AWC_Password = $request->new_password;
    $user->save();
    LogUser::addLogUser($user->User_ID, 'Change password AWC', 'Response data true', $request->ip());
    return $this->response(200, [], 'Change password success!');
  }

}
