<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegions;
use AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasCadastralNumberInterface
{
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
     * Return parsed fragments of cadastral number.
     *
     * @return CadastralNumberInfo
     */
    public function getNumberInfo(): CadastralNumberInfo
    {
        return CadastralNumberInfo::parse($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function getRegionData(): ?CadastralRegionEntry
    {
        static $regions = null;

        if (! $regions instanceof CadastralRegions) {
            $regions = new CadastralRegions;
        }

        return $regions->getRegionByCode($this->getNumberInfo()->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);
        
        $region_data = $this->getRegionData();

        return $validated
               && $region_data instanceof CadastralRegionEntry
               && $region_data->getDistricts()->hasDistrictCode($this->getNumberInfo()->getDistrictCode());
    }
}
