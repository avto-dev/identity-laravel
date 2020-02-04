<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\PtsCodeValidatorExtension;

class IDEntityPts extends AbstractTypedIDEntity
{
    /**
     * {@inheritDoc}
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
            $value = (string) \preg_replace('~[^' . 'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЫЭЮЯ' . 'A-Z' . '0-9]~u', '', $value);

            // Replace latin- chars with kyr- analogs (backward transliteration)
            $value = Transliterator::detransliterateString($value, true);

            return $value;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var PtsCodeValidatorExtension $validator */
        $validator = static::getContainer()->make(PtsCodeValidatorExtension::class);

        return \is_string($this->value) && $validator->passes('', $this->value);
    }
}
