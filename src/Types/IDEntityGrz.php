<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use Illuminate\Support\Str;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\StaticReferences\References\SubjectCodes;
use AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo;
use AvtoDev\ExtendedLaravelValidator\Extensions\GrzCodeValidatorExtension;

/**
 * @link <http://internet-law.ru/gosts/gost/7327/#53635> ГОСТ Р 50577-93
 */
class IDEntityGrz extends AbstractTypedIDEntity implements HasRegionDataInterface
{
    /**
     * {@inheritDoc}
     *
     * @return static
     */
    final public static function make(string $value, ?string $type = null): self
    {
        return new static($value);
    }

    /**
     * Format patterns.
     */
    public const FORMAT_PATTERN_1 = 'X000XX77_OR_X000XX777';

    public const FORMAT_PATTERN_2 = 'X000XX';

    public const FORMAT_PATTERN_3 = 'XX00077';

    public const FORMAT_PATTERN_4 = '0000XX77';

    public const FORMAT_PATTERN_5 = 'XX000077';

    public const FORMAT_PATTERN_6 = 'X000077';

    public const FORMAT_PATTERN_7 = '000X77';

    public const FORMAT_PATTERN_8 = '0000X77';

    public const FORMAT_PATTERN_9 = 'XX000X77_OR_XX000X777';

    /**
     * Types, declared in "ГОСТ Р 50577-93" (not all).
     */
    public const GOST_TYPE_1  = 'TYPE_1'; // тип 1 - Для легковых, грузовых, грузопассажирских ТС и автобусов

    public const GOST_TYPE_1A = 'TYPE_1A'; // тип 1А - Для легковых ТС должностных лиц

    public const GOST_TYPE_1B = 'TYPE_1B'; // тип 1Б - Для легковых ТС, исп. для перевозки людей на коммерч. основе, автобусов

    public const GOST_TYPE_2  = 'TYPE_2'; // тип 2 - Для автомобильных прицепов и полуприцепов

    public const GOST_TYPE_3  = 'TYPE_3'; // тип 3 - Для тракторов, самоход. дорожно-строительных машин и иных машин и прицепов

    public const GOST_TYPE_4  = 'TYPE_4'; // тип 4 - Для мотоциклов, мотороллеров, мопедов

    public const GOST_TYPE_5  = 'TYPE_5'; // тип 5 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов

    public const GOST_TYPE_6  = 'TYPE_6'; // тип 6 - Для автомобильных прицепов и полуприцепов

    public const GOST_TYPE_7  = 'TYPE_7'; // тип 7 - Для тракторов, самоход. дорожно-строительных машин и иных машин и прицепов

    public const GOST_TYPE_8  = 'TYPE_8'; // тип 8 - Для мотоциклов, мотороллеров, мопедов

    public const GOST_TYPE_15 = 'TYPE_15'; // тип 15 - Для легковых, грузовых, грузопассажирских автомобилей, автобусов, прицепов и полуприцепов (Транзит, ламинированный)

    public const GOST_TYPE_20 = 'TYPE_20'; // тип 20 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов

    public const GOST_TYPE_21 = 'TYPE_21'; // тип 21 - Для автомобильных прицепов и полуприцепов

    public const GOST_TYPE_22 = 'TYPE_22'; // тип 22 - Для мотоциклов

    /**
     * Разрешенные кириллические символы.
     */
    protected const KYR_CHARS = 'АВЕКМНОРСТУХ';

    /**
     * Латинские аналоги разрешенных кириллических символов.
     *
     * Внимание! Важно соответствие порядка символов со `self::CYR_CHARS`.
     */
    protected const KYR_ANALOGS = 'ABEKMHOPCTYX';

    /**
     * Pattern and types map.
     *
     * @var array<string, array<string>>
     */
    protected static $patterns_and_types_map = [
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
        self::FORMAT_PATTERN_9 => [ // XX000X77_OR_XX000X777
            self::GOST_TYPE_15,
        ],
    ];

    /**
     * Get pattern format by passed GOST type.
     *
     * @param string $gost_type
     *
     * @return string|null
     */
    public static function getFormatPatternByGostType($gost_type): ?string
    {
        foreach (static::$patterns_and_types_map as $format_pattern => $gost_types) {
            foreach ((array) $gost_types as $iterated_gost_type) {
                if ($iterated_gost_type === $gost_type) {
                    return $format_pattern;
                }
            }
        }

        return null;
    }

    /**
     * Get GOST types by passed format pattern.
     *
     * @param string $pattern
     *
     * @return string[]|null
     */
    public static function getGostTypesByPattern($pattern): ?array
    {
        if (isset(static::$patterns_and_types_map[$pattern])) {
            return static::$patterns_and_types_map[$pattern];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_GRZ;
    }

    /**
     * Returns value format pattern.
     *
     * @return string|null
     */
    public function getFormatPattern(): ?string
    {
        static $kyr = self::KYR_CHARS;

        $value = (string) $this->value;

        switch (true) {
            // X000XX77_OR_X000XX777
            case \preg_match("~^[{$kyr}]{1}\d{3}[{$kyr}]{2}\d{2,3}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_1;

            // X000XX
            case \preg_match("~^[{$kyr}]{1}\d{3}[{$kyr}]{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_2;

            // XX00077
            case \preg_match("~^[{$kyr}]{2}\d{3}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_3;

            // 0000XX77
            case \preg_match("~^\d{4}[{$kyr}]{2}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_4;

            // XX000077
            case \preg_match("~^[{$kyr}]{2}\d{4}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_5;

            // X000077
            case \preg_match("~^[{$kyr}]{1}\d{4}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_6;

            // 000X77
            case \preg_match("~^\d{3}[{$kyr}]{1}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_7;

            // 0000X77
            case \preg_match("~^\d{4}[{$kyr}]{1}\d{2}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_8;

            // XX000X77_OR_XX000X777
            case \preg_match("~^[{$kyr}]{2}\d{3}[{$kyr}]\d{2,3}$~iu", $value) === 1:
                return self::FORMAT_PATTERN_9;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Переводим в верхний регистр + trim
            $value = Str::upper(trim((string) $value));

            // Удаляем все символы, кроме разрешенных
            $value = (string) \preg_replace('~[^' . self::KYR_CHARS . self::KYR_ANALOGS . '0-9]~u', '', $value);

            // Производим замену латинских аналогов на кириллические (обратная транслитерация). Не прогоняю по всем
            // возможными символам, так как регулярка что выше всё кроме них как раз и удаляет
            $value = Transliterator::detransliterateLite($value);

            return $value;
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
        $format_pattern = $this->getFormatPattern();

        if ($format_pattern !== null) {
            $matches = [];

            switch ($format_pattern) {
                case self::FORMAT_PATTERN_1: // X000XX77_OR_X000XX777
                case self::FORMAT_PATTERN_9: // XX000X77_OR_XX000X777
                    \preg_match('~(?<region_code>\d{2,3})$~D', (string) $this->value, $matches);
                    break;

                case self::FORMAT_PATTERN_2: // X000XX
                    break;

                case self::FORMAT_PATTERN_3: // XX00077
                case self::FORMAT_PATTERN_4: // 0000XX77
                case self::FORMAT_PATTERN_5: // XX000077
                case self::FORMAT_PATTERN_6: // X000077
                case self::FORMAT_PATTERN_7: // 000X77
                case self::FORMAT_PATTERN_8: // 0000X77
                    \preg_match('~(?<region_code>\d{2})$~D', (string) $this->value, $matches);
                    break;
            }

            if (isset($matches['region_code']) && ! empty($region_code = $matches['region_code'])) {
                return (int) $region_code;
            }
        }

        return null;
    }

    /**
     * Возвращает данные региона по коду региона ГРЗ.
     *
     * @return SubjectCodesInfo|null
     */
    public function getRegionData(): ?SubjectCodesInfo
    {
        $region_code = $this->getRegionCode();

        if (\is_int($region_code)) {
            /** @var SubjectCodes $subjects */
            $subjects = static::getContainer()->make(SubjectCodes::class);

            return $subjects->getByGibddCode($region_code);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var GrzCodeValidatorExtension $validator */
        $validator = static::getContainer()->make(GrzCodeValidatorExtension::class);

        $validated = \is_string($this->value) && $validator->passes('', $this->value);

        $region_valid = false;

        // Пропускаем проверку формата, в котором в принципе нет кода региона
        if ($this->getFormatPattern() === self::FORMAT_PATTERN_2 || $this->getRegionData() instanceof SubjectCodesInfo) {
            $region_valid = true;
        }

        return $validated && $region_valid;
    }
}
