<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_RecursiveNameFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\RecursiveNameFilter;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_RecursiveNameFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RecursiveNameFilterTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/FilterTest/";
		$this->tearDown();
		mkDir( $this->path );
		mkDir( $this->path."nested/" );
		file_put_contents( $this->path."test1.test", "test1" );
		file_put_contents( $this->path."test2.test", "test2" );
		file_put_contents( $this->path."nested/test1.test", "test3" );
		file_put_contents( $this->path."nested/test2.test", "test4" );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->path."test1.test" );
		@unlink( $this->path."test2.test" );
		@unlink( $this->path."nested/test1.test" );
		@unlink( $this->path."nested/test2.test" );
		@rmDir( $this->path."nested/" );
		@rmDir( $this->path );
	}

	/**
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= new RecursiveNameFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "test1.test";
		$filter	= new RecursiveNameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( $search, $search );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "not_existing_file";
		$filter	= new RecursiveNameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
