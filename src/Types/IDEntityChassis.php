<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\ChassisCodeValidatorExtension;

class IDEntityChassis extends AbstractTypedIDEntity
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
        return static::ID_TYPE_CHASSIS;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Replace multiple whitespaces with one
            $value = (string) \preg_replace('~\s+~u', ' ', \trim((string) $value));

            // Normalize dash chars
            $value = Normalizer::normalizeDashChar($value);

            // Transliterate cyrillic chars with latin
            $value = Transliterator::transliterateString(\mb_strtoupper($value, 'UTF-8'), true);

            // Remove all chars except allowed
            $value = (string) \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

            // Replace multiple dashes with one
            $value = (string) \preg_replace('~-+~', '-', $value);

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
            /** @var ChassisCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(ChassisCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
