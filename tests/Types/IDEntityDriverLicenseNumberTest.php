<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber
 */
class IDEntityDriverLicenseNumberTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_DRIVER_LICENSE_NUMBER;

    /**
     * @return void
     */
    public function testGetRegionData(): void
    {
        $entity = $this->entityFactory();

        $expects = [
            '7414292010' => 'RU-CHE',
            '74AA292010' => 'RU-CHE',
            '7256292010' => 'RU-TYU',
            '8666112233' => 'RU-KHM',
        ];

        foreach ($expects as $what => $with) {
            $this->assertSame($with, $entity->setValue((string) $what)->getRegionData()->getIso31662Code());
        }

        foreach (['А098АА', 'AA123А098АА', 'foo bar'] as $fail) {
            $this->assertNull($entity->setValue($fail)->getRegionData());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = '7414292010', $entity::normalize(Str::lower($valid)));
        $this->assertSame($valid, $entity::normalize("  {$valid} "));
        $this->assertSame('74АВ142910', $entity::normalize('74 aB 142910'));
        $this->assertSame($valid, $entity::normalize('74 14  292010'));
        $this->assertSame($valid, $entity::normalize('74  14 292010'));
        $this->assertSame($valid, $entity::normalize('74  14  292010'));
        $this->assertSame($valid, $entity::normalize('7&#%4 14 2^(**^%920({]10 Ъ'));

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
                $this->assertSame($with, $entity::normalize($item));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityDriverLicenseNumber
    {
        return new IDEntityDriverLicenseNumber($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            '7414292010',

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
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            '74 14 210',
            '7414 210',
            '66 AЯ 123456',
            '66 12 BA3456',
            '66 12 12BA56',
            'BA 12 123456',
            '9814292010',
            '74 14210',
            '74/14210',
            '74\\14210',
            '66 12 АВ3456',
            'YX 12 123456',
            '38:49:924785:832907',

            Str::random(32),
        ];
    }
}
