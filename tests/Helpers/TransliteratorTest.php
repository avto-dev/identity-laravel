<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Helpers;

use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\IDEntity\Tests\AbstractTestCase;

/**
 * @covers \AvtoDev\IDEntity\Helpers\Transliterator
 */
class TransliteratorTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testTransliterate(): void
    {
        $this->assertSame(
            'АВЕКМНОРСТУХ авекмнорстух',
            Transliterator::detransliterateString('ABEKMHOPCTYX abekmhopctyx')
        );
    }
}
