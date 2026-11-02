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
		$coder		= new Unicoder( mb_convert_encoding( "äöüÄÖÜß", 'ISO-8859-1', 'UTF-8' ) );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );

		$coder		= new Unicoder( "äöüÄÖÜß" );
		$assertion	= "äöüÄÖÜß";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );

		$coder		= new Unicoder( "äöüÄÖÜß", TRUE );
		$assertion	= mb_convert_encoding( "äöüÄÖÜß", 'UTF-8', 'ISO-8859-1' );
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
		$creation	= (string) new Unicoder( mb_convert_encoding( "ÄÖÜäöü&§$%@µ", 'ISO-8859-1', 'UTF-8' ) );
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
		$creation	= Unicoder::isUnicode( mb_convert_encoding( "äöüÄÖÜß", 'ISO-8859-1', 'UTF-8' ) );
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
		$creation	= Unicoder::convertToUnicode( mb_convert_encoding( "äöüÄÖÜß", 'ISO-8859-1', 'UTF-8' ) );
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
		$assertion	= mb_convert_encoding( "äöüÄÖÜß", 'UTF-8', 'ISO-8859-1' );
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

		$coder		= new Unicoder( mb_convert_encoding( "ÄÖÜäöü&§$%@µ", 'ISO-8859-1', 'UTF-8' ) );
		$assertion	= "ÄÖÜäöü&§$%@µ";
		$creation	= $coder->getString();
		self::assertEquals( $assertion, $creation );
	}
}
