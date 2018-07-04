<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

use AvtoDev\IDEntity\Types\AbstractTypedIDEntity;

class IDEntityCantAutodetectMock extends AbstractTypedIDEntity
{
    const TYPE = 'CANT_BE_AUTODETECT';

    /**
     * {@inheritdoc}
     */
    protected $can_be_autodetect = false;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidateCallbacks()
    {
        return function () {
            return true;
        };
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        return $value;
    }
}
