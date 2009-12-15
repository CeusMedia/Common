<?php
/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@uses			Test_Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_ObjectSerializer
 *	@uses			Test_Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class Test_XML_DOM_ObjectSerializerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->fileName		= dirname( __FILE__ ).'/serializer.xml';
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->serializer	= new XML_DOM_ObjectSerializer();
		$this->object		= new Test_Object();
		$this->object->string	= "content";
		$this->object->integer	= 1;
		$this->object->boolean	= true;
		$this->object->list		= array( "item1", "item2" );
		$this->object->array	= array( "key" => "value" );
		$this->object->child	= new Test_Object();
		$this->object->child->integer	= 2;
#		$xml	= $this->serializer->serialize( $this->object );
#		file_put_contents( $this->fileName, $xml );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSerialize()
	{
		$assertion	= file_get_contents( $this->fileName );
		$creation	= $this->serializer->serialize( $this->object );
		$this->assertEquals( $assertion, $creation );
	}
}
?>