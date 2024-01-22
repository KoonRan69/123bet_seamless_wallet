<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;


class CheckCaptchav3
{
  /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
  public function handle($request, Closure $next)
  {
    $token = $request->token;
    if(!$token){
      return $this->response(200, [], 'Please try again!', [], false);
      return 'Please try again!'; 
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = ['secret' => '6Le4QNwZAAAAAO9WCGSI3uTwGPGsMVW1jZoeZHaB', 'response' => $token];
    $options = ['http' => [
      'header' => "Content-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($data)
    ]];
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $response_keys = json_decode($response);
    if ($response_keys->success==true && $response_keys->score <= 0.5) {
      //Do something to denied access
      if(Session('user')){
        $blockUser = User::where('User_ID', Session('user')->User_ID)->update(['User_Block'=>1]);
        Session::forget('user');
        //Log::insertLog(Session('user')->User_ID, "BLock ID Spam", 0, "IP: ".$request->ip()." Spam Google Captcha v3");
        return $this->response(200, [], 'Error!', [], false);
        return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Error!']);
      }
    }
    if($response_keys->success){
      return $next($request);
    }else{
      return $this->response(200, [], 'Please try again!', [], false);
      return redirect()->back()->with(['flash_level'=>'error', 'flash_message'=>'Please try again!']); 
    }

  }
  public function response($code = 200, $data = [], $message = '', $errors = [], $status = true)
  {
    return response()->json([
      'status' => $status,
      'code' => $code,
      'data' => $data,
      'message' => $message,
      'errors' => $errors
    ], $code);
  }
}
