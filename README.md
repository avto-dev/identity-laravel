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

IDEntity - это идентификационные сущности, объекты, реализующие расширенные методы валидации, нормализации и автоматического определения типа (опционально).

## Установка

Для установки данного пакета выполните в терминале следующую команду:

```shell
$ composer require avto-dev/identity-laravel "1.*"
```

> Для этого необходим установленный `composer`. Для его установки перейдите по [данной ссылке][getcomposer].

> Обратите внимание на то, что необходимо фиксировать мажорную версию устанавливаемого пакета.

## Использование

{% В данной блоке следует максимально подробно рассказать о том, какие задачи решает данный пакет, какое API предоставляет разработчику, из каких компонентов состоит и привести примеры использования с примерами кода. Привести максимально подробне разъяснения и комментарии. %}

### Тестирование

Для тестирования данного пакета используется фреймворк `phpunit`. Для запуска тестов выполните в терминале:

```shell
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
