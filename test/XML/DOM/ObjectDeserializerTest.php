<?php
/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@extends		Test_Case
 *	@uses			XML_DOM_ObjectDeserializer
 *	@uses			XML_DOM_ObjectSerializer
 *	@uses			Test_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2007
 *	@version		0.1
 */
class Test_XML_DOM_ObjectDeserializerTest extends Test_Case
{
	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->deserializer	= new XML_DOM_ObjectDeserializer();
		$this->object	= new Test_Object();
		$this->object->null		= NULL;
		$this->object->boolean	= true;
		$this->object->integer	= 1;
		$this->object->float	= (float) 1.23;
		$this->object->double	= (double) 2.34;
		$this->object->string	= "content";
		$this->object->list		= array( "item1", "item2" );
		$this->object->array	= array( "key" => "value" );
		$this->object->child	= new Test_Object();
		$this->object->child->integer	= 2;

		$serializer	= new XML_DOM_ObjectSerializer();
		$xml	= $serializer->serialize( $this->object );
		file_put_contents( dirname( __FILE__ ).'/deserializer.xml', $xml );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeserialize()
	{
		$xml		= file_get_contents( dirname( __FILE__ ).'/deserializer.xml' );
		$assertion	= $this->object;
		$creation	= $this->deserializer->deserialize( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
