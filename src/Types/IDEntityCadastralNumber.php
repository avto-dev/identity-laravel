<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\CadastralNumberInfo;
use AvtoDev\StaticReferences\References\CadastralDistricts;
use AvtoDev\StaticReferences\References\Entities\CadastralDistrict;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasCadastralNumberInterface
{
    /**
     * {@inheritdoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value);
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
            // Remove all chars except allowed (numbers and ':')
            $value = (string) \preg_replace('~[^\d:]~u', '', (string) $value);
            $parts = \explode(':', $value);

            return \sprintf('%02d:%02d:%07d:%d',
                (int) $parts[0],
                (int) $parts[1],
                (int) $parts[2],
                (int) $parts[3]
            );
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
    public function getDistrictData(): ?CadastralDistrict
    {
        /** @var CadastralDistricts $districts */
        $districts = static::getContainer()->make(CadastralDistricts::class);

        return $districts->getByCode($this->getNumberInfo()->getDistrictCode());
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        if (\is_string($this->value)) {
            /** @var CadastralNumberValidatorExtension $validator */
            $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

            if ($validator->passes('', $this->value)) {
                $district_data = $this->getDistrictData();

                return $district_data instanceof CadastralDistrict
                       && $district_data->hasAreaWithCode($this->getNumberInfo()->getAreaCode())
                       && $this->getNumberInfo()->getParcelNumber() > 0;
            }
        }

        return false;
    }
}
