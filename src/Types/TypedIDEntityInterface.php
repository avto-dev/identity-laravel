<?php

namespace AvtoDev\IDEntity\Types;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

interface TypedIDEntityInterface extends Arrayable, Jsonable
{
    /**
     * Get ID entity as a string (value only).
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Set ID entity value.
     *
     * @param string $value
     * @param bool   $make_normalization
     *
     * @return self|static
     */
    public function setValue(string $value, bool $make_normalization = true);

    /**
     * Get ID entity value.
     *
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * Get masked value.
     *
     * @param int    $start_offset
     * @param int    $end_offset
     * @param string $mask_char
     *
     * @return string|null
     */
    public function getMaskedValue(int $start_offset = 3, int $end_offset = 3, string $mask_char = '*'): ?string;

    /**
     * Get ID entity type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Current value is valid?
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Current ID entity can be auto-detected (if type is set `AUTODETECT`).
     *
     * @return bool
     */
    public function canBeAutoDetected(): bool;

    /**
     * Make ID entity value normalization.
     *
     * @param string $value
     *
     * @return string|null
     */
    public static function normalize($value): ?string;
}
