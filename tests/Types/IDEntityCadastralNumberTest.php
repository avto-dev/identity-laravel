<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityCadastralNumber;

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
            '29:28:2414138:3',
            '31:32:6107179:2',
            '13:01:167778:560',
            '27:46:729107:5843',
            '29:95:3773025:02',
            '15:17:7022097:973',
            '75:27:2374845:29545',
            '83:38:5396372:0778',
            '90:98:437369:87',
            '49:45:2136040:347',
            '73:74:2025396:33',
            '13:21:519846:854',
            '88:50:226635:1',
            '86:77:031622:8428',
            '84:27:427934:1',
            '87:32:1246950:4661',
            '74:81:1535682:971',
            '21:27:9584113:671510',
            '72:51:372923:8028',
            '79:96:2700420:365298',

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
