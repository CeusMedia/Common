# Upgrading

This page lists the changes which may need changes in your code. All changes are listed in the
[changelog](../changelog.md).

## From 1.1.0 to 1.1.1

A bugfix and security release. Apart from secure defaults, changes of behaviour which would break
existing code are held back for 1.2. Please check:

| Change | What to do |
|--------|------------|
| `Net\Reader` and `Net\HTTP\CrossDomainProxy` verify TLS certificates and hosts by default. This also applies to classes reading URLs via `Net\Reader`, like `XML\DOM\UrlReader`, the RSS and Atom readers, `XML\FeedIdentifier` and `Net\Site\Crawler`. | Requests to hosts with self-signed or invalid certificates fail now. Fix the certificate or disable verification explicitly: `setVerifyPeer( FALSE )` and `setVerifyHost( FALSE )` on `Net\Reader`, `setVerifySsl( FALSE )` on `CrossDomainProxy`. |
| `Net\CURL` only allows HTTP and HTTPS, also for redirects. | Read local files directly instead of using `file://` URLs. |
| `Net\AtomServerTime` applied the time difference with the wrong sign. | Delete existing sync files (default `AtomServerTime.diff`). |
| `Net\Reader` sends the user agent `CeusMedia-Common/1.1 Net\Reader`. | Set your own with `setUserAgent()` if a service expects a certain one. |
| `Alg\Search\Binary::search()` returns `-1` if nothing is found. | Check for `-1`. |
| `FS\Folder\Editor::move()` switches the editor to the moved folder (`archive/<name>`), not to the target path. | Remove own workarounds, e.g. a new editor for the moved folder. |
| `Env::set()` and `Env::load()` store values without surrounding quotes. | Remove own workarounds stripping quotes from `Env::get()`. |
| `FS\File\Arc\Gzip` and `FS\File\Arc\TarGzip` write the gzip format (zlib before). Older files are still read. | Files written now can not be read by version 1.1.0 or older – update all applications sharing these files. |
| `FS\File\Backup::store()` saves the first backup as version 0. | |
| `Net\API\DDNSS::update()` throws a `RuntimeException` on failure instead of ending the script. | Catch the exception. |
| `FS\File\ICal\Parser` returns values as UTF-8 (they were converted to ISO-8859-1). | Remove own conversions. |
| `XML\DOM\SyntaxValidator` checks well-formedness only and does not load DTDs. | |
| `Net\API\Google\ClosureCompiler`, `Net\API\Google\Sitemap\Submit` and `Net\API\Premailer` are deprecated – their services have been shut down. | Remove their usage; they will throw a `DeprecationException` in 1.2. |

## From 1.0 to 1.1

Version 1.1 requires PHP 8.3 and declares strict types in all classes.

- **Strict types:** scalar values are no longer coerced inside the library. Passing an `int` where a
  `string` is expected raises a `TypeError`.
- **Native types:** return, parameter and property types were added where the doc blocks were
  unambiguous. Subclasses overriding such methods must declare compatible types, otherwise they will
  not load. Hook methods documented as "to be overridden" were left untyped.
- **No compatibility layer:** `src/compat8.php` and `src/compat9.php` for code written for 0.8.x and
  0.9.x are removed, as well as the old tools (Migrator, Compatibility, Go).
- **Exceptions instead of empty results:** failing compression in `FS\File\Arc\Bzip`, `FS\File\Arc\Gzip`,
  `FS\File\PHP\Encoder` and `Net\HTTP\Response\Compressor`, failing conversion in
  `Alg\Text\EncodingConverter` and several failing file operations throw exceptions.
- **Deprecated methods throw a `DeprecationException`:** `CLI::checkIsHeadless()` and
  `CLI::checkIsCli()` – use `Env::checkIsHeadless()`, `Env::isHeadless()`, `Env::checkIsCli()` and
  `Env::isCli()`; `Loader::registerNew()` – use `new Loader(…)` or `Loader::create(…)`.

Changes of single classes:

| Class | Change |
|-------|--------|
| `Alg\UnitParser` | `M`/`MB` and `G`/`GB` are powers of 10, `MiB` and `GiB` powers of 2, `kiB` is 1024. Lowercase `m` and `g` are not supported anymore. |
| `Net\HTTP\Request` | `isAjax()` checks the value of header `X-Requested-With` – use `isAjax( FALSE )` to check for presence only. |
| `FS\File\Arc\Tar` | the file mode of opened archives is an `int`. |
| `ADT\Collection\PriorityQueue` | `enqueue()` takes the priority as `int`. |
| `XML\DOM\Node` | `__get()` is typed `string\|array\|NULL`. |
| `UI\Image\Graphviz\Graph` | `renderOptions()` takes one merged options array. |
