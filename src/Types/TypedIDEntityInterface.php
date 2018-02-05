<?php

namespace AvtoDev\IDEntity\Types;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Interface TypedIDEntityInterface.
 *
 * Типизированная идентификационная сущность.
 */
interface TypedIDEntityInterface extends Arrayable, Jsonable
{
    /**
     * Возвращает строковое представление объекта при попытке преобразовать в строку последнего.
     *
     * @return string
     */
    public function __toString();

    /**
     * Возвращает значение идентификатора.
     *
     * @return null|string
     */
    public function getValue();

    /**
     * Возвращает тип идентификатора.
     *
     * @return null|string
     */
    public function getType();

    /**
     * Производит проверку установленного значения с помощью callback-функций из стека callback-функций.
     *
     * @return bool
     */
    public function isValid();

    /**
     * Производит нормализацию входного значения согласно типу.
     *
     * @param string $value
     *
     * @return string|null
     */
    public static function normalize($value);
}
