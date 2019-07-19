<?php

declare(strict_types = 1);

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\ExtendedLaravelValidator\Extensions\CadastralNumberValidatorExtension;

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
            // Удаляем все символы, кроме ./разрешенных
            $value = (string) \preg_replace('~[^\d\:]~u', '', (string) $value);

            // Удаляем первые и последние символы, если это не цифры
            return \trim($value, ':');
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
