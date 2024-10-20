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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct(): void
	{
		self::assertEquals( Bitmask::fromArray( [static::BIT_0] ), new Bitmask() );
		self::assertEquals( Bitmask::fromArray( [static::BIT_1] ), new Bitmask( 1 ) );
		self::assertEquals( Bitmask::fromArray( [static::BIT_0] ), new Bitmask( new Bitmask() ) );
		self::assertEquals( Bitmask::fromArray( [static::BIT_1] ), new Bitmask( new Bitmask( 1 ) ) );

		self::assertEquals( Bitmask::fromArray( [static::BIT_2, static::BIT_1] ), new Bitmask( static::BIT_2 | static::BIT_1 ) );
	}

	/**
	 *	Tests Method '__toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString(): void
	{
		$obj	= new Bitmask( static::BIT_1 );
		self::assertEquals( 1, (string) $obj );

		$obj	= new Bitmask( static::BIT_2 | static::BIT_1 );
		self::assertEquals( 3, (string) $obj );

		$obj	= new Bitmask( new Bitmask( static::BIT_1 ) );
		self::assertEquals( 1, (string) $obj );
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd(): void
	{
		$obj	= new Bitmask();

		self::assertEquals( static::BIT_0, $obj->get() );
		$obj->add( static::BIT_1 );
		self::assertEquals( static::BIT_1, $obj->get() );
		$obj->add( static::BIT_2 );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->add( static::BIT_1 );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->get() );

		$obj	= new Bitmask();

		self::assertEquals( static::BIT_0, $obj->get() );
		$obj->add( new Bitmask( static::BIT_1 ) );
		self::assertEquals( static::BIT_1, $obj->get() );
		$obj->add( new Bitmask( static::BIT_2 ) );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->add( new Bitmask( static::BIT_1 ) );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->get() );
	}


	/**
	 *	Tests Method 'fromArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFromArray(): void
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
	public function testGet(): void
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
	public function testHas(): void
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

		$obj->set( 18 );
		self::assertTrue( $obj->has( static::BIT_16 | static::BIT_2 ) );
		self::assertFalse( $obj->has( static::BIT_16 & static::BIT_2 ) );
		self::assertTrue( $obj->has( static::BIT_4 | static::BIT_2 ) );
		self::assertFalse( $obj->has( static::BIT_4 | static::BIT_1 ) );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove(): void
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

		$obj		= new Bitmask( $original );
		$obj->remove( static::BIT_16 | static::BIT_8 );
		self::assertEquals( static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );

		$obj		= new Bitmask( $original );
		$obj->remove( new Bitmask( static::BIT_16 ) );
		self::assertEquals( static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );
	}


	/**
	 *	Tests Method 'with'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWith(): void
	{
		$obj		= new Bitmask();

		self::assertEquals( static::BIT_0, $obj->get() );
		self::assertEquals( static::BIT_1, $obj->with( static::BIT_1 )->get() );
		self::assertEquals( static::BIT_2, $obj->with( static::BIT_2 )->get() );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->with( static::BIT_2 | static::BIT_1 )->get() );

		self::assertEquals( static::BIT_1, $obj->with( new Bitmask( static::BIT_1 ) )->get() );
		self::assertEquals( static::BIT_2, $obj->with( new Bitmask( static::BIT_2 ) )->get() );
		self::assertEquals( static::BIT_2 | static::BIT_1, $obj->with( new Bitmask( static::BIT_2 | static::BIT_1 ) )->get() );
	}

	/**
	 *	Tests Method 'without'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWithout(): void
	{
		$obj		= new Bitmask();

		$original	= static::BIT_16 | static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1;
		$obj->set( $original );
		self::assertEquals( $original, $obj->get() );

		self::assertEquals( static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->without( static::BIT_16 )->get() );
		self::assertEquals( static::BIT_16 | static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->without( static::BIT_8 )->get() );
		self::assertEquals( static::BIT_16 | static::BIT_8 | static::BIT_4 | static::BIT_2, $obj->without( static::BIT_1 )->get() );
		self::assertEquals( static::BIT_16 | static::BIT_8 | static::BIT_4 | static::BIT_1, $obj->without( static::BIT_2 )->get() );
		self::assertEquals( static::BIT_16 | static::BIT_8 | static::BIT_2 | static::BIT_1, $obj->without( static::BIT_4 )->get() );

		self::assertEquals( $original, $obj->without( static::BIT_0 )->get() );
		self::assertEquals( static::BIT_0, $obj->without( $original )->get() );
	}
}
