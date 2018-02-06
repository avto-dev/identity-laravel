<?php

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\Types\IDEntityGrz;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;

/**
 * Class IDEntityGrzTest.
 */
class IDEntityGrzTest extends AbstractIDEntityTestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_GRZ, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            // Валидные номера прицепов
            'АА0001177',
            'КК3921090',
            'УК868026',

            // И эти тоже валидные
            '0001АА77',
            '3922АА190',
            '8688АА26',

            'Р392КК190',
            'С731НХ197',
            'Е750МО750',
            'М396СХ46',
            'А137НО89',
            'К898КМ40',
            'О772ТХ197',
            'В771ЕК126',
            'Х894СВ59',
            'Е373ТА73',
            'А777АА77',
            'О704КО190',
            'У868УК26',
            'М824РН78',
            'Т149ОЕ190',
            'Т293ТА178',
            'О476ЕЕ750',
            'В168ТС190',
            'У460УА77',
            'Т258СА77',
            'С475РУ777',
            'Р295ЕЕ178',
            'Х918УУ116',
            'Х116РЕ96',
            'У888ЕК99',
            'О292ОМ77',
            'С989ЕР72',
            'К324МУ750',
            'Е228РХ33',
            'О166РУ174',
            'Н492ТН197',
            'К206МХ32',
            'Р515ЕР19',
            'Н416ТЕ161',
            'У477ЕМ178',
            'Н090РН777',
            'В399УН777',
            'Е986НХ199',
            'М441ЕЕ73',
            'Р842СН777',
            'У914ВХ123',
            'Р181СК161',
            'У371ВН142',
            'У752НХ178',
            'А548ВР750',
            'Н580ХС38',
            'Е427ЕВ190',
            'О386АА40',
            'С061ОУ777',
            'Р295КА102',
            'Р239УЕ777',
            'О461ОВ750',
            'К005АВ77',
            'Е029ХВ70',
            'У956УС777',
            'А528КТ37',
            'Р602ВС86',
            'Р048ОА750',
            'Е251ВК82',
            'Е966РА777',
            'Н340АХ199',
            'Т555СН42',
            'К052ОУ178',
            'М333МВ161',
            'А028ЕУ178',
            'С326ХО199',
            'С976РТ98',
            'Н388ЕУ750',
            'М770РВ161',
            'М828МР02',
            'О377ЕТ750',
            'Е697ХС163',
            'Т612ХХ47',
            'В750КО777',
            'Т085КР123',
            'У700КХ61',
            'К988СС82',
            'Т039КР60',
            'Е751УХ197',
            'С572ЕУ777',
            'Е393МН33',
            'С552ВХ102',
            'Н327СМ777',
            'А284АР777',
            'У606КЕ33',
            'У828ХК47',
            'О590ТТ98',
            'У092СУ98',
            'О168РЕ197',
            'Т900ММ77',
            'Т462КО750',
            'Р012МА34',
            'У188РУ174',
            'В164ОЕ190',
            'О832ВТ31',
            'Е237ОА77',
            'А098АА99',
            'Е105ТУ777',
            'Е683СВ777',
            'М010ЕЕ26',
            'В199ХН199',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid());
        }

        $this->assertFalse($this->instance->setValue('TSMEYB21S00610448')->isValid());
        $this->assertFalse($this->instance->setValue('LN130-0128818')->isValid());
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $instance::normalize(Str::lower($this->getValidValue())));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $instance::normalize(' ' . $this->getValidValue() . ' '));

        // Латиницу заменяет на кириллицу
        $this->assertEquals($valid, $instance::normalize('X123YO96'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $instance::normalize('X123 #$^&&&% YO96 '));
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName()
    {
        return IDEntityGrz::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue()
    {
        return 'Х123УО96';
    }
}
