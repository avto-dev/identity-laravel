<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\ServiceProvider;

/**
 * @covers \AvtoDev\IDEntity\ServiceProvider
 */
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * Test service provider public methods.
     *
     * @return void
     */
    public function testServiceProviderMethods(): void
    {
        $this->assertSame('identity', ServiceProvider::getConfigRootKeyName());

        $this->assertSame(
            \realpath(__DIR__ . '/../config/identity.php'),
            \realpath(ServiceProvider::getConfigPath())
        );
    }

    /**
     * Test package configs.
     *
     * @return void
     */
    public function testPackageConfig(): void
    {
        $original_config_content = require __DIR__ . '/../config/identity.php';

        $this->assertIsArray($original_config_content);
        $this->assertArrayHasKey('extended_types_map', $original_config_content);
        $this->assertEmpty($original_config_content['extended_types_map']);

        $this->assertSame($this->app->make('config')->get('identity'), $original_config_content);
    }
}
