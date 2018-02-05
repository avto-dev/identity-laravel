<?php

namespace AvtoDev\IDEntity\Tests;

use Exception;
use Mockery as m;
use AvtoDev\IDEntity\IDEntitiesServiceProvider;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

/**
 * Class IDEntitiesServiceProviderTest.
 *
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

        foreach ([IDEntitiesServiceProvider::class, ExtendedValidatorServiceProvider::class] as $class_name) {
            $this->assertContains($class_name, $loaded_providers);
        }
    }

    /**
     * Тест исключения при попытке регистрации без установленного пакета 'avto-dev/extended-laravel-validator'.
     *
     * @return void
     */
    public function testWithNotInstalledExtendedLaravelValidator()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('~avto\-dev\/extended\-laravel\-validator~');

        $service_provider_mock = m::mock(IDEntitiesServiceProvider::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('extendedLaravelValidatorIsInstalled')
            ->once()
            ->andReturn(false)
            ->getMock();

        $this->app = $this->createApplication([$service_provider_mock]);
    }
}
