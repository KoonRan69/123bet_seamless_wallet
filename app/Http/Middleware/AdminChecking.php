<?php

namespace App\Http\Middleware;

use Closure;
class AdminChecking
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
        var_dump(Auth::user());exit;
        if(Auth::user()->User_Level == 1) {
            return $next($request);
        }
        // return redirect('home');
        // if (\Auth::user()->User_Level == 1) {
        //     return $next($request);
        //   }
      
          return response()->json([
            'status' => false,
            'code' => '401',
            'data' => [],
            'message' => 'You have no permission to access',
            'errors' => []
        ]);
        // $user = Auth::user();
        // $useri = $request->user();
        // var_dump($user);exit;
        // // $user = $request->user();
        // if ($user->User_Level != 1) {
        //     return response()->json([
        //         'status' => false,
        //         'code' => '401',
        //         'data' => [],
        //         'message' => 'You have no permission to access',
        //         'errors' => []
        //     ]);
        // }
        // return $next($request);
    }
}
