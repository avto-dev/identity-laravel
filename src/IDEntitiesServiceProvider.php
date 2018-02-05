<?php

namespace AvtoDev\IDEntity;

use Exception;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

/**
 * Class IDEntitiesServiceProvider.
 */
class IDEntitiesServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap any application services.
     *
     * @return void
     *
     * @throws Exception
     */
    public function boot()
    {
        // Убеждаемся что сервис-провайдер пакета "avto-dev/extended-laravel-validator"
        if (! $this->extendedLaravelValidatorIsRegistered()) {
            throw new Exception(
                sprintf(
                    'Service-provider for required package "%s" was not loaded. Please, fix it',
                    'avto-dev/extended-laravel-validator'
                )
            );
        }
    }

    /**
     * Возвращает true в том случае, если сервис-провайдер пакета 'avto-dev/extended-laravel-validator' был успешно
     * загружен (а с ним расширенные правила Laravel-валидатора).
     *
     * @return bool
     */
    protected function extendedLaravelValidatorIsRegistered()
    {
        try {
            return $this->app->make(ExtendedValidatorServiceProvider::SERVICE_PROVIDER_REGISTERED_ABSTRACT) === true;
        } catch (Exception $e) {
            // Do nothing
        }

        return false;
    }
}
