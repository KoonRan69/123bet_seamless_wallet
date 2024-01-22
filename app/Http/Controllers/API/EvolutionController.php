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
use App\Model\BetHistoryEvo;
use Carbon\Carbon;
use DB;

class EvolutionController extends Controller
{
  public $config;
  public $password_agin = 'KoonRan69';
  public $api_host;
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['saveHistoryBest']]);
    $this->config = config('urlAgin.agin_api');
    $this->api_host = "https://api.luckylivegames.com";
    $this->casinokey = "1gvsw90kwuok5zqs";
    $this->apitoken = "15a59174850db01115f28c0bd1705230";
    $this->currency = "CNY";
  }


  public function login(Request $request){
    $user = User::find($request->user()->User_ID);
    if($user->User_Evo == 1){
      if($request->password != $user->User_Evo_Password){
        return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
      }
    }

    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'password.min' => trans('notification.password_minimum_6_characters'),
      'password.max' => trans('notification.password_up_to_12_characters '),
      'password.required' =>  trans('notification.password_required'),
      'password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }



    /* "game": {
      "category": "baccarat",
      "interface": "inlinevideo",
             "table": {
               "id": "oytmvb9m1zysmc44"
                      }
      },*/
    $username = "now_" . $user->User_ID ; 
    $body = '{
                  "uuid": "' . md5($username) .'",
                  "player": {
                    "id": "' . $username. '",
                    "update": true,
                    "firstName": "' . $username. '",
                    "lastName": "' . $username. '",
                    "nickname": "'. $username . '",
                    "country": "VN",
                    "language": "en",
                    "currency": "'.$this->currency.'",
                    "session": {
                      "id": "' . md5($username) .'",
                      "ip": "89.45.67.50"
                    },
                    "group": {
                      "id": "qe6glrwau24joiu3",
                      "action": "assign"
                    }
                  },
                  "config": {
                    "brand": {
                      "id": "1",
                      "skin": "1"
                    },

                    "channel": {
                      "wrapped": false,
                      "mobile": true
                    },
                    "urls": {
                      "cashier": "https://123betnow.net/",
                      "responsibleGaming": "https://123betnow.net/live",
                      "lobby": "https://123betnow.net/live",
                      "sessionTimeout": "https://123betnow.net/"
                    },
                    "freeGames": true
                  }
                }';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$this->api_host/ua/v1/$this->casinokey/$this->apitoken");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $result = curl_exec($ch);
    $data =  json_decode($result)  ; 
    $url = [] ; 
    $url['entry'] = $data->entry ; 
    $url['entryEmbedded'] =  $data->entryEmbedded ; 

    if($user->User_Evo != 1){
      $user->User_Evo_Password = $request->password ; 
      $user->User_Evo = 1 ; 
      $user->save() ; 

      return $this->response(200, [], 'Register success!', [], true);
    }

    return $this->response(200, $url, trans('notification.login_success'), [], true);
  }
  public function postChangePass(Request $request){
    $user = User::find($request->user()->User_ID);
    $validator = Validator::make($request->all(), [
      'type' => "required|",
      'otp' => 'required|',
      'new_password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'confirm_password' => 'required|same:new_password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
    ], [
      'new_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      'confirm_password.regex' => trans('notification.your_password_must_be_at_least_6_digits'),
      //'password.required' => trans('notification.password_required'),
      //'password.min' => trans('notification.password_minimum_6_characters'),
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

    if($request->type !== 'evo' && $request->type !== 'sbobet' && $request->type !== 'sbobet_infinity' && $request->type !== 'sbobet_solar'){
      return $this->response(200, [], 'Error!', [], false);
    }

    if($request->type === 'evo'){
      if ($user->User_Evo != 1) {
        return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
      }
    }
    if($request->type === 'sbobet'){
      if (!$user->User_Name_Sbobet) {
        return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
      }
    }
    if($request->type === 'sbobet_infinity'){
      if (!$user->User_Name_Sbobet_Lucky_Infinity) {
        return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
      }
    }
    if($request->type === 'sbobet_solar'){
      if (!$user->User_Name_Sbobet_Lucky) {
        return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
      }
    }

    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    if($request->type === 'evo'){
      $tokenOTP = $user->otp_evo;
    }
    if($request->type === 'sbobet' || $request->type === 'sbobet_infinity' || $request->type === 'sbobet_solar'){
      $tokenOTP = $user->otp_sbobet;
    }
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);

    if($responseToken['user_id'] !== $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] !== $request->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    if($request->type === 'evo'){
      $user->User_Evo_Password = $request->new_password;
      $user->otp_evo = null;
    }
    if($request->type === 'sbobet'){
      $user->User_Sbobet_Password = $request->new_password;
      $user->otp_sbobet = null;
    }
    if($request->type === 'sbobet_infinity'){
      $user->User_Sbobet_Password_Infinity = $request->new_password;
      $user->otp_sbobet = null;
    }
    if($request->type === 'sbobet_solar'){
      $user->User_Sbobet_Password_Lucky = $request->new_password;
      $user->otp_sbobet = null;
    }
    $user->save();
    LogUser::addLogUser($user->User_ID, 'Change password Evo', 'Response data true', $request->ip());
    return $this->response(200, [], 'Change password success!');
  }
  public function getBalance(Request $request){
    $user = User::find($request->user()->User_ID);
    $username = "now_" . $user->User_ID ; 
    $contents = file_get_contents("$this->api_host/api/ecashier?cCode=RWA&ecID=$this->casinokey&euID=".$username."&output=0");
    $decodecontents = json_decode($contents);
    $abalance = $decodecontents -> userbalance -> abalance;

    return $this->response(200, $abalance*1);
  }


  public function evoBalance($id){
    try{ 
      $username = "now_" . $id ; 
      $contents = file_get_contents("$this->api_host/api/ecashier?cCode=RWA&ecID=$this->casinokey&euID=".$username."&output=0");
      $decodecontents = json_decode($contents);
      $abalance = $decodecontents->userbalance->abalance;
      return  $abalance*1;
    } catch (\Exception $e) {
      return  0;
    }
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
    //if ($user->User_Level != 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    if ($user->User_Evo != 1) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    //$etransid = $this->generateRandomString();
    //dd($etransid);
    $userBalance = User::getBalance($user->User_ID);
    if ($userBalance < $request->amount) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    if($request->password != $user->User_Evo_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to evo ' . $request->amount . ' EUSD',
      'Money_MoneyAction' => 87,
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
    //////////////////// CHUYEN TIEN VAO Evo /////////////////   

    $username = "now_" . $user->User_ID ; 
    $etransid = $this->generateRandomString();
    //dd("$this->api_host/api/ecashier?cCode=ECR&amount=".$request->amount."&ecID=$this->casinokey&eTransID=".$etransid."&euID=".$username."&output=0");$this->casinokey
    $contents = file_get_contents("$this->api_host/api/ecashier?cCode=ECR&amount=".$request->amount."&ecID=$this->casinokey&eTransID=".$etransid."&euID=".$username."&output=0");
    $decodecontents = json_decode($contents);
    $transid = $decodecontents -> transfer -> transid;

    $id = Money::insertGetId($arrayInsert);
    //dd($decodecontents,$transid);


    if($transid){
      LogUser::addLogUser($user->User_ID, 'Deposit evo success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.deposit_success'), [], true);
    }
    $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
    LogUser::addLogUser($user->User_ID, 'Deposit failed evo', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.deposit_failed'), [], false);

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

  public function withdrawV2(Request $request){
    $user = User::find($request->user()->User_ID);
    if ($user->User_Evo != 1) {
      return $this->response(200, ['status'=>false], trans('notification.please_register!'), [], false);
    }

    $balanceGame = $this->evoBalance($user->User_ID);
    $user->login_evo = 0;
    $user->save();
    if($balanceGame <= 0){
      return $this->response(200, ['status'=>false], '', [], false);
    }

    //////////////////// CHUYEN TIEN VAO  /////////////////   
    $username = "now_" . $user->User_ID ; 
    $etransid = $this->generateRandomString();

    $contents = file_get_contents("$this->api_host/api/ecashier?cCode=EDB&amount=".$balanceGame."&ecID=$this->casinokey&eTransID=".$etransid."&euID=".$username."&output=0");
    $decodecontents = json_decode($contents);//dd($decodecontents);
    if($decodecontents->transfer->transid){
      $transid = $decodecontents -> transfer -> transid;
      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $balanceGame,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw from evo with ' . $balanceGame . ' EUSD',
        'Money_MoneyAction' => 88,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => 3,
        'Money_CurrentAmount' => $balanceGame,
        'Money_CurrencyFrom' => 0,
        'Money_CurrencyTo' => 0,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1,
      );
      Money::insert($arrayInsert);
      LogUser::addLogUser($user->User_ID, 'Withdraw evo success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, ['status'=>true], '', [], true);
    }
    LogUser::addLogUser($user->User_ID, 'Withdraw failed evo', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, ['status'=>false], '', [], false);
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
    //if ($user->User_Level != 1) return $this->response(200, [], 'The system is maintained!!!', [], false);
    if ($user->User_Evo != 1) {
      return $this->response(200, [], trans('notification.please_register!'), [], false);
    }
    if($request->password != $user->User_Evo_Password){
      return $this->response(200, [], trans('notification.Incorrect_password'), [], false);
    }
    //dd($this->aginBalance($user->User_ID)*1);
    //dd($this->evoBalance($user->User_ID));
    if($request->amount > $this->evoBalance($user->User_ID)){
      //dd(123);
      return $this->response(200, [], trans('notification.Balance_evo_is_not_enough'), [], false);
    }
    //////////////////// CHUYEN TIEN VAO  /////////////////   
    $username = "now_" . $user->User_ID ; 
    $etransid = $this->generateRandomString();

    $contents = file_get_contents("$this->api_host/api/ecashier?cCode=EDB&amount=".$request->amount."&ecID=$this->casinokey&eTransID=".$etransid."&euID=".$username."&output=0");
    $decodecontents = json_decode($contents);//dd($decodecontents);
    if($decodecontents->transfer->transid){


      $transid = $decodecontents -> transfer -> transid;
      //dd($decodecontents);

      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => $request->amount,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => 'Withdraw from evo with ' . $request->amount . ' EUSD',
        'Money_MoneyAction' => 88,
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
      LogUser::addLogUser($user->User_ID, 'Withdraw evo success', $info_msgtcc ?? 'Response data false', $request->ip());
      return $this->response(200, [], trans('notification.withdraw_success'), [], true);
    }
    LogUser::addLogUser($user->User_ID, 'Withdraw failed evo', $info_msgtcc ?? 'Response data false', $request->ip());
    return $this->response(200, [], trans('notification.withdraw_failed'), [], false);
  }
  public function listmember(){
    $getUser = User::where('User_Evo',1)->select('User_ID','User_Email','User_Parent','User_Tree')->get();
    $prefix = $this->config['prefix'];
    return $this->response(200, ['list'=>$getUser,'prefix'=>'now_']);
  }
  public function saveHistoryBest(Request $req){
    return;
    date_default_timezone_set('UTC');
    $startDate = strtotime('-5 minutes');
    $endDate = strtotime('now');

    if($req->runtime == 10){
      $startDate = strtotime('-10 minutes');
    }
    if($req->runtime == 20){
      $startDate = strtotime('-20 minutes');
      $endDate = strtotime('-9 minutes');
    }
    if($req->runtime == 30){
      $startDate = strtotime('-30 minutes');
      $endDate = strtotime('-19 minutes');
    }
    if($req->runtime == 60){
      $startDate = strtotime('-60 minutes');
      $endDate = strtotime('-29 minutes');
    }

    $startDate = date("Y-m-d H:i:s", $startDate);
    $endDate = date("Y-m-d H:i:s", $endDate);

    $login = $this->casinokey;
    $password = $this->apitoken;

    //$url = "https://admin.luckylivegames.com/api/gamehistory/v1/casino/games?startDate=2022-06-08 04:9:05&endDate=2022-06-08 05:15:05";
    $url = "https://admin.luckylivegames.com/api/gamehistory/v1/casino/games?startDate=" . $startDate . "&endDate=" . $endDate . "";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
    $data = curl_exec($ch);
    //print_r($data);
    $data = json_decode($data,TRUE);
    if(count($data['data']) > 0){
      for($i=0;$i < count($data['data'][0]['games']);$i++)
      {
        for($z=0;$z < count($data['data'][0]['games'][$i]['participants']);$z++)
        {
          for($y=0;$y < count($data['data'][0]['games'][$i]['participants'][$z]['bets']);$y++)
          {
            $username=$data['data'][0]['games'][$i]['participants'][$z]['playerId'];
            $suboper=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['code'];
            $betmoney=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['stake'];
            $awardmoney=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['payout'];
            $bettime=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['placedOn'];
            $roundid=$data['data'][0]['games'][$i]['participants'][$z]['bets'][$y]['transactionId'];
            $game_name=$data['data'][0]['games'][$i]['gameType'];
            $orderid=$data['data'][0]['games'][$i]['id'];
            $betresult=$data['data'][0]['games'][$i]['status'];
            $timestring = strtotime($bettime);
            /*echo "username : $username\n";
          echo "suboper : $suboper\n";
          echo "betmoney : $betmoney\n";
          echo "awardmoney : $awardmoney\n";
          echo "bettime : $bettime\n";
          echo "roundid : $roundid\n";
          echo "game_name : $game_name\n";
          echo "orderid : $orderid\n";
          echo "betresult : $betresult\n";
          echo "timestring : $timestring\n";
          echo "-------------------------------------------\n";*/



            $checkid = substr($username, 0, 3) ;
            if($checkid == 'NOW' || $checkid == 'now'){
              $checkhistory = BetHistoryEvo::where('orderid',$orderid)->first() ; 
              $id = explode("_", $username);
              if(!$checkhistory){
                $bethistory = new BetHistoryEvo() ; 
                $bethistory->username = $username;
                $bethistory->user_id = $id[1];
                $bethistory->suboper = $suboper;
                $bethistory->betmoney = $betmoney;
                $bethistory->awardmoney	= $awardmoney;
                $bethistory->roundid = $roundid ; 
                $bethistory->orderid = $orderid ; 
                $bethistory->betresult = $betresult ;
                $bethistory->bettime = $bettime ;
                $bethistory->timestring = $timestring ;
                $bethistory->game_name = $game_name ; 
                $bethistory->save() ; 
              }
            }



            //BetHistoryEvo
          }
        }
      }
    }
    dd('Save History Best startdate: '.$startDate.' - enddate: today Success');
  }
}