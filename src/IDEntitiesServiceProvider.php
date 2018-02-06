<?php

namespace AvtoDev\IDEntity;

use Exception;
use AvtoDev\StaticReferencesLaravel\StaticReferences;
use AvtoDev\StaticReferencesLaravel\StaticReferencesServiceProvider;
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
            if (! $this->extendedLaravelValidatorIsRegistered()) {
                $this->registerExtendedLaravelValidator();
            }
        } else {
            throw new Exception(
                sprintf('Required package "%s" is not installed', 'avto-dev/extended-laravel-validator')
            );
        }

        // А так же проверяем пакет статических справочников
        if ($this->staticReferencesIsInstalled()) {
            if (! $this->staticReferencesIsRegistered()) {
                $this->registerStaticReferences();
            }
        } else {
            throw new Exception(
                sprintf('Required package "%s" is not installed', 'avto-dev/static-references-laravel')
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
        return $this->app->bound('extended-laravel-validator.registered');
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

    /**
     * Возвращает true в том случае, если сервис-провайдер пакета 'avto-dev/static-references-laravel' был успешно
     * загружен (а с ним и данные статических справочников).
     *
     * @return bool
     */
    protected function staticReferencesIsRegistered()
    {
        return $this->app->bound(StaticReferences::class) === true;
    }

    /**
     * Производит регистрацию сервис-провайдера пакета 'avto-dev/static-references-laravel'.
     *
     * @return void
     */
    protected function registerStaticReferences()
    {
        $this->app->register(StaticReferencesServiceProvider::class);
    }

    /**
     * Возвращает true в том случае, если пакет 'avto-dev/static-references-laravel' установлен.
     *
     * @return bool
     */
    protected function staticReferencesIsInstalled()
    {
        return class_exists(StaticReferencesServiceProvider::class);
    }
}
