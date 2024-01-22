<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Validator;
use App\Model\User;
use App\Model\Money;
use App\Model\LogUser;
use App\Model\BetHistoryWM;
use App\Model\BetHistoryAgin;
use Carbon\Carbon;
use DB;

class AGINController extends Controller
{
  public $config;
  public $password_agin = 'KoonRan69';
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['saveHistoryBest']]);
    $this->config = config('urlAgin.agin_api');

  }
  public function listmember(){
    $getUser = User::where('User_Agin',1)->select('User_ID','User_Email','User_Parent','User_Tree')->get();
    $prefix = $this->config['prefix'];
    return $this->response(200, ['list'=>$getUser,'prefix'=>$prefix]);
  }
  public function postChangePass(Request $request){
    // return $this->response(200, [], 'function under maintenance!', [], false);
    $user = User::find($request->user()->User_ID);
    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      'password' => 'required|min:6',
      'new_password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'confirm_password' => 'required|same:new_password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      //'new_password.regex' => 'Your password must be at least 6 digits and must be in uppercase and lowercase letters as well as have at least 1 number!',
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
    if ($user->User_Agin != 1) {
      return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
    }

    if ($user->User_Agin_Password !== $request->password) {
      return $this->response(200, [], trans('notification.old_password_is_incorrect'), [], false);
    }
    if ($request->password === $request->new_password) {
      return $this->response(200, [], trans('notification.New_password_and_old_password_cannot_be_the_same!'), [], false);
    }
    $user->User_Agin_Password = $request->new_password;
    $user->save();
    LogUser::addLogUser($user->User_ID, 'Change password agin', 'Response data true', $request->ip());
    return $this->response(200, [], trans('notification.change_password_agin_sportBook_successful'));
  }
  public function listHistoryBest(Request $request){
    $user = User::find($request->user()->User_ID);
    $betHistory = BetHistoryAgin::where('userid', $user->User_ID)
      ->select('id','userid','username','billno','productid','time_123betnow','account','cus_account')->paginate(10);
    return $this->response(200,$betHistory);
  }
  //đang chạy cron lưu lịch sử bet
  public function saveHistoryBest(){
    //////////////////////////// GET WIN LOST //////////////////////////////////
    date_default_timezone_set("America/Anguilla");
    $cagent = 'JT9'; 
    $t = strtotime('2022-02-15 00:00:49'); 
    //dd(date('Y-m-d H:i:s',$t));
    $t1 = strtotime('-1 minutes',$t); 
    $t2 = strtotime('-10 minutes',$t1); 
    $startdate = date('Y-m-d H:i:s',$t2); 
    $enddate = date('Y-m-d H:i:s',$t1); 
    $timeconvert = 11; //123betnow(GMT 0) trước múi giờ GM-4 là 4 tiếng lúc lưu lịch sử cần cộng 4 tiếng vào
    $key = md5($cagent.$startdate.$enddate.'6377A2D3DC3F79BFA3684DC886F28365');
    $url = 'http://jde6t9.gdcapi.com:3333/getagsportorders_ex.xml?startdate='.$startdate.'&enddate='.$enddate.'&cagent='.$cagent.'&key='.$key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    $xml = simplexml_load_string($data);
    dd($xml);
    $betHistory = BetHistoryAgin::where('create_date', '>=', strtotime('-1 days'))->pluck('billno')->toArray();
    $results = [];

    if (count($results) > 0) BetHistoryAgin::insert($results);
    dd('Save History Best startdate: '.$startdate.' - enddate: '.$enddate.' Success');

  }
  public function aginBalance($user){
    $user = User::find($user);
    if ($user->User_Agin != 1) {
      $balance = 0;
      return $balance;
    }
    $params = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'gb',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
    );
    $paramStr =  $this->encrypt($this->config['des_key'], $params);
    $key = md5($paramStr . $this->config['md5key']);
    $paramStr = urlencode($paramStr);
    $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
    $xml = simplexml_load_file($url);
    $input = json_encode($xml);
    $data = json_decode($input);
    foreach($data as $value){
      $info_value = $value->{'info'};
    }
    return $info_value;
  }
  public function withdraw(Request $request){
    $validator = Validator::make($request->all(), [
      'amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/',
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
      return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }
    if ($user->User_Agin != 1) {
      return $this->response(200, [], trans('notification.please_register!'), [], false);
    }
    if($request->password != $user->User_Agin_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    //dd($this->aginBalance($user->User_ID)*1);
    if($request->amount > $this->aginBalance($user->User_ID)*1){
      return $this->response(200, [], trans('notification.Balance_agin_sporbook_is_not_enough'), [], false);
    }
    //////////////////// CHUYEN TIEN VAO AG /////////////////   
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $transferno = $this->generateRandomDepositString();
    $paramstc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'tc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'OUT',
      'credit' => $request->amount
    );
    $paramStrtc =  $this->encrypt($this->config['des_key'], $paramstc);
    $keytc = md5($paramStrtc . $this->config['md5key']);
    $paramStrtc = urlencode($paramStrtc);
    $urltc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtc . '&key=' . $keytc;
    $xmltc = simplexml_load_file($urltc);
    $inputtc = json_encode($xmltc);
    $datatc = json_decode($inputtc);

    $paramstcc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'tcc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'OUT',
      'credit' => $request->amount,
      'flag' => '1'
    );
    $paramStrtcc =  $this->encrypt($this->config['des_key'], $paramstcc);
    $keytcc = md5($paramStrtcc . $this->config['md5key']);
    $paramStrtcc = urlencode($paramStrtcc);
    $urltcc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtcc . '&key=' . $keytcc;
    $xmltcc = simplexml_load_file($urltcc);
    $inputtcc = json_encode($xmltcc);
    $datatcc = json_decode($inputtcc);
    foreach($datatc as $value){
      $info_valuetc = $value->{'info'};
      $info_msgtc = $value->{'msg'};
    }
    foreach($datatcc as $value){
      $info_valuetcc = $value->{'info'};
      $info_msgtcc = $value->{'msg'};
    }
    if($info_valuetcc == 0 || $info_valuetc == 0){
      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $request->amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw from agin with ' . $request->amount . ' EUSD',
        'Money_MoneyAction' => 85,
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
      LogUser::addLogUser($user->User_ID, 'Withdraw agin success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.withdraw_success'), [], true);
    }
    LogUser::addLogUser($user->User_ID, 'Withdraw failed agin', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.withdraw_failed'), [], false);
  }
  public function getBalance(Request $request){
    $user = User::find($request->user()->User_ID);
    if ($user->User_Agin != 1) {
      $balance = 0;
      return $this->response(200, $balance);
    }

    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $params = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'gb',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
    );
    $paramStr =  $this->encrypt($this->config['des_key'], $params);
    $key = md5($paramStr . $this->config['md5key']);
    $paramStr = urlencode($paramStr);
    $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
    $xml = simplexml_load_file($url);
    $input = json_encode($xml);
    $data = json_decode($input);
    foreach($data as $value){
      $info_value = $value->{'info'};
    }
    return $this->response(200, $info_value*1);
  }
  public function generateRandomDepositString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = 'deposit';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  public function deposit(Request $request){
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
      return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }
    //dd($user);
    if ($user->User_Agin != 1) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    $userBalance = User::getBalance($user->User_ID);
    if ($userBalance < $request->amount) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    if($request->password != $user->User_Agin_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to agin ' . $request->amount . ' EUSD',
      'Money_MoneyAction' => 84,
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
    //////////////////// CHUYEN TIEN VAO AG /////////////////   

    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $transferno = $this->generateRandomDepositString();
    $paramstc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'tc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'IN',
      'credit' => $request->amount
    );
    $paramStrtc =  $this->encrypt($this->config['des_key'], $paramstc);
    $keytc = md5($paramStrtc . $this->config['md5key']);
    $paramStrtc = urlencode($paramStrtc);
    $urltc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtc . '&key=' . $keytc;
    $xmltc = simplexml_load_file($urltc);
    $inputtc = json_encode($xmltc);
    $datatc = json_decode($inputtc);

    $paramstcc = array(
      'cagent'    => $this->config['cagent'],
      'loginname' => $this->config['prefix'].$user->User_ID,
      'method'    => 'tcc',
      'actype'   => '1',
      'password'  =>  $this->password_agin,
      'cur'       => $this->config['currency'],
      'billno'    => $this->config['cagent'] . $transferno,
      'type' => 'IN',
      'credit' => $request->amount,
      'flag' => '1'
    );
    $paramStrtcc =  $this->encrypt($this->config['des_key'], $paramstcc);
    $keytcc = md5($paramStrtcc . $this->config['md5key']);
    $paramStrtcc = urlencode($paramStrtcc);
    $urltcc = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStrtcc . '&key=' . $keytcc;
    $xmltcc = simplexml_load_file($urltcc);
    $inputtcc = json_encode($xmltcc);
    $datatcc = json_decode($inputtcc);
    foreach($datatc as $value){
      $info_valuetc = $value->{'info'};
      $info_msgtc = $value->{'msg'};
    }
    foreach($datatcc as $value){
      $info_valuetcc = $value->{'info'};
      $info_msgtcc = $value->{'msg'};
    }
    if($info_valuetcc == 0 || $info_valuetc == 0){
      LogUser::addLogUser($user->User_ID, 'Deposit agin success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.deposit_success'), [], true);
    }
    $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
    LogUser::addLogUser($user->User_ID, 'Deposit failed agin', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.deposit_failed'), [], false);

  }
  public function login(Request $request){
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12',
    ], [
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'password.required' =>  trans('notification.password_required'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = User::find($request->user()->User_ID);

    if($user->User_Level != 1){
      return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }

    if($user->User_Agin != 1){
      return $this->response(200, [], trans('notification.you_have_not_registered_an_account_yet'), [], false);
    }
    if($request->password != $user->User_Agin_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    $sidag = $this->generateRandomString();
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $gameType="TASSPTA";//"TASSPTA"
    if($request->gameType){
      $gameType = $request->gameType;
    }
    $params = array( 
      "cagent"    => $this->config['cagent'],
      "loginname" => $this->config['prefix'].$user->User_ID,
      "actype"   => "1",	
      "password"  =>  $this->password_agin,  // 
      "cur"       => $this->config['currency'],
      "dm"  => "123betnow.net",
      "sid"  => $sidag,
      "lang"  => "3",
      "gameType"  => $gameType,
      "oddtype"  => "A",
    );
    $paramStr =  $this->encrypt($this->config['des_key'],$params); 
    $key = md5($paramStr. $this->config['md5key']); 
    $paramStr = urlencode($paramStr); 
    $urllogin = "https://gci.123betnow.net/forwardGame.do?params=".$paramStr."&key=".$key;
    return $this->response(200, $urllogin, trans('notification.login_success'), [], true);
  }

  public function CreateMember(Request $request){
    $user = User::find($request->user()->User_ID);

    if($user->User_Level != 1){
      return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
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

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    //////////////////////////// CREAT AG ACCOUNT //////////////////////////////////
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 904533){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 868151){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 606885){
      $this->password_agin = 'Aa123123';
    }
    if($user->User_ID == 691571){
      $this->password_agin = 'Koonran69';
    }
    if($user->User_ID == 350205){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 298926){
      $this->password_agin = 'KoonRan69';
    }
    if($user->User_ID == 246131){
      $this->password_agin = 'KoonRan969';
    }
    if($user->User_ID == 256163){
      $this->password_agin = 'KoonRan9696';
    }
    if($user->User_ID == 585389){
      $this->password_agin = '123123123Tin';
    }
    if($user->User_ID == 123123){
      $this->password_agin = 'Kyo2035';
    }
    if($user->User_ID == 652542){
      $this->password_agin = 'Lan123';
    }
    if($user->User_ID == 896794){
      $this->password_agin = 'Lan123123';
    }
    if($user->User_ID == 551419){
      $this->password_agin = 'N123123';
    }
    if($user->User_ID == 456319){
      $this->password_agin = 'Ninh123123';
    }
    $params = array(
      'cagent' =>$this->config['cagent'],
      'loginname' =>$this->config['prefix'].$user->User_ID,
      'password' => $this->password_agin,  //
      'actype' => '1', // 1 là tài khoản thực, 0 là tài khoản dùng thử
      'oddtype' => 'A',
      'cur' => $this->config['currency'],
      'method' => 'lg'
    );
    $paramStr =  $this->encrypt($this->config['des_key'], $params);
    $key = md5($paramStr . $this->config['md5key']);
    $paramStr = urlencode($paramStr);
    $url = 'https://gi.123betnow.net/doBusiness.do?params=' . $paramStr . '&key=' . $key;
    $xml = simplexml_load_file($url);
    $input = json_encode($xml);
    $data = json_decode($input);
    foreach($data as $value){
      $info_value = $value->{'info'};
      $info_msg = $value->{'msg'};
    }
    if($info_value == 0){
      LogUser::addLogUser($user->User_ID, 'register success agin', $info_msg ?? 'Response data false', $request->ip());

      $user->User_Agin_Password = $request->password;
      $user->User_Agin = 1;
      $user->save();
      return $this->response(200, [], trans('notification.register_success'), [], true);
    }
    LogUser::addLogUser($user->User_ID, 'register failed agin', $info_msg ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.register_failed'), [], false);
  }
  public function generateRandomString($length = 16)
  {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  } 
  function encrypt($des_key,$params) 
  { 
    $param = http_build_query($params); 
    $param = str_replace('&', '/\\\\\\\\/', $param); 
    $data = openssl_encrypt($param, 'DES-ECB', $des_key, OPENSSL_RAW_DATA); 
    return base64_encode($data); 
  } 
}
