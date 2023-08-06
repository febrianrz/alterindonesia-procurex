<?php
namespace Alterindonesia\Procurex\Facades;

use Alterindonesia\Procurex\Resources\AnonymousCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use function response;

class GlobalHelper
{
    public static function responseSuccess($message, $data=null, $code=200, $resource=null): AnonymousCollection|JsonResponse
    {
        if ($resource && !($data instanceof Model)) {
            $canStore = method_exists($resource,'canStore');
            if($canStore){
                $canStore = $resource::canStore();
            }
            return new AnonymousCollection(
                $data,
                $resource,
                $canStore
            );
        } elseif (is_array($data) || is_object($data)) {
            return response()->json([
                'meta'  => [
                    'message'   => $message,
                    'code'      => $code,
                ],
                'data'      => $data
            ], $code);
        } else {
            return response()->json($data);
        }
    }

    public static function responseError($message, $data=[], $code=400): JsonResponse
    {
        if ($code === 401) {
            return response()->json([
                'message'   => 'Unauthenticated'
            ], $code);
        }
        $meta = [
            'message'   => $message instanceof \Exception ? $message->getMessage() : $message,
            'code'      => $code,
            'number'    => $message instanceof \Exception ? $message->getLine() : "",
            'action'    => $message instanceof \Exception ? $message->getFile() : ""
        ];

        return response()->json([
            'meta'   => $meta,
            'data'  => $data
        ], $code);
    }

    public static function clearUserLogs($lastDays=0): void
    {
        if($lastDays === 0){
            $lastDays = config('procurex.clear_log_days',30);
        }
        if(\Schema::hasTable('user_logs')){
            DB::table('user_logs')
                ->where('created_at', '<', now()->subDays($lastDays))
                ->delete();
        }
    }

}
