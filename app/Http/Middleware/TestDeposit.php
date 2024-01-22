<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Model\User;
use App\Jobs\DepositJobs;


class TestDeposit 
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
      	$user_auth = Auth::user();
	    $user = User::find($user_auth->User_ID);
        if(!$user){
            return $this->response(200, [], "User does not exist", [], false);
        }
      	//if($user->User_Level == 1){
          dispatch(new DepositJobs($user));
          //return $this->response(200, [], "Permission!", [], false);
        //}
      	return $next($request);
      	//dd('Check deposit success!');
    }
}
