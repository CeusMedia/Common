<?php
/**
 *	TestUnit of Rot13.
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_Rot13
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
import( 'de.ceus-media.alg.crypt.Rot13' );
/**
 *	TestUnit of Rot13.
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_Rot13
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Alg_Crypt_Rot13Test extends PHPUnit_Framework_TestCase
{
	public function testEncrypt()
	{
		$assertion	= "nopqrs";
		$creation	= Alg_Crypt_Rot13::encrypt( 'abcdef' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Alg_Crypt_Rot13::encrypt( '123456' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Alg_Crypt_Rot13::encrypt( '!"§$%&/()=' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testDecrypt()
	{
		$assertion	= "abcdef";
		$creation	= Alg_Crypt_Rot13::decrypt( 'nopqrs' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "123456";
		$creation	= Alg_Crypt_Rot13::decrypt( '123456' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '!"§$%&/()=';
		$creation	= Alg_Crypt_Rot13::decrypt( '!"§$%&/()=' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>