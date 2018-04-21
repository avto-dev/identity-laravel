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
     * Устанавливает значение идентификатора.
     *
     * @param string $value
     * @param bool   $make_normalization
     *
     * @return self|static
     */
    public function setValue($value, $make_normalization = true);

    /**
     * Возвращает значение идентификатора.
     *
     * @return string|null
     */
    public function getValue();

    /**
     * Возвращает значение идентификатора, но скрытое за маской.
     *
     * @param int    $start_offset Сдвиг с начала
     * @param int    $end_offset   Сдвиг с конца
     * @param string $mask_char    Замещающий символ
     *
     * @return string|null
     */
    public function getMaskedValue($start_offset = 3, $end_offset = 3, $mask_char = '*');

    /**
     * Возвращает тип идентификатора.
     *
     * @return string|null
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
