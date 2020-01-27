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
                $region = \random_int(1, 91),
                $district = \random_int(1, 9),
                $quarter = \random_int(1, 999999),
                $area = \random_int(1, 999)
            );
            $attempts++;
            $helper = CadastralNumberInfo::parse($cadastral_number);
            $this->assertEquals($helper->getRegionCode(), $region);
            $this->assertEquals($helper->getDistrictCode(), $district);
            $this->assertEquals($helper->getQuarterCode(), $quarter);
            $this->assertEquals($helper->getAreaCode(), $area);
        } while ($attempts < 10);

        // Check with empty string
        $helper = CadastralNumberInfo::parse('');
        $this->assertInternalType('object', $helper);
        $this->assertSame(['region' => '', 'district' => '', 'quarter' => '', 'area' => ''], $helper->getFragments());

        // Check if value contains letters
        $helper = CadastralNumberInfo::parse('foo:bar:this:shit');
        $this->assertSame(
            ['region' => 'foo', 'district' => 'bar', 'quarter' => 'this', 'area' => 'shit'],
            $helper->getFragments()
        );

        // Check \trim in fragments
        $helper = CadastralNumberInfo::parse('   foo  : bar:this :shit   ');
        $this->assertSame(
            ['region' => 'foo', 'district' => 'bar', 'quarter' => 'this', 'area' => 'shit'],
            $helper->getFragments()
        );

        // Check without delimiter
        $helper = CadastralNumberInfo::parse('   foo   ');
        $this->assertSame(
            ['region' => 'foo', 'district' => '', 'quarter' => '', 'area' => ''],
            $helper->getFragments()
        );

        // Check with null
        $helper = CadastralNumberInfo::parse(null);
        $this->assertSame(
            ['region' => '', 'district' => '', 'quarter' => '', 'area' => ''],
            $helper->getFragments()
        );
    }
}
