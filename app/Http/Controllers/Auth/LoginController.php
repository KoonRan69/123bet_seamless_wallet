<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Model\User;
use App\Model\GoogleAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;
use DB;
class LoginController extends Controller
{

  public function getLogin()
  {
    // 	    $noti_image = DB::table('NotificationImage')->where('Status', 0)->where('Location_System', 1)->get();
    // return view('Auth.Login', compact('noti_image'));
    return view('System.Auth.Login');
  }

  public function postLogin(Request $request)
  {
    $loginUser = User::where('User_Email', $request->email)->first();
    if(!$loginUser){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your email is not found!']);
    }
    if($loginUser->User_EmailActive != 1){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please check your email and active this account!']);
    }
    if($loginUser->User_Block != 0){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Block account!']);
    }
    if($loginUser->User_Status != 1){
      return redirect()->back();
    }
    //chặn không cho đăng nhập
    if($loginUser->User_ID == 669839){
      Session::forget('user');
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error Login']);
    }
    if (!Hash::check($request->password, $loginUser->User_Password)) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Password incorrect']);
    }
    $auth = GoogleAuth::where('google2fa_User',$loginUser->User_ID)->first();
    if($auth){
      Session::put('auth',$auth);
      $otp = true;
      return redirect()->route('getLogin')->with(['otp'=>$otp]);
    }

    Session::put('user', $loginUser);
    return redirect()->route('system.admin.getBlogEvent')->with(['flash_level' => 'success', 'flash_message' => 'Login successfully']);

  }

  public function getLogout()
  {

    // dd(session('user'),session('userTemp'));
    if(session('userTemp')){
      $sessionOld = session('userTemp');
      // bỏ session củ
      Session::forget('user');
      Session::forget('userTemp');

      // tạo session mới
      Session::put('user', $sessionOld);

      return redirect()->route('Dashboard')->with(['flash_level'=>'success', 'flash_message'=>'Logout Success']);
    }

    Session::forget('user');
    return redirect()->route('getLogin');
  }

  public function postLoginCheckOTP(Request $request){
    $auth = Session('auth');
    $google2fa = app('pragmarx.google2fa');

    include(app_path() . '/functions/xxtea.php');
    $key = 'X21B9TT2AI';
    $responseSecret = json_decode(xxtea_decrypt(base64_decode($auth->google2fa_Secret), $key), true);
    $valid = $google2fa->verifyKey($responseSecret['secret'], "$request->otp");
    //$valid = $google2fa->verifyKey($auth->google2fa_Secret, "$request->otp");
    if ($valid) {
      $user = User::find($auth->google2fa_User);
      if($responseSecret['user_id'] != $user->User_ID) return $this->response(200, [], 'Error!', [], false);
      Session::put('user', $user);
      return 1;
    }
    return 0;
  }
}
