<?php
/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.alg.validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.alg.validation
 *	@extends		Test_Case
 *	@uses			Alg_Validation_LanguageValidator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_Alg_Validation_LanguageValidatorTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->validator	= new Alg_Validation_LanguageValidator( array( "en", "fr" ), "en" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		new Alg_Validation_LanguageValidator( "string" );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->expectException( 'RangeException' );
		new Alg_Validation_LanguageValidator( array() );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException3()
	{
		$this->expectException( 'OutOfRangeException' );
		new Alg_Validation_LanguageValidator( array( "de" ), "fr" );
	}

	/**
	 *	Tests Method 'getLanguage'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetLanguage()
	{
		$assertion	= "en";
		$creation	= $this->validator->getLanguage( "da,en-us;q=0.7,en;q=0.3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "fr";
		$creation	= $this->validator->getLanguage( "da,fr;q=0.3" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "en";
		$creation	= $this->validator->getLanguage( "" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$assertion	= "en";
		$creation	= Alg_Validation_LanguageValidator::validate( "da,en-us;q=0.7,en;q=0.3", array( "en", "fr" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "fr";
		$creation	= Alg_Validation_LanguageValidator::validate( "da,fr;q=0.3", array( "en", "fr" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "en";
		$creation	= Alg_Validation_LanguageValidator::validate( "", array( "en", "fr" ) );
		$this->assertEquals( $assertion, $creation );
	}
}
