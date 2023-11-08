<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityCadastralNumber;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityCadastralNumber
 */
class IDEntityCadastralNumberTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_CADASTRAL_NUMBER;

    /**
     * @return void
     */
    public function testGetDistrictCode(): void
    {
        $entity = $this->entityFactory();

        foreach (\range(0, 99) as $code) {
            $this->assertSame($code, $entity->setValue("{$code}:41:0:1")->getDistrictCode());
        }

        $this->assertNull($entity->setValue('')->getDistrictCode());
        $this->assertNull($entity->setValue('41:0:1')->getDistrictCode());
        $this->assertNull($entity->setValue(Str::random())->getDistrictCode());
    }

    /**
     * @return void
     */
    public function testGetAreaCode(): void
    {
        $entity = $this->entityFactory();

        foreach (\range(0, 99) as $code) {
            $this->assertSame($code, $entity->setValue("66:{$code}:0:1")->getAreaCode());
        }

        $this->assertNull($entity->setValue('')->getAreaCode());
        $this->assertNull($entity->setValue('41:0:1')->getAreaCode());
        $this->assertNull($entity->setValue(Str::random())->getAreaCode());
    }

    /**
     * @return void
     */
    public function testGetSectionCode(): void
    {
        $entity = $this->entityFactory();

        foreach (\range(0, 99) as $code) {
            $this->assertSame($code, $entity->setValue("66:41:{$code}:1")->getSectionCode());
        }

        $this->assertNull($entity->setValue('')->getSectionCode());
        $this->assertNull($entity->setValue('41:0:1')->getSectionCode());
        $this->assertNull($entity->setValue(Str::random())->getSectionCode());
    }

    /**
     * @return void
     */
    public function testGetParcelCode(): void
    {
        $entity = $this->entityFactory();

        foreach (\range(0, 99) as $code) {
            $this->assertSame($code, $entity->setValue("66:41:0003321:{$code}")->getParcelCode());
        }

        $this->assertNull($entity->setValue('')->getParcelCode());
        $this->assertNull($entity->setValue('41:0:1')->getParcelCode());
        $this->assertNull($entity->setValue(Str::random())->getParcelCode());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = '66:41:0010501:3', $entity::normalize(" {$valid}  "));
        $this->assertSame($valid, $entity::normalize('66:41:10501:3'));
        $this->assertSame($valid, $entity::normalize('Start6Шесть6:4One1:01ZeRO0501:ThrEE3'));
        $this->assertSame($valid, $entity::normalize(':: D66:41:0010501:3'));
        $this->assertSame('04:05:0000006:7', $entity::normalize('4:5:6:7'));
    }

    /**
     * @return void
     */
    public function testGetRegionData(): void
    {
        $entity = $this->entityFactory();

        $this->assertInstanceOf(CadastralDistrict::class, $entity->getDistrictData());

        $entity->setValue('');
        $this->assertNull($entity->getDistrictData());
    }

    /**
     * @return void
     */
    public function testGetValueWhenInvalidValueIsSet(): void
    {
        $entity = $this->entityFactory();

        foreach (['52', '52:', '52:0', '52:0:', '52:0:1'] as $value) {
            $this->assertNull($entity->setValue($value)->getValue());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityCadastralNumber
    {
        return new IDEntityCadastralNumber($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '02:04:000221:2',
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
            '39:05:131926:7',

            // Last part more than 1
            '66:41:0:1',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            '0:0:0:0',
            '66:0:0:0',
            '66:41:0:0',
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

            // Unknown region codes
            '92:77:031622:8428',
            '93:27:427934:1',
            '94:81:1535682:971',
            '95:27:9584113:671510',
            '96:51:372923:8028',
            '97:96:2700420:365298',
            '98:12:234567:1',
            '99:72:874527:985',

            // Valid region code but invalid area code
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

            '',
            Str::random(32),
        ];
    }
}
