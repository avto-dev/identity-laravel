<?php

namespace AvtoDev\IDEntity\Tests\Mocks\Types;

class IDEntityValidCanAutodetectMock extends AbstractTypeIDEntityMock
{
    const TYPE = 'VALID_CAN_AUTODETECT';

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
}
