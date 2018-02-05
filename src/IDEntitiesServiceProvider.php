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
     * @throws Exception
     *
     * @return void
     */
    public function boot()
    {
        // Проверяем - установлен ли пакет расширенного валидатора для Laravel (данный пакет от него зависит)
        if ($this->extendedLaravelValidatorIsInstalled()) {
            // Если он установлен, но не был зарегистрирован
            if (! $this->extendedLaravelValidatorIsRegistered()) {
                // То производим его регистрацию
                $this->registerExtendedLaravelValidator();
            }
        } else {
            throw new Exception(sprintf('Required package "%s" is not installed', 'avto-dev/extended-laravel-validator'));
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
            return $this->app->make('extended-laravel-validator.registered') === true;
        } catch (Exception $e) {
            // Do nothing
        }

        return false;
    }

    /**
     * Производит регистрацию сервис-провайдера пакета 'avto-dev/extended-laravel-validator'.
     *
     * @return void
     */
    protected function registerExtendedLaravelValidator()
    {
        $this->app->register(ExtendedValidatorServiceProvider::class);
    }

    /**
     * Возвращает true в том случае, если пакет 'avto-dev/extended-laravel-validator' установлен.
     *
     * @return bool
     */
    protected function extendedLaravelValidatorIsInstalled()
    {
        return class_exists(ExtendedValidatorServiceProvider::class);
    }
}
