<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Model\Stringsession;

class CheckRequestGame
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
		$user = $request->user();
        if ($user) {
			$keyServer = config('security.server');
			//var_dump($request->all()['Server'], $keyServer);exit;
			if(isset($request->all()['Server']) && $request->all()['Server'] == $keyServer){
				return $next($request);
			}else{
				return response()->json([
					'status' => false,
					'code' => '401',
					'data' => [],
					'message' => 'Permission denied!',
					'errors' => []
				]);
			}
        }
		return response()->json([
			'status' => false,
			'code' => '401',
			'data' => [],
			'message' => 'Please Login!',
			'errors' => []
		]);
    }
}
