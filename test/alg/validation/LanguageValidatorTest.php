<?php
/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.alg.validation.LanguageValidator' );
/**
 *	TestUnit of Alg_Validation_LanguageValidator.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Alg_Validation_LanguageValidatorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->validator	= new Alg_Validation_LanguageValidator( array( "en", "fr" ), "en" );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		Alg_Validation_LanguageValidator::__construct( "string" );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException2()
	{
		$this->setExpectedException( 'RangeException' );
		Alg_Validation_LanguageValidator::__construct( array() );
	}

	/**
	 *	Tests Exception of Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException3()
	{
		$this->setExpectedException( 'Exception' );
		Alg_Validation_LanguageValidator::__construct( array( "de" ), "fr" );
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
?>