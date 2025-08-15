<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\VinCodeValidatorExtension;

class IDEntityVin extends AbstractTypedIDEntity
{
    private const REPLACEMENTS = [
        'Q' => '0',
        'O' => '0',
        'I' => '1',
        'З' => '3',
        'Д' => 'D',
        'О' => '0',
        'А' => 'A',
        'В' => 'B',
        'Е' => 'E',
        'К' => 'K',
        'М' => 'M',
        'Н' => 'H',
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
        return static::ID_TYPE_VIN;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            if (! \is_string($value)) {
                return null;
            }

            $value = mb_strtoupper($value, 'UTF-8');

            foreach (self::REPLACEMENTS as $from => $to) {
                $value = str_replace($from, $to, $value);
            }

            return preg_replace('/[^\p{L}\p{N}]/u', '', $value);
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
            /** @var VinCodeValidatorExtension $validator */
            $validator = static::getContainer()->make(VinCodeValidatorExtension::class);

            return $validator->passes('', $this->value);
        }

        return false;
    }
}
