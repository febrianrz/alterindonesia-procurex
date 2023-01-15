<?php

namespace App\Http\Middleware;

use App\Helpers\GlobalHelper;
use App\Libraries\Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthJWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');
        if(
            !$authorization ||
            !Str::contains($authorization,['Bearer ']) ||
            !Auth::check()
        ) return GlobalHelper::responseError("Unauthorize",[],401);
        return $next($request);
    }
}
