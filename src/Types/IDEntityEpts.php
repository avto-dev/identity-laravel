<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\ExtendedLaravelValidator\Extensions\EptsCodeValidatorExtension;

class IDEntityEpts extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_EPTS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            $value = \trim((string) $value);

            // Remove all chars except digital
            return (string) \preg_replace('/[^0-9]/', '', $value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (\is_string($this->value) && $this->value !== '') {
            /** @var EptsCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(EptsCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
