<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

interface IDEntityInterface
{
    /**
     * Обозначает необходимость в автоматическом определении типа.
     *
     * @var string
     */
    public const ID_TYPE_AUTO = 'AUTODETECT';

    /**
     * Неизвестный тип идентификатора.
     *
     * @var string
     */
    public const ID_TYPE_UNKNOWN = 'UNKNOWN';

    /**
     * Тип - VIN-код.
     *
     * @var string
     */
    public const ID_TYPE_VIN = 'VIN';

    /**
     * Тип - регистрационный (ГРЗ) знак.
     *
     * @var string
     */
    public const ID_TYPE_GRZ = 'GRZ';

    /**
     * Тип - код СТС (Номер свидетельства о регистрации ТС).
     *
     * @var string
     */
    public const ID_TYPE_STS = 'STS';

    /**
     * Тип - код ПТС (паспорт транспортного средства).
     *
     * @var string
     */
    public const ID_TYPE_PTS = 'PTS';

    /**
     * Тип - номер шасси (встречается редко, но всё же встречается).
     *
     * @var string
     */
    public const ID_TYPE_CHASSIS = 'CHASSIS';

    /**
     * Тип - номер кузова.
     *
     * @var string
     */
    public const ID_TYPE_BODY = 'BODY';

    /**
     * Тип - номер водительского удостоверения (driver license number).
     *
     * @var string
     */
    public const ID_TYPE_DRIVER_LICENSE_NUMBER = 'DLN';

    /**
     * Фабричный метод, замена конструктору.
     *
     * @param string $value
     * @param string $type
     *
     * @return TypedIDEntityInterface
     */
    public static function make(string $value, ?string $type);

    /**
     * Проверяет, является ли переданное значение в $value типом $type (значения типов можно передать массивом).
     *
     * @param string          $value
     * @param string[]|string $type
     *
     * @return bool
     */
    public static function is(string $value, $type): bool;
}
