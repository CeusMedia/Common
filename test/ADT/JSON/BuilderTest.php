<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.json
 *	@extends		Test_Case
 *	@uses			Test_ADT_JSON_Builder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_ADT_JSON_BuilderTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->object		= new Test_Object();
		$this->object->a	= "test";
	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncode()
	{
		$data		= array( 1, 2.3, "string", TRUE, NULL, $this->object );
		$builder	= new ADT_JSON_Builder();
		$assertion	= '[1,2.3,"string",true,null,{"a":"test"}]';
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$builder	= new ADT_JSON_Builder();
		$assertion	= "[[1,2],[3,4]]";
		$creation	= $builder->encode( $data );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeStatic()
	{
		$data		= array( 1, 2.3, "string", TRUE, NULL, $this->object );
		$assertion	= '[1,2.3,"string",true,null,{"a":"test"}]';
		$creation	= ADT_JSON_Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

		$data		= array( array( 1, 2 ), array( 3, 4 ) );
		$assertion	= "[[1,2],[3,4]]";
		$creation	= ADT_JSON_Builder::encode( $data );
		$this->assertEquals( $assertion, $creation );

	}

	/**
	 *	Tests Exception of Method 'encodeStatic'.
	 *	@access					public
	 *	@return					void
	 */
	public function testEncodeStaticException()
	{
		$this->expectException( 'InvalidArgumentException' );
		ADT_JSON_Builder::encode( dir( dirname( __FILE__ ) ) );
	}
}
