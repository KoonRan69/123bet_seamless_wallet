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
use Carbon\Carbon;
use DB;
class G789APIController extends Controller
{
  public $config;

  public function __construct()
  {
    $this->middleware('auth:api');
    $this->config = config('url789.789api');
  }
  /**
     * @var string
     * 接口地址
     */

  //游戏平台类型 小写
  protected $plat_type = 'ag';

  protected $game_type_live = '1'; // 真人娱乐
  protected $game_type_slot = '2'; // 老虎机
  protected $game_type_lottery = '3'; // 彩票
  protected $game_type_sports = '4'; // 体育
  protected $game_type_esports = '5'; // 电竞
  protected $game_type_fishing = '6'; // 捕鱼
  protected $game_type_poker = '7'; // 棋牌

  /**
     * @param int $isMobileUrl
     * @param string $gameCode
     * @return mixed
     * 获取游戏登录地址
     */
  public function login(Request $request)
  {
    $user = User::find($request->user()->User_ID);
    if ($user->User_789API){
      $username = $user->User_789API;
    }
    $username = 'now' . $user->User_ID;
    $isMobileUrl = 0;
    $gameCode = "";
    $apiKey = $this->config['key'];
    $apiAccount = $this->config['account'];
    $code = md5($apiKey . $apiAccount . $username . $this->plat_type . $isMobileUrl);
    $data = array(
      "username" => $username,
      "plat_type" => $this->plat_type,
      "game_type" => $this->game_type_live,
      "game_code" => $gameCode,
      "sign_key" => $apiKey,
      "is_mobile_url" => $isMobileUrl,
      "code" => $code,
    );
    $res = $this->sendRequest($this->config['url'].'user/login', $data);

    $data = array();
    $data['message'] = $res['message'];
    $data['data'] = $res['data'];
    $data['status'] = $res['statusCode'];

    if (!$data['data'] || $data['status'] != 01 ) {
      LogUser::addLogUser($user->User_ID, 'Login failed 789API',  $data['message'] ?? 'Response data false', $request->ip());
      return $this->response(200, [],  $data['message'] ?? 'Login fail! Please try again!', [], false);
    }
    if (!$user->User_789API){
      $user->User_789API = $username;
      $user->save();
    }
    LogUser::addLogUser($user->User_ID, 'Login 789API', $data['message'] ?? 'Response data true', $request->ip());
    return $this->response(200, $data['data'], $data['message'], [], true);
  }

  /**
     * @param $money
     * @return mixed
     * 额度转换
     */
  public function deposit789API(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required',
      'money' => 'required|numeric|integer|min:1',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($request->money <= 0){
      return $this->response(200, [], trans('notification.deposit_amount_must_be_greater_than_0'), [], false);
    }
    $user = User::find($request->user()->User_ID);
    if(!$user->User_789API){
      return $this->response(200, [], 'Error!', [], false);
    }
    $userBalance = User::getBalance($user->User_ID);
    if ($userBalance < $request->money) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);
    $username = $user->User_789API;
    //nạp là số dương
    $money = $request->money;
    $client_transfer_id =  $this->RandonOder();
    // Minus balance in 123betnow
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => -$request->money,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to 789API with ' . $request->money . ' EUSD',
      'Money_MoneyAction' => 79,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->money,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => $client_transfer_id,
    );
    $id = Money::insertGetId($arrayInsert);

    $apiKey = $this->config['key'];
    $apiAccount = $this->config['account'];
    $code = md5($apiKey . $apiAccount . $username . $this->plat_type . $money . $client_transfer_id);
    $data = array(
      "username" => $username,
      "plat_type" => $this->plat_type,
      "money" => $money,
      "client_transfer_id" => $client_transfer_id,
      "sign_key" => $apiKey,
      "code" => $code,
    );

    $res = $this->sendRequest($this->config['url'].'user/trans', $data);
    $data = array();
    $data['message'] = $res['message'];
    $data['data'] = $res['data'];
    $data['status'] = $res['statusCode'];
    if (!$data['data'] || $data['status'] != 01) {
      $cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
      //LogUser::addLogUser($user->User_ID, 'Deposit WM555', $data->data->message ?? 'Response data false', $request->ip());
      LogUser::addLogUser($user->User_ID, 'Deposit 789API', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($data) . ') RESPONSE: ' . ($data['message'] ?? 'Response data false'), $request->ip());
      return $this->response(200, [], $data->data->message ?? trans('notification.Deposit_fail_Please_try_again'), [], false);
    }
    LogUser::addLogUser($user->User_ID, 'Deposit Success 789API', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($data) . ') RESPONSE: ',$request->ip());


    return $this->response(200, $data['data'], $data['message'], [], true);
  }
  public function withdraw789API(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required',
      'money' => 'required|numeric|min:1',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($request->money <= 0){
      return $this->response(200, [], trans('notification.withdrawal_must_be_greater_than_0'), [], false);
    }
    $user = User::find($request->user()->User_ID);
    if(!$user->User_789API){
      return $this->response(200, [], trans('notification.Error'), [], false);
    }

    $username = $user->User_789API;
    //rút là số âm
    $money = -$request->money;

    $client_transfer_id =  $this->RandonOder();
    $apiKey = $this->config['key'];
    $apiAccount = $this->config['account'];

    $code = md5($apiKey . $apiAccount . $username . $this->plat_type . $money . $client_transfer_id);
    $data = array(
      "username" => $username,
      "plat_type" => $this->plat_type,
      "money" => $money,
      "client_transfer_id" => $client_transfer_id,
      "sign_key" => $apiKey,
      "code" => $code,
    );

    $res = $this->sendRequest($this->config['url'].'user/trans', $data);
    $data = array();
    $data['message'] = $res['message'];
    $data['data'] = $res['data'];
    $data['status'] = $res['statusCode'];
    if (!$data['data'] || $data['status'] != 01) {
      //LogUser::addLogUser($user->User_ID, 'Deposit WM555', $data->data->message ?? 'Response data false', $request->ip());
      LogUser::addLogUser($user->User_ID, 'Withdraw 789API', 'URL: (' . ($this->config['url'] . 'user/trans?apikey=' . $apiKey) . ') PARAM: (' . json_encode($data) . ') RESPONSE: ' . ($data['message'] ?? 'Response data false'), $request->ip());
      return $this->response(200, [], $data['message'] ?? trans('notification.Deposit_fail_Please_try_again'), [], false);
    }
    LogUser::addLogUser($user->User_ID, 'Withdraw Success 789API', 'URL: (' . ($this->config['url'] . 'user/trans?apikey=' . $apiKey) . ') PARAM: (' . json_encode($data) . ') RESPONSE: ' ,$request->ip());
    // Minus balance in 123betnow
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_USDT' => $request->money,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Withdraw from 789API with ' . $request->money . ' EUSD',
      'Money_MoneyAction' => 80,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->money,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      'Money_FromAPI' => 1,
      'Money_TXID' => $client_transfer_id,
    );
    $id = Money::insertGetId($arrayInsert);

    return $this->response(200, $data['data'], $data['message'], [], true);
  }

  /**
     * @param $username
     * @return mixed
     * 获取用户余额
     */
  public function balance(Request $request)
  {
    $user = User::find($request->user()->User_ID);
    if(!$user->User_789API){
      return $this->response(200, [], trans('notification.Error'), [], false);
    }
    $username = $user->User_789API;
    $apiKey = $this->config['key'];
    $apiAccount = $this->config['account'];
    $code = md5($apiKey . $apiAccount . $username . $this->plat_type);

    $data = array(
      "username" => $username,
      "plat_type" => $this->plat_type,
      "sign_key" => $apiKey,
      "code" => $code,
    );
    $res = $this->sendRequest($this->config['url'].'user/balance', $data);
    $data = array();
    $data['message'] = $res['message'];
    $data['data'] = $res['data'];
    $data['status'] = $res['statusCode'];
    if (!$data['data'] || $data['status'] != 01) {
      return 0;
    }
    return $this->response(200, $data['data'], $data['message'], [], true);
  }
  //lịch sử bet đánh chưa được
  public function betHistory789API(Request $request){
    $user = User::find($request->user()->User_ID);
    if(!$user->User_789API){
      return $this->response(200, [], trans('notification.Error'), [], false);
    }
    $username = $user->User_789API;
    $apiKey = $this->config['key'];
    $apiAccount = $this->config['account'];
    $startTime = $request->startTime;
    $endTime = $request->endTime;
    $page = 1;
    $limit = 100;
    $code = md5($apiKey . $apiAccount . $startTime . $endTime);
    $data = array(
      "plat_type" => $this->plat_type,
      "game_type" => $this->game_type_live,
      "username" => $username,
      "idStr" =>'',
      "sign_key" => $apiKey,
      "startTime" => $startTime,
      "endTime" => $endTime,
      "timeType" => 0,
      "page" => $page,
      "limit" => $limit,
      "code" => $code,
    );
    $res = $this->sendRequest($this->config['url'].'user/record-all', $data);
    return $res;
    $data = array();
    $data['message'] = $res['message'];
    $data['data'] = $res['data'];
    $data['status'] = $res['statusCode'];
    if (!$data['data'] || $data['status'] != 01) {
      return 0;
    }
    return $this->response(200, $data['data'], $data['message'], [], true);
  }
  public function listMember789API(Request $req){
    $where = null;
    if ($req->UserID) {
      $where .= ' AND User_ID=' . $req->UserID;
    }
    if ($req->Username) {
      $where .= ' AND User_Name LIKE "' . $req->Username . '"';
    }
    if ($req->Email) {
      $where .= ' AND User_Email LIKE "%' . $req->Email . '%"';
    }
    if ($req->sponsor) {
      $where .= ' AND User_Parent = ' . $req->sponsor;
    }
    if ($req->agency_level) {
      $where .= ' AND User_Agency_Level = ' . $req->agency_level;
    }
    if ($req->datetime) {
      $where .= ' AND date(User_RegisteredDatetime) = "' . date('Y-m-d', strtotime($req->datetime)) . '"';
    }
    if ($req->status_email != null) {
      $where .= ' AND User_EmailActive = ' . $req->status_email;
    }
    if ($req->user_level != null) {
      $where .= ' AND User_Level = ' . $req->user_level;
    }
    if ($req->tree != '') {

      $where .= ' AND User_Tree LIKE "%' . str_replace(', ', ',', $req->tree) . '%"';
    }
    if ($req->suntree != '') {

      $where .= ' AND User_SunTree LIKE "%' . str_replace(', ', ',', $req->suntree) . '%"';
    }

    $user_list = User::leftJoin('google2fa', 'google2fa.google2fa_User', 'users.User_ID')
      ->join('user_level', 'User_Level_ID', 'User_Level')
      ->whereNotNull("User_789API")
      ->whereRaw('1 ' . $where)
      ->orderBy('User_RegisteredDatetime', 'DESC');

    $user_list = $user_list->paginate(50);

    $user_level = DB::table('user_level')->orderBy('User_Level_ID')->get();
    $user_agency_level = DB::table('user_agency_level')->orderBy('user_agency_level_ID')->get();
    $listSetAgency = DB::table('set_agency')->where('status', 1)->pluck('level', 'user')->toArray();
    return $this->response(200, [
      "user_list" => $user_list,
      "user_level" => $user_level,
      "user_agency_level" =>$user_agency_level,
      "listSetAgency"=>$listSetAgency,
    ], "", [], true);

  }
  //Lịch sử
  public function historyWallet789API(Request $req){
    $user = User::find($req->user()->User_ID);
    if(!$user->User_789API){
      return $this->response(200, [], trans('notification.Error'), [], false);
    }
    $limit = 20;
    $money = DB::table('money')
      ->leftjoin('moneyaction', 'Money_MoneyAction', 'MoneyAction_ID')
      ->leftjoin('currency', 'Money_Currency', 'Currency_ID')
      ->where('Money_MoneyStatus', 1)
      ->wherein('Money_MoneyAction',[79,80])
      ->where('Money_User', $user->User_ID)
      ->orderByDesc('Money_ID');
    if($req->from){
      $from = strtotime($req->from);
      $money = $money->where('Money_Time', '>=', $from);
    }
    if($req->to){
      $to = strtotime($req->to);
      $money = $money->where('Money_Time', '<=', $to);
    }
    $money = $money->paginate($limit);
    $list = [];
    for($i = 0; $i < count($money); $i++){
      $status = '';
      if($money[$i]->Money_MoneyStatus == 1){
        $status = 'Active';
      }
      if($money[$i]->Money_MoneyStatus == 2){
        $status = 'Waiting';
      }
      if($money[$i]->Money_MoneyStatus == -1){
        $status = 'Cancel';
      }
      $list[$i] = [
        'id'=> $money[$i]->Money_ID,
        'Amount' => $money[$i]->Money_USDT,
        'Fee'=> $money[$i]->Money_USDTFee*1,
        'Rate'=>$money[$i]->Money_Rate*1,
        'Currency'=> $money[$i]->Currency_Name,
        'Action'=> $money[$i]->MoneyAction_Name,
        'comment'=> $money[$i]->Money_Comment,
        'Time' => date('Y-m-d H:i:s',$money[$i]->Money_Time),
        'Status' => $status
      ];
    }
    $data = ['history'=>$list, 'current_page'=>$money->currentPage(), 'total_page'=>$money->lastPage() ];
    return response(array('status'=>true, 'data'=>$data), 200);
  }
  private function sendRequest($url, $post_data = array())
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $contents = curl_exec($ch);
    curl_close($ch);
    $getData = json_decode($contents, true);
    //print_r($data );
    //exit();

    return json_decode($contents, true);
  }
  public static function RandonOder()
  {
    $id =  md5(uniqid(rand(), true));
    $order =Money::where('Money_TXID', $id)->first();
    if (!$order) {
      return $id;
    } else {
      return $this->RandonOder();
    }
  }
}
