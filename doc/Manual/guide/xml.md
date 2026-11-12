# XML

The `XML` namespace reads, writes and queries XML: as a light tree of [`XML\DOM\Node`](../api/XML/DOM/Node.md)
objects, via XPath, or as an extended `SimpleXMLElement`. On top of that, it builds and reads RSS, Atom and OPML
feeds, calls XML-RPC services and runs XSL transformations.

| Task | Classes |
|------|---------|
| parse, build and edit XML trees | `XML\DOM\Parser`, `Node`, `Builder`, `FileReader`, `FileWriter`, `FileEditor` |
| check well-formedness | `XML\DOM\SyntaxValidator` |
| query with XPath | `XML\DOM\XPathQuery` |
| SimpleXML with extras | `XML\Element`, `XML\ElementReader` |
| settings file with dotted paths | `XML\DOM\Storage` |
| objects to XML and back | `XML\DOM\ObjectSerializer`, `ObjectDeserializer` |
| feeds | `XML\RSS\*`, `XML\Atom\Reader`, `XML\FeedIdentifier`, `XML\OPML\*` |
| remote calls, transformations | `XML\RPC\Client`, `XML\XSL\Transformator` |

## Parsing into a tree

[`XML\DOM\Parser`](../api/XML/DOM/Parser.md) turns an XML string into a tree of nodes and returns the root node.
Leading comments are skipped.

```php
use CeusMedia\Common\XML\DOM\Parser;

$xml	= <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<library name="City Library">
	<book id="b1" lang="en">
		<title>Dune</title>
		<author>Frank Herbert</author>
	</book>
	<book id="b2">
		<title>Solaris</title>
		<author>Stanisław Lem</author>
		<note><![CDATA[Path: C:\books\solaris & more]]></note>
	</book>
</library>
XML;

$parser	= new Parser();
$tree	= $parser->parse( $xml );								//  Node of <library>

echo $tree->getAttribute( 'name' ), PHP_EOL;					//  City Library
foreach( $tree->getChildren( 'book' ) as $book )
	printf( "%s: %s by %s (%s)\n",
		$book->getAttribute( 'id' ),
		$book->getChild( 'title' )->getContent(),
		$book->getChild( 'author' )->getContent(),
		$book->getAttribute( 'lang' ) ?? 'no language'
	);
echo $tree->getChildByIndex( 1 )->getChild( 'note' )->getContent();	//  Path: C:\books\solaris & more

echo $parser->getOption( 'encoding' );							//  UTF-8 (also: version, standalone)
$document	= $parser->getDocument();						//  the underlying DOMDocument
```

Invalid XML raises an `Exception` with the libxml messages. Since 1.1.1, backslashes in attribute values and
CDATA sections are kept as they are (they were removed before).

!!! warning "A tree of elements, not a full DOM"
    The node tree holds element names, attributes and text content – nothing else. An element with non-blank text
    gets its whole text content, including the text of nested elements, and **no** child nodes
    (`<p>Hello <b>World</b></p>` becomes a `p` node with content `Hello World`). Comments and processing
    instructions are dropped, and text directly inside the root element is not read. Use
    [XPath](#xpath) or [`XML\Element`](#simplexml-with-extras) for documents with mixed content.

### Nodes

A [`Node`](../api/XML/DOM/Node.md) has a name, attributes, children and an optional text content.
Setters return the node itself, `addChild()` returns the added child.

| Method | Purpose |
|--------|---------|
| `getNodeName()`, `setNodeName()` | element name |
| `getAttribute( $key )`, `getAttributes()`, `hasAttribute()`, `hasAttributes()` | attributes; missing attribute: `NULL` |
| `setAttribute( $key, $value )`, `removeAttribute( $key )` | change attributes (values are strings) |
| `getContent()`, `hasContent()`, `setContent()`, `removeContent()` | text content |
| `getChildren( $name = NULL )` | all children, or all with this name |
| `getChild( $name )` | first child with this name, otherwise `InvalidArgumentException` |
| `getChildByIndex( $index )` | child by position, starting at 0 |
| `hasChild( $name )`, `hasChildren()` | checks |
| `addChild( $node )`, `removeChild( $name )`, `removeChildAt( $index )` | change children; `removeChild()` removes the first match |

The magic properties `name`, `content` and `attributes` (and some aliases) are readable as well:
`$node->name`, `$node->attributes`.

!!! note "Check before using `getChildByIndex()`"
    For an index beyond the last child, `getChildByIndex()` may fail with a warning and a `TypeError` instead of
    the documented `InvalidArgumentException`. Check the index against `count( $node->getChildren() )` first.

### Building XML

Build a tree of nodes and render it by [`XML\DOM\Builder`](../api/XML/DOM/Builder.md). Content is escaped, the
output is indented.

```php
use CeusMedia\Common\XML\DOM\Builder;
use CeusMedia\Common\XML\DOM\Node;

$library	= new Node( 'library', NULL, ['name' => 'City Library'] );
$book		= $library->addChild( new Node( 'book', NULL, ['id' => 'b1'] ) );
$book->addChild( new Node( 'title', 'Dune' ) );
$book->addChild( new Node( 'author', 'Frank Herbert' ) );
$library->addChild( new Node( 'book' ) )
	->setAttribute( 'id', 'b2' )
	->addChild( new Node( 'title', 'Fish & Chips <Deluxe>' ) );

echo Builder::build( $library );
```

```xml
<?xml version="1.0" encoding="utf-8"?>
<library name="City Library">
  <book id="b1">
    <title>Dune</title>
    <author>Frank Herbert</author>
  </book>
  <book id="b2">
    <title>Fish &amp; Chips &lt;Deluxe&gt;</title>
  </book>
</library>
```

`build( $tree, $encoding = 'utf-8', $namespaces = [] )` declares namespaces on the root element, given as
prefix → URI. A node with children is rendered without its own content.

## Files

[`FileReader`](../api/XML/DOM/FileReader.md) and [`FileWriter`](../api/XML/DOM/FileWriter.md) combine parser and
builder with file access – statically or as instance. [`FileEditor`](../api/XML/DOM/FileEditor.md) changes single
nodes of a file and saves it right away.

```php
use CeusMedia\Common\XML\DOM\FileEditor;
use CeusMedia\Common\XML\DOM\FileReader;
use CeusMedia\Common\XML\DOM\FileWriter;
use CeusMedia\Common\XML\DOM\Node;

$config	= new Node( 'config' );
$db		= $config->addChild( new Node( 'db' ) );
$db->addChild( new Node( 'host', 'localhost' ) );
$db->addChild( new Node( 'port', '3306' ) );
FileWriter::save( 'config.xml', $config );					//  returns number of written bytes

$config	= FileReader::load( 'config.xml' );					//  or: ( new FileReader( 'config.xml' ) )->read()

$editor	= new FileEditor( 'config.xml' );
$editor->editNodeContent( 'db/host', 'db.example.com' );
$editor->editNodeAttribute( 'db', 'driver', 'mysql' );
$editor->addNode( 'db', 'name', 'shop' );
$editor->removeNode( 'db/port' );
```

Node paths of the editor start **below** the root element (an empty path addresses the root itself) and select
the first node of a name. Address further nodes of the same name by index, starting at 0: `book[1]` is the
second `book`. Methods: `addNode()`, `editNodeContent()`, `editNodeAttribute()`, `removeNodeAttribute()` and
`removeNode()`. Unknown paths raise an `InvalidArgumentException`.

!!! info "Fixed in 1.1.1"
    `removeNode()` removes the node now, top-level nodes can be addressed, and editing nested nodes no longer
    replaces the saved tree by the edited subnode.

## Validating

[`XML\DOM\SyntaxValidator`](../api/XML/DOM/SyntaxValidator.md) checks whether a string is well-formed XML.

```php
use CeusMedia\Common\XML\DOM\SyntaxValidator;

$validator	= new SyntaxValidator();
if( !$validator->validate( '<a><b></a>' ) )
	echo $validator->getErrors();		//  libxml messages with line numbers, one per line
else
	$document	= $validator->getDocument();				//  loaded DOMDocument
```

Since 1.1.1 the validator checks well-formedness only: DTDs are neither loaded nor validated, so there is no
network access. Errors are collected via libxml, independent of `display_errors`.

!!! warning "Prefer `SyntaxValidator` to `XML\Validator`"
    The older [`XML\Validator`](../api/XML/Validator.md) (based on the `xml` extension, with
    `getErrorMessage()` and `getErrorLine()`) accepts incomplete documents like `<a>` and even plain text.
    It only detects errors like mismatched tags.

## XPath

[`XML\DOM\XPathQuery`](../api/XML/DOM/XPathQuery.md) wraps `DOMXPath`. Load XML by `loadXml()`, `loadFile()`
or `loadUrl()`, then `query()` for node lists or `evaluate()` for any XPath result.

```php
use CeusMedia\Common\XML\DOM\XPathQuery;

$xml	= '<catalog xmlns:dc="http://purl.org/dc/elements/1.1/">
	<book id="b1" price="9.90"><dc:title>Dune</dc:title></book>
	<book id="b2" price="12.50"><dc:title>Solaris</dc:title></book>
</catalog>';

$query	= new XPathQuery();
$query->loadXml( $xml );
$query->registerNamespace( 'dc', 'http://purl.org/dc/elements/1.1/' );

foreach( $query->query( '//book[@price > 10]' ) as $book )		//  DOMNodeList of DOMElement
	echo $book->getAttribute( 'id' ), PHP_EOL;					//  b2

echo $query->evaluate( 'count(//book)' );						//  2
echo $query->evaluate( 'sum(//book/@price)' );					//  22.4
echo $query->evaluate( 'string(//book[@id="b2"]/dc:title)' );	//  Solaris

$first	= $query->query( '//book' )->item( 0 );
echo $query->query( 'dc:title', $first )->item( 0 )->textContent;	//  Dune – relative to a context node
```

Querying before loading raises a `RuntimeException`. `getDocument()` returns the `DOMDocument`.

## SimpleXML with extras

[`XML\Element`](../api/XML/Element.md) extends `SimpleXMLElement` with namespace-aware attribute handling,
CDATA and removal. [`XML\ElementReader`](../api/XML/ElementReader.md) creates elements from strings, files or
URLs and throws an [`Exception\Conversion`](../api/Exception/Conversion.md) on invalid XML.

```php
use CeusMedia\Common\XML\Element;
use CeusMedia\Common\XML\ElementReader;

$book	= ElementReader::read( '<book id="b1" lang="en"><title>Dune</title><tag>sf</tag><tag>classic</tag></book>' );
echo $book->getAttribute( 'id' );						//  b1 – RuntimeException if missing
echo $book->title->getValue();							//  Dune
foreach( $book->tag as $tag )
	echo $tag, PHP_EOL;									//  sf, classic
$book->setAttribute( 'lang', NULL );						//  NULL removes the attribute
$book->removeChild( 'tag', 0 );							//  first <tag>, counted from 0
$book->asFile( 'book.xml' );

$feed	= new Element( '<feed xmlns:media="http://search.yahoo.com/mrss/"/>' );
$item	= $feed->addChild( 'item' );
$item->setAttribute( 'id', '42' );
$item->addChild( 'title', 'Fish & Chips' );				//  text with & or < is stored as CDATA
$item->addChildCData( 'body', '<p>Hello</p>' );
$item->addChild( 'thumbnail', NULL, 'media' )->setAttribute( 'url', 'thumb.jpg' );
echo $feed->asXML();
```

Further methods: `getAttributes()`, `getAttributeNames()`, `hasAttribute()`, `countAttributes()`,
`countChildren()`, `removeAttribute()`, `remove()` and `setValue( $value, $cdata = FALSE )`. Namespaced
attributes and children take the prefix as extra argument – registered in the document or, with a new namespace,
followed by its URI.

## Storage

[`XML\DOM\Storage`](../api/XML/DOM/Storage.md) keeps scalar values in an XML file and addresses them by
dotted paths. The file is created if missing.

```php
use CeusMedia\Common\XML\DOM\Storage;

$storage	= new Storage( 'settings.xml' );
$storage->set( 'app.name', 'Shop' );
$storage->set( 'app.debug', FALSE );
$storage->set( 'db.port', 3306 );
$storage->set( 'db.host', 'localhost', TRUE );				//  TRUE: write the file now

$storage->get( 'db.port' );								//  int(3306) – types are kept
$storage->get( 'db' );									//  ['port' => 3306, 'host' => 'localhost']
$storage->get( 'app.missing' );							//  NULL
$storage->remove( 'app.debug' );
$storage->write();										//  save all changes
```

```xml
<storage>
  <rack name="app">
    <value name="name" type="string">Shop</value>
  </rack>
  <rack name="db">
    <value name="port" type="integer">3306</value>
    <value name="host" type="string">localhost</value>
  </rack>
</storage>
```

Values must be strings, integers, floats or booleans; other types raise an `InvalidArgumentException`.
Since 1.1.1 strings are written as plain UTF-8; files of older versions are still read correctly.

## Serializing objects

[`ObjectSerializer`](../api/XML/DOM/ObjectSerializer.md) writes the public properties of an object as XML,
including arrays and nested objects; [`ObjectDeserializer`](../api/XML/DOM/ObjectDeserializer.md) creates the
object again. The file variants [`ObjectFileSerializer`](../api/XML/DOM/ObjectFileSerializer.md) and
[`ObjectFileDeserializer`](../api/XML/DOM/ObjectFileDeserializer.md) take a file name.

```php
use CeusMedia\Common\XML\DOM\ObjectDeserializer;
use CeusMedia\Common\XML\DOM\ObjectFileDeserializer;
use CeusMedia\Common\XML\DOM\ObjectFileSerializer;
use CeusMedia\Common\XML\DOM\ObjectSerializer;

class Customer
{
	public string $name		= '';
	public ?int $age		= NULL;
	public array $tags		= [];
}

$customer		= new Customer();
$customer->name	= 'Ada € Lovelace';
$customer->age	= 36;
$customer->tags	= ['vip' => TRUE, 'score' => 9.5];

$xml	= ObjectSerializer::serialize( $customer );
$copy	= ObjectDeserializer::deserialize( $xml );			//  equal to $customer

ObjectFileSerializer::serialize( $customer, 'customer.xml' );
$copy	= ObjectFileDeserializer::deserialize( 'customer.xml' );
```

```xml
<object class="Customer">
  <string name="name">Ada € Lovelace</string>
  <integer name="age">36</integer>
  <array name="tags">
    <boolean name="vip">1</boolean>
    <double name="score">9.5</double>
  </array>
</object>
```

The class must be loaded and constructible without arguments; its constructor is called before the properties
are set. Since 1.1.1 deserializing keeps UTF-8 strings (like `€`) and works with typed properties and nested
objects of namespaced classes.

## Feeds

### RSS

[`XML\RSS\Builder`](../api/XML/RSS/Builder.md) creates RSS 2.0. Channel `title`, `link` and `description` are
required, items need a `title`. Dates (`date` of the channel, `pubDate`) may be given as timestamps.

```php
use CeusMedia\Common\XML\RSS\Builder;

$builder	= new Builder();
$builder->setChannelData( [
	'title'			=> 'Example News',
	'link'			=> 'https://example.com/',
	'description'	=> 'Latest news',
	'language'		=> 'en',
	'date'			=> time(),							//  rendered as pubDate
] );
$builder->addItem( [
	'title'			=> 'Release 1.1.1',
	'link'			=> 'https://example.com/news/111',
	'description'	=> 'Security & stability update',
	'pubDate'		=> time(),
	'guid'			=> 'https://example.com/news/111',
] )->addItem( [
	'title'			=> 'Release 1.1.0',
] );
$xml	= $builder->build();				//  build( $encoding = 'utf-8', $version = '2.0' )
```

Missing required channel elements raise a `DomainException`. `build()` empties the item list afterwards.
Further channel keys: `pubDate`, `lastBuildDate`, `copyright`, `managingEditor`, `webMaster`, `category`,
`generator`, `docs`, `ttl`, `imageUrl` (with `imageTitle`, `imageLink`, …) and `textInputTitle` (with
`textInputDescription`, …). [`XML\RSS\Writer`](../api/XML/RSS/Writer.md) saves the result to a file:
`Writer::save( $fileName, $channelData, $itemList )`.

[`XML\RSS\Reader`](../api/XML/RSS/Reader.md) reads feeds into arrays – by `readXml()`, `readFile()` or
`readUrl()`:

```php
use CeusMedia\Common\XML\RSS\Reader;

$feed	= Reader::readXml( $xml );
echo $feed['version'];									//  2.0
echo $feed['channelData']['title'];						//  Example News
foreach( $feed['itemList'] as $item )
	echo $item['title'], ': ', $item['link'] ?? '-', PHP_EOL;
```

The result contains `encoding`, `version`, `channelData` and `itemList`. All known channel and item keys are
present, missing ones as `NULL`; nested channel elements are flattened (`imageUrl`, `textInputTitle`).
[`XML\RSS\SimpleReader`](../api/XML/RSS/SimpleReader.md) returns only the elements actually present.

### Atom

[`XML\Atom\Reader`](../api/XML/Atom/Reader.md) parses Atom 1.0 feeds and checks the rules of RFC 4287 first
(an `Exception` names the first violated rule).

```php
use CeusMedia\Common\XML\Atom\Reader;

$reader	= new Reader();
$reader->readFile( 'feed.atom' );				//  or readXml( $xml ), readUrl( $url )

echo $reader->getChannelTitle();
echo $reader->getChannelUpdated();
foreach( $reader->getEntries() as $index => $entry ){
	echo $reader->getEntryTitle( $index ), PHP_EOL;
	echo $reader->getEntryLinks( $index )[0]['href'], PHP_EOL;
	echo $reader->getEntrySummary( $index ), PHP_EOL;
	echo $reader->getEntrySummary( $index, 'type' ), PHP_EOL;	//  text, html or xhtml
}
```

Text constructs (title, subtitle, summary, content) are read by attribute, `content` by default. There are
getters for every channel and entry element, e.g. `getChannelAuthors()`, `getChannelLinks()`, `getLanguage()`,
`getEntryId()`, `getEntryPublished()` and `getEntryContent()`.

!!! note "Alternate links"
    Entries without `content` need an alternate link. As defined by RFC 4287, a link without `rel` counts as
    alternate. Rules are checked by [`XML\Atom\Validator`](../api/XML/Atom/Validator.md); to skip them,
    use [`XML\Atom\Parser`](../api/XML/Atom/Parser.md) with `parse( $xml, FALSE )`.

### Identifying feeds

[`XML\FeedIdentifier`](../api/XML/FeedIdentifier.md) tells RSS (0.9x, 1.0/RDF, 2.0) and Atom apart:

```php
use CeusMedia\Common\XML\FeedIdentifier;

$identifier	= new FeedIdentifier();
if( $identifier->identify( $xml ) )						//  or identifyFromFile(), identifyFromUrl()
	echo $identifier->getType(), ' ', $identifier->getVersion();	//  RSS 2.0, ATOM 1.0, …

$identifier	= FeedIdentifier::fromUrl( 'https://example.com/feed', 5 );	//  timeout in seconds
```

[`XML\DOM\FeedIdentifier`](../api/XML/DOM/FeedIdentifier.md) does the same on a node tree and returns
`RSS/2.0` or `ATOM/1.0` (or `NULL`).

### Reading from URLs

Classes reading URLs – `XML\DOM\UrlReader`, `XML\DOM\XPathQuery::loadUrl()`, `XML\ElementReader::readUrl()`,
`XML\RSS\Reader`, `XML\Atom\Reader`, `XML\FeedIdentifier` and others – use [`Net\Reader`](../api/Net/Reader.md),
see [Network](network.md). Since 1.1.1, TLS certificates and host names are verified by default.
For a server with a self-signed certificate, use `Net\Reader` directly and pass the XML on:

```php
use CeusMedia\Common\Net\Reader as NetReader;
use CeusMedia\Common\XML\RSS\Reader;

$reader	= new NetReader( 'https://intranet.local/feed.rss' );
$reader->setVerifyPeer( FALSE );
$reader->setVerifyHost( FALSE );
$feed	= Reader::readXml( $reader->read() );
```

[`XML\DOM\UrlReader`](../api/XML/DOM/UrlReader.md) loads a URL into a node tree and accepts cURL options:
`UrlReader::load( $url, [CURLOPT_SSL_VERIFYPEER => FALSE] )`. It rejects responses not delivered with an XML
MIME type (`UrlReader::$mimeTypes`).

!!! note
    `UrlReader` sets its own user agent (`UrlReader::$userAgent`) on `Net\Reader`, which is static – so it stays
    in effect for all later `Net\Reader` requests of the same process.

## OPML

[`XML\OPML\Builder`](../api/XML/OPML/Builder.md) and [`Outline`](../api/XML/OPML/Outline.md) create outline
documents, e.g. feed lists. [`XML\OPML\Parser`](../api/XML/OPML/Parser.md) reads them.

```php
use CeusMedia\Common\XML\OPML\Builder;
use CeusMedia\Common\XML\OPML\FileWriter;
use CeusMedia\Common\XML\OPML\Outline;
use CeusMedia\Common\XML\OPML\Parser;

$opml	= new Builder();
$opml->setHeader( 'title', 'My feeds' );
$opml->setHeader( 'dateCreated', date( 'r' ) );

$news	= new Outline();
$news->setAttribute( 'text', 'News' );
$feed	= new Outline();
$feed->setAttribute( 'text', 'Example News' );
$feed->setAttribute( 'type', 'rss' );
$feed->setAttribute( 'xmlUrl', 'https://example.com/feed.rss' );
$news->addOutline( $feed );
$opml->addOutline( $news );

$xml	= $opml->build();
FileWriter::save( 'feeds.opml', $opml );

$parser	= new Parser();
$parser->parse( $xml );									//  or: OPML\FileReader::load( 'feeds.opml' )
echo $parser->getOption( 'opml_title' );					//  My feeds
foreach( $parser->getOutlines() as $outline ){
	echo $outline['text'], PHP_EOL;
	foreach( $outline['outlines'] as $child )
		echo ' - ', $child['text'], ': ', $child['xmlUrl'], PHP_EOL;
}
```

Header options are prefixed by `opml_` (dates as timestamps), the XML declaration by `xml_`.
`getOutlineTree()` returns the `body` node.

## XML-RPC

[`XML\RPC\Client`](../api/XML/RPC/Client.md) calls a method and returns the list of returned values.

```php
use CeusMedia\Common\XML\RPC\Client;

$client	= new Client( 'https://example.com/xmlrpc' );
$result	= $client->call( 'math.add', [2, 3] );				//  e.g. [5]
$result	= $client->call( 'user.update', [(object) ['id' => 7, 'name' => 'Ada']] );
```

| PHP | XML-RPC |
|-----|---------|
| `int`, `bool`, `float`, `string` | `int`, `boolean`, `double`, `string` (escaped) |
| `array` | `array` – keys are dropped |
| `object` | `struct` of its public properties – decoded as associative array |

Values without type element are decoded as strings; `nil`, `i8` and `base64` are decoded as well. Fault
responses are returned as empty list.

## XSL

[`XML\XSL\Transformator`](../api/XML/XSL/Transformator.md) applies a stylesheet (needs the `xsl` extension):

```php
use CeusMedia\Common\XML\XSL\Transformator;

$xslt	= new Transformator();
$xslt->loadXmlFile( 'catalog.xml' );
$xslt->loadXslFile( 'catalog.xsl' );
$html	= $xslt->transform();
$xslt->transformToFile( 'catalog.html' );
```

## More classes

| Class | Purpose |
|-------|---------|
| [`XML\DOM\Formater`](../api/XML/DOM/Formater.md) | `format( $xml, $leadingTabs = FALSE )` indents XML; `recode( $xml, $encoding )` converts the encoding |
| [`XML\Converter`](../api/XML/Converter.md) | `toPlainObject()` / `toJson()`: XML as objects with `children`, `attributes` and `content` – children of the same name overwrite each other |
| [`XML\Parser`](../api/XML/Parser.md) | expat-based parser: `toArray()` and `toObject()` (node tree) |
| [`XML\Namespaces`](../api/XML/Namespaces.md) | detects namespaces declared in XML, `DOMDocument` or `SimpleXMLElement` |
| [`XML\DOM\GoogleSitemapBuilder`](../api/XML/DOM/GoogleSitemapBuilder.md), [`GoogleSitemapWriter`](../api/XML/DOM/GoogleSitemapWriter.md) | sitemap XML from a list of links (namespace `http://www.sitemaps.org/schemas/sitemap/0.9`) |
| [`XML\RSS\GoogleBaseBuilder`](../api/XML/RSS/GoogleBaseBuilder.md) | RSS builder with Google Base item elements |
| [`XML\RSS\Parser`](../api/XML/RSS/Parser.md), [`SimpleParser`](../api/XML/RSS/SimpleParser.md) | the parsers behind the RSS readers |
| [`XML\Atom\Parser`](../api/XML/Atom/Parser.md), [`Validator`](../api/XML/Atom/Validator.md) | the parser and rule checker behind the Atom reader |
| [`XML\OPML\FileReader`](../api/XML/OPML/FileReader.md), [`FileWriter`](../api/XML/OPML/FileWriter.md) | OPML files |
| [`XML\DOM\PEAR\PackageReader`](../api/XML/DOM/PEAR/PackageReader.md) | reads PEAR `package.xml` files |
| [`XML\UnitTestResultReader`](../api/XML/UnitTestResultReader.md) | reads PHPUnit JUnit XML logs: counts, errors, failures |
