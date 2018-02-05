<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityVin;

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
        $this->assertEquals('UNKNOWN', IDEntity::ID_TYPE_UNKNOWN);
        $this->assertEquals('VIN', IDEntity::ID_TYPE_VIN);
        $this->assertEquals('GRZ', IDEntity::ID_TYPE_GRZ);
        $this->assertEquals('STS', IDEntity::ID_TYPE_STS);
        $this->assertEquals('PTS', IDEntity::ID_TYPE_PTS);
        $this->assertEquals('CHASSIS', IDEntity::ID_TYPE_CHASSIS);
        $this->assertEquals('BODY', IDEntity::ID_TYPE_BODY);
    }

    public function testSome()
    {
        $instance = IDEntity::make('JF1SJ5LC5DG0486671', 'VIN');

        $vin = new IDEntityVin('jF1SJ5LC5DG048667');

        dump($vin, $vin->isValid(), $vin->getType());
        dump($instance, $instance->isValid());

        dump(IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN));
    }
}
