<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Rot13.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Crypt;

use CeusMedia\Common\Alg\Crypt\Rot13;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Rot13.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Rot13Test extends BaseCase
{
	public function testEncrypt()
	{
		$assertion	= "nopqrs";
		$creation	= Rot13::encrypt( 'abcdef' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Rot13::encrypt( '123456' );
		self::assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Rot13::encrypt( '!"§$%&/()=' );
		self::assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Rot13::decrypt( 'nopqrs' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Rot13::decrypt( '123456' );
		self::assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Rot13::decrypt( '!"§$%&/()=' );
		self::assertEquals( $assertion, $creation );
	}
}
