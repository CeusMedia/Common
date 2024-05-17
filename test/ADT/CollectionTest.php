<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of ADT\Bitmask.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT;

use CeusMedia\Common\ADT\Bitmask;
use CeusMedia\Common\ADT\Collection;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\Bitmask.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CollectionTest extends BaseCase
{
	/** @var Collection $collection */
	protected Collection $collection;

	public function testFilter(): void
	{
		$func	= function( int $item ): bool{ return $item !== 2; };			//  long syntax
		$result	= $this->collection->filter( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [1, 3], $result->getValues() );
		self::assertEquals( [1, 3], $this->collection->getValues() );

		$this->setUp();
		$func	= fn( int $item ) => $item !== 2;								//  short syntax
		$result	= $this->collection->filter( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [1, 3], $result->getValues() );
		self::assertEquals( [1, 3], $this->collection->getValues() );
	}

	public function testFilterToCopy(): void
	{
		$func	= function( int $item ): bool{ return $item !== 2; };			//  long syntax
		$result	= $this->collection->filterToCopy( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [1, 3], $result->getValues() );
		self::assertEquals( [1, 2, 3], $this->collection->getValues() );

		$this->setUp();
		$func	= fn( int $item ) => $item !== 2;								//  short syntax
		$result	= $this->collection->filterToCopy( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [1, 3], $result->getValues() );
		self::assertEquals( [1, 2, 3], $this->collection->getValues() );
	}

	public function testGetDictionary(): void
	{
		$dictionary	= $this->collection->getDictionary();
		self::assertInstanceOf( Collection\Dictionary::class, $dictionary );
		self::assertEquals( [1, 2, 3], array_values( $dictionary->getAll() ) );
		self::assertEquals( [0, 1, 2], array_keys( $dictionary->getAll() ) );
	}

	public function testGetIterator(): void
	{
		self::assertInstanceOf( \Traversable::class, $this->collection->getIterator() );
	}

	public function testMap(): void
	{
		$func	= function( int & $item ){ $item *= 2; };						//  long syntax
		$result	= $this->collection->map( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [2, 4, 6], $result->getValues() );
		self::assertEquals( [2, 4, 6], $this->collection->getValues() );

		$this->setUp();
		$func	= fn( int & $item ) => $item *= 2;								//  short syntax
		$result	= $this->collection->map( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [2, 4, 6], $result->getValues() );
		self::assertEquals( [2, 4, 6], $this->collection->getValues() );
	}

	public function testMapToCopy(): void
	{
		$func	= function( $item ): int{ return $item * 2; };					//  long syntax
		$result	= $this->collection->mapToCopy( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [2, 4, 6], $result->getValues() );
		self::assertEquals( [1, 2, 3], $this->collection->getValues() );

		$this->setUp();

		$func	= fn( int $item ) => $item * 2;									//  short syntax
		$result	= $this->collection->mapToCopy( $func );
		self::assertInstanceOf( Collection::class, $result );
		self::assertEquals( [2, 4, 6], $result->getValues() );
		self::assertEquals( [1, 2, 3], $this->collection->getValues() );
	}

	protected function setUp(): void
	{
		parent::setUp();
		$this->collection	= new Collection( [1, 2, 3] );
	}
}