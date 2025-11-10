# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## UNRELEASED

### Changed

- Normalization of `VIN`, `BODY` and `CHASSIS`
- Not used code marked as deprecated

## v5.10.0

### Added

- Laravel `12.x` support
- Using `docker` with `compose` plugin instead of `docker-compose` for test environment

### Changed

- Package `avto-dev/extended-laravel-validator` up to `^5.0`

### Fixed

- Тests for `IDEntityBody`

## v5.9.0

### Changed

- Package `avto-dev/extended-laravel-validator` up to `^4.0`

## v5.8.0

### Added

- Laravel `11.x` support

### Changed

- Minimal Laravel version now is `10.0`
- Version of `composer` in docker container updated up to `2.7.6`
- Updated dev dependencies

## v5.7.0

### Added

- Support Laravel `10.x`
- Support PHPUnit `v10`

### Changed

- Minimal require PHP version now is `8.0.2`
- Composer version up to `2.6.5`
- Package `phpstan/phpstan` up to `^1.10`
- Package `avto-dev/static-references-laravel` up to `^4.5`
- Package `avto-dev/extended-laravel-validator` up to `^3.7`

## v5.6.0

### Added

- Support Laravel `9.x`

### Changed

- Minimal required PHP version now is `7.3`

## v5.5.1

### Fixed

- Wrong behavior in `IDEntityCadastralNumber` [#20]

[#20]:https://github.com/avto-dev/identity-laravel/issues/20

## v5.5.0

### Changed

- Validation Region data in `IDEntityDriverLicenseNumber`

## v5.4.0

### Removed

- Dependency `tarampampam/wrappers-php` because this package was deprecated and removed

## v5.3.0

### Added

- Support PHP `8.x`

### Changed

- Composer `2.x` is supported now

## v5.2.0

### Changed

- Laravel `8.x` is supported now
- Minimal Laravel version now is `6.0` (Laravel `5.5` LTS got last security update August 30th, 2020)
- CI completely moved from "Travis CI" to "Github Actions" _(travis builds disabled)_
- Minimal required PHP version now is `7.2`

## v5.1.0

### Changed

- Maximal `illuminate/*` packages version now is `7.*`
- Method `Transliterator::transliterateString()` uses `Stringy\Stringy::toAscii()` instead `Illuminate\Support\Str::ascii()` for backward compatibility with previous versions
- Classes `Transliterator` and `Normalizer` marked as internal

### Added

- Package `danielstjules/stringy` for strings transliteration

## v5.0.0

### Changed

- Maximal `illuminate/*` packages version now is `~6.0`
- Minimal `illuminate/*` packages version now is `^5.5`
- Minimal version of `avto-dev/extended-laravel-validator` package now `^3.2`
- Minimal version of `avto-dev/static-references-laravel` package now `^4.0`
- Typed IDEntities validation depends on `avto-dev/static-references-laravel` package service-provider loading
- `styleci.io` rules
- Config file location `./src/config/identity.php` &rarr; `./config/identity.php`
- Method signatures in `AvtoDev\IDEntity\IDEntity` class:
  - `::typeIsSupported($type): bool` &rarr; `::typeIsSupported(string $type): bool`
  - `::is(string $value, $type): bool` &rarr; `::is(string $value, string $type): bool`
- Method signatures in `AvtoDev\IDEntity\IDEntityInterface` interface:
  - `::is(string $value, $type): bool` &rarr; `::is(string $value, string $type): bool`
- Constructor in `AvtoDev\IDEntity\Types\AbstractTypedIDEntity` finalized
- Method signatures in `AvtoDev\IDEntity\HasCadastralNumberInterface` interface:
  - `->getRegionData(): ?\AvtoDev\StaticReferences\References\CadastralDistricts\CadastralRegionEntry` &rarr; `->getDistrictData(): ?\AvtoDev\StaticReferences\References\Entities\CadastralDistrict`
- Method signatures in `AvtoDev\IDEntity\HasRegionDataInterface` interface:
  - `->getRegionData(): ?\AvtoDev\StaticReferences\References\AutoRegions\AutoRegionEntry` &rarr; `->getRegionData(): ?\AvtoDev\StaticReferences\References\Entities\SubjectCodesInfo`
- Typed IDEntity classes now contains finalized static method `::make(string $value, ?string $type = null): self`
- Validation logic in `AvtoDev\IDEntity\Types\IDEntityCadastralNumber` now more strict
- Dev-dependencies `phpstan/phpstan` and `phpunit/phpunit` updated
- Unit-tests re-wrote

### Added

- GitHub Actions for a tests running
- PHP 7.4 support
- `tarampampam/wrappers-php` dependency
- Class `AvtoDev\IDEntity\Types\IDEntityCadastralNumber` now contains new methods:
  - `->getDistrictCode(): ?int`
  - `->getAreaCode(): ?int`
  - `->getSectionCode(): ?int`
  - `->getParcelCode(): ?int`

### Removed

- Dev-dependency `mockery/mockery`

## v4.1.0

### Added

- `IDEntityCadastralNumber` for cadastral number

## v4.0.0

### Added

- Docker-based environment for development
- Project `Makefile`
- VIN code checksum validation method (`isChecksumValidated`) for `IDEntityVin`
- `declare(strict_types = 1)` into each class

### Changed

- `AvtoDev\IDEntity\Helpers\Normalizer::normalizeDashChar` now always returns empty string
- `AvtoDev\IDEntity\IDEntitiesServiceProvider` &rarr; `AvtoDev\IDEntity\ServiceProvider`
- Dependency `laravel/framework` changed to `illuminate/*`
- Composer scripts
- **Method signatures in classes now type-hinted (where it possible)**
- Constants `ID_TYPE_AUTO`, `ID_TYPE_UNKNOWN`, `ID_TYPE_VIN`, `ID_TYPE_GRZ`, `ID_TYPE_STS`, `ID_TYPE_PTS`, `ID_TYPE_CHASSIS`, `ID_TYPE_BODY`, `ID_TYPE_DRIVER_LICENSE_NUMBER` moved into interface `IDEntityInterface` (from `IDEntity` class)
- Constructor in class `IDEntity` now **protected** (not available outside class)
- Method `getExtendedTypesMap` in `IDEntity` now uses direct call to the `Illuminate\Container\Container` instance instead calling `resolve` laravel helper
- Validation using `avto-dev/extended-laravel-validator` now works without laravel validator
- Accessing to the data from `avto-dev/static-references-laravel` package now works without laravel dependency

### Removed

- Auto-registering service-providers from packages `avto-dev/extended-laravel-validator` and `avto-dev/static-references-laravel`

## v3.1.0

### Changed

- Maximal PHP version now is undefined
- Maximal `laravel/framework` version now is `5.7.*`
- CI changed to [Travis CI][travis]
- [CodeCov][codecov] integrated
- Issue templates updated

[travis]:https://travis-ci.org/
[codecov]:https://codecov.io/

## v3.0.1

### Fixed

- Replacement `space` to `dash` between words was removed in normalize method for `CHASSIS` identifier. [#4]

## v3.0.0

### Changed

- Minimal `avto-dev/extended-laravel-validator` package version now `2.0`
- `IDEntityGrz` now now follows `ГОСТ Р 50577-93` excepts "transit" and "diplomatic" numbers formats (**be careful - this changes can break your previous code**)

### Added

- `IDEntityGrz` - constants with patters formats, GOST types
- `IDEntityGrz` - method `getFormatPatternByGostType()`
- `IDEntityGrz` - method `getGostTypesByPattern()`
- `IDEntityGrz` - method `getFormatPattern()`

## v2.4.0

### Added

- Package config file. That can extends package types map. [#2]

## v2.3.0

### Added

- Property `can_be_auto_detected` to the `AbstractTypedIDEntity`
- Method `canBeAutoDetected` to the `AbstractTypedIDEntity` and `TypedIDEntityInterface` [#3]

## v2.2.0

### Changed

- CI config updated
- Package PHPUnit minimal version now is `5.7.10`
- Unimportant PHPDoc blocks removed
- Code a little bit refactored

## v2.1.3

### Fixed

- Region detection method for `GRZ` identifier

## v2.1.2

### Changed

- `DLN` identifier now works only with Russian identifier number formats

### Fixed

- Transliterate method for `GRZ` identifier
- Transliterate method for `DLN` identifier

## v2.1.1

### Changed

- Method for extracting region code from `GRZ` identifier refactored

### Added

- Supports for `GRZ` "taxi" format

## v2.1.0

### Added

- Supports for "Driver License Number" (`DLN`)

## v2.0.0

### Changed

- Migrated to the `v2.0` of package `avto-dev/static-references-laravel`

## v1.1.7

### Added

- `::is()` method now supports array of types

## v1.1.6

### Fixed

- Fix body number normalization method *(now spaces inside number does not replaced with single dash char)*

## v1.1.5

### Changed

- Composer dependencies versions updated

[#2]:https://github.com/avto-dev/identity-laravel/issues/2
[#3]:https://github.com/avto-dev/identity-laravel/issues/3
[#4]:https://github.com/avto-dev/identity-laravel/issues/4

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
