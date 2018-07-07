<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegions;
use AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry;

/**
 * Идентификатор - номер ГРЗ.
 *
 * @link <http://internet-law.ru/gosts/gost/7327/#53635> ГОСТ Р 50577-93
 */
class IDEntityGrz extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * Format patterns.
     */
    const FORMAT_PATTERN_1 = 'X000XX77_OR_X000XX777';
    const FORMAT_PATTERN_2 = 'X000XX';
    const FORMAT_PATTERN_3 = 'XX00077';
    const FORMAT_PATTERN_4 = '0000XX77';
    const FORMAT_PATTERN_5 = 'XX000077';
    const FORMAT_PATTERN_6 = 'X000077';
    const FORMAT_PATTERN_7 = '000X77';
    const FORMAT_PATTERN_8 = '0000X77';

    /**
     * Types, declared in "ГОСТ Р 50577-93" (not all).
     */
    const GOST_TYPE_1  = 'TYPE_1'; // тип 1 - Для легковых, грузовых, грузопассажирских ТС и автобусов
    const GOST_TYPE_1A = 'TYPE_1A'; // тип 1А - Для легковых ТС должностных лиц
    const GOST_TYPE_1B = 'TYPE_1B'; // тип 1Б - Для легковых ТС, исп. для перевозки людей на коммерч. основе, автобусов
    const GOST_TYPE_2  = 'TYPE_2'; // тип 2 - Для автомобильных прицепов и полуприцепов
    const GOST_TYPE_3  = 'TYPE_3'; // тип 3 - Для тракторов, самоход. дорожно-строительных машин и иных машин и прицепов
    const GOST_TYPE_4  = 'TYPE_4'; // тип 4 - Для мотоциклов, мотороллеров, мопедов
    const GOST_TYPE_5  = 'TYPE_5'; // тип 5 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов
    const GOST_TYPE_6  = 'TYPE_6'; // тип 6 - Для автомобильных прицепов и полуприцепов
    const GOST_TYPE_7  = 'TYPE_7'; // тип 7 - Для тракторов, самоход. дорожно-строительных машин и иных машин и прицепов
    const GOST_TYPE_8  = 'TYPE_8'; // тип 8 - Для мотоциклов, мотороллеров, мопедов
    const GOST_TYPE_20 = 'TYPE_20'; // тип 20 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов
    const GOST_TYPE_21 = 'TYPE_21'; // тип 21 - Для автомобильных прицепов и полуприцепов
    const GOST_TYPE_22 = 'TYPE_22'; // тип 22 - Для мотоциклов

    /**
     * Pattern and types map.
     */
    const PATTERNS_AND_TYPES_MAP = [
        self::FORMAT_PATTERN_1 => [ // X000XX77_OR_X000XX777
            self::GOST_TYPE_1,
        ],
        self::FORMAT_PATTERN_2 => [ // X000XX
            self::GOST_TYPE_1A,
        ],
        self::FORMAT_PATTERN_3 => [ // XX00077
            self::GOST_TYPE_1B,
            self::GOST_TYPE_2,
        ],
        self::FORMAT_PATTERN_4 => [ // 0000XX77
            self::GOST_TYPE_3,
            self::GOST_TYPE_4,
            self::GOST_TYPE_5,
            self::GOST_TYPE_7,
            self::GOST_TYPE_8,
        ],
        self::FORMAT_PATTERN_5 => [ // XX000077
            self::GOST_TYPE_6,
        ],
        self::FORMAT_PATTERN_6 => [ // X000077
            self::GOST_TYPE_20,
        ],
        self::FORMAT_PATTERN_7 => [ // 000X77
            self::GOST_TYPE_21,
        ],
        self::FORMAT_PATTERN_8 => [ // 0000X77
            self::GOST_TYPE_22,
        ],
    ];

    /**
     * Разрешенные кириллические символы.
     */
    const KYR_CHARS = 'АВЕКМНОРСТУХ';

    /**
     * Латинские аналоги разрешенных кириллических символов.
     *
     * Внимание! Важно соответствие порядка символов со `self::CYR_CHARS`.
     */
    const KYR_ANALOGS = 'ABEKMHOPCTYX';

    /**
     * Get pattern format by passed GOST type.
     *
     * @param string $gost_type
     *
     * @return string|null
     */
    public static function getFormatPatternByGostType($gost_type)
    {
        foreach (self::PATTERNS_AND_TYPES_MAP as $format_pattern => $gost_types) {
            foreach ((array) $gost_types as $iterated_gost_type) {
                if ($iterated_gost_type === $gost_type) {
                    return $format_pattern;
                }
            }
        }
    }

    /**
     * Get GOST types by passed format pattern.
     *
     * @param string $pattern
     *
     * @return string[]|null
     */
    public static function getGostTypesByPattern($pattern)
    {
        if (isset(self::PATTERNS_AND_TYPES_MAP[$pattern])) {
            return self::PATTERNS_AND_TYPES_MAP[$pattern];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_GRZ;
    }

    /**
     * Returns value format pattern.
     *
     * @return string|null
     */
    public function getFormatPattern()
    {
        static $kyr = self::KYR_CHARS;

        switch (true) {
            // X000XX77_OR_X000XX777
            case \preg_match("~^[{$kyr}]{1}\d{3}[{$kyr}]{2}\d{2,3}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_1;

            // X000XX
            case \preg_match("~^[{$kyr}]{1}\d{3}[{$kyr}]{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_2;

            // XX00077
            case \preg_match("~^[{$kyr}]{2}\d{3}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_3;

            // 0000XX77
            case \preg_match("~^\d{4}[{$kyr}]{2}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_4;

            // XX000077
            case \preg_match("~^[{$kyr}]{2}\d{4}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_5;

            // X000077
            case \preg_match("~^[{$kyr}]{1}\d{4}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_6;

            // 000X77
            case \preg_match("~^\d{3}[{$kyr}]{1}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_7;

            // 0000X77
            case \preg_match("~^\d{4}[{$kyr}]{1}\d{2}$~iu", $this->value) === 1:
                return self::FORMAT_PATTERN_8;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(trim((string) $value));

            // Удаляем все символы, кроме разрешенных
            $value = \preg_replace('~[^' . self::KYR_CHARS . self::KYR_ANALOGS . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
            $value = Transliterator::detransliterateLite($value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * Возвращает код региона.
     *
     * @return int|null
     */
    public function getRegionCode()
    {
        $format_pattern = $this->getFormatPattern();

        if ($format_pattern !== null) {
            $matches = [];

            switch ($format_pattern) {
                case self::FORMAT_PATTERN_1: // X000XX77_OR_X000XX777
                    \preg_match('~(?<region_code>\d{2,3})$~D', $this->value, $matches);
                    break;

                case self::FORMAT_PATTERN_2: // X000XX
                    break;

                case self::FORMAT_PATTERN_3: // XX00077
                case self::FORMAT_PATTERN_4: // 0000XX77
                case self::FORMAT_PATTERN_5: // XX000077
                case self::FORMAT_PATTERN_6: // X000077
                case self::FORMAT_PATTERN_7: // 000X77
                case self::FORMAT_PATTERN_8: // 0000X77
                    \preg_match('~(?<region_code>\d{2})$~D', $this->value, $matches);
                    break;
            }

            if (isset($matches['region_code']) && ! empty($region_code = $matches['region_code'])) {
                return (int) $region_code;
            }
        }
    }

    /**
     * Возвращает данные региона по коду региона ГРЗ.
     *
     * @return AutoRegionEntry|null
     */
    public function getRegionData()
    {
        /** @var AutoRegions $reference */
        $reference = resolve(AutoRegions::class);

        return $reference->getByAutoCode($this->getRegionCode());
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function () {
                return $this->validateWithValidatorRule($this->getValue(), 'required|string|grz_code');
            },
            function () {
                // Пропускаем проверку формата, в котором в принципе нет кода региона
                if ($this->getFormatPattern() === self::FORMAT_PATTERN_2) {
                    return true;
                }

                return $this->getRegionData() instanceof AutoRegionEntry;
            },
        ];
    }
}
