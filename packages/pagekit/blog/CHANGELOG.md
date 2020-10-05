# Changelog

## 1.0.7 (July 10, 2019)

### Added
- Added new translations

## 1.0.6 (November 14, 2018)

### Added
- Added reCAPTCHA for comments

### Fixed
- Fixed closing <li> tag in pagination

## 1.0.5 (August 31, 2017)

### Fixed
- Fixed Gravatar for frontend comments

## 1.0.4 (June 29, 2017)

### Fixed
- Fixed MySQL <= 5.7.5 support (ONLY_FULL_GROUP_BY)

## 1.0.3 (January 16, 2017)

### Security
- Fixed API information leak, discovered by Mahmoud Reda

## 1.0.2 (June 29, 2016)

### Fixed
- Fixed access check for settings screen

### Removed
- Removed manual access check from controller actions

## 1.0.1 (May 12, 2016)

### Changed
- Smooth scroll for comment anchor links

### Fixed
- Fixed duplicated request occasionally caused by pagination

## 1.0.0 (April 13, 2016)

### Fixed
- Add roles check to feed action

## 0.11.2 (April 7, 2016)

### Fixed
- Fixed update URL after comment
- Fixed different prefixes with SQLLite
- Fixed SQLite collations

## 0.11.1 (April 1, 2016)

### Changed
- Use truncated post content as Open Graph description if not explicitly provided

### Fixed
- Fixed comments links
- Fixed wrapping sidebar if content in main column is to large
- Fixed anonymous comment author not editable
- Fixed adding posts with permission 'edit own'
- Fixed deleting posts with permission 'edit own'

## 0.11.0 (March 30, 2016)

### Added
- Added OpenGraph

### Fixed
- Fixed RSS feed links

## 0.10.2 (February 24, 2016)

### Added
- Added filter cache for posts and comments settings

### Changed
- Ensure newest comments are displayed first in comment administration

### Fixed
- Fixed post url in backend view
- Fixed comments count
- Fixed comments filters

## 0.10.1 (January 11, 2016)

### Added
- Added link to login page at comment section

### Changed
- Set comment ordering to DESC at admin panel
- Updated Vue resource

### Fixed
- Fixed comments permalink

## 0.10.0 (December 15, 2015)

### Changed
- Switched to Vuejs 1.0

## 0.9.3 (October 30, 2015)

### Changed
- Date is set to "now" on post copy

## 0.9.2 (October 14, 2015)

### Fixed
- Fixed date conversion to ISO8601
- Fixed feed charset

## 0.9.1 (October 8, 2015)

### Added
- Added sections tabs in post edit view

### Fixed
- Fixed comments margin
- Fixed "Require email" setting
- Fixed display of URLs with UTF8 characters

## 0.9.0 (September 10, 2015)

- Initial release
