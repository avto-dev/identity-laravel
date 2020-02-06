<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use function mb_strlen;
use function mb_substr;
use AvtoDev\IDEntity\IDEntity;
use Tarampampam\Wrappers\Json;

abstract class AbstractTypedIDEntity extends IDEntity implements TypedIDEntityInterface
{
    /**
     * @var string|null
     */
    protected $value;

    /**
     * @var bool
     */
    protected $can_be_auto_detected = true;

    /**
     * Create a new typed IDEntity instance.
     *
     * @param string $value
     * @param bool   $make_normalization
     */
    final public function __construct(string $value, bool $make_normalization = true)
    {
        parent::__construct();

        $this->setValue($value, $make_normalization);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public static function make(string $value, ?string $type = null)
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function is(string $value, ?string $type = null): bool
    {
        return static::make($value)->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(string $value, bool $make_normalization = true)
    {
        $this->value = $make_normalization === true
            ? static::normalize($value)
            : $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaskedValue(int $start_offset = 3, int $end_offset = 3, string $mask_char = '*'): ?string
    {
        return $this->value === null
            ? null
            : $this->hideString($this->value, $start_offset, $end_offset, $mask_char);
    }

    /**
     * @return array{value:?string, type:string}
     */
    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'type'  => $this->getType(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return Json::encode((object) $this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeAutoDetected(): bool
    {
        return $this->can_be_auto_detected;
    }

    /**
     * Hide part of string using 'mask char'.
     *
     * @param string $string
     * @param int    $start_offset
     * @param int    $end_offset
     * @param string $mask_char
     *
     * @return string
     */
    protected function hideString(string $string,
                                  int $start_offset = 3,
                                  int $end_offset = 3,
                                  string $mask_char = '*'): string
    {
        if (mb_strlen($mask_char) > 1) {
            $mask_char = (string) mb_substr($mask_char, 0, 1);
        }

        $number_length = mb_strlen($string);

        if ($number_length <= $start_offset + $end_offset) {
            return $string;
        }

        $hidden_str    = mb_substr($string, $start_offset, $number_length - ($start_offset + $end_offset));
        $stars         = '';
        $hidden_length = mb_strlen($hidden_str);

        for ($i = 0; $i < $hidden_length; $i++) {
            $stars .= $mask_char;
        }

        return mb_substr($string, 0, $start_offset)
               . $stars
               . mb_substr($string, $number_length - $end_offset, $end_offset);
    }
}
