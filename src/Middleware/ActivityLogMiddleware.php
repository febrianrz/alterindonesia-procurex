<?php
namespace Alterindonesia\Procurex\Middleware;

use Alterindonesia\Procurex\Facades\Auth;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class ActivityLogMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->writeLog($request,$response);
        return $response;
    }

    private function writeLog(Request $request, $response) {
        $fullUrl = url()->full();
        $uri = $request->path();
        $routeName = Route::currentRouteName();
        $routeAction = Route::currentRouteAction();
        $payload = $request->all();
        $method = $request->method();
        $response = (array)json_decode($response->getContent());
        $user = $this->getUser($request);
        UserLog::create([
            'user_id'       => $user->id,
            'username'      => $user->username,
            'email'         => $user->email,
            'name'          => $user->name,
            'company_code'  => $user->company_code,
            'method'        => $method,
            'full_url'      => $fullUrl,
            'uri'           => $uri,
            'route_name'    => $routeName,
            'route_action'  => $routeAction,
            'payload'       => $payload,
            'response'      => $response,
            'agent'         => $request->userAgent(),
            'ip'            => $request->ip()
        ]);
    }

    private function getUser(Request $request): object
    {
        $user = [
            'id'            => Auth::user() ? Auth::user()->id : null,
            'username'      => Auth::user() ? Auth::user()->username : null,
            'email'         => Auth::user() ? Auth::user()->email : null,
            'company_code'  => Auth::user() && Auth::user()->company_code ? Auth::user()->company_code : null,
            'name'          => Auth::user() ? Auth::user()->name : null,
        ];
        return (object)$user;
    }
}
