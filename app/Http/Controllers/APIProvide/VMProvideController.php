<?php

namespace App\Http\Controllers\APIProvide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Validator;
use App\Model\User;
use App\Model\ProUser;
use App\Model\Money;
use App\Model\ProMoney;
use App\Model\LogUser;
use App\Model\ProBetHistoryWM;
use Carbon\Carbon;
use DB;

class VMProvideController extends Controller
{
  public $config;
  public $is_maintain = 0;

  public function __construct()
  {
    $this->config = config('utils.wm555');
  }

  public function listHistoryBest(Request $request){
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $betHistory = ProBetHistoryWM::where('user_parent', $user->User_ID)
      ->select('id','username', 'user_id', 'bet_amount', 'result_amount','game_type', 'bet_time', 'payout_time', 'bet_source')->orderBy('created_at', 'DESC')->paginate(50);
    return $this->response(200,$betHistory);
  }

  public function saveHistoryBest(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required',
      'user_wm' => 'required',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    /*$checkInsertBet = BetHistoryWM::orderByDesc('created_at')->first();
    $timePeriod = 300;
    //dd($checkInsertBet,$timePeriod, $checkInsertBet->created_at + $timePeriod > time());
    if ($checkInsertBet && $checkInsertBet->created_at + $timePeriod > time()) {
      return 0;
    }
    $user = User::find($request->user()->User_ID);

    if ($user->User_WM555 != 1) {
      return 0;
      //return $this->response(200, [], 'You have no game account, just register!', [], false);
    }
*/
    //if(!$user->User_Name) return $this->response(200, [], 'You have no game account, just register!', [], false);
    $Provide_Key_API = $request->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $user = ProUser::where('User_WM555', $request->user_wm )->first();

    //$startDate = (string)date('Y/m/d 00:00:00', strtotime('-1 minute'));
    //$endDate = (string)date('Y/m/d H:i:s', strtotime('+1 minute', time()));
    $startDate = (string)date('Y/m/d H:i:s', strtotime('+7 hours -10 minute',time())) ;
    $endDate= (string)date('Y/m/d H:i:s', strtotime('+7 hours', time()));
    $dataRaw = [
      'username' => 'now' . $user->User_ID,
      'startDate' => $startDate,//$startDate,
      'endDate' => $endDate,//$endDate,
    ];

    //dd($dataRaw);
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'winloss?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);

    $data = $res->getBody()->getContents();

    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    /*if ($data->data->status == false) {
      //return 0;
      return $this->response(200, [], trans('notification.Get_history_fail'), [], false);
    }*/
    return $this->response(200, $data);
    dd($data->data);
    $username = 'now' . $user->User_ID;
    $betHistoryWM = ProBetHistoryWM::where('created_at', '>=', strtotime('-7 days'))->pluck('bet_id')->toArray();
    //dd($betHistoryWM);
    $results = [];
    //return 0;
    foreach ($data->data as $value) {

      if (!in_array($value->BetID, $betHistoryWM)) {
        $results[] = [
          'username' => $value->Username,
          'user_parent' => $user_pro->User_ID,
          'user_id' => str_replace('now', '', $value->Username),
          'game_type' => $value->GameType,
          'game_id' => $value->GameID,
          'web' => $value->web,
          'bet_id' => $value->BetID,
          'bet_amount' => $value->BetAmount,
          'rolling' => $value->Rolling,
          'result_amount' => $value->ResultAmount,
          'balance' => $value->Balance,
          'game_result' => $value->GameResult,
          'transaction_id' => $value->TransactionID,
          'bet_source' => $value->BetSource,
          'bet_type' => $value->BetType,
          'bet_time' => $value->BetTime,
          'payout_time' => $value->PayoutTime,
          'game_set' => $value->GameSet,
          'host_id' => $value->HostID,
          'host_name' => $value->HostName,
          'off_set' => $value->Offset,
          'created_at' => time(),
        ];
      }
    }

    if (count($results) > 0) ProBetHistoryWM::insert($results);
    return $this->response(200, ['bet_history' => $results, 'count' => count($results)]);
  }

  public function CreateMember(Request $request){
    //dd('createMember:'.$user_parent->User_ID);
    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      'email' => 'required|email|max:255',
      'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
      'password_confirm' => 'required|same:password|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/',
    ], [
      'password.regex' => trans('notification.Your_password_must_be_at_least_8_digits_and_must_be_in_uppercase_and_lowercase_letters_as_well_as_have_at_least_1_number!'),
      'password.min' => trans('notification.Your_password_must_be_at_least_8_digits_and_must_be_in_uppercase_and_lowercase_letters_as_well_as_have_at_least_1_number!'),
      'password_confirm.min' => trans('notification.Your_password_must_be_at_least_8_digits_and_must_be_in_uppercase_and_lowercase_letters_as_well_as_have_at_least_1_number!'),
      'password_confirm.required' => trans('notification.password_required') , 
      'password_confirm.same' => trans('notification.confirm_password_must_be_the_same_as_the_old_password') , 
      'password_confirm.regex' => trans('notification.Your_password_must_be_at_least_8_digits_and_must_be_in_uppercase_and_lowercase_letters_as_well_as_have_at_least_1_number!') , 
      'password.required' => trans('notification.password_required') , 

    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    //if($user->User_Level != 1 && $this->is_maintain == 1) return $this->response(200, [], trans('notification.the_system_is_maintained'), [], false);
    //dd($user);
    $Provide_Key_API = $request->key_api;
    $user = User::where('Provide_Key_API', $Provide_Key_API)->first();
    $user_pro = ProUser::where('User_Email', $request->email )->first();
    if($user_pro && ($user_pro->User_WM_Password != NULL) ){
      return $this->response(200, [], [], 'Account already exists' , false);
    }
    if(!$user_pro){
      return $this->response(200, [], [], 'Email is not exists' , false);
    }

    // if ($user->User_WM555) return $this->response(200, [], trans('notification.You_are_already_registered'), [], false);
    //$request->username = 'now' . $user_pro->User_ID;
    //$request->nickname = 'now' . $user_pro->User_ID;
    $dataRaw = [
      'username' => 'now' . $user_pro->User_ID,
      'nickname' => 'now' . $user_pro->User_ID,
      'password' => $request->password,
    ];

    $client = new Client();
    $apiKey = $this->config['key'];
    //		dd( $this->config['url'].'addmember?apikey='.$apiKey , json_encode($dataRaw));
    $res = $client->request('POST', $this->config['url'] . 'addmember?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);
    $data = $res->getBody()->getContents();
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    //dd( $this->config['url'].'addmember?apikey='.$apiKey , $data, json_encode($dataRaw));
    if (!$data || $data->error_code != 0 || $data->data->status == false) {

      //LogUser::addLogUser($user->User_ID, 'register failed wm555', $data->data->message ?? 'Response data false', $request->ip());
      return $this->response(200, [], $data->data->message ?? trans('notification.Register_fail_Please_try_again'), [], false);
    }
    //LogUser::addLogUser($user->User_ID, 'register success wm555', $data->data->message ?? 'Response data false', $request->ip());

    //$user->User_WM_Password = $request->password;
    //$user->User_WM555 = 1;
    //$user->save();
    $d = [
      'User_WM_Password' => $request->password,
      'User_WM555' => 'now' . $user_pro->User_ID,
    ];
    ProUser::where('User_Email', $request->email )->update($d);
    return $this->response(200, ['user_wm'=> 'now'.$user_pro->User_ID], trans('notification.Register_WM555_Successful'));
  }
  public function postChangePass(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      'user_wm' => 'required',
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
    $user = ProUser::where('User_WM555', $request->user_wm )->first();
    //$user = User::find($request->user()->User_ID);
    //if ($user->User_Level != 1 && $this->is_maintain == 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    if (!$user) {
      return $this->response(200, [], trans('notification.You_have_not_registered!'), [], false);
    }

    if ($user->User_WM_Password !== $request->password) {
      return $this->response(200, [], trans('notification.Old_password_is_incorrect'), [], false);
    }
    if ($request->password === $request->new_password) {
      return $this->response(200, [], trans('notification.New_password_and_old_password_cannot_be_the_same!'), [], false);
    }
    //$request->username = 'now' . $user->User_ID;
    $dataRaw = [
      'username' => 'now' . $user->User_ID,
      'password' => $request->new_password,
    ];
    //dd($dataRaw);
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'changepassword?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);
    $data = $res->getBody()->getContents();
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    //dd( $this->config['url'].'addmember?apikey='.$apiKey , $data, json_encode($dataRaw));
    if (!$data || $data->error_code != 0 || $data->data->status == false) {

      //LogUser::addLogUser($user->User_ID, 'Change password wm555', $data->data->message ?? 'Response data false', $request->ip());
      return $this->response(200, [], $data->data->message ?? trans('notification.Change_password_fail_Please_try_again!'), [], false);
    }

    ProUser::where('User_WM555', $request->user_wm )->update(['User_WM_Password'=> $request->new_password]);
    //$user->User_WM_Password = $request->new_password;
    //$user->save();
    //LogUser::addLogUser($user->User_ID, 'Change password wm555', $data->data->message ?? 'Response data true', $request->ip());
    return $this->response(200, [], trans('notification.Change_Password_WM555_Successful'));
  }
  public function deposit(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required',
      'user_wm' => 'required',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'amount' => 'required|numeric|min:50',
    ],[
      'amount.required' => trans('notification.amount_required') ,
      'amount.min' => trans('notification.minimum_amount_50') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $user = ProUser::where('User_WM555', $request->user_wm )->first();
    $Provide_Key_API = $request->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    //$user = User::find($request->user()->User_ID);
    //if ($user->User_Level != 1 && $this->is_maintain == 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    if (!$user) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    //if($user->User_Level != 1) return $this->response(200, [], 'Please come back later!', [], false);

    //$request->username = 'now' . $user->User_ID;
    //$userBalance = User::getBalance($user->User_ID);

    //if ($userBalance < $request->amount) return $this->response(200, [], trans('notification.Your_balance_is_not_enough'), [], false);

    // Minus balance in 123betnow

    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_Parent_ID'=>$user_pro->User_ID,
      'Money_USDT' => $request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Deposit to WM555 with ' . $request->amount . ' point',
      'Money_MoneyAction' => 75,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      //'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      //'Money_FromAPI' => 1,
    );
    ProMoney::insert($arrayInsert);

    $dataRaw = [
      'username' => 'now' . $user->User_ID,
      'amount' => $request->amount,
    ];
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'deposited?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);

    $data = $res->getBody()->getContents();
    //        dd($this->config['url'] . 'deposited?apikey=' . $apiKey, $data, json_encode($dataRaw));
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));

    //return $this->response(200, ['data' => $res->getBody()], 'Deposit to WM555 game successful');
    //if($data->error_code != 0 || $data->data == false) return $this->response(200, [], 'Deposit Fail! Please Try Again!', [], false);

    if (!$data || $data->error_code != 0 || $data->data->status == false) {
      //$cancel = Money::where('Money_ID', $id)->update(['Money_MoneyStatus' => -1]);
      //LogUser::addLogUser($user->User_ID, 'Deposit WM555', $data->data->message ?? 'Response data false', $request->ip());
      //LogUser::addLogUser($user->User_ID, 'Deposit WM555', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($dataRaw) . ') RESPONSE: ' . ($data->data->message ?? 'Response data false'), $request->ip());
      return $this->response(200, [], $data->data->message ?? trans('notification.Deposit_fail_Please_try_again'), [], false);
    }
    //LogUser::addLogUser($user->User_ID, 'Deposit Success WM555', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($dataRaw) . ') RESPONSE: ' . ($res->getBody()->getContents()), $request->ip());

    return $this->response(200, [], trans('notification.Deposit_to_WM555_game_successful'));

  }

  public function withdraw(Request $request)
  {
    $validator = Validator::make($request->all(), [
      //'username' => 'required',
      'user_wm' => 'required',
      'password' => 'required|min:6|max:12|regex:/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*?[a-z])(?=.*\d)[A-Za-z\d]{6,}$/',
      'amount' => 'required|numeric|min:100',
    ],[
      'amount.required' => trans('notification.amount_required') ,
      'amount.min' => trans('notification.amount_min_100') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    //$user = User::find($request->user()->User_ID);
    //if ($user->User_Level != 1 && $this->is_maintain == 1) return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);
    //dd($user);
    $user = ProUser::where('User_WM555', $request->user_wm)->first();
    $Provide_Key_API = $request->key_api;
    $user_pro = User::where('Provide_Key_API', $Provide_Key_API)->first();
    if (!$user) {
      return $this->response(200, [], trans('notification.Please_register!'), [], false);
    }
    //if($user->User_Level != 1) return $this->response(200, [], 'Please come back later!', [], false);
    //$request->username = 'now' . $user->User_ID;
    $userBalance = $this->getBalance($user->User_ID);

    if($userBalance < $request->amount) return $this->response(200, [], 'Your balance is not enough', [], false);

    $dataRaw = [
      'username' => 'now' . $user->User_ID,
      'amount' => $request->amount,
    ];
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'withdraw?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);

    $data = $res->getBody()->getContents();
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    //        dd($data, $this->config['url'], $this->config['url'].'withdraw?apikey='.$apiKey, json_encode($dataRaw));
    //return $this->response(200, ['data' => $res->getBody()], 'Deposit to WM555 game successful');
    if (!isset($data) || !$data || $data->error_code != 0 || $data->data->status == false) {
      //LogUser::addLogUser($user->User_ID, 'Withdraw WM555', $data->data->message ?? 'Response data false', $request->ip());
      //LogUser::addLogUser($user->User_ID, 'Withdraw WM555', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($dataRaw) . ') RESPONSE: ' . $data->data->message ?? 'Response data false', $request->ip());
      return $this->response(200, [],  trans('notification.Withdraw_fail_Please_try_again!'), [], false);
    }
    //LogUser::addLogUser($user->User_ID, 'Deposit Success WM555', 'URL: (' . ($this->config['url'] . 'deposited?apikey=' . $apiKey) . ') PARAM: (' . json_encode($dataRaw) . ') RESPONSE: ' . ($res->getBody()->getContents()), $request->ip());
    // Minus balance in 123betnow
    $arrayInsert = array(
      'Money_User' => $user->User_ID,
      'Money_Parent_ID'=>$user_pro->User_ID,
      'Money_USDT' => -$request->amount,
      'Money_USDTFee' => 0,
      'Money_Time' => time(),
      'Money_Comment' => 'Withdraw from WM555 with ' . $request->amount . ' point',
      'Money_MoneyAction' => 76,
      'Money_MoneyStatus' => 1,
      'Money_Address' => null,
      //'Money_Currency' => 3,
      'Money_CurrentAmount' => $request->amount,
      'Money_CurrencyFrom' => 0,
      'Money_CurrencyTo' => 0,
      'Money_Rate' => 1,
      'Money_Confirm' => 0,
      'Money_Confirm_Time' => null,
      //'Money_FromAPI' => 1,
    );
    ProMoney::insert($arrayInsert);

    return $this->response(200, [], trans('notification.Withdraw_from_WM555_game_successful'));

  }
  public function getBalance($user_id)
  {
    //$user = Auth::user();
    /*
        $request = new Request;
        $getListMember = $this->listMemberWM($request);
        if($user_id == 123123){
            //dd($getListMember, $user_id);
      }
        $balance = 0;
        foreach($getListMember as $member){
            if($member->username == 'now'.$user_id){
                //dd($member, $member->quota);
                $balance = $member->quota;
                break;
          }
      }
        return $balance;
      */
    //return 0;
    $dataRaw = [
      'username' => 'now' . $user_id,
    ];
    $client = new Client();
    $apiKey = $this->config['key'];
    $res = $client->request('POST', $this->config['url'] . 'userinfo?apikey=' . $apiKey, [
      'body' => json_encode($dataRaw)
    ]);
    $data = $res->getBody()->getContents();
    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data));
    //        dd($data);
    //      	$data = json_decode($data);
    //        dd($data, $this->config['url'], $this->config['url'].'userinfo?apikey='.$apiKey, json_encode($dataRaw));
    //dd( $this->config['url'].'addmember?apikey='.$apiKey , $data, json_encode($dataRaw));
    if ($data->error_code != 0 || $data->data == null) {
      return 0;
    }
    return $data->data->quota;
  }
}