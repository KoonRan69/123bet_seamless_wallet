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
use App\Model\BetHistorySbobet;
use App\Model\BetHistorySbobetCasino;
use App\Model\BetHistorySbobetVirtualSport;
use App\Model\BetHistorySbobetSeamless;
use App\Model\BetHistorySbobetThirdPartySportsBook;
use App\Model\GameBet;
use App\Model\GameListV2;
use Carbon\Carbon;
use DB;
use DateTime;
use App\Model\logMoney;
use Illuminate\Support\Str;

class SbobetController extends Controller
{
  public $config;
  //public $is_maintain = 0;

  public function __construct()
  {
    //$this->middleware('auth:api');
    $this->config = config('urlSBOBET.sbobet');
  }

   public function updateMaxbetMember(Request $request){
    $url = 'https://ex-api-yy.xxttgg.com/web-root/restricted/player/update-player-bet-settings.aspx';
    $body = [
      "CompanyKey"=> "2F4B29F27A4E497AB8FC779944E54A01",
      "ServerId"=> "YY-ADMIN",
      "Username" => $request->username,
      "Min"=>1,
      "Max"=> $request->max*1,
      "MaxPerMatch"=> $request->maxpermatch*1,
      "CasinoTableLimit"=>1,
    ];

    $topup_str   = json_encode($body);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',

    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    curl_close($ch);
    $check= json_decode($result);
    dd($check);
    if ($err) {
      return $this->response(200, $err, trans('notification.register_failed'), [], false);
    }
    dd($check);
    if($check->status != 0000){
      return $this->response(200, $check->desc , trans('notification.register_failed'), [], false);
    }
    $user->User_Sbobet_Password = $request->password;
    $user->save();
    return $this->response(200, [], trans('notification.register_success'), [], true);
  }

  
  
  //==========================================================================V2==================================================================================
  public function loginV2(Request $request){
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], "Coming soon", [], false);
    }
    $validator = Validator::make($request->all(), [
      'game' => 'required',
    ], [
      'game.required' => 'Please choose game',
    ]);
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $lucky = $request->lucky; //đăng ký tài khoản sử dụng balance lucky 

    $User_Name_Sbobet = $user->User_Name_Sbobet;
    $User_Sbobet_Password = $user->User_Sbobet_Password;

    if($User_Name_Sbobet == NULL || $User_Sbobet_Password == NULL){
      return $this->response(200, [], 'Error! Please contact support', [], false);
    }

    $device = $request->device;
    if(!$device){
      $device = 'd';
    }
    $getGame = GameListV2::with('gameParent')->where('id',$request->game)->where('show',1)->where('parent','!=',0)->first();
    if(!$getGame){
      return $this->response(200, [], 'The game does not exist!', [], false);
    }

    $portfolio = $getGame->portfolio;
    $url = $this->config['url'].'/web-root/restricted/player/login.aspx';
    //$url = $getGame->gameParent->url_play.'/web-root/restricted/player/login.aspx';
    $urlBetnow = "https://123betnow.net/";

    $body = [
      "Username" => $User_Name_Sbobet,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
      "Portfolio"=>$portfolio,
    ];

    $topup_str  = json_encode($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);
    if ($err) {
      LogUser::addLogUser($user->User_ID, 'login sbobet faild', $err ??'Response data false', $request->ip());
      return $this->response(200, $err, 'login failed', [], false);
    } 
    if($check->error->id != 0){
      LogUser::addLogUser($user->User_ID, 'login sbobet faild', $check->msg ?? 'Response data false', $request->ip());
      return $this->response(200, $check->error->msg, 'login failed', [], false);
    }

    LogUser::addLogUser($user->User_ID, 'login sbobet success', $err ?? 'Response data false', $request->ip());



    $urlLogin = "https:" . $check->url.$getGame->url_play.'&device='.$device;

    return $this->response(200, ["url" => $urlLogin] );
  }
  public function depositV2(Request $request){
    $user = User::find($request->user()->User_ID);//

    if($user->User_Level != 1){
      //return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }


    $Money_MoneyAction = 91;
    $coin = 3;
    $userBalance = User::getBalance($user->User_ID,$coin);
    $userBalance = (int)$userBalance;
    //Không nạp số thập phân

    $User_Name_Sbobet = $user->User_Name_Sbobet;
    $cmt = 'Deposit to sbobet ' . $userBalance . ' USD';


    if($User_Name_Sbobet == NULL){
      return $this->response(200, ['status'=>false], 'Error!. Please contact support', [], false);
    }
    $user->login_sbobet = 1;
    $user->save();
    if ($userBalance > 0){
      $url = $this->config['url'].'/web-root/restricted/player/deposit.aspx';
      $txCode = Str::random(29);
      $arrayInsert = array(
        'Money_User' => $user->User_ID,
        'Money_USDT' => -$userBalance,
        'Money_USDTFee' => 0,
        'Money_Time' => time(),
        'Money_Comment' => $cmt,
        'Money_MoneyAction' => $Money_MoneyAction,
        'Money_MoneyStatus' => 1,
        'Money_Address' => null,
        'Money_Currency' => $coin,
        'Money_CurrentAmount' => $userBalance,
        'Money_CurrencyFrom' => 0,
        'Money_CurrencyTo' => 0,
        'Money_Rate' => 1,
        'Money_Confirm' => 0,
        'Money_Confirm_Time' => null,
        'Money_FromAPI' => 1,
        'Money_TXID' => $txCode,
      );

      $id = Money::insertGetId($arrayInsert);

      $body = [
        "Username" => $User_Name_Sbobet,
        "Amount"=> $userBalance,
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
        LogUser::addLogUser($user->User_ID, 'Deposit failed sbobet', $err ?? 'Response data false', $request->ip());
        return $this->response(200, ['status'=>false], '', [], false);
      } else {
        if($check->error->id != 0){
          $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
          LogUser::addLogUser($user->User_ID, 'Deposit failed sbobet', $err ?? 'Response data false', $request->ip());
          return $this->response(200, ['status'=>false], '', [], false);
        }
        LogUser::addLogUser($user->User_ID, 'Deposit sbobet success', $err ?? 'Response data false', $request->ip());
        return $this->response(200, ['status'=>true], '', [], true);
      }
    }
    return $this->response(200, ['status'=>true], '', [], true);
  }

  public function withdrawV2(Request $request){
    $user = User::find($request->user()->User_ID);//$request->user()->User_ID
    $address_pool = '';
    $User_Name_Sbobet = $user->User_Name_Sbobet;

    $Money_MoneyAction = 92;
    $coin = 3;

    if($User_Name_Sbobet == NULL){
      return $this->response(200, ['status'=>false], 'Error! Please contact support', [], false);
    }

    $balanceGame = $this->getBalancePlayer($user->User_ID, $User_Name_Sbobet);
    $user->login_sbobet = 0;
    $user->save();
    if($balanceGame <= 0){
      return $this->response(200, ['status'=>false], 'Error!', [], false);
    }
    //Check điều kiện volume x15 nếu có nhập gift code tân thủ
    $checkUseCode = Money::where('Money_MoneyAction',95)->where('Money_User',$user->User_ID)->where('Money_MoneyStatus',1)->orderbyDesc('Money_ID')->first();
    if($checkUseCode){
      $totalTradeBonusArr = GameBet::getTotalTradeBonus($user->User_ID);
      $totalTradeBonus = $totalTradeBonusArr->totalBet ?? 0;
      if($totalTradeBonus/15 < abs($checkUseCode->Money_USDT)){
        LogUser::addLogUser($user->User_ID, 'Your volume trade is not enough to withdraw', trans('notification.your_volume_trade_is_not_enough_to_withdraw'), $request->ip());
        return $this->response(200, ['status'=>false], 'After using the beginner gift, the volume must be x15 compared to the gift code amount received to be withdrawn', [], false);
      }
    }


    $txCode = Str::random(29);
    $url = $this->config['url'].'/web-root/restricted/player/withdraw.aspx';
    $body = [
      "Username" => $User_Name_Sbobet,
      "Amount"=> $balanceGame,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
      "IsFullAmount" => false,
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
      LogUser::addLogUser($user->User_ID, 'Withdraw failed sbobet', $err, $request->ip());
      return $this->response(200, ['status'=>false], '', [], false);
    } 

    if($check->error->id != 0){
      LogUser::addLogUser($user->User_ID, 'Withdraw failed sbobet', $check->error->msg , $request->ip());
      return $this->response(200, ['status'=>false], '', [], false);
    }
    //////////////////// CHUYEN TIEN VAO  /////////////////   
    $cmt = 'Withdraw from sbobet with ' . $balanceGame . ' USD';
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => $balanceGame,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $cmt,
      'Money_MoneyAction' => $Money_MoneyAction,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $coin,
      'Money_CurrentAmount' => $balanceGame,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => $txCode,
    );
    Money::insert($arrayInsert);
    LogUser::addLogUser($user->User_ID,'Withdraw sbobet success', 'withraw', $request->ip());
    return $this->response(200, ['status'=>true], '', [], true);
  }

  //==========================================================================V2==================================================================================

  //lock agency
  public function BlockAgent(Request $request){
    $url = 'https://ex-api-yy.xxttgg.com/web-root/restricted/agent/update-agent-status.aspx';
    $body = [
      "Username" => "Betnow_Sbobet_123",
      "CompanyKey"=> "2F4B29F27A4E497AB8FC779944E54A01",
      "ServerId"=> "YY-ADMIN",
      "Lock" => "Closed"

    ];
    $topup_str   = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    #FIXME: Hardcoded Access Token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    curl_close($ch);
    $check= json_decode($result);
    dd($check);
  }
  // đăng ký agent
  public function CreateMemberAgent(Request $request){
    dd('stop');

    //$url = $this->config['url'].'/web-root/restricted/agent/register-agent.aspx';
    //$betLimit = '{
    //"SEXYBCRT":{
    //	"LIVE":{"limitId":[260312,260317]}
    //    }
    //}';

    //$body = "&CompanyKey=".$this->config['CompanyKey']."&currency=".$this->config['currency']."&Username=".$this->config['prefix'].$user->User_ID."&Agent=".$this->config['Agent']."ServerId=".$this->config['ServerId'];

    //chính
    $url = 'https://ex-api-yy.xxttgg.com/web-root/restricted/agent/register-agent.aspx';
    $body = [
      "Username" => "Betnow_Sbobet_VND",
      "Password"=> "Sbobet123456",
      "Currency"=>"VND",
      "Min"=>1,
      "Max"=> 100000,
      "MaxPerMatch"=> 2000000,
      "CasinoTableLimit"=>1,
      "CompanyKey"=> "2F4B29F27A4E497AB8FC779944E54A01",
      "ServerId"=> "YY-ADMIN",
    ];

    //dd($body);

    //demo
    //$body = [
    //"Username" => "Betnow_Sbobet_123",
    //"Password"=> "Sbobet123456",
    //"Currency"=>"USD",
    //"Min"=>1,
    //"Max"=> 100000,
    //"MaxPerMatch"=> 200000,
    //"CasinoTableLimit"=>1,
    //"CompanyKey"=> "32AA14B122094C1C8B17B7B20DC8DA9B",
    //"ServerId"=> "YY-TEST",
    //];
    $topup_str   = json_encode($body);
    //dd($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    #FIXME: Hardcoded Access Token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);

    #Ensure to close curl
    curl_close($ch);


    //$response = curl_exec($curl);

    //$err = curl_error($curl);

    //curl_close($curl);
    $check= json_decode($result);
    dd($check);
    if ($err) {
      return $this->response(200, $err, trans('notification.register_failed'), [], false);
    }
    dd($check);
    if($check->status != 0000){
      return $this->response(200, $check->desc , trans('notification.register_failed'), [], false);
    }
    $user->User_Sbobet_Password = $request->password;
    $user->save();
    return $this->response(200, [], trans('notification.register_success'), [], true);
  }

  public function CreateMember(Request $request){
    //return $this->response(200, [], 'Game pause!', [], false);
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], "Coming soon", [], false);
    }
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'password_confirm' => 'required|same:password|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      //'lucky' => 'required|numeric|min:0|max:1',
    ], [
      'password.regex' => 'Your password must have at least 6 digits including uppercase and lowercase letters, must contain at least 1 number, and do not contain special characters..',
      'password.required' => 'Password required',
      'password.min' => 'password minimum 6 characters',
      'password.max' => 'password up to 12 characters',
      'password_confirm.regex' => 'Your password confirm must have at least 6 digits including uppercase and lowercase letters, must contain at least 1 number, and do not contain special characters..',
      'password_confirm.min' => 'password_minimum_6_characters',
      'password_confirm.max' => 'password_up_to_12_characters ',
      'password_confirm.same' => 'Confirm_password_is_not_the_same_as_password',
    ]);
    if(!$user){
      return $this->response(200, [], trans('notification.you_not_login_123bet'), [], false);
    }
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $lucky = $request->lucky; //đăng ký tài khoản sử dụng balance lucky 
    if($lucky == 1){
      $pool = $request->pool;
      if($pool != 'SolarSystem' && $pool != 'Infinity'){
        return $this->response(200, [],'Pool invalid', [], false);
      }
      if($pool == 'SolarSystem'){
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Lucky;
      }else{
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky_Infinity;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Infinity;
      }
    } else {
      $User_Name_Sbobet = $user->User_Name_Sbobet;
      $User_Sbobet_Password = $user->User_Sbobet_Password;
    }
    if($User_Name_Sbobet != NULL || $User_Sbobet_Password != NULL){
      return $this->response(200, [], 'the account you have registered', [], false);
    }
    $url = $this->config['url'].'/web-root/restricted/player/register-player.aspx';

    if($lucky == 1){
      if($pool == 'SolarSystem'){
        $bodyUsername = "now_LuckySolarsystem_$user->User_ID";
      }else{
        $bodyUsername = "now_LuckyInfinity_$user->User_ID";
      }
    }
    else $bodyUsername = "now_123Betnow_$user->User_ID";

    $body = [
      "Username" => $bodyUsername,
      "Agent"=> $this->config['Agent'],
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
    ];
    $topup_str   = json_encode($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);
    if ($err) {
      return $this->response(200, $err, 'register_failed', [], false);
    }
    if($check->error->id != 0){
      return $this->response(200, $check->error->msg , 'register_failed', [], false);
    }
    if($lucky == 1){
      if($pool == 'SolarSystem'){
        $user->User_Name_Sbobet_Lucky = $bodyUsername;
        $user->User_Sbobet_Password_Lucky = $request->password;
      }else{
        $user->User_Name_Sbobet_Lucky_Infinity = $bodyUsername;
        $user->User_Sbobet_Password_Infinity = $request->password;
      }
    } else {
      $user->User_Name_Sbobet = $bodyUsername;
      $user->User_Sbobet_Password = $request->password;
    }
    $user->save();
    return $this->response(200, [], 'register success', [], true);
  }


  public function login(Request $request){
    //return $this->response(200, [], 'Game pause!', [], false);
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], "Coming soon", [], false);
    }
    $validator = Validator::make($request->all(), [
      'Portfolio' => 'required',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      //'lucky' => 'required|numeric|min:0|max:1',
    ], [
      'Portfolio.required' => 'Please choose game',
      'password.regex' => 'Incorrect password',
      'password.required' => 'Password required',
      'password.min' => 'password minimum 6 characters',
      'password.max' => 'password up to 12 characters',
    ]);
    if(!$user){
      return $this->response(200, [], trans('notification.you_not_login_123bet'), [], false);
    }
    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $lucky = $request->lucky; //đăng ký tài khoản sử dụng balance lucky 
    if($lucky == 1){
      $pool = $request->pool;
      if($pool != 'SolarSystem' && $pool != 'Infinity'){
        return $this->response(200, [],'Pool invalid', [], false);
      }
      if($pool == 'SolarSystem'){
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Lucky;
      }else{
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky_Infinity;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Infinity;
      }
    } else {
      $User_Name_Sbobet = $user->User_Name_Sbobet;
      $User_Sbobet_Password = $user->User_Sbobet_Password;
    }
    if($User_Name_Sbobet == NULL || $User_Sbobet_Password == NULL){
      return $this->response(200, [], 'You have not registered an account yet', [], false);
    }

    if($request->password != $User_Sbobet_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }

    $url = $this->config['url'].'/web-root/restricted/player/login.aspx';
    $urlBetnow = "https://123betnow.net/";

    $body = [
      "Username" => $User_Name_Sbobet,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
      "Portfolio"=>$request->Portfolio,
    ];
    $topup_str   = json_encode($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);
    if ($err) {
      LogUser::addLogUser($user->User_ID, 'login sbobet faild', $err ??'Response data false', $request->ip());
      return $this->response(200, $err, 'login failed', [], false);
    } 
    if($check->error->id != 0){
      LogUser::addLogUser($user->User_ID, 'login sbobet faild', $check->msg ?? 'Response data false', $request->ip());
      return $this->response(200, $check->error->msg, 'login failed', [], false);
    }

    LogUser::addLogUser($user->User_ID, 'login sbobet success', $err ?? 'Response data false', $request->ip());
    return $this->response(200, ["url" => "https:" . $check->url] , 'login success', [], true);

  }

  public function getBalancePlayer($user ,$Username,$lucky = 0){
    $user_data = User::find($user);
    if($lucky == 1){
      $User_Sbobet_Password = $user_data->User_Sbobet_Password_Lucky;
    } else {
      $User_Sbobet_Password = $user_data->User_Sbobet_Password;
    }
    if ($User_Sbobet_Password == NULL) {
      $balance = 0;
      return $balance;
    }
    $url = $this->config['url'].'/web-root/restricted/player/get-player-balance.aspx';
    $body = [
      "Username" => $Username,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
    ];
    $topup_str = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);

    if ($err) {
      return 0;
    }
    if($check->error->id != 0){
      return 0;
    }

    $total = $check->balance;

    return $total;
  }

  public function depositTestSbobet(Request $request){
    $user = User::find(829234);
    $amount = 50;
    $User_Name_Sbobet = $user->User_Name_Sbobet;

    if($User_Name_Sbobet == NULL){
      return $this->response(200, [], 'Please register!', [], false);
    }


    $url = $this->config['url'].'/web-root/restricted/player/deposit.aspx';
    $txCode = Str::random(29);

    $body = [
      "Username" => $User_Name_Sbobet,
      "Amount"=> $amount,
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
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);
    if ($err) {
      return $this->response(200, [], 'false', [], false);
    } else {
      if($check->error->id != 0){
        return $this->response(200, [], 'false', [], false);
      }
      return $this->response(200, [], 'success', [], true);
    }

  }

  public function deposit(Request $request){
    //return $this->response(200, [], 'Game pause!', [], false);
    $validator = Validator::make($request->all(), [
      'Amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      //'lucky' => 'required|numeric|min:0|max:1',
    ],[
      'password.regex' => 'Incorrect password',
      'password.required' => 'Password required',
      'password.min' => 'password minimum 6 characters',
      'password.max' => 'password up to 12 characters',
      'Amount.required' => 'amount required',
      'Amount.min' => 'minimum amount 50',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], "Coming soon", [], false);
    }

    $lucky = $request->lucky; //đăng ký tài khoản sử dụng balance lucky 
    $address_pool = '';
    if($lucky == 1){
      $pool = $request->pool;
      if($pool != 'SolarSystem' && $pool != 'Infinity'){
        return $this->response(200, [],'Pool invalid', [], false);
      }
      $Money_MoneyAction = 93;
      $coin = 18;
      if($pool == 'SolarSystem'){
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Lucky;
        $cmt = 'Deposit SolarSystem lucky to sbobet ' . $request->Amount . ' USD';
      }else{
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky_Infinity;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Infinity;
        $cmt = 'Deposit Infinity lucky to sbobet ' . $request->Amount . ' USD';
      }
      $getBalance = User::getBalanceLucky($user->User_ID,$coin);
      $getBalance = $getBalance[$pool];
      $userBalance = $getBalance['balance'];
      $address_pool = $getBalance['addressPool'];

    } else {
      $User_Name_Sbobet = $user->User_Name_Sbobet;
      $User_Sbobet_Password = $user->User_Sbobet_Password;
      $cmt = 'Deposit to sbobet ' . $request->Amount . ' USD';
      $Money_MoneyAction = 91;
      $coin = 3;
      $userBalance = User::getBalance($user->User_ID,$coin);
    }
    if($User_Name_Sbobet == NULL || $User_Sbobet_Password == NULL){
      return $this->response(200, [], 'Please register!', [], false);
    }

    if ($userBalance < $request->Amount) return $this->response(200, [], 'Your balance is not enough', [], false);

    if($request->password !== $User_Sbobet_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }

    $url = $this->config['url'].'/web-root/restricted/player/deposit.aspx';
    $txCode = Str::random(29);

    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$request->Amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $cmt,
      'Money_MoneyAction' => $Money_MoneyAction,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $coin,
      'Money_CurrentAmount' => $request->Amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => $txCode,
      'multiplay_pool'=>$address_pool
    );


    $id = Money::insertGetId($arrayInsert);

    $body = [
      "Username" => $User_Name_Sbobet,
      "Amount"=> $request->Amount,
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
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);

    if ($err) {
      $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
      if($lucky == 1){
        $cmtLogTitle = 'Deposit failed lucky to sbobet';
      } else {
        $cmtLogTitle = 'Deposit failed sbobet';
      }
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $request->ip());
      return $this->response(200, [], $cmtLogTitle, [], false);
    } else {
      if($check->error->id != 0){
        $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
        if($lucky == 1){
          $cmtLogTitle = 'Deposit failed lucky to sbobet';
        } else {
          $cmtLogTitle = 'Deposit failed sbobet';
        }
        LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $request->ip());
        return $this->response(200, $check->status , $cmtLogTitle, [], false);
      }
      if($lucky == 1){
        $cmtLogTitle = 'Deposit lucky to sbobet success';
      } else {
        $cmtLogTitle = 'Deposit sbobet success';
      }
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err ?? 'Response data false', $request->ip());
      return $this->response(200, ['balance' => $check->balance, 'amount' => $request->Amount], $cmtLogTitle, [], true);
    }

  }

  public function withdraw(Request $request){
    $validator = Validator::make($request->all(), [
      'Amount' => 'required|numeric|min:50',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      //'lucky' => 'required|numeric|min:0|max:1',
    ],[
      'password.regex' => 'Incorrect password',
      'password.required' => 'Password required',
      'password.min' => 'password minimum 6 characters',
      'password.max' => 'password up to 12 characters',
      'Amount.required' => 'amount required',
      'Amount.min' => 'minimum amount 50',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = User::find($request->user()->User_ID);
    if($user->User_Level != 1){
      //return $this->response(200, [], "Coming soon", [], false);
    }

    $lucky = $request->lucky; //đăng ký tài khoản sử dụng balance lucky 
    $address_pool = '';
    if($lucky == 1){
      $pool = $request->pool;
      if($pool != 'SolarSystem' && $pool != 'Infinity'){
        return $this->response(200, [],'Pool invalid', [], false);
      }
      if($pool == 'SolarSystem'){
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Lucky;
        $cmt = 'Withdraw SolarSystem from sbobet to lucky with ' . $request->Amount . ' USD';
      }else{
        $User_Name_Sbobet = $user->User_Name_Sbobet_Lucky_Infinity;
        $User_Sbobet_Password = $user->User_Sbobet_Password_Infinity;
        $cmt = 'Withdraw Infinity from sbobet to lucky with ' . $request->Amount . ' USD';
      }
      $Money_MoneyAction = 94;
      $coin = 18;
      $getBalance = User::getBalanceLucky($user->User_ID,$coin);
      $getBalance = $getBalance[$pool];
      $userBalance = $getBalance['balance'];
      $address_pool = $getBalance['addressPool'];

    } else {
      $User_Name_Sbobet = $user->User_Name_Sbobet;
      $User_Sbobet_Password = $user->User_Sbobet_Password;
      $cmt = 'Withdraw from sbobet with ' . $request->Amount . ' USD';
      $Money_MoneyAction = 92;
      $coin = 3;
    }
    if($User_Name_Sbobet == NULL || $User_Sbobet_Password == NULL){
      return $this->response(200, [], 'Please register!', [], false);
    }

    if($request->password != $User_Sbobet_Password){
      return $this->response(200, [], 'Incorrect password', [], false);
    }


    //Check điều kiện volume x15 nếu có nhập gift code tân thủ
    if($lucky != 1){
      $checkUseCode = Money::where('Money_MoneyAction',95)->where('Money_User',$user->User_ID)->where('Money_MoneyStatus',1)->orderbyDesc('Money_ID')->first();
      if($checkUseCode){
        $totalTradeBonusArr = GameBet::getTotalTradeBonus($user->User_ID);
        $totalTradeBonus = $totalTradeBonusArr->totalBet ?? 0;
        if($totalTradeBonus/15 < abs($checkUseCode->Money_USDT)){
          return $this->response(200, [], trans('notification.your_volume_trade_is_not_enough_to_withdraw'), [], false);
        }
      }
    }

    if($request->Amount > $this->getBalancePlayer($user->User_ID, $User_Name_Sbobet,$lucky)){
      return $this->response(200, [], 'Balance sbobet is not enough', [], false);
    }


    $txCode = Str::random(29);
    $url = $this->config['url'].'/web-root/restricted/player/withdraw.aspx';
    $body = [
      "Username" => $User_Name_Sbobet,
      "Amount"=> $request->Amount,
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
      "IsFullAmount" => false,
      "TxnId" => "$txCode",
    ];
    $topup_str = json_encode($body);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
      // 'Content-Length: '.strlen($topup_str),
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

    $check= json_decode($result);

    if ($err) {
      if($lucky == 1){
        $cmtLogTitle = 'Withdraw failed sbobet to lucky';
      } else {
        $cmtLogTitle = 'Withdraw failed sbobet';
      }
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $err, $request->ip());
      return $this->response(200, $err, $cmtLogTitle, [], false);
    } 

    if($check->error->id != 0){
      if($lucky == 1){
        $cmtLogTitle = 'Withdraw failed sbobet to lucky';
      } else {
        $cmtLogTitle = 'Withdraw failed sbobet';
      }
      LogUser::addLogUser($user->User_ID, $cmtLogTitle, $check->error->msg , $request->ip());
      return $this->response(200, $check->error->msg, $cmtLogTitle, [], false);
    }
    //////////////////// CHUYEN TIEN VAO  /////////////////   

    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => $request->Amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => $cmt,
      'Money_MoneyAction' => $Money_MoneyAction,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => $coin,
      'Money_CurrentAmount' => $request->Amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => $txCode,
      'multiplay_pool'=>$address_pool
    );
    Money::insert($arrayInsert);
    if($lucky == 1){
      $cmtLogTitle = 'Withdraw sbobet to lucky success';
    } else {
      $cmtLogTitle = 'Withdraw sbobet success';
    }
    LogUser::addLogUser($user->User_ID,$cmtLogTitle, 'withraw', $request->ip());
    return $this->response(200, ['balance' => $check->balance, 'amount_withdraw' => $request->Amount], $cmtLogTitle, [], true);
  }

  public function listHistoryBest(Request $request){
    $user = User::find($request->user()->User_ID);
    $betHistory = BetHistoryAeSexy::where('userId', $user->User_ID)
      ->select('id','userId', 'winAmount', 'realBetAmount', 'realWinAmount', 'txTime', 'gameName', 'betTime')->paginate(10);
    return $this->response(200,$betHistory);
  }

  public function saveHistorySportbookSbolive(Request $request)
  {
    $dateCurrent = time();
    if($request->starttime == 1){
      $dateEnd = date('Y-m-d H:i:s',strtotime('-4 hours',$dateCurrent));
      $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));
    }
    if($request->starttime == 2){
      $dateEnd = date('Y-m-d H:i:s',strtotime('-4 hours 30 minute',$dateCurrent));
      $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));
    }
    if($request->starttime == 3){
      $dateEnd = date('Y-m-d H:i:s',strtotime('-5 hours',$dateCurrent));
      $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));
    }
    if($request->starttime == 4){
      $dateEnd = date('Y-m-d H:i:s',strtotime('-5 hours 30 minute',$dateCurrent));
      $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));
    }
    if($request->starttime == 5){
      $dateEnd = date('Y-m-d H:i:s',strtotime('-6 hours',$dateCurrent));
      $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));
    }

    $url = $this->config['url'] . '/web-root/restricted/report/get-bet-list-by-transaction-date.aspx';

    //"StartDate" => "2022-11-01T08:30:31+0700",
    //"EndDate" => "2022-11-15T08:30:31+0700",

    $list_game = DB::table('list_game')->whereIn('name', ['SportsBook', 'SboLive'])->where('show', 1)->where('dealer', 'Sbobet')->get();
    $list_user = User::whereNotNull('User_Name_Sbobet')->whereNotNull('User_Sbobet_Password')->get();

    //dd($list_game, $list_user);
    $results = [];
    $i = 1;
    foreach ($list_game as $v_game) {
      foreach ($list_user as $v_user) {

        $Portfolio = $v_game->name;
        //dd($Portfolio);
        $username = $v_user->User_Name_Sbobet;

        try{ 
          $body = [
            "Username" => $username,
            "Portfolio" => $Portfolio,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "StartDate" => $dateStart,
            "EndDate" => $dateEnd,
            "Language" => 'en',
          ];
          //dd($body);
          $topup_str = json_encode($body);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
          ));

          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
          $result = curl_exec($ch);
          $err = curl_error($ch);

          curl_close($ch);
          $check = json_decode($result);
          $sum = array_column($check->result, 'modifyDate');
          array_multisort($sum, SORT_DESC, $check->result);
        } catch (\Exception $e) {
          continue;
        }



        if ($err) {
          //dd($check);
          echo "cURL Error #:" . $err;
          return $this->response(200, $err, 'faild', [], false);
        } else {
          //dd($check);
          if ($check->error->id != 0) {
            return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
          }

          if ($Portfolio == 'SportsBook' || $Portfolio == 'SboLive') {
            foreach ($check->result as $item) {
              $check_user = User::where('User_Name_Sbobet', $item->username)->first();
              $checkExist = BetHistorySbobet::where('ip', $item->ip)->where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->first();
              $checkStatus = BetHistorySbobet::where('ip', $item->ip)->where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'running')->first();

              if($checkStatus){
                foreach ($item->subBet as $v) {
                  if($item->status != 'running'){
                    //dd($v);
                    $datas_update = [
                      'username' => $item->username,
                      'user_id' => $check_user->User_ID,
                      'sportsType' => $item->sportsType,
                      'bet_winlost' => $item->winLost,
                      'stake' => $item->stake,
                      'amount_win' => $item->winLost + $item->stake,
                      'winLostDate' => $item->winLostDate,
                      'modifyDate' => $item->modifyDate,
                      'currency' => $item->currency,
                      'status' => $item->status,
                      'refNo' => $item->refNo,
                      'Portfolio' => $Portfolio,
                      'ip' => $item->ip,
                      'betOption' => $v->betOption,
                      'marketType' => $v->marketType,
                      'hdp' => $v->hdp,
                      'odds' => $v->odds,
                      'league' => $v->league,
                      'match' => $v->match,
                      'liveScore' => $v->liveScore,
                      'htScore' => $v->htScore,
                      'orderTime' => $item->orderTime,
                      'maxWinWithoutActualStake' => $item->maxWinWithoutActualStake,
                      'oddsStyle' => $item->oddsStyle,
                      'actualStake' => $item->actualStake,
                      'turnover' => $item->turnover,
                      'turnoverByStake' => $item->turnoverByStake,
                      'turnoverByActualStake' => $item->turnoverByActualStake,
                      'netTurnoverByStake' => $item->netTurnoverByStake,
                      'netTurnoverByActualStake' => $item->netTurnoverByActualStake,
                      'isLive' => $item->isLive,
                      'topDownline' => $item->topDownline,
                      'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                    ];
                    BetHistorySbobet::where('refNo', $item->refNo)->update($datas_update);
                    if($item->status == 'lose'){
                      $bonus = logMoney::getBonusFirstBetSportBet($check_user->User_ID, $item->stake, 3);
                    }
                    array_push($results, $datas_update);
                  }

                }
              }

              if ($check && !$checkExist) {
                foreach ($item->subBet as $v) {
                  //dd($v);
                  $datas = [
                    'username' => $item->username,
                    'user_id' => $check_user->User_ID,
                    'sportsType' => $item->sportsType,
                    'bet_winlost' => $item->winLost,
                    'stake' => $item->stake,
                    'amount_win' => $item->winLost + $item->stake,
                    'winLostDate' => $item->winLostDate,
                    'modifyDate' => $item->modifyDate,
                    'currency' => $item->currency,
                    'status' => $item->status,
                    'refNo' => $item->refNo,
                    'Portfolio' => $Portfolio,
                    'ip' => $item->ip,
                    'betOption' => $v->betOption,
                    'marketType' => $v->marketType,
                    'hdp' => $v->hdp,
                    'odds' => $v->odds,
                    'league' => $v->league,
                    'match' => $v->match,
                    'liveScore' => $v->liveScore,
                    'htScore' => $v->htScore,
                    'orderTime' => $item->orderTime,
                    'maxWinWithoutActualStake' => $item->maxWinWithoutActualStake,
                    'oddsStyle' => $item->oddsStyle,
                    'actualStake' => $item->actualStake,
                    'turnover' => $item->turnover,
                    'turnoverByStake' => $item->turnoverByStake,
                    'turnoverByActualStake' => $item->turnoverByActualStake,
                    'netTurnoverByStake' => $item->netTurnoverByStake,
                    'netTurnoverByActualStake' => $item->netTurnoverByActualStake,
                    'isLive' => $item->isLive,
                    'topDownline' => $item->topDownline,
                    'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                  ];
                  BetHistorySbobet::insert($datas);
                  array_push($results, $datas);
                }
              }
            }
          }

        }
      }
    }

    if (empty($results)) {
      dd('Đã thêm thành công toàn bộ dữ liệu');
      //return $this->response(200, [], 'empty', [], false);
    }
    dd('Đã thêm thành công. Đang update những trận đấu');
    //return $this->response(200, $results, 'success', [], false);
    //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');

  }
  public function saveHistoryCasino(Request $request)
  {
    $dateCurrent = time();
    $dateEnd = date('Y-m-d H:i:s',strtotime('-4 hours',$dateCurrent));
    $dateStart = date('Y-m-d H:i:s', strtotime('-30 minute',strtotime($dateEnd)));

    $url = $this->config['url'] . '/web-root/restricted/report/get-bet-list-by-transaction-date.aspx';

    //"StartDate" => "2022-11-01T08:30:31+0700",
    //"EndDate" => "2022-11-15T08:30:31+0700",

    $list_game = DB::table('list_game')->whereIn('name', ['Casino', 'ThirdPartySportsBook'])->where('show', 1)->where('dealer', 'Sbobet')->get();
    $list_user = User::whereNotNull('User_Name_Sbobet')->whereNotNull('User_Sbobet_Password')->get();

    //dd($list_game, $list_user);
    $results = [];

    foreach ($list_game as $v_game) {
      foreach ($list_user as $v_user) {

        $Portfolio = $v_game->name;
        //dd($Portfolio);
        $username = $v_user->User_Name_Sbobet;

        try{
          $body = [
            "Username" => $username,
            "Portfolio" => $Portfolio,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "StartDate" => $dateStart,
            "EndDate" => $dateEnd,
            "Language" => 'en',
          ];
          //dd($body);
          $topup_str = json_encode($body);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
          ));

          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
          $result = curl_exec($ch);
          $err = curl_error($ch);

          curl_close($ch);

          $check = json_decode($result);

          $sum = array_column($check->result, 'modifyDate');

          array_multisort($sum, SORT_DESC, $check->result);
          //dd($check);
        } catch (\Exception $e) {
          continue;
        }

        if ($err) {
          //dd($check);
          echo "cURL Error #:" . $err;
          return $this->response(200, $err, 'faild', [], false);
        } else {
          //dd($check);
          if ($check->error->id != 0) {
            return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
          }
          //dd($check,$dateStart,$dateEnd,$username,$Portfolio);
          //dd($check);

          if ($Portfolio == 'Casino') {
            foreach ($check->result as $item) {
              $check_user = User::where('User_Name_Sbobet', $item->username)->first();
              //dd($check_user);
              $checkExist = BetHistorySbobetCasino::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->first();
              $checkStatus = BetHistorySbobetCasino::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'Running')->first();

              if($checkStatus){
                if($item->status != 'Running'){
                  $datas_update = [
                    'username' => $item->username,
                    'user_id' => $check_user->User_ID,
                    'gameId' => $item->gameId,
                    'winLost' => $item->winLost,
                    'stake' => $item->stake,
                    'amount_win' => $item->winLost + $item->stake,
                    'tableName' => $item->tableName,
                    'turnover' => $item->turnover,
                    'productType' => $item->productType,
                    'orderTime' => $item->orderTime,
                    'modifyDate' => $item->modifyDate,
                    'settleTime' => $item->settleTime,
                    'winLostDate' => $item->winLostDate,
                    'refNo' => $item->refNo,
                    'currency' => $item->currency,
                    'status' => $item->status,
                    'topDownline' => $item->topDownline,
                    'Portfolio' => $Portfolio,
                    'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                  ];
                  BetHistorySbobetCasino::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'Running')->update($datas_update);
                }
                //dd($item, $v);
              }

              if ($check && !$checkExist) {
                $datas = [
                  'username' => $item->username,
                  'user_id' => $check_user->User_ID,
                  'gameId' => $item->gameId,
                  'winLost' => $item->winLost,
                  'stake' => $item->stake,
                  'amount_win' => $item->winLost + $item->stake,
                  'tableName' => $item->tableName,
                  'turnover' => $item->turnover,
                  'productType' => $item->productType,
                  'orderTime' => $item->orderTime,
                  'modifyDate' => $item->modifyDate,
                  'settleTime' => $item->settleTime,
                  'winLostDate' => $item->winLostDate,
                  'refNo' => $item->refNo,
                  'currency' => $item->currency,
                  'status' => $item->status,
                  'topDownline' => $item->topDownline,
                  'Portfolio' => $Portfolio,
                  'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                ];
                BetHistorySbobetCasino::insert($datas);
                array_push($results, $datas);
              }
            }
          }

          if ($Portfolio == 'ThirdPartySportsBook') {
            foreach ($check->result as $item) {
              $check_user = User::where('User_Name_Sbobet', $item->username)->first();
              //dd($check_user);
              $checkExist = BetHistorySbobetThirdPartySportsBook::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->first();
              $checkStatus = BetHistorySbobetThirdPartySportsBook::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'Running')->first();

              if($checkStatus){
                if($item->status != 'Running'){
                  $datas_update = [
                    'username' => $item->username,
                    'user_id' => $check_user->User_ID,
                    'gamePeriodId' => $item->gamePeriodId,
                    'winLost' => $item->winLost,
                    'stake' => $item->stake,
                    'amount_win' => $item->winLost + $item->stake,
                    'gameRoundId' => $item->gameRoundId,
                    'gameType' => $item->gameType,
                    'turnoverStake' => $item->turnoverStake,
                    'orderDetail' => $item->orderDetail,
                    'gameResult' => $item->gameResult,
                    'gameId' => $item->gameId,
                    'gpId' => $item->gpId,
                    'orderTime' => $item->orderTime,
                    'winLostDate' => $item->winLostDate,
                    'refNo' => $item->refNo,
                    'status' => $item->status,
                    'topDownline' => $item->topDownline,
                    'currency' => $item->currency,
                    'Portfolio' => $Portfolio,
                    'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                  ];
                  BetHistorySbobetThirdPartySportsBook::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'Running')->update($datas_update);
                }
                //dd($item, $v);
              }
              if ($check && !$checkExist) {
                $datas = [
                  'username' => $item->username,
                  'user_id' => $check_user->User_ID,
                  'gamePeriodId' => $item->gamePeriodId,
                  'winLost' => $item->winLost,
                  'stake' => $item->stake,
                  'amount_win' => $item->winLost + $item->stake,
                  'gameRoundId' => $item->gameRoundId,
                  'gameType' => $item->gameType,
                  'turnoverStake' => $item->turnoverStake,
                  'orderDetail' => $item->orderDetail,
                  'gameResult' => $item->gameResult,
                  'gameId' => $item->gameId,
                  'gpId' => $item->gpId,
                  'orderTime' => $item->orderTime,
                  'winLostDate' => $item->winLostDate,
                  'refNo' => $item->refNo,
                  'status' => $item->status,
                  'topDownline' => $item->topDownline,
                  'currency' => $item->currency,
                  'Portfolio' => $Portfolio,
                  'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                ];
                BetHistorySbobetThirdPartySportsBook::insert($datas);
                array_push($results, $datas);
              }
            }
          }

        }
      }
    }

    if (empty($results)) {
      dd('Đã thêm thành công toàn bộ dữ liệu');
      //return $this->response(200, [], 'empty', [], false);
    }
    dd('Đã thêm thành công. Đang update những trận đấu');
    //return $this->response(200, $results, 'success', [], false);
    //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');

  }

  public function saveHistorySeamlessGame(Request $request)
  {
    /*$after  = date(DATE_ISO8601, mktime(date("H")-4, date("i")+5, date("s"), date("m"), date("d"), date("Y")));
    $now = new \DateTime($after);

    $start  = date(DATE_ISO8601, mktime(date("H")-5, date("i") - 59, date("s"), date("m"), date("d"), date("Y")));
    $before = new \DateTime($start);

    $dateEnd = $now->format(DateTime::ISO8601);
    $dateStart = $before->format(DateTime::ISO8601);

    $dateEnd = $now->format(DateTime::ISO8601);
    $dateStart = $before->format(DateTime::ISO8601);*/
    $dateCurrent = time();
    $dateEnd = date('Y-m-d H:i:s',strtotime('-4 hours',$dateCurrent));
    $dateStart = date('Y-m-d H:i:s', strtotime('-5 hours',strtotime($dateEnd)));

    $url = $this->config['url'] . '/web-root/restricted/report/get-bet-list-by-transaction-date.aspx';


    $list_game = DB::table('list_game')->where('name', 'SeamlessGame')->where('show', 1)->where('dealer', 'Sbobet')->get();
    $list_user = User::whereNotNull('User_Name_Sbobet')->whereNotNull('User_Sbobet_Password')->get();

    $results = [];

    foreach ($list_game as $v_game) {
      foreach ($list_user as $v_user) {

        $Portfolio = $v_game->name;
        //dd($Portfolio);
        $username = $v_user->User_Name_Sbobet;

        try{
          $body = [
            "Username" => $username,
            "Portfolio" => $Portfolio,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "StartDate" => $dateStart,
            "EndDate" => $dateEnd,
            "Language" => 'en',
          ];
          //dd($body);
          $topup_str = json_encode($body);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
          ));

          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
          $result = curl_exec($ch);
          $err = curl_error($ch);

          curl_close($ch);

          $check = json_decode($result);
          $sum = array_column($check->result, 'modifyDate');
          array_multisort($sum, SORT_DESC, $check->result);
        } catch (\Exception $e) {
          continue;
        }

        if ($err) {
          //dd($check);
          echo "cURL Error #:" . $err;
          return $this->response(200, $err, 'faild', [], false);
        } else {
          //dd($check);
          if ($check->error->id != 0) {
            return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
          }
          //dd($check);
          if ($Portfolio == 'SeamlessGame') {
            foreach ($check->result as $item) {
              $check_user = User::where('User_Name_Sbobet', $item->username)->first();
              $checkExist = BetHistorySbobetSeamless::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->first();
              $checkStatus = BetHistorySbobetSeamless::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'Running')->first();
              if($checkStatus){
                if($item->status != 'Running'){
                  $datas_update = [
                    'username' => $item->username,
                    'user_id' => $check_user->User_ID,
                    'gameType' => $item->gameType,
                    'winLost' => $item->winLost,
                    'stake' => $item->stake,
                    'amount_win' => $item->winLost + $item->stake,
                    'turnoverStake' => $item->turnoverStake,
                    'gameRoundId' => $item->gameRoundId,
                    'gamePeriodId' => $item->gamePeriodId,
                    'orderDetail' => $item->orderDetail,
                    'gameResult' => $item->gameResult,
                    'gameId' => $item->gameId,
                    'gpId' => $item->gpId,
                    'orderTime' => $item->orderTime,
                    'modifyDate' => $item->modifyDate,
                    'settleTime' => $item->settleTime,
                    'winLostDate' => $item->winLostDate,
                    'refNo' => $item->refNo,
                    'currency' => $item->currency,
                    'status' => $item->status,
                    'topDownline' => $item->topDownline,
                    'Portfolio' => $Portfolio,
                    'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                  ];
                  BetHistorySbobetSeamless::where('refNo', $item->refNo)->where('user_id', $check_user->User_ID)->where('status', 'Running')->update($datas_update);
                }
                //dd($item, $v);
              }

              if ($check->result && !$checkExist) {
                //dd($item);
                $datas = [
                  'username' => $item->username,
                  'user_id' => $check_user->User_ID,
                  'gameType' => $item->gameType,
                  'winLost' => $item->winLost,
                  'stake' => $item->stake,
                  'amount_win' => $item->winLost + $item->stake,
                  'turnoverStake' => $item->turnoverStake,
                  'gameRoundId' => $item->gameRoundId,
                  'gamePeriodId' => $item->gamePeriodId,
                  'orderDetail' => $item->orderDetail,
                  'gameResult' => $item->gameResult,
                  'gameId' => $item->gameId,
                  'gpId' => $item->gpId,
                  'orderTime' => $item->orderTime,
                  'modifyDate' => $item->modifyDate,
                  'settleTime' => $item->settleTime,
                  'winLostDate' => $item->winLostDate,
                  'refNo' => $item->refNo,
                  'currency' => $item->currency,
                  'status' => $item->status,
                  'topDownline' => $item->topDownline,
                  'Portfolio' => $Portfolio,
                  'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                ];
                BetHistorySbobetSeamless::insert($datas);
                array_push($results, $datas);
              }
            }
          }

        }
      }
    }

    if (empty($results)) {
      dd('Đã thêm thành công toàn bộ dữ liệu');
      //return $this->response(200, [], 'empty', [], false);
    }
    dd('Đã thêm thành công. Đang update những trận đấu');
    //return $this->response(200, $results, 'success', [], false);
    //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');

  }

  public function saveHistoryVirtual(Request $request)
  {

    $dateCurrent = time();
    $dateEnd = date('Y-m-d H:i:s',strtotime('-4 hours',$dateCurrent));
    $dateStart = date('Y-m-d H:i:s', strtotime('-5 hours',strtotime($dateEnd)));


    $url = $this->config['url'] . '/web-root/restricted/report/get-bet-list-by-transaction-date.aspx';

    //"StartDate" => "2022-11-01T08:30:31+0700",
    //"EndDate" => "2022-11-15T08:30:31+0700",

    $list_game = DB::table('list_game')->where('name', 'VirtualSports')->where('show', 1)->where('dealer', 'Sbobet')->get();
    $list_user = User::whereNotNull('User_Name_Sbobet')->whereNotNull('User_Sbobet_Password')->get();

    //dd($list_game, $list_user);
    $results = [];
    //$dateStart = '2022-12-19 00:00:00';
    //$dateEnd = '2022-12-27 00:00:00';
    foreach ($list_game as $v_game) {
      foreach ($list_user as $v_user) {

        $Portfolio = $v_game->name;
        //dd($Portfolio);
        $username = $v_user->User_Name_Sbobet;

        try{
          $body = [
            "Username" => $username,
            "Portfolio" => $Portfolio,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "StartDate" => $dateStart,
            "EndDate" => $dateEnd,
            "Language" => 'en',
          ];
          //dd($body);
          $topup_str = json_encode($body);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
          ));

          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
          $result = curl_exec($ch);
          $err = curl_error($ch);

          curl_close($ch);

          $check = json_decode($result);


          $sum = array_column($check->result, 'modifyDate');
          array_multisort($sum, SORT_DESC, $check->result);
          //dd($check);

        } catch (\Exception $e) {
          continue;
        }


        if ($err) {
          //dd($check);
          echo "cURL Error #:" . $err;
          return $this->response(200, $err, 'faild', [], false);
        } else {
          //dd($check);
          if ($check->error->id != 0) {
            return $this->response(200, [], 'Unable to proceed. please try again later', [], false);
          }

          if ($Portfolio == 'VirtualSports') {
            foreach ($check->result as $item) {
              $check_user = User::where('User_Name_Sbobet', $item->username)->first();
              $checkExist = BetHistorySbobetVirtualSport::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->first();
              $checkStatus = BetHistorySbobetVirtualSport::where('user_id', $check_user->User_ID)->where('refNo', $item->refNo)->where('status', 'running')->first();

              if($checkStatus){
                foreach ($item->subBet as $v) {
                  if($item->status != 'running'){
                    $datas_update = [
                      'username' => $item->username,
                      'user_id' => $check_user->User_ID,
                      'gameId' => $item->gameId,
                      'winLost' => $item->winLost,
                      'stake' => $item->stake,
                      'amount_win' => $item->winLost + $item->stake,
                      'odds' => $item->odds,
                      'oddsStyle' => $item->oddsStyle,
                      'actualStake' => $item->actualStake,
                      'turnover' => $item->turnover,
                      'productType' => $item->productType,
                      'Portfolio' => $Portfolio,
                      'orderTime' => $item->orderTime,
                      'htScore' => $v->htScore,
                      'ftScore' => $v->ftScore,
                      'betOption' => $v->betOption,
                      'marketType' => $v->marketType,
                      'hdp' => $v->hdp,
                      'match' => $v->match,
                      'htScore' => $v->htScore,
                      'modifyDate' => $item->modifyDate,
                      'settleTime' => $item->settleTime,
                      'winLostDate' => $item->winLostDate,
                      'refNo' => $item->refNo,
                      'currency' => $item->currency,
                      'topDownline' => $item->topDownline,
                      'status' => $item->status,
                      'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                    ];
                    BetHistorySbobetVirtualSport::where('refNo', $item->refNo)->update($datas_update);
                    if($item->status == 'lose'){
                      $bonus = logMoney::getBonusFirstBetSportBet($check_user->User_ID, $item->stake, 3);
                    }
                    array_push($results, $datas_update);
                  }
                  //dd($item, $v);
                }
              }

              if ($check->result && !$checkExist) {

                foreach ($item->subBet as $v) {
                  //dd($item, $v);
                  $datas = [
                    'username' => $item->username,
                    'user_id' => $check_user->User_ID,
                    'gameId' => $item->gameId,
                    'winLost' => $item->winLost,
                    'stake' => $item->stake,
                    'amount_win' => $item->winLost + $item->stake,
                    'odds' => $item->odds,
                    'oddsStyle' => $item->oddsStyle,
                    'actualStake' => $item->actualStake,
                    'turnover' => $item->turnover,
                    'productType' => $item->productType,
                    'Portfolio' => $Portfolio,
                    'orderTime' => $item->orderTime,
                    'htScore' => $v->htScore,
                    'ftScore' => $v->ftScore,
                    'betOption' => $v->betOption,
                    'marketType' => $v->marketType,
                    'hdp' => $v->hdp,
                    'match' => $v->match,
                    'htScore' => $v->htScore,
                    'modifyDate' => $item->modifyDate,
                    'settleTime' => $item->settleTime,
                    'winLostDate' => $item->winLostDate,
                    'refNo' => $item->refNo,
                    'currency' => $item->currency,
                    'topDownline' => $item->topDownline,
                    'status' => $item->status,
                    'statistical_time123betnow' => date('Y-m-d H:i:s', strtotime('+4 hours',strtotime($item->modifyDate))),
                  ];
                  BetHistorySbobetVirtualSport::insert($datas);
                  array_push($results, $datas);
                }
              }
            }
          }

        }
      }
    }

    if (empty($results)) {
      dd('Đã thêm thành công toàn bộ dữ liệu');
      //return $this->response(200, [], 'empty', [], false);
    }
    dd('Đã thêm thành công. Đang update những trận đấu');
    //return $this->response(200, $results, 'success', [], false);
    //dd('Save History Best startdate: '.$dateStart.' - enddate: '.$now.' Success');

  }

  public function getListGameOfSbobet(Request $request){
    $list = DB::table('list_game')->where('show', 1)->where('dealer', 'Sbobet')->get();

    return $this->response(200, $list, '');
  }

  public function postChangePass(Request $request){
    $user = User::find($request->user()->User_ID);
    $validator = Validator::make($request->all(), [
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
    if ($user->User_Sbobet != 1) {
      return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
    }

    include(app_path() . '/functions/xxtea.php');
    $key = 'CD17TT2AI';
    //check OTP
    $tokenOTP = $user->otp_sbobet;
    if(!$tokenOTP) return $this->response(200, [], 'OTP code is not correct', [], false);
    $responseToken = json_decode(xxtea_decrypt(base64_decode($tokenOTP), $key), true);
    if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
    if($responseToken['otp'] != $req->otp) return $this->response(200, [], 'OTP code is not correct', [], false);
    if(strtotime('+5 minutes', $responseToken['time']) < time()) return $this->response(200, [], 'OTP has expired', [], false);

    $user->User_Sbobet_Password = $request->new_password;
    $user->save();
    LogUser::addLogUser($user->User_ID, 'Change password Evo', 'Response data true', $request->ip());
    return $this->response(200, [], 'Change password success!');
  }
}
