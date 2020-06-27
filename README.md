<p align="center">
  <img src="https://hsto.org/webt/jz/ou/8h/jzou8hewegcf-cx5fz1qn63aasa.png" alt="IDEntity" width="240" />
</p>

# IDEntity

[![Version][badge_packagist_version]][link_packagist]
[![PHP Version][badge_php_version]][link_packagist]
[![Build Status][badge_build_status]][link_build_status]
[![Coverage][badge_coverage]][link_coverage]
[![Downloads count][badge_downloads_count]][link_packagist]
[![License][badge_license]][link_license]

**IDEntity** (identification entities) is objects with implemented methods of validation, normalization, and optional automatic type determination.

## Installation

Require this package with composer using the next command:

```shell
$ composer require avto-dev/identity-laravel "^5.0"
```

> Installed `composer` is required. To install composer, please [click here][getcomposer].

> Please note that you need to fix the **major** version of the package.

After that you **can** "publish" configuration file (`./config/identity.php`) using next command:

```bash
$ ./artisan vendor:publish --provider="AvtoDev\IDEntity\ServiceProvider"
```

## Usage

Below you can find some usage examples.

Type detection:

```php
use AvtoDev\IDEntity\IDEntity;

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN); // true
IDEntity::is('A123AA177', IDEntity::ID_TYPE_VIN); // false

IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_GRZ); // false
IDEntity::is('A123AA177', IDEntity::ID_TYPE_GRZ); // true

IDEntity::is('14:36:102034:2256', IDEntity::ID_TYPE_CADASTRAL_NUMBER); // true
IDEntity::is('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_CADASTRAL_NUMBER); // false

$valid_vin = IDEntity::make('JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN);
$valid_vin->isValid(); // true

$invalid_vin = IDEntity::make('SOME INVALID', IDEntity::ID_TYPE_VIN);
$invalid_vin->isValid(); // false
```

Entities creation:

```php
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityCadastralNumber;

$vin = IDEntity::make('JF1SJ5LC5DG048667');
$vin->getType();  // 'VIN'
$vin->getValue(); // 'JF1SJ5LC5DG048667'
$vin->isValid();  // true
\get_class($vin); // 'AvtoDev\IDEntity\Types\IDEntityVin'

$cadastral_number = new IDEntityCadastralNumber('10:01:0030104:691');
$cadastral_number->getType(); // 'CADNUM'
$cadastral_number->isValid(); // true
```

> Some typed entity classes contains a lot of additional methods for a working with passed value.

Value normalization:

```php
use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityVin;

$vin = new IDEntityVin(' jf1SJ5LC5DG048 667');
$vin->getValue(); // 'JF1SJ5LC5DG048667'

IDEntity::make('  a123аY777', IDEntity::ID_TYPE_GRZ)->getValue(); // 'А123АУ777'
```

Value masking:

```php
<?php

use AvtoDev\IDEntity\Types\IDEntityVin;

$vin = IDEntityVin::make('JF1SJ5LC5DG048667');
$vin->getMaskedValue(2, 4);      // JF***********8667
$vin->getMaskedValue(4, 2, '_'); // JF1S___________67
```

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
[badge_build_status]:https://img.shields.io/github/workflow/status/avto-dev/identity-laravel/tests/master
[badge_coverage]:https://img.shields.io/codecov/c/github/avto-dev/identity-laravel/master.svg?maxAge=60
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
[link_coverage]:https://codecov.io/gh/avto-dev/identity-laravel/
[link_issues]:https://github.com/avto-dev/identity-laravel/issues
[link_create_issue]:https://github.com/avto-dev/identity-laravel/issues/new/choose
[link_commits]:https://github.com/avto-dev/identity-laravel/commits
[link_pulls]:https://github.com/avto-dev/identity-laravel/pulls
[link_license]:https://github.com/avto-dev/identity-laravel/blob/master/LICENSE
[getcomposer]:https://getcomposer.org/download/
