<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Model\Stringsession;

class CheckBlockUser
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
    

		if (Auth::user()) {
			$user = User::find(Auth::user()->User_ID);
	        if($user->User_Block == 1){
				$logoutType = config('utils.action.logout');
				LogUser::addLogUser($user->User_ID, $logoutType['action_type'], $logoutType['message'], $request->ip());
				DB::table('oauth_refresh_tokens')
					->where('access_token_id', $accessToken->id)
					->update([
						'revoked' => true
					]);
				$accessToken->revoke();
				return $this->response(200, [], 'Error');
	        }
            return $next($request);
        }
		return $this->response(200, [], 'Error');

    }
}
