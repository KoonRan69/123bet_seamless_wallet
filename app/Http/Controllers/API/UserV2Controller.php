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
class UserV2Controller extends Controller
{
  public $key;
  public $urlApi;
  public $agid;
  public $lang;
  public $config;

  //evo
  public $api_host;
  //evo



  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['postLogin','postCooperationContact','getLiquidPartner', 'postRegister','postForgetPassword', 'getCountries', 'postAddAgency']]);
    $this->middleware('2fa')->only(['postLogin']);;
    $ag = config('ag');

    $this->config = config('urlSBOBET.sbobet');
    //evo
    $this->api_host = "https://api.luckylivegames.com";
    $this->casinokey = "1gvsw90kwuok5zqs";
    $this->apitoken = "15a59174850db01115f28c0bd1705230";
    $this->currency = "CNY";
    //evo

    $this->key = $ag['key'];
    $this->urlApi = $ag['url'];
    $this->agid = $ag['agid'];
    $this->lang = $ag['lang'];
  }


  public function checkLoginGame(Request $request){

    $user = $request->user();     
    //Kiểm tra balance game còn tiền không, nếu còn thì update trạng thái về 1 để client thực hiện gọi api rút của game về hệ thống
    //Evo
    $checkBalanbceEvo = app('App\Http\Controllers\API\EvolutionController')->evoBalance($user->User_ID);
    if($checkBalanbceEvo > 0) $user->login_evo = 1;
    else $user->login_evo = 0;

    //Sbo
    $checkBalanbceSbo = app('App\Http\Controllers\API\SbobetController')->getBalancePlayer($user->User_ID,$user->User_Name_Sbobet);
    if($checkBalanbceSbo > 0) $user->login_sbobet = 1;
    else $user->login_sbobet = 0;

    $user->save();

    $data = User::where('User_ID',$user->User_ID)->select('login_sbobet','login_evo','login_chicken')->first();
    return $this->response(200, $data);

  }

  public function resentMail(Request $req){
    $user = Auth::user();
    if(!$user->User_Email){
      return $this->response(200, [],"You have not updated your email", [], false);
    }

    if($user->User_EmailActive == 1){
      return $this->response(200, [], 'The account has been activated!', [], false);
    }
    //Tạo token cho mail
    $dataToken = array('user_id' => $req->user_id, 'time' => time());
    $token = encrypt(json_encode($dataToken));
    $user->User_Token = $token;
    $user->save();
    try {
      // gửi mail thông báo
      $data = array('User_ID' => $user->User_ID, 'User_Email' => $user->User_Email, 'token' => $token);
      //Job
      dispatch(new SendMailJobs('Active', $data, 'Active Email!', $user->User_ID));
      return $this->response(200, [], 'Please check your email');
      // Mail::to($request->User_Email)->send(new UserSignUp($user));
    } catch (Exception $e) {
      return $this->response(200, [], 'Wrong email format', [], false);
    }
  }

  public function updateEmail(Request $request){
    $user = Auth::user();
    if($user->User_Email){
      if($user->User_EmailActive == 1){
        return $this->response(200, [], 'The account has been activated!', [], false);
      }
    }

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ],[
      'email.required' => "Email required",
      'email.email' => "Email invalidate",
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $checkEmail = User::where('User_Email', trim($request->email))->first();
    if ($checkEmail) {
      return $this->response(200, [], "Email address already exists", [], false);
    }

    $user->User_Email = trim($request->email);
    $user->save();
    return $this->response(200, [], "Email updated successfully", [], true);
  }

  public function postLogin(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required|min:6',
      'authCode' => 'nullable'
    ],[
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      'username.required' => "Username required",
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $random = rand(1, 100);
    $now = time() + $random;

    $user = User::where('User_Email', trim($request->username))->orwhere('User_ID', trim($request->username))->orWhere('User_Name', trim($request->username))->first();
    if (!$user) {
      return $this->response(200, [], trans('notification.User_is_not_exist'), [], false);
    }
    if($user->User_Level != 1){
      //return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }

    if($user->User_Block == 1){
      return $this->response(200, [], trans('notification.please_come_back_later'), [], false);
    }
    if($user->User_EmailActive != 1){
      //return $this->response(200, [], trans('notification.Please_active_your_email!'), [], false); Bỏ bắt đk này vì giờ cho đăng ký bằng username
    }


    $credentials = [
      'User_ID' => trim($user->User_ID),
      'password' => $request->password,
    ];
    if (!Auth::attempt($credentials)) {
      return $this->response(200, ['require_auth' => false], trans('notification.Login_information_is_not_valid'), [], false);
    }


    $google2fa = app('pragmarx.google2fa');
    $auth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();

    if ($auth) {
      if (!$request->authCode) {
        return $this->response(200, ['require_auth' => true], trans('notification.Please_enter_your_authentication_code'), ['auth' => false]);
      }

      include(app_path() . '/functions/xxtea.php');
      $key = 'X21B9TT2AI';
      $responseSecret = json_decode(xxtea_decrypt(base64_decode($auth->google2fa_Secret), $key), true);
      if($responseSecret['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
      $valid = $google2fa->verifyKey($responseSecret['secret'], "$request->authCode");

      //$valid = $google2fa->verifyKey($auth->google2fa_Secret, $request->authCode);
      if (!$valid) {
        return $this->response(200, [], trans('notification.Code_wrong'), [], false);
      }
    }

    $user = $request->user();
    // xoa token cu
    DB::table('oauth_access_tokens')->where('user_id', $user->User_ID)->delete();

    if ($user) {
      $user->User_Log = $now;
      $user->save();
    }

    $tokenResult = $user->createToken('WINBOSS');
    $token = $tokenResult->token;

    $loginType = config('utils.action.login');
    LogUser::addLogUser($user->User_ID, $loginType['action_type'], $loginType['message'], $request->ip());

    $token->save();

    $UserID = $user->User_ID;
    /*if($user->User_Name_Sbobet == NULL){
      $urlSBO = $this->config['url'].'/web-root/restricted/player/register-player.aspx';
      $body = [
        "Username" => "now_123Betnow_$UserID",
        "Agent"=> $this->config['Agent'],
        "CompanyKey"=> $this->config['CompanyKey'],
        "ServerId"=> $this->config['ServerId'],
      ];
      $topup_str = json_encode($body);
      #Curl init
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $urlSBO);
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
      if (!$err) {
        if($check->error->id == 0 || $check->error->id == 4103){
          $user->User_Name_Sbobet = "now_123Betnow_$UserID";
          $user->User_Sbobet_Password = $request->password;
        }
      }
    }else{
      $withdrawSbo = app('App\Http\Controllers\API\SbobetController')->withdrawV2($request);
      $user->login_sbobet = 0;
    }
*/


    //    if($user->User_Evo != 1){
    //      /****************************
    //		 * đăng ký bên Evo game *
    //		***************************/
    //      $usernameEvo = "now_123Betnow_$UserID";
    //      $body = '{
    //                  "uuid": "' . md5($usernameEvo) .'",
    //                  "player": {
    //                    "id": "' . $usernameEvo. '",
    //                    "update": true,
    //                    "firstName": "' . $usernameEvo. '",
    //                    "lastName": "' . $usernameEvo. '",
    //                    "nickname": "'. $usernameEvo . '",
    //                    "country": "VN",
    //                    "language": "en",
    //                    "currency": "'.$this->currency.'",
    //                    "session": {
    //                      "id": "' . md5($usernameEvo) .'",
    //                      "ip": "89.45.67.50"
    //                    },
    //                    "group": {
    //                      "id": "qe6glrwau24joiu3",
    //                      "action": "assign"
    //                    }
    //                  },
    //                  "config": {
    //                    "brand": {
    //                      "id": "1",
    //                      "skin": "1"
    //                    },
    //
    //                    "channel": {
    //                      "wrapped": false,
    //                      "mobile": true
    //                    },
    //                    "urls": {
    //                       "cashier": "https://v2.123betnow.net/",
    //                      "responsibleGaming": "https://v2.123betnow.netm/live",
    //                      "lobby": "https://v2.123betnow.net/live",
    //                      "sessionTimeout": "https://v2.123betnow.net/"
    //                    },
    //                    "freeGames": true
    //                  }
    //                }';
    //
    //      $ch = curl_init();
    //      curl_setopt($ch, CURLOPT_URL, "$this->api_host/ua/v1/$this->casinokey/$this->apitoken");
    //      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //      curl_setopt($ch, CURLOPT_POST, 1);
    //      curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    //      $result = curl_exec($ch);
    //      $data =  json_decode($result)  ;
    //      $url = [] ;
    //      $url['entry'] = $data->entry ;
    //      $url['entryEmbedded'] =  $data->entryEmbedded;
    //
    //      $user->User_Evo_Password = $request->password;
    //      $user->User_Evo = 1 ;
    //      /*****************************
    //		 * kết thúc đăng ký bên Evo game *
    //		*****************************/
    //    }else{
    //      $withdrawEvo = app('App\Http\Controllers\API\EvolutionController')->withdrawV2($request);
    //      $user->login_evo = 0;
    //    }

    //    if($user->User_ID == 259683){
    //      dd($withdrawEvo, $withdrawSbo);
    //    }

    $user->save();
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

      return $this->response(200, [], 'Logout complete');
    } else {
      return $this->response(200, [], 'Logout fail', [], false);
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
  //dùng để add member
  public function postAddMember(Request $request)
  {
    $userLogin = Auth::user();
    $validator = Validator::make($request->all(), [
      'sponsor' => 'required|min:6|max:6',
      'password' => 'required|min:6',
      'username' => 'required|regex:/^[A-Za-z][A-Za-z0-9]*$/'
    ],[
      'sponsor.required' => trans('notification.Sponsor_required'),
      'username.required' => "Username required",
      'username.regex' => "Username can only enter letters and numbers",
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $checkEmail = User::where('User_Name', trim($request->username))->first();
    if($checkEmail){
      return $this->response(200, [], "Username already exists", [], false);
    }

    $parents = $request->sponsor;
    $InfoPonser = User::where('User_ID', $parents)->orWhere('User_Name', trim($parents))->orWhere('User_Email', trim($parents))->orWhere('User_WalletAddress', $parents)->first();
    if (!$InfoPonser) {
      return $this->response(200, [], trans('notification.Sponsor_doesnt_exists'), [], false);
    }

    $passwordnohash = $request->password;//tạo password ngẫu nhiên
    $password = Hash::make($passwordnohash);
    $UserID = $this->RandonIDUser();

    //Tạo token cho mail
    $dataToken = array('user_id' => $UserID, 'time' => time());
    $userTree = $userLogin->User_Tree . ',' . $UserID;
    $token = encrypt(json_encode($dataToken));

    $level = 0;
    if (strpos($userTree, '123123') !== false) {
      $level = 5;
    }

    $user = new User();
    $user->User_ID = $UserID;
    $user->User_Name = trim($request->username);
    $user->User_Email = trim($request->email);
    //$user->User_Phone = $request->phone;
    $user->User_EmailActive = 0;
    $user->User_Password = $password;
    //$user->User_FullName = $request->fullname;
    //$user->User_PasswordNotHash = $passwordnohash;
    $user->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $user->User_Parent = $parents;
    $user->User_Parent_AddMember = $userLogin->User_ID;
    $user->User_Tree = $userTree;
    $user->User_Level = $level;
    $user->User_Token = $token;
    // $user->User_WalletAddress = $request->Wallet;
    $user->User_Status = 1;


    /****************************
		 * đăng ký bên SBO game *
		***************************/
    $urlSBO = $this->config['url'].'/web-root/restricted/player/register-player.aspx';
    $body = [
      "Username" => "now_123Betnow_$UserID",
      "Agent"=> $this->config['Agent'],
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
    ];
    $topup_str = json_encode($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlSBO);
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
    if (!$err) {
      if($check->error->id == 0){
        $user->User_Name_Sbobet = "now_123Betnow_$UserID";
        $user->User_Sbobet_Password = $passwordnohash;
      }     
    }
    /*****************************
		 * kết thúc đăng ký bên SBO game *
		*****************************/

    /****************************
		 * đăng ký bên Evo game *
		***************************/
    /*$usernameEvo = "now_123Betnow_$UserID"; 
    $body = '{
                  "uuid": "' . md5($usernameEvo) .'",
                  "player": {
                    "id": "' . $usernameEvo. '",
                    "update": true,
                    "firstName": "' . $usernameEvo. '",
                    "lastName": "' . $usernameEvo. '",
                    "nickname": "'. $usernameEvo . '",
                    "country": "VN",
                    "language": "en",
                    "currency": "'.$this->currency.'",
                    "session": {
                      "id": "' . md5($usernameEvo) .'",
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
                       "cashier": "https://v2.123betnow.net/",
                      "responsibleGaming": "https://v2.123betnow.net/live",
                      "lobby": "https://v2.123betnow.net/live",
                      "sessionTimeout": "https://v2.123betnow.net/"
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
    $url['entryEmbedded'] =  $data->entryEmbedded; 

    $user->User_Evo_Password = $passwordnohash; 
    $user->User_Evo = 1 ; */
    /*****************************
		 * kết thúc đăng ký bên Evo game *
		*****************************/

    if ($user->save()) {
      // gửi mail thông báo
      //$data = array('User_ID' => $UserID, 'User_Email' => $request->email,'User_Password'=>$passwordnohash, 'token' => $token);
      //Job
      //dispatch(new SendMailJobs('Active', $data, 'Active Account!', $UserID));
      return $this->response(200, array('user'=>trim($request->username), 'id'=>$UserID), "Register completed!");
    }
    return $this->response(200, [], trans('notification.Register_failed_Please_contact_admin'), [], false);
  }
  public function postRegister(Request $request)
  {
    //return $this->response(200, [], 'The registration system is under maintenance!', [], true);
    $validator = Validator::make($request->all(), [
      //'username' => 'required|min:8|unique:users,User_Name',
      //'nickname' => 'nullable|min:6',
      'password' => 'required|min:6',//|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[A-Z])(?=.*[0-9])/
      'username' => 'required|regex:/^[A-Za-z][A-Za-z0-9]*$/',
      //'sponsor' => 'required|exists:users,User_ID'
    ],[
      'username.required' => "Username required!",
      'username.regex' => "Username can only enter letters and numbers",
      'password.required' => trans('notification.password_required'),
      'password.min' => trans('notification.password_minimum_6_characters'),
      //'sponsor.required' => trans('notification.sponsor_required'),
      //'sponsor.exists' => trans('notification.sponsor_exists'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    $checkUsername = User::where('User_Name', trim($request->username))->first();
    if($checkUsername){
      return $this->response(200, [], "Username already exists", [], false);
    }
    $parents = 801479;
    if ($request->sponsor) {
      $parents = $request->sponsor;
    }

    $InfoPonser = User::where('User_ID', $parents)->orWhere('User_Name', trim($parents))->orWhere('User_Email', trim($parents))->orWhere('User_WalletAddress', $parents)->first();

    if (!$InfoPonser) {
      return $this->response(200, [], trans('notification.Sponsor_doesnt_exists'), [], false);
    }
    $parents = $InfoPonser->User_ID;
    $password = Hash::make($request->password);
    $UserID = $this->RandonIDUser();

    //Tạo token cho mail
    $dataToken = array('user_id' => $UserID, 'time' => time());
    $userTree = $InfoPonser->User_Tree . ',' . $UserID;
    $token = encrypt(json_encode($dataToken));

    $level = 0;
    if (strpos($userTree, '123123') !== false) {
      $level = 5;
    }

    $user = new User();
    $user->User_ID = $UserID;
    $user->User_Name = trim($request->username);
    $user->User_Email = trim($request->email);
    //$user->User_Phone = $request->phone;
    $user->User_EmailActive = 0;
    $user->User_Password = $password;
    //$user->User_FullName = $request->fullname;
    //$user->User_PasswordNotHash = $request->password;
    $user->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $user->User_Parent = $parents;
    $user->User_Tree = $userTree;
    $user->User_Level = $level;
    $user->User_Token = $token;
    // $user->User_WalletAddress = $request->Wallet;
    $user->User_Status = 1;

    /****************************
		 * đăng ký bên SBO game *
		***************************/
    $urlSBO = $this->config['url'].'/web-root/restricted/player/register-player.aspx';
    $body = [
      "Username" => "now_123Betnow_$UserID",
      "Agent"=> $this->config['Agent'],
      "CompanyKey"=> $this->config['CompanyKey'],
      "ServerId"=> $this->config['ServerId'],
    ];
    $topup_str = json_encode($body);
    #Curl init
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlSBO);
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
    if (!$err) {
      if($check->error->id == 0){
        $user->User_Name_Sbobet = "now_123Betnow_$UserID";
        $user->User_Sbobet_Password = $request->password;
      }     
    }
    /*****************************
		 * kết thúc đăng ký bên SBO game *
		*****************************/

    /****************************
		 * ĐÃ ĐÓNG - đăng ký bên Evo game *
		***************************/
    //    $usernameEvo = "now_123Betnow_$UserID";
    //    $body = '{
    //                  "uuid": "' . md5($usernameEvo) .'",
    //                  "player": {
    //                    "id": "' . $usernameEvo. '",
    //                    "update": true,
    //                    "firstName": "' . $usernameEvo. '",
    //                    "lastName": "' . $usernameEvo. '",
    //                    "nickname": "'. $usernameEvo . '",
    //                    "country": "VN",
    //                    "language": "en",
    //                    "currency": "'.$this->currency.'",
    //                    "session": {
    //                      "id": "' . md5($usernameEvo) .'",
    //                      "ip": "89.45.67.50"
    //                    },
    //                    "group": {
    //                      "id": "qe6glrwau24joiu3",
    //                      "action": "assign"
    //                    }
    //                  },
    //                  "config": {
    //                    "brand": {
    //                      "id": "1",
    //                      "skin": "1"
    //                    },
    //
    //                    "channel": {
    //                      "wrapped": false,
    //                      "mobile": true
    //                    },
    //                    "urls": {
    //                      "cashier": "https://v2.123betnow.net/",
    //                      "responsibleGaming": "https://v2.123betnow.net/live",
    //                      "lobby": "https://v2.123betnow.net/live",
    //                      "sessionTimeout": "https://v2.123betnow.netm/"
    //                    },
    //                    "freeGames": true
    //                  }
    //                }';
    //
    //    $ch = curl_init();
    //    curl_setopt($ch, CURLOPT_URL, "$this->api_host/ua/v1/$this->casinokey/$this->apitoken");
    //    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //    curl_setopt($ch, CURLOPT_POST, 1);
    //    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    //    $result = curl_exec($ch);
    //    $data =  json_decode($result)  ;
    //    $url = [] ;
    //    $url['entry'] = $data->entry ;
    //    $url['entryEmbedded'] =  $data->entryEmbedded;
    //
    //    $user->User_Evo_Password = $request->password;
    //    $user->User_Evo = 1 ;
    /*****************************
		 * kết thúc đăng ký bên Evo game *
		*****************************/

    if ($user->save()) {
      // gửi mail thông báo
      //$data = array('User_ID' => $UserID, 'User_Email' => $request->email,'User_Password'=>null, 'token' => $token);
      //Job
      //dispatch(new SendMailJobs('Active', $data, 'Active Account!', $UserID));
      return $this->response(200, array('user'=>trim($request->username), 'id'=>$UserID), "Register completed!");//trans('notification.Register_completed!_Please_check_your_email_to_active')
    }
    return $this->response(200, [], trans('notification.Register_failed_Please_contact_admin'), [], false);
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
  public function postForgetPassword(Request $request){

    $validator = Validator::make($request->all(), [
      'User_Email' => 'required|email',
    ],[
      'User_Email.required' => trans('notification.User_email_required!'),
      'User_Email.email' => trans('notification.Wrong_email_format'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $user = User::where('User_Email', $request->User_Email)->first();

    $forgotPassword = config('utils.action.forgot_password');

    if (!$user) {
      return $this->response(200, [], trans('notification.Email_not_exists'), [], false);
    }

    $passwordRan = $this->generateRandomString(8);

    $token = Crypt::encryptString($user->User_ID . ':' . time() . ':' . $passwordRan);
    $user->User_Token = $token;
    // $user->User_Password = Hash::make($passwordRan);
    $user->save();
    $data = [
      'User_Email' => $request->User_Email,
      'pass' => $passwordRan,
      'token' => $token
    ];

    dispatch(new SendMailJobs('Forgot', $data, 'New Password!', $user->User_ID));
    try {
      //code...
      // Mail::to($request->User_Email)->send(new UserForgotPassword($data));
    } catch (Exception $e) {
      return $e;
    }
    return $this->response(200, [], trans('notification.Please_check_your_email'));
  }

  public function getInfo($adminCheckUser = null)
  {
    $user = Auth::user();
    if($adminCheckUser){
      $user = $adminCheckUser;
    }
    $wallet = $user->User_WalletAddress;
    $check_auth = DB::table('users')->where('User_ID', $user->User_ID)->join('google2fa', 'google2fa.google2fa_User', 'users.User_ID')->first();
    $status_auth = false;
    if ($check_auth) {
      $status_auth = true;
    }

    if($user->time_update_game_balance < date('Y-m-d')){
      ListMission::setBalanceGameDay($user->User_ID);
    }

    $total_egg = count(Eggs::where(['Owner' => $user->User_ID,])->get());
    $total_food = Foods::where(['Owner' => $user->User_ID,])->sum('Amount');
    $total_pool = count(Pools::where(['Owner' => $user->User_ID,])->get());

    $myEggs = DB::table('eggsTemp')->where('user', $user->User_ID)->sum('amount');
    $TotalMember = User::whereRaw('User_Tree LIKE "'.$user->User_Tree.'%"')->where('User_ID', '!=', $user->User_ID)->count('User_ID');
    $info = array(
      'ID' => $user->User_ID,
      'Email' => $user->User_Email,
      'Phone' => $user->User_Phone,
      'RegisteredDatetime' => $user->User_RegisteredDatetime,
      'Parent' => $user->User_Parent,
      'Balance' => User::getBalance($user->User_ID, 3),
      'Wallet' => $wallet,
      'Auth' => $status_auth,
      'TotalEggs' => $myEggs,
      'TotalMember' => $TotalMember,
      'total_egg' => $total_egg,
      'total_food' => $total_food,
      'total_pool' => $total_pool
    );
    $info['LevelName'] = ($user->User_Agency_Level == 0) ? "Member" : "Star " . $user->User_Agency_Level;
    $info['UserLevel'] = $user->User_Level;

    $info['Level'] = $user->User_Agency_Level;

    //kiem tra KYC
    $checkKYC = Profile::where('Profile_User', $user->User_ID)->whereIn('Profile_Status', [0, 1])->first();
    // dd($checkExist);
    $passport = '';
    if ($checkKYC) {
      $reason = '';
      $KYC = $checkKYC->Profile_Status;
      $passport = $checkKYC->Profile_Passport_ID;
      $passport_image = config('url.media') . $checkKYC->Profile_Passport_Image;
      $passport_image_selfie = config('url.media') . $checkKYC->Profile_Passport_Image_Selfie;
    } else {
      $KYC = -1;
      $reason = 'Your Profile KYC Is Unverify!';
      $passport_image = '';
      $passport_image_selfie = '';
    }
    $KYC_infor['status'] = $KYC;
    $KYC_infor['reason'] = $reason;
    $KYC_infor['passport'] = $passport;
    $KYC_infor['passport_image'] = $passport_image;
    $KYC_infor['passport_image_selfie'] = $passport_image_selfie;
    return $this->response(200, [
      'info' => $info,
      'check_kyc' => $KYC_infor
    ]);
  }


  public function postChangePassword(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'User_Password' => 'required|min:6|string',
      'User_New_Password' => 'required|min:6',
      'User_Re_New_Password' => 'required|min:6|same:User_New_Password',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $currentUser = $request->user();

    $passwordChanging = config('utils.action.change_password');
    LogUser::addLogUser($currentUser->User_ID, $passwordChanging['action_type'], $passwordChanging['message'], $request->ip());

    if (Hash::check($request->User_Password, $currentUser->User_Password)) {
      $user = User::find($currentUser->User_ID);
      $user->User_Password = bcrypt($request->User_New_Password);


      if($user->User_Evo_Password){
        $user->User_Evo_Password = $request->User_New_Password;
      }
      if($user->User_Sbobet_Password){
        $user->User_Sbobet_Password = $request->User_New_Password;
      }

      $user->save();
      return $this->response(200, [], 'Change password successful');
    }
    return $this->response(200, [], 'Please enter correct current password', [], false);
  }

  public function getAuth()
  {
    $user = Auth::user();
    $google2fa = app('pragmarx.google2fa');

    //kiểm tra member có secret chưa?
    $auth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();

    $Enable = false;
    if ($auth) {
      $Enable = true;
      $secret = $auth->google2fa_Secret;
    } else {
      $secret = $google2fa->generateSecretKey();
    }
    $google2fa->setAllowInsecureCallToGoogleApis(true);

    $inlineUrl = $google2fa->getQRCodeUrl(
      "123BetNow",
      $user->User_Email,
      $secret
    );
    $qr = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . $inlineUrl . "&choe=UTF-8";

    return $this->response(200, [
      'enable' => $Enable,
      'secret' => $secret,
      'qr' => $qr,
    ]);
  }

  public function postConfirmAuth(Request $req)
  {
    $validator = Validator::make($req->all(), [
      'authCode' => 'required',
      'secret' => 'nullable'
    ]);

    if (!$validator) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }
    include(app_path() . '/functions/xxtea.php');
    $key = 'X21B9TT2AI';
    $user = Auth::user();
    $google2fa = app('pragmarx.google2fa');
    $auth = GoogleAuth::where('google2fa_User', $user->User_ID)->first();
    $authCode = $req->authCode . "";

    if (!$auth) {
      if (!$req->secret) {
        return $this->response(200, [], 'Miss secret', [], false);
      }
      $valid = $google2fa->verifyKey($req->secret, $authCode);
    } else {
      $responseToken = json_decode(xxtea_decrypt(base64_decode($auth->google2fa_Secret), $key), true);
      if($responseToken['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
      $valid = $google2fa->verifyKey($responseToken['secret'], $authCode); //$auth->google2fa_Secret
    }

    if ($valid) {
      if ($auth) {
        // xoá
        GoogleAuth::where('google2fa_User', $user->User_ID)->delete();
        $disableAuth = config('utils.action.disable_auth');
        LogUser::addLogUser($user->User_ID, $disableAuth['action_type'], $disableAuth['message'], $req->ip());
        return $this->response(200, [], trans('notification.Disable_Authenticator'), [], true);
      } else {
        $dataToken = array('secret'=>$req->secret,'user_id' => $user->User_ID , 'time' => time()); 
        $tokenSecret = base64_encode(xxtea_encrypt(json_encode($dataToken), $key));
        $r = new GoogleAuth();
        $r->google2fa_User = $user->User_ID;
        $r->google2fa_Secret = $tokenSecret; //$req->secret
        $r->save();
        $enableAuth = config('utils.action.enable_auth');
        LogUser::addLogUser($user->User_ID, $enableAuth['action_type'], $enableAuth['message'], $req->ip());
        return $this->response(200, [], trans('notification.Enable_Authenticator'), [], true);
      }
    }

    return $this->response(200, [], trans('notification.Code_wrong'), [], false);
  }

  public function getCoin()
  {
    $user = User::find(Auth::user()->User_ID);

    $coin = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();

    $coinArr = config('coin');
    $checkBalance = User::checkBlockBalance($user->User_ID);
    // $getLastedGas = DB::table('gas')->orderByDesc('id')->first();
    // if(!$getLastedGas || (time()- $getLastedGas->time >= $getLastedGas->duration)){
    // 	$json = json_decode(file_get_contents('https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=GMGAYV28HNBZSAHUQQD3PQDXMFGZU7BMBP'));
    // 	$pricegas = 150;
    // 	if($json->message == 'OK'){
    // 		$pricegas = $json->result->FastGasPrice;
    // 	}

    // 	$timeChange = 1800;
    //     $data = [
    // 	    'amount' => $pricegas,
    // 	    'time' => time(),
    // 	    'duration' => $timeChange,
    //     ];
    //     DB::table('gas')->insert($data);
    // }else{
    // 	$pricegas = $getLastedGas->amount;
    // }
    // $pricegas = $pricegas/1000000000;
    $feeGas = Money::feeGas();
    $EUSD = [
      'Name' => 'Eggs Book USD (EUSD)',
      'Symbol' => 'EUSD',
      'showDashboard' => true,
      'address' => '',
      'qr' => '',
      'id' => 3,
      'balance' => User::getBalance($user->User_ID, 3),
      'Price' => 1,
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/EUSD.png',

    ];

    $EBP = [
      'Name' => 'Eggs Book POP (EBP)',
      'Symbol' => 'EBP',
      'showDashboard' => true,
      'address' => '',
      'qr' => '',
      'id' => 8,
      'balance' => User::getBalance($user->User_ID, 8),
      'Price' => $coin['EBP'],
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/EBP_1.png',
    ];

    $USDT = [
      'Name' => 'Tether (USDT ERC-20)',
      'Symbol' => 'USDT',
      'showDashboard' => true,
      'address' => '',
      'qr' => '',
      'id' => 5,
      'balance' => 0,
      'Price' => 1,
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/USDT.png',
    ];

    $BTC = [
      'Name' => 'Bitcoin (BTC)',
      'Symbol' => 'BTC',
      'showDashboard' => false,
      'address' => '',
      'qr' => '',
      'id' => 1,
      'balance' => 0,
      'Price' => $coin['BTC'],
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/BTC_1.png',
    ];

    $ETH = [
      'Name' => 'Ethereum (ETH)',
      'Symbol' => 'ETH',
      'showDashboard' => true,
      'address' => '',
      'qr' => '',
      'id' => 2,
      'balance' => 0,
      'Price' => $coin['ETH'],
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/ETH_1.png',
    ];

    $GOLD = [
      'Name' => 'GOLD',
      'Symbol' => 'GOLD',
      'showDashboard' => true,
      'address' => '',
      'qr' => '',
      'id' => 9,
      'balance' => User::getBalance($user->User_ID, 9),
      'Price' => 1,
      'Gas' => $feeGas,
      'PecentPlus' => 0,
      'image' => config('url.media').'coin/Gold.png',
    ];

    $coinArray = array(
      'EUSD' => array_merge($EUSD, $coinArr['EUSD']),
      'EBP' => array_merge($EBP, $coinArr['EBP']),
      'USDT' => array_merge($USDT, $coinArr['USDT']),
      'BTC' => array_merge($BTC, $coinArr['BTC']),
      'ETH' => array_merge($ETH, $coinArr['ETH']),
      'GOLD' => array_merge($GOLD, $coinArr['GOLD']),
    );

    return $this->response(200, $coinArray);
  }

  public function getUserDetails(Request $req){

    $user = User::where('User_ID', $req->user)->first();
    $wallet = 1;
    if ($user->User_WalletAddress == null) {
      $wallet = 0;
    }
    $check_auth = DB::table('users')->where('User_ID', $user->User_ID)->join('google2fa', 'google2fa.google2fa_User', 'users.User_ID')->first();
    $status_auth = false;
    if ($check_auth) {
      $status_auth = true;
    }
    $info = array(
      'ID' => $user->User_ID,
      'Email' => $user->User_Email,
      'Phone' => $user->User_Phone,
      'RegisteredDatetime' => $user->User_RegisteredDatetime,
      'Parent' => $user->User_Parent,
      'Balance' => User::getBalance($user->User_ID, 3),
      'Wallet' => $wallet,
      'Auth' => $status_auth,
      'PrivateKey' => $user->User_PrivateKey,
      'WalletAddressSystem' => $user->User_WalletAddress,
      'WalletAddressAvailable' => $user->User_WalletAddressAvailable,
    );
    $info['LevelName'] = ($user->User_Agency_Level == 0) ? "Member" : "Star " . $user->User_Agency_Level;
    $info['UserLevel'] = $user->User_Level;
    // return $info;
    // $info['LevelImage'] = 'http://dafco.org/test/public/dafco/assets/images/level/LEVEL_'.$user->User_Agency_Level.'.png';
    $getInvestFirst = Investment::where('investment_User', $user->User_ID)->where('investment_Status', 1)->orderBy('investment_ID')->first();
    $info['Level'] = !$getInvestFirst ? -1 : $user->User_Agency_Level;
    $sales = 0;
    if ($getInvestFirst) {
      $sales = Investment::selectRaw('Sum(`investment_Amount`*`investment_Rate`) as SumInvest')
        ->whereRaw('investment_User IN (SELECT User_ID FROM users WHERE User_Tree LIKE "' . $user->User_Tree . '%")')
        // ->where('investment_Time', '>=', $getInvestFirst->investment_Time)
        ->where('investment_User', '<>', $user->User_ID)
        ->where('investment_Status', 1)
        ->first()->SumInvest;
    }
    //kiem tra KYC
    $checkKYC = Profile::where('Profile_User', $user->User_ID)->whereIn('Profile_Status', [0, 1])->first();
    // dd($checkExist);
    $passport = '';
    if ($checkKYC) {
      $reason = '';
      $KYC = $checkKYC->Profile_Status;
      $passport = $checkKYC->Profile_Passport_ID;
      $passport_image = config('url.media') . $checkKYC->Profile_Passport_Image;
      $passport_image_selfie = config('url.media') . $checkKYC->Profile_Passport_Image_Selfie;
    } else {
      $KYC = -1;
      $reason = 'Your Profile KYC Is Unverify!';
      $passport_image = '';
      $passport_image_selfie = '';
    }
    $KYC_infor['status'] = $KYC;
    $KYC_infor['reason'] = $reason;
    $KYC_infor['passport'] = $passport;
    $KYC_infor['passport_image'] = $passport_image;
    $KYC_infor['passport_image_selfie'] = $passport_image_selfie;
    return $this->response(200, [
      'info' => $info,
      'total_sale' => number_format($sales, 2),
      'check_kyc' => $KYC_infor
    ]);

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
    ],[
      'coin.required' => trans('notification.coin_required') ,
      'address.required' => trans('notification.address_requaired') ,
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

    return $this->response(200, [], trans('notification.Update_Fail'), [], false);
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
    ],[
      'email.required' => trans('notification.email_invalidate'),
      'name.required' => trans('notification.name_required'),
      'telegram_id.required' => trans('notification.telegram_id_required'),
      'position.required' => trans('notification.position_required'),
      'work.required' => trans('notification.work_required '),
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
    return $this->response(200, [], trans('notification.Add_agency_successfully!'));
  }
  public function getTransactionDetail(Request $req){
    $data = Money::where('Money_ID', $req->id)->get();
    return $this->response(200, $data, '');
  }

  public function postCooperationContact(Request $req){
    $validator = Validator::make($req->all(), [
      'email' => 'required|email',
      'name' => 'required',
      'project_name' => 'required',
      'project_website' => 'required',
      'with_link' => 'required',
      'contact' => 'required',
      'amount' => 'required',
    ],[
      'email.required' => trans('notification.cooperation_email_required'),
      'name.required' => trans('notification.cooperation_name_required'),
      'project_name.required' => trans('notification.cooperation_project_name_required'),
      'project_website.required' => trans('notification.cooperation_project_website_required'),
      'with_link.required' => trans('notification.cooperation_with_link_required'),
      'contact.required' => trans('notification.cooperation_contract_required'),
      'amount.required' => trans('notification.cooperation_amount_required'),
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $data = [
      'email' => $req->email,
      'name' => $req->name,
      'project_name' => $req->project_name,
      'project_website' => $req->project_website,
      'with_link' => $req->with_link,
      'contact' => $req->contact,
      'amount' => $req->amount,
      'other_information' => $req->order_info,
      'status' => 1,
    ];

    $update = DB::table('cooperations')->insert($data);
    return $this->response(200, [], trans('notification.cooperation_send_success'));
  }
  public function getLiquidPartner(Request $req){
    //    $rate['DP-NFT'] = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('DP-NFT');
    //    $rate['BNB'] = json_decode(file_get_contents('https://api.binance.com/api/v1/ticker/price?symbol=BNBUSDT'))->price;
    //    $rate['USDT'] = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy('USDT');
    $rate = app('App\Http\Controllers\API\CoinbaseController')->coinRateBuy();
    $list = DB::table('liquid_partners')->where('status', 1)->select('id', 'name', 'icon', 'contract', 'url', 'name_token', 'status', 'color_code')->get();
    $user = Auth::user();
    //    dd($user);
    if($req->test){
      $list = DB::table('liquid_partners')->whereIn('status', [0,1])->select('id', 'name', 'icon', 'contract', 'url', 'name_token', 'status', 'color_code')->get();
      //      dd($list);
    }
    foreach($list as $l){
      $l->rate = $rate[$l->name_token];
    }
    //dd($list);

    return $this->response(200, $list, []);

  }

}
