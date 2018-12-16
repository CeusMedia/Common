<?php
/**
 *	TestUnit of Test_ADT_Bitmask.
 *	@package		Tests.{classPackage}
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2018
 *	@version		0.1
 */
require_once dirname( __DIR__ ).'/initLoaders.php';
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
	const BIT_1		= 1;
	const BIT_2		= 2;
	const BIT_4		= 4;
	const BIT_8		= 8;
	const BIT_16	= 16;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->markTestIncomplete( 'Not implemented yet.' );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$obj	= new Test_ADT_Bitmask();
		$obj->add( self::BIT_2 );
		$this->markTestIncomplete( 'Not implemented yet.' );
/*		$assertion	= '';
		$creation	= '';
		$this->assertEquals( $assertion, $creation );*/
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetRangeException()
	{
		$obj	= new ADT_Bitmask();
		$this->expectException( 'Range_Exception' );
		$obj->get( 'invalid' );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAll()
	{
		$this->markTestIncomplete( 'Not implemented yet.' );
/*		$assertion	= '';
		$creation	= '';
		$this->assertEquals( $assertion, $creation );*/
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$this->markTestIncomplete( 'Not implemented yet.' );
/*		$assertion	= '';
		$creation	= '';
		$this->assertEquals( $assertion, $creation );*/
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$this->markTestIncomplete( 'Not implemented yet.' );
/*		$assertion	= '';
		$creation	= '';
		$this->assertEquals( $assertion, $creation );*/
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveRangeException()
	{
		$obj	= new ADT_Bitmask();
//		$this->expectException( 'Range_Exception' );
		$this->setExpectedException( 'RangeException' );
		$obj->remove( 'invalid' );
	}
}
?>
