<p align="center">
  <img alt="logo" src="https://hsto.org/webt/v1/jq/ii/v1jqiivfgdwfxuaomvl9tzwi-ba.png" width="70" height="70" />
</p>

# IDEntity для Laravel приложений

[![Version][badge_version]][link_packagist]
[![License][badge_license]][link_license]
[![Build Status][badge_build_status]][link_build_status]
![StyleCI][badge_styleci]
[![Code Coverage][badge_coverage]][link_coverage]
[![Scrutinizer Code Quality][badge_quality]][link_coverage]

**IDEntity** - это идентификационные сущности (объекты), реализующие расширенные методы валидации, нормализации и автоматического определения типа (опционально).

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/identity-laravel "1.*"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

> Для версии __Laravel 5.4.*__ необходимо вручную зарегистрировать сервис-провайдер и алиасы фасадов (опционально):
> 
> ```php
> 'providers' => [
>     // ...
>     AvtoDev\IDEntity\IDEntitiesServiceProvider::class,
> ];
> ```

### Зависимые пакеты

Стоит обратить внимание на то, что данный пакет имеет в зависимостях следующие пакеты:

 * `avto-dev/extended-laravel-validator` - используется для валидации значений с помощью встроенного в Laravel валидатора;
 * `avto-dev/static-references-laravel` - используется справочник регионов (ГИБДД), для извлечения подробной информации о регионе ГРЗ-знака, и его валидации.
 
Регистрация сервис-провайдеров указанных выше пакетов производится автоматически в том случае, если их сервис-провайдеры не были зарегистрированы до того, как была произведена регистрация сервис-провайдера данного пакета.

## Использование

Данный пакет предоставляет API для работы с идентификационными сущностями, такими так:

Тип       | Описание                                                       | Класс объекта, его обслуживающего
:-------: | :------------------------------------------------------------: | :-------------------------------------:
`VIN`     | VIN-код ТС                                                     | `\AvtoDev\IDEntity\Types\IDEntityVin`
`GRZ`     | Государственный регистрационный знак (ГРЗ)                     | `\AvtoDev\IDEntity\Types\IDEntityGrz`
`STS`     | Номер свидетельства о регистрации транспортного средства (СТС) | `\AvtoDev\IDEntity\Types\IDEntitySts`
`PTS`     | Номер паспорта транспортного средства (ПТС)                    | `\AvtoDev\IDEntity\Types\IDEntityPts`
`BODY`    | Номер кузова транспортного средства                            | `\AvtoDev\IDEntity\Types\IDEntityBody`
`CHASSIS` | Номер шасси транспортного средства                             | `\AvtoDev\IDEntity\Types\IDEntityChassis`
`UNKNOWN` | Неизвестный идентификатор                                      | `\AvtoDev\IDEntity\Types\IDEntityUnknown`

> Все объекты, обслуживающие типы идентификаторов (типизированные идентификаторы) являются наследниками класса `AvtoDev\IDEntity\IDEntity`.

Позволяя производить с ними следующие операции:

 * Нормализацию - приведение к нормальному виду;
 * Валидацию - произведение проверки корректности значения;
 * Автоматическое определение типа (работает не всегда корректно, так как форматы некоторых идентификаторов идентичны).

### Примеры использования

Для того, что бы получить все поддерживаемые типы идентификаторов, или проверить какой-либо на предмет поддержки данным пакетом вы можете воспользоваться следующими методами:

```php
use AvtoDev\IDEntity\IDEntity;

IDEntity::getSupportedTypes(); // ['VIN', 'GRZ', 'STS', ...]
IDEntity::typeIsSupported('VIN'); // true
IDEntity::typeIsSupported('FOO BAR'); // false
```

Для того, что бы создать объект идентификационной сущности вы можете воспользоваться фабричным методом `::make` у объекта `IDEntity`, или создать объект необходимого класса напрямую. Рассмотрим подробнее на примере VIN-кода:

```php
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityVin;

$code = 'JF1SJ5LC5DG048667';

$vin = IDEntity::make($code, IDEntity::ID_TYPE_VIN);
$vin = new IDEntityVin($code);
```

В обоих случаях переменной `$vin` будет присвоен объект типа `IDEntityVin`. Необходимость наличия статичного метода `::make` обусловлена тем, что с его помощью возможно использование метода автоматического определения типа, и он является единой точкой входа в метод создания типизированных идентификаторов - его использование является более лучшей практикой.

Если в метод `::make` вторым аргументом не будет передан тип идентификатора, и попытка автоматически определить его тип не увенчается успехом - будет возвращён объект типа `\AvtoDev\IDEntity\Types\IDEntityUnknown`. Данный объект всегда возвращает `false` при попытке его валидации.

Без указания вторым аргументом типа идентификатора будет произведена попытка автоматического определения его типа:

```php
use AvtoDev\IDEntity\IDEntity;

$vin = IDEntity::make('JF1SJ5LC5DG048667');
$vin->getType(); // 'VIN'

$vin = IDEntity::make('A123AA177');
$vin->getType(); // 'GRZ'
```

> Имейте в виду - данный функционал не всегда корректно определяет тип переданного в `::make` идентификатора, так как проверка переданного типа производится путём прохождения им валидации, а правила валидации, например, у номеров СТС и ПТС аналогичны.

### Нормализация значений

В момент создания объекта производится автоматическая нормализация переданного в него идентификатора. Для того, что бы нормализация не производилась - вам необходимо создать необходимый объект с использованием ключевого слова `new` и передать в конструктор вторым аргументом `false`:

```php
use AvtoDev\IDEntity\Types\IDEntityVin;

$un_noramalized = ' jf1SJ5LC5DG048 667';

new IDEntityVin($un_noramalized); // 'JF1SJ5LC5DG048667'
new IDEntityVin($un_noramalized, false); // ' jf1SJ5LC5DG048 667'
```

> Имейте в виду, что не-нормализованные значения могут не проходить валидацию!

> Не пытайтесь вызвать конструктор у объекта `IDEntity` (например: `$some = new IDEntity()`) - это приведёт к фатальной ошибке типа `LogicException`.

Для изменения значения идентификатора у объекта - можете воспользоваться методом `->setValue(...)`, например:

```php
use AvtoDev\IDEntity\Types\IDEntityVin;

$vin = new IDEntityVin('JF1SJ5LC5DG048667'); // 'JF1SJ5LC5DG048667'
$vin->setValue('X9FDXXEEBDDG37057'); // Now 'X9FDXXEEBDDG37057'
```

Вторым аргументом в метод `->setValue(..., true)` вы можете передать `false`, что предотвратит принудительную нормализацию передаваемого значения.

### Валидация значений

Для валидации значения используйте метод `->isValid()` у объекта типизированного идентификатора:

```php
use AvtoDev\IDEntity\IDEntity;

$valid_vin = IDEntity::make('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN);
$valid_vin->isValid(); // true

$invalid_vin = IDEntity::make('SOME INVALID', IDEntity::ID_TYPE_VIN);
$invalid_vin->isValid(); // false

$valid_grz = IDEntity::make('A123AA177', IDEntity::ID_TYPE_GRZ);
$valid_grz->isValid(); // true

$invalid_grz = IDEntity::make('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_GRZ);
$invalid_grz->isValid(); // false
```

Так же для валидации значений вы можете использовать следующий метод:

```php
use AvtoDev\IDEntity\IDEntity;

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN); // true
IDEntity::is('A123AA177', IDEntity::ID_TYPE_VIN); // false

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_GRZ); // false
IDEntity::is('A123AA177', IDEntity::ID_TYPE_GRZ); // true
```

### Расширенные методы

Некоторые объекты типизированных идентификаторов имеют расширенные методы, например `IDEntityGrz` умеет:

Имя метода | Его описание
---------: | :-----------
`getRegionCode()` | Возвращает код региона ГРЗ знака
`getRegionData()` | Возвращает объект с детализированными данными о регионе, указанном в коде региона ГРЗ знака

Более подробно о расширенных методах смотрите в классах-реализациях типизированных идентификаторов.

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
$ git clone git@github.com:avto-dev/identity-laravel.git
$ cd ./identity-laravel
$ composer update --dev
$ composer test
```

## Поддержка и развитие

Если у вас возникли какие-либо проблемы по работе с данным пакетом, пожалуйста, создайте соответствующий `issue` в данном репозитории.

Если вы способны самостоятельно реализовать тот функционал, что вам необходим - создайте PR с соответствующими изменениями. Крайне желательно сопровождать PR соответствующими тестами, фиксирующими работу ваших изменений. После проверки и принятия изменений будет опубликована новая минорная версия.

## Лицензирование

Код данного пакета распространяется под лицензией [MIT][link_license].

[badge_version]:https://img.shields.io/packagist/v/avto-dev/identity-laravel.svg?style=flat&maxAge=30
[badge_license]:https://img.shields.io/packagist/l/avto-dev/identity-laravel.svg
[badge_build_status]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/badges/build.png?b=master
[badge_styleci]:https://styleci.io/repos/120107651/shield?style=flat&maxAge=30
[badge_coverage]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/badges/coverage.png?b=master
[badge_quality]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/badges/quality-score.png?b=master
[link_packagist]:https://packagist.org/packages/avto-dev/identity-laravel
[link_license]:https://github.com/avto-dev/identity-laravel/blob/master/LICENSE
[link_build_status]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/build-status/master
[link_coverage]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/?branch=master
[getcomposer]:https://getcomposer.org/download/
