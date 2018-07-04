<?php

namespace AvtoDev\IDEntity\Types;

use Closure;
use AvtoDev\IDEntity\IDEntity;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * Абстрактный класс типизированного идентификатора.
 */
abstract class AbstractTypedIDEntity extends IDEntity implements TypedIDEntityInterface
{
    /**
     * Значение идентификатора.
     *
     * @var string|null
     */
    protected $value;

    /**
     * Может ли тип быть автоматически определяемым.
     *
     * @var bool
     */
    protected $can_be_auto_detected = true;

    /**
     * AbstractTypedIDEntity constructor.
     *
     * @param string $value
     * @param bool   $make_normalization
     */
    public function __construct($value, $make_normalization = true)
    {
        $this->setValue($value, $make_normalization);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * {@inheritdoc}
     *
     * Метод-заглушка для родительского метода-факторки.
     */
    public static function make($value, $type = null)
    {
        return new static($value);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $type Тип ИГНОРИРУЕТСЯ
     *
     * Метод-заглушка для родительского метода
     */
    public static function is($value, $type = null)
    {
        $instance = new static($value);

        return $instance->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value, $make_normalization = true)
    {
        $this->value = $make_normalization === true
            ? static::normalize($value)
            : $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaskedValue($start_offset = 3, $end_offset = 3, $mask_char = '*')
    {
        return ($current = $this->getValue()) === null
            ? $current
            : $this->hideString($current, $start_offset, $end_offset, $mask_char);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getType();

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            'value' => $this->getValue(),
            'type'  => $this->getType(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return \json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $value        = $this->getValue();
        $passed_count = 0;

        foreach ($callbacks = (array) $this->getValidateCallbacks() as $callback) {
            if ($callback($value) === true) {
                $passed_count++;
            } else {
                return false;
            }
        }

        return \count($callbacks) === $passed_count;
    }

    /**
     * {@inheritdoc}
     */
    public function canBeAutoDetected()
    {
        return $this->can_be_auto_detected === true;
    }

    /**
     * Массив callback-функций, с помощью которых производится валидация значения.
     *
     * Первым аргументом в Closure передаётся валидируемое значение (не типизированное).
     *
     * @return Closure|Closure[]|null
     */
    abstract protected function getValidateCallbacks();

    /**
     * Возвращает инстанс валидатора.
     *
     * @return ValidationFactory
     */
    protected function laravelValidatorFactory()
    {
        return resolve('validator');
    }

    /**
     * Производит валидацию переданного (произвольного строкового значения) значения с помощью Laravel-валидатора.
     *
     * @param string $value
     * @param string $rule
     *
     * @return bool
     */
    protected function validateWithValidatorRule($value, $rule = 'required')
    {
        return $this->laravelValidatorFactory()->make(['value' => $value], ['value' => $rule])->fails() === false;
    }

    /**
     * Скрытие строки под звездами.
     *
     * @param string $string       Входящая строка
     * @param int    $start_offset Сдвиг с начала
     * @param int    $end_offset   Сдвиг с конца
     * @param string $mask_char    Замещающий символ
     *
     * @return string
     */
    protected function hideString($string, $start_offset = 3, $end_offset = 3, $mask_char = '*')
    {
        if (\is_string($mask_char) && ! empty($mask_char)) {
            $mask_char = \mb_strlen($mask_char) > 1
                ? \mb_substr($mask_char, 0, 1)
                : $mask_char;
        } else {
            $mask_char = '*';
        }

        $number_length = \mb_strlen($string);

        if ($number_length <= $start_offset + $end_offset) {
            return $string;
        }

        $hidden_str    = \mb_substr($string, $start_offset, $number_length - ($start_offset + $end_offset));
        $stars         = '';
        $hidden_length = \mb_strlen($hidden_str);

        for ($i = 0; $i < $hidden_length; $i++) {
            $stars .= $mask_char;
        }

        return \mb_substr($string, 0, $start_offset)
               . $stars
               . \mb_substr($string, $number_length - $end_offset, $end_offset);
    }
}
