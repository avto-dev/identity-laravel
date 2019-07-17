<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber<extended>
 */
class IDEntityDriverLicenseNumberTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityDriverLicenseNumber
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType(): void
    {
        $this->assertEquals(IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid(): void
    {
        $valid = [
            $this->getValidValue(),
            '74 14 292010',
            '77 16 235662',
            '66 02 123456',
            '66 АК123456',
            '66BA 123456',
            '66 CY 123456',

            '66 АВ 123456',
            '66 ЕК 123456',
            '66 МН 123456',
            '66 ОР 123456',
            '66 СТ 123456',
            '66 УХ 123456',

            '66 AB 123456',
            '66 EK 123456',
            '66 MH 123456',
            '66 OP 123456',
            '66 CT 123456',
            '66 YX 123456',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $invalid = [
            '74 14 210',
            '7414 210',
            '66 AЯ 123456',
            '66 12 BA3456',
            '66 12 12BA56',
            'BA 12 123456',
            '9814292010',
            '74 14210',
            '74/14210',
            '74\14210',
            '66 12 АВ3456',
            'YX 12 123456',
        ];

        foreach ($invalid as $value) {
            $this->assertFalse($this->instance->setValue($value)->isValid());
        }
    }

    /**
     * Тест метода, возвращающего данные о регионе из номера ВУ.
     *
     * @return void
     */
    public function testGetRegionData(): void
    {
        $expects = [
            '7414292010' => 'RU-CHE',
            '74AA292010' => 'RU-CHE',
            '7256292010' => 'RU-TYU',
            '8666112233' => 'RU-KHM',
        ];

        foreach ($expects as $what => $with) {
            $this->assertEquals($with, $this->instance->setValue((string) $what)->getRegionData()->getIso31662());
        }

        $fails = [
            'А098АА',
            'AA123А098АА',
            'foo bar',
        ];

        foreach ($fails as $fail) {
            $this->assertNull($this->instance->setValue($fail)->getRegionData());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $this->instance::normalize(Str::lower($valid)));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $this->instance::normalize(' ' . $this->getValidValue() . ' '));

        // Латиницу заменяет на кириллицу ("а" и "В" - латинские)
        $this->assertEquals('74АВ142910', $this->instance::normalize('74 aB 142910'));

        // Успешно заменяет множественные разделители - сплитит
        $this->assertEquals($valid, $this->instance::normalize('74 14  292010'));
        $this->assertEquals($valid, $this->instance::normalize('74  14 292010'));
        $this->assertEquals($valid, $this->instance::normalize('74  14  292010'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $this->instance::normalize('7&#%4 14 2^(**^%920({]10 Ъ'));

        $asserts = [
            '66АВ123456' => ['66 АВ 123456', '66 AB 123456'],
            '66ЕК123456' => ['66 ЕК 123456', '66 EK 123456'],
            '66МН123456' => ['66 МН 123456', '66 MH 123456'],
            '66ОР123456' => ['66 ОР 123456', '66 OP 123456'],
            '66СТ123456' => ['66 СТ 123456', '66 CT 123456'],
            '66УХ123456' => ['66 УХ 123456', '66 YX 123456'],
        ];

        foreach ($asserts as $with => $what) {
            foreach ($what as $item) {
                $this->assertEquals($with, $this->instance::normalize($item));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityDriverLicenseNumber::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return '7414292010';
    }
}
