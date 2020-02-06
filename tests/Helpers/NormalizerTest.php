<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\IDEntity\Helpers\Normalizer
 */
class NormalizerTest extends AbstractTestCase
{
    /**
     * Тест метода нормализации символа тире.
     *
     * @return void
     */
    public function testNormalizeDashChar(): void
    {
        foreach ([
            '–' /* Unicode U+2013 */,
            '—' /* Unicode U+2014 */,
            '‒' /* Unicode U+2012 */,
            '―' /* Unicode U+2015 */,
            '-',
        ] as $dash) {
            $this->assertSame('-', Normalizer::normalizeDashChar($dash));
        }

        $this->assertSame('', Normalizer::normalizeDashChar(''));
    }
}
