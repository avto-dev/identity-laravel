<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityPts;

class IDEntityPtsTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityPts
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType(): void
    {
        $this->assertEquals(IDEntity::ID_TYPE_PTS, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid(): void
    {
        $valid = [
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

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $this->assertFalse($this->instance->setValue('TSMEYB21S00610448')->isValid());
        $this->assertFalse($this->instance->setValue('LN130-0128818')->isValid());
        $this->assertFalse($this->instance->setValue('A111AA177')->isValid());
        $this->assertFalse($this->instance->setValue('А123АА77')->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $this->instance::normalize(Str::lower($this->getValidValue())));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $this->instance::normalize(' ' . $this->getValidValue() . ' '));

        // Латиницу заменяет на кириллицу
        $this->assertEquals($valid, $this->instance::normalize('36tc369230'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $this->instance::normalize('36ТС3 $%@*%^$ 69230 '));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityPts::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return '36ТС369230';
    }
}
