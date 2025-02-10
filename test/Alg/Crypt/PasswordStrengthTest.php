<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Dictionary
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Crypt;

use CeusMedia\Common\Alg\Crypt\PasswordStrength;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Dictionary
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PasswordStrengthTest extends BaseCase
{
	public function testGetScore()
	{
		$assertion	= 15;
		$creation	= PasswordStrength::getScore( 'hansi1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 13;
		$creation	= PasswordStrength::getScore( 'qweasdyxc' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 43;
		$creation	= PasswordStrength::getScore( 'test123#@' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 50;
		$creation	= PasswordStrength::getScore( 'tEsT123#@' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 56;
		$creation	= PasswordStrength::getScore( '$Up3r$3CuR3#1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -178;
		$creation	= PasswordStrength::getScore( 'abc123' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -193;
		$creation	= PasswordStrength::getScore( 'qwerty' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -299;
		$creation	= PasswordStrength::getScore( 'sex' );
		self::assertEquals( $assertion, $creation );
	}

	public function testGetStrength()
	{
		$assertion	= 27;
		$creation	= PasswordStrength::getStrength( 'hansi1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 23;
		$creation	= PasswordStrength::getStrength( 'qweasdyxc' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 77;
		$creation	= PasswordStrength::getStrength( 'test123#@' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 89;
		$creation	= PasswordStrength::getStrength( 'tEsT123#@' );
		self::assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= PasswordStrength::getStrength( '$Up3r$3CuR3#1' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -178;
		$creation	= PasswordStrength::getStrength( 'abc123' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -193;
		$creation	= PasswordStrength::getStrength( 'qwerty' );
		self::assertEquals( $assertion, $creation );

		$assertion	= -299;
		$creation	= PasswordStrength::getStrength( 'sex' );
		self::assertEquals( $assertion, $creation );
	}

	public function testNormaliseScore()
	{
		$assertion	= 27;
		$creation	= PasswordStrength::normaliseScore( 15 );
		self::assertEquals( $assertion, $creation );

		$assertion	= 23;
		$creation	= PasswordStrength::normaliseScore( 13 );
		self::assertEquals( $assertion, $creation );

		$assertion	= 77;
		$creation	= PasswordStrength::normaliseScore( 43 );
		self::assertEquals( $assertion, $creation );

		$assertion	= 89;
		$creation	= PasswordStrength::normaliseScore( 50 );
		self::assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= PasswordStrength::normaliseScore( 56 );
		self::assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= PasswordStrength::normaliseScore( 0 );
		self::assertEquals( $assertion, $creation );

		$assertion	= -1;
		$creation	= PasswordStrength::normaliseScore( -1 );
		self::assertEquals( $assertion, $creation );

		$assertion	= -100;
		$creation	= PasswordStrength::normaliseScore( -100 );
		self::assertEquals( $assertion, $creation );
	}
}
