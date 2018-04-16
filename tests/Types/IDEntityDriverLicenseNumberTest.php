<?php

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber;

/**
 * Class IDEntityDriverLicenseNumberTest.
 */
class IDEntityDriverLicenseNumberTest extends AbstractIDEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            $this->getValidValue(),
            '74 14 292010',
            '77 16 235662',
            '190195-0000',
            '23506/04/2469',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $this->assertFalse($this->instance->setValue('74 14 210')->isValid());
        $this->assertFalse($this->instance->setValue('7414 210')->isValid());
        $this->assertFalse($this->instance->setValue('74 14210')->isValid());
        $this->assertFalse($this->instance->setValue('74/14210')->isValid());
        $this->assertFalse($this->instance->setValue('74\14210')->isValid());
    }

    /**
     * Тест метода, возвращающего данные о регионе из номера ВУ.
     *
     * @return void
     */
    public function testGetRegionData()
    {
        $expects = [
            '7414292010' => 'RU-CHE',
            '74AA292010' => 'RU-CHE',
            '7256292010' => 'RU-TYU',
            '8666112233' => 'RU-KHM',
        ];

        /** @var IDEntityDriverLicenseNumber $instance */
        $instance = $this->instance;

        foreach ($expects as $what => $with) {
            $this->assertEquals($with, $instance->setValue($what)->getRegionData()->getIso31662());
        }

        $fails = [
            'А098АА',
            'AA123А098АА',
            'foo bar',
        ];

        foreach ($fails as $fail) {
            $this->assertNull($instance->setValue($fail)->getRegionData());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $instance::normalize(Str::lower($valid)));

        // Пробелы и другие разделители - успешно триммит
        $this->assertEquals($valid, $instance::normalize(' ' . $this->getValidValue() . ' '));
        $this->assertEquals($valid, $instance::normalize('\\' . $this->getValidValue() . '-'));
        $this->assertEquals($valid, $instance::normalize('/' . $this->getValidValue() . "\t"));

        // Кириллицу заменяет на латиницу ("а" и "В" - кириллическая)
        $this->assertEquals('74AB142910', $instance::normalize('74 аВ 142910'));

        // Успешно заменяет множественные разделители - сплитит
        $this->assertEquals($valid, $instance::normalize('74 14  292010'));
        $this->assertEquals($valid, $instance::normalize('74  14 292010'));
        $this->assertEquals($valid, $instance::normalize('74  14  292010'));
        $this->assertEquals('23506042469', $instance::normalize('23506//04/2469'));
        $this->assertEquals('23506042469', $instance::normalize(' 23506/04//////2469'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $instance::normalize('7&#%4 14 2^(**^%920({]10 Ъ'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName()
    {
        return IDEntityDriverLicenseNumber::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue()
    {
        return '7414292010';
    }
}
