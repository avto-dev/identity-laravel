<?php

namespace AvtoDev\IDEntity\Types;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

interface TypedIDEntityInterface extends Arrayable, Jsonable
{
    /**
     * Возвращает строковое представление объекта при попытке преобразовать в строку последнего.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Устанавливает значение идентификатора.
     *
     * @param string $value
     * @param bool   $make_normalization
     *
     * @return self|static
     */
    public function setValue(string $value, bool $make_normalization = true);

    /**
     * Возвращает значение идентификатора.
     *
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * Возвращает значение идентификатора, но скрытое за маской.
     *
     * @param int    $start_offset Сдвиг с начала
     * @param int    $end_offset   Сдвиг с конца
     * @param string $mask_char    Замещающий символ
     *
     * @return string|null
     */
    public function getMaskedValue(int $start_offset = 3, int $end_offset = 3, string $mask_char = '*'): ?string;

    /**
     * Возвращает тип идентификатора.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Производит проверку установленного значения с помощью callback-функций из стека callback-функций.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Производит проверку на возможность идентификатора быть автоматически определяемым.
     *
     * @return bool
     */
    public function canBeAutoDetected(): bool;

    /**
     * Производит нормализацию входного значения согласно типу.
     *
     * @param string $value
     *
     * @return string|null
     */
    public static function normalize($value): ?string;
}
