<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;
use AvtoDev\IDEntity\Types\IDEntityCadastralNumber;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityCadastralNumber<extended>
 */
class IDEntityCadastralNumberTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityCadastralNumber
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_CADASTRAL_NUMBER, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            '09:04:0134001:102',
            '10:01:0030104:691',
            '11:05:0105013:390',
            '13:23:1203002:556',
            '14:36:102034:2256',
            '15:09:0020708:133',
            '16:18:140401:1627',
            '17:10:0601038:174',
            '18:26:041293:142',
            '19:01:020109:1480',
            '21:05:010142:710',
            '22:60:150103:2689',
            '23:47:0308001:925',
            '24:50:0100498:1287',
            '25:28:050005:802',
            '26:12:011503:10279',
            '27:23:0000000:1348',
            '29:13:031001:1116',
            '30:12:032078:616',
            '31:16:0114021:959',
            '32:01:0280602:40',
            '33:22:011262:526',
            '34:34:030070:656',
            '35:24:0000000:1828',
            '36:23:0101007:737',
            '37:29:010121:75',
            '38:06:100801:26333',
            '39:15:131926:797',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid(), $value);
        }

        $invalid = [
            '359:924:190:795',
            '5:01:4286525/047215',
            '0:22:4357409:744560',
            '9:79:822:078276',
            '061:212:111:597',
            '2:42:9790497/271694',
            '923:045:888:214',
            '0:65:260:685116',
            '266:320:100:338',
            'foo bar',
            '{cadastal number}',
            '12:ca123das22tal;number=',
            '7:42:2550168/906215',
            '9:69:3759000:798065',
            '5:66:5951772:833946',
            '2:24:3483960/790543',
            '6:15:5787963:669678',
            '[array12:11]:34',
            '7:41:944:150150',
            '66/41/0000000/38949',
            '66;41;0000000;38949',
            '66\'41\'0000000\'38949',
            '66"41"0000000"38949',
            '66.41.0000000.38949',
            '66,41,0000000,38949',
            '66-41-0000000-38949',
            '66=41=0000000=38949',
            '66*41*0000000*38949',

            // C несуществующим регионом
            '92:77:031622:8428',
            '93:27:427934:1',
            '94:81:1535682:971',
            '95:27:9584113:671510',
            '96:51:372923:8028',
            '97:96:2700420:365298',
            '98:12:234567:1',
            '99:72:874527:985',

            // C реальным регионом и несуществующим районом
            '18:50:031622:8428',
            '84:27:427934:1',
            '85:81:1535682:971',
            '88:27:9584113:671510',
            '80:72:874527:985',
            '81:72:874527:985',
            '66:75:874527:985',
            '50:75:7345257:8',
            '77:84:996770:5193',
            '38:49:924785:832907',
        ];

        foreach ($invalid as $value) {
            $this->assertFalse($this->instance->setValue($value)->isValid(), $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $valid = $this->getValidValue();

        // Пробелы с двум сторон
        $this->assertEquals($valid, $this->instance::normalize(' ' . $valid . ' '));

        // Запрещенные символы
        $this->assertEquals($valid, $this->instance::normalize('6+6:/4$1:;0(1%^0)&5*-0!0@1#:=?3'));

        // С буквами
        $this->assertEquals($valid, $this->instance::normalize('Start6Шесть6:4One1:01ZeRO05001:ThrEE3'));
        //Первый символ не цифра
        $this->assertFalse($this->instance->setValue(':D61:41:123456:102360')->isValid());
        // Засовываем всякую шляпу
        foreach ([
                     function () {
                     },
                     new static,
                     new \stdClass,
                     ['foo' => 'bar'],
                 ] as $item) {
            $this->assertNull($this->instance::normalize($item));
        }
    }

    /**
     * Test of method getNumberInfo.
     */
    public function testGetNumberInfo(): void
    {
        $this->assertInstanceOf(CadastralNumberInfo::class, $this->instance->getNumberInfo());
        $this->assertSame(66, $this->instance->getNumberInfo()->getDistrictCode());
        $this->assertSame(41, $this->instance->getNumberInfo()->getAreaCode());
        $this->assertSame('0105001', $this->instance->getNumberInfo()->getSectionCode());
        $this->assertSame('3', $this->instance->getNumberInfo()->getParcelNumber());

        $this->instance->setValue('52:25');
        $this->assertSame(
            ['district' => 52, 'area' => 25, 'section' => '', 'parcel_number' => ''],
            $this->instance->getNumberInfo()->getFragments()
        );
    }

    /**
     * Test of method getRegionData.
     */
    public function testGetRegionData(): void
    {
        $this->assertInstanceOf(CadastralDistrict::class, $this->instance->getDistrictData());

        $this->instance->setValue('');
        $this->assertNull($this->instance->getDistrictData());
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityCadastralNumber::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return '66:41:0105001:3';
    }
}
