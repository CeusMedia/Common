<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Field;
use CeusMedia\CommonTest\BaseCase;

/**
 *	UnitTest for Request Header Field.
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FieldTest extends BaseCase
{
	public function testConstruct()
	{
		$header	= new Field( "key", "value" );

		$creation	= (bool) strlen( $header->toString() );
		self::assertTrue( $creation );
	}

	public function testToString()
	{
		$header	= new Field( "key", "value" );
		$assertion	= "Key: value";
		$creation	= $header->toString();
		self::assertEquals( $assertion, $creation );

		$header	= new Field( "key-with-more-words", "value" );
		$assertion	= "Key-With-More-Words: value";
		$creation	= $header->toString();
		self::assertEquals( $assertion, $creation );
	}
}
