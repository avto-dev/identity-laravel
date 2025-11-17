<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Strings;
use AvtoDev\ExtendedLaravelValidator\Extensions\BodyCodeValidatorExtension;

class IDEntityBody extends AbstractTypedIDEntity
{
    protected const REPLACEMENTS_LAT_CYR = [
        'A' => 'А',
        'B' => 'В',
        'E' => 'Е',
        'K' => 'К',
        'M' => 'М',
        'H' => 'Н',
        'O' => 'О',
        'P' => 'Р',
        'C' => 'С',
        'T' => 'Т',
        'X' => 'Х',
        'Y' => 'У',
    ];

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
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        if (!\is_string($value)) {
            return null;
        }

        $value = Strings::removeNonAlphanumericChars($value);

        $value = \mb_strtoupper($value, 'UTF-8');

        $replacements = Strings::hasSpecificCyrillicUpperLetters($value)
            ? static::REPLACEMENTS_LAT_CYR
            : \array_flip(static::REPLACEMENTS_LAT_CYR);

        return Strings::replaceByMap($value, $replacements);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (\is_string($this->value) && $this->value !== '') {
            /** @var BodyCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(BodyCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
