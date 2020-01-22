<?php
/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_NameFilter.
 *	@package		Tests.file
 *	@extends		Test_Case
 *	@uses			FS_File_RegexFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
class Test_FS_File_RegexFilterTest extends Test_Case
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
		$index	= new FS_File_RegexFilter( "not_existing", "@not_relevant@" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "@^RegexFilterTest@";
		$filter	= new FS_File_RegexFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "RegexFilterTest.php" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "@not_existing_file@";
		$filter	= new FS_File_RegexFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAcceptContent()
	{
		$name	= "@^RegexFilterTest@";
		$incode	= "@RegexFilterTest extends@";
		$filter	= new FS_File_RegexFilter( $this->path, $name, $incode );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "RegexFilterTest.php" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "@".time()."@";
		$filter	= new FS_File_RegexFilter( $this->path, "@\.php3$@", $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
