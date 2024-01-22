<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

class LocaleAPI
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
		//Check header request and set language defaut
        if($request->hasHeader('accept-language')) {
            $lang = $request->Header('accept-language') ;
        }
        else {
            $lang = "en";
        }

        //Set laravel localization
        app()->setLocale($lang);

        //Continue request
        return $next($request);

	}
}
