<?php
/**
 *	TestUnit of Alg_Text_Unicoder.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Alg_Text_Unicoder.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			Alg_Text_Unicoder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_Alg_Text_UnicoderTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$coder		= new Alg_Text_Unicoder( utf8_decode( "äöüÄÖÜß" ) );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_Text_Unicoder( "äöüÄÖÜß" );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_Text_Unicoder( "äöüÄÖÜß", TRUE );
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
		$creation	= (string) new Alg_Text_Unicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode1()
	{
		$creation	= Alg_Text_Unicoder::isUnicode( "äöüÄÖÜß" );
		$this->assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode2()
	{
		$creation	= Alg_Text_Unicoder::isUnicode( utf8_decode( "äöüÄÖÜß" ) );
		$this->assertEquals( FALSE, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode1()
	{
		$assertion	= "äöüÄÖÜß";
		$creation	= Alg_Text_Unicoder::convertToUnicode( utf8_decode( "äöüÄÖÜß" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode2()
	{
		$assertion	= "äöüÄÖÜß";
		$creation	= Alg_Text_Unicoder::convertToUnicode( "äöüÄÖÜß" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode3()
	{
		$assertion	= utf8_encode( "äöüÄÖÜß" );
		$creation	= Alg_Text_Unicoder::convertToUnicode( "äöüÄÖÜß", TRUE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetString()
	{
		$coder		= new Alg_Text_Unicoder( "abc" );
		$assertion	= "abc";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );

		$coder		= new Alg_Text_Unicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= $coder->getString();
		$this->assertEquals( $assertion, $creation );
	}
}
