<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity;

use Illuminate\Container\Container;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class IDEntity implements IDEntityInterface
{
    /**
     * Disable outside constructor calling.
     */
    protected function __construct()
    {
        //
    }

    /**
     * @return Container
     */
    protected static function getContainer(): Container
    {
        return Container::getInstance();
    }

    /**
     * Get supported types.
     *
     * @return string[]
     */
    public static function getSupportedTypes(): array
    {
        return \array_keys(static::getTypesMap());
    }

    /**
     * Passed type is supported?
     *
     * @param string|mixed $type
     *
     * @return bool
     */
    public static function typeIsSupported($type): bool
    {
        return \is_string($type) && \in_array($type, static::getSupportedTypes(), true);
    }

    /**
     * {@inheritdoc}
     */
    public static function make(string $value, ?string $type = self::ID_TYPE_AUTO)
    {
        $class_name = static::getEntityClassByType($type);

        if (\is_string($class_name) && \class_exists($class_name)) {
            return new $class_name($value, true);
        }

        if ($type === self::ID_TYPE_AUTO) {
            foreach (static::getTypesMap() as $class_name) {
                /** @var TypedIDEntityInterface $instance */
                $instance = new $class_name($value, true);

                if ($instance->canBeAutoDetected() && $instance->isValid()) {
                    return $instance;
                }
            }
        }

        return new IDEntityUnknown($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function is(string $value, $type): bool
    {
        foreach ((array) $type as $type_value) {
            if (self::make($value, $type_value)->isValid()) {
                return true;
            }
        }

        return false;
    }

    /**
     * This method returns an array, where key is supported IDEntity type, and value is a class for this type.
     *
     * Note: Order is important for automatic detection.
     *
     * @return array<string, class-string>
     */
    protected static function getTypesMap(): array
    {
        return \array_merge([
            self::ID_TYPE_VIN                   => Types\IDEntityVin::class,
            self::ID_TYPE_GRZ                   => Types\IDEntityGrz::class,
            self::ID_TYPE_STS                   => Types\IDEntitySts::class,
            self::ID_TYPE_PTS                   => Types\IDEntityPts::class,
            self::ID_TYPE_BODY                  => Types\IDEntityBody::class,
            self::ID_TYPE_CHASSIS               => Types\IDEntityChassis::class,
            self::ID_TYPE_DRIVER_LICENSE_NUMBER => Types\IDEntityDriverLicenseNumber::class,
            self::ID_TYPE_CADASTRAL_NUMBER      => Types\IDEntityCadastralNumber::class,
        ], static::getExtendedTypesMap());
    }

    /**
     * Get an extended types map, declared in configuration file.
     *
     * @return array<string, class-string>
     */
    protected static function getExtendedTypesMap(): array
    {
        /** @var ConfigRepository $config */
        $config = static::getContainer()->make(ConfigRepository::class);

        return (array) $config->get(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', []);
    }

    /**
     * Get IDEntity typed class name for passed type.
     *
     * @param string|null $type
     *
     * @return class-string|null
     */
    protected static function getEntityClassByType(?string $type): ?string
    {
        return static::typeIsSupported($type)
            ? static::getTypesMap()[$type]
            : null;
    }
}
