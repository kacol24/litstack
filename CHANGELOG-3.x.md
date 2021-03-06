# Release Notes for 3.x

## [Unreleased](https://github.com/litstack/litstack/compare/v3.4.1...3.x)

### Fixed

-   Fixed images not showing in nested blocks ([617dbaa](https://github.com/litstack/litstack/commit/617dbaa221976ee5b4b857fc026020afebb3243d))

## [v3.4.1](https://github.com/litstack/litstack/compare/v3.4.0...v3.4.1)

### Fixed

-   Fixed editing translatable fields when locale is not in model ([#168](https://github.com/litstack/litstack/pull/168))
-   Fixed `CrudCreate` and `CrudUpdate` ([8996d4e](https://github.com/litstack/litstack/commit/8996d4ea22df4c6624dfd69ba0d711ccf459348b))
-   Fixed chart `last` intervals not including current interval ([b38407d](https://github.com/litstack/litstack/commit/b38407dff0d47f1d11cec75835bab716373a8679), [#169](https://github.com/litstack/litstack/issues/169))

## [v3.4.0](https://github.com/litstack/litstack/compare/v3.3.3...v3.4.0)

### Added

-   Added multiple crud form functionality :fire: ([#161](https://github.com/litstack/litstack/pull/161))
-   Added the possibility to add actions anywhere on the cudshow ([49b7d1c](https://github.com/litstack/litstack/commit/49b7d1cecff0cd7caaffaabdef2cc5f703950973))
-   Added custom style for table columns ([85de72d](https://github.com/litstack/litstack/commit/85de72d7403382d3039f3a7a689a36eaf8b519b8))

### Changed

-   Changed to version of laravel-mix from v5.0 to v6.0 ([#165](https://github.com/litstack/litstack/pull/165), [7787747](https://github.com/litstack/litstack/commit/77877470636fe28c01f3fcf31447ecd86acded1a))

## Fixed

-   Fixed breaking changes in datetime field ([86bbe9b](https://github.com/litstack/litstack/commit/86bbe9bb76484da3ac3430c72ce23ab0b97db743))
-   Fixed field conditions apply after model saving ([49b7d1c](https://github.com/litstack/litstack/commit/49b7d1cecff0cd7caaffaabdef2cc5f703950973))

## [v3.3.3](https://github.com/litstack/litstack/compare/v3.3.2...v3.3.3)

### Added

-  Added formatting model attributes for page info and text field ([#159](https://github.com/litstack/litstack/pull/159))

### Fixed

-  Fixed unlink or delete colum in crud relation table ([#158](https://github.com/litstack/litstack/pull/158))
-  Fixed css ([#160](https://github.com/litstack/litstack/pull/160))

## [v3.3.2](https://github.com/litstack/litstack/compare/v3.3.1...v3.3.2)

### Added

-   Added default minute interval `5` ([a550bc2](https://github.com/litstack/litstack/commit/a550bc21e4f59073c258c443c58f617c18d604b5))
-   Added `date`, `time` and `datetime` fields as replacement for `datetime` url ([e197e28](https://github.com/litstack/litstack/commit/e197e2868e4a56b505592e681a7bf52fbac809a3))

### Fixed

-   Fixed crud action for CrudShow ([ecddd85](https://github.com/litstack/litstack/commit/ecddd8515ea28832185193ddeae80a00be2c6e5f))
-   Fixed redirecting after creating new model ([9ac2014](https://github.com/litstack/litstack/commit/9ac201472900dd595b82cd3a9947e8adef635de2))

### Changed

-   Changed url after login to intendet url ([#157](https://github.com/litstack/litstack/pull/157))

## [v3.3.1](https://github.com/litstack/litstack/compare/v3.3.0...v3.3.1)

### Added

-   Added `addMiddleware` method to Kernel ([b748b37](https://github.com/litstack/litstack/commit/b748b37eddf50b7d508cd881cfe4c89087ef3509))

## [v3.3.0](https://github.com/litstack/litstack/compare/v3.2.5...v3.3.0)

### Added

-   Added input masks ([#127](https://github.com/litstack/litstack/pull/127))
-   Added relation preview inline fields ([#143](https://github.com/litstack/litstack/pull/143), [f44f333](https://github.com/litstack/litstack/commit/f44f333e22d98c23fa851228c43ac7a45b418939))
-   Added `appends` method to Crud show page ([#135](https://github.com/litstack/litstack/pull/135))
-   Added `info` method to Crud index page ([e69a6e8](https://github.com/litstack/litstack/commit/e69a6e8a1ef99637aecdccd637d8b23c79c1b1e3))
-   Added `translation` option to the crud meta macro ([#146](https://github.com/litstack/litstack/pull/146))
-   Added new `Listing` field ([#148](https://github.com/litstack/litstack/pull/148))
-   Added `only` method to wysiwyg ([3ad7a3c](https://github.com/litstack/litstack/commit/3ad7a3ca6cd2ff4939c13429844dba1560d2191f))
-   Added password reset functionality for litstack users ([#149](https://github.com/litstack/litstack/pull/149), [8b9c658](https://github.com/litstack/litstack/commit/8b9c6582dafaab6138343f2ec76393b60cccc0e5))
-   Added crud events ([#152](https://github.com/litstack/litstack/pull/152))
-   Added raw editing in WYSIWYG ([cf8d0ce](https://github.com/litstack/litstack/commit/cf8d0ce233eec29b2858931f20cc3bee2cf61191))([07cef8a](https://github.com/litstack/litstack/commit/07cef8af7fd77b4c6092b65bd852d0b57d2d2343))
-   Added optional system info page ([#150](https://github.com/litstack/litstack/pull/150))

### Fixed

-   Fixed sort icon for index tables ([4cead36](https://github.com/litstack/litstack/commit/4cead3652f6f078d42531092f2562f8b5d5bd787))
-   Fixed table column `date` method casting `null` as carbon instance ([338dae9](https://github.com/litstack/litstack/commit/338dae90d575a960206ea8ca100390e9cae7428a))
-   Fixed crud model binding ([#141](https://github.com/litstack/litstack/pull/141))
-   Fixed script mime types ([#142](https://github.com/litstack/litstack/pull/142))
-   Fixed missing translations ([#145](https://github.com/litstack/litstack/pull/145))
-   Fixed nested block fields ([#144](https://github.com/litstack/litstack/pull/144))
-   Fixed tags in breadcrump navigation ([dd16a1a](https://github.com/litstack/litstack/commit/dd16a1a227621a5e869aee2b39dec40b3e75dd06))
-   Fixed duplicate change password link in user profile ([#151](https://github.com/litstack/litstack/pull/151))

### Changed

-   Changed field constructor ([#147](https://github.com/litstack/litstack/pull/147))
-   Disabled default input and paste rules for markdown in wysiwyg field ([3d01fa0](https://github.com/litstack/litstack/commit/3d01fa012f02cab2f312e5759eb3837e73986c37))

## [v3.2.5](https://github.com/litstack/litstack/compare/v3.2.4...v3.2.5)

### Added

-   Added `confirmDelete` method to block field ([#131](https://github.com/litstack/litstack/pull/131))

## [v3.2.4](https://github.com/litstack/litstack/compare/v3.2.3...v3.2.4)

### Added

-   Added `allowEmpty` method to route field ([#129](https://github.com/litstack/litstack/pull/129))

## [v3.2.3](https://github.com/litstack/litstack/compare/v3.2.2...v3.2.3)

### Added

-   Added `whenIn` field condition method ([#125](https://github.com/litstack/litstack/pull/125))

### Fixed

-   Fixed authentication with custom guard ([#103](https://github.com/litstack/litstack/issues/103), [#128](https://github.com/litstack/litstack/pull/128))
-   Fixed modal bug for multiple image fields ([ad56d49](https://github.com/litstack/litstack/commit/ad56d4911dee16b47750be49e3c27acc7b5a96c9))

## [v3.2.2](https://github.com/litstack/litstack/compare/v3.2.1...v3.2.2)

### Added

-   Added PHP 8.0 support ([#126](https://github.com/litstack/litstack/pull/126))

### Fixed

-   Fixed sluggable stub ([f8515e8](https://github.com/litstack/litstack/commit/f8515e8d912acfd1a3cc12648aa45bb1f1f8b000))
-   Fixed `manyRelation` and `oneRelation` field ([384f5e4](https://github.com/litstack/litstack/commit/384f5e4de12ed2d5e00cfe50862f5476c512c235), [2f04d4e](https://github.com/litstack/litstack/commit/2f04d4e22698aa6ad67d8ff5f76693bf31f2b52b))

## [v3.2.1](https://github.com/litstack/litstack/compare/v3.2.0...v3.2.1)

### Fixed

-   Fixed media field crop ([#123](https://github.com/litstack/litstack/issues/123), [c4542cb](https://github.com/litstack/litstack/commit/c4542cb3028f1f229ab8d679f7dccd2873aac285))

## [v3.2.0](https://github.com/litstack/litstack/compare/v3.1.3...v3.2.0)

### Added

-   Added table column fields :fire: ([5e76e3f](https://github.com/litstack/litstack/commit/5e76e3ffb47a0bd803c79c54487d972d7a16fb8e))
-   Added changelog ([949ed52](https://github.com/litstack/litstack/commit/949ed5224da968500780f91f45b596268c9f6613))
-   Added `resource` method to `Ignite\Crud\Models\LitFormModel` ([#97](https://github.com/litstack/litstack/issues/97), [#100](https://github.com/litstack/litstack/pull/100))
-   Added `right` method to `datetime` field ([#96](https://github.com/litstack/litstack/pull/96))
-   Added boolean support for table column value options ([
    129f722](https://github.com/litstack/litstack/commit/129f722e26f5af7f386cc46e2b4aac9fe783ea49))
-   Added datetime field methods `minuteInterval` and `disableHours` ([#110](https://github.com/litstack/litstack/pull/110))
-   Added medialibrary 9 support ([720290d](https://github.com/litstack/litstack/commit/720290d4aec58d7811bb7b7dea4b9ad56e00be34))
-   Added wysiwyg field settings to `lit` config ([#120](https://github.com/litstack/litstack/pull/120))
-   Images can now be recropped after upload ([#108](https://github.com/litstack/litstack/pull/108))

### Fixed

-   Fixed relation form modal closing after saving ([62db77e](https://github.com/litstack/litstack/commit/62db77e92fe5b29d7fdd27393e7e8c3a41f4573d))
-   Fixed installation issue with custom permissions tables names ([#105](https://github.com/litstack/litstack/issues/105), [#106](https://github.com/litstack/litstack/pull/106))
-   Fixed image column `square` ([#95](https://github.com/litstack/litstack/pull/95))
-   Fixed crud config route binding ([9a2f5db](https://github.com/litstack/litstack/commit/9a2f5dbe2c6801d7b164a6ce57b564a394d68e2a))

### Changed

-   Moved litstack core ServiceProvider's to the config `lit.providers` ([#99](https://github.com/litstack/litstack/issues/99), [#101](https://github.com/litstack/litstack/pull/101))
-   Improvid ordering performance ([a7558cb](https://github.com/litstack/litstack/commit/a7558cbf014d2c58f0655cd3b25a60bce29f8db5))
