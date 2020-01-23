<?php
/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			25.10.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@extends		Test_Case
 *	@uses			XML_DOM_PEAR_PackageReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			25.10.2008
 *	@version		0.1
 */
class Test_XML_DOM_PEAR_PackageReaderTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__)."/";
		$this->reader	= new XML_DOM_PEAR_PackageReader();
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
