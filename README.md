<p align="center">
  <img src="https://hsto.org/webt/fu/du/pt/fuduptocorcmar5s0o9hx3vld6g.png" alt="IDEntity" width="240" />
</p>

# IDEntity <sub><sup>| _Native and Laravel_</sup></sub>

[![Version][badge_packagist_version]][link_packagist]
[![Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Code quality][badge_code_quality]][link_code_quality]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

**IDEntity** (identification entities) is objects with implemented methods of validation, normalization, and optional automatic type determination.

## Installation

Require this package with composer using the next command:

```shell
$ composer require avto-dev/identity-laravel "^4.0"
```

> Installed `composer` is required. To install composer, please [click here][getcomposer].

> Please note that you need to fix the **major** version of the package.

> For __Laravel 5.4.*__ you must manually register package service-provider:
> 
> ```php
> 'providers' => [
>     // ...
>     AvtoDev\IDEntity\ServiceProvider::class,
> ];
> ```

After that you **can** "publish" configuration file (`./config/identity.php`) using next command:

```bash
$ ./artisan vendor:publish --provider="AvtoDev\IDEntity\ServiceProvider"
```

## Usage

Данный пакет предоставляет API для работы с идентификационными сущностями, такими так:

Тип       | Описание                                                       | Класс объекта, его обслуживающего
:-------: | :------------------------------------------------------------: | :-------------------------------------:
`VIN`     | VIN-код ТС                                                     | `\AvtoDev\IDEntity\Types\IDEntityVin`
`GRZ`     | Государственный регистрационный знак (ГРЗ)                     | `\AvtoDev\IDEntity\Types\IDEntityGrz`
`STS`     | Номер свидетельства о регистрации транспортного средства (СТС) | `\AvtoDev\IDEntity\Types\IDEntitySts`
`PTS`     | Номер паспорта транспортного средства (ПТС)                    | `\AvtoDev\IDEntity\Types\IDEntityPts`
`BODY`    | Номер кузова транспортного средства                            | `\AvtoDev\IDEntity\Types\IDEntityBody`
`CHASSIS` | Номер шасси транспортного средства                             | `\AvtoDev\IDEntity\Types\IDEntityChassis`
`DLN`     | Номер водительского удостоверения                              | `\AvtoDev\IDEntity\Types\IDEntityDriverLicenseNumber`
`CADNUM`  | Кадастровый номер объекта недвижимости                         | `\AvtoDev\IDEntity\Types\IDEntityCadastralNumber`
`UNKNOWN` | Неизвестный идентификатор                                      | `\AvtoDev\IDEntity\Types\IDEntityUnknown`

> Все объекты, обслуживающие типы идентификаторов (типизированные идентификаторы) являются наследниками класса `AvtoDev\IDEntity\IDEntity`.

Позволяя производить с ними следующие операции:

 * Нормализацию - приведение к нормальному виду;
 * Валидацию - произведение проверки корректности значения;
 * Автоматическое определение типа (работает не всегда корректно, так как форматы некоторых идентификаторов идентичны).

> Опционально (при использовании вместе с Laravel) вы можете в конфигурационном файле указать дополнительные провайдеры типизированных идентификаторов, которые вам необходимы.

### Usage examples

Для того, что бы получить все поддерживаемые типы идентификаторов, или проверить какой-либо на предмет поддержки данным пакетом вы можете воспользоваться следующими методами:

```php
<?php

use AvtoDev\IDEntity\IDEntity;

IDEntity::getSupportedTypes(); // ['VIN', 'GRZ', 'STS', ...]
IDEntity::typeIsSupported('VIN'); // true
IDEntity::typeIsSupported('FOO BAR'); // false
```

Для того, что бы создать объект идентификационной сущности вы можете воспользоваться фабричным методом `::make` у объекта `IDEntity`, или создать объект необходимого класса напрямую. Рассмотрим подробнее на примере VIN-кода:

```php
<?php

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityVin;

$code = 'JF1SJ5LC5DG048667';

$vin         = IDEntity::make($code, IDEntity::ID_TYPE_VIN);
$same_object = new IDEntityVin($code);
```

Если в метод `::make` вторым аргументом не будет передан тип идентификатора, и попытка автоматически определить его тип не увенчается успехом - будет возвращён объект типа `\AvtoDev\IDEntity\Types\IDEntityUnknown`. Данный объект всегда возвращает `false` при попытке его валидации.

Без указания вторым аргументом типа идентификатора будет произведена попытка автоматического определения его типа:

```php
<?php

use AvtoDev\IDEntity\IDEntity;

$vin = IDEntity::make('JF1SJ5LC5DG048667');
$vin->getType(); // 'VIN'

$vin = IDEntity::make('A123AA177');
$vin->getType(); // 'GRZ'
```

> Имейте в виду - данный функционал не всегда корректно определяет тип переданного в `::make` идентификатора, так как проверка переданного типа производится путём прохождения им валидации, а правила валидации, например, у номеров СТС и ПТС аналогичны.

Так же реализован механизм "маскировки" значений (скрытия части идентификатора за, например, "звездочками"), для этого вы можете использовать метод `getMaskedValue()` у объекта типизированного идентификатора. Данный метод принимает первым аргументом число "отрытых" символов значения идентификатора в начале, вторым - в конце, и третьим - символ, которым необходимо "маскировать", например:

```php
<?php

use AvtoDev\IDEntity\Types\IDEntityVin;

$vin = IDEntityVin::make('JF1SJ5LC5DG048667');
$vin->getMaskedValue(2, 4); // JF***********8667
$vin->getMaskedValue(4, 2, '_'); // JF1S___________67
```

### Нормализация значений

В момент создания объекта производится автоматическая нормализация переданного в него идентификатора. Для того, что бы нормализация не производилась - вам необходимо создать необходимый объект с использованием ключевого слова `new` и передать в конструктор вторым аргументом `false`:

```php
<?php

use AvtoDev\IDEntity\Types\IDEntityVin;

$un_noramalized = ' jf1SJ5LC5DG048 667';

new IDEntityVin($un_noramalized); // 'JF1SJ5LC5DG048667'
new IDEntityVin($un_noramalized, false); // ' jf1SJ5LC5DG048 667'
```

> Имейте в виду, что не-нормализованные значения могут не проходить валидацию!

> Не пытайтесь вызвать конструктор у объекта `IDEntity` (например: `$some = new IDEntity()`) - это приведёт к фатальной ошибке типа `LogicException`.

Для изменения значения идентификатора у объекта - можете воспользоваться методом `->setValue(...)`, например:

```php
<?php

use AvtoDev\IDEntity\Types\IDEntityVin;

$vin = new IDEntityVin('JF1SJ5LC5DG048667'); // 'JF1SJ5LC5DG048667'
$vin->setValue('X9FDXXEEBDDG37057'); // Now 'X9FDXXEEBDDG37057'
```

Вторым аргументом в метод `->setValue(..., true)` вы можете передать `false`, что предотвратит принудительную нормализацию передаваемого значения.

### Валидация значений

Для валидации значения используйте метод `->isValid()` у объекта типизированного идентификатора:

```php
<?php

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
<?php

use AvtoDev\IDEntity\IDEntity;

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN); // true
IDEntity::is('A123AA177', IDEntity::ID_TYPE_VIN); // false

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_GRZ); // false
IDEntity::is('A123AA177', IDEntity::ID_TYPE_GRZ); // true

IDEntity::is('JF1SJ5LC5DG048667', [IDEntity::ID_TYPE_VIN, IDEntity::ID_TYPE_GRZ]); // true
IDEntity::is('А123АА177', [IDEntity::ID_TYPE_VIN, IDEntity::ID_TYPE_PTS]); // false
```

### Расширенные методы

Некоторые объекты типизированных идентификаторов имеют расширенные методы, например `IDEntityGrz` и `IDEntityDriverLicenseNumber` умеют:

Имя метода | Его описание
---------: | :-----------
`getRegionCode()` | Возвращает код региона, связанный с идентификатором
`getRegionData()` | Возвращает объект с детализированными данными о регионе, связанным с идентификатором
`getFormatPattern()` | Возвращает формат значения идентификатора


Идентификатор `IDEntityCadastralNumber` имеет расширенные методы:

Имя метода | Его описание
---------: | :-----------
`getRegionCode()` | Возвращает кадастровый номер округа, связанный с идентификатором
`getDistrictCode()` | Возвращает кадастровый номер района, связанный с идентификатором
`getDistrictData()` | Возвращает объект с данными об кадастровом районе

Более подробно о расширенных методах смотрите в классах-реализациях типизированных идентификаторов.

### Testing

For package testing we use `phpunit` framework and `docker-ce` + `docker-compose` as develop environment. So, just write into your terminal after repository cloning:

```bash
$ make build
$ make latest # or 'make lowest'
$ make test
```

## Changes log

[![Release date][badge_release_date]][link_releases]
[![Commits since latest release][badge_commits_since_release]][link_commits]

Changes log can be [found here][link_changes_log].

## Support

[![Issues][badge_issues]][link_issues]
[![Issues][badge_pulls]][link_pulls]

If you will find any package errors, please, [make an issue][link_create_issue] in current repository.

## License

This is open-sourced software licensed under the [MIT License][link_license].

[badge_packagist_version]:https://img.shields.io/packagist/v/avto-dev/identity-laravel.svg?maxAge=180
[badge_php_version]:https://img.shields.io/packagist/php-v/avto-dev/identity-laravel.svg?longCache=true
[badge_build_status]:https://travis-ci.org/avto-dev/identity-laravel.svg?branch=master
[badge_code_quality]:https://img.shields.io/scrutinizer/g/avto-dev/identity-laravel.svg?maxAge=180
[badge_downloads_count]:https://img.shields.io/packagist/dt/avto-dev/identity-laravel.svg?maxAge=180
[badge_license]:https://img.shields.io/packagist/l/avto-dev/identity-laravel.svg?longCache=true
[badge_release_date]:https://img.shields.io/github/release-date/avto-dev/identity-laravel.svg?style=flat-square&maxAge=180
[badge_commits_since_release]:https://img.shields.io/github/commits-since/avto-dev/identity-laravel/latest.svg?style=flat-square&maxAge=180
[badge_issues]:https://img.shields.io/github/issues/avto-dev/identity-laravel.svg?style=flat-square&maxAge=180
[badge_pulls]:https://img.shields.io/github/issues-pr/avto-dev/identity-laravel.svg?style=flat-square&maxAge=180
[link_releases]:https://github.com/avto-dev/identity-laravel/releases
[link_packagist]:https://packagist.org/packages/avto-dev/identity-laravel
[link_build_status]:https://travis-ci.org/avto-dev/identity-laravel
[link_changes_log]:https://github.com/avto-dev/identity-laravel/blob/master/CHANGELOG.md
[link_code_quality]:https://scrutinizer-ci.com/g/avto-dev/identity-laravel/
[link_issues]:https://github.com/avto-dev/identity-laravel/issues
[link_create_issue]:https://github.com/avto-dev/identity-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/identity-laravel/commits
[link_pulls]:https://github.com/avto-dev/identity-laravel/pulls
[link_license]:https://github.com/avto-dev/identity-laravel/blob/master/LICENSE
[laravel_validator_doc]:https://laravel.com/docs/5.5/validation
[getcomposer]:https://getcomposer.org/download/
