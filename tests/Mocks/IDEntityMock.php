<?php

namespace AvtoDev\IDEntity\Tests\Mocks;

use AvtoDev\IDEntity\IDEntity;

class IDEntityMock extends IDEntity
{
    /**
     * IDEntityMock constructor.
     */
    public function __construct()
    {
        // Разрешаем конструктор
    }

    /**
     * @return array|string[]
     */
    protected static function getTypesMap()
    {
        // Добавляем новый тип, который не должен возвращаться автоматическим определением
        return array_merge(parent::getTypesMap(), [
            IDEntityCantAutodetectMock::TYPE => IDEntityCantAutodetectMock::class
        ]);
    }
}
