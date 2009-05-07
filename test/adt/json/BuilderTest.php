<?php
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			BinaryTree
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
require_once( 'TestObject.php5' );
import( 'de.ceus-media.adt.json.Builder' );
/**
 *	TestUnit of LinkList
 *	@package		Tests.adt.json
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_JSON_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class ADT_JSON_BuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->object		= new TestObject();
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
	 *	@access		public
	 *	@return		void
	 */
	public function testEncodeStaticException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		ADT_JSON_Builder::encode( dir( dirname( __FILE__ ) ) );
	}
}
?>