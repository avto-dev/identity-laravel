<?php

namespace AvtoDev\IDEntity;

use AvtoDev\StaticReferences\StaticReferencesServiceProvider;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

class IDEntitiesServiceProvider extends IlluminateServiceProvider
{
    /**
     * Get config root key name.
     *
     * @return string
     */
    public static function getConfigRootKeyName()
    {
        return \basename(static::getConfigPath(), '.php');
    }

    /**
     * Returns path to the configuration file.
     *
     * @return string
     */
    public static function getConfigPath()
    {
        return __DIR__ . '/config/identity.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $required_providers = [
            ExtendedValidatorServiceProvider::class,
            StaticReferencesServiceProvider::class,
        ];

        foreach ($required_providers as $required_provider) {
            if (! $this->serviceProviderIsRegistered($required_provider)) {
                $this->app->register($required_provider);
            }
        }
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->initializeConfigs();
    }

    /**
     * Make check - service provider is loaded or not?
     *
     * @param string $class_name
     *
     * @return bool
     */
    protected function serviceProviderIsRegistered($class_name)
    {
        if (\method_exists($this->app, 'getLoadedProviders')) {
            $loaded = \array_keys($this->app->getLoadedProviders());

            return \in_array($class_name, $loaded, true);
        }

        return false;
    }

    /**
     * Initialize configs.
     *
     * @return void
     */
    protected function initializeConfigs()
    {
        $this->mergeConfigFrom(static::getConfigPath(), static::getConfigRootKeyName());

        $this->publishes([
            \realpath(static::getConfigPath()) => config_path(\basename(static::getConfigPath())),
        ], 'config');
    }
}
