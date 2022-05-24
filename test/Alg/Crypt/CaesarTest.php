<?php
/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.alg.crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.alg.crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			14.02.2008
 */
class Test_Alg_Crypt_CaesarTest extends Test_Case
{
	public function testEncrypt()
	{
		$assertion	= "nopqrs";
		$creation	= Alg_Crypt_Caesar::encrypt( 'abcdef', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "NOPQRS";
		$creation	= Alg_Crypt_Caesar::encrypt( 'ABCDEF', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Alg_Crypt_Caesar::encrypt( '123456', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Alg_Crypt_Caesar::encrypt( '!"§$%&/()=', 13 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Alg_Crypt_Caesar::decrypt( 'nopqrs', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Alg_Crypt_Caesar::decrypt( '123456', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Alg_Crypt_Caesar::decrypt( '!"§$%&/()=', 13 );
		$this->assertEquals( $assertion, $creation );
	}
}
