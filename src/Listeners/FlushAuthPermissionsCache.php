<?php

namespace Alterindonesia\Procurex\Listeners;

use Alterindonesia\Procurex\Facades\Auth;

class FlushAuthPermissionsCache
{
    public function handle($event): void
    {
        Auth::clearPermissionCaches();
    }
}