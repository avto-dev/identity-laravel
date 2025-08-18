<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\ExtendedLaravelValidator\Extensions\VinCodeValidatorExtension;

class IDEntityVin extends AbstractTypedIDEntity
{
    protected const REPLACE_FROM = ['Q', 'O', 'I', 'З', 'Д', 'О', 'А', 'В', 'Е', 'К', 'М', 'Н', 'Р', 'С', 'Т', 'У', 'Х'];
    protected const REPLACE_TO   = ['0', '0', '1', '3', 'D', '0', 'A', 'B', 'E', 'K', 'M', 'H', 'P', 'C', 'T', 'Y', 'X'];

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
            if (!\is_string($value)) {
                return null;
            }

            $value = \mb_strtoupper($value, 'UTF-8');
            $value = \str_replace(self::REPLACE_FROM, self::REPLACE_TO, $value);

            return \preg_replace('/[^\p{L}\p{N}]/u', '', $value);
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
