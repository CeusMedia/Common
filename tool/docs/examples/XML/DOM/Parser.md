```php
use CeusMedia\Common\XML\DOM\Builder;
use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\Common\XML\DOM\Parser;

$tree	= ( new Parser() )->parse( '<library><book id="b1"><title>Dune</title></book></library>' );
foreach( $tree->getChildren( 'book' ) as $book )
	echo $book->getAttribute( 'id' ), ': ', $book->getChild( 'title' )->getContent(), PHP_EOL;

$tree->addChild( new Node( 'book', NULL, ['id' => 'b2'] ) )
	->addChild( new Node( 'title', 'Solaris' ) );
echo Builder::build( $tree );
```

See guide: [XML](../../../guide/xml.md)
