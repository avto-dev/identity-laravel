<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

/**
 * Идентификатор - кадастровый номер
 */
class IDEntityCadastralNumber extends AbstractTypedIDEntity
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
            // Удаляем все символы, кроме разрешенных
            $value = \preg_replace('~[^\d\:]~u', '', (string) $value);

            //Удаляем первый символ, если это не цифра
            return \preg_replace('~^([^\d]+)~u', '', (string) $value);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        /** @var CadastralNumberValidatorExtension $validator */
        $validator = static::getContainer()->make(CadastralNumberValidatorExtension::class);

        return \is_string($this->value) && $validator->passes('', $this->value);
    }
}
