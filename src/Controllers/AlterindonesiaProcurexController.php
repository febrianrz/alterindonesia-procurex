<?php
namespace Alterindonesia\Procurex\Controllers;

use Alterindonesia\Procurex\Facades\GlobalHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
           'permission_name'    => 'required',
            'state'     => 'required|boolean',
            'url'       => 'nullable'
        ]);

        try {
            DB::beginTransaction();
            if ($request->input('state') === true) {
                $exists = DB::table('role_permission_procurex')
                    ->where('role_code', $request->input('role_code'))
                    ->where('permission_name', $request->input('permission_name'))
                    ->first();
                if (!$exists) {
                    DB::table('role_permission_procurex')->insert([
                        'role_code' => $request->input('role_code'),
                        'permission_name' => $request->input('permission_name')
                    ]);
                }
            } else {
                DB::table('role_permission_procurex')
                    ->where('role_code', $request->input('role_code'))
                    ->where('permission_name', $request->input('permission_name'))
                    ->delete();
            }

            // Jika service ini adalah SSO, maka redirect assign ke service yang bersangkutan
            if (config('procurex.is_sso_service') && $request->has('url') && $request->input('url')) {
                $http = Http::withHeaders([
                    'Authorization' => $request->header('Authorization')
                ])->post($request->input('url'), [
                    'role_code' => $request->input('role_code'),
                    'permission_name' => $request->input('permission_name'),
                    'state' => $request->input('state')
                ]);
                if ($http->status() !== 200) throw new \Exception("Gagal meneruskan ke service {$request->url}");
            }

            DB::commit();
            return GlobalHelper::responseSuccess("Success", []);
        } catch (\Exception $e){
            DB::rollBack();
            return GlobalHelper::responseError($e->getMessage());
        }
    }
}
