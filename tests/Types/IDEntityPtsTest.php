<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityPts;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityPts
 */
class IDEntityPtsTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_PTS;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = '36ТС369230', $entity::normalize(Str::lower($valid)));
        $this->assertSame($valid, $entity::normalize("  {$valid} "));
        $this->assertSame($valid, $entity::normalize('36tc369230'));
        $this->assertSame($valid, $entity::normalize('36ТС3 $%@*%^$ 69230 '));
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityPts
    {
        return new IDEntityPts($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '78УЕ952328',
            '16 НО 224663',
            '78УС434434',
            '39НЕ248423',
            '40НК602618',
            '02УК922390',
            '47 ТА 183843',
            '77УР781043',
            '61МЕ524040',
            '36ТС369230',
            '66ЕА402408',
            '78 ОН 937380',
            '78НР408206',
            '78УХ169669',
            '47НН307196',
            '78УН113064',
            '78 УА 115947',
            '47НМ321533',
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
            'A111AA177',
            'А123АА77',

            '',
            Str::random(32),
        ];
    }
}
