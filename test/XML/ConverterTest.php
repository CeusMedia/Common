<?php

declare( strict_types = 1 );

/**
 *	TestUnit of XML Converter.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML;

use CeusMedia\Common\XML\Converter;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML Converter.
 *	@package		Tests.xml
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ConverterTest extends BaseCase
{
	protected string $fileRead;

	public function testToJson(): void
	{
		$xml	= '<?xml version="1.0"?><root><status>data</status><data>1</data><dev>3</dev></root>';
		$json	= Converter::toJson( $xml );
		self::assertIsString( $json );
		self::assertJson( $json );

		$object	= json_decode( $json, FALSE );
		self::assertEquals( 'data', $object->root->children->status->content );
		self::assertEquals( '1', $object->root->children->data->content );
		self::assertEquals( '3', $object->root->children->dev->content );
	}

	public function testToPlainObject(): void
	{
		$xml	= '<?xml version="1.0"?><root><status>data</status><data>1</data><dev>3</dev></root>';
		$object	= Converter::toPlainObject( $xml );
		self::assertEquals( 'data', $object->root->children->status->content );
		self::assertEquals( '1', $object->root->children->data->content );
		self::assertEquals( '3', $object->root->children->dev->content );
	}

	protected function setUp(): void
	{
		$this->fileRead		= dirname( __FILE__ ).'/assets/element_reader.xml';
		$this->xml			= file_get_contents( $this->fileRead );
	}
}
