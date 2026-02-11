<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\PtsCodeValidatorExtension;

class IDEntityPts extends AbstractTypedIDEntity
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
        return static::ID_TYPE_PTS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Uppercase + trim
            $value = \mb_strtoupper(\trim((string) $value), 'UTF-8');

            // Remove all chars except allowed
            $value = (string) \preg_replace('~[^\p{L}0-9]|[ЁЙЪЬ]~u', '', $value);

            // Replace latin chars with cyrillic analogs (backward transliteration)
            $value = Transliterator::detransliterateString($value);

            return $value;
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
            /** @var PtsCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(PtsCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
