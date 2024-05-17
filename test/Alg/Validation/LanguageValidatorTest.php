<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Validation;

use CeusMedia\Common\Alg\Validation\LanguageValidator;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class LanguageValidatorTest extends BaseCase
{
	protected LanguageValidator $validator;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->validator	= new LanguageValidator( array( "en", "fr" ), "en" );
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
	public function testConstructException2()
	{
		$this->expectException( 'RangeException' );
		new LanguageValidator( [] );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException3()
	{
		$this->expectException( 'OutOfRangeException' );
		new LanguageValidator( array( "de" ), "fr" );
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
		self::assertEquals( $assertion, $creation );

		$assertion	= "fr";
		$creation	= $this->validator->getLanguage( "da,fr;q=0.3" );
		self::assertEquals( $assertion, $creation );

		$assertion	= "en";
		$creation	= $this->validator->getLanguage( "" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'validate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValidate()
	{
		$assertion	= "en";
		$creation	= LanguageValidator::validate( "da,en-us;q=0.7,en;q=0.3", array( "en", "fr" ) );
		self::assertEquals( $assertion, $creation );

		$assertion	= "fr";
		$creation	= LanguageValidator::validate( "da,fr;q=0.3", array( "en", "fr" ) );
		self::assertEquals( $assertion, $creation );

		$assertion	= "en";
		$creation	= LanguageValidator::validate( "", array( "en", "fr" ) );
		self::assertEquals( $assertion, $creation );
	}
}
