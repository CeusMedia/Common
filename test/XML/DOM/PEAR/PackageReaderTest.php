<?php
declare( strict_types = 1 );

/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			25.10.2008
 *
 */

namespace CeusMedia\Common\XML\DOM\PEAR;

use CeusMedia\CommonTest\BaseCase;
use  CeusMedia\Common\XML\DOM\PEAR\PackageReader;

/**
 *	TestUnit of XML_DOM_PEAR_PackageReader.
 *	@package		Tests....
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			25.10.2008
 *
 */
class PackageReaderTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__)."/";
		$this->reader	= new PackageReader();
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
