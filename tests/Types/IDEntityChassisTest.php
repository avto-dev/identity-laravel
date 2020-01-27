<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityChassis;
use Illuminate\Support\Str;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityChassis<extended>
 */
class IDEntityChassisTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityChassis
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType(): void
    {
        $this->assertEquals(IDEntity::ID_TYPE_CHASSIS, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid(): void
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
    public function testNormalize(): void
    {
        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $this->instance::normalize(Str::lower($this->getValidValue())));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $this->instance::normalize(' ' . $this->getValidValue() . ' '));

        // Не корректный, длинный тире
        $this->assertEquals($valid, $this->instance::normalize('LA130–0128818'));

        // С кириллицей
        $this->assertEquals($valid, $this->instance::normalize('Lа130-0128818'));

        // С двумя тире (должны преобразоваться в одиночное тире)
        $this->assertEquals($valid, $this->instance::normalize('LA130--0128818'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $this->instance::normalize('LA130-0128№;:?№?*№%$@$%@#818'));

        // С двумя пробелами (должны преобразоваться в одиночное тире)
        $this->assertEquals('LA130 0128818', $this->instance::normalize(' LA130  0128818'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityChassis::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return 'LA130-0128818';
    }
}
