<?php
/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@uses			Test_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2007
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			11.12.2007
 *
 */
class Test_XML_DOM_ObjectSerializerTest extends BaseCase
{
	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ ).'/serializer.xml';
		$this->serializer		= new XML_DOM_ObjectSerializer();
		$this->object			= new Test_Object();
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
