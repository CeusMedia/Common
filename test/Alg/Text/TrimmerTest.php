<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg\Text\Trimmer.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Text;

use CeusMedia\Common\Alg\Text\Trimmer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Alg\Text\Trimmer.
 *	@package		Tests.Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TrimmerTest extends BaseCase
{
	/**	@var		string		Default test string */
	protected $string;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->string	= "abcdefghijklmnop";
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
	 *	Tests Method 'trim'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrim()
	{
		self::assertEquals( $this->string, Trimmer::trim( $this->string ) );

		$assertion	= "abc...";
		$creation	= Trimmer::trim( $this->string, 6 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "abc---";
		$creation	= Trimmer::trim( $this->string, 6, '---' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "ÄÄ-";
		$creation	= Trimmer::trim( "ÄÄÖÖÜÜ", 3, '-' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		Trimmer::trimCentric( "not_relevant", 2 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		Trimmer::trimCentric( "not_relevant", 3 );
	}

	/**
	 *	Tests Exception of Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentricException3()
	{
		$this->expectException( 'InvalidArgumentException' );
		Trimmer::trimCentric( "not_relevant", 4, "1234" );
	}

	/**
	 *	Tests Method 'trimCentric'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTrimCentric()
	{
		self::assertEquals( $this->string, Trimmer::trimCentric( $this->string ) );

		$assertion	= "ab...p";
		$creation	= Trimmer::trimCentric( $this->string, 6 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "a...p";
		$creation	= Trimmer::trimCentric( $this->string, 5 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "ab...p";
		$creation	= Trimmer::trimCentric( $this->string, 6 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "ab---p";
		$creation	= Trimmer::trimCentric( $this->string, 6, '---' );
		self::assertEquals( $assertion, $creation );

		$assertion	= "ab...op";
		$creation	= Trimmer::trimCentric( $this->string, 7 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "Ä-Ü";
		$creation	= Trimmer::trimCentric( "ÄÄÖÖÜÜ", 3, '-' );
		self::assertEquals( $assertion, $creation );
	}

	public function testTrimLeft()
	{
		$assertion	= "abcdefghijklmnop";
		$creation	= Trimmer::trimLeft( $this->string, 60 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "...efghijklmnop";
		$creation	= Trimmer::trimLeft( $this->string, 15 );
		self::assertEquals( $assertion, $creation );

		$assertion	= "...nop";
		$creation	= Trimmer::trimLeft( $this->string, 6 );
		self::assertEquals( $assertion, $creation );
	}
}
