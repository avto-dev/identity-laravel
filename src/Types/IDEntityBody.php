<?php

declare(strict_types=1);

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

    protected const REPLACEMENTS_CYR_LAT = [
        'А' => 'A',
        'В' => 'B',
        'Е' => 'E',
        'К' => 'K',
        'М' => 'M',
        'Н' => 'H',
        'О' => 'O',
        'Р' => 'P',
        'С' => 'C',
        'Т' => 'T',
        'У' => 'Y',
        'Х' => 'X',
    ];


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
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            if (!\is_string($value)) {
                throw new \LogicException('Value must be a string.');
            }

            $value = Strings::onlyAlfaNumeric($value);

            $value = \mb_strtoupper($value, 'UTF-8');

            $replacements = Strings::isCyrillicValue($value) ? static::REPLACEMENTS_LAT_CYR : static::REPLACEMENTS_CYR_LAT;

            return Strings::replaceByMap($value, $replacements);
        } catch (\Throwable) {
            return null;
        }
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
