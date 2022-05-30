<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg\Crypt;

use CeusMedia\Common\Alg\Crypt\Caesar;
use CeusMedia\Common\Test\BaseCase;

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
		$this->assertEquals( $assertion, $creation );

		$assertion	= "NOPQRS";
		$creation	= Caesar::encrypt( 'ABCDEF', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Caesar::encrypt( '123456', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Caesar::encrypt( '!"§$%&/()=', 13 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Caesar::decrypt( 'nopqrs', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Caesar::decrypt( '123456', 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Caesar::decrypt( '!"§$%&/()=', 13 );
		$this->assertEquals( $assertion, $creation );
	}
}
