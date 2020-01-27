<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Tests\Mocks\IDEntityMock;
use AvtoDev\IDEntity\Tests\Mocks\Types\IDEntityCantAutodetectMock;
use AvtoDev\IDEntity\Tests\Traits\InstancesAccessorsTrait;
use AvtoDev\IDEntity\Types\IDEntityBody;
use AvtoDev\IDEntity\Types\IDEntityGrz;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use AvtoDev\IDEntity\Types\IDEntityVin;
use Exception;

/**
 * @covers \AvtoDev\IDEntity\IDEntity<extended>
 */
class IDEntityTest extends AbstractTestCase
{
    use InstancesAccessorsTrait;

    /**
     * @var IDEntityMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->instance = new IDEntityMock;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест констант.
     *
     * @return void
     */
    public function testConstants(): void
    {
        $checks = [
            'AUTODETECT' => IDEntity::ID_TYPE_AUTO,
            'UNKNOWN'    => IDEntity::ID_TYPE_UNKNOWN,
            'VIN'        => IDEntity::ID_TYPE_VIN,
            'GRZ'        => IDEntity::ID_TYPE_GRZ,
            'STS'        => IDEntity::ID_TYPE_STS,
            'PTS'        => IDEntity::ID_TYPE_PTS,
            'BODY'       => IDEntity::ID_TYPE_BODY,
            'CHASSIS'    => IDEntity::ID_TYPE_CHASSIS,
            'DLN'        => IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER,
            'CADNUM'     => IDEntity::ID_TYPE_CADASTRAL_NUMBER,
        ];

        foreach ($checks as $what => $with) {
            $this->assertEquals($what, $with);
        }
    }

    /**
     * Тест реализации необходимых интерфейсов.
     *
     * @return void
     */
    public function testImplements(): void
    {
        foreach ([IDEntityInterface::class] as $class_name) {
            $this->assertInstanceOf($class_name, $this->instance);
        }
    }

    /**
     * Тест метода 'getSupportedTypes'.
     *
     * @return void
     */
    public function testGetSupportedTypes(): void
    {
        $expects = [
            IDEntity::ID_TYPE_VIN,
            IDEntity::ID_TYPE_GRZ,
            IDEntity::ID_TYPE_STS,
            IDEntity::ID_TYPE_PTS,
            IDEntity::ID_TYPE_CHASSIS,
            IDEntity::ID_TYPE_BODY,
            IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER,
            IDEntity::ID_TYPE_CADASTRAL_NUMBER,
        ];

        foreach ($expects as $type) {
            $this->assertContains($type, IDEntity::getSupportedTypes());
        }

        foreach (['foo', null, 123, new Exception] as $type) {
            $this->assertNotContains($type, IDEntity::getSupportedTypes());
        }
    }

    /**
     * Тест метода 'typeIsSupported'.
     *
     * @return void
     */
    public function testTypeIsSupported(): void
    {
        $expects = [
            IDEntity::ID_TYPE_VIN,
            IDEntity::ID_TYPE_GRZ,
            IDEntity::ID_TYPE_STS,
            IDEntity::ID_TYPE_PTS,
            IDEntity::ID_TYPE_CHASSIS,
            IDEntity::ID_TYPE_BODY,
            IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER,
            IDEntity::ID_TYPE_CADASTRAL_NUMBER,
        ];

        foreach ($expects as $type) {
            $this->assertTrue(IDEntity::typeIsSupported($type));
        }

        foreach (['foo', null, 123, new Exception] as $type) {
            $this->assertFalse(IDEntity::typeIsSupported($type));
        }
    }

    /**
     * Проверяем, что возможность автоматического определения сущности задается свойством
     */
    public function testCanAutodetectMethod(): void
    {
        $instance = new IDEntityCantAutodetectMock('');
        $this->assertFalse($instance->canBeAutoDetected());
    }

    /**
     * Тест метода 'make' с передачей конкретного типа.
     *
     * @return void
     */
    public function testMakeWithPassedType(): void
    {
        $instance = IDEntity::make('JF1SJ5LC5DG048667', $type = IDEntity::ID_TYPE_VIN);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('А123АА177', $type = IDEntity::ID_TYPE_GRZ);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('11АА112233', $type = IDEntity::ID_TYPE_STS);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('11АА112233', $type = IDEntity::ID_TYPE_PTS);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('FN15-002153', $type = IDEntity::ID_TYPE_BODY);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('FN15-002153', $type = IDEntity::ID_TYPE_CHASSIS);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('77 16 235662', $type = IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('33:22:011262:526', $type = IDEntity::ID_TYPE_CADASTRAL_NUMBER);
        $this->assertEquals($type, $instance->getType());
    }

    /**
     * Тест метода 'make' с типом "авто-определение типа".
     *
     * @return void
     */
    public function testMakeWithAutoType(): void
    {
        // Все возможные типы ГРЗ для авто-определения
        $values = [
            // М000ММ77 или М000ММ777 (тип 1 - Для легковых, грузовых, грузопассажирских ТС и автобусов)
            'М000ММ77',
            'М000ММ777',

            // М000ММ (тип 1А - Для легковых ТС должностных лиц)
            'М000ММ',
            'О772ТХ',

            // ММ00077 (тип 1Б - Для легковых ТС, исп. для перевозки людей на коммерческой основе, автобусов)
            // ММ00077 (тип 2 - Для автомобильных прицепов и полуприцепов)
            'ММ00077',
            'СХ39646',

            // 0000ММ77 (тип 3 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 4 - Для мотоциклов, мотороллеров, мопедов)
            // 0000ММ77 (тип 5 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            // 0000ММ77 (тип 7 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 8 - Для мотоциклов, мотороллеров, мопедов)
            '0000ММ77',
            '6868УК26',

            // ММ000077 (тип 6 - Для автомобильных прицепов и полуприцепов)
            'ММ000077',
            'УК868626',

            // М000077 (тип 20 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            'М000077',
            'К868626',

            // 000М77 (тип 21 - Для автомобильных прицепов и полуприцепов)
            '000М77',
            '866К26',

            // 0000М77 (тип 22 - Для мотоциклов)
            '0000М77',
            '6868У26',

            // ММ000М77 или ММ000М777 (тип 15 - Транзит, ламинированный)
            'АХ368У77',
            'АХ368У777',
        ];

        foreach ($values as $value) {
            $instance = IDEntity::make($value);
            $this->assertEquals(IDEntity::ID_TYPE_GRZ, $instance->getType());
            $this->assertEquals($value, $instance->getValue());
        }

        $instance = IDEntity::make($value = 'JF1SJ5LC5DG048667');
        $this->assertEquals(IDEntity::ID_TYPE_VIN, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        $this->assertEquals($value, $instance->getValue());

        $instance = IDEntity::make($value = '11АА112233');
        $this->assertEquals(IDEntity::ID_TYPE_STS, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        // Тип "номер ПТС" автоматически отдетектить невозможно, так как правила проверки птс и стс идентичны

        $instance = IDEntity::make($value = 'FN15-002153');
        $this->assertEquals(IDEntity::ID_TYPE_BODY, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        // Тип "номер ШАССИ" автоматически отдетектить невозможно, так как правила проверки шасси и кузова идентичны
        // Тип "номер водительского удостоверения" тоже :(
        // Тип "кадастровый номер" тоже :(
    }

    /**
     * Тест метода 'make' с передачей неизвестного типа.
     *
     * @return void
     */
    public function testMakeWithUnknownType(): void
    {
        $instance = IDEntity::make('foo');
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);

        $instance = IDEntity::make('foo', 'bar');
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);

        $instance = IDEntity::make('foo', IDEntity::ID_TYPE_AUTO);
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);
    }

    /**
     * Проверяем, что тип не может автоматически определяться.
     *
     * @return void
     */
    public function testOneTypeCantAutodetect(): void
    {
        /* @var IDEntity $mock */
        $mock = $this->createIDEntityMock([
            IDEntity::ID_TYPE_VIN            => IDEntityVin::class,
            IDEntity::ID_TYPE_GRZ            => $except = IDEntityGrz::class,
            IDEntityCantAutodetectMock::TYPE => IDEntityCantAutodetectMock::class,
        ]);

        $this->assertInstanceOf($except, $mock::make('А111АА77'));

        /* @var IDEntity $mock */
        $mock = $this->createIDEntityMock([
            IDEntity::ID_TYPE_BODY           => IDEntityBody::class,
            IDEntity::ID_TYPE_VIN            => IDEntityVin::class,
            IDEntityCantAutodetectMock::TYPE => IDEntityCantAutodetectMock::class,
            IDEntity::ID_TYPE_GRZ            => $except = IDEntityGrz::class,
        ]);

        $this->assertInstanceOf($except, $mock::make('А111АА77'));

        /* @var IDEntity $mock */
        $mock = $this->createIDEntityMock([
            IDEntityCantAutodetectMock::TYPE => IDEntityCantAutodetectMock::class,
            IDEntity::ID_TYPE_BODY           => IDEntityBody::class,
            IDEntity::ID_TYPE_VIN            => IDEntityVin::class,
            IDEntity::ID_TYPE_GRZ            => $except = IDEntityGrz::class,
        ]);

        $this->assertInstanceOf($except, $mock::make('А111АА77'));
    }

    /**
     * Тест метода 'is'.
     *
     * @return void
     */
    public function testIsMethod(): void
    {
        $this->assertTrue(IDEntity::is($value = 'JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_STS));

        $this->assertTrue(IDEntity::is($value = 'А123АА177', IDEntity::ID_TYPE_GRZ));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_STS));

        $this->assertTrue(IDEntity::is($value = '11АА112233', IDEntity::ID_TYPE_STS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = '11АА332211', IDEntity::ID_TYPE_PTS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = 'FN15-002153', IDEntity::ID_TYPE_BODY));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = 'FN15-102153', IDEntity::ID_TYPE_CHASSIS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = '33:22:011262:526', IDEntity::ID_TYPE_CADASTRAL_NUMBER));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_DRIVER_LICENSE_NUMBER));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));

        // Тестируем проверку по набору типов
        $this->assertTrue(IDEntity::is('FN15-102153', [IDEntity::ID_TYPE_VIN, IDEntity::ID_TYPE_CHASSIS]));
        $this->assertTrue(IDEntity::is('JF1SJ5LC5DG048667', [IDEntity::ID_TYPE_VIN, IDEntity::ID_TYPE_CHASSIS]));
        $this->assertFalse(IDEntity::is('А123АА177', [IDEntity::ID_TYPE_VIN, IDEntity::ID_TYPE_PTS]));
        $this->assertFalse(IDEntity::is('JF1SJ5LC5DG048667', [IDEntity::ID_TYPE_STS, IDEntity::ID_TYPE_GRZ]));
        $this->assertTrue(IDEntity::is('33:22:011262:526', [
            IDEntity::ID_TYPE_CADASTRAL_NUMBER,
            IDEntity::ID_TYPE_BODY,
        ]));
    }

    /**
     * Test method that returns extended types map.
     *
     * @return void
     */
    public function testExtendedTypesMapMethod(): void
    {
        $extended_map = $this->callMethod($this->instance, 'getExtendedTypesMap');

        $this->assertInternalType('array', $extended_map);

        $this->app->make('config')->set('identity.extended_types_map', $expects = ['foo' => \stdClass::class]);

        $this->assertEquals($expects, $this->callMethod($this->instance, 'getExtendedTypesMap'));
    }

    /**
     * Test map extending with package config.
     *
     * @return void
     */
    public function testExtendsGetTypesMapWithPackageConfig(): void
    {
        $original_map = $this->callMethod($this->instance, 'getTypesMap');

        $this->assertNotEmpty($original_map);

        $this->app->make('config')->set('identity.extended_types_map', $expects = ['foo' => $type = \stdClass::class]);

        $map = $this->callMethod($this->instance, 'getTypesMap');

        $this->assertEquals($type, $map['foo']);

        foreach ($original_map as $expected_type => $expected_class) {
            $this->assertEquals($map[$expected_type], $expected_class);
        }
    }

    /**
     * @param array $types_map
     *
     * @return \Mockery\Mock
     */
    protected function createIDEntityMock($types_map)
    {
        $mock = \Mockery::mock(IDEntity::class)->makePartial();
        $mock->shouldAllowMockingProtectedMethods();

        $mock
            ->shouldReceive('getTypesMap')
            ->andReturn($types_map)
            ->getMock();

        return $mock;
    }
}
