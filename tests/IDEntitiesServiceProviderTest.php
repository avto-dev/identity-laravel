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
}
