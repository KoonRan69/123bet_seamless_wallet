<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Model\Stringsession;

class CheckLogin
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
/*
        if (session('user')) {
	        if(!session('userTemp')){
		        $timeMaintenance = strtotime('2020-07-03');
		        if((session('user')->User_Level != 1 && session('user')->User_Level != 2 && session('user')->User_Level != 3 && session('user')->User_Level != 10) && time() >= $timeMaintenance){
			        Session::forget('user');
			        return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Updating System!']);
		        }
	        }
            return $next($request);
        }
        
*/
    

		if (session('user')) {
	        // if((session('user')->User_Level != 1) ){
	        //     Session::forget('user');
	        //     return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Updating System!']);
	        // }
	        if(session('user')->User_Block == 1){
		        return redirect()->route('getLogout');
	        }
	       
	        // $check = Stringsession::where('user', Session('user')->User_ID)->where('sessionID', session()->getId())->first();

	        // if(!$check){
		    //     return redirect()->route('getLogout');
	        // }
            return $next($request);
        }

        return redirect()->route('getLogin',['redirect'=>encrypt($request->fullUrl())])->with(['flash_level'=>'error', 'flash_message'=>'Please Login!']);

    }
}
