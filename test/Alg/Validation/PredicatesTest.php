<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Predicates.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Validation;

use CeusMedia\Common\Alg\Validation\Predicates;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Predicates.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PredicatesTest extends BaseCase
{
	protected $point;

	function setUp(): void
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
		$creation	= Predicates::hasMaxLength( "test1", 6 );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasMaxLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMaxLengthNegative()
	{
		$creation	= Predicates::hasMaxLength( "test1", 3 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasMinLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMinLengthPositive()
	{
		$creation	= Predicates::hasMinLength( "test1", 4 );
		self::assertTrue( $creation );

		$creation	= Predicates::hasMinLength( "test1", 5 );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasMinLength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasMinLengthNegative()
	{
		$creation	= Predicates::hasMinLength( "test1", 6 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasPasswordScore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordScorePositive()
	{
		//  15
		$creation	= Predicates::hasPasswordScore( 'hansi1', 15 );
		self::assertTrue( $creation );

		//  13
		$creation	= Predicates::hasPasswordScore( 'qweasdyxc', 10 );
		self::assertTrue( $creation );

		//  43
		$creation	= Predicates::hasPasswordScore( 'test123#@', 40 );
		self::assertTrue( $creation );

		//  50
		$creation	= Predicates::hasPasswordScore( 'tEsT123#@', 50 );
		self::assertTrue( $creation );

		//  56
		$creation	= Predicates::hasPasswordScore( '$Up3r$3CuR3#1', 55 );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasPasswordScore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordScoreNegative()
	{
		//  15
		$creation	= Predicates::hasPasswordScore( 'hansi1', 20 );
		self::assertFalse( $creation );

		//  -178
		$creation	= Predicates::hasPasswordScore( 'abc123', 0 );
		self::assertFalse( $creation );

		//  -193
		$creation	= Predicates::hasPasswordScore( 'qwerty', 10 );
		self::assertFalse( $creation );

		//  -299
		$creation	= Predicates::hasPasswordScore( 'sex', 0 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasPasswordStrength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordStrengthPositive()
	{
		//  27
		$creation	= Predicates::hasPasswordStrength( 'hansi1', 20 );
		self::assertTrue( $creation );

		//  23
		$creation	= Predicates::hasPasswordStrength( 'qweasdyxc', 20 );
		self::assertTrue( $creation );

		//  77
		$creation	= Predicates::hasPasswordStrength( 'test123#@', 75 );
		self::assertTrue( $creation );

		//  89
		$creation	= Predicates::hasPasswordStrength( 'tEsT123#@', 89 );
		self::assertTrue( $creation );

		//  100
		$creation	= Predicates::hasPasswordStrength( '$Up3r$3CuR3#1', 99 );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasPasswordStrength'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasPasswordStrengthNegative()
	{
		//  27
		$creation	= Predicates::hasPasswordStrength( 'hansi1', 30 );
		self::assertFalse( $creation );

		//  -178
		$creation	= Predicates::hasPasswordStrength( 'abc123', 0 );
		self::assertFalse( $creation );

		//  -193
		$creation	= Predicates::hasPasswordStrength( 'qwerty', 10 );
		self::assertFalse( $creation );

		//  -299
		$creation	= Predicates::hasPasswordStrength( 'sex', 0 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'hasValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasValuePositive()
	{
		$creation	= Predicates::hasValue( "test" );
		self::assertTrue( $creation );

		$creation	= Predicates::hasValue( "1" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'hasValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasValueNegative()
	{
		$creation	= Predicates::hasValue( "" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterPositive()
	{
		$creation	= Predicates::isAfter( "01.2037", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "01.01.2037", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "01.01.2037 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "2037-01", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "2037-01-01", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "2037-01-01 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "01/2037", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "01/01/2037", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( "01/01/2037 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( date( "d.m.Y" )." 23:59:59", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( date( "Y-m-d" )." 23:59:59", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( date( "m/d/Y" )." 23:59:59", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isAfter( date( "Y-m-d" )." 23:59:59", $this->point );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterNegative()
	{
		$creation	= Predicates::isAfter( "01.2001", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( "01.01.2001", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( "2001-01", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( "2001-01-01", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( "01/01/2001", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( "01/2001", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "m.Y" ), $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "d.m.Y" ), $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "Y-m-d" ), $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "Y-m" ), $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "m/d/Y" ), $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isAfter( date( "m/Y" ), $this->point );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAfterException()
	{
		$this->expectException( "InvalidArgumentException" );
		Predicates::isAfter( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isAlpha'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlpha()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isAlpha( "a" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlpha( "1" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isAlpha( "#" );
		self::assertFalse( $creation );

		$creation	= Predicates::isAlpha( "a#1" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAlphahyphen'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlphahypen()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isAlphahyphen( "a" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphahyphen( "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphahyphen( "-" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphahyphen( "a-1" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isAlphahyphen( "#" );
		self::assertFalse( $creation );

		$creation	= Predicates::isAlphahyphen( "-#-" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAlphaspace'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsAlphaspace()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isAlphaspace( "a" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphaspace( "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphaspace( " " );
		self::assertTrue( $creation );

		$creation	= Predicates::isAlphaspace( "a 1" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isAlphaspace( "#" );
		self::assertFalse( $creation );

		$creation	= Predicates::isAlphaspace( " # " );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isBefore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforePositive()
	{
		$creation	= Predicates::isBefore( "01.2001", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "01.01.2001", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "01.01.2001 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "2001-01", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "2001-01-01", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "2001-01-01 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "01/2001", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "01/01/2001", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( "01/01/2001 01:02:03", $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( date( "Y-m-d" ), $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( date( "m/d/Y" ), $this->point );
		self::assertTrue( $creation );

		$creation	= Predicates::isBefore( date( "d.m.Y" ), $this->point );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isBefore'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforeNegative()
	{
		$creation	= Predicates::isBefore( "01.2037", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( "01.01.2037", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( "2037-01", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( "2037-01-01", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( "01/2037", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( "01/01/2037", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "m.Y" ), $this->point - 24 * 60 * 60 );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "d.m.Y" )." 23:59:59", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "Y-m" ), $this->point - 24 * 60 * 60 );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "Y-m-d" )." 23:59:59", $this->point );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "m/Y" ), $this->point - 24 * 60 * 60 );
		self::assertFalse( $creation );

		$creation	= Predicates::isBefore( date( "m/d/Y" )." 23:59:59", $this->point );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsBeforeException()
	{
		$this->expectException( "InvalidArgumentException" );
		Predicates::isBefore( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isDate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDatePositive()
	{
		$creation	= Predicates::isDate( "01.02.2003" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDate( "02/01/2003" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDate( "2003-02-01" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDate( "02.2003" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDate( "02/2003" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDate( "2003-02" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isDate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDateNegative()
	{
		$creation	= Predicates::isDate( "123" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDate( "abc" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDate( "32.2.2000" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDate( "71.2009" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDate( "40.71.2009" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDate( "2009-40-40" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isDigit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsDigit()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isDigit( "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isDigit( "123" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isDigit( "a" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDigit( "1a3" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDigit( "@" );
		self::assertFalse( $creation );

		$creation	= Predicates::isDigit( "²³" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isEmail'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsEmail()
	{
		$creation	= Predicates::isEmail( "christian.wuerker@ceus-media.de" );
		self::assertTrue( $creation );

		$creation	= Predicates::isEmail( "hans@hans" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isFloat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFloatPositive()
	{
		$creation	= Predicates::isFloat( "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFloat( "1.0" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFloat( "123.456" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isFloat'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFloatNegative()
	{
		$creation	= Predicates::isFloat( "" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( "." );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( ".1" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( ",1" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( "1,0" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( "1.2,3" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFloat( "1.2.3" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isFuture'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFuturePositive()
	{
		$creation	= Predicates::isFuture( "01.2037" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "01.01.2037" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "01.01.2037 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "2037-01" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "2037-01-01" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "2037-01-01 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "01/2037" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "01/01/2037" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( "01/01/2037 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( date( "d.m.Y" )." 23:59:59" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( date( "Y-m-d" )." 23:59:59" );
		self::assertTrue( $creation );

		$creation	= Predicates::isFuture( date( "m/d/Y" )." 23:59:59" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isFuture'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFutureNegative()
	{
		$creation	= Predicates::isFuture( "01.01.2001" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( "2001-01-01" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( "01/2001" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( "01/01/2001" );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( date( "m.Y" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( date( "d.m.Y" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( date( "Y-m-d" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( date( "Y-m-d" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isFuture( date( "m/d/Y" ) );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFutureException()
	{
		$this->expectException( "InvalidArgumentException" );
		Predicates::isFuture( "01.71.2008" );
	}

	/**
	 *	Tests method 'isGreater'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsGreater()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isGreater( "1", 0 );
		self::assertTrue( $creation );

		$creation	= Predicates::isGreater( "1", "0" );
		self::assertTrue( $creation );

		$creation	= Predicates::isGreater( "2", "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isGreater( "-1", "-2" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isGreater( "2", "2" );
		self::assertFalse( $creation );

		$creation	= Predicates::isGreater( "1", "2" );
		self::assertFalse( $creation );

		$creation	= Predicates::isGreater( "-2", "-1" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'idId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsId()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isId( "a1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isId( "aa123bb456cc" );
		self::assertTrue( $creation );

		$creation	= Predicates::isId( "a#1@2:3_4-5.6/7" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isId( "1a" );
		self::assertFalse( $creation );

		$creation	= Predicates::isId( "#a" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isLess'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLessPositive()
	{
		$creation	= Predicates::isLess( "0", 1 );
		self::assertTrue( $creation );

		$creation	= Predicates::isLess( "0", "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isLess( "1", "2" );
		self::assertTrue( $creation );

		$creation	= Predicates::isLess( "-2", "-1" );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isLess'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLessNegative()
	{
		$creation	= Predicates::isLess( "2", "2" );
		self::assertFalse( $creation );

		$creation	= Predicates::isLess( "2", "1" );
		self::assertFalse( $creation );

		$creation	= Predicates::isLess( "-1", "-2" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isLetter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsLetter()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isLetter( "a" );
		self::assertTrue( $creation );

		$creation	= Predicates::isLetter( "abc" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isLetter( "1" );
		self::assertFalse( $creation );

		$creation	= Predicates::isLetter( "1a3" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isMaximum'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsMaximum()
	{
		$creation	= Predicates::isMaximum( "1", 2 );
		self::assertTrue( $creation );

		$creation	= Predicates::isMaximum( "1", "2" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMaximum( "2", "2" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMaximum( "-20", "-10" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMaximum( "-20", "-20" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isMaximum( "3", "2" );
		self::assertFalse( $creation );

		$creation	= Predicates::isMaximum( "-10", "-20" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'inMinimum'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsMinimumPositive()
	{
		$creation	= Predicates::isMinimum( "1", 0 );
		self::assertTrue( $creation );

		$creation	= Predicates::isMinimum( "1", "0" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMinimum( "2", "2" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMinimum( "-10", "-20" );
		self::assertTrue( $creation );

		$creation	= Predicates::isMinimum( "-20", "-20" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isMinimum( "1", "2" );
		self::assertFalse( $creation );

		$creation	= Predicates::isMinimum( "-20", "-10" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isNumeric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsNumeric()
	{
		//  --  POSITIVE  --  //
		$creation	= Predicates::isNumeric( "1" );
		self::assertTrue( $creation );

		$creation	= Predicates::isNumeric( "123" );
		self::assertTrue( $creation );

		//  --  NEGATIVE  --  //
		$creation	= Predicates::isNumeric( "²³" );
		self::assertFalse( $creation );

		$creation	= Predicates::isNumeric( "a" );
		self::assertFalse( $creation );

		$creation	= Predicates::isNumeric( "1a3" );
		self::assertFalse( $creation );

		$creation	= Predicates::isNumeric( "@" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isPast'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastPositive()
	{
		$creation	= Predicates::isPast( "01.2001" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "01.01.2001" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "01.01.2001 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "2001-01" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "2001-01-01" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "2001-01-01 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "01/2001" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "01/01/2001" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( "01/01/2001 01:02:03" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( date( "d.m.Y" ) );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( date( "Y-m-d" ) );
		self::assertTrue( $creation );

		$creation	= Predicates::isPast( date( "m/d/Y" ) );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests method 'isPast'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastNegative()
	{
		$creation	= Predicates::isPast( "01.2037" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( "01.01.2037" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( "2037-01" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( "2037-01-01" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( "01/2037" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( "01/01/2037" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "m.Y" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "Y-m" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "m/Y" ) );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "d.m.Y" )." 23:59:59" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "Y-m-d" )." 23:59:59" );
		self::assertFalse( $creation );

		$creation	= Predicates::isPast( date( "m/d/Y" )." 23:59:59" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isAfter'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPastException()
	{
		$this->expectException( "InvalidArgumentException" );
		Predicates::isPast( "01.71.2008", $this->point );
	}

	/**
	 *	Tests method 'isPreg'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsPreg()
	{
		$creation	= Predicates::isPreg( "1", "@[0-9]+@" );
		self::assertTrue( $creation );

		$creation	= Predicates::isPreg( "1", "@[1-9][0-9]+@" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests method 'isUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUrl()
	{
		$creation	= Predicates::isUrl( "https://ceus-media.de/references.html" );
		self::assertTrue( $creation );

		$creation	= Predicates::isUrl( "ftp://google.de/public/" );
		self::assertTrue( $creation );

		$creation	= Predicates::isUrl( "tp://domain.tld" );
		self::assertFalse( $creation );
	}
}
