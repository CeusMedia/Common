<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\CrossDomainProxy;
use CeusMedia\CommonTest\BaseCase;
use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;

/**
 *	TestUnit of Net\HTTP\CrossDomainProxy.
 *	@package		Tests.Net.HTTP
 */
class CrossDomainProxyTest extends BaseCase
{
	/**
	 *	Tests that TLS certificate and host are verified by default and verification can be disabled.
	 */
	public function testVerifySslDefault(): void
	{
		$property	= new ReflectionProperty( CrossDomainProxy::class, 'verifySsl' );
		$proxy		= new CrossDomainProxy( 'https://example.org/service' );
		self::assertTrue( $property->getValue( $proxy ) );

		$proxy->setVerifySsl( FALSE );
		self::assertFalse( $property->getValue( $proxy ) );

		$parameter	= ( new ReflectionMethod( CrossDomainProxy::class, 'requestUrl' ) )->getParameters()[4];
		self::assertSame( 'verifySsl', $parameter->getName() );
		self::assertTrue( $parameter->getDefaultValue() );
	}

	public function testUnserializeException(): void
	{
		$method	= new ReflectionMethod( CrossDomainProxy::class, 'unserializeException' );

		$exception	= $method->invoke( NULL, serialize( new RuntimeException( 'remote failure', 42 ) ) );
		self::assertInstanceOf( RuntimeException::class, $exception );
		self::assertSame( 'remote failure', $exception->getMessage() );
		self::assertSame( 42, $exception->getCode() );

		//  no other objects are unserialized
		self::assertNull( $method->invoke( NULL, serialize( new CrossDomainProxyTestGadget() ) ) );
		self::assertFalse( CrossDomainProxyTestGadget::$woken );
		self::assertNull( $method->invoke( NULL, 'plain response' ) );
		self::assertNull( $method->invoke( NULL, serialize( ['a' => 1] ) ) );
	}
}

class CrossDomainProxyTestGadget
{
	public static bool $woken	= FALSE;

	public function __wakeup(): void
	{
		self::$woken	= TRUE;
	}
}
