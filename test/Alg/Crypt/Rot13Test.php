<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Rot13.
 *	@package		Tests.Alg.Crypt
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test;

use CeusMedia\Common\Alg\Crypt\Rot13;
use CeusMedia\Common\Test\BaseCase;

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
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Rot13::encrypt( '123456' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Rot13::encrypt( '!"§$%&/()=' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Rot13::decrypt( 'nopqrs' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Rot13::decrypt( '123456' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Rot13::decrypt( '!"§$%&/()=' );
		$this->assertEquals( $assertion, $creation );
	}
}
