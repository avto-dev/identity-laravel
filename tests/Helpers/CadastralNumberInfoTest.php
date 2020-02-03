<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Tests\AbstractTestCase;
use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;

/**
 * @covers \AvtoDev\IDEntity\Helpers\CadastralNumberInfo
 */
class CadastralNumberInfoTest extends AbstractTestCase
{
    /**
     * Проверка работы всех геттеров.
     */
    public function testGetters(): void
    {
        $attempts = 0;
        do {
            $cadastral_number = \sprintf(
                '%02d:%02d:%06d:%03d',
                $district = \random_int(1, 91),
                $area = \random_int(1, 9),
                $section = \random_int(1, 999999),
                $parcel_number = \random_int(1, 999)
            );
            $attempts++;
            $helper = CadastralNumberInfo::parse($cadastral_number);
            $this->assertEquals($helper->getDistrictCode(), $district);
            $this->assertEquals($helper->getAreaCode(), $area);
            $this->assertEquals($helper->getSectionCode(), $section);
            $this->assertEquals($helper->getParcelNumber(), $parcel_number);
        } while ($attempts < 10);

        // Check with empty string
        $helper = CadastralNumberInfo::parse('');
        $this->assertIsObject($helper);
        $this->assertSame(
            ['district' => 0, 'area' => 0, 'section' => '', 'parcel_number' => ''],
            $helper->toArray()
        );

        // Check if value contains letters
        $helper = CadastralNumberInfo::parse('foo:bar:this:shit');
        $this->assertSame(
            ['district' => 0, 'area' => 0, 'section' => 'this', 'parcel_number' => 'shit'],
            $helper->toArray()
        );

        // Check \trim in fragments
        $helper = CadastralNumberInfo::parse('   foo  : bar:this :shit   ');
        $this->assertSame(
            ['district' => 0, 'area' => 0, 'section' => 'this', 'parcel_number' => 'shit'],
            $helper->toArray()
        );

        // Check without delimiter
        $helper = CadastralNumberInfo::parse('   foo   ');
        $this->assertSame(
            ['district' => 0, 'area' => 0, 'section' => '', 'parcel_number' => ''],
            $helper->toArray()
        );

        // Check with null
        $helper = CadastralNumberInfo::parse(null);
        $this->assertSame(
            ['district' => 0, 'area' => 0, 'section' => '', 'parcel_number' => ''],
            $helper->toArray()
        );
    }
}
