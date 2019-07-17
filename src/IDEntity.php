<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity;

use Exception;
use Illuminate\Container\Container;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class IDEntity implements IDEntityInterface
{
    /**
     * Create a new IDEntity instance.
     */
    protected function __construct()
    {
        // Disable outside constructor calling
    }

    /**
     * Возвращает массив поддерживаемых типов идентификаторов.
     *
     * @return string[]
     */
    public static function getSupportedTypes(): array
    {
        return \array_keys(static::getTypesMap());
    }

    /**
     * Проверяет наличие поддержки переданного типа идентификатора.
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

        // Если указанный тип идентификатора нам известен - то его и создаём
        if (\is_string($class_name) && \class_exists($class_name)) {
            return new $class_name($value, true);
        }

        // Если указан тип "авто-определение" - то поочерёдно создаем каждый тип, проверяем, может ли он быть
        // автоматически определяемым, и проверяем соответствие методом валидации
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
     * Метод, возвращающий массив связок "%тип_идентификатора% => %класс_его_обслуживающий%".
     *
     * Порядок элементов важен для механизма автоматического определения типа.
     *
     * @return string[]
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
        ], static::getExtendedTypesMap());
    }

    /**
     * Get an extended types map, declared in configuration file.
     *
     * @return string[]|array
     */
    protected static function getExtendedTypesMap(): array
    {
        try {
            return (array) static::getContainer()->make(ConfigRepository::class)->get(
                \sprintf('%s.extended_types_map', ServiceProvider::getConfigRootKeyName())
            );
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Resolve container instance.
     *
     * @return Container
     */
    protected static function getContainer(): Container
    {
        return Container::getInstance();
    }

    /**
     * Возвращает имя класса, который обслуживает идентификатор по его типу. В случае ошибки или не обнаружения - вернет
     * null.
     *
     * @param string|null $type
     *
     * @return string|null
     */
    protected static function getEntityClassByType(?string $type): ?string
    {
        return static::typeIsSupported($type)
            ? static::getTypesMap()[$type]
            : null;
    }
}
