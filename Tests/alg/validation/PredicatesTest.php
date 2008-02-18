<?php
/**
 *	TestUnit of Predicates.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Crypt_PasswordStrength
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.alg.validation.Predicates' );
import( 'de.ceus-media.alg.crypt.PasswordStrength' );
/**
 *	TestUnit of Predicates.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Crypt_PasswordStrength
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
	
	public function testHasPasswordScore()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'hansi1', 15 );				//  15
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'qweasdyxc', 10 );			//  13
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'test123#@', 40 );			//  43
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'tEsT123#@', 50 );			//  50
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( '$Up3r$3CuR3#1', 55 );		//  56
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'hansi1', 20 );				//  15
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'abc123', 0 );				//  -178
		$this->assertEquals( $assertion, $creation );

		$assertion	= false ;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'qwerty', 10 );				//  -193
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'sex', 0 );					//  -299
		$this->assertEquals( $assertion, $creation );
	}

	public function testHasPasswordStrength()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'hansi1', 20 );				//  27
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'qweasdyxc', 20 );			//  23
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'test123#@', 75 );			//  77
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'tEsT123#@', 89 );			//  89
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( '$Up3r$3CuR3#1', 99 );		//  100
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'hansi1', 30 );				//  27
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'abc123', 0 );				//  -178
		$this->assertEquals( $assertion, $creation );

		$assertion	= false ;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'qwerty', 10 );				//  -193
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'sex', 0 );					//  -299
		$this->assertEquals( $assertion, $creation );
	}

	public function testHasValue()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasValue( "test" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasValue( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasValue( "" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsAfter()
	{
		$point	= time();
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2037", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2037 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "2037-01-01", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "2037-01-01 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2037", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2037 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2001", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( "2001-01-01", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2001", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( date( "d.m.Y" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( date( "d.m.Y" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m-d" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m-d" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAfter( date( "m/d/Y" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAfter( date( "m/d/Y" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsAll()
	{
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
	}

	public function testIsAlpha()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlpha( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlpha( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlpha( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlpha( "#" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlpha( "a#1" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsAlphahypen()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "-" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "a-1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "#" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "-#-" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsAlphaspace()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphaspace( "a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphaspace( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphaspace( 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphaspace( " " );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isAlphaspace( "a 1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlphaspace( "#" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isAlphaspace( " # " );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsAlphasymbol()
	{
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
	}

	public function testIsBefore()
	{
		$point	= time();
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001 01:02:03", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2037", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "2037-01-01", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2037", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" ), $point );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" )." 23:59:59", $point );
		$this->assertEquals( $assertion, $creation );
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
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDotnumeric( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDotnumeric( "1.0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDotnumeric( ".1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isDotnumeric( "123.456" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDotnumeric( "." );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isDotnumeric( "1.2.3" );
		$this->assertEquals( $assertion, $creation );
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
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
	}

	public function testIsEregi()
	{
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
	}

	public function testIsFloat()
	{
		throw new PHPUnit_Framework_IncompleteTestError( 'Dieser Test ist noch nicht fertig ausprogrammiert.' );
/*		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFloat( "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFloat( "1.0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFloat( "1,0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFloat( ".1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFloat( ",1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFloat( "123.456" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFloat( "." );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFloat( "1.2,3" );
		$this->assertEquals( $assertion, $creation );
*/	}


	public function testIsFuture()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2037" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2037 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "2037-01-01" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "2037-01-01 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2037" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2037 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2001" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( "2001-01-01" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2001" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( date( "d.m.Y" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( date( "d.m.Y" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( date( "Y-m-d" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( date( "Y-m-d" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isFuture( date( "m/d/Y" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isFuture( date( "m/d/Y" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );
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
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isId( "a1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isId( "aa123bb456cc" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isId( "a#1@2:3_4-5.6/7" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isId( "1a" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isId( "#a" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsLess()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLess( 0, 1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLess( "0", "1" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLess( "1", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isLess( "2", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isLess( "2", "1" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isLess( "-2", "-1" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isLess( "-1", "-2" );
		$this->assertEquals( $assertion, $creation );
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

	public function testIsMinimum()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMinimum( 1, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMinimum( "1", "0" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMinimum( "2", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isMinimum( "1", "2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMinimum( "-10", "-20" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMinimum( "-20", "-20" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isMinimum( "-20", "-10" );
		$this->assertEquals( $assertion, $creation );
	}

	public function testIsMaximum()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMaximum( 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMaximum( "1", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMaximum( "2", "2" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isMaximum( "3", "2" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMaximum( "-20", "-10" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isMaximum( "-20", "-20" );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isMaximum( "-10", "-20" );
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
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001 01:02:03" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "01.01.2037" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "2037-01-01" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( "01/01/2037" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= false;
		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" )." 23:59:59" );
		$this->assertEquals( $assertion, $creation );
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