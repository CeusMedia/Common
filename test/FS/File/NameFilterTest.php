<?php
/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.File
 *	@extends		Test_Case
 *	@uses			FS_File_NameFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 */
class Test_FS_File_NameFilterTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->path	= dirname( __FILE__ );
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
	 *	Tests Exception of Method 'construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( 'RuntimeException' );
		$index	= new FS_File_NameFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "NameFilterTest.php";
		$filter	= new FS_File_NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( $search );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "not_existing_file";
		$filter	= new FS_File_NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
