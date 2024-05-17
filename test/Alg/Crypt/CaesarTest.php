<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Crypt;

use CeusMedia\Common\Alg\Crypt\Caesar;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CaesarTest extends BaseCase
{
	public function testEncrypt()
	{
		$assertion	= "nopqrs";
		$creation	= Caesar::encrypt( 'abcdef', 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "NOPQRS";
		$creation	= Caesar::encrypt( 'ABCDEF', 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Caesar::encrypt( '123456', 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Caesar::encrypt( '!"§$%&/()=', 13 );
		self::assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Caesar::decrypt( 'nopqrs', 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Caesar::decrypt( '123456', 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Caesar::decrypt( '!"§$%&/()=', 13 );
		self::assertEquals( $assertion, $creation );
	}
}
