<?php
/**
 *	TestUnit of Predicates
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.alg.validation.Predicates' );
/**
 *	TestUnit of Predicates
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Tests_Alg_Validation_PredicatesTest extends PHPUnit_Framework_TestCase
{
	public function testHasMaxLength()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 6 );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 3 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testHasMinLength()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 4 );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 5 );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 6 );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testHasPasswordStrength()
	{
	}

	public function testHasValue()
	{
	}

	public function testIsAfter()
	{
	}

	public function testIsAll()
	{
	}

	public function testIsAlpha()
	{
	}

	public function testIsAlphahypen()
	{
	}

	public function testIsAlphaspace()
	{
	}

	public function testIsAlphasymbol()
	{
	}

	public function testIsAtleast()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAtleast( 1, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAtleast( "1", "0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAtleast( "2", "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAtleast( "2", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAtleast( "1", "2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAtleast( "-1", "-2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAtleast( "-2", "-1" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsBefore()
	{
	}

	public function testIsDigit()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDigit( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDigit( "123" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDigit( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDigit( "1a3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDigit( "@" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDigit( "²³" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsDotnumeric()
	{
	}
	
	public function testIsEmail()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isEmail( "christian.wuerker@ceus-media.de" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isEmail( "hans@hans" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsEreg()
	{
	}

	public function testIsEregi()
	{
	}

	public function testIsFloat()
	{
	}


	public function testIsFuture()
	{
	}

	public function testIsGreater()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isGreater( 1, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isGreater( "1", "0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isGreater( "2", "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isGreater( "2", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isGreater( "1", "2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isGreater( "-1", "-2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isGreater( "-2", "-1" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsId()
	{
	}

	public function testIsLess()
	{
	}

	public function testIsLetter()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLetter( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLetter( "abc" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isLetter( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isLetter( "1a3" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsNumeric()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isNumeric( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isNumeric( "123" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isNumeric( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isNumeric( "1a3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isNumeric( "@" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isNumeric( "²³" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsPast()
	{
	}

	public function testIsPreg()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPreg( "1", "@[0-9]+@" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPreg( "1", "@[1-9][0-9]+@" );
		$this->assertEquals( $assertion, $creation );
	}
	
	public function testIsUrl()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isUrl( "http://ceus-media.de/references.html" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isUrl( "ftp://google.de/public/" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isUrl( "tp://domain.tld" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>