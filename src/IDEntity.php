<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\IDEntityVin;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;
use LogicException;

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
        ID_TYPE_AUTO = 'AUTODETECT',

        // Тип - VIN-код
        ID_TYPE_VIN = 'VIN',

        // Тип - регистрационный (ГРЗ) знак
        ID_TYPE_GRZ = 'GRZ',

        // Тип - код СТС (Номер свидетельства о регистрации ТС)
        ID_TYPE_STS = 'STS',

        // Тип - код ПТС (паспорт транспортного средства)
        ID_TYPE_PTS = 'PTS',

        // Тип - номер шасси (встречается редко, но всё же встречается)
        ID_TYPE_CHASSIS = 'CHASSIS',

        // Тип - номер кузова
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
     * Метод, возвращающий массив связок "%тип_идентификатора% => %класс_его_обслуживающий%".
     *
     * @return string[]
     */
    protected static function getSupportedTypes()
    {
        return [
            self::ID_TYPE_VIN => IDEntityVin::class,
        ];
    }

    /**
     * Проверяет наличие поддержки переданного типа идентификатора.
     *
     * @param string $id_type
     *
     * @return bool
     */
    public static function typeIsSupported($id_type)
    {
        return in_array($id_type, array_keys(static::getSupportedTypes()));
    }

    /**
     * Возвращает имя класса, который обслуживает идентификатор по его типу. В случае ошибки или не обнаружения - вернет
     * null.
     *
     * @param string $id_type
     *
     * @return string|null
     */
    protected static function getEntityClassByType($id_type)
    {
        return static::typeIsSupported($id_type)
            ? static::getSupportedTypes()[$id_type]
            : null;
    }

    /**
     * Фабричный метод, замена конструктору.
     *
     * @param string|mixed $id_value
     * @param string       $id_type
     *
     * @return TypedIDEntityInterface
     */
    public static function make($id_value, $id_type = self::ID_TYPE_AUTO)
    {
        //
    }
}
