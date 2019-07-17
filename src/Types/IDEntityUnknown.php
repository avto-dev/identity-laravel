<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Closure;
use Exception;

class IDEntityUnknown extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return static::ID_TYPE_UNKNOWN;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            return (string) $value;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks(): Closure
    {
        return function (): bool {
            return false;
        };
    }
}
