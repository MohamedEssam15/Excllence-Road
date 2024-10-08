<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class SetApplicationLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Language','ar');
        if($locale == 'ar' || $locale == 'en'){
            App::setLocale($locale);
            return $next($request);
        }
        return apiResponse('language not exist yet',new stdClass(),['language not exist yet'],422);

    }
}
