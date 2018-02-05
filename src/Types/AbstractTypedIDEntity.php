<?php

namespace AvtoDev\IDEntity\Types;

use Closure;
use AvtoDev\IDEntity\IDEntity;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

/**
 * Class AbstractTypedIDEntity.
 *
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
     * AbstractTypedIDEntity constructor.
     *
     * @param string $value
     * @param bool   $make_normalization
     */
    public function __construct($value, $make_normalization = true)
    {
        $this->value = $make_normalization === true
            ? static::normalize($value)
            : $value;
    }

    /**
     * Возвращает строковое представление объекта при попытке преобразовать в строку последнего.
     *
     * @return string
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
     * Метод-заглушка для родительского метода.
     */
    public static function is($value, $type = null)
    {
        $instance = new static($value);

        return $instance->isValid();
    }

    /**
     * Возвращает значение идентификатора.
     *
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Возвращает тип идентификатора.
     *
     * @return null|string
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
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $value        = $this->getValue();
        $passed_count = 0;

        foreach (($callbacks = (array) $this->getValidateCallbacks()) as $callback) {
            if ($callback($value) === true) {
                $passed_count++;
            } else {
                return false;
            }
        }

        return count($callbacks) === $passed_count;
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
        return app()->make('validator');
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
}
