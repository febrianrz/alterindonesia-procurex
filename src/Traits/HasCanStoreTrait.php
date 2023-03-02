<?php

namespace Alterindonesia\Procurex\Traits;

use Alterindonesia\Procurex\Facades\Auth;

trait HasCanStoreTrait {
    public static function canStore():bool
    {
        $routeName = request()->route()->getName();
        $routeName = str_replace(['.index','.update','.destroy'],[".store"],$routeName);
        return Auth::user()->can($routeName);
    }
}
