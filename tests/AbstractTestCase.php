<?php

namespace AvtoDev\IDEntity\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\IDEntity\IDEntitiesServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Register needed service-providers manually
        $app->register(ExtendedValidatorServiceProvider::class);
        $app->register(IDEntitiesServiceProvider::class);

        return $app;
    }
}
