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
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of ADT\Bitmask.
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BitmaskTest extends BaseCase
{
	const BIT_0		= 0;
	const BIT_1		= 1;
	const BIT_2		= 2;
	const BIT_4		= 4;
	const BIT_8		= 8;
	const BIT_16	= 16;

	/**
	 *	Tests Method 'fromArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFromArray()
	{
		$obj	= Bitmask::fromArray( [self::BIT_1, self::BIT_2, self::BIT_16] );
		self::assertTrue( $obj->has( static::BIT_16 ) );
		self::assertFalse( $obj->has( static::BIT_8 ) );
		self::assertFalse( $obj->has( static::BIT_4 ) );
		self::assertTrue( $obj->has( static::BIT_2 ) );
		self::assertTrue( $obj->has( static::BIT_1 ) );
		self::assertEquals( static::BIT_1 + static::BIT_2 + static::BIT_16, $obj->get() );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$obj	= new Bitmask();
		$obj->add( static::BIT_2 );

		self::assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		self::assertEquals( static::BIT_2, $obj->get() );

		$obj->set( 31 );
		self::assertEquals( static::BIT_16, $obj->get() & static::BIT_16 );
		self::assertEquals( static::BIT_8, $obj->get() & static::BIT_8 );
		self::assertEquals( static::BIT_4, $obj->get() & static::BIT_4 );
		self::assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		self::assertEquals( static::BIT_1, $obj->get() & static::BIT_1 );

		$obj->set( 0 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_16 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_8 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_4 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_2 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_1 );

		$obj->set( 18 );
		self::assertEquals( static::BIT_16, $obj->get() & static::BIT_16 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_8 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_4 );
		self::assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		self::assertEquals( static::BIT_0, $obj->get() & static::BIT_1 );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$obj	= new Bitmask();
		$obj->add( static::BIT_2 );

		self::assertFalse( $obj->has( static::BIT_16 ) );
		self::assertFalse( $obj->has( static::BIT_8 ) );
		self::assertFalse( $obj->has( static::BIT_4 ) );
		self::assertTrue( $obj->has( static::BIT_2 ) );
		self::assertFalse( $obj->has( static::BIT_1 ) );

		$obj->set( 31 );
		self::assertTrue( $obj->has( static::BIT_16 ) );
		self::assertTrue( $obj->has( static::BIT_8 ) );
		self::assertTrue( $obj->has( static::BIT_4 ) );
		self::assertTrue( $obj->has( static::BIT_2 ) );
		self::assertTrue( $obj->has( static::BIT_1 ) );

		$obj->set( 0 );
		self::assertFalse( $obj->has( static::BIT_16 ) );
		self::assertFalse( $obj->has( static::BIT_8 ) );
		self::assertFalse( $obj->has( static::BIT_4 ) );
		self::assertFalse( $obj->has( static::BIT_2 ) );
		self::assertFalse( $obj->has( static::BIT_1 ) );

		$obj->set( 18 );
		self::assertTrue( $obj->has( static::BIT_16 ) );
		self::assertFalse( $obj->has( static::BIT_8 ) );
		self::assertFalse( $obj->has( static::BIT_4 ) );
		self::assertTrue( $obj->has( static::BIT_2 ) );
		self::assertFalse( $obj->has( static::BIT_1 ) );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$obj		= new Bitmask();

		$original	= static::BIT_16 | static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1;
		$obj->set( $original );
		self::assertEquals( $original, $obj->get() );

		$obj->remove( static::BIT_16 );
		self::assertEquals( static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->remove( static::BIT_8 );
		self::assertEquals( static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->remove( static::BIT_1 );
		self::assertEquals( static::BIT_4 | static::BIT_2, $obj->get() );

		$obj->remove( static::BIT_2 );
		self::assertEquals( static::BIT_4, $obj->get() );

		$obj->remove( static::BIT_4 );
		self::assertEquals( static::BIT_0, $obj->get() );
	}
}
