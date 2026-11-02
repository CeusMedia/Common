<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\XML\RPC;

use CeusMedia\Common\XML\RPC\Client;
use CeusMedia\CommonTest\BaseCase;
use ReflectionMethod;

/**
 *	TestUnit of XML\RPC\Client (without network).
 *	@package		Tests.XML.RPC
 */
class ClientTest extends BaseCase
{
	public function testEncodeEscapesAndWrapsValues(): void
	{
		$method	= new ReflectionMethod( Client::class, 'encodeXmlParameter' );
		$xml	= $method->invoke( NULL, (object) ['password' => 'a<b&c', 'list' => [1, TRUE]] );
		self::assertSame(
			'<value><struct>'
				.'<member><name>password</name><value><string>a&lt;b&amp;c</string></value></member>'
				.'<member><name>list</name><value><array><data><value><int>1</int></value><value><boolean>1</boolean></value></data></array></value></member>'
			.'</struct></value>',
			$xml
		);
		self::assertNotFalse( simplexml_load_string( '<param>'.$xml.'</param>' ) );
	}

	public function testParseResponse(): void
	{
		$method	= new ReflectionMethod( Client::class, 'parseResponse' );
		$xml	= '<methodResponse><params><param><value><struct>'
			.'<member><name>a</name><value><int>1</int></value></member>'
			.'<member><name>b</name><value>plain</value></member>'
			.'<member><name>c</name><value><array><data><value><string>x</string></value><value><boolean>1</boolean></value></data></array></value></member>'
			.'</struct></value></param></params></methodResponse>';
		self::assertSame( [['a' => 1, 'b' => 'plain', 'c' => ['x', TRUE]]], $method->invoke( NULL, $xml ) );
	}

	public function testParseFault(): void
	{
		$method	= new ReflectionMethod( Client::class, 'parseResponse' );
		$xml	= '<methodResponse><fault><value><struct>'
			.'<member><name>faultCode</name><value><int>4</int></value></member>'
			.'<member><name>faultString</name><value><string>Too many parameters</string></value></member>'
			.'</struct></value></fault></methodResponse>';
		//  faults are not thrown in 1.0.x (from 1.2 on, a RuntimeException is thrown)
		self::assertSame( [], $method->invoke( NULL, $xml ) );
	}
}
