<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\ServiceProvider;

/**
 * Тесты сервис-провайдера пакета.
 *
 * @group service_provider
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

        $this->assertInternalType('array', $original_config_content);
        $this->assertArrayHasKey('extended_types_map', $original_config_content);
        $this->assertEmpty($original_config_content['extended_types_map']);

        $this->assertEquals($this->app->make('config')->get('identity'), $original_config_content);
    }
}
