<?php

namespace AvtoDev\IDEntity\Types;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Interface TypedIDEntityInterface.
 *
 * Типизированная идентификационная сущность.
 */
interface TypedIDEntityInterface extends Arrayable, Jsonable
{
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
     * Возвращает строковое представление объекта при попытке преобразовать в строку последнего.
     *
     * @return string
     */
    public function __toString();
}
