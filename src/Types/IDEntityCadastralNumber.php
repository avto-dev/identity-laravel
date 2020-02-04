<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

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
            $value = \trim((string) \preg_replace('~[^\d:]~u', '', (string) $value), ':');

            // Pad value parts with zeros
            return \sprintf('%02d:%02d:%07d:%d', ...\array_slice(\explode(':', $value), 0, 4));
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Get district code.
     *
     * @return int|null
     */
    public function getDistrictCode(): ?int
    {
        return $this->getNumberPart(0);
    }

    /**
     * Get area code.
     *
     * @return int|null
     */
    public function getAreaCode(): ?int
    {
        return $this->getNumberPart(1);
    }

    /**
     * Get section code.
     *
     * @return int|null
     */
    public function getSectionCode(): ?int
    {
        return $this->getNumberPart(2);
    }

    /**
     * Get parcel code.
     *
     * @return int|null
     */
    public function getParcelCode(): ?int
    {
        return $this->getNumberPart(3);
    }

    /**
     * {@inheritdoc}
     */
    public function getDistrictData(): ?CadastralDistrict
    {
        $district_code = $this->getDistrictCode();

        if (\is_int($district_code)) {
            /** @var CadastralDistricts $districts */
            $districts = static::getContainer()->make(CadastralDistricts::class);

            return $districts->getByCode($district_code);
        }

        return null;
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
                $area_code     = $this->getAreaCode();
                $parcel_code   = $this->getParcelCode();
                $district_data = $this->getDistrictData();

                if ($district_data instanceof CadastralDistrict && \is_int($area_code) && \is_int($parcel_code)) {
                    return $district_data->hasAreaWithCode($area_code) && $parcel_code > 0;
                }
            }
        }

        return false;
    }

    /**
     * @param int $part_number 0, 1, 2 or 3
     *
     * @return int|null
     */
    protected function getNumberPart(int $part_number): ?int
    {
        if (\is_string($this->value)) {
            $parts = \mb_split(':', $this->value, 4);

            return \count($parts) === 4 && isset($parts[$part_number]) && \is_numeric($parts[$part_number])
                ? (int) $parts[$part_number]
                : null;
        }

        return null;
    }
}
