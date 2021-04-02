<?php

namespace AvtoDev\IDEntity\Tests\Mocks;

use BadMethodCallException;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

class TypedIDEntityMock implements TypedIDEntityInterface
{
    /**
     * @var string|null
     */
    public static $value;

    /**
     * @var string|null
     */
    public static $type;

    /**
     * @var bool|null
     */
    public static $detectable;

    /**
     * @var bool|null
     */
    public static $is_valid;

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return (string) static::$value;
    }

    /**
     * Reset own static properties state.
     *
     * @return void
     */
    public static function reset(): void
    {
        static::$value      = null;
        static::$type       = null;
        static::$detectable = null;
        static::$is_valid   = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(string $value, bool $make_normalization = true)
    {
        static::$value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): ?string
    {
        return static::$value;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeAutoDetected(): bool
    {
        return static::$detectable ?? true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadMethodCallException
     */
    public function getMaskedValue(int $start_offset = 3, int $end_offset = 3, string $mask_char = '*'): ?string
    {
        throw new BadMethodCallException('Not supported');
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::$type ?? 'TYPED_MOCK';
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return static::$is_valid ?? true;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        return \is_string($value)
            ? \mb_strtoupper($value, 'UTF-8')
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'value' => static::$type,
            'type'  => $this->getType(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0): string
    {
        return \json_encode($this->toArray(), $options);
    }
}
