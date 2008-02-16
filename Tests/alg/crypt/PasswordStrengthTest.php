<?php
/**
 *	TestUnit of Dictionay
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.alg.crypt.PasswordStrength' );
/**
 *	TestUnit of Dictionay
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Crypt_PasswordStrengthTest
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Tests_Alg_Crypt_PasswordStrengthTest extends PHPUnit_Framework_TestCase
{
	public function testGetScore()
	{
		$assertion	= 15;
		$creation	= Alg_Crypt_PasswordStrength::getScore( "hansi1" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>