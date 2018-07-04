# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

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

[#3]:https://github.com/avto-dev/identity-laravel/issues/3

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
