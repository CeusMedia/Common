<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_ICal_Parser.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\ICal;

use CeusMedia\Common\FS\File\ICal\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_ICal_Parser.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Parser::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Parser::parse();
		self::assertEquals( $assertion, $creation );
	}
}
