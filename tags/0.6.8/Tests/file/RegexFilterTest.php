<?php
/**
 *	TestUnit of File_NameFilter.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.RegexFilter' );
/**
 *	TestUnit of File_NameFilter.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_RegexFilter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.06.2008
 *	@version		0.1
 */
class Tests_File_RegexFilterTest extends PHPUnit_Framework_TestCase
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
		$index	= new File_RegexFilter( "not_existing", "@not_relevant@" );
	}

	/**
	 *	Tests Method 'accept'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAccept()
	{
		$search	= "@^RegexFilterTest@";
		$filter	= new File_RegexFilter( $this->path, $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "RegexFilterTest.php" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "@not_existing_file@";
		$filter	= new File_RegexFilter( $this->path, $search );

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
		$filter	= new File_RegexFilter( $this->path, $name, $incode );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array( "RegexFilterTest.php" );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$search	= "@".time()."@";
		$filter	= new File_RegexFilter( $this->path, "@\.php3$@", $search );

		$files	= array();
		foreach( $filter as $entry )
			$files[]	= $entry->getFilename();

		$assertion	= array();
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getNumberFound'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNumberFound()
	{
		$search	= "@^RegexFilterTest@";
		$filter	= new File_RegexFilter( $this->path, $search );

		foreach( $filter as $entry );
		$assertion	= 1;
		$creation	= $filter->getNumberFound();
		$this->assertEquals( $assertion, $creation );
		
		foreach( $filter as $entry );
		$assertion	= 1;
		$creation	= $filter->getNumberFound();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getNumberScanned'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetNumberScanned()
	{
		$search	= "@^RegexFilterTest@";
		$filter	= new File_RegexFilter( $this->path, $search );

		foreach( $filter as $entry );
		$creation	= $filter->getNumberScanned();
		$this->assertGreaterThan( 1, $creation );
		
		foreach( $filter as $entry );
		$assertion	= $creation;
		$creation	= $filter->getNumberScanned();
		$this->assertEquals( $assertion, $creation );
	}
}
?>