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
            '50:01:654123:229',
            '51:01:9876563:1000',
            '52:01:100500:1000',
            '53:00:456789:736',
            '44:41:1234567:81545',
            '38:41:123456:102360',
            '29:28:2414138:3',
            '11:32:6107179:2',
            '13:01:167778:560',
            '27:46:729107:5843',
            '29:95:3773025:02',
            '15:17:7022097:973',
            '75:27:2374845:29545',
            '83:38:5396372:0778',
            '62:98:437369:87',
            '49:45:2136040:347',
            '73:74:2025396:33',
            '13:21:519846:854',
            '87:50:226635:1',
            '86:77:031622:8428',
            '83:27:427934:1',
            '74:81:1535682:971',
            '21:27:9584113:671510',
            '72:51:372923:8028',
            '99:96:2700420:365298',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid(), $value);
        }

        $invalid = [
            '359:924:190:795',
            '5:01:4286525/047215',
            '0:22:4357409:744560',
            '9:79:822:078276',
            '061:212:111:597',
            '2:42:9790497/271694',
            '923:045:888:214',
            '0:65:260:685116',
            '266:320:100:338',
            'foo bar',
            '{cadastal number}',
            '12:ca123das22tal;number=',
            '7:42:2550168/906215',
            '9:69:3759000:798065',
            '5:66:5951772:833946',
            '2:24:3483960/790543',
            '6:15:5787963:669678',
            '[array12:11]:34',
            '7:41:944:150150',
            '66/41/0000000/38949',
            '66;41;0000000;38949',
            '66\'41\'0000000\'38949',
            '66"41"0000000"38949',
            '66.41.0000000.38949',
            '66,41,0000000,38949',
            '66-41-0000000-38949',
            '66=41=0000000=38949',
            '66*41*0000000*38949',

            // C несуществующим регионом
            '82:77:031622:8428',
            '84:27:427934:1',
            '85:81:1535682:971',
            '88:27:9584113:671510',
            '93:51:372923:8028',
            '94:96:2700420:365298',
            '95:12:234567:1',
            '80:72:874527:985',
            '81:72:874527:985',
            '96:75:7345257:8',
            '97:84:996770:5193',
            '98:10:924785:832907',

        ];

        foreach ($invalid as $value) {
            $this->assertFalse($this->instance->setValue($value)->isValid());
        }
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
        $this->assertFalse($instance->setValue(':D61:41:123456:102360')->isValid());
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
