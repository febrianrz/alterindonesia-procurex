<?php
namespace Alterindonesia\Procurex\Middleware;

use Alterindonesia\Procurex\Facades\Auth;
use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Alterindonesia\Procurex\Models\UserLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;


class ActivityLogMiddleware
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

    private function writeLog(Request $request, $response): void
    {
        $exception = $response?->exception;
        if(!Schema::hasColumn('user_logs','exception')){
            Schema::table('user_logs',function (Blueprint $table){
                $table->longText('exception')->nullable();
            });
        }
        $fullUrl = url()->full();
        $uri = $request->path();
        $routeName = Route::currentRouteName();
        $routeAction = Route::currentRouteAction();
        $payload = $request->all();
        $method = $request->method();
        $response = (array)json_decode($response->getContent());
        $user = $this->getUser($request);
        $logId = UserLog::create([
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
            'exception'     => $exception ?? '',
            'agent'         => $request->userAgent(),
            'ip'            => $request->ip()
        ]);
        if(config('procurex.is_send_error_to_discord',false) && $exception){
            if ($exception instanceof ValidationException) {
                return;
            }

            try {
                $hookUrl = "https://discord.com/api/webhooks/1137646975685234749/bg2jVge6T-3DLJ2_bHMJikEmOr3N6otXY9XApUNHZEecmc8gUCMp6UywwKipEqmNkwM8";
                $serviceName = config('procurex.service_name', 'Procurex');
                $http = Http::withHeaders([
                    'Content-Type' => 'application/json'
                ])->timeout(3)->post($hookUrl, [
                    'content' => "Error Handler {$serviceName} ".(App::isProduction() ? 'Production' : 'Development')." with Log ID: {$logId->id}, Error: {$exception->getMessage()}"
                ]);
            } catch (\Exception $e) {
            }
        }
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
