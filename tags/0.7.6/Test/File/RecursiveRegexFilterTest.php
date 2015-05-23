<?php
/**
 *	TestUnit of File_RecursiveRegexFilter.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_NameFilter.
 *	@package		Tests.file
 *	@extends		Test_Case
 *	@uses			File_RecursiveRegexFilter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
class Test_File_RecursiveRegexFilterTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/FilterTest/";
		$this->tearDown();
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		mkDir( $this->path );
		mkDir( $this->path."nested/" );
		file_put_contents( $this->path."test1.test", "test1" );
		file_put_contents( $this->path."test2.test", "test2" );
		file_put_contents( $this->path."nested/test3.test", "test3" );
		file_put_contents( $this->path."nested/test4.test", "test4" );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->path."test1.test" );
		@unlink( $this->path."test2.test" );
		@unlink( $this->path."nested/test3.test" );
		@unlink( $this->path."nested/test4.test" );
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
		$this->setExpectedException( 'RuntimeException' );
		$index	= new File_RecursiveRegexFilter( "not_existing", "@not_relevant@" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "@^test@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		sort( $files );
		$assertion	= array(
			"test1.test",
			"test2.test",
			"test3.test",
			"test4.test"
		);
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );


		$search	= "@^test1@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "test1.test" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );


		$search	= "@not_existing_file@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $search );

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
		$name	= "@^test@";
		$incode	= "@test2@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "test2.test" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );



		$incode	= "@test@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		sort( $files );
		$assertion	= array( "test1.test",  "test2.test",  "test3.test",  "test4.test" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );




		$incode	= "@test5@";
		$filter	= new File_RecursiveRegexFilter( $this->path, $name, $incode );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
