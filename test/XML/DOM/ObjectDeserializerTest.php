<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\DOM;

use CeusMedia\Common\XML\DOM\ObjectDeserializer;
use CeusMedia\Common\XML\DOM\ObjectSerializer;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\Object_;

/**
 *	TestUnit of XML DOM Object Deserializer.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ObjectDeserializerTest extends BaseCase
{
	/** @var ObjectDeserializer  */
	protected $deserializer;

	/** @var Object_  */
	protected $object;

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->deserializer	= new ObjectDeserializer();
		$this->object	= new Object_();
		$this->object->null		= NULL;
		$this->object->boolean	= true;
		$this->object->integer	= 1;
		$this->object->float	= (float) 1.23;
		$this->object->double	= (double) 2.34;
		$this->object->string	= "content";
		$this->object->list		= array( "item1", "item2" );
		$this->object->array	= array( "key" => "value" );
		$this->object->child	= new Object_();
		$this->object->child->integer	= 2;

		$serializer	= new ObjectSerializer();
		$xml	= $serializer->serialize( $this->object );
		file_put_contents( dirname( __FILE__ ).'/assets/deserializer.xml', $xml );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeserialize()
	{
		$xml		= file_get_contents( dirname( __FILE__ ).'/assets/deserializer.xml' );
		$assertion	= $this->object;
		$creation	= $this->deserializer->deserialize( $xml );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests that UTF-8 strings, nested arrays and typed properties survive a round trip.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeserializeUtf8AndTypedProperties(): void
	{
		$object	= new ObjectDeserializerTypedExample();
		$object->text	= 'Größe: 5 €';
		$object->count	= 3;
		$object->nested	= ['a' => ['b' => 'ü'], 'list' => [1, 2]];

		$xml	= ObjectSerializer::serialize( $object );
		$result	= ObjectDeserializer::deserialize( $xml );
		self::assertEquals( $object, $result );
		self::assertSame( 'Größe: 5 €', $result->text );
	}
}

class ObjectDeserializerTypedExample
{
	public string $text		= '';
	public int $count		= 0;
	public array $nested	= [];
}
