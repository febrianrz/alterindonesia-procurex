<?php
namespace Alterindonesia\Procurex\Controllers;

use Alterindonesia\Procurex\Facades\GlobalHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AlterindonesiaProcurexController extends \App\Http\Controllers\Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\AnonymousCollection
     */
    public function getRouteList(Request $request): \Illuminate\Http\JsonResponse|\App\Http\Resources\AnonymousCollection
    {
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
        return GlobalHelper::responseSuccess("Success",$routes);
    }


    /**
     * @param Request $request
     * @return \App\Http\Resources\AnonymousCollection|\Illuminate\Http\JsonResponse
     */
    public function assignRoleRoute(Request $request) {
        $request->validate([
           'role_code'  => 'required',
           'permission_name'    => 'required'
        ]);
        return GlobalHelper::responseSuccess("Success",true);
    }
}
