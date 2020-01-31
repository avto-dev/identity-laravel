<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @param string[] $service_providers
     *
     * @return Application
     */
    public function createApplication($service_providers = [
        \AvtoDev\IDEntity\ServiceProvider::class,
        \AvtoDev\StaticReferences\ServiceProvider::class,
    ])
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        foreach ((array) $service_providers as $service_provider) {
            $app->register($service_provider);
        }

        return $app;
    }
}
