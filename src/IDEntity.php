<?php

namespace AvtoDev\IDEntity;

use LogicException;
use AvtoDev\IDEntity\Types\IDEntityGrz;
use AvtoDev\IDEntity\Types\IDEntityPts;
use AvtoDev\IDEntity\Types\IDEntitySts;
use AvtoDev\IDEntity\Types\IDEntityVin;
use AvtoDev\IDEntity\Types\IDEntityBody;
use AvtoDev\IDEntity\Types\IDEntityChassis;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

/**
 * Class IDEntity.
 *
 * Объект идентификационной сущности.
 */
class IDEntity implements IDEntityInterface
{
    /**
     * Константы значений типов идентификаторов.
     *
     * ВНИМАНИЕ! При добавлении нового типа не забудь его добавить в метод getSupportedTypes().
     */
    const
        // Тип обозначает необходимость в автоматическом определении типа
        ID_TYPE_AUTO = 'AUTODETECT';
    const // Неизвестный тип идентификатора
        ID_TYPE_UNKNOWN = 'UNKNOWN';
    const // Тип - VIN-код
        ID_TYPE_VIN = 'VIN';
    const // Тип - регистрационный (ГРЗ) знак
        ID_TYPE_GRZ = 'GRZ';
    const // Тип - код СТС (Номер свидетельства о регистрации ТС)
        ID_TYPE_STS = 'STS';
    const // Тип - код ПТС (паспорт транспортного средства)
        ID_TYPE_PTS = 'PTS';
    const // Тип - номер шасси (встречается редко, но всё же встречается)
        ID_TYPE_CHASSIS = 'CHASSIS';
    const // Тип - номер кузова
        ID_TYPE_BODY = 'BODY';

    /**
     * IDEntity constructor.
     *
     * Запрещаем использование конструктора в пользу фабричного метода.
     *
     * @throws LogicException
     */
    public function __construct()
    {
        throw new LogicException(
            sprintf('Constructor for this object is unsupported. Use method "::%s" instead', 'make')
        );
    }

    /**
     * Возвращает массив поддерживаемых типов идентификаторов.
     *
     * @return string[]
     */
    public static function getSupportedTypes()
    {
        return array_keys(static::getTypesMap());
    }

    /**
     * Проверяет наличие поддержки переданного типа идентификатора.
     *
     * @param string $type
     *
     * @return bool
     */
    public static function typeIsSupported($type)
    {
        return is_string($type) && in_array($type, static::getSupportedTypes());
    }

    /**
     * {@inheritdoc}
     */
    public static function make($value, $type = self::ID_TYPE_AUTO)
    {
        // Если указанный тип идентификатора нам известен - то его и создаём
        if (class_exists($class_name = static::getEntityClassByType($type))) {
            return new $class_name($value);
        }

        // Если указан тип "авто-определение" - то поочерёдно создаем каждый тип, и проверяем соответствие методом
        // валидации
        if ($type === self::ID_TYPE_AUTO) {
            foreach (static::getTypesMap() as $class_name) {
                /** @var TypedIDEntityInterface $instance */
                if (($instance = new $class_name($value)) && $instance->isValid()) {
                    return $instance;
                }
            }
        }

        return new IDEntityUnknown($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function is($value, $type)
    {
        return self::make($value, $type)->isValid();
    }

    /**
     * Метод, возвращающий массив связок "%тип_идентификатора% => %класс_его_обслуживающий%".
     *
     * @return string[]
     */
    protected static function getTypesMap()
    {
        return [
            self::ID_TYPE_VIN     => IDEntityVin::class,
            self::ID_TYPE_BODY    => IDEntityBody::class,
            self::ID_TYPE_CHASSIS => IDEntityChassis::class,
            self::ID_TYPE_PTS     => IDEntityPts::class,
            self::ID_TYPE_GRZ     => IDEntityGrz::class,
            self::ID_TYPE_STS     => IDEntitySts::class,
        ];
    }

    /**
     * Возвращает имя класса, который обслуживает идентификатор по его типу. В случае ошибки или не обнаружения - вернет
     * null.
     *
     * @param string $type
     *
     * @return string|null
     */
    protected static function getEntityClassByType($type)
    {
        return static::typeIsSupported($type)
            ? static::getTypesMap()[$type]
            : null;
    }
}
