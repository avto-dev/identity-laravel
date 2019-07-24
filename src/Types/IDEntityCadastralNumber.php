<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;
use Exception;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralDistrictEntry;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasDistrictDataInterface
{
    /**
     * @var CadastralNumberInfo
     */
    protected $cadastral_number;

    /**
     * {@inheritdoc}
     */
    public function setValue(string $value, bool $make_normalization = true)
    {
        parent::setValue($value, $make_normalization);

        $this->cadastral_number = CadastralNumberInfo::parse($value);

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
     * {@inheritdoc}
     */
    public function getDistrictData(): ?CadastralDistrictEntry
    {
        static $districts = null;

        if (! $districts instanceof CadastralRegions) {
            $districts = new CadastralRegions;
        }

        if (($region = $districts->getRegionByCode($this->cadastral_number->getRegionCode()))
            instanceof CadastralRegionEntry
        ) {
            return $region->getDistricts()->getDistrictByCode($this->cadastral_number->getDistrictCode());
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
