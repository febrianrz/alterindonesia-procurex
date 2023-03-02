<?php

namespace Alterindonesia\Procurex\Traits;

use Alterindonesia\Procurex\Facades\Auth;
use Illuminate\Http\Request;

trait HasActionTrait {
    public static function canStore():bool
    {
        $routeName = request()->route()->getName();
        $routeName = str_replace(['.index','.update','.destroy'],[".store"],$routeName);
        return Auth::user()->can($routeName);
    }

    public static function canUpdate():bool
    {
        $routeName = request()->route()->getName();
        $routeName = str_replace(['.index','.update','.destroy'],[".update"],$routeName);
        return Auth::user()->can($routeName);
    }

    public static function canDestroy():bool
    {
        $routeName = request()->route()->getName();
        $routeName = str_replace(['.index','.update','.destroy'],[".destroy"],$routeName);
        return Auth::user()->can($routeName);
    }

    public function action(Request $request){
        return $this->whenHas(
            'id',
            function () use ($request) {
                // set action
                $action = [
                    "edit"  => self::canUpdate(),
                    "delete"=> self::canDestroy(),
                    "restore"  => self::canDestroy()
                ];

                // check if trashed resource
                if ($request->has("filter")
                    && array_key_exists("trashed", $request->input('filter'))
                ) {
                    unset($action["delete"]);
                } else {
                    unset($action["restore"]);
                }

                return $action;
            }
        );
    }
}
