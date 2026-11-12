# Algorithms

The `Alg` namespace collects algorithms and helpers working on strings, numbers and lists:
case conversion, trimming, sorting and searching, colour conversion, unit and duration formatting,
validation, password strength, IDs and random strings, HTML parsing and more.
Most classes offer static methods.

## Text

### Case conversion

[`Alg\Text\CamelCase`](../api/Alg/Text/CamelCase.md), [`Alg\Text\PascalCase`](../api/Alg/Text/PascalCase.md)
and [`Alg\Text\SnakeCase`](../api/Alg/Text/SnakeCase.md) encode words into identifiers, decode them and convert between each other:

```php
use CeusMedia\Common\Alg\Text\CamelCase;
use CeusMedia\Common\Alg\Text\PascalCase;
use CeusMedia\Common\Alg\Text\SnakeCase;

CamelCase::encode( 'hello big world' );         // helloBigWorld
CamelCase::encode( 'user_first_name' );         // userFirstName
CamelCase::encode( 'HTTP request' );            // httpRequest
CamelCase::decode( 'helloBigWorld' );           // hello big world
CamelCase::toSnakeCase( 'helloBigWorld' );      // hello_big_world
CamelCase::validate( 'HelloBigWorld' );         // FALSE

PascalCase::encode( 'hello big world' );        // HelloBigWorld
PascalCase::toCamelCase( 'HelloBigWorld' );     // helloBigWorld

SnakeCase::toCamelCase( 'hello_big_world' );    // helloBigWorld
SnakeCase::toPascalCase( 'hello_big_world' );   // HelloBigWorld
```

By default, `encode()` lowercases the words first; `CamelCase::encode( 'HTTP request', FALSE )` keeps
the letters (`hTTPRequest`). `SnakeCase::encode()` only replaces spaces by underscores and keeps the case.

### Trimming and padding

[`Alg\Text\Trimmer`](../api/Alg/Text/Trimmer.md) cuts long strings to a maximum length, including a mask.
It is multibyte safe (UTF-8 by default):

```php
use CeusMedia\Common\Alg\Text\Extender;
use CeusMedia\Common\Alg\Text\Trimmer;

Trimmer::trim( 'Schöne Grüße aus Leipzig', 15 );            // Schöne Grüße...
Trimmer::trim( 'Schöne Grüße aus Leipzig', 15, '…', TRUE ); // …ße aus Leipzig
Trimmer::trimCentric( 'Schöne Grüße aus Leipzig', 15 );     // Schöne...eipzig
Trimmer::trimLeft( 'Schöne Grüße aus Leipzig', 15 );        // ... aus Leipzig

Extender::extend( 'Grüße', 8 );                 // "Grüße   "
Extender::extend( '42', 6, TRUE, '0' );         // 000042
Extender::extendCentric( 'Title', 11, '-' );    // ---Title---
```

An HTML entity as mask, like `&hellip;`, counts as one character. A mask longer than the length throws an
`InvalidArgumentException`. [`Alg\Text\Extender`](../api/Alg/Text/Extender.md) is the counterpart: it pads strings
multibyte safe.

### Encodings

```php
use CeusMedia\Common\Alg\Text\EncodingConverter;
use CeusMedia\Common\Alg\Text\Unicoder;

Unicoder::isUnicode( 'Grüße' );                 // TRUE
Unicoder::isUnicode( "Gr\xFC\xDFe" );           // FALSE – ISO-8859-1
Unicoder::convertToUnicode( "Gr\xFC\xDFe" );    // Grüße – UTF-8 strings stay unchanged

$latin1	= EncodingConverter::convert( 'Grüße', 'UTF-8', 'ISO-8859-1' );
```

[`Alg\Text\Unicoder`](../api/Alg/Text/Unicoder.md) converts ISO-8859-1 to UTF-8.
[`Alg\Text\EncodingConverter`](../api/Alg/Text/EncodingConverter.md) uses `iconv` and throws an
`InvalidArgumentException` if the string cannot be converted.

### Filtering HTML

[`Alg\Text\Filter`](../api/Alg/Text/Filter.md) strips parts of HTML:

```php
use CeusMedia\Common\Alg\Text\Filter;

Filter::stripScripts( '<p>Hi<script>alert(1)</script></p>' );   // <p>Hi</p>
Filter::stripEventAttributes( '<p onclick="x()">Hi</p>' );      // <p>Hi</p>
Filter::stripStyles( '<style>p{}</style><p>Hi</p>' );           // <p>Hi</p> – also removes stylesheet links
Filter::stripTags( '<p>Hi <b>there</b></p>' );                  // Hi there
Filter::stripComments( 'a <!-- x --> b /* y */ c' );            // a  b  c
```

!!! warning
    These filters use regular expressions. They help to clean up content, but are no protection against
    cross-site scripting – escape output with `htmlspecialchars()` instead.

[`Alg\Text\TermExtractor`](../api/Alg/Text/TermExtractor.md) counts the words of a text
(`getTerms()`), optionally without words of a blacklist.

## Sorting

[`Alg\Sort\MapList`](../api/Alg/Sort/MapList.md) sorts lists of maps – like database rows – by one or several columns.
Entries with equal values keep their order:

```php
use CeusMedia\Common\Alg\Sort\MapList;

$products	= [
	['name' => 'Pen',		'price' => 1.5,	'stock' => 10],
	['name' => 'Paper',		'price' => 1.2,	'stock' => 0],
	['name' => 'Folder',	'price' => 2.0,	'stock' => 10],
];

$list	= MapList::sort( $products, 'price' );                    // Paper, Pen, Folder
$list	= MapList::sort( $products, 'price', MapList::DIRECTION_DESC );
$list	= MapList::sortByMultipleColumns( $products, [
	'stock'	=> MapList::DIRECTION_DESC,
	'name'	=> MapList::DIRECTION_ASC,
] );                                                               // Folder, Pen, Paper
```

The classic algorithms are available for learning and comparison – for production code, use PHP's `sort()`:

| Class | Usage |
|-------|-------|
| [`Alg\Sort\Bubble`](../api/Alg/Sort/Bubble.md) | `$sorted = Bubble::sort( $list )` |
| [`Alg\Sort\Selection`](../api/Alg/Sort/Selection.md) | `$sorted = Selection::sort( $list )` |
| [`Alg\Sort\Insertion`](../api/Alg/Sort/Insertion.md) | `$sorted = ( new Insertion() )->sort( $list )` |
| [`Alg\Sort\Gnome`](../api/Alg/Sort/Gnome.md) | `Gnome::sort( $list )` – sorts in place |
| [`Alg\Sort\Quick`](../api/Alg/Sort/Quick.md) | `Quick::sort( $list )` – sorts in place, lists with sequential keys only |

The returned lists are reindexed.

## Searching

Search classes find a value in a *sorted* list and return its position, or `-1`:

```php
use CeusMedia\Common\Alg\Search\Binary;
use CeusMedia\Common\Alg\Search\Interpolation;
use CeusMedia\Common\Alg\Search\Strange;

$sorted	= [1, 3, 5, 7, 9, 11];

( new Binary() )->search( $sorted, 7 );         // 3
( new Binary() )->search( $sorted, 4 );         // -1
( new Interpolation() )->search( $sorted, 9 );  // 4 – numbers only
( new Strange() )->search( $sorted, 11 );       // 5 – ternary search
```

[`Alg\Search\Binary`](../api/Alg/Search/Binary.md) works for all comparable values,
[`Alg\Search\Interpolation`](../api/Alg/Search/Interpolation.md) for numbers,
[`Alg\Search\Strange`](../api/Alg/Search/Strange.md) divides the range into thirds.

## Colours

[`Alg\ColorConverter`](../api/Alg/ColorConverter.md) converts between HTML, RGB, HSV, CMY, CMYK and XYZ:

```php
use CeusMedia\Common\Alg\ColorConverter;

ColorConverter::html2rgb( '#F80' );             // [255, 136, 0] – also 'ff8800' or '#FF8800'
ColorConverter::rgb2html( [255, 136, 0] );      // FF8800
ColorConverter::rgb2hsv( [255, 136, 0] );       // [32, 100, 100] – hue in degrees, saturation and value in percent
ColorConverter::hsv2rgb( [32, 100, 100] );      // [255, 136, 0]
ColorConverter::hsv2html( [0, 100, 100] );      // FF0000
ColorConverter::rgb2cmyk( [255, 136, 0] );      // [0, 0.4666…, 1, 0] – values between 0 and 1
ColorConverter::rgb2cmyk( [0, 0, 0] );          // [0, 0, 0, 1]
```

`rgb2html()` returns uppercase codes without `#` and limits values to 0–255. Invalid HTML colours result in
`[NULL, NULL, NULL]`. Conversions from CMY and CMYK to RGB return floats – round them if needed.

## Units and durations

[`Alg\UnitFormater`](../api/Alg/UnitFormater.md) formats numbers of bytes, seconds and pixels with the best fitting unit.
It switches to the next unit when half of it is reached (`$edge = 0.5`). Byte units are binary (1 KB = 1024 B):

```php
use CeusMedia\Common\Alg\UnitFormater;
use CeusMedia\Common\Alg\UnitParser;

UnitFormater::formatBytes( 400 );               // 400 B
UnitFormater::formatBytes( 1536 );              // 1.5 KB
UnitFormater::formatBytes( 5000000, 2 );        // 4.77 MB
UnitFormater::formatSeconds( 90 );              // 1.5 m
UnitFormater::formatSeconds( 7200 );            // 2 h
UnitFormater::formatMilliSeconds( 1500 );       // 1.5 s
UnitFormater::formatPixels( 2500000 );          // 2.5 MP

UnitParser::parse( '2.5 MB' );                  // 2500000.0
UnitParser::parse( '4 KB' );                    // 4096.0
UnitParser::parse( '1k' );                      // 1000.0
```

!!! note "Units of the parser"
    [`Alg\UnitParser`](../api/Alg/UnitParser.md) reads `KB` and `K` as 1024, but `MB`, `GB` and larger as powers of 1000;
    use `MiB`, `GiB` … for binary units. Unknown units throw a `DomainException`.

[`Alg\Time\Duration`](../api/Alg/Time/Duration.md) parses and renders durations in weeks, days, hours, minutes and seconds:

```php
use CeusMedia\Common\Alg\Time\Duration;

Duration::parse( '1d 2h 30m' );                 // 95400
Duration::render( 95400 );                      // 1d 2h 30m
Duration::render( 95400, '' );                  // 1d2h30m
Duration::render( 95400, ' ', TRUE );           // 1d – shortened to the largest unit
Duration::render( 700000 );                     // 1w 1d 2h 26m 40s
```

[`Alg\Time\DurationPhraser`](../api/Alg/Time/DurationPhraser.md) turns durations into phrases like "2 hours ago".
Ranges start at a duration (`s`, `m`, `h`, `D`, `W`, `M`, `Y`); placeholders in braces are replaced by the duration in that unit:

```php
use CeusMedia\Common\Alg\Time\DurationPhraser;

$phraser	= DurationPhraser::fromArray( [
	'0s'	=> 'just now',
	'1m'	=> '{m} minutes ago',
	'1h'	=> '{h} hours ago',
	'1D'	=> '{D} days ago',
] );
$phraser->getPhraseFromSeconds( 20 );                       // just now
$phraser->getPhraseFromSeconds( 7200 );                     // 2 hours ago
$phraser->getPhraseFromTimestamp( time() - 3 * 86400 );     // 3 days ago
```

[`Alg\Time\Clock`](../api/Alg/Time/Clock.md) is a stopwatch with laps:

```php
use CeusMedia\Common\Alg\Time\Clock;

$clock	= new Clock();                       // starts immediately
// … setup …
$clock->stopLap( Clock::BASE_MILLI, 0, 'setup' );   // e.g. 20 – milliseconds, no decimals
// … work …
$total	= $clock->stop( Clock::BASE_MILLI, 0 );      // e.g. 30 – since start
$clock->getLaps();                          // [['time' => 20.0, 'timeMicro' => …, 'total' => 20.0, 'label' => 'setup', …]]
```

[`Alg\Time\Converter`](../api/Alg/Time/Converter.md) converts timestamps to and from German date formats (`date`, `datetime`, `time`, …).

## Validation

[`Alg\Validation\Predicates`](../api/Alg/Validation/Predicates.md) is a set of static checks on strings:

```php
use CeusMedia\Common\Alg\Validation\Predicates;

Predicates::isEmail( 'jane@example.com' );      // TRUE
Predicates::isUrl( 'https://example.com/a?b=1' );   // TRUE
Predicates::isDigit( '0815' );                  // TRUE
Predicates::isFloat( '3.14' );                  // TRUE
Predicates::isDate( '2026-11-12' );             // TRUE
Predicates::hasMinLength( 'abc', 5 );           // FALSE
Predicates::isGreater( '10', 5 );               // TRUE
```

!!! note
    `isAlpha()` accepts letters *and digits*, `isAlphahyphen()` and `isAlphaspace()` also hyphens or spaces.
    Use `isLetter()` for ASCII letters only.

[`Alg\Validation\DefinitionValidator`](../api/Alg/Validation/DefinitionValidator.md) validates a value against a
field definition and returns a list of failed predicates with their argument – an empty list means valid:

```php
use CeusMedia\Common\Alg\Validation\DefinitionValidator;

$definition	= [
	'syntax'	=> [
		'mandatory'	=> TRUE,
		'class'		=> 'digit',     // predicate isDigit
		'minlength'	=> 4,
		'maxlength'	=> 5,
	],
	'semantic'	=> [
		['predicate' => 'isGreater', 'edge' => '999'],
	],
];
$validator	= new DefinitionValidator();
$validator->validate( $definition, '1234' );    // []
$validator->validate( $definition, '0815' );    // [['isGreater', '999']]
$validator->validate( $definition, '' );        // [['isMandatory', NULL]]
```

[`Alg\Validation\PredicateValidator`](../api/Alg/Validation/PredicateValidator.md) calls a single predicate by name
(`validate( $value, 'hasMaxLength', 5 )`).
[`Alg\Validation\LanguageValidator`](../api/Alg/Validation/LanguageValidator.md) picks an allowed language from an
`Accept-Language` header:

```php
use CeusMedia\Common\Alg\Validation\LanguageValidator;

LanguageValidator::validate( 'de-DE,de;q=0.9,en;q=0.8', ['en', 'de'] );    // de
LanguageValidator::validate( 'fr', ['en', 'de'] );                         // en – the default (first allowed)
```

## Passwords, IDs and random strings

```php
use CeusMedia\Common\Alg\Crypt\PasswordStrength;
use CeusMedia\Common\Alg\ID;
use CeusMedia\Common\Alg\Randomizer;

PasswordStrength::getStrength( 'password' );        // -193 – common passwords are negative
PasswordStrength::getStrength( 'Tr0ub4dor' );       // 50
PasswordStrength::getStrength( 'x7#Kp!2qLm9$' );    // 100 – maximum
PasswordStrength::getScore( 'Tr0ub4dor' );          // 28 – raw score, maximum 56

$uuid	= ID::uuid();                        // e.g. A7AA86E4-C85A-4267-864E-8991DA491307 (version 4)
ID::isUuid( $uuid );                        // TRUE

$randomizer	= new Randomizer();
$randomizer->get( 12 );                     // e.g. kw!bPRJ2)y7Q – digits, letters and signs
$randomizer->get( 12, 50 );                 // with a password strength of at least 50
$randomizer->configure( TRUE, TRUE, TRUE, FALSE, 0 );   // digits, small and large letters, no signs
$randomizer->get( 16 );                     // e.g. CW8SVTBkzjM5nRID
```

[`Alg\Crypt\PasswordStrength`](../api/Alg/Crypt/PasswordStrength.md) rates length, character classes and known
weak passwords. [`Alg\ID`](../api/Alg/ID.md) creates random UUIDs (version 4) using `random_int()`.
By default, [`Alg\Randomizer`](../api/Alg/Randomizer.md) uses every character only once (`$randomizer->unique`);
set it to `FALSE` for strings longer than the character pool, otherwise an `UnderflowException` is thrown.

!!! warning "Signs and UTF-8"
    The default signs (`$randomizer->signs`) contain `§`, which is a two-byte character in UTF-8. Since the pool
    is split into bytes, strings with signs may contain broken UTF-8. Set `$randomizer->signs` to ASCII characters
    only, or disable signs by `configure()`.

[`Alg\Crypt\Caesar`](../api/Alg/Crypt/Caesar.md) and [`Alg\Crypt\Rot13`](../api/Alg/Crypt/Rot13.md) are
classic ciphers – for fun and puzzles, not for security:

```php
use CeusMedia\Common\Alg\Crypt\Caesar;
use CeusMedia\Common\Alg\Crypt\Rot13;

Caesar::encrypt( 'Hello World', 3 );            // Khoor Zruog
Rot13::encrypt( 'Hello' );                      // Uryyb
```

## HTML

[`Alg\HtmlParser`](../api/Alg/HtmlParser.md) loads HTML into a DOM document and reads typical information:

```php
use CeusMedia\Common\Alg\HtmlParser;

$html	= file_get_contents( 'index.html' );     // a page with title, meta tags, links …
$parser	= new HtmlParser();
$parser->parseHtml( $html );                // or parseHtmlFile( 'index.html' )

$parser->getTitle();                        // Demo page
$parser->getDescription();                  // A small demo
$parser->getKeywords();                     // ['php', 'demo', 'library']
$parser->getStyleSheetUrls();               // ['style.css']
$parser->getFavoriteIcon();                 // /favicon.ico
$parser->getTagById( 'top' );               // DOMElement
$parser->getTags( 'a', 'class', 'nav' );    // links with class "nav"
$parser->getLanguage( FALSE );              // '' – no Content-Language meta tag
```

The getters for title, description, keywords, language, icon and tag by ID throw a `RuntimeException` if nothing
is found – pass `FALSE` to get an empty value instead.

[`Alg\HtmlMetaTagReader`](../api/Alg/HtmlMetaTagReader.md) reads `name` and `http-equiv` meta tags from an HTML
string without DOM:

```php
use CeusMedia\Common\Alg\HtmlMetaTagReader;

HtmlMetaTagReader::getMetaTags( $html, HtmlMetaTagReader::TRANSFORM_LOWERCASE );
// ['description' => 'A small demo', 'keywords' => 'php, demo, library']
```

[`Alg\SgmlTagReader`](../api/Alg/SgmlTagReader.md) splits a single tag into node name, attributes and content:

```php
use CeusMedia\Common\Alg\SgmlTagReader;

SgmlTagReader::getTagData( '<a href="/home" class="nav">Home</a>' );
// ['nodename' => 'a', 'content' => 'Home', 'attributes' => ['href' => '/home', 'class' => 'nav']]
```

## Objects

| Class | Purpose |
|-------|---------|
| [`Alg\Obj\Constant`](../api/Alg/Obj/Constant.md) | reads class constants by prefix: `( new Constant( Order::class ) )->getAll( 'STATUS' )` returns `['NEW' => 0, 'PAID' => 1, …]`, `getKeyByValue( 2, 'STATUS' )` returns `SENT` |
| [`Alg\Obj\Factory`](../api/Alg/Obj/Factory.md) | creates objects by class name and arguments: `Factory::createObject( Order::class, ['A-1'] )` |
| [`Alg\Obj\MethodFactory`](../api/Alg/Obj/MethodFactory.md) | calls methods of objects or classes by name, checking existence and visibility |
| [`Alg\Obj\Delegation`](../api/Alg/Obj/Delegation.md) | composes objects and delegates calls to the first object having the method |
| [`Alg\Obj\EventHandler`](../api/Alg/Obj/EventHandler.md) | calls registered object or class methods on named events |
| [`Alg\Obj\MethodDocBlockInspector`](../api/Alg/Obj/MethodDocBlockInspector.md) | returns the doc block lines of a method |

`Alg\Obj\Constant::getKeyByValue()` throws an `Exception\Data\Ambiguous` if several constants have the value.

## More classes

??? info "Other classes in the Alg namespace"

    | Class | Purpose |
    |-------|---------|
    | [`Alg\JS\Minifier`](../api/Alg/JS/Minifier.md) | minifies JavaScript (JSMin): `Minifier::minify( $js )` |
    | [`Alg\Tree\Menu\Converter`](../api/Alg/Tree/Menu/Converter.md) | converts OPML outlines to a [tree menu](data-types.md) |
    | [`Alg\Turing\Machine`](../api/Alg/Turing/Machine.md) | Turing machine with one tape (see below) |
    | [`Alg\UnusedVariableFinder`](../api/Alg/UnusedVariableFinder.md) | finds assigned but unused variables in methods of PHP code |
    | [`Alg\Parcel\Packer`](../api/Alg/Parcel/Packer.md), [`Factory`](../api/Alg/Parcel/Factory.md), [`Packet`](../api/Alg/Parcel/Packet.md) | calculates packets and prices for articles of given volumes |
    | [`Alg\Time\DurationPhraseRanges`](../api/Alg/Time/DurationPhraseRanges.md) | ranges used by `DurationPhraser` |

??? info "Turing machine"

    Transitions map a state and a read symbol to a new state, a symbol to write and a direction (`l`, `r` or `n`).
    The machine halts in a final state, if no transition matches or after `$maxSteps` (default 200):

    ```php
    use CeusMedia\Common\Alg\Turing\Machine;

    $machine	= new Machine(
    	['q0', 'qf'],                           // states
    	['0', '1', '#'],                        // alphabet
    	[                                       // transitions: invert bits until blank
    		[['q0', '0'], ['q0', '1', 'r']],
    		[['q0', '1'], ['q0', '0', 'r']],
    		[['q0', '#'], ['qf', '#', 'n']],
    	],
    	'q0',                                   // start state
    	'#',                                    // blank
    	['qf']                                  // final states
    );
    $machine->run( '10110' );                   // 01001
    ```
