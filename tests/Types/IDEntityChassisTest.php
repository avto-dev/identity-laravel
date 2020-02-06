<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityChassis;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityChassis
 */
class IDEntityChassisTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_CHASSIS;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = 'LA130-0128818', $entity::normalize(Str::lower($valid)));
        $this->assertSame($valid, $entity::normalize(" {$valid}  "));
        $this->assertSame($valid, $entity::normalize('LA130–0128818'));
        $this->assertSame($valid, $entity::normalize('Lа130-0128818'));
        $this->assertSame($valid, $entity::normalize('LA130--0128818'));
        $this->assertSame($valid, $entity::normalize('LA130-0128№;:?№?*№%$@$%@#818'));

        $this->assertSame('LA130 0128818', $entity::normalize(' LA130  0128818'));
    }

    /**
     * {@inheritDoc}
     */
    protected function entityFactory(?string $value = null): IDEntityChassis
    {
        return new IDEntityChassis($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            'RN1350007371',
            'LH800023313',
            'TA01W863799',
            'LN130-0128818',
            'SE28M404312',
            'UZJ100-0140027',
            'K971009415',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            'TSMEYB21S00610448',
            '38:49:924785:832907',
            Str::random(32),
        ];
    }
}
