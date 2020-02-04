<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;

class IDEntityCantAutodetectMock extends AbstractTypedIDEntity
{
    public const TYPE = 'CANT_BE_AUTODETECT';

    /**
     * {@inheritdoc}
     */
    protected $can_be_auto_detected = false;

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        return $value;
    }
}
