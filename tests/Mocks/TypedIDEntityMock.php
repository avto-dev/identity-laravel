<?php

namespace AvtoDev\IDEntity\Tests\Mocks;

use BadMethodCallException;
use Tarampampam\Wrappers\Json;
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
     * Reset own static properties state.
     *
     * @return void
     */
    public static function reset(): void
    {
        static::$value = null;
        static::$type = null;
        static::$detectable = null;
        static::$is_valid = null;
    }

    /**
     * {@inheritDoc}
     */
    public function setValue(string $value, bool $make_normalization = true)
    {
        static::$value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): ?string
    {
        return static::$value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return (string) static::$value;
    }

    /**
     * {@inheritDoc}
     */
    public function canBeAutoDetected(): bool
    {
        return static::$detectable ?? true;
    }

    /**
     * {@inheritDoc}
     *
     * @throws BadMethodCallException
     */
    public function getMaskedValue(int $start_offset = 3, int $end_offset = 3, string $mask_char = '*'): ?string
    {
        throw new BadMethodCallException('Not supported');
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return static::$type ?? 'TYPED_MOCK';
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(): bool
    {
        return static::$is_valid ?? true;
    }

    /**
     * {@inheritDoc}
     */
    public static function normalize($value): ?string
    {
        return \is_string($value)
            ? \mb_strtoupper($value, 'UTF-8')
            : null;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'value' => static::$type,
            'type'  => $this->getType(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function toJson($options = 0): string
    {
        return Json::encode($this->toArray(), $options);
    }
}
