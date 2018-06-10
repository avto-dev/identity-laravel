<?php

namespace AvtoDev\IDEntity;

use AvtoDev\StaticReferences\StaticReferencesServiceProvider;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use AvtoDev\ExtendedLaravelValidator\ExtendedValidatorServiceProvider;

class IDEntitiesServiceProvider extends IlluminateServiceProvider
{
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
}
