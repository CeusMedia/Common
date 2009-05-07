<?php
/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_ObjectDeserializer
 *	@uses			XML_DOM_ObjectSerializer
 *	@uses			TestObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
import( 'de.ceus-media.xml.dom.ObjectDeserializer' );
import( 'de.ceus-media.xml.dom.ObjectSerializer' );
require_once ( 'TestObject.php5' );
/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_ObjectDeserializer
 *	@uses			XML_DOM_ObjectSerializer
 *	@uses			TestObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class XML_DOM_ObjectDeserializerTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->deserializer	= new XML_DOM_ObjectDeserializer();
		$this->object	= new TestObject();
		$this->object->null		= NULL;
		$this->object->boolean	= true;
		$this->object->integer	= 1;
		$this->object->float	= (float) 1.23;
		$this->object->double	= (double) 2.34;
		$this->object->string	= "content";
		$this->object->list		= array( "item1", "item2" );
		$this->object->array	= array( "key" => "value" );
		$this->object->child	= new TestObject();
		$this->object->child->integer	= 2;

		$serializer	= new XML_DOM_ObjectSerializer();
		$xml	= $serializer->serialize( $this->object );
		file_put_contents( "xml/dom/deserializer.xml", $xml );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeserialize()
	{
		$xml		= file_get_contents( "xml/dom/deserializer.xml" );
		$assertion	= $this->object;
		$creation	= $this->deserializer->deserialize( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
?>