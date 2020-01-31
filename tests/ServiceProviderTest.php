<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\ServiceProvider;

/**
 * @group service_provider
 *
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
        $this->assertEquals('identity', ServiceProvider::getConfigRootKeyName());

        $this->assertEquals(
            \realpath(__DIR__ . '/../src/config/identity.php'),
            ServiceProvider::getConfigPath()
        );
    }

    /**
     * Test package configs.
     *
     * @return void
     */
    public function testPackageConfig(): void
    {
        $original_config_content = require __DIR__ . '/../src/config/identity.php';

        $this->assertIsArray($original_config_content);
        $this->assertArrayHasKey('extended_types_map', $original_config_content);
        $this->assertEmpty($original_config_content['extended_types_map']);

        $this->assertEquals($this->app->make('config')->get('identity'), $original_config_content);
    }
}
