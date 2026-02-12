<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\Types;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\ServiceProvider;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Tests\Mocks\TypedIDEntityMock;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * @covers \AvtoDev\IDEntity\IDEntity
 */
class IDEntityTest extends AbstractTestCase
{
    /**
     * @var ?ConfigRepository
     */
    protected ?ConfigRepository $config;

    /**
     * @var array<string, class-string>
     */
    protected array $basic_types = [
        IDEntityInterface::ID_TYPE_VIN                   => Types\IDEntityVin::class,
        IDEntityInterface::ID_TYPE_GRZ                   => Types\IDEntityGrz::class,
        IDEntityInterface::ID_TYPE_STS                   => Types\IDEntitySts::class,
        IDEntityInterface::ID_TYPE_PTS                   => Types\IDEntityPts::class,
        IDEntityInterface::ID_TYPE_BODY                  => Types\IDEntityBody::class,
        IDEntityInterface::ID_TYPE_CHASSIS               => Types\IDEntityChassis::class,
        IDEntityInterface::ID_TYPE_DRIVER_LICENSE_NUMBER => Types\IDEntityDriverLicenseNumber::class,
        IDEntityInterface::ID_TYPE_CADASTRAL_NUMBER      => Types\IDEntityCadastralNumber::class,
        IDEntityInterface::ID_TYPE_EPTS                  => Types\IDEntityEpts::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->app->make(ConfigRepository::class);
    }

    /**
     * @return void
     */
    public function testImplementedInterfaces(): void
    {
        $this->assertContains(IDEntityInterface::class, \class_implements(IDEntity::class));
    }

    /**
     * @return void
     */
    public function testConstructorAccess(): void
    {
        $reflection_class = (new \ReflectionClass(IDEntity::class));

        $this->assertSame(
            0,
            $reflection_class->getMethod('__construct')->getModifiers() & \ReflectionMethod::IS_PUBLIC,
            'Constructor must have non-public access'
        );
    }

    /**
     * @return void
     */
    public function testGetSupportedTypes(): void
    {
        $this->config->set(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', [
            $new_type = Str::random() => TypedIDEntityMock::class,
        ]);

        $actual     = IDEntity::getSupportedTypes();
        $expected   = \array_keys($this->basic_types);
        $expected[] = $new_type; // Push new type into expected types list

        $this->assertSameSize($expected, $actual);

        foreach ($expected as $type) {
            $this->assertContains($type, $actual);
        }
    }

    /**
     * @return void
     */
    public function testTypeIsSupported(): void
    {
        $this->config->set(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', [
            $new_type = Str::random() => TypedIDEntityMock::class,
        ]);

        foreach (\array_keys($this->basic_types) as $type) {
            $this->assertTrue(IDEntity::typeIsSupported($type));
        }

        $this->assertTrue(IDEntity::typeIsSupported($new_type));

        $this->assertFalse(IDEntity::typeIsSupported(Str::random()));
        $this->assertFalse(IDEntity::typeIsSupported(''));
    }

    /**
     * @return void
     */
    public function testMakeDefaultType(): void
    {
        $reflection_class = (new \ReflectionClass(IDEntity::class));

        $this->assertSame(
            IDEntityInterface::ID_TYPE_AUTO,
            $reflection_class->getMethod('make')->getParameters()[1]->getDefaultValue()
        );
    }

    /**
     * @return void
     */
    public function testMakeWithAutoUndetectableValue(): void
    {
        $this->assertInstanceOf(Types\IDEntityUnknown::class, IDEntity::make(Str::random(5)));
    }

    /**
     * @return void
     */
    public function testMakeWithUnknownTypePassing(): void
    {
        // Make sure that value can be auto-detected
        $this->assertNotInstanceOf(Types\IDEntityUnknown::class, IDEntity::make($value = 'А123АА177'));

        $this->assertInstanceOf(Types\IDEntityUnknown::class, IDEntity::make($value, Str::random()));
    }

    /**
     * @return void
     */
    public function testMakeWithTypePassing(): void
    {
        $this->assertInstanceOf(
            Types\IDEntityVin::class,
            IDEntity::make('JF1SJ5LC5DG048667', IDEntityInterface::ID_TYPE_VIN)
        );

        $this->assertInstanceOf(
            Types\IDEntityGrz::class,
            IDEntity::make('А123АА177', IDEntityInterface::ID_TYPE_GRZ)
        );

        $this->assertInstanceOf(
            Types\IDEntitySts::class,
            IDEntity::make('11АА112233', IDEntityInterface::ID_TYPE_STS)
        );

        $this->assertInstanceOf(
            Types\IDEntityPts::class,
            IDEntity::make('11АА112233', IDEntityInterface::ID_TYPE_PTS)
        );

        $this->assertInstanceOf(
            Types\IDEntityBody::class,
            IDEntity::make('FN15-002153', IDEntityInterface::ID_TYPE_BODY)
        );

        $this->assertInstanceOf(
            Types\IDEntityChassis::class,
            IDEntity::make('FN15-002153', IDEntityInterface::ID_TYPE_CHASSIS)
        );

        $this->assertInstanceOf(
            Types\IDEntityDriverLicenseNumber::class,
            IDEntity::make('77 16 235662', IDEntityInterface::ID_TYPE_DRIVER_LICENSE_NUMBER)
        );

        $this->assertInstanceOf(
            Types\IDEntityCadastralNumber::class,
            IDEntity::make('33:22:011262:526', IDEntityInterface::ID_TYPE_CADASTRAL_NUMBER)
        );

        $this->assertInstanceOf(
            Types\IDEntityEpts::class,
            IDEntity::make('123456789012345', IDEntityInterface::ID_TYPE_EPTS)
        );
    }

    /**
     * @return void
     */
    public function testMakeVinAutoDetection(): void
    {
        $values = [
            '5UXFA13585LY08847',
            'WBAFW51040C245397',
            'Z94CB41ABDR105897',
            'XUUNF486J90008440',
            'Z94CT41DBFR411079',
            'X96ERB6X180001283',
            'KMHDN45D22U348878',
            'KMHE341CBFA025224',
            'XWB3L32EDCA218918',
            'WBASP81010C353098',
            'VF1UDC3K640850971',
            'W0LPE6DJ1BG069892',
        ];

        foreach ($values as $value) {
            $this->assertInstanceOf(Types\IDEntityVin::class, IDEntity::make($value));
        }
    }

    /**
     * @return void
     */
    public function testMakeGrzAutoDetection(): void
    {
        $values = [
            'М000ММ77', 'М000ММ777',
            'М000ММ', 'О772ТХ',
            'ММ00077', 'СХ39646',
            '0000ММ77', '6868УК26',
            'ММ000077', 'УК868626',
            'М000077', 'К868626',
            '000М77', '866К26',
            '0000М77', '6868У26',
            'АХ368У77', 'АХ368У777',
        ];

        foreach ($values as $value) {
            $this->assertInstanceOf(Types\IDEntityGrz::class, IDEntity::make($value));
        }
    }

    /**
     * @return void
     */
    public function testMakeStsAutoDetection(): void
    {
        $values = [
            '11АА112233', '78УЕ952328', '16НО224663', '78УС434434', '39НЕ248423',
            '40 НК 602618', '02 УК 922390', '47 ТА 183843', '77 УР 781043', '61 МЕ 524040',
        ];

        foreach ($values as $value) {
            $this->assertInstanceOf(Types\IDEntitySts::class, IDEntity::make($value));
        }
    }

    /**
     * @return void
     */
    public function testMakeBodyAutoDetection(): void
    {
        $values = [
            '06852512',
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
        ];

        foreach ($values as $value) {
            $this->assertInstanceOf(Types\IDEntityBody::class, IDEntity::make($value));
        }
    }

    /**
     * @return void
     */
    public function testMakeCadastralNumberAutoDetection(): void
    {
        $values = [
            '02:04:000221:2',
            '09:04:0134001:102',
            '10:01:0030104:691',
            '11:05:0105013:390',
            '13:23:1203002:556',
            '14:36:102034:2256',
            '15:09:0020708:133',
            '16:18:140401:1627',
            '17:10:0601038:174',
        ];

        foreach ($values as $value) {
            $this->assertInstanceOf(Types\IDEntityCadastralNumber::class, IDEntity::make($value));
        }
    }

    /**
     * @return void
     */
    public function testMakeTypesExtendingUsingConfig(): void
    {
        TypedIDEntityMock::$type = $type = Str::random();

        $this->config->set(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', [
            $type => TypedIDEntityMock::class,
        ]);

        $this->assertInstanceOf(TypedIDEntityMock::class, $identity = IDEntity::make(Str::random(), $type));
        $this->assertSame($type, $identity->getType());
    }

    /**
     * @return void
     */
    public function testCustomTypeDetection(): void
    {
        TypedIDEntityMock::$type       = $type = Str::random();
        TypedIDEntityMock::$detectable = true;
        TypedIDEntityMock::$is_valid   = true;

        $this->config->set(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', [
            $type => TypedIDEntityMock::class,
        ]);

        $this->assertInstanceOf(TypedIDEntityMock::class, IDEntity::make(Str::random(64)));
    }

    /**
     * @return void
     */
    public function testCustomTypeDetectionSkipping(): void
    {
        TypedIDEntityMock::$type       = $type = Str::random();
        TypedIDEntityMock::$detectable = false;
        TypedIDEntityMock::$is_valid   = true;

        $this->config->set(ServiceProvider::getConfigRootKeyName() . '.extended_types_map', [
            $type => TypedIDEntityMock::class,
        ]);

        $this->assertInstanceOf(Types\IDEntityUnknown::class, IDEntity::make(Str::random(64)));
    }

    /**
     * @return void
     */
    public function testIsForVinType(): void
    {
        $this->assertTrue(IDEntity::is('JF1SJ5LC5DG048667', $type = IDEntityInterface::ID_TYPE_VIN));

        $wrong = ['А123АА177', '11АА112233', 'FN15-002153', '77 УР 781043', '33:22:011262:526', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForGrzType(): void
    {
        $this->assertTrue(IDEntity::is('А123АА177', $type = IDEntityInterface::ID_TYPE_GRZ));

        $wrong = ['JF1SJ5LC5DG048667', '11АА112233', 'FN15-002153', '77 УР 781043', '33:22:011262:526', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForCadastralNumberType(): void
    {
        $this->assertTrue(IDEntity::is('33:22:011262:526', $type = IDEntityInterface::ID_TYPE_CADASTRAL_NUMBER));

        $wrong = ['А123АА177', '11АА112233', 'FN15-002153', '77 УР 781043', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForStsType(): void
    {
        $this->assertTrue(IDEntity::is('11АА112233', $type = IDEntityInterface::ID_TYPE_STS));
        $this->assertTrue(IDEntity::is('77 УР 781043', $type));

        $wrong = ['А123АА177', 'JF1SJ5LC5DG048667', 'FN15-002153', '33:22:011262:526', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForPtsType(): void
    {
        $this->assertTrue(IDEntity::is('78УЕ952328', $type = IDEntityInterface::ID_TYPE_PTS));
        $this->assertTrue(IDEntity::is('16 НО 224663', $type));

        $wrong = ['А123АА177', 'JF1SJ5LC5DG048667', 'FN15-002153', '33:22:011262:526', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForBodyType(): void
    {
        $this->assertTrue(IDEntity::is('NZE141-9134919', $type = IDEntityInterface::ID_TYPE_BODY));
        $this->assertTrue(IDEntity::is('SGLW301293', $type));
        $this->assertTrue(IDEntity::is('77 УР 781043', $type));

        $wrong = ['JF1SJ5LC5DG048667', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForChassisType(): void
    {
        $this->assertTrue(IDEntity::is('RN1350007371', $type = IDEntityInterface::ID_TYPE_CHASSIS));
        $this->assertTrue(IDEntity::is('LN130-0128818', $type));

        $wrong = ['JF1SJ5LC5DG048667', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }

    /**
     * @return void
     */
    public function testIsForDlnType(): void
    {
        $this->assertTrue(IDEntity::is('66 02 123456', $type = IDEntityInterface::ID_TYPE_DRIVER_LICENSE_NUMBER));
        $this->assertTrue(IDEntity::is('66BA 123456', $type));

        $wrong = ['А123АА177', 'JF1SJ5LC5DG048667', 'FN15-002153', '33:22:011262:526', Str::random()];

        foreach ($wrong as $item) {
            $this->assertFalse(IDEntity::is($item, $type), "[{$item}] must be not [{$type}] type");
        }
    }
}
