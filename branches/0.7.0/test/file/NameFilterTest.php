<?php
/**
 *	TestUnit of File_NameFilter.
 *	@package		Tests.file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of File_NameFilter.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_NameFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
class Test_File_NameFilterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ );
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
		$this->setExpectedException( 'RuntimeException' );
		$index	= new File_NameFilter( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "NameFilterTest.php";
		$filter	= new File_NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( $search );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "not_existing_file";
		$filter	= new File_NameFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}
}
?>