<?php

namespace AvtoDev\IDEntity\Types;

use AvtoDev\IDEntity\IDEntity;

abstract class AbstractTypedIDEntity extends IDEntity implements TypedIDEntityInterface
{
    /**
     * Значение идентификатора.
     *
     * @var string|null
     */
    protected $id_value;

    /**
     * Тип идентификатора.
     *
     * @var string|null
     */
    protected $id_type;

    /**
     * Возвращает значение идентификатора.
     *
     * @return null|string
     */
    public function getValue()
    {
        return $this->id_value;
    }

    /**
     * Возвращает тип идентификатора.
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->id_type;
    }

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
     * Возвращает строковое представление объекта при попытке преобразовать в строку последнего.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}
