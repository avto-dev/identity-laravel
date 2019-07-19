<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

class IDEntityCadastralNumber extends AbstractTypedIDEntity implements HasRegionDataInterface
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
     * Возвращает код региона.
     *
     * @return int|null
     */
    public function getRegionCode(): ?int
    {
        if (\preg_match('~^(?<region_code>[0-9]{2})~', (string) $this->value, $matches)) {
            if (isset($matches['region_code']) && ! \trim($region_code = $matches['region_code']) !== '') {
                return (int) $region_code;
            }
        }

        return null;
    }

    /**
     * Возвращает данные региона по коду региона кадастрового номера (первые 2 цифры).
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData(): ?AutoRegionEntry
    {
        static $regions = null;

        if (! $regions instanceof AutoRegions) {
            $regions = new AutoRegions;
        }

        return $regions->getByRegionCode($this->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        $region_valid = false;

        // Пропускаем проверку формата, в котором в принципе нет кода региона
        if ($this->getRegionData() instanceof AutoRegionEntry) {
            $region_valid = true;
        }

        return $validated && $region_valid;
    }
}
