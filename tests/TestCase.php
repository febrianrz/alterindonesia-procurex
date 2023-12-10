<?php

namespace Alterindonesia\Procurex\Tests;

use Alterindonesia\Procurex\Providers\AlterindonesiaProcurexProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            AlterindonesiaProcurexProvider::class,
        ];
    }
}
