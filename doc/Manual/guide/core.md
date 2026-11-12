# Core

The root namespace holds a few classes used across the library: the environment, a classic
autoloader, deprecation handling and the `Renderable` interface. Besides, Composer loads some
global debug functions, and the `Exception` namespace offers exceptions with additional data.

## Environment

[`Env`](../api/Env.md) reads and sets environment variables and tells where the script runs:

```php
use CeusMedia\Common\Env;

$mode	= Env::get( 'APP_MODE', 'production' );          // environment variable or default
$host	= Env::getServerVariable( 'HTTP_HOST' );          // server or request variable
$ip		= Env::getServerVariable( 'REMOTE_ADDR', '127.0.0.1' );

Env::isCli();          // TRUE on the console
Env::isHeadless();     // TRUE if neither TERM nor DISPLAY is set, like in cron jobs
Env::checkIsCli();     // throws a RuntimeException outside the console
```

`getServerVariable()` reads the environment first, then `$_SERVER` – some SAPIs, like the built-in
web server (`php -S`), provide request variables in `$_SERVER` only. Non-scalar values are ignored.

`Env::load()` reads an INI file (like an `.env` file) into the environment. Keys of sections get
the section name as prefix, dots become underscores, booleans become `1` or `0`:

```ini
[db]
host = localhost
```

```php
Env::load( '.env' );          // sets db_host
echo Env::get( 'db_host' );   // localhost
Env::set( 'APP_MODE', 'dev' );
echo Env::getAllAsJson();     // all environment variables as JSON
```

Values are stored as strings, as given – `Env::get()` returns them without added quotes.

## Deprecation

[`Deprecation`](../api/Deprecation.md) marks code as deprecated, depending on the version of the
library: from the *warning version* on it triggers an `E_USER_WARNING`, from the *error version* on
an `E_USER_DEPRECATED` error, from the *exception version* on it throws a
[`Exception\Deprecation`](../api/Exception/Deprecation.md). Unset versions are skipped:

```php
use CeusMedia\Common\Deprecation;

class Example
{
	public function oldMethod(): void
	{
		Deprecation::getInstance()
			->setErrorVersion( '1.0' )
			->setExceptionVersion( '1.2' )
			->message( 'Use newMethod() instead' );
		$this->newMethod();
	}
}
```

The message gets the file and line of the caller of `oldMethod()` appended. The library compares
against its own version, read from `Common.ini` (1.1.1). Methods deprecated with an exception
version up to 1.1 already throw, like `CLI::checkIsCli()` – see [Upgrading](upgrading.md).

For your own library, extend the class and set your version in `onInit()`:

```php
use CeusMedia\Common\Deprecation;

class MyDeprecation extends Deprecation
{
	protected function onInit(): void
	{
		parent::onInit();
		$this->version	= '2.3.0';				//  version of your own library
	}
}

MyDeprecation::getInstance()
	->setErrorVersion( '2.3' )
	->setExceptionVersion( '3.0' )
	->message( 'Use newFunction() instead' );
//  E_USER_DEPRECATED: Use newFunction() instead, invoked in … on line …, triggered
```

## Loader

[`Loader`](../api/Loader.md) is an autoloader for classes without namespace, named after their path
(`My_Shop_Cart` in `My/Shop/Cart.php`). Composer is the way to load this library itself – the
loader is meant for older code:

```php
use CeusMedia\Common\Loader;

//  constructor: extensions, path, class prefix, log file, register (default: TRUE)
$loader	= new Loader( 'php', 'legacy/' );
$cart	= new My_Shop_Cart();                  //  loads legacy/My/Shop/Cart.php

//  only classes with prefix "Legacy_", which is removed from the file path
$loader	= Loader::create( ['php', 'inc'], 'legacy/', 'Legacy_' );
$loader->register();                           //  create() does not register by default
$loader->unregister();
```

`setLowerPath( TRUE )` uses lower case folder names, `setLogFile()` logs loaded files and
`setVerbose()` prints them (1: loaded files, 2: lookups too).

## Renderable

[`Renderable`](../api/Renderable.md) is an interface with a single method `render(): string`. It is
implemented by classes producing output, like [`UI\HTML\Tag`](../api/UI/HTML/Tag.md) and
[`ADT\URL`](../api/ADT/URL.md), and lets your own components be handled the same way:

```php
use CeusMedia\Common\Renderable;

class Badge implements Renderable
{
	public function __construct( protected string $label ) {}

	public function render(): string
	{
		return '<span class="badge">'.htmlspecialchars( $this->label ).'</span>';
	}
}
```

## Global functions

Composer loads `src/global.php`, which declares some global functions for quick debugging. They
print text on the console and HTML in the browser, using [`UI\DevOutput`](../api/UI/DevOutput.md):

| Function | Output |
|----------|--------|
| `print_m( $mixed, $sign, $factor, $return, $channel )` | readable dump of a value, with types (`[S]` string, `[A]` array, …) |
| `show( $mixed )` | short for `print_m()` |
| `print_j( $mixed, $sign, $factor, $return )` | value as indented JSON |
| `print_rx( $variable, $return )` | `print_r()` output, in `<xmp>` in the browser |
| `print_globals()` | all global variables, using `print_m()` |
| `remark( $text, $parameters, $break )` | text with parameters, like `Done: rows -> 3` |
| `xmp( $string, $dump )` | text, in `<xmp>` in the browser |
| `pre( $string, $dump )` | escaped text in `<pre>` – also on the console |
| `code( $string )` | text in `<code>` – also on the console |
| `showDOM( $node )` | structure of a DOM node |
| `dev( $content, $force, $flagKey )` | prints only if constant `CM_SHOW_DEV` (or the given flag) is set and true, or `$force` |

```php
$data	= ['name' => 'Alice', 'roles' => ['admin', 'editor'], 'active' => TRUE];
print_m( $data );
//  [S] name => Alice
//  [A] roles
//    [S] 0 => admin
//    [S] 1 => editor
//  [B] active => TRUE

$dump	= print_m( $data, NULL, NULL, TRUE );     // return instead of print
remark( 'Done', ['rows' => 3] );                  // Done: rows -> 3
```

## Exceptions

The `Exception` namespace has exceptions for typical errors. Nearly all of them extend
[`Exception\Runtime`](../api/Exception/Runtime.md) – and so PHP's `RuntimeException` – and share
these abilities, provided by traits in `Exception\Traits`:

| Trait | Methods |
|-------|---------|
| [`Creatable`](../api/Exception/Traits/Creatable.md) | static `create( $message, $code, $previous )`, fluent `setMessage()`, `setCode()` |
| [`Descriptive`](../api/Exception/Traits/Descriptive.md) | `setDescription()`, `setSuggestion()`, `getAdditionalProperties()` |
| [`Renderable`](../api/Exception/Traits/Renderable.md) | `render()` as plain text (console) or HTML, `setFormat()` (0: auto, 1: text, 2: HTML) |
| [`Jsonable`](../api/Exception/Traits/Jsonable.md) | `getJson()` with all properties, trace and previous exceptions |
| [`Serializable`](../api/Exception/Traits/Serializable.md) | `__serialize()`: exceptions can be passed to `serialize()` and `unserialize()`, e.g. to send them between services |

```php
use CeusMedia\Common\Exception\IO as IoException;

function readConfig( string $path ): string
{
	if( !file_exists( $path ) )
		throw IoException::create( 'Config file is missing' )
			->setResource( $path )
			->setDescription( 'The application needs a config file to start.' )
			->setSuggestion( 'Copy config.ini.dist to config.ini.' );
	return (string) file_get_contents( $path );
}

try{
	readConfig( 'config.ini' );
}
catch( IoException $e ){
	echo $e->getResource();       // config.ini
	echo $e->render();            // message, description, suggestion, resource, facts and trace
	$json	= $e->getJson();
}
```

```text
Exception:
- Message:     Config file is missing
- Code:        0
- Description: The application needs a config file to start.
- Suggestion:  Copy config.ini.dist to config.ini.
- Format:      0
- Resource:    config.ini

Facts:
- File:Line:   /path/to/script.php:14
- Class/Type:  IO (in CeusMedia\Common\Exception)
- rCanonical:  IOException/Common/CeusMedia

Trace:
…
```

!!! info "File and line of `create()`"
    `create()` sets file and line of the exception to the outermost call of the trace – the line
    in the main script, not the line of `create()` (line 14 above, the call of `readConfig()`).
    Use `new` to keep the place where the exception was thrown.

Plain text rendering is done by [`CLI\Exception\View`](../api/CLI/Exception/View.md), which also
works for any other `Throwable`; HTML by [`UI\HTML\Exception\View`](../api/UI/HTML/Exception/View.md).

### Available exceptions

| Exception | Use for | Additional data |
|-----------|---------|-----------------|
| [`Runtime`](../api/Exception/Runtime.md) | base of all others | – |
| [`IO`](../api/Exception/IO.md) | input/output errors | `getResource()` |
| [`FileNotExisting`](../api/Exception/FileNotExisting.md) | missing files, extends `IO` | `getResource()` |
| [`Logic`](../api/Exception/Logic.md) | logic errors (still a runtime exception) | `getSubject()` |
| [`Validation`](../api/Exception/Validation.md) | invalid input | `getErrors()`, `getForm()` |
| [`Access`](../api/Exception/Access.md) | denied access | `getObject()`, `getRule()`, `getSubject()` |
| [`SQL`](../api/Exception/SQL.md) | database errors | `getSQLSTATE()` |
| [`Template`](../api/Exception/Template.md) | missing template files or labels | `getFilePath()`, `getNotUsedLabels()` |
| [`HTTP\Client`](../api/Exception/HTTP/Client.md), [`HTTP\Server`](../api/Exception/HTTP/Server.md) | HTTP errors 4xx and 5xx | – |
| [`Conversion`](../api/Exception/Conversion.md) | failed conversions or transformations | – |
| [`Deprecation`](../api/Exception/Deprecation.md) | use of removed features | – |
| [`MissingExtension`](../api/Exception/MissingExtension.md) | missing PHP extensions | – |
| [`NotEnabled`](../api/Exception/NotEnabled.md), [`NotFound`](../api/Exception/NotFound.md), [`NotSupported`](../api/Exception/NotSupported.md) | disabled, missing or unsupported things | – |
| [`Data\Missing`](../api/Exception/Data/Missing.md), [`Data\InvalidTypeCast`](../api/Exception/Data/InvalidTypeCast.md) | missing data, invalid type casts | – |
| [`Data\Ambiguous`](../api/Exception/Data/Ambiguous.md) | several candidates instead of one; extends `\Exception`, no traits | `getVariants()` |

Exceptions with additional data take it as further constructor arguments after message, code and
previous exception, or by setters:

```php
use CeusMedia\Common\Exception\SQL as SqlException;
use CeusMedia\Common\Exception\Template as TemplateException;
use CeusMedia\Common\Exception\Validation as ValidationException;

throw new ValidationException( 'Invalid input', 0, NULL, ['email' => 'invalid'], 'register' );
throw new SqlException( 'Table missing', 1146, '42S02' );   // message, code, SQLSTATE, previous
throw new TemplateException( TemplateException::LABELS_MISSING, 'mail.html', ['title', 'text'] );
//  message: Template is missing "title", "text"
```

!!! warning "`create()` with other constructors"
    `create()` passes message, code and previous exception to the constructor. For `SQL` the third
    argument is the SQLSTATE, `Template` expects a code first – use `new` for these two.

[`Exception\Serializable`](../api/Exception/Serializable.md) is deprecated: use any exception of the
namespace instead, they are all serializable.
