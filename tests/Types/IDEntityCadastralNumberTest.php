<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityCadastralNumber;

/**
 * Class IDEntityCadastralNumberTest.
 */
class IDEntityCadastralNumberTest extends AbstractIDEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_CADASTRAL_NUMBER, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            '77:01:654123:229',
            '77:01:9876563:1000',
            '77:01:100500:1000',
            '02:00:456789:736',
            '66:41:1234567:81545',
            '66:41:123456:102360',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $this->assertFalse($this->instance->setValue('66:41:123456102360')->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        $valid = $this->getValidValue();

        // Пробелы с двум сторон
        $this->assertEquals($valid, $instance::normalize(' ' . $valid . ' '));

        // Запрещенные символы
        $this->assertEquals($valid, $instance::normalize('6+6:/4$1:;0(1%^0)&5*-0!0@1#:=?3'));

        // С буквами
        $this->assertEquals($valid, $instance::normalize('Start6Шесть6:4One1:01ZeRO05001:ThrEE3'));
        //Первый символ не цифра
        $this->assertTrue($instance->setValue(':x:y:ц:61:41:123456:102360')->isValid());
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName(): string
    {
        return IDEntityCadastralNumber::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue(): string
    {
        return '66:41:0105001:3';
    }
}
