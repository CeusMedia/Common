<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg\Text\Unicoder.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Text;

use CeusMedia\Common\Alg\Text\Unicoder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Text\Unicoder.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class UnicoderTest extends BaseCase
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
		$coder		= new Unicoder( utf8_decode( "äöüÄÖÜß" ) );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );

		$coder		= new Unicoder( "äöüÄÖÜß" );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );

		$coder		= new Unicoder( "äöüÄÖÜß", TRUE );
		$assertion	= utf8_encode( "äöüÄÖÜß" );
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'toString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testToString()
	{
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= (string) new Unicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode1()
	{
		$creation	= Unicoder::isUnicode( "äöüÄÖÜß" );
		self::assertEquals( TRUE, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode2()
	{
		$creation	= Unicoder::isUnicode( utf8_decode( "äöüÄÖÜß" ) );
		self::assertEquals( FALSE, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode1()
	{
		$assertion	= "äöüÄÖÜß";
		$creation	= Unicoder::convertToUnicode( utf8_decode( "äöüÄÖÜß" ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode2()
	{
		$assertion	= "äöüÄÖÜß";
		$creation	= Unicoder::convertToUnicode( "äöüÄÖÜß" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode3()
	{
		$assertion	= utf8_encode( "äöüÄÖÜß" );
		$creation	= Unicoder::convertToUnicode( "äöüÄÖÜß", TRUE );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getString'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetString()
	{
		$coder		= new Unicoder( "abc" );
		$assertion	= "abc";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );

		$coder		= new Unicoder( utf8_decode( "ÄÖÜäöü&§$%@µ" ) );
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );
	}
}
