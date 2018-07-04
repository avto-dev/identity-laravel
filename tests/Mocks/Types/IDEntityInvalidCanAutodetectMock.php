<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

class IDEntityInvalidCanAutodetectMock extends AbstractTypeIDEntityMock
{
    const TYPE = 'INVALID_CAN_AUTODETECT';

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
            return false;
        };
    }
}
