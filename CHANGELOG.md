# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

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

## v2.1

### Added

- Supports for "Driver License Number" (`DLN`)

## v2.0

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
