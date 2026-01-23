# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

License: MIT.

## [Unreleased]

### Added
- Documentation improvements and minor internal refactors.

## [1.0.8] - 2026-01-23

### Added
- Official compatibility declaration for **PrestaShop 9**.
- New translations added: English (en), French (fr), Spanish (es), German (de), Polish (pl), Portuguese (pt), Romanian (ro).
- README updated with installation, configuration and troubleshooting guidance.

### Fixed
- Validation and static-analysis fixes:
  - Resolved negated-count warning in `postProcess()` (replaced `!count(...)` with `count(...) === 0`).
  - Ensured error message variable is always defined before calling `displayError()`.
  - Cleaned up corrupted characters in translation files (notably `pl.php`).
- License and header updates: module and template file docblocks updated to reference the **MIT** license and harmonised copyright years.
- TPL header/comment harmonisation to use the module translation convention ({l s="" mod='freega4'}).

### Changed
- Bumped module version to 1.0.8.
- Minor code-style cleanups and comment improvements for better static-analysis compatibility.

## [1.0.7] - 2024-12-21

### Added
- Optional **Vanilla JS** implementation for the `add_to_cart` event (beta) to avoid relying on jQuery.

### Changed
- General compatibility improvements for PrestaShop versions supported by the module.

## [1.0.5] - 2023-06-15

### Added
- Module configuration page in Back Office.
- Enable/disable toggle.
- Field to configure **GA4 Measurement ID** (G-XXXXXXXXXX).

## [1.0.1] - 2023-02-17

### Added
- PrestaShop 8.0 compatibility update.
- Basic **GA4 e-commerce events** integration:
  - Product view tracking (via `displayFooterProduct`).
  - Purchase tracking on order confirmation (via `orderConfirmation`).
  - Add-to-cart tracking script injection (via `displayFooter`).

### Changed
- Hook migration:
  - Unregistered legacy hooks: `header`, `backOfficeHeader`.
  - Registered modern hooks: `displayHeader`, `displayFooterProduct`, `displayFooter`, `orderConfirmation`.

[1.0.8]: https://github.com/TECN0ACQUISTI/freega4/releases/tag/1.0.8
[1.0.7]: https://github.com/TECN0ACQUISTI/freega4/releases/tag/1.0.7
[1.0.5]: https://github.com/TECN0ACQUISTI/freega4/releases/tag/1.0.5
[1.0.1]: https://github.com/TECN0ACQUISTI/freega4/releases/tag/1.0.1
