<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\ServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

#[CoversClass(ServiceProvider::class)]
class ServiceProviderTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testGetConfigRootKeyName(): void
    {
        $this->assertSame('identity', ServiceProvider::getConfigRootKeyName());
    }

    /**
     * @return void
     */
    public function testGetConfigPath(): void
    {
        $this->assertFileExists(ServiceProvider::getConfigPath());

        $this->assertSame(
            \realpath(__DIR__ . '/../config/identity.php'),
            \realpath(ServiceProvider::getConfigPath())
        );
    }

    /**
     * @return void
     */
    public function testConfigsInitialization(): void
    {
        $package_config_src    = \realpath($config_path = ServiceProvider::getConfigPath());
        $package_config_target = $this->app->configPath(\basename($config_path));

        $this->assertSame(
            $package_config_target,
            IlluminateServiceProvider::$publishes[ServiceProvider::class][$package_config_src]
        );

        $this->assertSame(
            $package_config_target,
            IlluminateServiceProvider::$publishGroups['config'][$package_config_src],
            "Publishing group value {$package_config_target} was not found"
        );

        $original_config_content = require $config_path;

        $this->assertIsArray($original_config_content);
        $this->assertArrayHasKey('extended_types_map', $original_config_content);
        $this->assertEmpty($original_config_content['extended_types_map']);
    }
}
