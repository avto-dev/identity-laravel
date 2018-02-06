<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;
use AvtoDev\IDEntity\IDEntitiesServiceProvider;
use AvtoDev\StaticReferencesLaravel\StaticReferencesServiceProvider;
use Exception;
use Mockery as m;

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

        foreach ([
                     IDEntitiesServiceProvider::class,
                     ExtendedValidatorServiceProvider::class,
                     StaticReferencesServiceProvider::class,
                 ] as $class_name) {
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

        $service_provider_mock = m::mock(IDEntitiesServiceProvider::class, [$this->app])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('extendedLaravelValidatorIsInstalled')
            ->once()
            ->andReturn(false)
            ->getMock();

        $this->app = $this->createApplication([$service_provider_mock]);
    }

    /**
     * Тест исключения при попытке регистрации без установленного пакета 'avto-dev/static-references-laravel'.
     *
     * @return void
     */
    public function testWithNotInstalledStaticReferences()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('~avto\-dev\/static\-references\-laravel~');

        $service_provider_mock = m::mock(IDEntitiesServiceProvider::class, [$this->app])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('staticReferencesIsInstalled')
            ->once()
            ->andReturn(false)
            ->getMock();

        $this->app = $this->createApplication([$service_provider_mock]);
    }
}
