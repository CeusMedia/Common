<?php
/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.10.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_PEAR_PackageReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			25.10.2008
 *	@version		0.1
 */
class Test_XML_DOM_PEAR_PackageReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__)."/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->reader	= new XML_DOM_PEAR_PackageReader();
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
	 *	Tests Method 'getPackageDataFromXmlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPackageDataFromXmlFile()
	{
		$assertion	= unserialize( file_get_contents( $this->path."package.serial" ) );
		$creation	= $this->reader->getPackageDataFromXmlFile( $this->path."package.xml" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>