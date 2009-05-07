<?php
/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_Caesar
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
import( 'de.ceus-media.alg.crypt.Caesar' );
/**
 *	TestUnit of Caesar Crypt.
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_Caesar
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Alg_Crypt_CaesarTest extends PHPUnit_Framework_TestCase
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
?>