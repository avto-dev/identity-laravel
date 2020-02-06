<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntityInterface;

class IDEntityInterfaceTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testConstants(): void
    {
        $this->assertSame('AUTODETECT', IDEntityInterface::ID_TYPE_AUTO);
        $this->assertSame('UNKNOWN', IDEntityInterface::ID_TYPE_UNKNOWN);
        $this->assertSame('VIN', IDEntityInterface::ID_TYPE_VIN);
        $this->assertSame('GRZ', IDEntityInterface::ID_TYPE_GRZ);
        $this->assertSame('STS', IDEntityInterface::ID_TYPE_STS);
        $this->assertSame('PTS', IDEntityInterface::ID_TYPE_PTS);
        $this->assertSame('CHASSIS', IDEntityInterface::ID_TYPE_CHASSIS);
        $this->assertSame('BODY', IDEntityInterface::ID_TYPE_BODY);
        $this->assertSame('DLN', IDEntityInterface::ID_TYPE_DRIVER_LICENSE_NUMBER);
        $this->assertSame('CADNUM', IDEntityInterface::ID_TYPE_CADASTRAL_NUMBER);
    }
}
