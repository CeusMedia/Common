<?php
/**
 *	TestUnit of Alg_StringUnicoder.
 *	@package		Tests.
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Alg_StringUnicoder.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_StringUnicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_Alg_StringUnicoderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$coder		= new Alg_StringUnicoder( utf8_decode( "äöüÄÖÜß" ) );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_StringUnicoder( "äöüÄÖÜß" );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_StringUnicoder( "äöüÄÖÜß", TRUE );
		$assertion	= utf8_encode( "äöüÄÖÜß" );
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= (string) new Alg_StringUnicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode()
	{
		$assertion	= TRUE;
		$creation	= Alg_StringUnicoder::isUnicode( "äöüÄÖÜß" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode()
	{
		$assertion	= "äöüÄÖÜß";
		$creation	= Alg_StringUnicoder::convertToUnicode( utf8_decode( "äöüÄÖÜß" ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "äöüÄÖÜß";
		$creation	= Alg_StringUnicoder::convertToUnicode( "äöüÄÖÜß" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= utf8_encode( "äöüÄÖÜß" );
		$creation	= Alg_StringUnicoder::convertToUnicode( "äöüÄÖÜß", TRUE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetString()
	{
		$coder		= new Alg_StringUnicoder( "abc" );
		$assertion	= "abc";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_StringUnicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>