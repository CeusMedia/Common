<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of FS_File_Block_Reader.
 *	@package		Tests.FS.File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Block;

use CeusMedia\Common\FS\File\Block\Reader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_Block_Reader.
 *	@package		Tests.FS.File.Block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
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
		$creation	= Reader::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlockNames'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlockNames()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getBlockNames();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlock()
	{
		$fileName	= sys_get_temp_dir().'/'.uniqid( 'BlockReaderTest' ).'.txt';
		file_put_contents( $fileName, "[first]\nline 1\nline 2\n\n[second]\nline 3\n" );
		$reader		= new Reader( $fileName );
		unlink( $fileName );

		self::assertSame( "line 1\nline 2", $reader->getBlock( 'first' ) );
		self::assertSame( 'line 3', $reader->getBlock( 'second' ) );
		self::assertSame( '', $reader->getBlock( 'not_existing' ) );
	}

	/**
	 *	Tests Method 'hasBlock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasBlock()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::hasBlock();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlocks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlocks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Reader::getBlocks();
		self::assertEquals( $assertion, $creation );
	}
}
