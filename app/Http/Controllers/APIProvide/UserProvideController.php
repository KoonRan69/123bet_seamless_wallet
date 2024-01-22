<?php

namespace App\Http\Controllers\APIProvide;

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
class UserProvideController extends Controller
{
  public $key;
  public $urlApi;
  public $agid;
  public $lang;
  public function __construct()
  {
    /*$this->middleware('auth:api', ['except' => ['postLogin','postCooperationContact','getLiquidPartner', 'postRegister', 'resentMail', 'postForgetPassword', 'getCountries', 'postAddAgency']]);
    $this->middleware('2fa')->only(['postLogin']);;
    $ag = config('ag');
    $this->key = $ag['key'];
    $this->urlApi = $ag['url'];
    $this->agid = $ag['agid'];
    $this->lang = $ag['lang'];*/
  }



  public function postCreateProvide(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required',
      'ip_address' =>'required',

    ],[
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $value) {
        // return $error;
        return $this->response(200, [], $value, $validator->errors(), false);
      }
    }

    $random = rand(1, 100);
    $now = time() + $random;

    $user = User::where('User_Email', trim($request->email))->first();

    if (!$user) {
      return $this->response(200, ['require_auth' => false], 'User is not valid', [], false);
    }
    if($user->User_Level != 1){
      //return $this->response(200, [], trans('notification.The_system_is_maintained'), [], false);//'require_auth' => false
    }

    if($user->User_Block == 1){
      return $this->response(200, [], 'Please come back later', [], false);
    }
    if($user->User_EmailActive != 1){
      return $this->response(200, [], 'Please active your email!', [], false);
    }

    /*
    $credentials = [
      'User_Email' => $request->email,
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
      $valid = $google2fa->verifyKey($auth->google2fa_Secret, $request->authCode);
      if (!$valid) {
        return $this->response(200, [], trans('notification.Code_wrong'), [], false);
      }
    }

    $user = $request->user();
    // xoa token cu
    DB::table('oauth_access_tokens')->where('user_id', $user->User_ID)->delete();

	*/
    if($user->Provide_Key_API != NULL){
      return $this->response(200, [], 'User already registered, please use orther account!', [], false);
    }
    
    $key_provide = $user->User_ID.$this->randomKeyApi();
    //dd($key_provide);
    if ($user) {
      $user->User_Log = $now;
      $user->Provide_Key_API = $key_provide;
      $user->Provide_IP_Address = $request->ip_address;
      $user->save();
    }

   /* $tokenResult = $user->createToken('WINBOSS');
    $token = $tokenResult->token;

    $loginType = config('utils.action.login');
    LogUser::addLogUser($user->User_ID, $loginType['action_type'], $loginType['message'], $request->ip());

    $token->save();
    */

    return $this->response(200, [
      'key-api' => $user->Provide_Key_API,
    ], 'Success');
    
  }
  
  public function randomKeyApi(){
    $code = $this->generateRandomString(10);
    $user = User::where('Provide_Key_API', $code)->first();
    if(!$user){
      return $code;
    }else{
      return $this->randomKeyApi();
    }
  }
  
  public function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

}
