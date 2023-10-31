<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

use CeusMedia\Common\XML\RSS\Reader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of XML RSS Reader.
 *	@package		Tests.xml.rss
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	protected $file;
	protected $serial;
	protected $reader;
	protected $url		= 'http://www.rssboard.org/files/sample-rss-2.xml';

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->file		= dirname( __FILE__ )."/assets/reader.xml";
		$this->serial	= dirname( __FILE__ )."/assets/reader.serial";
		$this->reader	= new Reader();
	}

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'The cURL extension is not available.' );
		$rss		= $this->reader->readUrl( $this->url );

		$assertion	= "http://www.nasa.gov/";
		$creation	= $rss['channelData']['link'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 5;
		$creation	= count( $rss['itemList'] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "Louisiana Students to Hear from NASA Astronauts Aboard Space Station";
		$creation	= $rss['itemList'][0]['title'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= strlen( trim( $rss['itemList'][0]['description'] ) ) > 0;
		$this->assertEquals( $assertion, $creation );

		$assertion	= "http://www.nasa.gov/press-release/louisiana-students-to-hear-from-nasa-astronauts-aboard-space-station";
		$creation	= $rss['itemList'][0]['link'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'readFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFile()
	{
		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= $this->reader->readFile( $this->file );
#		file_put_contents( $this->serial, serialize( $creation ) );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'readXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadXml()
	{
		$xml		= file_get_contents( $this->file );

		$assertion	= unserialize( file_get_contents( $this->serial ) );
		$creation	= $this->reader->readXml( $xml );
		$this->assertEquals( $assertion, $creation );
	}
}
