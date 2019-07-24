<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasDistrictDataInterface
{
    /**
     * @var string|int Код субъекта
     */
    protected $region_code;

    /**
     * @var string|int Номер района
     */
    protected $district_code;

    /**
     * @var string|int Номер квартала
     */
    protected $quarter_code;

    /**
     * @var string|int Номер участка
     */
    protected $area_code;

    /**
     * {@inheritdoc}
     */
    public function setValue(string $value, bool $make_normalization = true)
    {
        parent::setValue($value, $make_normalization);

        if ($make_normalization) {
            $this->splitCadastralNumber();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_CADASTRAL_NUMBER;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Удаляем все символы, кроме разрешенных (цифры и знак ":")
            return (string) \preg_replace('~[^\d\:]~u', '', (string) $value);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Split cadastral number to fragments.
     *
     * @return void
     */
    public function splitCadastralNumber(): void
    {
        $codes               = \mb_split(':', $this->value ?? '');
        $this->region_code   = $codes[0] ?? null;
        $this->district_code = $codes[1] ?? null;
        $this->quarter_code  = $codes[2] ?? null;
        $this->area_code     = $codes[3] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrictData(): ?CadastralDistrictEntry
    {
        static $districts = null;

        if (! $districts instanceof CadastralRegions) {
            $districts = new CadastralRegions;
        }

        if (($region = $districts->getRegionByCode($this->region_code)) instanceof CadastralRegionEntry) {
            return $region->getDistricts()->getDistrictByCode($this->district_code);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        return $validated && $this->getDistrictData() instanceof CadastralDistrictEntry;
    }
}
