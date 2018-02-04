<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntity;

/**
 * Class IDEntityTest.
 *
 * @todo: Write class description.
 */
class IDEntityTest extends AbstractTestCase
{
    /**
     * Тест констант.
     *
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('AUTODETECT', IDEntity::ID_TYPE_AUTO);
        $this->assertEquals('VIN', IDEntity::ID_TYPE_VIN);
        $this->assertEquals('GRZ', IDEntity::ID_TYPE_GRZ);
        $this->assertEquals('STS', IDEntity::ID_TYPE_STS);
        $this->assertEquals('PTS', IDEntity::ID_TYPE_PTS);
        $this->assertEquals('CHASSIS', IDEntity::ID_TYPE_CHASSIS);
        $this->assertEquals('BODY', IDEntity::ID_TYPE_BODY);
    }
}
