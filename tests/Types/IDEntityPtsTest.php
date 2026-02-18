<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityPts;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IDEntityPts::class)]
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

        $this->assertSame('12АВ345678', $entity::normalize('  12-a.b_345-6 78  ')); // Базовая стандартная коррекция - весь комплекс
        $this->assertSame('12АБ345678', $entity::normalize('12аб345678'));          // Приведение к верхнему регистру
        $this->assertSame('12АБ345678', $entity::normalize('12/А_Б-345.678'));      // Удаление разделителей
        $this->assertSame('12ΛΩ345678', $entity::normalize('12ΛΩ345678'));          // Буквы из других алфавитов не удаляются
        $this->assertSame('12АВ345678', $entity::normalize('12AB345678'));          // Замена A,B на А,В
        $this->assertSame('12МН345678', $entity::normalize('12MH345678'));          // Замена M,H на М,Н
        $this->assertSame('12ДА345678', $entity::normalize('12ДА345678'));          // Буква Д не заменяется
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
