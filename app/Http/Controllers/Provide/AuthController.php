<?php

namespace App\Http\Controllers\Provide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login;
use App\Model\User;
use App\Model\GoogleAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
  public function getDocument(){
    return view('Provide.document');
  }
    
  public function getLogin(){
    if(session('user')){
      return redirect()->route('provide.getUser');
    }
    return view('Provide.login');
  }

  public function postLogin(Request $request){
    $loginUser = User::where('User_Email', $request->email)->first();
   
    if(!$loginUser){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Your email is not found!']);
    }
    if($loginUser->User_EmailActive != 1){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Please check your email and active this account!']);
    }
    if($loginUser->User_Status != 1){
      return redirect()->back();
    }
    //chặn không cho đăng nhập
    /*if($loginUser->User_ID == 669839){
      Session::forget('user');
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Error Login']);
    }*/
    if (!Hash::check($request->password, $loginUser->User_Password)) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Password incorrect']);
    }
    /*$auth = GoogleAuth::where('google2fa_User',$loginUser->User_ID)->first();
    if($auth){
      Session::put('auth',$auth);
      $otp = true;
      return redirect()->route('getLogin')->with(['otp'=>$otp]);
    }*/
    if($loginUser->Provide_Key_API == NULL){
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'User is not registed']);
    }
    Session::put('user', $loginUser);
    return redirect()->route('provide.getUser')->with(['flash_level' => 'success', 'flash_message' => 'Login successfully']);

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
    return redirect()->route('provide.getLogin');
  }


}
