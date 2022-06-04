<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File;

use CeusMedia\Common\FS\File\NameFilter;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class NameFilterTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ );
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
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= new NameFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "NameFilterTest.php";
		$filter	= new NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( $search );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "not_existing_file";
		$filter	= new NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
