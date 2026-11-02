<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\ADT\JSON;

use CeusMedia\Common\ADT\JSON\Converter;
use CeusMedia\Common\ADT\JSON\Pretty;
use CeusMedia\Common\ADT\Tree\MagicNode;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of ADT\JSON\Converter and Pretty.
 *	@package		Tests.ADT.JSON
 */
class ConverterTest extends BaseCase
{
	public function testConvertToArray(): void
	{
		self::assertSame( ['a' => [['b' => 1]], '0' => ['c' => 2]], Converter::convertToArray( '{"a":[{"b":1}],"0":{"c":2}}' ) );
	}

	public function testConvertInvalidException(): void
	{
		$this->expectException( InvalidArgumentException::class );
		Converter::convertToArray( '{invalid' );
	}

	public function testPrettyPrint(): void
	{
		$json		= '{"a":"x\\\\","b":[1,2],"c":"q\\"{,:}"}';
		$expected	= "{\n  \"a\": \"x\\\\\",\n  \"b\": [\n    1,\n    2\n  ],\n  \"c\": \"q\\\"{,:}\"\n}";
		self::assertSame( $expected, Pretty::print( $json ) );
		self::assertSame( json_decode( $json, TRUE ), json_decode( Pretty::print( $json ), TRUE ) );
	}

	public function testPrettyValidation(): void
	{
		$this->expectException( InvalidArgumentException::class );
		Pretty::print( '{invalid', TRUE );
	}

	public function testMagicNodeFromInvalidJson(): void
	{
		$this->expectException( InvalidArgumentException::class );
		( new MagicNode() )->fromJson( 'nope' );
	}
}
