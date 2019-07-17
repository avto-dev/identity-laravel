<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityBody;

class IDEntityBodyTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityBody
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    public function testGetType(): void
    {
        $this->assertEquals(IDEntity::ID_TYPE_BODY, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid(): void
    {
        $valid = [
            // С пробелами - считаются валидными
            'NZE141 9134919',
            'GX115 0001807',
            'FN15 002153',
            'S15 017137',
            'ZCT10 0020100',
            'GRX130 6026674',
            'JZX90 6562365',

            '0685251',
            'AT2113041080',
            'NZE141-9134919',
            'GD11231271',
            'GX115-0001807',
            'LS131701075',
            'FN15-002153',
            'S15-017137',
            'NT30305643',
            'AT2120020984',
            'JZX930012010',
            'AT2110076157',
            'EXZ10-0040809',
            'NU3030532899',
            'AE1015080276',
            'NZE1210079301',
            'AT190-4018171',
            'DC5R101807',
            'GA31035490',
            'Z26A5101387',
            'JCG100044285',
            'SF5091230',
            'BJ5W117467',
            'JZX90-0025950',
            'HK30310303',
            'NZE1243011784',
            'SV43-0008767',
            'Z27A0300360',
            'SV320027585',
            'KSP921001169',
            'GX1006108167',
            'ZNE10-0237030',
            'CE105-0005302',
            'GS1510019960',
            'P25W-0506755',
            'ST1900038890',
            'SXA100090135',
            'SGLW301293',
            'ZCT10-0020100',
            'GRX130-6026674',
            'JZX90-6562365',
            'E11012005',
            'PE8W0115960',
            'SXE100010919',
            'LH1681001088',
            'ZNE10-0195718',
            'ST190-4020234',
            'NZE1210273553',
            'ZZT241-0007004',
            'SRF9W401273',
            'ST1830020258',
            'JZX90-6500314',
            'ZZT2410023674',
            'LS151-0002351',
            'SG5-050150',
            'NCP58-0025169',
            'Z10169738',
            'VEW11500278',
            'ZNE10-0126698',
            'CR305023587',
            'HP11724818',
            'CF51100187',
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
        $this->assertEquals($valid, $this->instance::normalize('JS3SE–102734'));

        // С кириллицей
        $this->assertEquals($valid, $this->instance::normalize('JS3Sе–102734'));

        // С двумя тире (должны преобразоваться в одиночное тире)
        $this->assertEquals($valid, $this->instance::normalize('JS3SE--102734'));

        // Встречающиеся идущие подряд тире и пробел - заменяются на одиночный тире
        $this->assertEquals($valid, $this->instance::normalize('JS3SE -102734'));
        $this->assertEquals($valid, $this->instance::normalize('JS3SE- 102734'));
        $this->assertEquals($valid, $this->instance::normalize('JS3SE - 102734'));
        $this->assertEquals($valid, $this->instance::normalize('JS3SE -  102734'));
        $this->assertEquals($valid, $this->instance::normalize('JS3SE  -  102734'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $this->instance::normalize('JS3#^&@^^SE–102":";%?734'));

        // Дублирующиеся пробелы заменяются на одиночные, но замена их на тире НЕ происходит
        $this->assertEquals('JS3SE 102734', $this->instance::normalize(' JS3SE  102734'));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityBody::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return 'JS3SE-102734';
    }
}
