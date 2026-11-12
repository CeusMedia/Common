# Data types

The `ADT` namespace (abstract data types) holds small data structures: dictionaries, queues and stacks,
bitmasks, a registry, URLs, JSON helpers, trees, graphs, a CSS sheet model, vCards and more.
Most of them are plain PHP objects without dependencies – use them wherever an array is not expressive enough.

## Dictionaries

A [`ADT\Collection\Dictionary`](../api/ADT/Collection/Dictionary.md) is a map of pairs, usable like an array
(`ArrayAccess`, `Countable`, `Iterator`), with defaults, prefix filters and type casting:

```php
use CeusMedia\Common\ADT\Collection\Dictionary;

$config	= new Dictionary( ['mail.host' => 'localhost', 'mail.port' => 25, 'debug' => FALSE] );

$config->get( 'mail.port' );                // 25
$config->get( 'mail.user', 'nobody' );      // nobody – default for missing keys
$config->has( 'debug' );                    // TRUE
$config['mail.user']	= 'admin';           // same as set()
count( $config );                           // 4

$config->getAll( 'mail.' );                 // ['host' => 'localhost', 'port' => 25, 'user' => 'admin']
$config->getAll( 'mail.', TRUE );           // the same as Dictionary
$config->cast( '587', 'mail.port' );        // 587 – cast to the type of the current value
$config->getKeyOf( 'admin' );               // mail.user
```

`getAll()` and `filterByKeyPrefix()` cut the prefix from the keys. `remove()` removes a pair, `flush()` all pairs.

!!! warning "Case-insensitive dictionaries"
    `new Dictionary( $pairs, FALSE )` creates a dictionary with case-insensitive keys: keys are lowercased
    by `set()`, `get()`, `has()` and `remove()`. Initial pairs given to the constructor are stored as they are,
    so pass them with lowercase keys.

A [`ADT\Collection\LevelMap`](../api/ADT/Collection/LevelMap.md) is a dictionary which understands key levels,
divided by a dot (or another divider given to the constructor):

```php
use CeusMedia\Common\ADT\Collection\LevelMap;

$map	= new LevelMap( ['db.host' => 'localhost', 'db.port' => 3306, 'app.name' => 'Demo'] );

$map->get( 'db.port' );                     // 3306
$map->get( 'db' );                          // ['host' => 'localhost', 'port' => 3306]
$map->has( 'db' );                          // TRUE – key prefix exists
$map->remove( 'db' );                       // removes db.host and db.port
$map->getAll();                             // ['app.name' => 'Demo']
```

## Collections

[`ADT\Collection`](../api/ADT/Collection.md) wraps a list for filtering, mapping and reordering:

```php
use CeusMedia\Common\ADT\Collection;

$list	= new Collection( [3, 1, 4, 1, 5] );

$list->filterToCopy( fn( $value ) => $value > 2 )->getValues();    // [3, 4, 5]
$list->mapToCopy( fn( $value ) => $value * 10 )->getArray();        // [30, 10, 40, 10, 50]
$list->raise( 2 )->getArray();              // [3, 4, 1, 1, 5] – item 2 moved up by one
```

`filter()` and `map()` change the collection itself, `filterToCopy()` and `mapToCopy()` return a new one.
`map()` uses `array_walk()`, so the callback has to take the item by reference to change it.
`getDictionary()` and `getLevelMap()` convert the items.

## Queues and stacks

| Class | Order | Add | Take |
|-------|-------|-----|------|
| [`ADT\Collection\Queue`](../api/ADT/Collection/Queue.md) | first in, first out | `push()` | `pop()` |
| [`ADT\Collection\Stack`](../api/ADT/Collection/Stack.md) | last in, first out | `push()` | `pop()` |
| [`ADT\Collection\PriorityQueue`](../api/ADT/Collection/PriorityQueue.md) | by priority, then first in, first out | `enqueue()` | `dequeue()` |

```php
use CeusMedia\Common\ADT\Collection\PriorityQueue;
use CeusMedia\Common\ADT\Collection\Queue;
use CeusMedia\Common\ADT\Collection\Stack;

$queue	= new Queue( ['a', 'b'] );
$queue->push( 'c' );
$queue->pop();                              // a
echo $queue;                                // (b|c)

$stack	= new Stack( ['a', 'b'] );
$stack->push( 'c' );                        // 3 – new number of items
$stack->pop();                              // c

$jobs	= new PriorityQueue();               // default priority: 5
$jobs->enqueue( 'normal' )->enqueue( 'urgent', 1 )->enqueue( 'later', 9 );
$jobs->toList();                            // ['urgent', 'normal', 'later']
$jobs->dequeue();                           // urgent
count( $jobs );                             // 2
```

The lowest number is dequeued first – think of priority 1 as "first". Taking from an empty queue or stack
throws a `RuntimeException` (`OutOfBoundsException` for the priority queue); check `isEmpty()` or `count()` before.

!!! warning "`top()` and `bottom()` remove items"
    In 1.1, `top()` and `bottom()` of `Queue` and `Stack` do not just peek, but *remove* the returned item –
    `Queue::top()` works like `pop()`. Use `toArray()` to look at items without removing them.

[`ADT\Collection\SectionList`](../api/ADT/Collection/SectionList.md) groups entries in named sections:

```php
use CeusMedia\Common\ADT\Collection\SectionList;

$list	= new SectionList();
$list->addEntry( 'apple', 'fruits' )->addEntry( 'pear', 'fruits' )->addEntry( 'carrot', 'vegetables' );

$list->getSections();                       // ['fruits', 'vegetables']
$list->getEntries( 'fruits' );              // ['apple', 'pear']
$list->getSectionOfEntry( 'carrot' );       // vegetables
```

## Bitmasks

[`ADT\Bitmask`](../api/ADT/Bitmask.md) handles flags combined in an integer:

```php
use CeusMedia\Common\ADT\Bitmask;

const READ		= 1;
const WRITE		= 2;
const DELETE	= 4;

$mask	= new Bitmask( READ | WRITE );
$mask->has( WRITE );                        // TRUE
$mask->has( DELETE );                       // FALSE
$mask->has( WRITE | DELETE );               // TRUE – at least one of the bits is set
$mask->with( DELETE )->get();               // 7 – new bitmask, $mask is unchanged
$mask->without( READ )->get();              // 2
$mask->add( DELETE )->remove( READ );       // changes $mask
echo $mask;                                 // 6

Bitmask::fromArray( [READ, DELETE] )->get();    // 5
```

## Registry, constants and options

The [`ADT\Registry`](../api/ADT/Registry.md) is a singleton to share objects by key.
Values are stored by reference, in a pool within `$GLOBALS` (key `REFERENCES` by default):

```php
use CeusMedia\Common\ADT\Registry;

$config		= new ArrayObject( ['debug' => TRUE] );
$registry	= Registry::getInstance();
$registry->set( 'config', $config );

$registry->has( 'config' );                 // TRUE
Registry::getStatic( 'config' );            // the same object
$registry->set( 'config', $config );        // InvalidArgumentException: already registered
$registry->set( 'config', $config, TRUE );  // overwrite
$registry->get( 'missing' );                // InvalidArgumentException
```

`set()` and `setStatic()` take the value by reference, so pass a variable, not a literal.
Separate pools are available by `Registry::getInstance( 'poolKey' )`.

[`ADT\Constant`](../api/ADT/Constant.md) defines and reads global constants – names are uppercased:

```php
use CeusMedia\Common\ADT\Constant;

Constant::set( 'app_mode', 'dev' );         // defines APP_MODE
Constant::get( 'app_mode' );                // dev
Constant::set( 'app_mode', 'live' );        // RuntimeException: already defined

define( 'LEVEL_LOW', 1 );
define( 'LEVEL_HIGH', 9 );
Constant::getAll( 'LEVEL_' );               // ['LEVEL_LOW' => 1, 'LEVEL_HIGH' => 9]
Constant::getKeyByValue( 'LEVEL_', 9 );     // LEVEL_HIGH
```

`getAll()` needs a prefix of at least two characters. For class constants, see
[`Alg\Obj\Constant`](algorithms.md).

[`ADT\OptionObject`](../api/ADT/OptionObject.md) is a base class for objects with options – defaults
merged with settings, accessible by methods or as array:

```php
use CeusMedia\Common\ADT\OptionObject;

$options	= new OptionObject( ['timeout' => 10, 'retries' => 3], ['timeout' => 30] );

$options->getOption( 'timeout' );           // 30
$options['retries'];                        // 3
$options->setOption( 'retries', 5 );        // TRUE – FALSE if the value was the same
$options->getOption( 'proxy', FALSE );      // NULL
$options->getOption( 'proxy' );             // OutOfRangeException
```

## URLs and URNs

[`ADT\URL`](../api/ADT/URL.md) parses a URL into its parts, lets you change them and renders it again:

```php
use CeusMedia\Common\ADT\URL;

$url	= new URL( 'https://user:secret@example.com:8080/shop/list.php?page=2#top' );
$url->getScheme();                          // https
$url->getHost();                            // example.com
$url->getPort();                            // 8080
$url->getUsername();                        // user
$url->getPath();                            // /shop/list.php
$url->getQuery();                           // page=2
$url->getFragment();                        // top
$url->getRelative();                        // shop/list.php?page=2#top

$url	= new URL( 'https://example.com/shop/' );
$url->setPath( 'cart/../products/42' )
	->setQuery( ['color' => 'red', 'size' => 'L'] )
	->setFragment( 'details' );
echo $url;                                  // https://example.com/products/42?color=red&size=L#details
$url->get( FALSE );                         // products/42?color=red&size=L#details
```

`setPath()` resolves `../` – a path leaving the root throws a `RangeException`.
Rendering an absolute URL (`get()`, `echo`) needs scheme and host, otherwise a `RuntimeException` is thrown.

A second constructor argument gives a default URL, which completes relative URLs:

```php
use CeusMedia\Common\ADT\URL;

$logo	= new URL( 'images/logo.png', 'https://example.com/' );
echo $logo;                                 // https://example.com/images/logo.png

$logo	= new URL( 'images/logo.png', 'https://example.com/app/' );
$logo->setPath( 'images/logo.png', TRUE );  // based on the path of the default URL
echo $logo;                                 // https://example.com/app/images/logo.png

$page	= new URL( 'https://example.com/docs/guide/intro.html' );
$page->getRelativeTo( 'https://example.com/docs/' );        // guide/intro.html
$page->getRelativeTo( 'https://example.com/docs/api/' );    // ../guide/intro.html
```

[`ADT\URL\Compare`](../api/ADT/URL/Compare.md) checks whether two URLs have the same base –
scheme, host, port and credentials:

```php
use CeusMedia\Common\ADT\URL\Compare;

Compare::sameBaseStatic( 'https://example.com/a', 'https://example.com/b?x=1' );   // TRUE
Compare::sameBaseStatic( 'https://example.com/a', 'http://example.com/a' );        // FALSE
```

[`ADT\URL\Inference`](../api/ADT/URL/Inference.md) builds links from the current request parameters (`$_GET`),
adding and removing some – useful for pagination or filters:

```php
use CeusMedia\Common\ADT\URL\Inference;

// request: list.php?page=2&sort=name&debug=1
$url	= new Inference( 'https://example.com/list.php' );
$url->build( ['page' => 3], ['debug'] );            // https://example.com/list.php?page=3&sort=name
$url->build( ['page' => 3], ['debug'], 'results' ); // …?page=3&sort=name#results
$url->buildQueryString( ['sort' => 'date'] );       // page=2&sort=date&debug=1

Inference::buildStatic( ['page' => 1] );            // ./?page=1&sort=name&debug=1
```

[`ADT\URN`](../api/ADT/URN.md) holds a uniform resource name and validates its parts:

```php
use CeusMedia\Common\ADT\URN;

$urn	= new URN( 'urn:isbn:978-3-16-148410-0' );
$urn->getIdentifier();                      // isbn
$urn->getSpecificString();                  // 978-3-16-148410-0
$urn->getUrn( TRUE );                       // isbn:978-3-16-148410-0
echo new URN( 'ietf', 'rfc:2141' );         // urn:ietf:rfc:2141
```

Invalid identifiers or specific strings throw an `InvalidArgumentException`.
For requests and HTTP, see [Network](network.md).

## JSON

| Class | Purpose |
|-------|---------|
| [`ADT\JSON\Encoder`](../api/ADT/JSON/Encoder.md) | `json_encode()` throwing an `Exception\Conversion` on failure |
| [`ADT\JSON\Parser`](../api/ADT/JSON/Parser.md) | `json_decode()` throwing a `RuntimeException`, keeping error details |
| [`ADT\JSON\Converter`](../api/ADT/JSON/Converter.md) | JSON object or array to nested PHP array |
| [`ADT\JSON\Pretty`](../api/ADT/JSON/Pretty.md) | formats JSON with indentation |
| [`ADT\JSON\Builder`](../api/ADT/JSON/Builder.md) | encodes data without `json_encode()` (legacy) |

```php
use CeusMedia\Common\ADT\JSON\Converter;
use CeusMedia\Common\ADT\JSON\Encoder;
use CeusMedia\Common\ADT\JSON\Parser;
use CeusMedia\Common\ADT\JSON\Pretty;

Encoder::do( ['name' => 'Demo', 'path' => 'a/b'], JSON_UNESCAPED_SLASHES );    // {"name":"Demo","path":"a/b"}

$parser	= new Parser();
$data	= $parser->parse( '{"a":1,"b":[1,2]}' );          // stdClass object
$data	= $parser->parse( '{"a":1}', TRUE );              // ['a' => 1]
try{
	$parser->parse( '{"a":' );
}
catch( RuntimeException $e ){
	echo $e->getMessage();                  // Decoding JSON failed (JSON_ERROR_SYNTAX): Syntax error
	$parser->getError( TRUE );              // JSON_ERROR_SYNTAX
	$parser->getInfo();                     // {status: 2, code: 4, constant: 'JSON_ERROR_SYNTAX', message: 'Syntax error'}
}

Converter::convertToArray( '{"user":{"name":"Anna","roles":[{"id":1},{"id":2}]}}' );
// ['user' => ['name' => 'Anna', 'roles' => [['id' => 1], ['id' => 2]]]]

echo Pretty::print( '{"name":"Demo","tags":["a","b"]}' );
// {
//   "name": "Demo",
//   "tags": [
//     "a",
//     "b"
//   ]
// }
```

`Converter::convertToArray()` throws an `InvalidArgumentException` for invalid JSON and for JSON without
object or array. `Pretty::print( $json, TRUE )` validates the JSON first, the same way.
For JSON files, see [File system](filesystem.md).

## Trees and menus

[`ADT\Tree\Node`](../api/ADT/Tree/Node.md) is a simple tree of named children
(`addChild()`, `getChild()`, `hasChild()`, `removeChild()`, `getChildren()`).

[`ADT\Tree\MagicNode`](../api/ADT/Tree/MagicNode.md) gives access to nested values by properties:

```php
use CeusMedia\Common\ADT\Tree\MagicNode;

$config	= new MagicNode();
$config->fromArray( ['database' => ['host' => 'localhost', 'port' => 3306]] );

echo $config->database->host;               // localhost
$config->database->port	= 3307;
$config->database->user	= 'root';
$config->app			= 'Demo';
$config->database->port->value();           // 3307
echo $config->toJson();                     // {"database":{"host":"localhost","port":3307,"user":"root"},"app":"Demo"}
echo $config->mail->host;                   // empty string – missing nodes have the value NULL
```

!!! note
    Reading a missing node returns a new, detached node. So `$config->mail->host = 'x'` does not create
    `mail` – set `$config->mail = NULL` first, or import arrays with `fromArray()` or `fromJson()`.

[`ADT\Tree\Menu\Collection`](../api/ADT/Tree/Menu/Collection.md) and
[`ADT\Tree\Menu\Item`](../api/ADT/Tree/Menu/Item.md) describe nested menus, rendered by
`UI\HTML\Tree\Menu` (see [User interface](ui.md)) or read from OPML by
[`Alg\Tree\Menu\Converter`](algorithms.md):

```php
use CeusMedia\Common\ADT\Tree\Menu\Collection;
use CeusMedia\Common\ADT\Tree\Menu\Item;

$menu		= new Collection( 'Main' );
$products	= new Item( '/products', 'Products', ['class' => 'products'] );
$products->addChild( new Item( '/products/new', 'New' ) );
$menu->addChild( $products );
$menu->addChild( new Item( '/contact', 'Contact' ) );

$products->getUrl();                        // /products
$products->getAttribute( 'class' );         // products
$menu->toArray();                           // nested arrays with url, label, children, …
```

## Graphs

The `ADT\Graph` classes model weighted graphs of [`Node`](../api/ADT/Graph/Node.md)s and
[`Edge`](../api/ADT/Graph/Edge.md)s:

| Class | Graph |
|-------|-------|
| [`ADT\Graph\Weighted`](../api/ADT/Graph/Weighted.md) | undirected |
| [`ADT\Graph\DirectedWeighted`](../api/ADT/Graph/DirectedWeighted.md) | directed |
| [`ADT\Graph\DirectedAcyclicWeighted`](../api/ADT/Graph/DirectedAcyclicWeighted.md) | directed, rejects edges creating a cycle |

```php
use CeusMedia\Common\ADT\Graph\DirectedWeighted;

$graph	= new DirectedWeighted();
$a		= $graph->addNode( 'A' );
$b		= $graph->addNode( 'B' );
$c		= $graph->addNode( 'C' );
$d		= $graph->addNode( 'D' );
$graph->addEdge( $a, $b, 2 );               // edge A → B with value 2
$graph->addEdge( $b, $c, 3 );
$graph->addEdge( $a, $d, 7 );
$graph->addEdge( $d, $c, 1 );

$graph->getNodeSize();                      // 4
$graph->isPath( $a, $c );                   // TRUE
$graph->isPath( $c, $a );                   // FALSE
$graph->traverseBreadth( $a );              // ['A' => 0, 'B' => 2, 'D' => 7, 'C' => 5]
$graph->getTargetNodes( $a );               // [B, D]
$graph->toList();                           // ['A' => ['B' => 2, 'D' => 7], 'B' => ['C' => 3], 'C' => [], 'D' => ['C' => 1]]
$graph->hasCycle();                         // FALSE
$graph->addEdge( $c, $a );
$graph->hasCycle();                         // TRUE
```

`addNode()` returns the existing node for a known name. `traverseBreadth()` returns the distances of all
reachable nodes – summed edge values along the breadth-first traversal, not necessarily the shortest ones.
`hasCycle()`, `hasLoop()` and `isForrest()` check the structure – a forest is a graph without cycle.

A [`DirectedAcyclicWeighted`](../api/ADT/Graph/DirectedAcyclicWeighted.md) graph refuses edges which would
close a cycle with an `Exception` ("Graph would have a cycle"), unless `addEdge()` gets `TRUE` as fourth argument.

!!! warning "Path methods"
    `getPath()`, `getPathValue()`, `getGrade()` and `getWays()` follow the first path found, not the shortest one,
    and are not reliable for graphs with several paths between two nodes.
    Use `isPath()` and `traverseBreadth()`, or your own algorithm on `toList()`.

## CSS sheets

[`ADT\CSS\Sheet`](../api/ADT/CSS/Sheet.md) is a model of a style sheet: [rules](../api/ADT/CSS/Rule.md)
with a selector and [properties](../api/ADT/CSS/Property.md):

```php
use CeusMedia\Common\ADT\CSS\Property;
use CeusMedia\Common\ADT\CSS\Rule;
use CeusMedia\Common\ADT\CSS\Sheet;

$sheet	= new Sheet();
$sheet->set( 'body', 'margin', '0' );
$sheet->set( 'body', 'color', '#333' );
$sheet->addRule( new Rule( 'a:hover', [new Property( 'color', 'red' )] ) );

$sheet->getSelectors();                     // ['body', 'a:hover']
$sheet->get( 'body', 'color' )->getValue(); // #333
$sheet->get( 'body', 'padding' );           // NULL
$sheet->remove( 'body', 'margin' );
foreach( $sheet->getRules() as $rule )
	foreach( $rule->getProperties() as $property )
		echo $rule->getSelector(), ' { ', $property->getKey(), ': ', $property->getValue(), ' }', PHP_EOL;
```

Sheets are read from and written to files by the classes in `FS\File\CSS` – see [File system](filesystem.md).

## vCards

[`ADT\VCard`](../api/ADT/VCard.md) holds contact data and converts it to vCard 3.0, arrays and JSON:

```php
use CeusMedia\Common\ADT\VCard;

$card	= new VCard();
$card->setName( 'Doe', 'Jane', NULL, 'Dr.' )
	->setFormattedName( 'Dr. Jane Doe' )
	->setOrganisation( 'Example Ltd.', 'Research' )
	->addEmail( 'jane@example.com', ['internet', 'work'] )
	->addPhone( '+49 30 123456', ['work', 'voice'] );

echo $card->toString();
// BEGIN:VCARD
// VERSION:3.0
// N:Doe;Jane;;Dr.;
// FN:Dr. Jane Doe
// ORG:Example Ltd.;Research
// EMAIL;TYPE=internet,work:jane@example.com
// TEL;TYPE=work,voice:+49 30 123456
// END:VCARD

$card->getNameField( 'familyName' );        // Doe
$copy	= VCard::createFromString( $card->toString() );
$copy->getOrganisationField( 'name' );      // Example Ltd.
```

Reading and writing uses `FS\File\VCard\Parser` and `Builder` – see [File system](filesystem.md).

## Events

An [`ADT\Event\Handler`](../api/ADT/Event/Handler.md) binds callbacks to event keys and triggers them.
Each callback receives an [`ADT\Event\Data`](../api/ADT/Event/Data.md) object.
Keys with a dot are nested: triggering `save` also calls callbacks bound to `save.log`.

```php
use CeusMedia\Common\ADT\Event\Callback;
use CeusMedia\Common\ADT\Event\Data;
use CeusMedia\Common\ADT\Event\Handler;

$events	= new Handler();
$events->bind( 'save', function( Data $event ){
	echo 'saved #', $event->arguments['id'], PHP_EOL;
} );
$events->bind( 'save.log', new Callback( function( Data $event ){
	echo $event->data, ': ', $event->key, ' via ', $event->trigger, PHP_EOL;
}, 'logger' ) );

$events->trigger( 'save', NULL, ['id' => 42] );     // 2 – number of called callbacks
// saved #42
// logger: save.log via save
```

A callback can call `$event->stop()` to skip the remaining callbacks of this trigger.
[`ADT\Event\Callback`](../api/ADT/Event/Callback.md) binds additional data to a callback (`$event->data`).

## Time

[`ADT\Time\Delay`](../api/ADT/Time/Delay.md) is a non-blocking delay in milliseconds:

```php
use CeusMedia\Common\ADT\Time\Delay;

$delay	= new Delay( 50 );
$delay->isActive();                         // TRUE
usleep( 60000 );
$delay->isReached();                        // TRUE
$delay->restart();                          // starts again; RuntimeException while still active, unless forced by TRUE
```

For stopwatches, durations and time formats, see [`Alg\Time`](algorithms.md).

## More classes

??? info "Other classes in the ADT namespace"

    | Class | Purpose |
    |-------|---------|
    | [`ADT\String_`](../api/ADT/String_.md) | string object wrapping PHP string functions (`capitalize()`, `replace()`, `split()`, …) |
    | [`ADT\StringBuffer`](../api/ADT/StringBuffer.md) | Java-like string buffer with `append()`, `insert()`, `deleteCharAt()` |
    | [`ADT\Null_`](../api/ADT/Null_.md) | null object: answers every call, property and offset with itself |
    | [`ADT\Singleton`](../api/ADT/Singleton.md) | abstract base class for singletons (`getInstance()`) |
    | [`ADT\Object_`](../api/ADT/Object_.md) | base class with reflection helpers (`getClass()`, `getMethods()`, `hasMethod()`) |
    | [`ADT\Multiplexer`](../api/ADT/Multiplexer.md) | selects one of several inputs by controls |
    | [`ADT\URL\Parts`](../api/ADT/URL/Parts.md) | parts of a URL, used by `ADT\URL` |
    | [`ADT\Graph\NodeSet`](../api/ADT/Graph/NodeSet.md), [`ADT\Graph\EdgeSet`](../api/ADT/Graph/EdgeSet.md) | node and edge storage of graphs |
