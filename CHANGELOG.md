# Changelog

All notable changes to this project are documented in this file.
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html) since 1.0.0.
Releases before 1.0.0 used a four-part version scheme (e.g. 0.8.5.9) and contain breaking changes in patch releases.

Entries for releases up to 1.0.1 were reconstructed from git history, tags and GitHub release notes.

## [Unreleased]

### Changed
- `FS\Folder\Reader::getSize()` with a unit returns the size as number in that unit (`int`, or `float` when a precision is given) instead of a formatted string.
- `FS\File\Block\Reader::getBlock()` returns a string (`''` for missing blocks) instead of a list of lines.
- `Net\HTTP\Reader::getCurlInfo()` takes the info key as string (e.g. `http_code`), like `Net\CURL::getInfo()`.
- `FS\File\INI::remove()` returns `FALSE` instead of `NULL` if nothing was removed.
- `CLI\Server\Cron\Job::checkMaturity()` is public now.

### Added
- `XML\DOM\Node::removeChildAt()` removes a child node by its index.

### Fixed
- `CLI\Server\Cron\Daemon` failed because `Job::checkMaturity()` was not accessible.
- `FS\File\PHP\Encoder::decodeFile()` encoded instead of decoding, wrote into the source file and computed an empty target file name; `isEncoded()` read only part of the prefix.
- `FS\File\CSS\Theme\Minimizer` overwrote the prefix when setting the suffix.
- `FS\File\ICal\Builder` dropped the last piece of folded lines.
- `Alg\Search\Binary::search()` returned wrong positions and recursed endlessly on empty lists; it returns `-1` if nothing is found.
- `XML\DOM\FileEditor`: `removeNode()` did not remove the node; top-level nodes could not be addressed, and editing nested nodes replaced the whole tree by the edited subnode when saving.
- `Net\HTTP\Header\Field\Parser::parse()` failed when decoding qualified values was enabled.
- `Alg\Time\Duration::render()` did not shorten the rendered duration.
- `Alg\Text\Unicoder::isUnicode()` did not use `mb_check_encoding()` when available.
- `FS\File\Backup`: `store()` saved the first backup as version 1 instead of 0; requesting the latest version without any backup throws a `RuntimeException` now instead of failing on a missing version.
- Development: `FS\File\BackupTest` is part of the unit test suite again.

## [1.1.0] - 2026-11-02

### Breaking changes
- Require PHP ^8.3 (was ^8.1).
- All classes declare `strict_types=1`. Scalar values are no longer coerced silently inside the library; passing e.g. an `int` where a `string` is expected now raises a `TypeError` at the call site within the library.
- Native return, parameter and property types were added where the doc blocks were unambiguous. Subclasses overriding such methods without a return type will no longer load. Hook methods documented as "to be overridden" were left untyped on purpose.
- Removed backwards compatibility layer for 0.8.x (`src/compat8.php`, `src/compat9.php`) and the old tools (Migrator, Compatibility, Go) as well as the make file.
- `Alg\UnitParser`: `M`/`MB` and `G`/`GB` are powers of 10 now (were powers of 2), `MiB` and `GiB` are powers of 2 now (were powers of 10), `kiB` is 1024 now (was 1000). Lowercase `m` and `g` are not supported anymore. `K`/`KB` stay 1024. Whitespace between number and unit is allowed.
- `Net\HTTP\Request::isAjax()` checks the value of header `X-Requested-With` by default (`XMLHttpRequest` or custom values). Use `isAjax( FALSE )` to check for presence only.
- `FS\File\Arc\Tar`: file mode of opened archives is an `int` now (was the raw octal string).
- Failing compression in `FS\File\Arc\Bzip`, `FS\File\Arc\Gzip`, `FS\File\PHP\Encoder` and `Net\HTTP\Response\Compressor`, failing conversion in `Alg\Text\EncodingConverter` and several failing file operations throw exceptions now instead of returning empty strings or error codes.
- `ADT\Collection\PriorityQueue::enqueue()`: priority must be an `int` (`float` is not accepted anymore).
- `XML\DOM\Node::__get()` is typed `string|array|NULL` (was `mixed`).
- `UI\Image\Graphviz\Graph::renderOptions()` (protected) takes one merged options array instead of options and override options.
- Deprecated methods throw a `DeprecationException` now: `CLI::checkIsHeadless()` and `CLI::checkIsCli()` (use `Env::checkIsHeadless()`/`Env::isHeadless()` and `Env::checkIsCli()`/`Env::isCli()`), `Loader::registerNew()` (use `new Loader(…)` or `Loader::create(…)`).

### Added
- `Exception\Access` for access violations.
- `Exception\HTTP\Client` and `Exception\HTTP\Server` for HTTP client and server errors.
- `Net\HTTP\Request`: support for trusted proxies (`setTrustedProxies()`, `setDefaultTrustedProxies()`, `getRemoteAddress()`, `determineClientIp()`, `isIpInList()`); `X-Forwarded-For` is only evaluated for trusted proxies, which may be given as IPs or CIDR networks.
- `Net\HTTP\Request::isAjax()` accepts a list of custom header values.
- `Alg\UnitParser`: support for tera-, peta- and exabytes (`T`, `P`, `E` and their `B`/`iB` variants).
- `Exception\NotFound`.
- `UI\Image\Exif::getDictionary()` returns the EXIF facts as dictionary.
- `CLI\Downloader::downloadUrl()` accepts an `ADT\URL` as well as a string.
- `CLI\Server\Daemon` can be stopped by setting the new property `$shallQuit`.
- README with many more usage examples.

### Changed
- VCard classes (`ADT\VCard`, `FS\File\VCard\*`) upgraded to strict types; getters for formatted name, role and title return `?string`.
- `Alg\Validation\PredicateValidator` casts scalar arguments to the parameter types declared by the predicate, so string input from forms or configuration files keeps working with typed predicates.
- `Net\HTTP\Cookie`: path defaults to `/`.
- `XML\RSS\Builder`: `addItem()` is chainable now; `setChannelPair()` and `setChannelData()` return `static`.
- `CLI` reads terminal width, height and colors on each call instead of caching them at construction.
- `ADT\Collection\LevelMap` validates the divider given to the constructor; `UI\SVG\Chart` builds its SVG node via `UI\HTML\Tag`.
- Merged changes from branch 1.0.x; copyright year updated to 2026.
- Development: PHPStan v2 at level 6 with baseline (now also analysing the bundled XMPPHP library), PHPUnit 10 configuration as default.
- Composer: package description added. Development dependencies raised to PHPUnit ^10.5, PHPStan 2 (with strict rules 2), `ceus-media/database` 0.7, `ceus-media/doc-creator` 1.1 and `ceus-media/php-parser` 0.5; `minimum-stability` set to `dev` for development. The scripts `dev:install` and `dev:update` set `COMPOSER_ROOT_VERSION=1.1.x-dev`, and autoloading of the removed tools was dropped.

### Fixed
- `Alg\JS\Minifier::minify()` always failed with a `TypeError` at end of input.
- `ADT\Graph\Weighted::getPath()` called a non-existing method when more than one path was found.
- `Alg\Parcel\Packer::hasLargerPacket()` always returned `TRUE` for numeric packet names.
- `CLI\Prompt` threw "Could not create any terminal" exactly when a terminal could be opened.
- `CLI::getHeight()` and `CLI::getWidth()` always failed.
- `CLI\Fork\Server\Client\Abstraction::getResponse()` called itself endlessly.
- `CLI\Server\Cron\Parser` failed on ranges like `1-5`.
- `FS\File\CSV\Iterator` looped endlessly on blank lines.
- `FS\File\Arc\Tar`: file names of exactly 100 characters were read as empty; file permissions were written wrongly when saving an opened archive.
- `FS\Autoloader\Psr4` resolved a non-existing base directory to the file system root.
- `Net\HTTP\Reader::get()` and `post()` failed for every request with headers.
- `Net\HTTP\Response\Decompressor` failed on every decompression.
- `Net\HTTP\Request\Receiver` used the whole header line as IP when `X-Forwarded-For` was set.
- `Net\HTTP\Request::isAjax()` compared the header object instead of its value.
- `Net\HTTP\Cookie` and `Net\HTTP\PartitionCookie` could not be constructed without a path.
- `Net\HTTP\UploadErrorHandler::setMessages()` lost the upload error codes as keys.
- `Net\Site\MapCreator::createSitemap()` passed the verbose flag as wrong argument.
- `Net\Connectivity` failed when offline or on status change without callback.
- `UI\HTML\Elements::RadioLabel()` failed unless all optional arguments were given.
- `UI\DevOutput::printJson()` failed for non-string values.
- `XML\DOM\Storage` and `XML\OPML\Parser` failed on nested nodes.
- `Alg\Obj\Constant`: `getAll()` ignored the dictionary flag; `hasKey()` and `hasValue()` were swapped. `hasValue()` checks the constant values now (strictly, like `getKeyByValue()`), and `hasKey()` also finds numeric keys left after removing a prefix.
- `Alg\HtmlParser`: query results for attributes (`/@name`) return the attribute value; failed queries return an empty list.
- `Alg\Time\Converter::convertToTimestamp()` with incomplete date formats.
- `ADT\String_`: RTL detection and `split()` on invalid input.
- `CLI\Exception\View` and `Deprecation` failed on trace steps without class, file or line; `Deprecation` failed without readable INI file.
- `CLI\Question` failed when stdin could not be opened.
- Many hidden type coercion problems, e.g. floats used as integers (`str_repeat`, `usleep`, image functions) or `FALSE`/`NULL` results of failed function calls passed on as strings.

## [1.0.1] - 2025-03-15

### Added
- Static constructors on `FS\AbstractNode`, `FS\File` and `FS\Folder`.
- `ADT\URL` implements `Renderable` and `Stringable`.

### Changed
- Updated copyright year and composer files.

## [1.0.0] - 2025-02-10

### Breaking changes
- Require PHP ^8.1 (was >=7.4).
- `Net\HTTP\Request` is no longer a `ADT\Collection\Dictionary`; it is decoupled from the dictionary.
- Exception base classes `Exception\Abstraction` and `Exception\Interface_` removed; exceptions are built from traits now (see below). Exception constructors changed accordingly.
- Removed deprecated `Net\HTTP\Sniffer\Browser`, `Net\HTTP\Sniffer\OS` and `Net\SVN`.
- Changed deprecation handling (`Deprecation`, `Loader`).
- ADTs use less `mixed` types.

### Added
- Exception traits `Creatable`, `Descriptive`, `Jsonable`, `Renderable` and `Serializable`; exceptions render themselves using CLI or HTML exception views.
- New exceptions: `Conversion`, `Data\InvalidTypeCast`, `Data\Missing`, `Deprecation`, `FileNotExisting`, `MissingExtension`, `NotEnabled`, `NotSupported`.
- `Env` for handling of system environment variables.
- `ADT\Collection\PriorityQueue`.
- `ADT\Collection` is iterable, countable, filterable and mappable now.
- `ADT\Bitmask`: static construction from array of bits, `with()`, `without()`, implements `Stringable`.
- `ADT\JSON\Encoder`.
- `ADT\URL\Parts`.
- `Alg\Obj\MethodDocBlockInspector`.
- `Alg\Time\Clock`: constants.
- `CLI\Password` for hidden input on console.
- `CLI\Output\TableBorderTheme`.
- `Net\HTTP\Response`: negotiation of response compression.
- Re-imported from 0.8.6: `UI\HTML\Tree\*` (ArrayView, FolderCheckView, FolderView, LayerMenu, Menu, VariableDump) and `UI\SVG\*` (BarAcross, Chart, ChartData, PieGraph).

### Changed
- Development: PHPUnit 10, PHPStan level 6 on PHP 8.1, DocCreator 1.0.x; first classes declare strict types.
- Updated HTTP classes, exception page and output buffer; methods moved from file reader to `FS\File`.
- Many new and updated unit tests.

### Deprecated
- `Net\HTTP\Sniffer\Browser` and `Net\HTTP\Sniffer\OS` (removed in this release).

### Fixed
- `ADT\Collection\Dictionary::filterByKeyPrefix()`.
- Bugs in `Alg\Time\Clock`, `Alg\Time\Converter`, `CLI`, `Net\HTTP\Response`, `UI\HTML\Indicator` and `UI\HTML\PageFrame`.

## [0.9.1] - 2023-10-31

### Changed
- Applied complete Rector rule set for PHP 7.4.
- Refactored HTTP header section (`Net\HTTP\Header\Section`, `Net\HTTP\Header\Renderer`).
- Work towards PHPStan level 6; short array syntax everywhere.
- Badges in readme file.

### Fixed
- Option element in `UI\HTML\FormElements`.
- Bugs in `ADT\Collection\Dictionary`, `ADT\URL`, `Alg\Obj\MethodFactory`, `Alg\Time\Clock`, `FS\File\INI`, `Net\HTTP\Reader` and `Net\HTTP\Request`.
- Doc bug in `XML\Element`, migration bugs and tests.

## [0.9.0] - 2022-11-01

### Breaking changes
- Require PHP >=7.4 (was >=7.2).
- All classes use namespaces (`CeusMedia\Common\…`) and PSR-4 autoloading instead of PSR-0 class names like `ADT_List_Dictionary`. Global functions moved to `src/global.php`.
- Renamed: `ADT_List*` → `ADT\Collection*`, `Alg_Object_*` → `Alg\Obj\*`, `ADT_JSON_Formater` → `ADT\JSON\Pretty`, and reserved words got a suffix: `ADT\Null_`, `ADT\Object_`, `ADT\String_`, `Exception\Abstraction`, `Exception\Interface_`.
- Typed properties and typed method arguments.
- Removed (moved to own packages):
  - `DB_*` → `ceus-media/database`
  - `ADT_PHP_*` and `FS_File_PHP_Parser_*` → `ceus-media/php-parser`
  - `Alg_Math_*` → `ceus-media/math`
  - `Net_Mail_*` → `ceus-media/mail`
- Removed outdated classes, e.g. `Net_IMAP_*`, `Net_Memory_*`, `XML_WDDX_*`, `UI_HTML_Button_*`, some `UI_Image_Graph_*` and `ADT_Tree` node classes, `ADT_Cache_*`, `Net_HTTP_PageRank`, `UI_HTML_Informant`. `UI_HTML_Tree_*` and `UI_SVG_*` were left out and came back in 1.0.0.
- Removed deprecated code doc tags `@extends`, `@uses`, `@implements`, `@version`.

### Added
- Compatibility mode for projects based on 0.8.x: `require 'vendor/ceus-media/common/src/compat8.php';` maps old class names to the new namespaced classes (`compat9.php` vice versa). Not recommended for production.
- Rector configuration.

### Changed
- Development: PHPStan level 5 (started at level 1), PHPUnit 9, quality tool configurations moved to `tool/config`, Go tool deprecated.
- Improved base and PSR-4 autoloader.
- Code strictness improved throughout, according to PHPStan.

### Fixed
- Many bugs found while migrating the unit tests, e.g. in `ADT\JSON\Builder`, `Alg\HtmlParser`, `FS\File\Collection\*`, `FS\File\JSON\Reader`, `Net\HTTP\Cookie`, `Net\HTTP\Reader`, `UI\HTML\FormElements`, `UI\HTML\PageFrame`, `UI\Image\ThumbnailCreator`, `XML\DOM\*`.
- File check in `FS\File\Reader`.

## [0.8.6] - 2022-05-01

### Changed
- `Deprecation` is reusable now.
- Updated PHPStan, code cleanup.

### Removed
- Tests for deprecated classes.

### Fixed
- Type bug in `ADT_URL`.

## [0.8.5.9] - 2022-03-19

### Added
- Exception classes accept a previous exception.
- Extended object constant handling (`Alg_Object_Constant`).

### Fixed
- File check in `FS_File_Reader`.
- `FS_File_CSV_Iterator`.

## [0.8.5.8] - 2021-05-11

### Changed
- Replaced implementation of CSV reader and iterator (`FS_File_CSV_Reader`, `FS_File_CSV_Iterator`).
- Code quality improved to PHPStan level 1 in ADT, CLI and XML.

### Fixed
- Bugs in ADT lists and CLI found by tests and PHPStan; `FS_File_CSV_Iterator`.

## [0.8.5.7] - 2021-03-03

### Changed
- `Alg_Object_MethodFactory` extended by OOP style. **May break applications** using the method factory.
- Improved HTTP request handling (`Net_HTTP_Request`, `Net_HTTP_Request_Receiver`, `Net_HTTP_Header_Section`).
- Development: added PHPStan.

### Fixed
- Doc blocks of `UI_Template::render` and `UI_HTML_Tag`.

## [0.8.5.6] - 2021-01-30

### Added
- Support for PHP 8.
- `Net_HTTP_Request::getPath()`.

## [0.8.5.5] - 2020-11-06

### Breaking changes
- `FS_File_Permissions`: integer mode removed.

### Changed
- CLI output is suppressed for executions via crond.
- Cleanup of base filesystem classes.

### Fixed
- Notice on changing session name in `Net_HTTP_PartitionSession`.
- Compatibility of `FS_File::setPathName()`.
- Bugs in `CLI_Dimensions`, `CLI_Output` and `CLI_Output_Progress`.

## [0.8.5.4] - 2020-08-21

### Breaking changes
- `Net_HTTP_Response_Sender::send()`: parameter `$compression` removed; signature is `send( $sendLengthHeader = TRUE, $andExit = FALSE )`, compression is set on the sender instead.

### Added
- `CLI_Command_BackgroundProcess`.
- `CLI::checkIsHeadless()`.

### Changed
- Improved strictness of `Alg_Validation_Predicates`.
- `UI_DevOutput` uses constants.
- PHP parser keeps source code indents while parsing methods.

### Deprecated
- PHP parser classes (`ADT_PHP_*`, `FS_File_PHP_Parser_*`), extracted to `ceus-media/php-parser`.

### Fixed
- `Net_HTTP_Response`.

## [0.8.5.3] - 2020-06-21

### Added
- `CLI_Output_Table` and demo.

### Changed
- `Net_HTTP_Request::getAllFromSource()` returns an array by default.
- Extracted prefix filter from `ADT_List_Dictionary::getAll()`.
- Updates carried over from branch 0.9.

### Deprecated
- `FS_Folder_ServiceNameSortCheck`, `UI_HTML_Service_Table`, `UI_HTML_Service_Test`.

## [0.8.5.2] - 2020-06-14

### Added
- Support for forwarded requests and forwarded remote IP address.
- More HTTP methods in `Net_HTTP_Method`.

### Fixed
- `Net_HTTP_Request` is compatible to dictionary again.
- `FS_File_JSON_Reader` when reading into an array; `Net_HTTP_Response`.

## [0.8.5.1] - 2020-04-05

### Breaking changes
- Require PHP ~7.2; tests use PHPUnit 8+.

### Added
- `ADT_JSON_Parser` status; used in `FS_File_JSON_Reader`.
- Configurable states for `CLI_Output_Progress`.
- Migration tool; Go tool moved to `tool` folder.
- Code of conduct and issue templates.

### Changed
- Response sender prepared to handle compression differently.
- `UI_HTML_Tag`: improved chainability, return types and visibility.
- Compatibility of inheriting classes improved (`ADT_List_LevelMap`, `ADT_Graph_DirectedWeighted`, `Net_HTTP_PartitionCookie`, configuration reader, …). Hardened `ADT_Bitmask`.
- PSR-0 autoloader without namespace.
- Updated `Spyc` (YAML) to 0.6.2.
- Code style: LF line endings, no closing PHP tags, no SVN keywords.

### Deprecated
- `Net_HTTP_Request_Sender`, since not working with HTTPS.

### Fixed
- `FS_File_CSV_Iterator`, `FS_File_Writer`, `Net_HTTP_Response`, `Net_HTTP_Response_Sender`.

## [0.8.5] - 2020-01-17

### Added
- `ADT_JSON_Parser`.
- `CLI_Output_Progress`.
- CLI colors (including 256 colors) in `CLI_Color`.
- `CLI_Question`: static constructor and chainability.
- Demos.

### Changed
- PHP parser and unused variable finder support nullable parameters.
- Improved error information of JSON parser.

## [0.8.4.9] - 2019-05-14

### Changed
- `UI_HTML_Tag` implements `Renderable`.

### Fixed
- PSR-4 autoloader.

## [0.8.4.8] - 2019-02-14

### Added
- `Net_Connectivity`.
- `DB_PDO_Connection::getTables()`.

### Fixed
- `DB_PDO_Connection`.

## [0.8.4.7] - 2018-12-16

### Added
- `ADT_Bitmask`.
- `Net_HTTP_Method`, extracted from HTTP request.

### Changed
- `Net_HTTP_Request` uses `ADT_URL`.
- Improved error handling in JSON reader and PHP parsers.

### Removed
- Tests of deprecated classes.

### Fixed
- `ADT_URL`.

## [0.8.4.6] - 2018-11-30

### Added
- `ADT_Constant::getKeyByValue()`, `excludePath` on `getAll()`.
- Exit codes in `CLI_Command_Program` and `Alg_Object_MethodFactory`.

### Changed
- Replaced URL data type (`ADT_URL`, `ADT_URL_Compare`).

### Fixed
- POST requests in `Net_HTTP_Request_Sender`; `CLI_Command_ArgumentParser`.

## [0.8.4.5] - 2018-08-09

### Added
- `ADT_String::hyphenate()`.
- `Alg_Text_PascalCase`; updated camel case and snake case.
- Missing getters in mail classes.
- `Net_HTTP_Download` supports PHP via CGI/FastCGI.

### Deprecated
- All math classes (`Alg_Math_*`), use `ceus-media/math`.

### Removed
- Classes extracted to own repositories (JSON-RPC, socket stream client, …).

### Fixed
- `Alg_ID`, `Alg_Object_Constant`, `UI_HTML_Exception_View`.

## [0.8.4.4] - 2018-05-23

### Added
- `Net_HTTP_Status::isCode()`.

### Fixed
- `UI_Template`.

## [0.8.4.3] - 2018-04-04

### Added
- Class constants handler `Alg_Object_Constant`.

## [0.8.4.2] - 2018-03-08

### Added
- `CLI_Question` for asking questions; next-gen CLI classes (`CLI`, `CLI_Dimensions`, `Alg_Text_CamelCase`, …).

### Deprecated
- Mail classes (`Net_Mail_*`), use `ceus-media/mail`.

## [0.8.4.1] - 2018-03-01

### Added
- `CLI_Size` to get width and height of terminal.

### Changed
- Improved column enumeration in PDO table reader and writer.

### Deprecated
- Most database classes (`DB_*`), use `ceus-media/database`.

## [0.8.4] - 2018-02-19

### Changed
- CLI argument parser supports commands; changed behavior of CLI request receiver.
- `UI_HTML_PageFrame` allows favicon formats other than ICO.

### Fixed
- Duplicate status 424 in `Net_HTTP_Status`.

## [0.8.3.9] - 2018-01-25

### Changed
- `UI_Image_Captcha` can return binary image content.
- License in composer file.

## [0.8.3.8] - 2018-01-15

### Changed
- Updated exception page and its assets.
- Count methods return integers.

## [0.8.3.7] - 2017-10-16

### Added
- Static notify method in deprecation handler.

### Changed
- JSON pretty printer uses built-in pretty print; method `convert` renamed to `encode` (alias kept).

## [0.8.3.6] - 2017-10-08

### Added
- New deprecation handler (`Deprecation`).

### Changed
- Improved download header handling.

## [0.8.3.5] - 2017-08-12

### Added
- Premailer API class.

### Changed
- HTTP header parser returns header section instead of list of header fields.
- Removed references deprecated in PHP 7.0.

## [0.8.3.4] - 2017-05-09

### Added
- HTTP header parsers and renderers.
- File lock class `FS_File_Lock`.
- Snake case strings.

### Changed
- Improved folder and file permissions; Gravatar uses HTTPS.

### Fixed
- Full HTTP headers: skip HTTP status line; missing rule for bytes in unit parser.

## [0.8.3.3] - 2017-04-10

### Fixed
- `DB_PDO_Table`.

## [0.8.3.2] - 2017-03-16

### Added
- `UI_Image::render()`.

### Fixed
- `DB_PDO_Table`.

## [0.8.3.1] - 2017-02-28

### Added
- Duration converter (time algorithm).
- Prototype of PDF-to-image converter.

### Fixed
- `DB_PDO_Table`.

## [0.8.3] - 2017-02-24

### Added
- Method to check if file is writable.
- PDO table reader: operator `BETWEEN`.

### Changed
- Parameters of `getByIndex()` and `getByIndices()`.

## [0.8.2.7] - 2016-12-21

### Added
- PDO table reader: `countFast()` for large tables.
- `ADT_String`: right-to-left detection.
- `print_j()` for JSON output.

### Changed
- HTTP header field keys and values are trimmed; count always returns integer.

## [0.8.2.6] - 2016-10-18

### Added
- Method to check HTTP method of received request.
- HTTP response sender delivers several header fields of same key.

## [0.8.2.5] - 2016-10-17

### Added
- Parser for HTTP headers.
- Path detection in request receiver.

## [0.8.2.4] - 2016-09-21

### Added
- `UI_DevOutput::printJson()` and alias `print_j()`.
- POST request body may be a string already (e.g. JSON).
- PHP parsers support reference methods.

## [0.8.2.3] - 2016-07-25

### Changed
- POST request maximum data length unlimited, with method to set length.

## [0.8.2.2] - 2016-05-31

### Changed
- Compatibility script checks for existing libraries; DocCreator tool checks for configuration section.

## [0.8.2.1] - 2016-05-26

### Added
- Trigger support for PHP parser.

### Fixed
- HTTP request on console, where `getallheaders()` is not available.

## [0.8.2] - 2016-04-20

### Added
- Zip archive support.
- Message ID in mail class.
- Abstract database table class.
- `trimLeft()` in text trimmer, using trim with new mode parameter.
- Clock laps with description.
- Compatibility script for projects still using the outdated SVN repositories.
- README.md and .gitignore.

### Changed
- Loader class renamed from `CMC_Loader` to `Loader`; removed version constant `CMC_VERSION`.
- `UI_HTML_Tag::setContent()` accepts stringable objects.
- cURL: improved response header handling; no follow-location when `open_basedir` or safe mode is set.
- Improved CDATA handling and exception trace rendering.
- Mail body chunk size 76.
- Improved method signatures.

### Fixed
- SMTP: SSL transport, HELO to SMTP host instead of localhost.
- `Net_HTTP_Request::getHeader()`; site crawler; text extender.

## [0.8.1] - 2015-06-05

### Breaking changes
- Restructured class folders; class files use extension `.php` instead of `.php5`.
- Net service classes removed in favour of `ceus-media/net-services`.
- Deprecated classes removed.

### Added
- Composer package `ceus-media/common` with PSR autoloader classes.
- Configuration for DocCreator.

### Changed
- `UI_HTML_Tag` can stringify objects.
- Google Maps geocoder checks query limit.
- Updated code doc (`@package`, `@category`, `@link`, `@copyright`).

[Unreleased]: https://github.com/CeusMedia/Common/compare/1.1.0...HEAD
[1.1.0]: https://github.com/CeusMedia/Common/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/CeusMedia/Common/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/CeusMedia/Common/compare/0.9.1...1.0.0
[0.9.1]: https://github.com/CeusMedia/Common/compare/0.9.0...0.9.1
[0.9.0]: https://github.com/CeusMedia/Common/compare/0.8.6...0.9.0
[0.8.6]: https://github.com/CeusMedia/Common/compare/0.8.5.9...0.8.6
[0.8.5.9]: https://github.com/CeusMedia/Common/compare/0.8.5.8...0.8.5.9
[0.8.5.8]: https://github.com/CeusMedia/Common/compare/0.8.5.7...0.8.5.8
[0.8.5.7]: https://github.com/CeusMedia/Common/compare/0.8.5.6...0.8.5.7
[0.8.5.6]: https://github.com/CeusMedia/Common/compare/0.8.5.5...0.8.5.6
[0.8.5.5]: https://github.com/CeusMedia/Common/compare/0.8.5.4...0.8.5.5
[0.8.5.4]: https://github.com/CeusMedia/Common/compare/0.8.5.3...0.8.5.4
[0.8.5.3]: https://github.com/CeusMedia/Common/compare/0.8.5.2...0.8.5.3
[0.8.5.2]: https://github.com/CeusMedia/Common/compare/0.8.5.1...0.8.5.2
[0.8.5.1]: https://github.com/CeusMedia/Common/compare/0.8.5...0.8.5.1
[0.8.5]: https://github.com/CeusMedia/Common/compare/0.8.4.9...0.8.5
[0.8.4.9]: https://github.com/CeusMedia/Common/compare/0.8.4.8...0.8.4.9
[0.8.4.8]: https://github.com/CeusMedia/Common/compare/0.8.4.7...0.8.4.8
[0.8.4.7]: https://github.com/CeusMedia/Common/compare/0.8.4.6...0.8.4.7
[0.8.4.6]: https://github.com/CeusMedia/Common/compare/0.8.4.5...0.8.4.6
[0.8.4.5]: https://github.com/CeusMedia/Common/compare/0.8.4.4...0.8.4.5
[0.8.4.4]: https://github.com/CeusMedia/Common/compare/0.8.4.3...0.8.4.4
[0.8.4.3]: https://github.com/CeusMedia/Common/compare/0.8.4.2...0.8.4.3
[0.8.4.2]: https://github.com/CeusMedia/Common/compare/0.8.4.1...0.8.4.2
[0.8.4.1]: https://github.com/CeusMedia/Common/compare/0.8.4...0.8.4.1
[0.8.4]: https://github.com/CeusMedia/Common/compare/0.8.3.9...0.8.4
[0.8.3.9]: https://github.com/CeusMedia/Common/compare/0.8.3.8...0.8.3.9
[0.8.3.8]: https://github.com/CeusMedia/Common/compare/0.8.3.7...0.8.3.8
[0.8.3.7]: https://github.com/CeusMedia/Common/compare/0.8.3.6...0.8.3.7
[0.8.3.6]: https://github.com/CeusMedia/Common/compare/0.8.3.5...0.8.3.6
[0.8.3.5]: https://github.com/CeusMedia/Common/compare/0.8.3.4...0.8.3.5
[0.8.3.4]: https://github.com/CeusMedia/Common/compare/0.8.3.3...0.8.3.4
[0.8.3.3]: https://github.com/CeusMedia/Common/compare/0.8.3.2...0.8.3.3
[0.8.3.2]: https://github.com/CeusMedia/Common/compare/0.8.3.1...0.8.3.2
[0.8.3.1]: https://github.com/CeusMedia/Common/compare/0.8.3...0.8.3.1
[0.8.3]: https://github.com/CeusMedia/Common/compare/0.8.2.7...0.8.3
[0.8.2.7]: https://github.com/CeusMedia/Common/compare/0.8.2.6...0.8.2.7
[0.8.2.6]: https://github.com/CeusMedia/Common/compare/0.8.2.5...0.8.2.6
[0.8.2.5]: https://github.com/CeusMedia/Common/compare/0.8.2.4...0.8.2.5
[0.8.2.4]: https://github.com/CeusMedia/Common/compare/0.8.2.3...0.8.2.4
[0.8.2.3]: https://github.com/CeusMedia/Common/compare/0.8.2.2...0.8.2.3
[0.8.2.2]: https://github.com/CeusMedia/Common/compare/0.8.2.1...0.8.2.2
[0.8.2.1]: https://github.com/CeusMedia/Common/compare/0.8.2...0.8.2.1
[0.8.2]: https://github.com/CeusMedia/Common/compare/0.8.1...0.8.2
[0.8.1]: https://github.com/CeusMedia/Common/compare/0.7.6...0.8.1
