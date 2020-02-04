<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\TypedIDEntityInterface;

interface IDEntityInterface
{
    /**
     * Allowed ID entity types.
     */
    public const
        ID_TYPE_AUTO                  = 'AUTODETECT', // Internal type, means "automatic type detection is required"
        ID_TYPE_UNKNOWN               = 'UNKNOWN',    // Unknown type
        ID_TYPE_VIN                   = 'VIN',        // Vehicle identification number
        ID_TYPE_GRZ                   = 'GRZ',        // Vehicle registration sign number (as usual - russian)
        ID_TYPE_STS                   = 'STS',        // Number of vehicle registration certificate
        ID_TYPE_PTS                   = 'PTS',        // Vehicle passport number
        ID_TYPE_CHASSIS               = 'CHASSIS',    // Vehicle chassis number
        ID_TYPE_BODY                  = 'BODY',       // Vehicle body number
        ID_TYPE_DRIVER_LICENSE_NUMBER = 'DLN',        // Driver license number
        ID_TYPE_CADASTRAL_NUMBER      = 'CADNUM';     // Cadastral number (unique property number)

    /**
     * Create a new ID entity instance.
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
     * @param string               $value
     * @param string|array<string> $type
     *
     * @return bool
     */
    public static function is(string $value, $type): bool;
}
