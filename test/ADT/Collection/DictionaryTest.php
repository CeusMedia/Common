<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Dictionary
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\ADT\Collection;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Exception\Data\InvalidTypeCast as InvalidTypeCastException;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Dictionary
 *	@package		Tests.ADT.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class DictionaryTest extends BaseCase
{
	/**	@var	Dictionary		$list		Instance of Dictionary */
	private Dictionary $dictionary;

	public function setUp(): void
	{
		$this->dictionary	= new Dictionary();
		$this->dictionary->set( 'key0', 0 );
		$this->dictionary->set( 'key1', 'value1' );
		$this->dictionary->set( 'key2', 'value2' );
		$this->dictionary->set( 'key3', array( 'value3-1', 'value3-2' ) );
		$this->dictionary->set( 'key4', array( 'key4-1' => 'value4-1', 'key4-2' => 'value4-2' ) );
		$this->dictionary->set( 'key5', new Dictionary( array( '0', '1' ) ) );
	}

	public function testConstruct(): void
	{
		$dictionary	= new Dictionary();
		$assertion	= 0;
		$creation	= $dictionary->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= [];
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );

		$dictionary	= new Dictionary( array( 1, 2, 3 ) );
		$assertion	= 3;
		$creation	= $dictionary->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 1, 2, 3 );
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );

		$dictionary	= new Dictionary( array( 'a' => 'b', 'b' => 'c', 'c' => 'd' ) );
		$assertion	= array( 'a' => 'b', 'b' => 'c', 'c' => 'd' );
		$creation	= $dictionary->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCast(): void
	{
		$assertion	= 2;
		$creation	= $this->dictionary->cast( "2", 'key0' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->dictionary->cast( M_PI, 'key0' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= (string) M_PI;
		$creation	= $this->dictionary->cast( M_PI, 'key1' );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 */
	public function testCastException1(): void
	{
		$this->expectException( InvalidTypeCastException::class );
		$fp	= fopen( __FILE__, 'r' );
		$this->dictionary->cast( $fp, 'key1' );
	}

	/**
	 */
	public function testCastException2(): void
	{
		$this->expectException( 'OutOfRangeException' );
		$this->dictionary->cast( 'whatever', 'invalid' );
	}

	/**
	 */
	public function testCastException3(): void
	{
		$this->expectException( 'UnexpectedValueException' );
		$this->dictionary->cast( [], 'key1' );
	}

	public function testCount(): void
	{
		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCountableInterface(): void
	{
		$assertion	= 6;
		$creation	= count( $this->dictionary );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFlush(): void
	{
		$this->dictionary->next();
		$this->dictionary->next();
		$this->assertEquals( 'value2', $this->dictionary->current() );
		$this->assertEquals( 'value2', $this->dictionary->current() );
		$this->dictionary->flush();
		$this->assertEquals( 0, $this->dictionary->count() );
		$this->dictionary->set( 'key1', 'value1' );
		$this->dictionary->set( 'key2', 'value2' );
		$this->dictionary->set( 'key3', 'value3' );
		$this->assertEquals( 'value1', $this->dictionary->current() );
	}

	public function testGet(): void
	{
		$assertion	= "value2";
		$creation	= $this->dictionary->get( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->get( 'key3' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $this->dictionary->get( 'invalid' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetWithDefault(): void
	{
		$assertion	= "value2";
		$creation	= $this->dictionary->get( 'key2', -1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->get( 'key3', -1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -1;
		$creation	= $this->dictionary->get( 'invalid', -1 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAll(): void
	{
		$assertion	= array(
			'key0'	=> 0,
			'key1'	=> 'value1',
			'key2'	=> 'value2',
			'key3'	=> array( 'value3-1', 'value3-2' ),
			'key4'	=> array( 'key4-1' => 'value4-1', 'key4-2' => 'value4-2' ),
			'key5'	=> new Dictionary( array( '0', '1' ) ),
		);
		$creation	= $this->dictionary->getAll();
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetAllWithPrefix(): void
	{
		$dictionary	= new Dictionary( array(
			'A.a'		=> 0,
			'A.a.1'		=> 1,
			'A.a.2'		=> 2,
			'A.b.1'		=> 3,
			'A.b.2'		=> 4,
			'B.a.1'		=> 5,
			'B.a.2'		=> 6,
			'B.b.1'		=> 7,
			'B.b.2'		=> 8,
		) );

		$assertion	= array( '1' => 7, '2' => 8  );
		$creation	= $dictionary->getAll( 'B.b.' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( '.1' => 7, '.2' => 8  );
		$creation	= $dictionary->getAll( 'B.b' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'a.1' => 5, 'a.2' => 6, 'b.1' => 7, 'b.2' => 8  );
		$creation	= $dictionary->getAll( 'B.' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( '.a.1' => 5, '.a.2' => 6, '.b.1' => 7, '.b.2' => 8  );
		$creation	= $dictionary->getAll( 'B' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'a' => 0, 'a.1' => 1, 'a.2' => 2, 'b.1' => 3, 'b.2' => 4 );
		$creation	= $dictionary->getAll( 'A.' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetKeyOf(): void
	{
		$assertion	= 'key2';
		$creation	= $this->dictionary->getKeyOf( 'value2' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'key0';
		$creation	= $this->dictionary->getKeyOf( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= null;
		$creation	= $this->dictionary->getKeyOf( 'invalid' );
		$this->assertEquals( $assertion, $creation );


		$array		= $this->dictionary->get( 'key3' );
		$assertion	= 0;
		$creation	= array_search( 'value3-1', $array );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHas(): void
	{
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertTrue( $creation );

		$creation	= $this->dictionary->has( 'invalid' );
		$this->assertFalse( $creation );

		$creation	= $this->dictionary->has( '0' );
		$this->assertFalse( $creation );
	}

	public function testRemove(): void
	{
		$this->dictionary->remove( 'key2' );
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertFalse( $creation );

		$this->dictionary->remove( 'invalid' );
		$assertion	= 5;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testIterator(): void
	{
		$list	= [];
		foreach( $this->dictionary as $key => $value )
			$list[$key]	= $value;
		$this->assertEquals( $list, $this->dictionary->getAll() );
	}

	public function testRemove2(): void
	{
		foreach( $this->dictionary->getKeys() as $key )
			$this->dictionary->remove( $key );

		$assertion	= 0;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testSet(): void
	{
		$this->dictionary->set( 'key2', 'value2#' );
		$assertion	= 'value2#';
		$creation	= $this->dictionary->get( 'key2' );
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->set( 'key6', 'value6' );
		$assertion	= 'value6';
		$creation	= $this->dictionary->get( 'key6' );
		$this->assertEquals( $assertion, $creation );
	}

	//  --  TESTS OF ARRAY ACCESS INTERFACE  --  //
	public function testOffsetExists(): void
	{
		$creation	= isset( $this->dictionary['key2'] );
		$this->assertTrue( $creation );
	}

	public function testOffsetGet(): void
	{
		$assertion	= "value2";
		$creation	=$this->dictionary['key2'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetSet(): void
	{
		$this->dictionary['key2']	= "value2#";
		$assertion	= "value2#";
		$creation	= $this->dictionary['key2'];
		$this->assertEquals( $assertion, $creation );

		$this->dictionary['key6']	= "value6";
		$assertion	= "value6";
		$creation	= $this->dictionary['key6'];
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset(): void
	{
		unset( $this->dictionary['key2'] );
		$creation	= $this->dictionary->has( 'key2' );
		$this->assertFalse( $creation );

		unset( $this->dictionary['key2'] );
		$assertion	= 5;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testOffsetUnset2(): void
	{
		foreach( $this->dictionary as $key => $value )
			unset( $this->dictionary[$key] );

		$assertion	= 0;
		$creation	= count( $this->dictionary );
		$this->assertEquals( $assertion, $creation );
	}

	//  --  TESTS OF ITERATOR INTERFACE  --  //
	public function testKey(): void
	{
		$assertion	= 'key0';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$assertion	= 'key1';
		$creation	= $this->dictionary->key();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testCurrent(): void
	{
		$expected	= 0;
		$this->assertEquals( $expected, $this->dictionary->current() );
		$this->assertEquals( $expected, $this->dictionary->current() );

		$this->dictionary->next();
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->dictionary->count();
		$this->assertEquals( $assertion, $creation );
	}

	public function testNext(): void
	{
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();

		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$this->dictionary->next();
		$assertion	= array( 'value3-1', 'value3-2' );
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$this->dictionary->next();
		$this->dictionary->next();
		$creation	= $this->dictionary->current();
		$this->assertNull( $creation );
	}

	public function testRewind(): void
	{
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->next();
		$assertion	= 'value1';
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );

		$this->dictionary->rewind();
		$assertion	= 0;
		$creation	= $this->dictionary->current();
		$this->assertEquals( $assertion, $creation );
	}

 	public function testValid(): void
	{
		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertTrue( $creation );

		$this->dictionary->next();
		$creation	= $this->dictionary->valid();
		$this->assertFalse( $creation );
	}

	//  --  TEST OF ITERATOR AGGREGATE INTERFACE  --  //
/*	public function testGetIterator()
	{
		$it	= $this->dictionary->getIterator();
		foreach( $it as $key => $value )
			$array[$key]	= $value;
		$assertion	= array(
			'key1' => 'value1',
			'key2' => 'value2',
			'key3' => 'value3',
			);
		$creation	= $array;
		$this->assertEquals( $assertion, $creation );
	}*/
}
