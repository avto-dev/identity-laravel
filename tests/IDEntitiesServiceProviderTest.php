<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntitiesServiceProvider;
use AvtoDev\StaticReferences\StaticReferencesServiceProvider;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

/**
 * Тесты сервис-провайдера пакета.
 *
 * @group service_provider
 */
class IDEntitiesServiceProviderTest extends AbstractTestCase
{
    /**
     * Тест того, что сервис провайдер был зарегистрирован. Дополнительно убеждаемся в загрузке зависимых
     * сервис-провайдеров.
     *
     * @return void
     */
    public function testServiceProviderRegistered()
    {
        $loaded_providers = $this->app->getLoadedProviders();

        $needles = [
            IDEntitiesServiceProvider::class,
            ExtendedValidatorServiceProvider::class,
            StaticReferencesServiceProvider::class,
        ];

        foreach ($needles as $class_name) {
            $this->assertContains($class_name, \array_keys($loaded_providers));
        }
    }

    /**
     * Test service provider public methods.
     *
     * @return void
     */
    public function testServiceProviderMethods()
    {
        $this->assertEquals('identity', IDEntitiesServiceProvider::getConfigRootKeyName());

        $this->assertEquals(
            \realpath(__DIR__ . '/../src/config/identity.php'),
            IDEntitiesServiceProvider::getConfigPath()
        );
    }

    /**
     * Test package configs.
     *
     * @return void
     */
    public function testPackageConfig()
    {
        $original_config_content = require __DIR__ . '/../src/config/identity.php';

        $this->assertInternalType('array', $original_config_content);
        $this->assertArrayHasKey('extended_types_map', $original_config_content);
        $this->assertEmpty($original_config_content['extended_types_map']);

        $this->assertEquals($this->app->make('config')->get('identity'), $original_config_content);
    }
}
