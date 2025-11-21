# Release Notes for Block Styles

## [Unreleased]

### Added
- Config caching: Added static `$configCache` property to both field types to avoid repeated file reads
- `allowEmpty` property: Added public `bool $allowEmpty = false` to both field types for controlling empty option behavior (backward compatible)
- Null checks: Added comprehensive validation for element, fieldId, field, and blockType before use
- Type hints: All private methods now have proper parameter and return type hints
- PHPDoc comments: Added documentation blocks for all private methods explaining parameters and return values

### Changed
- Code formatting: Improved consistency and readability throughout both field classes
- Replaced `array_push()` with `[]` syntax for better performance

### Fixed
- Potential null pointer exceptions when element or field properties are not set
- Missing type safety in private methods
