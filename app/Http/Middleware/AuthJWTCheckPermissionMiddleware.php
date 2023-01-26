<?php

namespace App\Http\Middleware;

use App\Helpers\GlobalHelper;
use App\Libraries\Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthJWTCheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        /** Check permission based on action name */
        $routeName = Route::currentRouteName();
        if(!Auth::user()->hasPermission($routeName))
            return GlobalHelper::responseError("Forbidden",[],403);

        return $next($request);
    }
}
