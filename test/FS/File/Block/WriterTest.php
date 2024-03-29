<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_Block_Writer.
 *	@package		Tests.FS.File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Block;

use CeusMedia\Common\FS\File\Block\Writer;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Block_Writer.
 *	@package		Tests.FS.File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WriterTest extends BaseCase
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
		$creation	= Writer::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'writeBlocks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteBlocks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Writer::writeBlocks();
		$this->assertEquals( $assertion, $creation );
	}
}
