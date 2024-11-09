<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class CheckPlatformHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $platform = $request->header('X-Requested-From');
        if ($platform == null) {
            return apiResponse('please add X-Requested-From to your header', new stdClass(), ['please add X-Requested-From to your header'], 422);
        }
        return $next($request);
    }
}
