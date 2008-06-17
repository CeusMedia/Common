<?php
/**
 *	TestUnit of File_RecursiveNameFilter.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_RecursiveNameFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.RecursiveNameFilter' );
/**
 *	TestUnit of File_RecursiveNameFilter.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_RecursiveNameFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
class Tests_File_RecursiveNameFilterTest extends PHPUnit_Framework_TestCase
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
		file_put_contents( $this->path."test1.test", "test1" );
		file_put_contents( $this->path."test2.test", "test2" );
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
		$index	= new File_RecursiveNameFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "test1.test";
		$filter	= new File_RecursiveNameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( $search );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "not_existing_file";
		$filter	= new File_RecursiveNameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
?>