<?php
namespace Alterindonesia\Procurex\Facades;

use App\Http\Resources\AnonymousCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use function response;

class GlobalHelper
{
    public static function responseSuccess($message, $data=null, $code=200, $resource=null)
    {
        if ($resource) {
            return new AnonymousCollection(
                $data,
                $resource
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
        if ($code == 401) {
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

    public static function generateRolePermissions(): array {
        $request = request();
        $roleCode = $request->input('role_code') ?? '';
        $routeCollection = Route::getRoutes();
        $routes = [];
        foreach ($routeCollection as $value) {
            if (str_starts_with($value->getName(), 'api.')){
                $routeName = $value->getName();
                $rolePermissionExists = DB::table('role_permission_procurex')
                    ->where('role_code',$roleCode)
                    ->where('permission_name',$routeName)
                    ->first();
                $routes[] = [
                    'route' => $routeName,
                    'role'  => $roleCode,
                    'status'=> boolval($rolePermissionExists)
                ];
            }
        }
        return $routes;
    }
}
