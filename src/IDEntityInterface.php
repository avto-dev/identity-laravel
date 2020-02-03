<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

interface IDEntityInterface
{
    /**
     * Internal type, which means "automatic type detection is required".
     *
     * @var string
     */
    public const ID_TYPE_AUTO = 'AUTODETECT';

    /**
     * Unknown type.
     *
     * @var string
     */
    public const ID_TYPE_UNKNOWN = 'UNKNOWN';

    /**
     * Vehicle identification number.
     *
     * @var string
     */
    public const ID_TYPE_VIN = 'VIN';

    /**
     * Vehicle registration sign number (as usual - russian).
     *
     * @var string
     */
    public const ID_TYPE_GRZ = 'GRZ';

    /**
     * Number of vehicle registration certificate.
     *
     * @var string
     */
    public const ID_TYPE_STS = 'STS';

    /**
     * Vehicle passport number.
     *
     * @var string
     */
    public const ID_TYPE_PTS = 'PTS';

    /**
     * Vehicle chassis number.
     *
     * @var string
     */
    public const ID_TYPE_CHASSIS = 'CHASSIS';

    /**
     * Vehicle body number.
     *
     * @var string
     */
    public const ID_TYPE_BODY = 'BODY';

    /**
     * Driver license number.
     *
     * @var string
     */
    public const ID_TYPE_DRIVER_LICENSE_NUMBER = 'DLN';

    /**
     * Cadastral number (unique property number).
     *
     * @var string
     */
    public const ID_TYPE_CADASTRAL_NUMBER = 'CADNUM';

    /**
     * Create a new IDEntity instance.
     *
     * @param string $value
     * @param string $type
     *
     * @return TypedIDEntityInterface
     */
    public static function make(string $value, ?string $type);

    /**
     * Check for passed value has passed type?
     *
     * @param string          $value
     * @param string|string[] $type
     *
     * @return bool
     */
    public static function is(string $value, $type): bool;
}
