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
     * Outside constructor calling disabled for this class.
     */
    protected function __construct()
    {
        //
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
     * @param string $type
     *
     * @return bool
     */
    public static function typeIsSupported(string $type): bool
    {
        return \in_array($type, static::getSupportedTypes(), true);
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
    public static function is(string $value, string $type): bool
    {
        return self::make($value, $type)->isValid();
    }

    /**
     * @return Container
     */
    protected static function getContainer(): Container
    {
        return Container::getInstance();
    }

    /**
     * This method returns an array, where key is supported IDEntity type, and value is a class for this type.
     *
     * Note: Order is important for automatic detection.
     *
     * @return array<string, class-string<TypedIDEntityInterface>>
     */
    protected static function getTypesMap(): array
    {
        return \array_merge([
            self::ID_TYPE_VIN                   => Types\IDEntityVin::class,
            self::ID_TYPE_GRZ                   => Types\IDEntityGrz::class,
            self::ID_TYPE_CADASTRAL_NUMBER      => Types\IDEntityCadastralNumber::class,
            self::ID_TYPE_STS                   => Types\IDEntitySts::class,
            self::ID_TYPE_PTS                   => Types\IDEntityPts::class,
            self::ID_TYPE_BODY                  => Types\IDEntityBody::class,
            self::ID_TYPE_CHASSIS               => Types\IDEntityChassis::class,
            self::ID_TYPE_DRIVER_LICENSE_NUMBER => Types\IDEntityDriverLicenseNumber::class,
        ], static::getExtendedTypesMap());
    }

    /**
     * Get an extended types map, declared in configuration file.
     *
     * @return array<string, class-string<TypedIDEntityInterface>>
     */
    protected static function getExtendedTypesMap(): array
    {
        /** @var ConfigRepository $config */
        $config = static::getContainer()->make(ConfigRepository::class);

        /** @var array<string, class-string<TypedIDEntityInterface>> */
        return (array) $config->get(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', []);
    }

    /**
     * Get IDEntity typed class name for passed type.
     *
     * @param string|null $type
     *
     * @return class-string<TypedIDEntityInterface>|null
     */
    protected static function getEntityClassByType(?string $type): ?string
    {
        return \is_string($type) && static::typeIsSupported($type)
            ? static::getTypesMap()[$type]
            : null;
    }
}
