<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! is_null(auth()->user())){
            if(auth()->user()->is_active){
                return $next($request);
            }else{
                return apiResponse(__('auth.activateFirst'),new stdClass(),[__('auth.activateFirst')],401);
            }
        }else{
            return apiResponse(__('auth.loginFirst'),new stdClass(),[__('auth.loginFirst')],401);
        }

    }
}
