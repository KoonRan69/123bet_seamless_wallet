<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use Illuminate\Http\Request;

class checkUserParentProvide
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
    if($request->key_api){
      $Provide_Key_API = $request->key_api;
      $user_parent = User::where('Provide_Key_API', $Provide_Key_API)->first();
      if(!$user_parent){
        return response()->json([
          'status' => false,
          'code' => 200,
          'data' => [],
          'message' => "Key api not found",
          'errors' => []
        ], 200);
      }
      $ipAddress = $this->getIp();
      
      //dd($ipAddress);
      if($ipAddress != $user_parent->Provide_IP_Address	){
        return response()->json([
          'status' => false,
          'code' => 200,
          'data' => [],
          'message' => "IP Address: '.$ipAddress.' is not whitelist",
          'errors' => []
        ], 200);
      }
      return $next($request);
    }

    return response()->json([
      'status' => false,
      'code' => 200,
      'data' => [],
      'message' => "Key api is not valid",
      'errors' => []
    ], 200);

  }

  public function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
      if (array_key_exists($key, $_SERVER) === true){
        foreach (explode(',', $_SERVER[$key]) as $ip){
          $ip = trim($ip); // just to be safe
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
            return $ip;
          }
        }
      }
    }
    return request()->ip(); // it will return server ip when no client ip found
  }
}
