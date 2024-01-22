<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Crypt;

use App\Model\User;
use App\Model\Profile;
use App\Model\GoogleAuth;
use App\Model\Investment;
use App\Model\userBalance;

use App\Mails\UserForgotPassword;
use App\Mails\UserSignUp;
use App\Model\LogUser;
use App\Model\Agency;
use App\Model\Money;

use App\Model\MUser;
use App\Model\Eggs;
use App\Model\Foods;
use App\Model\Pools;
use App\Model\ListMission;

use Carbon\Carbon;

use GuzzleHttp\Client;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Jobs\SendMailJobs;
class AuthAddressController extends Controller
{
  public $key;
  public $urlApi;
  public $agid;
  public $lang;
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['postLoginAddress', 'postRegisterAddress', 'resentMail', 'postForgetPassword', 'getCountries', 'postAddAgency']]);
    $this->middleware('2fa')->only(['postLogin']);;
    $ag = config('ag');
    $this->key = $ag['key'];
    $this->urlApi = $ag['url'];
    $this->agid = $ag['agid'];
    $this->lang = $ag['lang'];
  }
  public function postUpdateAddress(Request $request){
    $user = Auth::user();
    if(!$user){
      return $this->response(200, [], trans('notification.please_login'), [], false);
    }
    //return false;
    $user = User::where('User_ID', $user->User_ID)->first();
    if(!$user){
      return $this->response(200, [], trans('notification.please_login'), [], false);
    }
    if($user->User_WalletAddress != null){
      return $this->response(200, [], trans('notification.Error_Updated_wallet'), [], false);
    }
    $validator = Validator::make($request->all(), [
      'address' => 'required|string|regex:/0x[a-fA-F0-9]{40}/m'
    ],[
      'address.required' => trans('notification.address_requaired') ,
      'address.regex' => trans('notification.the_address_is_not_in_the_correct_format') ,
    ]);

    $checkWallet = User::where('User_WalletAddress', $request->address)->first();
    if($checkWallet){
      return $this->response(200, ['require_auth' => false], trans('notification.your_wallet_address_is_already_in_the_system'), [], false);
    }

    $message = 'NOW'.$user->User_ID;
    $address = $request->address;
    $signature = $request->signature;
    if(!$signature || $signature == null){
      return $this->response(200, ['message'=>$message], [], [], true);
    }
    if(!$request->message || $request->message == null){
      return $this->response(200, [], trans('notification.Message_cannot_be_null'), [], false);
    }
    $dataSend = [
      'wallet' => $address,
      'sign' => $signature,
      'message' => $request->message
    ];
    //dd(json_encode($dataSend));
    $client = new Client();
    $res = $client->request('POST', 'https://auto-spread.123betnow.net/api/v1/sign', [
      'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
      'body' => json_encode($dataSend),
    ]);
    $data = json_decode($res->getBody()->getContents());
    if($data->status == false){
      return $this->response(200, [], trans('notification.update_wallet_address_false'), [], false);
    }
    $user->User_WalletAddress = $address;
    $user->save();
    return $this->response(200, [], trans('notification.update_wallet_address_success'), [], true);
  }
  public function postLoginAddress(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'address' => 'required|string|regex:/0x[a-fA-F0-9]{40}/m'
    ],[
      'address.required' => trans('notification.address_requaired') ,
      'address.regex' => trans('notification.the_address_is_not_in_the_correct_format') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $random = rand(1, 100);
    $now = time() + $random;

    $user = User::where('User_WalletAddress', $request->address)->first();

    if (!$user) {
      return $this->response(200, ['require_auth' => false], trans('notification.User_is_not_exist'), [], false);
    }
    if($user->User_Level != 1){
      //return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }
    if($user->User_Block == 1){
      return $this->response(200, [], trans('notification.Please_come_back_later'), [], false);
    }
    if($user->User_EmailActive != 1){
      return $this->response(200, [], trans('notification.Please_active_your_email!'), [], false);
    }
    /*
		$credentials = [
			'User_Email' => $request->email,
			'password' => $request->password,
		];

		if (!Auth::attempt($credentials)) {
			return $this->response(200, ['require_auth' => false], 'Login information is not valid', [], false);
		}


		$google2fa = app('pragmarx.google2fa');
		$auth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();

		if ($auth) {
			if (!$request->authCode) {
				return $this->response(200, ['require_auth' => true], 'Please enter your authentication code', ['auth' => false]);
			}
			$valid = $google2fa->verifyKey($auth->google2fa_Secret, $request->authCode);
			if (!$valid) {
				return $this->response(200, [], 'Code wrong', [], false);
			}
		}*/
    //xác minh address
    //check address
    $message = 'NOW'.$user->User_ID;
    $address = $request->address;
    $signature = $request->signature;
    if(!$signature || $signature == null){
      return $this->response(200, ['message'=>$message], [], [], true);
    }
    if(!$request->message || $request->message == null){
      return $this->response(200, [], trans('notification.message_cannot_be_null'), [], false);
    }
    /*
        $code = explode('NOW', $request->message);
      	$code = $code[1];
      	$checkCode = User::where('User_ID', $code)->first();
      	if($checkCode){
          	return $this->response(200, [], 'Error login!', [], false);
        }
        */
    $dataSend = [
      'wallet' => $address,
      'sign' => $signature,
      'message' => $request->message
    ];
    //dd(json_encode($dataSend));
    $client = new Client();
    $res = $client->request('POST', 'https://auto-spread.123betnow.net/api/v1/sign', [
      'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
      'body' => json_encode($dataSend),
    ]);
    $data = json_decode($res->getBody()->getContents());
    if($data->status == false){
      return $this->response(200, [], trans('notification.login_false'), [], false);
    }


    // xoa token cu
    DB::table('oauth_access_tokens')->where('user_id', $user->User_ID)->delete();

    //dd($user);
    //$user = $request->user();
    //dd($user);
    if ($user) {
      $user->User_Log = $now;
      $user->save();
    }

    $tokenResult = $user->createToken('BeTNoW');
    $token = $tokenResult->token;
    //dd($token);

    $loginType = config('utils.action.login');
    LogUser::addLogUser($user->User_ID, $loginType['action_type'], $loginType['message'], $request->ip());

    $token->save();

    return $this->response(200, [
      'token' => $tokenResult->accessToken,
      'token_type' => 'Bearer',
      'id' => (int)$user->User_ID
    ]);
  }


  public function getLogout(Request $request)
  {
    if (Auth::check()) {

      $accessToken = $request->user()->token();

      $user = $request->user();
      $logoutType = config('utils.action.logout');
      LogUser::addLogUser($user->User_ID, $logoutType['action_type'], $logoutType['message'], $request->ip());
      DB::table('oauth_refresh_tokens')
        ->where('access_token_id', $accessToken->id)
        ->update([
          'revoked' => true
        ]);

      $accessToken->revoke();

      return $this->response(200, [], trans('notification.logout_complete'));
    } else {
      return $this->response(200, [], trans('notification.Logout_fail') , [], false);
    }
  }


  /**
    * function postRegister
    *
    * @param username
    * @param nickname
    * @param password
    * @param email
    * @param sponsor
    */
  public function postRegisterAddress(Request $request)
  {
    if($request->sponsor != '0xd1323ACE26d2cCB30afD3FB023F2517B9d50a0AF'){
      //return $this->response(200, [], 'The registration system is under maintenance!', [], false);
    }

    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      //'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*[0-9])/',
      'address' => 'required|string|regex:/0x[a-fA-F0-9]{40}/m'
    ],[
      'address.required' => trans('notification.address_requaired') ,
      'address.regex' => trans('notification.the_address_is_not_in_the_correct_format') ,
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $checkAddress = User::where('User_WalletAddress', $request->address)->first();
    if($checkAddress){
      return $this->response(200, [], trans('notification.your_wallet_address_is_already_in_the_system'), [], false);
    }
    $parents = 801479;
    if ($request->sponsor) {
      $parents = $request->sponsor;
    }

    $InfoPonser = User::where('User_WalletAddress', $parents)->orWhere('User_ID', $parents)->first();

    if (!$InfoPonser) {
      //	$InfoPonser = User::where('User_ID', $parents)->first();
      //	if(!$InfoPonser){
      return $this->response(200, [], trans('notification.Sponsor_doesnt_exists'), [], false);
      //}
    }
    $parentsID = $InfoPonser->User_ID;
    //$password = Hash::make($request->password);
    $UserID = $this->RandonIDUser();



    //check address
    $message = 'NOW'.$UserID;
    $address = $request->address;
    $signature = $request->signature;
    if(!$signature || $signature == null){
      return $this->response(200, ['message'=>$message], [], [], true);
    }
    if(!$request->message || $request->message == null){
      return $this->response(200, [], trans('notification.Message_cannot_be_null'), [], false);
    }
    $code = explode('NOW', $request->message);
    $code = $code[1];
    $checkCode = User::where('User_ID', $code)->first();
    if($checkCode){
      return $this->response(200, [], trans('notification.error_register'), [], false);
    }
    $dataSend = [
      'wallet' => $address,
      'sign' => $signature,
      'message' => $request->message
    ];
    //dd(json_encode($dataSend));
    $client = new Client();
    $res = $client->request('POST', 'https://auto-spread.123betnow.net/api/v1/sign', [
      'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
      'body' => json_encode($dataSend),
    ]);
    $data = json_decode($res->getBody()->getContents());
    if($data->status == false){
      return $this->response(200, [], $data->message.'!', [], false);
    }
    $UserID = $code;
    //Tạo token cho mail
    $dataToken = array('user_id' => $UserID, 'time' => time());
    $userTree = $InfoPonser->User_Tree . ',' . $UserID;
    $token = encrypt(json_encode($dataToken));

    $level = 0;
    if (strpos($userTree, '123123') !== false) {
      $level = 5;
    }
    //dd($data,$UserID,$userTree);
    //$UserID = $UserID;
    //dd($data,$dataSend);
    /****************************
		 * đăng ký bên SA game *
		***************************/

    //$sagame = app('App\Http\Controllers\API\SAGameController')->register($UserID);
    //if($sagame!==true){
    //	return $this->response(200, [], $sagame, [], false);
    //}

    /*****************************
		 * kết thúc đăng ký bên SA game *
		*****************************/

    //Register on WM555
    //$client = new Client();
    //$apiKey = config('utils.key');
    //$res = $client->request('POST', 'http://ag.sieuhen.com/api/addmember?apikey='.$apiKey, [
    //  'body' => json_encode([
    //   'username' => $request->username,
    //   'nickname' => $request->nickname,
    //   'password' => $request->password,
    // ])
    // ]);

    //$data = json_decode($res->getBody()->getContents());

    // if($data->error_code != 0 || $data->data == false) return $this->response(200, [], 'Register fail', [], false);


    $user = new User();
    $user->User_ID = $UserID;
    $user->User_Name = $request->username;
    //$user->User_Email = $request->email;
    //$user->User_Phone = $request->phone;
    $user->User_EmailActive = 1;
    $user->User_ConfirmMail = 0;
    //$user->User_Password = $password;
    //$user->User_FullName = $request->fullname;
    //$user->User_PasswordNotHash = $request->password;
    $user->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $user->User_Parent = $parentsID;
    $user->User_Tree = $userTree;
    $user->User_Level = $level;
    $user->User_Token = $token;
    $user->User_WalletAddress = $address;
    $user->User_Status = 1;
    $user->save();
    return $this->response(200, array('user'=>$request->username, 'id'=>$UserID), trans('notification.register_completed'));
    //dd($user);
    //if () {
    // gửi mail thông báo
    //$data = array('User_ID' => $UserID, 'User_Email' => $request->email, 'token' => $token);
    //Job
    //dispatch(new SendMailJobs('Active', $data, 'Active Account!', $UserID));

    //}
    //return $this->response(200, [], 'Register failed! Please contact admin', [], false);
  }

  public function postUpdateEmail(Request $req){
    $user = Auth::user();
    if(!$user){
      return $this->response(200, [], trans('notification.please_login'), [], false);
    }
    $validator = Validator::make($req->all(), [
      'email' => 'required|email',
    ],[
      'email.required' => trans('notification.email_required'),
      'email.email' => trans('notification.email_invalidate'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    if($user->User_Email != null){
      return $this->response(200, [], 'Error Updated Email!', [], false);
    }
    $checkMail = User::where('User_Email', $req->email)->first();
    if($checkMail){
      return $this->response(200, [], trans('notification.email_address_already_exists'), [], false);
    }

    $dataToken = array('user_id' => $user->User_ID, 'time' => time());
    $token = $token = encrypt(json_encode($dataToken));

    $update = DB::table('users')->where('User_ID', $user->User_ID)->update([
      'User_Email'=> $req->email,
      'User_Token'=> $token,
    ]);
    if($update){
      LogUser::addLogUser($user->User_ID,'update email '.$req->email, 'Update success email '.$req->email , $req->ip());

      $data = array('User_ID' => $user->User_ID, 'User_Email' => $req->email, 'token' => $token);
      //Job
      dispatch(new SendMailJobs('AddEmailSuccess', $data, 'Add Email Success!', $user->User_ID));

      return $this->response(200, [], trans('notification.Update_email_success_Please_check_your_email_to_active!', ['email' => $req->email] ), [], true);
    }

    return $this->response(200, [], trans('notification.update_fail'), [], false);
  }

  public function RandonIDUser(){

    $id = rand(100000, 999999);
    $user = User::where('User_ID', $id)->first();
    if (!$user) {
      return $id;
    } else {
      return $this->RandonIDUser();
    }
  }
  public function postUpdatePassword(Request $request){

    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6',
      're_password' => 'required|same:password',
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      're_password.required' => trans('notification.password_required'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $user = Auth::user();
    if(!$user){
      return $this->response(200, [], trans('notification.please_login'), [], false);
    }
    $user = User::where('User_ID', $user->User_ID)->first();

    if (!$user) {
      return $this->response(200, [], trans('notification.user_not_exists'), [], false);
    }
    if($user->User_ConfirmMail != 1){
      return $this->response(200, [], trans('notification.please_active_your_email_first'), [], false);
    }

    $password = Hash::make($request->password);

    $user->User_Password = $password;
    $user->User_PasswordNotHash = $request->password;
    $user->save();
    return $this->response(200, [], trans('notification.update_password_success'), [], true);
    //return $this->response(200, [], 'Update password success!');
  }


  public function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function getLog()
  {
    $user = Auth::user();
    $log = LogUser::where('user', $user->User_ID)->orderBy('id', 'DESC')->select('action', 'comment', 'ip', 'datetime')->limit(20)->get();
    return $this->response(200, $log, '', [], true);
  }

  public function getCountries(){
    $countries = DB::table('countries')->get();
    return $this->response(200, ['countries'=>$countries]);
  }

  public function updateWithdrawAddress(Request $req){
    $user = Auth::user();
    $validator = Validator::make($req->all(), [
      'coin' => 'required|nullable',
      'address' => 'required|nullable',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $currency =  [
      1=>"BTC",
      2=>"ETH",
      5=>"USDT",
    ];
    $arr_update = [
      "User_WalletAddress".$currency[$req->coin] => $req->address
    ];
    $update = DB::table('users')->where('User_ID', $user->User_ID)->update($arr_update);
    if($update){
      LogUser::addLogUser($user->User_ID,'update wallet '.$currency[$req->coin], 'Update success wallet address '.$currency[$req->coin] , $req->ip());

      return $this->response(200, [], 'Update wallet address'.$currency[$req->coin].' Success!', [], true);
    }

    return $this->response(200, [], 'Update Fail', [], false);
  }
  public function postAddAgency(Request $req){
    $validator = Validator::make($req->all(), [
      'email' => 'required|email|unique:agency,email',
      'country_id' => 'required|exists:countries,Countries_ID',
      // 'country_id' => 'required',
      // 'phone_number' => 'required|nullable',
      'name' => 'required|nullable',
      'date' => 'required|nullable',
      'telegram_id' => 'required|nullable',
      'position' => 'required|nullable',
      'work' => 'required|in:0,1,2',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $agency = new Agency;
    $agency->email = $req->email;
    $agency->country_id = $req->country_id;
    // $agency->country_name = $req->country_id;
    $agency->phone_number = $req->phone_number;
    $agency->name = $req->name;
    $agency->birthday = $req->date;
    $agency->telegram_id = $req->telegram_id;
    $agency->position = $req->position;
    $agency->work = $req->work;
    $agency->resume = $req->resume;
    $agency->created_at = date('Y-m-d H:i:s');
    $agency->save();
    return $this->response(200, [], 'Add agency successfully!');
  }
  public function getTransactionDetail(Request $req){
    $data = Money::where('Money_ID', $req->id)->get();
    return $this->response(200, $data, '');
  }
}
