<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use AvtoDev\IDEntity\Tests\Mocks\TypedIDEntityMock;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class AbstractTestCase extends BaseTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        TypedIDEntityMock::reset();

        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $app->register(\AvtoDev\StaticReferences\ServiceProvider::class);
        $app->register(\AvtoDev\IDEntity\ServiceProvider::class);

        return $app;
    }
}
