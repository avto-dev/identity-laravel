<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntitySts;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntitySts<extended>
 */
class IDEntityStsTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_STS;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = '61МЕ524040', $entity::normalize(Str::lower($valid)));
        $this->assertSame($valid, $entity::normalize("  {$valid} "));
        $this->assertSame($valid, $entity::normalize('61me524040'));
        $this->assertSame($valid, $entity::normalize('61МЕ ;?*:;% 524040 '));
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntitySts
    {
        return new IDEntitySts($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '78УЕ952328',
            '16НО224663',
            '78УС434434',
            '39НЕ248423',
            '40НК602618',
            '02УК922390',
            '47ТА183843',
            '77УР781043',
            '61МЕ524040',
            '36ТС369230',
            '66ЕА402408',
            '78ОН937380',
            '78НР408206',
            '78УХ169669',
            '47НН307196',
            '78УН113064',
            '78УА115947',
            '47НМ321533',
            '78ТО073191',
            '78УМ119929',
            '39ТМ563386',
            '42УК452588',
            '39НЕ245073',
            '39ТМ563386',
            '61МС370573',
            '77ТР323897',
            '25УР529766',
            '60ВТ667733',
            '78ОМ921332',
            '77УА935302',
            '40НВ079158',
            '78ОЕ428114',
            '78УС415371',
            '77УН805532',
            '78УЕ951322',
            '39НО522304',
            '78УС983408',
            '39ОА455936',
            '46УО999434',
            '77УО057998',
            '77УН704900',
            '39НТ284120',
            '40НХ126615',
            '78УТ188336',
            '78УЕ249779',
            '78ОН927226',
            '77ТС260463',
            '77УН519063',
            '77УО128717',
            '39ТН112225',
            '78ТК031884',
            '25НН487669',
            '77ТС613495',
            '77НХ391287',
            '40НМ256423',
            '18МЕ459606',
            '40НА229767',
            '78УС500868',
            '50НА773969',
            '77ТТ748854',
            '78УА076742',
            '78НУ318739',
            '77УО819118',
            '77УС000146',
            '25ОН029167',
            '39ТН936734',
            '63МУ577558',
            '78УК010710',
            '77ТХ415094',
            '78УС982181',
            '78УН434096',
            '40НУ174909',
            '77УС077461',
            '78УО344551',
            '77УО598178',
            '77УН572708',
            '77УМ201875',
            '40ОМ069528',
            '66УО994708',
            '77УН738909',
            '78УС944099',
            '39НЕ857176',
            '77УА965135',
            '78УА052443',
            '63НМ915281',
            '78ТУ621655',
            '39НН651930',
            '78УВ438226',
            '18МТ474729',
            '77УА974000',
            '77УВ600767',
            '78УЕ974480',
            '78НР460564',
            '77ТУ419256',
            '77УА919798',
            '77УК151556',
            '47НЕ450869',
            '78УН371922',
            '78ОМ243043',
            '78УУ300901',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            'TSMEYB21S00610448',
            'LN130-0128818',
            '38:49:924785:832907',

            '',
            Str::random(32),
        ];
    }
}
