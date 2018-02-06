<?php

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityChassis;

/**
 * Class IDEntityChassisTest.
 */
class IDEntityChassisTest extends AbstractIDEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_CHASSIS, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            'RN1350007371',
            'LH800023313',
            'TA01W863799',
            'LN130-0128818',
            'SE28M404312',
            'UZJ100-0140027',
            'K971009415',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $this->assertFalse($this->instance->setValue('TSMEYB21S00610448')->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $instance::normalize(Str::lower($this->getValidValue())));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $instance::normalize(' ' . $this->getValidValue() . ' '));

        // Не корректный, длинный тире
        $this->assertEquals($valid, $instance::normalize('LA130–0128818'));

        // С двумя пробелами (должны преобразоваться в одиночное тире)
        $this->assertEquals($valid, $instance::normalize(' LA130  0128818'));

        // С кириллицей
        $this->assertEquals($valid, $instance::normalize('Lа130-0128818'));

        // С двумя тире (должны преобразоваться в одиночное тире)
        $this->assertEquals($valid, $instance::normalize('LA130--0128818'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $instance::normalize('LA130-0128№;:?№?*№%$@$%@#818'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName()
    {
        return IDEntityChassis::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue()
    {
        return 'LA130-0128818';
    }
}
