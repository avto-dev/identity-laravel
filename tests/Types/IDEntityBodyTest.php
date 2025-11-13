<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Types\IDEntityBody;

/**
 * @covers \AvtoDev\IDEntity\Types\IDEntityBody
 */
class IDEntityBodyTest extends AbstractIDEntityTestCase
{
    /**
     * @var string
     */
    protected $expected_type = IDEntityInterface::ID_TYPE_BODY;

    /**
     * {@inheritdoc}
     */
    public function testNormalize(): void
    {
        $entity = $this->entityFactory();

        $this->assertSame($valid = 'JS3SE102734', $entity::normalize(Str::lower($valid)));
        $this->assertSame($valid, $entity::normalize(" $valid  "));
        $this->assertSame($valid, $entity::normalize('JS3SE–102734'));
        $this->assertSame($valid, $entity::normalize('JS3Sе–102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE--102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE -102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE- 102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE - 102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE -  102734'));
        $this->assertSame($valid, $entity::normalize('JS3SE  -  102734'));
        $this->assertSame($valid, $entity::normalize('JS3#^&@^^SE–102":";%?734'));
        $this->assertSame($valid, $entity::normalize(' JS3SE  102734'));

        $this->assertSame('АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ', $entity::normalize('абвгдеёжзийклмнопрстуфхцчшщъыьэюя'));
        $this->assertSame('A12BC45', $entity::normalize('  А12-ВС.45  '));
        $this->assertSame('A123BC45', $entity::normalize('  А123ВС45  '));
        $this->assertSame('A12BC45', $entity::normalize('А12/В_С-45'));
        $this->assertSame('ΛΩШЖԱԲ', $entity::normalize('ΛΩШЖԱԲ'));
        $this->assertSame('УУУШ111', $entity::normalize('YYYШ111'));
        $this->assertSame('YYYT111', $entity::normalize('УУУТ111'));
        $this->assertSame('УУУШ111', $entity::normalize('  YYY-Ш.111  '));
        $this->assertSame('АВСDЕFGНIJКLМNОРQRSТUVWХУZШ', $entity::normalize('ABCDEFGHIJKLMNOPQRSTUVWXYZШ'));
    }

    /**
     * {@inheritdoc}
     */
    protected function entityFactory(?string $value = null): IDEntityBody
    {
        return new IDEntityBody($value ?? $this->getValidValues()[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValues(): array
    {
        return [
            // Whitespaces allowed
            'NZE141 9134919',
            'GX115 0001807',
            'FN15 002153',
            'S15 017137',
            'ZCT10 0020100',
            'GRX130 6026674',
            'JZX90 6562365',

            '06852511',
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
    }

    /**
     * {@inheritdoc}
     */
    protected function getInvalidValues(): array
    {
        return [
            '068525',
            'TSMEYB21S00610448',
            '38:49:924785:832907',
            '',
            Str::random(32),
        ];
    }
}
