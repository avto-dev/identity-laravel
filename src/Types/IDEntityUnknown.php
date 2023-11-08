<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

class IDEntityUnknown extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_UNKNOWN;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            /** @throws \Throwable */
            return (string) $value;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return false;
    }
}
