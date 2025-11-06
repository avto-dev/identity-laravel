<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

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
        return IDEntityBody::normalize($value);
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
