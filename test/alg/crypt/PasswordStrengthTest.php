<?php
/**
 *	TestUnit of Dictionay
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once '../autoload.php5';
import( 'de.ceus-media.alg.crypt.PasswordStrength' );
/**
 *	TestUnit of Dictionay
 *	@package		Tests.alg.crypt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_PasswordStrengthTest
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Alg_Crypt_PasswordStrengthTest extends PHPUnit_Framework_TestCase
{
	public function testGetScore()
	{
		$assertion	= 15;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'hansi1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 13;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'qweasdyxc' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 43;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'test123#@' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 50;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'tEsT123#@' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 56;
		$creation	= Alg_Crypt_PasswordStrength::getScore( '$Up3r$3CuR3#1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -178;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'abc123' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -193;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'qwerty' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -299;
		$creation	= Alg_Crypt_PasswordStrength::getScore( 'sex' );
		$this->assertEquals( $assertion, $creation );
	}

	public function testGetStrength()
	{
		$assertion	= 27;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'hansi1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 23;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'qweasdyxc' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 77;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'test123#@' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 89;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'tEsT123#@' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( '$Up3r$3CuR3#1' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -178;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'abc123' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -193;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'qwerty' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -299;
		$creation	= Alg_Crypt_PasswordStrength::getStrength( 'sex' );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testNormaliseScore()
	{
		$assertion	= 27;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 15 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 23;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 13 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 77;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 43 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 89;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 50 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 100;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 56 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -1;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( -1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= -100;
		$creation	= Alg_Crypt_PasswordStrength::normaliseScore( -100 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>