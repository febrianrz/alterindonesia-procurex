<?php

namespace Alterindonesia\Procurex\Traits;

use Alterindonesia\Procurex\Facades\Auth;

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
}
