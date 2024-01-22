<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPassword;
use App\Model\Log;
use App\Model\User;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendMailJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;
use Hash;
use Redirect;

class ForgotPasswordController extends Controller
{
  public function getForgotPassword()
  {
    return view('System.Auth.Forgot-Password');
  }

  public function postForgotPassword(Request $request)
  {

    if (!$request->Email) {
      return redirect()->route('getForgotPass')->with(['flash_level' => 'error', 'flash_message' => 'Missing Email']);
    }
    include(app_path() . '/functions/xxtea.php');

    $result = User::where('User_Email', $request->Email)->first();
    if (!$result) {
      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Email Error!']);
    }
    $pass = $this->generateRandomString(6);

    $token = Crypt::encryptString($result->User_ID . ':' . time() . ':' . $pass);

    $json = Crypt::decryptString($token);
    $json = explode(':', $json);

    $data = array('User_Email' => $request->Email, 'pass' => $pass, 'token' => $token);

    // gửi mail thông báo
    //         dd($data);
    dispatch(new SendMailJobs('Forgot', $data, 'New Password!', $result->User_ID));

    return redirect()->route('getLogin')->with(['flash_level' => 'success', 'flash_message' => 'Please. check your email! We sent a new password to the email address you are register']);
  }

  public function activePass(Request $req)
  {
    include(app_path() . '/functions/xxtea.php');
    $json = Crypt::decryptString($req->token);
    $json = explode(':', $json);
    if (time() - $json[1] > 300) {
      $urlSystem = config('url.system');
      //return redirect()->away($urlSystem . 'login?s=0&m=Token expires!');
      return redirect()->away($urlSystem . '?s=0&m=Token expires!');
      // return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Token expires!']);
    }

    //update pass
    //dd($json[2]);
    $pass = User::where('User_ID', $json[0])->first();
    $pass->User_Password = bcrypt($json[2]);
    $pass->save();

    // Session::put('user', $pass);   
    $urlSystem = config('url.system');
    //return redirect()->away($urlSystem . 'login?s=1&m=Please Login Again!');
    return redirect()->away($urlSystem . '?s=1&m=Please Login Again!');
    // return redirect()->route('Dashboard')->with(['flash_level'=>'success', 'flash_message'=>'Login Success!']);
  }
  public function mailActivePassword(Request $req)
  {
    $user = User::where('User_Token', $req->token)->first();
    //dd($user);
    if ($user) {
      if ($user->User_EmailActive == 0) {
        $user->User_EmailActive = 1;
      }
      //$user->User_PasswordNotHash = $req->pass;
      $user->User_Password = Hash::make($req->pass);
      if($user->User_Evo_Password){
        $user->User_Evo_Password = $req->pass;
      }
      if($user->User_Sbobet_Password){
        $user->User_Sbobet_Password = $req->pass;
      }

      $user->save();
      return redirect::to(config('url.system').'?s=1&m=Active success!');
    }
    return redirect::to(config('url.system').'?s=0&m=Error!');
  }

  public function activeAddUser(Request $request)
  {
    $findUser = User::where([
      ['User_Email', $request->User_Email],
      ['User_EmailActive', 1]
    ])->first();

    if ($findUser) {
      return 'This user has been added successful';
    }
    $user = new User();
    $user->User_ID = $request->User_ID;
    $user->User_Email = $request->User_Email;
    $user->User_EmailActive = 1;
    $user->User_Password = bcrypt($request->User_PasswordNotHash);
    //$user->User_PasswordNotHash = $request->User_PasswordNotHash;
    $user->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $user->User_Parent = $request->User_Parent;
    $user->User_Tree = $request->User_Tree;
    $user->User_Level = 0;
    $user->User_Token = $request->User_Token;
    $user->User_Agency_Level = 0;
    $user->User_Status = 1;
    $user->save();
    //        return redirect::to('https://123betnow.net/signin');
    return redirect::to('https://123betnow.net/');
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
}
