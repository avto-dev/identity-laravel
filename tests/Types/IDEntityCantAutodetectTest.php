<?php

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\Tests\AbstractTestCase;
use AvtoDev\IDEntity\Tests\Mocks\IDEntityCantAutodetectMock;

/**
 * Class IDEntityBodyTest.
 */
class IDEntityCantAutodetectTest extends AbstractTestCase
{
    /**
     * Тестируем, что сущность не может быть автоматически определена.
     */
    public function testCanAutodetectMethod()
    {
        $instance = new IDEntityCantAutodetectMock('');

        $this->assertFalse($instance->canAutodetect());
    }
}
