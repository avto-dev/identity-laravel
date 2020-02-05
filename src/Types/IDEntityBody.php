<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;
use AvtoDev\ExtendedLaravelValidator\Extensions\BodyCodeValidatorExtension;

class IDEntityBody extends AbstractTypedIDEntity
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
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value): ?string
    {
        try {
            // Replace multiple whitespaces with one
            $value = (string) \preg_replace('~\s+~u', ' ', \trim((string) $value));

            // Normalize dash char
            $value = Normalizer::normalizeDashChar($value);

            // Replace multiple dashes with one
            $value = (string) \preg_replace('~-+~', '-', $value);

            // Replace white spaces around dash with one dash
            $value = (string) \preg_replace('~\s*-\s*~', '-', $value);

            // Transliterate kyr- chars with latin-
            $value = Transliterator::transliterateString(\mb_strtoupper($value, 'UTF-8'), true);

            // Remove all chars except allowed
            $value = (string) \preg_replace('~[^A-Z0-9\- ]~u', '', $value);

            return $value;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var BodyCodeValidatorExtension $validator */
        $validator = static::getContainer()->make(BodyCodeValidatorExtension::class);

        return \is_string($this->value) && $validator->passes('', $this->value);
    }
}
