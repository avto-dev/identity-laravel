<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

/**
 * Interface IDEntityInterface.
 *
 * Интерфейс идентификационной сущности.
 */
interface IDEntityInterface
{
    /**
     * Фабричный метод, замена конструктору.
     *
     * @param string|mixed $value
     * @param string       $type
     *
     * @return TypedIDEntityInterface
     */
    public static function make($value, $type);

    /**
     * Проверяет, является ли переданное значение в $value типом $type.
     *
     * @param string $value
     * @param string $type
     *
     * @return bool
     */
    public static function is($value, $type);
}
