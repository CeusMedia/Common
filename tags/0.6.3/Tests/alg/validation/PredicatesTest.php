g98<?php
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
	function setUp()
	{
		$this->point	= time();
	}

	/**
	 *	Tests method 'hasMaxLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMaxLengthPositive()
	{
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 6 );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasMaxLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMaxLengthNegative()
	{
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 3 );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasMinLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMinLengthPositive()
	{
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 4 );
		$this->assertTrue( $creation );
	
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 5 );
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests method 'hasMinLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMinLengthNegative()
	{
		$creation	= Alg_Validation_Predicates::hasMinLength( "test1", 6 );
		$this->assertFalse( $creation );
	}
	
	/**
	 *	Tests method 'hasPasswordScore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordScorePositive()
	{
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'hansi1', 15 );				//  15
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'qweasdyxc', 10 );			//  13
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'test123#@', 40 );			//  43
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'tEsT123#@', 50 );			//  50
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( '$Up3r$3CuR3#1', 55 );		//  56
		$this->assertTrue( $creation );
	}
	
	/**
	 *	Tests method 'hasPasswordScore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordScoreNegative()
	{
		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'hansi1', 20 );				//  15
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'abc123', 0 );				//  -178
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'qwerty', 10 );				//  -193
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordScore( 'sex', 0 );					//  -299
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasPasswordStrength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordStrengthPositive()
	{
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'hansi1', 20 );				//  27
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'qweasdyxc', 20 );			//  23
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'test123#@', 75 );			//  77
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'tEsT123#@', 89 );			//  89
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( '$Up3r$3CuR3#1', 99 );		//  100
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasPasswordStrength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordStrengthNegative()
	{
		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'hansi1', 30 );				//  27
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'abc123', 0 );				//  -178
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'qwerty', 10 );				//  -193
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::hasPasswordStrength( 'sex', 0 );					//  -299
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasValuePositive()
	{
		$creation	= Alg_Validation_Predicates::hasValue( "test" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::hasValue( 1 );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasValueNegative()
	{
		$creation	= Alg_Validation_Predicates::hasValue( "" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterPositive()
	{
		$creation	= Alg_Validation_Predicates::isAfter( "01.2037", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2037", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2037 01:02:03", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "2037-01", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "2037-01-01", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "2037-01-01 01:02:03", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01/2037", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2037", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2037 01:02:03", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "d.m.Y" )." 23:59:59", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m-d" )." 23:59:59", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "m/d/Y" )." 23:59:59", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m-d" )." 23:59:59", $this->point );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterNegative()
	{
		$creation	= Alg_Validation_Predicates::isAfter( "01.2001", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01.01.2001", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "2001-01", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "2001-01-01", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01/01/2001", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( "01/2001", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "m.Y" ), $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "d.m.Y" ), $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m-d" ), $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "Y-m" ), $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "m/d/Y" ), $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAfter( date( "m/Y" ), $this->point );
		$this->assertFalse( $creation );
	}
	
	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterException()
	{
		$this->setExpectedException( "InvalidArgumentException" );
		Alg_Validation_Predicates::isAfter( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isAlpha'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlpha()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlpha( "a" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlpha( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlpha( 1 );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlpha( "#" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAlpha( "a#1" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAlphahyphen'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlphahypen()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "a" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphahyphen( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphahyphen( 1 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphahyphen( "-" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphahyphen( "a-1" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlphahyphen( "#" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAlphahyphen( "-#-" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAlphaspace'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlphaspace()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlphaspace( "a" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphaspace( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphaspace( 1 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphaspace( " " );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isAlphaspace( "a 1" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isAlphaspace( "#" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isAlphaspace( " # " );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isBefore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforePositive()
	{
		$creation	= Alg_Validation_Predicates::isBefore( "01.2001", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01.01.2001", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01.01.2001 01:02:03", $this->point );

		$creation	= Alg_Validation_Predicates::isBefore( "2001-01", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "2001-01-01", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "2001-01-01 01:02:03", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01/2001", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01/01/2001", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01/01/2001 01:02:03", $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "Y-m-d" ), $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "m/d/Y" ), $this->point );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "d.m.Y" ), $this->point );
		$this->assertTrue( $creation );
	}
		
	/**
	 *	Tests method 'isBefore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforeNegative()
	{
		$creation	= Alg_Validation_Predicates::isBefore( "01.2037", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01.01.2037", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "2037-01", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "2037-01-01", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01/2037", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( "01/01/2037", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "m.Y" ), $this->point - 24 * 60 * 60 );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "d.m.Y" )." 23:59:59", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "Y-m" ), $this->point - 24 * 60 * 60 );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "Y-m-d" )." 23:59:59", $this->point );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "m/Y" ), $this->point - 24 * 60 * 60 );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isBefore( date( "m/d/Y" )." 23:59:59", $this->point );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforeException()
	{
		$this->setExpectedException( "InvalidArgumentException" );
		Alg_Validation_Predicates::isBefore( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isDate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDatePositive()
	{
		$creation	= Alg_Validation_Predicates::isDate( "01.02.2003" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "02/01/2003" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "2003-02-01" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "02.2003" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "02/2003" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "2003-02" );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isDate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDateNegative()
	{
		$creation	= Alg_Validation_Predicates::isDate( "123" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "abc" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "32.2.2000" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "71.2009" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "40.71.2009" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDate( "2009-40-40" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isDigit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDigit()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isDigit( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isDigit( "123" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isDigit( "a" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDigit( "1a3" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDigit( "@" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isDigit( "²³" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isEmail'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEmail()
	{
		$creation	= Alg_Validation_Predicates::isEmail( "christian.wuerker@ceus-media.de" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isEmail( "hans@hans" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isEreg'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEreg()
	{
		$creation	= Alg_Validation_Predicates::isEreg( "1", "^[[:digit:]]+$" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isEreg( "a", "^[[:lower:]]+$" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isEreg( "a", "^[[:upper:]]+$" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isEregi'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEregi()
	{
		$creation	= Alg_Validation_Predicates::isEregi( "1", "^[[:digit:]]+$" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isEregi( "a", "^[[:lower:]]+$" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isEregi( "a", "^[[:upper:]]+$" );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isFloat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFloatPositive()
	{
		$creation	= Alg_Validation_Predicates::isFloat( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "1.0" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "123.456" );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isFloat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFloatNegative()
	{
		$creation	= Alg_Validation_Predicates::isFloat( "" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "." );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( ".1" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( ",1" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "1,0" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "1.2,3" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFloat( "1.2.3" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isFuture'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFuturePositive()
	{
		$creation	= Alg_Validation_Predicates::isFuture( "01.2037" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2037" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2037 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "2037-01" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "2037-01-01" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "2037-01-01 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01/2037" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2037" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2037 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "d.m.Y" )." 23:59:59" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "Y-m-d" )." 23:59:59" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "m/d/Y" )." 23:59:59" );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isFuture'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFutureNegative()
	{
		$creation	= Alg_Validation_Predicates::isFuture( "01.01.2001" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "2001-01-01" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01/2001" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( "01/01/2001" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "m.Y" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "d.m.Y" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "Y-m-d" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "Y-m-d" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isFuture( date( "m/d/Y" ) );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFutureException()
	{
		$this->setExpectedException( "InvalidArgumentException" );
		Alg_Validation_Predicates::isFuture( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isGreater'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsGreater()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isGreater( 1, 0 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isGreater( "1", "0" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isGreater( "2", "1" );
		$this->assertTrue( $creation );
		
		$creation	= Alg_Validation_Predicates::isGreater( "-1", "-2" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isGreater( "2", "2" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isGreater( "1", "2" );
		$this->assertFalse( $creation );
		
		$creation	= Alg_Validation_Predicates::isGreater( "-2", "-1" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'idId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsId()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isId( "a1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isId( "aa123bb456cc" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isId( "a#1@2:3_4-5.6/7" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isId( "1a" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isId( "#a" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isLess'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLessPositive()
	{
		$creation	= Alg_Validation_Predicates::isLess( 0, 1 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isLess( "0", "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isLess( "1", "2" );
		$this->assertTrue( $creation );
		
		$creation	= Alg_Validation_Predicates::isLess( "-2", "-1" );
		$this->assertTrue( $creation );
	}
		
	/**
	 *	Tests method 'isLess'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLessNegative()
	{
		$creation	= Alg_Validation_Predicates::isLess( "2", "2" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isLess( "2", "1" );
		$this->assertFalse( $creation );
		
		$creation	= Alg_Validation_Predicates::isLess( "-1", "-2" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isLetter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLetter()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isLetter( "a" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isLetter( "abc" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isLetter( "1" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isLetter( "1a3" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isMaximum'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsMaximum()
	{
		$creation	= Alg_Validation_Predicates::isMaximum( 1, 2 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isMaximum( "1", "2" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isMaximum( "2", "2" );
		$this->assertTrue( $creation );
		
		$creation	= Alg_Validation_Predicates::isMaximum( "-20", "-10" );
		$this->assertTrue( $creation );
		
		$creation	= Alg_Validation_Predicates::isMaximum( "-20", "-20" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isMaximum( "3", "2" );
		$this->assertFalse( $creation );
		
		$creation	= Alg_Validation_Predicates::isMaximum( "-10", "-20" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'inMinimum'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsMinimumPositive()
	{
		$creation	= Alg_Validation_Predicates::isMinimum( 1, 0 );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isMinimum( "1", "0" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isMinimum( "2", "2" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isMinimum( "-10", "-20" );
		$this->assertTrue( $creation );
		
		$creation	= Alg_Validation_Predicates::isMinimum( "-20", "-20" );
		$this->assertTrue( $creation );
		
		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isMinimum( "1", "2" );
		$this->assertFalse( $creation );
		
		$creation	= Alg_Validation_Predicates::isMinimum( "-20", "-10" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isNumeric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsNumeric()
	{
		//  --  POSITIVE  --  //
		$creation	= Alg_Validation_Predicates::isNumeric( "1" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isNumeric( "123" );
		$this->assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Alg_Validation_Predicates::isNumeric( "²³" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isNumeric( "a" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isNumeric( "1a3" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isNumeric( "@" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isPast'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastPositive()
	{
		$creation	= Alg_Validation_Predicates::isPast( "01.2001" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01.01.2001 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "2001-01" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "2001-01-01 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01/2001" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01/01/2001 01:02:03" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" ) );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" ) );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" ) );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests method 'isPast'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastNegative()
	{
		$creation	= Alg_Validation_Predicates::isPast( "01.2037" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01.01.2037" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "2037-01" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "2037-01-01" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01/2037" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( "01/01/2037" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "m.Y" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "m/Y" ) );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "d.m.Y" )." 23:59:59" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "Y-m-d" )." 23:59:59" );
		$this->assertFalse( $creation );

		$creation	= Alg_Validation_Predicates::isPast( date( "m/d/Y" )." 23:59:59" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastException()
	{
		$this->setExpectedException( "InvalidArgumentException" );
		Alg_Validation_Predicates::isPast( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isPreg'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPreg()
	{
		$creation	= Alg_Validation_Predicates::isPreg( "1", "@[0-9]+@" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isPreg( "1", "@[1-9][0-9]+@" );
		$this->assertFalse( $creation );
	}
	
	/**
	 *	Tests method 'isUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUrl()
	{
		$creation	= Alg_Validation_Predicates::isUrl( "http://ceus-media.de/references.html" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isUrl( "ftp://google.de/public/" );
		$this->assertTrue( $creation );

		$creation	= Alg_Validation_Predicates::isUrl( "tp://domain.tld" );
		$this->assertFalse( $creation );
	}
}
?>