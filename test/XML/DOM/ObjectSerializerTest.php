<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@uses			Test_Object
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\ObjectSerializer;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\Object_ as TestObject;

/**
 *	TestUnit of XML DOM Object Serializer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ObjectSerializerTest extends BaseCase
{
	/** @var string  */
	protected $fileName;

	/** @var ObjectSerializer  */
	protected $serializer;

	/** @var TestObject  */
	protected $object;

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ ).'/assets/serializer.xml';
		$this->serializer		= new ObjectSerializer();
		$this->object			= new TestObject();
		$this->object->string	= "content";
		$this->object->integer	= 1;
		$this->object->boolean	= true;
		$this->object->list		= array( "item1", "item2" );
		$this->object->array	= array( "key" => "value" );
		$this->object->child	= new TestObject();
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
		self::assertEquals( $assertion, $creation );
	}
}
