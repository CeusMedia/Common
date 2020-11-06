<?php
/**
 *	TestUnit of Test_ADT_Bitmask.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2018
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Test_ADT_Bitmask.
 *	@extends		Test_Case
 *	@package		Tests.{classPackage}
 *	@uses			ADT_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2018
 *	@version		0.1
 */
class Test_ADT_BitmaskTest extends Test_Case
{
	const BIT_0		= 0;
	const BIT_1		= 1;
	const BIT_2		= 2;
	const BIT_4		= 4;
	const BIT_8		= 8;
	const BIT_16	= 16;

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$obj	= new ADT_Bitmask();
		$obj->add( static::BIT_2 );

		$this->assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		$this->assertEquals( static::BIT_2, $obj->get() );

		$obj->set( 31 );
		$this->assertEquals( static::BIT_16, $obj->get() & static::BIT_16 );
		$this->assertEquals( static::BIT_8, $obj->get() & static::BIT_8 );
		$this->assertEquals( static::BIT_4, $obj->get() & static::BIT_4 );
		$this->assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		$this->assertEquals( static::BIT_1, $obj->get() & static::BIT_1 );

		$obj->set( 0 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_16 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_8 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_4 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_2 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_1 );

		$obj->set( 18 );
		$this->assertEquals( static::BIT_16, $obj->get() & static::BIT_16 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_8 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_4 );
		$this->assertEquals( static::BIT_2, $obj->get() & static::BIT_2 );
		$this->assertEquals( static::BIT_0, $obj->get() & static::BIT_1 );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$obj	= new ADT_Bitmask();
		$obj->add( static::BIT_2 );

		$this->assertFalse( $obj->has( static::BIT_16 ) );
		$this->assertFalse( $obj->has( static::BIT_8 ) );
		$this->assertFalse( $obj->has( static::BIT_4 ) );
		$this->assertTrue( $obj->has( static::BIT_2 ) );
		$this->assertFalse( $obj->has( static::BIT_1 ) );

		$obj->set( 31 );
		$this->assertTrue( $obj->has( static::BIT_16 ) );
		$this->assertTrue( $obj->has( static::BIT_8 ) );
		$this->assertTrue( $obj->has( static::BIT_4 ) );
		$this->assertTrue( $obj->has( static::BIT_2 ) );
		$this->assertTrue( $obj->has( static::BIT_1 ) );

		$obj->set( 0 );
		$this->assertFalse( $obj->has( static::BIT_16 ) );
		$this->assertFalse( $obj->has( static::BIT_8 ) );
		$this->assertFalse( $obj->has( static::BIT_4 ) );
		$this->assertFalse( $obj->has( static::BIT_2 ) );
		$this->assertFalse( $obj->has( static::BIT_1 ) );

		$obj->set( 18 );
		$this->assertTrue( $obj->has( static::BIT_16 ) );
		$this->assertFalse( $obj->has( static::BIT_8 ) );
		$this->assertFalse( $obj->has( static::BIT_4 ) );
		$this->assertTrue( $obj->has( static::BIT_2 ) );
		$this->assertFalse( $obj->has( static::BIT_1 ) );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$obj		= new ADT_Bitmask();

		$original	= static::BIT_16 | static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1;
		$obj->set( $original );
		$this->assertEquals( $original, $obj->get() );

		$obj->remove( static::BIT_16 );
		$this->assertEquals( static::BIT_8 | static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->remove( static::BIT_8 );
		$this->assertEquals( static::BIT_4 | static::BIT_2 | static::BIT_1, $obj->get() );

		$obj->remove( static::BIT_1 );
		$this->assertEquals( static::BIT_4 | static::BIT_2, $obj->get() );

		$obj->remove( static::BIT_2 );
		$this->assertEquals( static::BIT_4, $obj->get() );

		$obj->remove( static::BIT_4 );
		$this->assertEquals( static::BIT_0, $obj->get() );
	}
}
